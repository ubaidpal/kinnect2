<?php
namespace kinnect2Store\Store\Http;

use App\Events\SendEmail;
use App\Http\Controllers\Controller;
use App\Store;

use App\User;
use Auth;
use Config;
use Illuminate\Support\Facades\View;
use Input;
use kinnect2Store\Store\StoreOrder;
use kinnect2Store\Store\StoreOrderItems;
use kinnect2Store\Store\StoreOrderTransaction;
use kinnect2Store\Store\StoreProduct;
use kinnect2Store\Store\StoreDeliveryAddress;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use Redirect;
use Session;
use Symfony\Component\HttpFoundation\Request;
use URL;
use App\Classes\Worldpay;
use App\Classes\WorldpayException;
use Vinkla\Hashids\Facades\Hashids;

class PaymentController extends Controller
{
    protected $storeRepository;
    protected $storeOrderRepository;

    private $_api_context;

    public function __construct(
        \kinnect2Store\Store\Repository\StoreRepository $storeRepository,
        \kinnect2Store\Store\Repository\StoreOrderRepository $storeOrderRepository
    )
    {
        $this->storeRepository = $storeRepository;
        $this->storeOrderRepository = $storeOrderRepository;

// setup PayPal api context
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['ClientId'], $paypal_conf['ClientSecret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function pay(\Illuminate\Http\Request $request)
    {

        if($request->sellerBrandId != "buy-all"){
            $data['sellerBrandIdEncoded'] = $request->sellerBrandId;

            $data['sellerBrandId'] = Hashids::decode($request->sellerBrandId);
            $data['sellerBrandId'] = $data['sellerBrandId'][0];
            $data['cartProducts'][$data['sellerBrandId']]  = $this->storeRepository->getCartProducts($data['sellerBrandId']);
        }else{
            $data['sellerBrandIdEncoded'] = $request->sellerBrandId;

            $data['sellerBrandId'] = $request->sellerBrandId;
            $data['cartProducts']  = $this->storeRepository->getCartProducts();
        }

        $method = \Input::get('payment_type');

        $data['cartProductsCount'] = $this->storeRepository->getCartProductsCount();

        if(empty($data['cartProducts'])){
            return redirect()->to('store/cart/your-cart-is-empty');
        }
        $order_address_id = Session::get('cart.order_address');
        $data['address'] = $this->storeRepository->getDeliveryAddressByID($order_address_id);
        
        $data['totalShippingCost'] = $this->storeOrderRepository->getOrderTotalShippingCost($data['cartProducts'], $data['address']['country_id'], $data['sellerBrandId']);

        $data['method'] = $method;

        if ($method == 'paypal') {

            return view("Store::Cart.paypalPayment", $data);

        } elseif ($method == 'card') {
            //order ids
            return view("Store::Cart.cardPayment", $data);

        } else {

            $data['cartProducts'] = Session::get('cart.products');

            $data['address'] = Session::get('cart.order_address');
            $data['address'] = end($data['address']);

            return view("Store::Cart.reviewOrder", $data)->with('payment_type_error', '1');
        }
    }

    public function makePayment(\Illuminate\Http\Request $request)
    {
        if($request->sellerBrandId != "buy-all"){
            $data['sellerBrandIdEncoded'] = $request->sellerBrandId;

            $data['sellerBrandId'] = Hashids::decode($request->sellerBrandId);
            $data['sellerBrandId'] = $data['sellerBrandId'][0];
        }else{
            $data['sellerBrandIdEncoded'] = $request->sellerBrandId;

            $data['sellerBrandId'] = $request->sellerBrandId;
        }

        $service_key = \Config::get('constants_brandstore.WORLDPAY_SERVICE_KEY');

        $world_pay = new Worldpay($service_key);

        $inputTokenWorldPay = \Input::get('token');
        $inputNameWorldPay  = \Input::get('name');

        $method = \Input::get('method');
        $data['method'] = $method;

        $address_id = $this->storeRepository->getCartDeliverAddress();
        $address = $this->storeRepository->getDeliveryAddressByID($address_id);
        $cartItems = $this->storeRepository->getCartProducts($data['sellerBrandId']);
        if(empty($cartItems)){
            return redirect()->to('store/cart/your-cart-is-empty');
        }

        $orderTotalPayablePrice = $this->storeRepository->getOrderTotal($data['sellerBrandId']);

        try {

            $billing_address = array(
                "address1" => $address->st_address_1,
                "address2" => $address->st_address_2,
                "postalCode" => $address->zip_code,
                "city" => $address->city,
                "state" => $address->state,
            );

            $response = $world_pay->createOrder(array(
                'token' => $inputTokenWorldPay,
                'amount' => round($orderTotalPayablePrice,2) * 100,
                'currencyCode' => 'USD',
                'name' => $inputNameWorldPay,
                'billingAddress' => $billing_address,
                'orderDescription' => 'Order from user:' . Auth::user()->displayname . ' received.',
                'customerOrderCode' => ''
            ));

            if ($response['paymentStatus'] === 'SUCCESS') {

                $order_ids = $this->storeRepository->storeOrder($data['sellerBrandId'],Auth::user()->id);

                foreach ($order_ids as $orderId) {
                    $order = StoreOrder::where('id',$orderId)->first();
                    $order_total = str_replace(',','',$order->total_price) - str_replace(',','',$order->total_discount);
                    $this->saveTransaction($response, $order->id, $order_total);

                    $orderStatusHtml = '';

                    $orderItems = StoreOrderItems::where('order_id', $order->id)->get();

                    $data['purchasedProducts'] = [];
                    $data['totalShippingCost'] = 0;
                    $country_id = $this->storeRepository->getAddressFieldByID($order->delivery_address_id,'country_id');
                    foreach ($orderItems as $orderItem) {

                        $productShippingCost = $this->storeOrderRepository->getProductRegionShippingCost($country_id, $orderItem->product_id);
                        $orderProduct = StoreProduct::where('id',$orderItem->product_id)->select(['title'])->first();
                        $data['purchasedProducts'][$orderItem->product_id] = [
                            'productShippingCost' => $productShippingCost,
                            'productTitle' => $orderProduct->title,
                            'productDiscount' => $orderItem->product_discount,
                            'productQuantity' => $orderItem->quantity,
                            'productPrice' => $orderItem->product_price,
                        ];

                        $data['totalShippingCost'] = $data['totalShippingCost'] + ($productShippingCost * $orderItem->quantity);
                    }

                    //<editor-fold desc="sending email to customer">
                    $emailData = array(
                        'subject' => 'Order Placed successfully.',
                        'message' => 'Following is detail of your order (ID: ' . $order->order_number . ') ' . $orderStatusHtml,
                        'from' => \Config::get('admin_constants.ORDER_STATUS_EMAIL'),
                        'name' => 'Kinnect2 Admin',
                        'template' => 'orderStatus',

                        'orderCreated_at' => $order->created_at,
                        'orderTotalPrice' => str_replace(',','',$order->total_price) - str_replace(',','',$order->total_discount),
                        'orderTotalShippingCost' => str_replace(',','',$order->total_shiping_cost),
                        'orderSellerId' => $order->seller_id,
                        'orderBuyerId' => $order->customer_id,
                        'isSeller' => 0,
                        'orderOrderNumber' => $order->order_number,

                        'orderProductsInfo' => $data['purchasedProducts'],
                        'totalShippingCost' => $data['totalShippingCost'],
                        'billingAddress' => $address,
                        'to' => $this->storeRepository->getAddressFieldByID($order->delivery_address_id,'email'),
                    );

                    \Event::fire(new SendEmail($emailData));
                    // </editor-fold>

                    //<editor-fold desc="sending email to seller">
                    $sellerEmailAddress = $this->storeOrderRepository->sellerEmail($order->seller_id);

                    if($sellerEmailAddress != false){
                        $emailData = array(
                            'subject' => 'Customer Placed New Order.',
                            'message' => 'Detail of order (ID: ' . $order->order_number . ') ' . $orderStatusHtml,
                            'from' => \Config::get('admin_constants.ORDER_STATUS_EMAIL'),
                            'name' => 'Kinnect2 Admin',
                            'template' => 'orderStatus',

                            'orderCreated_at' => $order->created_at,
                            'orderTotalPrice' => $order->total_price,
                            'orderTotalShippingCost' => $order->total_shiping_cost,
                            'orderSellerId' => $order->seller_id,
                            'orderBuyerId' => $order->customer_id,
                            'isSeller' => 1,
                            'orderOrderNumber' => $order->order_number,

                            'orderProductsInfo' => $data['purchasedProducts'],
                            'totalShippingCost' => $data['totalShippingCost'],
                            'billingAddress' => $address,
                            'to' => $sellerEmailAddress,
                        );

                        \Event::fire(new SendEmail($emailData));
                    }
                    // </editor-fold>

                    //<editor-fold desc="sending email to kinnect2">
                    $sellerEmailAddress = $this->storeOrderRepository->sellerEmail($order->seller_id);

                    if($sellerEmailAddress != false){
                        $emailData = array(
                            'subject' => 'Customer Placed New Order.',
                            'message' => 'Detail of order (ID: ' . $order->order_number . ') ' . $orderStatusHtml,
                            'from' => \Config::get('admin_constants.ORDER_STATUS_EMAIL'),
                            'name' => 'Kinnect2 Admin',
                            'template' => 'orderStatus',

                            'orderCreated_at' => $order->created_at,
                            'orderTotalPrice' => $order->total_price,
                            'orderTotalShippingCost' => $order->total_shiping_cost,
                            'orderSellerId' => $order->seller_id,
                            'orderBuyerId' => $order->customer_id,
                            'isSeller' => 0,
                            'orderOrderNumber' => $order->order_number,

                            'orderProductsInfo' => $data['purchasedProducts'],
                            'totalShippingCost' => $data['totalShippingCost'],
                            'billingAddress' => $address,
                            'to' => 'stores.manager@kinnect2.com',
                        );

                        \Event::fire(new SendEmail($emailData));
                    }
                }
            } else {
                throw new WorldpayException(print_r($response, true));
            }

        } catch (WorldpayException $e) {
            $data['e'] = $e;
            $carProducts = $this->storeRepository->getCartProducts();
            $data['totalShippingCost'] = $data['totalShippingCost'] = $this->storeOrderRepository->getOrderTotalShippingCost($carProducts, $address->country_id, $data['sellerBrandId']);;
            $data['method'] = $method;
            $data['cartProducts'] = $this->storeRepository->getCartProducts();
            $data['cartProductsCount'] = $this->storeRepository->getCartProductsCount();
            $data['address'] = $this->storeRepository->getDeliveryAddressByID($address_id);

            return view("Store::Cart.cardPayment", $data);
        }
        catch (Exception $e) {
            echo 'Error message: ' . $e->getMessage();
        }
        $order_numbers = StoreOrder::whereIn('id',$order_ids)->select(['id','order_number','seller_id'])->get();
        foreach ($order_numbers as $order_number){
            $order_number->seller = User::where('id',$order_number->seller_id)->select('displayname')->first();
        }
        return redirect('store/order/completed/'.Hashids::encode($order->id))->with('order_numbers',$order_numbers);
    }

    protected function saveTransaction($respone, $order_id, $orderPrice)
    {

        $orderTransaction = new StoreOrderTransaction();

        $orderTransaction->user_id = Auth::user()->id;
        $orderTransaction->order_id = $order_id;
        $orderTransaction->gateway_id = 2;
        $orderTransaction->type = $respone['paymentResponse']['type'];
        $orderTransaction->state = $respone['paymentStatus'];
        $orderTransaction->gateway_transaction_id = $respone['orderCode'];
        $orderTransaction->gateway_parent_transaction_id = 0;
        $orderTransaction->gateway_order_id = $respone['orderCode'];
        $orderTransaction->amount = $orderPrice;
        $orderTransaction->total_amount = round($respone['amount'] / 100,2);
        $orderTransaction->currency = $respone['currencyCode'];
        $orderTransaction->response_object = serialize($respone);

        if ($orderTransaction->save()) {
            $order = StoreOrder::where('id', $order_id)->first();
            $order->status = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_PAYMENT_VERIFIED');
            $order->save();

            $products = StoreOrderItems::where('order_id', $order_id)
                ->select(['id', 'order_id', 'quantity', 'product_id'])
                ->get();

            foreach ($products as $item) {
                $store_product = StoreProduct::where('id', $item->product_id)
                    ->select(['id', 'sold', 'quantity'])
                    ->first();

                if (!empty($store_product->id)) {
                    $store_product->sold = (INT)$store_product->sold + (INT)$item->quantity;
                    $store_product->quantity = (INT)$store_product->quantity - (INT)$item->quantity;
                    $store_product->save();
                }
            }
        }
    }

    public function postPayment($order_id)
    {
        $order = StoreOrder::find($order_id);
        $orderItems = StoreOrderItems::where('order_id', $order_id)->get();
        if (!isset($order->id)) {
            return redirect()->back()->with('info', 'order not found');
        }

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $items = '';
        $i = 0;
        foreach ($orderItems as $unpaidItem) {
            $productInfo = StoreProduct::find($unpaidItem->product_id);
            $items[$i] = new Item();
//            $items[$i]->setQuantity($unpaidItem->quantity)->setName($productInfo->title)->setPrice($unpaidItem->product_price)->setCurrency('USD');
            $items[$i]->setName($productInfo->title)// item name
            ->setCurrency('USD')
                ->setQuantity($unpaidItem->quantity)
                ->setPrice($unpaidItem->product_price); // unit price
            $i++;
        }

//        $item_1 = new Item();
//        $item_1->setName('Product order') // item name
//        ->setCurrency('USD')
//            ->setQuantity($order->total_quantity)
//            ->setPrice($order->total_price); // unit price

        if (!is_array($items)) {
            return redirect('/store/cart')->with('info', 'no product found in your order');
        }
        // add item to list
        $item_list = new ItemList();
        $item_list->setItems($items);

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($order->total_price);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription("Paying amount for Order: ");

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(url('order/payment/status/order_id=' . $order->id))// Specify return URL
        ->setCancelUrl(url('order/payment/status/order_id=' . $order->id));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                $err_data = json_decode($ex->getData(), true);
                exit;
            } else {
                die('Some error occur, sorry for inconvenient');
            }
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        // add payment ID to session
        Session::put('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)) {
            // redirect to paypal
            return Redirect::away($redirect_url);
        }

        return Redirect::route('original.route')
            ->with('error', 'Unknown error occurred');
    }

    public function getPaymentStatus($order_id)
    {
        $order_id = explode('=', $order_id);

        $order = StoreOrder::where('id', $order_id[1])->first();
        // Get the payment ID before session clear
        $payment_id = Session::get('paypal_payment_id');
        // clear the session payment ID
        Session::forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            return Redirect::route('original.route')
                ->with('error', 'Payment failed');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        // PaymentExecution object includes information necessary
        // to execute a PayPal account payment.
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));
        //Execute the payment
        $result = $payment->execute($execution, $this->_api_context);

        //Save transaction for order
        $orderTransaction = new StoreOrderTransaction();

        $orderTransaction->user_id = Auth::user()->id;
        $orderTransaction->order_id = $order->id;
        $orderTransaction->gateway_id = '1';
        $orderTransaction->gateway_timestamp = $payment->transactions[0]->related_resources[0]->sale->create_time;
        $orderTransaction->type = $payment->transactions[0]->related_resources[0]->sale->payment_mode;
        $orderTransaction->state = $result->getState();
        $orderTransaction->gateway_transaction_id = $payment->id;
        $orderTransaction->gateway_parent_transaction_id = $payment->transactions[0]->related_resources[0]->sale->parent_payment;
        $orderTransaction->gateway_order_id = $payment->id;
        $orderTransaction->amount = $payment->transactions[0]->amount->total;
        $orderTransaction->currency = $payment->transactions[0]->amount->currency;;

        $orderTransaction->save();

//        return $payment->id. ' > '. $payment->transactions[0]->related_resources[0]->sale->parent_payment;
//        $payment->transactions[0]->amount->currency;//currency
//        $payment->transactions[0]->amount->total;//total_amount
//        $payment->transactions[0]->related_resources[0]->sale->payment_mode;//type
//        $payment->transactions[0]->related_resources[0]->sale->create_time;//timestamp
//        echo '<pre>';print_r($result);echo '</pre>';exit; // DEBUG RESULT, remove it later

        if ($result->getState() == 'approved') { // payment made
            return redirect('store/order/completed/' . $order->id)->with('info', 'Payment success');
        }
        return redirect('store/order/completed/' . $order->id)->with('info', 'Payment failed');
    }
}
