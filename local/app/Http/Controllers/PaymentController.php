<?php
namespace App\Http\Controllers;

use App\Ad;
use App\AdTransaction;
use App\AdUserAd;
use Config;
use Input;
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

class PaymentController extends Controller
{

    private $_api_context;
    public function __construct()
    {

// setup PayPal api context
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['ClientId'], $paypal_conf['ClientSecret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function postPayment()
    {
        $ad_id = Input::get('ad_id');
        $package_id = Input::get('pkg_id');
        if($ad_id < 1 || $package_id < 1)
        {
            return redirect('ads/my-campaigns/error_paypal');
        }

        $ad      = AdUserAd::find($ad_id);
        $package = Ad::find($package_id);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();
        $item_1->setName($ad->cads_title) // item name
        ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($package->price); // unit price

        // add item to list
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($package->price);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription("Paying amount for Ad: ".$ad->cads_title);

        $redirect_urls = new RedirectUrls();

        $redirect_urls->setReturnUrl(url('payment/status/'.$ad->id)) // Specify return URL on which paypal response will go
        ->setCancelUrl(url('ads/manage/ad/'.$ad->id.'/payment-not-paid'));

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

        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        // add payment ID to session
        Session::put('paypal_payment_id', $payment->getId());

        if(isset($redirect_url)) {
            // redirect to paypal
            return Redirect::away($redirect_url);
        }

        return Redirect::route('original.route')
            ->with('error', 'Unknown error occurred');
    }

    public function getPaymentStatus($ad_id)
    {
        $package_id = $ad_id;

        if($ad_id < 1 || $package_id < 1)
        {
            return redirect('ads/my-campaigns/error_paypal');
        }

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

        $adTransaction = new AdTransaction();

        $adTransaction->user_id                         = \Auth::user()->id;
        $adTransaction->ad_id                           = $ad_id;
        $adTransaction->gateway_id                      = '1';
        $adTransaction->timestamp                       = $payment->transactions[0]->related_resources[0]->sale->create_time;
        $adTransaction->type                            = $payment->transactions[0]->related_resources[0]->sale->payment_mode;
        $adTransaction->state                           = $result->getState();
        $adTransaction->gateway_transaction_id          = $payment->id;
        $adTransaction->gateway_parent_transaction_id   = $payment->transactions[0]->related_resources[0]->sale->parent_payment;
        $adTransaction->gateway_order_id                = $payment->id;
        $adTransaction->amount                          = $payment->transactions[0]->amount->total;
        $adTransaction->currency                        = $payment->transactions[0]->amount->currency;

        $adTransaction->save();

//        return $payment->id. ' > '. $payment->transactions[0]->related_resources[0]->sale->parent_payment;
//        $payment->transactions[0]->amount->currency;//currency
//        $payment->transactions[0]->amount->total;//total_amount
//        $payment->transactions[0]->related_resources[0]->sale->payment_mode;//type
//        $payment->transactions[0]->related_resources[0]->sale->create_time;//timestamp
//        echo '<pre>';print_r($result);echo '</pre>';exit; // DEBUG RESULT, remove it later
        $ad      = AdUserAd::find($ad_id);
        $package = Ad::find($package_id);

        if ($result->getState() == 'approved') { // payment made

            $ad->enable         = 1;
            $ad->payment_status = 1;

            $ad->save();

            return redirect('/ads/manage/campaign/'.$ad->campaign_id)->with('success', 'Payment success');
        }
        return redirect('/ads/manage/campaign/'.$ad->campaign_id)->with('error', 'Payment failed');
    }
}
