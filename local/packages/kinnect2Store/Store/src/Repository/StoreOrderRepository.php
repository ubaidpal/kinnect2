<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 1/6/2016
 * Time: 9:31 PM
 */
namespace kinnect2Store\Store\Repository;

use kinnect2Store\Store\StoreDeliveryAddress;
use kinnect2Store\Store\StoreOrder;
use kinnect2Store\Store\StoreOrderDispute;
use kinnect2Store\Store\StoreOrderItems;
use kinnect2Store\Store\StoreOrderTransaction;
use kinnect2Store\Store\StoreProductReview;
use kinnect2Store\Store\StoreShippingCost;
use kinnect2Store\Store\StoreShippingCountry;
use LucaDegasperi\OAuth2Server\Authorizer;
use kinnect2Store\Store\StoreProduct;
use App\Facades\UrlFilter;
use App\StorageFile;
use App\AlbumPhoto;
use Carbon\Carbon;
use App\Album;
use App\User;
use Session;
use Auth;
use DB;


class StoreOrderRepository
{
    protected $store;

    protected $data;
    protected $user_id;
    protected $is_api;

    /**
     *
     */
    public function __construct() {

        $this->is_api = UrlFilter::filter();
        if ($this->is_api) {
            $this->user_id = Authorizer::getResourceOwnerId();
            @$this->data->user = User::findOrNew($this->user_id);
        } else {
            if (Auth::check()) {
                @$this->data->user = Auth::user();
                $this->user_id = $this->data->user->id;
            }
        }

    }

    // ==================== Ubaid code ============================

    /**
     * @return int
     */
    public function getAllOrdersCurrentUser() {
        return $allOrders = StoreOrder::where('is_deleted', 0)->where('customer_id', $this->user_id)->with('delivery')->orderBy('id', 'DESC')->get();
    }
    public function paginateUserOrders($user_id,$status = null){

        $query = StoreOrder::where('customer_id', $this->user_id)->orderBy('id', 'DESC');
        if($status != null){
            $query->whereIn('status',$status);
        }
        
        return $query->paginate(10);
    }
    public function getOrderDeliveryInfo($id) {
        return $allOrders = DB::table('store_order_delivery_info')->where('order_id', $id)->first();
    }


    public function getOrder($id) {
        return StoreOrder::find($id);
    }
    public function countRequestToReviseCurrentUser() {
        $productReviewCount = 0;

        $orderIds = StoreOrder::where('status', \Config::get("constants_brandstore.ORDER_STATUS.ORDER_DELIVERED"))->where('customer_id', $this->user_id)->lists('id');

        $orderProductsIds = StoreOrderItems::whereIn('order_id', $orderIds)->groupBy("product_id")->lists('product_id');

        foreach($orderProductsIds as $orderProductsId){
            $review = StoreProductReview::where('product_id', $orderProductsId)->where('owner_id', $this->user_id)->first();
            if(isset($review->id)){
                if($review->is_revised == 0 AND $review->is_revise_request == 1){
                    $productReviewCount++;
                }
            }else{
                $productReviewCount++;
            }
        }

        return $productReviewCount;
    }

    public function getOrderAllProducts($order_id = 0) {
        $orderAllProductsIds = StoreOrderItems::where('order_id', $order_id)->lists('product_id');

        return $orderAllProducts = StoreProduct::whereIn('id', $orderAllProductsIds)->get();
    }
    public function getOrderAllProductsByKey($order_id = 0) {
        $orderAllProductsIds = StoreOrderItems::where('order_id', $order_id)->lists('product_id');

        return $orderAllProducts = StoreProduct::whereIn('id', $orderAllProductsIds)->get()->keyBy('id');
    }
    public function paymentReceivedInfo() {
        return $allOrders = StoreOrderTransaction::where('user_id', $this->user_id)->get();
    }

    public function paymentReceivedInfoSingle($order_id) {
        return $allOrders = StoreOrderTransaction::where('order_id', $order_id)->first();
    }

    public function checkProductShippingCountry($product_ids, $country_id, $sub_total = 0) {
        $data['allowedProducts']    = [];
        $data['notAllowedProducts'] = [];
        $data['totalShippingCost']  = 0;
        $sub_total                  = !empty($sub_total) ? str_replace(',', '', $sub_total) : 0;
        $data['sub_total']          = $sub_total;

        foreach ($product_ids as $product_id) {
            $isAllow = StoreShippingCountry::select('region_id')
                ->where('country_id', $country_id)
                ->where('product_id', $product_id)
                ->first();

            if (isset($isAllow->region_id)) {
                $totalShippingCost       = StoreShippingCost::select('shipping_cost')->where('region_id', $isAllow->region_id)->where('product_id', $product_id)->first();
                $data['allowedProducts'] = $data['allowedProducts'] + [$product_id];

                if (isset($totalShippingCost->shipping_cost)) {
                    $productInf = StoreProduct::where('id',$product_id)->select(['owner_id'])->first();
                    $quantity = Session::get('cart.products.'.$productInf->owner_id.'.'.$product_id.'.quantity');
                    $data['totalShippingCost'] = $data['totalShippingCost'] + ($totalShippingCost->shipping_cost * $quantity);
                }
            } else {
                $data['notAllowedProducts'] = $data['notAllowedProducts'] + [$product_id];
            }
        }

        if (!empty($data['totalShippingCost'])) {
            $data['totalShippingCost'] = format_currency($data['totalShippingCost']);
            $data['grand_total']       = format_currency($sub_total + $data['totalShippingCost']);
        } else {
            $data['grand_total'] = $sub_total;
        }

        return $data;
    }

    public function getProductRegionShippingCost($country_id, $product_id) {
        // Calculate shipping cost every time, whenever it is needed.
        $region_id = getRegionId($country_id);

        $regionCostInfo = getRegionCostByProductId($region_id, $product_id);

        if (isset($regionCostInfo->shipping_cost)) {
            return $regionCostInfo->shipping_cost;
        }

        return 0;
    }

    public function getOrderTotalShippingCost($allOrderProductsIds, $country_id, $sellerBrandId = 0) {
        // Calculate shipping cost every time, whenever it is needed.
        $region_id = getRegionId($country_id);

        $totalShippingCost = 0;

        foreach ($allOrderProductsIds as $brand => $products) {
            foreach ($products as $orderProduct) {
                $productInfo = getProductDetailsByID($orderProduct['product_id']);

                if ($sellerBrandId > 0) {
                    if ($productInfo->owner_id != $sellerBrandId) {
                        continue;
                    }
                }

                $regionCostInfo = getRegionCostByProductId($region_id, $orderProduct['product_id']);

                if (isset($regionCostInfo->shipping_cost)) {
                    $totalShippingCost = $totalShippingCost + ($regionCostInfo->shipping_cost * $orderProduct['quantity']);
                }
            }
        }

        return $totalShippingCost;
    }

    public function countOrdersStatusWise($customer_id)
    {
        $data = [];
        $order_status = \Config::get( 'constants_brandstore.ORDER_STATUS');
        $data['All'] = StoreOrder::where('customer_id',$customer_id)->where('is_deleted',0)->count();
        foreach ($order_status as $key => $value){
            if($key == 'ORDER_AWAITING_PAYMENT' || $key == 'ORDER_PAYMENT_BEING_VERIFIED'){
                continue;
            }
            if($key == 'ORDER_DISPUTED' || $key == 'ORDER_DISPUTED_CANCELLED' || $key == 'ORDER_DISPUTED_REJECTED' || $key == 'ORDER_DISPUTE_ACCEPTED'){
                if(isset($data['ORDER_DISPUTED'])){
                    $data['ORDER_DISPUTED'] += StoreOrder::where('status', $value)->where('customer_id', $customer_id)->where('is_deleted', 0)->count();
                }else {
                    $data['ORDER_DISPUTED'] = StoreOrder::where('status', $value)->where('customer_id', $customer_id)->where('is_deleted', 0)->count();
                }
            }else {
                $data[$key] = StoreOrder::where('status', $value)->where('customer_id', $customer_id)->where('is_deleted', 0)->count();
            }
        }

        return $data;
    }

    public function sellerEmail($ordeSellerId) {
        $user = User::select('email')->where('id', $ordeSellerId)->first();

        if (isset($user->email)) {
            return $user->email;
        }

        return FALSE;
    }

    public function searchMyOrders($order_number, $product_name) {
        $allOrders = \DB::table('store_orders')
            ->select('store_orders.id', 'order_number', 'store_orders.customer_id', 'store_orders.seller_id', 'delivery_address_id', 'payment_type', 'status', 'is_deleted', 'total_price', 'total_shiping_cost', 'total_discount', 'total_quantity', 'store_orders.created_at', 'approved_date', 'shiping_date', 'received_date')
            ->join('store_order_items', 'store_order_items.order_id', '=', 'store_orders.id')
            ->join('store_products', 'store_products.id', '=', 'store_order_items.product_id')
            ->where('store_orders.order_number', 'like', $order_number . '%')
            ->where('store_orders.is_deleted', '!=', 1)
            ->where('store_orders.customer_id', '=', $this->user_id)
            ->where('store_products.title', 'like', "%".$product_name . '%')
            ->get();
        $html      = '';

        foreach ($allOrders as $order):
            $html .='<div class="orderb-item orderBox order_item_'.$order->id.'">
                        <div class="product-header ph-pset">';
                              $orderAllProducts = getOrderAllProducts($order->id);
            $html .='<div class="oi-header">
                <div class="oi-image">';
                   foreach($orderAllProducts as $orderProduct):
                        $product = getProductDetailsByID($orderProduct->product_id);//Complete detail of product
                    if (!isset($product->id)) {
                        continue;
                    }

                       $html .='<div class="oi-product">
                                    <div class="oi-product-item pdt"><!-- oi-title -->
                        <a class="product-img" href="'.getProductUrlByIdAndOwnerId($orderProduct->product_id, $product->owner_id).'">';
                       $discountedPercented = ($orderProduct->product_price / 100 ) * $orderProduct->product_discount;
                       $oi_price = format_currency($orderProduct->product_price - $discountedPercented);

                            $imageThumb = getThumbSrcWithProductId($product->id, 'product_thumb');
                       $html .='<img class="product-image" width="100" height="100" src="'.$imageThumb.'"
                                 alt="IMAGE"></a>

                        <span>'.$product->title.'</span>
                        </div>
                        <div class="oi-product-item">
                            &dollar;'.format_currency($orderProduct->product_price).'
                        </div>
                        <div class="oi-product-item">
                            &dollar;'.format_currency($discountedPercented).'
                        </div>
                        <div class="oi-product-item order_product_qty_'.$order->id.'">'.$orderProduct->quantity.'</div>';

                       $html .='<div class="oi-product-item opi-txtb">$'.format_currency(($orderProduct->product_price - $discountedPercented) * $orderProduct->quantity).'</div>
                    </div>';
                    endforeach;

                $html .='</div>

            </div>
            <div class="oi-footer">
                <div class="oi-detail">
                    <p class="mb5">';
                        $storeName = getUserNameByUserId($product->owner_id);
            $html .='Order ID: '.$order->order_number.' <a
                            href="'.url('order-invoice/'.$order->id).'">View
                            Detail</a>
                    </p>

                    <p>
                        Order time & date: '.$order->created_at.'
                    </p>
                </div>
                <div class="oi-profile w230x">';
                    if(isset($product->owner_id)):
                        $html .=' <p class="mb5">
                        Store Name: <a target="_blank" href="'.url('store/'.$storeName).'">'.$storeName = ucfirst($storeName);
                        $html .='</a>
                    </p>';
                    endif;
                $html .= '</div>';
                $productPrice = $order->total_price - $order->total_discount;
                $html .= '<div class="oi-amount-container">
                            <div class="oi-ship-cost">
                                <div class="oi-sc-txt">Shipping Cost:</div>
                                <div class="oi-sc-value">$'.format_currency($order->total_shiping_cost).'</div>
                            </div>
                            <div class="oi-amount-total">
                                <div class="oi-amount-t-txt">Order Amount:</div>
                                <div class="oi-amount-t-value">&dollar;'.format_currency($productPrice).'</div>
                            </div>
                        </div>
            </div>
            </div>
            <div class="clrfix"></div>

            <div id="orderStatusWrap" class="orderStatusWrap">';
                if (!isset($product->id)) {
                    $data['class'] = '';
                    $data['action_btn_1'] = '';
                    $data['action_btn_2'] = '';
                    $data['status'] = 'Product Deleted';
                    $productPrice = '';
                } else {
                    $data         = getOrderStatusForBuyer($order->id, $order->status, $order);

                }
                $html .='<div class="oi-action order_action_'.$order->id.' '.$data['class'].'">
                     '.$data['action_btn_1']. $data['action_btn_2'].'
                </div>
                <div class="oi-status order_status_'.$order->id.'"><span>Order Status</span>'.$data['status'].'</div>
            </div>
            </div>';
        endforeach;

        return $html;
    }

    public function searchMyReviews($order_number, $product_name) {
        $allOrders = \DB::table('store_orders')
            ->select('store_orders.id', 'order_number', 'store_orders.customer_id', 'store_orders.seller_id', 'delivery_address_id', 'payment_type', 'status', 'is_deleted', 'total_price', 'total_shiping_cost', 'total_discount', 'total_quantity', 'store_orders.created_at', 'approved_date', 'shiping_date', 'received_date')
            ->join('store_order_items', 'store_order_items.order_id', '=', 'store_orders.id')
            ->join('store_products', 'store_products.id', '=', 'store_order_items.product_id')
            ->where('store_orders.order_number', 'like', $order_number . '%')
            ->where('store_orders.customer_id', '=', $this->user_id)
            ->where('store_orders.is_deleted', '!=', 1)
            ->where('store_orders.status', '=', \Config::get("constants_brandstore.ORDER_STATUS.ORDER_DELIVERED"))
            ->where('store_products.title', 'like', $product_name . '%')
            ->get();
        $html      = '';

        if (count($allOrders) == 0) {
            return $html = '<h1 style="margin-top:20px;" id="nothing_found">Nothing found try again.</h1>';
        }

        foreach ($allOrders as $order):
            $html .= '<div class="orderb-item order_item_{{$order->id}}">
                ';
            $orderAllProducts = getOrderAllProducts($order->id);
            $orderBuyer       = getUserDetail($order->customer_id);
            $html .= '
<div class="oi-header">
	<div class="oi-image">';
            foreach ($orderAllProducts as $orderProduct):
                $product = getProductDetailsByID($orderProduct->product_id);//Complete detail of product

                $review    = getRatingOfUserById($order->customer_id, $product->id);
                $storeName = getUserNameByUserId($product->owner_id);

                $html .= '<div class="oi-product">
			<a class="product-img" href="' . getProductUrlByIdAndOwnerId($orderProduct->product_id, $product->owner_id) . '">';
                $imageThumb = getThumbSrcWithProductId($product->id, 'product_thumb');
                $html .= '<img width="100" height="100" src="' . $imageThumb . '" alt="IMAGE"></a>
			<div class="oi-title">' . $product->title . '</div>
		</div>';
            endforeach;

            if (!isset($product->id)) {
                $data['class']        = '';
                $data['action_btn_1'] = '';
                $data['action_btn_2'] = '';
                $data['status']       = 'Product Deleted';
                $productPrice         = '';
            } else {
                $data         = getReviewStatusForBuyer($review, $orderBuyer, $storeName, $order->id, $product->id);
                $productPrice = "$" . $order->total_price;
            }

            $html .= '</div>
	<div class="oi-action order_action_' . $order->id . $data['class'] . '">' .
                $data['action_btn_1'] . $data['action_btn_2'] . '
	</div>
	<div class="oi-status order_status_' . $order->id . '">' . $data['status'] . '</div>
	<div class="oi-amount"><p class="oi-price">' . $productPrice . '</p></div>
</div>

<div class="oi-footer">
	<div class="oi-detail">
		<p class="mb5">
			Order ID: ' . $order->order_number . ' <a href="' . url('order-invoice/' . $order->id) . '">View
				Detail</a>
		</p>
		<p>
			Order time & date: ' . $order->created_at . '
		</p>
	</div>
	<div class="oi-profile">
		<p class="mb5">
			';
            if (isset($product->owner_id)) {

                $html .= 'Store Name: ';

                $html .= '<a
					target="_blank"
					href="';
                url('store/' . $storeName);
                $html .= '"> ' . $storeName = ucfirst($storeName) . '</a>
		</p>

		<p>
			<a href="' . url('brand/' . $storeName) . '">View Profile</a>
		</p>
		';
                if (isset($order->delivery)) {
                    $html .= '<b class="mb5">
			Time Remaining: ' . date_difference_human($order->delivery->date_to_be_delivered) . '
		</b>';
                }
            }
            $html .= '</div>
</div>
</div>';
        endforeach;

        return $html;
    }
    //======= End of Zahid Code ===============
}
