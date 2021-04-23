<?php


use App\AlbumPhoto;
use App\StorageFile;
use App\User;
use Carbon\Carbon;
use kinnect2Store\Store\Category;
use kinnect2Store\Store\StoreAlbumPhotos;
use kinnect2Store\Store\StoreOrder;
use kinnect2Store\Store\StoreOrderItems;
use kinnect2Store\Store\StoreProduct;
use kinnect2Store\Store\StoreProductReview;
use kinnect2Store\Store\StoreShippingCost;
use kinnect2Store\Store\StoreShippingCountry;
use kinnect2Store\Store\StoreShippingRegion;
use kinnect2Store\Store\StoreStorageFiles;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 1/15/2016
 * Time: 4:12 PM
 */

//=======================Zahid code ============================

function getStatusForBuyerOrderById($status_id){
	$data['status']       = '';
	$data['reminder']     = '';

	switch ( $status_id ) {
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_CANCELED" ):
			$data['status']       = 'Order Canceled';
			$data['reminder']       = 'Order Canceled by you or either by seller.';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_AWAITING_PAYMENT" ):
			$data['status']       = 'Awaiting for Payment';
			$data['reminder']       = 'You should pay for further processing.';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_PAYMENT_BEING_VERIFIED" ):
			$data['status']       = 'Payment to be verified';
			$data['reminder']       = 'Kinnect2 is verifying your payment.';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_PAYMENT_VERIFIED" ):
			$data['status']       = 'Payment approved';
			$data['reminder']       = 'Waiting for Seller to approve your order.';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_AWAITING_SHIPMENT" ):
			$data['status']       = 'Awaiting Shipment';
			$data['reminder']       = 'Order is approved, waiting to be dispatched by seller.';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPATCHED" ):
			$data['status']       = 'Dispatched';
			$data['reminder']       = 'Order dispatched by seller, Waiting for your confirmation. ';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DELIVERED" ):
			$data['status']       = 'Finished';
			$data['reminder']       = 'You have confirmed order received.';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTED" ):
			$data['status']       = 'Refund Requsted';
			$data['reminder']       = 'You have requested refund for the order';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTED_REJECTED" ):
			$data['status']       = 'Request Refund Rejected';
			$data['reminder']       = 'Your refund request has been rejected';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_ACCEPTED" ):
			$data['status']       = 'Request Refund Accepted';
			$data['reminder']       = 'Your refund request has been accepted';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_CLAIMED" ):
			$data['status']       = 'Dispute Opened';
			$data['reminder']       = 'You have opened claimed for this order';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_RESOLVED" ):
			$data['status']       = 'Dispute Resolved';
			$data['reminder']       = 'The dispute for this order has been resolved';
			break;
	}

	return $data;
}

function getPaymentGateway($gateway_id){
	$data['gateway'] = '';
	switch ( $gateway_id ) {
		case \Config::get("constants_brandstore.PAYMENT_GATEWAY.PAYPAL"):
			$data['gateway'] = 'Pay Pal';
			break;

		case \Config::get("constants_brandstore.PAYMENT_GATEWAY.WORLD_PAY"):
			$data['gateway'] = 'World Pay';
			break;
	}

	return $data['gateway'];
}

function checkProductShippingCountryByProductsIds($product_ids, $country_id)
{
	$data['allowedProducts']    = [];
	$data['notAllowedProducts'] = [];
	$data['totalShippingCost']  = 0;

	foreach($product_ids as $product_id)
	{
		$isAllow = StoreShippingCountry::select('region_id')
				->where('country_id', $country_id)
				->where('product_id', $product_id)
				->first();

		if(isset($isAllow->region_id)){
			$totalShippingCost = StoreShippingCost::select('shipping_cost')->where('region_id', $isAllow->region_id)->where('product_id', $product_id)->first();
			$data['allowedProducts']   = $data['allowedProducts']   + [$product_id];

			if(isset($totalShippingCost->shipping_cost)){
				$data['totalShippingCost'] = $data['totalShippingCost'] + $totalShippingCost->shipping_cost;
			}
		}else{
			$data['notAllowedProducts']  = $data['notAllowedProducts'] +  [$product_id];
		}
	}

	return $data;
}

function getRegionCostByProductId($region_id, $product_id){
	$costInfo = StoreShippingCost::select('shipping_cost', 'status')
			->where( 'product_id', $product_id )
			->where( 'region_id', $region_id )
			->first();
	
	if(isset($costInfo->shipping_cost)){
		return $costInfo;
	}

	return false;
}
function getCurrentUserRegionId($userTimezone){
	$region = currentUserRegion('', $userTimezone);

	 $regionInfo = \kinnect2Store\Store\StoreShippingRegion::select('id')->where( 'name', $region )
			->first();
	if(isset($regionInfo->id)){
		return $regionInfo->id;
	}
	return false;
}

function allCountriesOfRegion($region_name){
	 $allCountriesOfRegion = DB::table('countries')->where( 'region', $region_name )->lists('name', 'id');
	if(is_array($allCountriesOfRegion)){
		return $allCountriesOfRegion;
//		return [0 => 'Select All'] + $allCountriesOfRegion;
	}

	return $allCountriesOfRegion;
}

function selectedProductShippingCountriesOfRegion($region_id, $product_id){
	$countriesIds = StoreShippingCountry::where('region_id', $region_id)->where('product_id', $product_id)->lists('country_id');
	return $allCountriesOfRegion = DB::table('countries')->whereIn( 'id', $countriesIds)->lists('id');
}

function allCountriesOfRegionHtml($region_name, $product_id='', $region_id=''){

	$allCountries               = allCountriesOfRegion($region_name);
	$selectedCountriesCountries = selectedProductShippingCountriesOfRegion($region_id, $product_id);

	$html = '
<div id="countryListOfRegion_' .$region_name . '" class="cssPopup_overlay">
	<div class="cssPopup_popup" style="width: 290px;">
	 	<a class="cssPopup_close" id="cssPopup_close_' .$region_name . '" href="#work">&times;</a>
		<div id="all_countries_list">
			<h1 style="font-size:16px;">Select countries to add in shipping country.</h1>
			<br />
		';
			$html .= Form::select('country_'.$region_name.'[]', $allCountries, $selectedCountriesCountries, array('multiple'=>'multiple',
					'id'=>'country_'.$region_name,
					'name'=>'country['.$region_name.'][]')) ;
		$html .= '
		<div>
			<a class="btn blue mt10" href="#work">Done</a>
		</div>
	</div>
	</div>
</div>
<script>
$("#donePopBtn_'.$region_name.'").click(function(e){
	e.preventDefault();
	$("#cssPopup_close_'.$region_name.'").click();
});

$("#country_'.$region_name.'").multiselect({
            columns: 1,
            search: true,
            selectedList : 1,
            placeholder: "Select Country",
            selectAll:true,
});

</script>';

	return $html;
}

function humanDifferenceInDateNow($date){
	$date = new Carbon($date);
	$now = Carbon::now();
	return $date->diffInDays($now).' Days';
}

function getProductShippingCost($userTimezone = null, $product_id=null){
	if($userTimezone == null || $product_id == null){
		return 0;
	}

	$region = currentUserRegion('', $userTimezone);

	$regionInfo = \kinnect2Store\Store\StoreShippingRegion::where( 'name', $region )
			->first();

	$regionCost = \kinnect2Store\Store\StoreShippingCost::select('shipping_cost')->where( 'region_id', $regionInfo->id )
			->where( 'product_id', $product_id )
			->first();
	if(isset($regionCost->shipping_cost)){
		return $regionCost->shipping_cost;
	}else{
		return 0;
	}

}
function currentUserRegion($userObj=null, $timezone=null){
	if($userObj != null AND isset($userObj->timezone)){
		$timezone = $userObj->timezone;
		$region = explode('/', $timezone);
		return strtolower($region[0]);
	}

	if($timezone != null){
		$region = explode('/', $timezone);
		return strtolower($region[0]);
	}

}
function getStatusForSellerOrderById($status_id){
	$data['status']       = '';
	$data['reminder']     = '';

	switch ( $status_id ) {
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_CANCELED" ):
			$data['status']       = 'Order Canceled';
			$data['reminder']       = 'Order Canceled';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_AWAITING_PAYMENT" ):
			$data['status']       = 'Awaiting for Payment';
			$data['reminder']       = 'Awaiting for Payment';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_PAYMENT_BEING_VERIFIED" ):
			$data['status']       = 'Payment to be verified';
			$data['reminder']       = 'Payment to be verified';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_PAYMENT_VERIFIED" ):
			$data['status']       = 'Payment approved';
			$data['reminder']       = 'The payment for this order has been verified';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_AWAITING_SHIPMENT" ):
			$data['status']       = 'Awaiting Shipment';
			$data['reminder']       = 'The Buyer is waiting for the order to be shipped';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPATCHED" ):
			$data['status']       = 'Dispatched';
			$data['reminder']       = 'The order is dispatched and awaiting for buyer acceptance';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DELIVERED" ):
			$data['status']       = 'Finished';
			$data['reminder']       = 'The buyer has confirmed order received.';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTED_REJECTED" ):
			$data['status']       = 'Refund Request Rejected';
			$data['reminder']       = 'You have rejected the refund request from buyer';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_ACCEPTED" ):
			$data['status']       = 'Refund Request Accepted';
			$data['reminder']       = 'You have accepted the refund request form buyer';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_CLAIMED" ):
			$data['status']       = 'Dispute Opended';
			$data['reminder']       = 'The buyer has opened dispute for this order';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_RESOLVED" ):
			$data['status']       = 'Dispute Resolved';
			$data['reminder']       = 'The dispute for this order has been resolved';
			break;
	}

	return $data;
}

function getOrderStatusForBuyer( $order_id, $status_id,$order ) {
	$data['class']        = '';
	$data['status']       = '';
	$data['action_btn_1'] = '';
	$data['action_btn_2'] = '';

	switch ( $status_id ) {
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_CANCELED" ):
			$data['class']        = 'cancel';
			$data['status']       = 'Order Canceled';
			$data['action_btn_1'] = '<a class="btn btng order_delete_btn order_action_btn_' . $order_id . '" id="deleteOrder_' . $order_id . '" href="javascript:void(0)">Delete</a>';
			$data['action_btn_2'] = '';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_AWAITING_PAYMENT" ):
			$data['class']        = 'inprogress';
			$data['status']       = 'Payment being verified';
			$data['action_btn_1'] = '';
			$data['action_btn_2'] = '<a class="btn btng order_status_btns order_action_brn_' . $order_id . '" id="cancel_0_order_' . $order_id . '" href="#orderCancelationInfo_'.$order_id.'">Cancel</a>'.cancelOrderBuyerFormHtml($order_id);
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_PAYMENT_BEING_VERIFIED" ):
			$data['class']        = 'inprogress';
			$data['status']       = 'Payment to be verified';
			$data['action_btn_1'] = '';
			$data['action_btn_2'] = '<a class="btn btng order_status_btns order_action_brn_' . $order_id . '" id="cancel_0_order_' . $order_id . '" href="#orderCancelationInfo_'.$order_id.'">Cancel</a>'.cancelOrderBuyerFormHtml($order_id);
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_PAYMENT_VERIFIED" ):
			$data['class']        = 'awaiting_dispatch';
			$data['status']       = 'Awaiting Shipment';
			$data['action_btn_1'] = '';
			$data['action_btn_2'] = '';//'<a class="btn btng order_status_btns order_action_brn_' . $order_id . '" id="cancel_0_order_' . $order_id . '" href="#orderCancelationInfo_'.$order_id.'">Cancel</a>'.cancelOrderBuyerFormHtml($order_id);
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_AWAITING_SHIPMENT" ):
			$data['class']        = 'awaiting_shipment';
			$data['status']       = 'Awaiting Shipment';
			$data['action_btn_1'] = '';
			$data['action_btn_2'] = '';//'<a class="btn btng order_status_btns order_action_brn_' . $order_id . '" id="cancel_0_order_' . $order_id . '" href="#orderCancelationInfo_'.$order_id.'">Cancel</a>'.cancelOrderBuyerFormHtml($order_id);
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPATCHED" ):
			$data['class']        = 'shipped';
			$data['status']       = 'Awaiting Delivery';
			$data['action_btn_1'] = '<a class="btn" href="#confirmationOfOrderReceived_'.$order_id.'">Order Received</a>'.confirmationOfOrderReceivedBuyerFormHtml($order_id);

			if(isset($order->delivery)){
				$days = date_difference($order->delivery->date_to_be_delivered);
			}

			//if($days < 0){
				$data['action_btn_2'] = '<a class="btn btng order_dispute_btn order_action_brn_' . $order_id . '" id="cancel_0_order_' . $order_id . '" href="' . url( 'store/order/dispute/' . $order_id ) . '">Request Refund</a>';
			//}else{
				/*$data['action_btn_2'] = '<a class="btn btng order_dispute_btn order_action_brn_' . $order_id . '" id="cancel_0_order_' . $order_id . '" href="#courierServiceInfo_'.$order_id.'">Open Dispute</a>'.timeRemainingPopUp( date_difference_human($order->delivery->date_to_be_delivered), $order_id );
			}*/

			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTED" ):
			$data['class']        = 'disputed';
			$data['status']       = 'Refund request is created';
			$data['action_btn_1'] = '';
			$data['action_btn_2'] = '<a class="btn " id="" href="' . url( 'store/order/dispute/' . $order_id ) . '">Refund Detail</a>';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTED_REJECTED" ):
			$data['class']        = 'disputed';
			$data['status']       = 'Dispute has been rejected by seller';
			$data['action_btn_1'] = '<a class="btn order_status_btn order_action_brn_' . $order_id . '" id="approve_6_order_' . $order_id . '" href="javascript:void(0)">Order Received</a>'.confirmationOfOrderReceivedBuyerFormHtml($order_id);;

			$data['action_btn_2'] = '<a class="btn btng order_dispute_btn order_action_brn_' . $order_id . '" id="cancel_0_order_' . $order_id . '" href="' . url( 'store/order/dispute/' . $order_id ) . '"> Refund Detail</a>';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTED_CANCELLED" ):
			$data['class']        = 'disputed';
			$data['status']       = 'Dispute has been cancelled';
			$data['action_btn_1'] = '<a class="btn order_status_btn order_action_brn_' . $order_id . '" id="approve_6_order_' . $order_id . '" href="javascript:void(0)">Order Received</a>'.confirmationOfOrderReceivedBuyerFormHtml($order_id);;

			$data['action_btn_2'] = '<a class="btn btng order_dispute_btn order_action_brn_' . $order_id . '" id="cancel_0_order_' . $order_id . '" href="' . url( 'store/order/dispute/' . $order_id ) . '"> Refund Detail</a>';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_ACCEPTED" ):
			$data['class']        = 'disputed';
			$data['status']       = config("constants_brandstore.ORDER_STATUS_MESSAGE.".$status_id);
			$data['action_btn_1'] = '';//'<a class="btn order_status_btn order_action_brn_' . $order_id . '" id="approve_6_order_' . $order_id . '" href="javascript:void(0)">Order Received</a>'.confirmationOfOrderReceivedBuyerFormHtml($order_id);

			$data['action_btn_2'] = '<a class="btn btng order_dispute_btn order_action_brn_' . $order_id . '" id="cancel_0_order_' . $order_id . '" href="' . url( 'store/order/dispute/' . $order_id ) . '"> Refund Detail</a>';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DELIVERED" ):
			$data['class']        = 'finished';
			$data['status']       = 'Order Finished';
			$data['action_btn_1'] = '';
			$data['action_btn_2'] = '';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_CLAIMED" ):
			$data['class']        = 'disputed';
			$data['status']       = 'Refund request has been disputed';
			$data['action_btn_1'] = '';

			$data['action_btn_2'] = '<a class="btn btng order_dispute_btn order_action_brn_' . $order_id . '" id="cancel_0_order_' . $order_id . '" href="' . url( 'store/order/dispute/' . $order_id ) . '"> Refund Detail</a>';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_RESOLVED" ):
			$data['class']        = 'disputed';

			$data['action_btn_2'] = '<a class="btn btng order_dispute_btn order_action_brn_' . $order_id . '" id="cancel_0_order_' . $order_id . '" href="' . url( 'store/order/dispute/' . $order_id ) . '"> Dispute Detail</a>';;

			$data['status']       = \Config::get( "constants_brandstore.ORDER_STATUS_MESSAGE.".$status_id );
			$data['action_btn_1'] = '';//'<a class="btn order_status_btn order_action_brn_' . $order_id . '" id="approve_6_order_' . $order_id . '" href="javascript:void(0)">Order Received</a>'.confirmationOfOrderReceivedBuyerFormHtml($order_id);;
			break;
	}

	return $data;
}

function getOrderStatusForSeller( $order_id, $status_id ) {

	$data['class']        = '';
	$data['status']       = '';
	$data['action_btn_1'] = '';
	$data['action_btn_2'] = '';

	switch ( $status_id ) {
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_CANCELED" ):
			$data['class']        = 'cancel';
			$data['status']       = 'Order Canceled';
			$data['action_btn_1'] = '<a class="btn btng order_delete_btn order_action_btn_' . $order_id . '" id="deleteOrder_' . $order_id . '" href="javascript:void(0)">Delete</a>';
			$data['action_btn_2'] = '';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_AWAITING_PAYMENT" ):
			$data['class']        = 'inprogress';
			$data['status']       = 'Awaiting for Payment';
			$data['action_btn_1'] = '<a class="btn order_request_pay_btn order_action_brn_' . $order_id . '" id="order_' . $order_id . '" href="javascript:void(0)">Request to Pay</a>';
			$data['action_btn_2'] = '';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_PAYMENT_BEING_VERIFIED" ):
			$data['class']        = 'inprogress';
			$data['status']       = 'Payment to be verified';
			$data['action_btn_1'] = '';
			$data['action_btn_2'] = '';
			break;

		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_PAYMENT_VERIFIED" ):
			$data['class']        = 'awaiting_dispatch';
			$data['status']       = 'Payment approved';
			$data['action_btn_1'] = '<a class="btn order_status_btn order_action_brn_' . $order_id . '" id="approve_4_order_' . $order_id . '" href="javascript:void(0)">Approve</a>';
			$data['action_btn_2'] = '<a class="btn btng order_status_btns order_action_brn_' . $order_id . '" id="cancel_0_order_' . $order_id . '" href="#orderCancelationInfo_'.$order_id.'">Cancel</a>'.cancelOrderSellerFormHtml($order_id, Auth::user()->username);
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_AWAITING_SHIPMENT" ):
			$data['class']        = 'awaiting_shipment';
			$data['status']       = 'Awaiting Shipment';
			$data['action_btn_1'] = '<a class="btn order_action_brn_' . $order_id . '" id="approve_5_order_' . $order_id . '" href="#courierServiceInfo_' . $order_id . '">Send Order</a>' . getOrderCourierServiceInformationForm( $order_id, 5 );
			$data['action_btn_2'] = '';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPATCHED" ):
			$data['class']        = 'shipped';
			$data['status']       = 'Awaiting receiver approval';
			$data['action_btn_1'] = '';
			$data['action_btn_2'] = '';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTED" ):
			$data['class']        = 'disputed';
			$data['status']       = 'Refund request has been created';
			$data['action_btn_1'] = '';
			$data['action_btn_2'] = '<a class="btn " id="" href="' . url( 'store/order/dispute/' . $order_id ) . '">Refund Detail</a>';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_ACCEPTED" ):
			$data['class']        = 'disputed';
			$data['status']       = config("constants_brandstore.ORDER_STATUS_MESSAGE.".$status_id);
			$data['action_btn_2'] = '<a class="btn " id="" href="' . url( 'store/order/dispute/' . $order_id ) . '">Refund Detail</a>';

			$data['action_btn_2'] = '<a class="btn btng order_dispute_btn order_action_brn_' . $order_id . '" href="' . url( 'store/order/dispute/' . $order_id ) . '"> Refund Detail</a>';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTED_REJECTED" ):
			$data['class']        = 'disputed';
			$data['status']       = 'Refund request has been rejected';
			$data['action_btn_1'] = '';

			$data['action_btn_2'] = '<a class="btn btng order_dispute_btn order_action_brn_' . $order_id . '" href="' . url( 'store/order/dispute/' . $order_id ) . '"> Refund Detail</a>';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTED_CANCELLED" ):
			$data['class']        = 'disputed';
			$data['status']       = 'Refund request has been cancelled by buyer';
			$data['action_btn_1'] = '';

			$data['action_btn_2'] = '<a class="btn btng order_dispute_btn order_action_brn_' . $order_id . '" href="' . url( 'store/order/dispute/' . $order_id ) . '"> Refund Detail</a>';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DELIVERED" ):
			$data['class']        = 'finished';
			$data['status']       = 'Goods Deliverd';
			$data['action_btn_1'] = '';
			$data['action_btn_2'] = '';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_CLAIMED" ):
			$data['class']        = 'disputed';
			$data['status']       = 'Refund request has been disputed by buyer';
			$data['action_btn_1'] = '';

			$data['action_btn_2'] = '<a class="btn btng order_dispute_btn order_action_brn_' . $order_id . '" href="' . url( 'store/order/dispute/' . $order_id ) . '"> Refund Detail</a>';
			break;
		case \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_RESOLVED" ):
			$data['class']        = 'disputed';
			$data['action_btn_2'] = '<a class="btn btng order_dispute_btn order_action_brn_' . $order_id . '"  href="' . url( 'store/order/dispute/' . $order_id ) . '"> Dispute Detail</a>';;

			$data['status']       = \Config::get( "constants_brandstore.ORDER_STATUS_MESSAGE.".$status_id );
			$data['action_btn_1'] = '';

			break;



	}

	return $data;

}

function getOrderCourierServiceInformationForm( $order_id, $order_status ) {
	return '
<div id="courierServiceInfo_' .$order_id . '" class="cssPopup_overlay">
	<div class="cssPopup_popup" style="width: 46.7%; background:none;">
	 	<a style="top: 23px !important; right: 27px;" class="cssPopup_close" href="#">&times;</a>

 <div class="courierServiceInfoWrap">
        <div class="addProduct">
            <h1>Add Courier Service Information</h1>

<div id="delivery_info_form_wrap" class="dispute-wrapper">
	' . Form::open( [ 'url'     => url( "store/" . Auth::user()->username . "/admin/add-courier-service-info/" . $order_id . "/" . $order_status ),
	                  "id"      => "add_courier_service_info_".$order_id,
	                  "enctype" => "multipart/form-data"
	] ) . '
        <div class="field-item dispute-row">
		    <div class="title mW mt5"><label for="courier_service_name">Courier Service Title:</label></div>
				<div class="detail bb">
		    		<input type="text" value="" id="courier_service_name_'.$order_id.'" name="courier_service_name" placeholder="Enter Courier Service Title" required="required" class="inp">
		    		<input type="hidden" id="order_id" name="order_id" value="' . $order_id . '">
		    		<span id="title_error_'.$order_id.'" class="error"></span>
				</div>
			</div>

		<div class="field-item dispute-row">
		    <div class="title mW mt5"><label for="courier_service_url">Courier Service website link</label></div>
		    <div class="detail bb"><input type="text" id="courier_service_url_'.$order_id.'" name="courier_service_url" placeholder="Enter Courier Service website link" required="required" class="inp"> <span class="error" id="urlLinkError_' . $order_id . '" ></span></div>
		</div>

		<div class="field-item dispute-row">
		    <div class="title mW mt5"><label for="order_tracking_number">Order Tracking Number</label></div>
		    <div class="detail bb"><input type="text" id="order_tracking_number_'.$order_id.'" name="order_tracking_number" placeholder="Enter Order Tracking Number" required="required" class="inp"><span class="error" id="urlTrackOrdrError_' . $order_id . '" ></span></div>
		</div>

		<!--<div class="field-item dispute-row">
		    <div class="title mW mt5"><label for="delivery_estimated_time">Delivery estimated time</label></div>
		    <div class="detail bb"><input type="text" id="delivery_estimated_time_'.$order_id.'" name="delivery_estimated_time" placeholder="Enter Order delivery estimated time" required="required" class="inp"></div>
		</div>-->

		<div class="field-item dispute-row">
		    <div class="title mW mt5"><label for="date_to_be_delivered">Delivery Date</label></div>
		    <div class="detail bb">
		    	<input type="text" id="date_to_be_delivered_'.$order_id.'" name="date_to_be_delivered" placeholder="Enter Order delivery date" required="required" class="inp">
		    	<span id="date_error_'.$order_id.'" class="error"></span>
		    </div>
		</div>

		<!--<div class="field-item dispute-row">
		    <div class="title mW mt5"><label for="delivery_charges_paid">Delivery charges paid</label></div>
		    <div class="detail bb">Yes<input type="radio" id="delivery_charges_paid_'.$order_id.'" name="delivery_charges_paid" value="1" checked="checked"  required="required" style="width:auto;" class="mr10">
		    No<input type="radio" id="delivery_charges_paid" name="delivery_charges_paid" value="0" required="required" style="width:auto;">
			</div>
		</div>-->

		<input type="hidden" id="delivery_charges_paid" name="delivery_charges_paid" value="1">

		<div class="fltR mt20 mb20">
                <button id="addOrderDeliveryButton_' . $order_id . '" class="btn blue fltL mr10" type="button">Save</button>
        </div>
        ' . Form::close() . '
            <script>
        function validCadsUrl(s, trackUrl){
            var message;
            var myRegExp =/^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
            var urlToValidate = s;
            var trackUrlUrlToValidate = trackUrl;

            if (!myRegExp.test(urlToValidate)){
                return "serviceLink";
            }

            if (!myRegExp.test(trackUrlUrlToValidate)){
                //return "trackUrl";
            }

			return true;

            alert(message);
        }

        $("#addOrderDeliveryButton_' . $order_id . '").click(function(evt){

		var serviceUrl = $("#courier_service_url_' . $order_id . '").val();
		var trackUrl   = $("#order_tracking_number_' . $order_id . '").val();

		var isValidUrl = validCadsUrl(serviceUrl, trackUrl);
		var isValidForm = true;
		if(isValidUrl != true){
			isValidForm = false;
			$("#urlLinkError_' . $order_id . '").html("Please enter valid \'Courier Service website link\' (e.g: http://www.dhl.com.pk) ");
			//alert("Please enter valid \'Courier Service website link\' (e.g: http://www.dhl.com.pk) ");
			//return false;
		}

		if(trackUrl == ""){
			isValidForm = true;
			$("#urlTrackOrdrError_' . $order_id . '").html("Please enter  \'Order Tracking Number \'");
			//alert("Please enter valid \'Order Tracking website link\' (e.g: http://www.dhl.com.pk/order=ASW98234) ");
			//return false;
		}
		
		$("#linkSpan").remove();
		
		var title = jQuery("#courier_service_name_'.$order_id.'").val();
		
		if(title == ""){
			jQuery("#title_error_'.$order_id.'").text("Please enter courier service title");
			isValidForm = false;
		}
		
    	var selectedDeliveryDate = $("#date_to_be_delivered_'.$order_id.'").val();
			
		if(selectedDeliveryDate == ""){
			jQuery("#date_error_'.$order_id.'").text("Please select delivery date");
			isValidForm = false;
		}
		
		if(date > selectedDeliveryDate){
			jQuery("#date_error_'.$order_id.'").text("Please enter future date from now for delivery");
			isValidForm = false;
		}

		if(isValidForm === false){
			return false;
		}
        evt.preventDefault();
        $.ajax({type:\'POST\', url: "' . url( "store/" . Auth::user()->username . '/admin/add-courier-service-info/' . $order_id . '/' . $order_status ) . '", data:$(\'#add_courier_service_info_'.$order_id.'\').serialize(), success: function(data) {
    $(".order_action_brn_"+data.order_id).remove();
                $(".order_action_"+data.order_id).html(data.action_btn_1 + data.action_btn_2);
                $(".order_status_"+data.order_id).html(data.status);
}});
});

        </script>

<script>
	var today = new Date();
	var dd = today.getDate();
	if(dd < 10){
		dd = "0"+dd;
	}
    var mm = today.getMonth()+1;
    if(mm < 10){
		mm = "0"+mm;
	}
    var yy = today.getFullYear();
	var date  = yy+"-"+mm+"-"+dd;

	$(function(){
        $("#date_to_be_delivered_'.$order_id.'").datepicker({
            inline: true,
            showOtherMonths: true,
            minDate: 0,
			onSelect: function(theDate) {
				$("#dataEnd").datepicker(\'option\', \'minDate\', new Date(theDate));
			},
            dateFormat: \'yy-mm-dd\' ,
            dayNamesMin: [\'Sun\', \'Mon\', \'Tue\', \'Wed\', \'Thu\', \'Fri\', \'Sat\'],

        });
    });
</script>
</div>
</div>
</div>
</div>
</div>';
}
function sendFeedbackReminder($order_id, $product_id, $storeName){
	return '<script>
	$("#sendFeedbackReminder_' . $order_id.$product_id . '").click(function(evt){
		evt.preventDefault();
		$.ajax({type:"POST", url: "' . url( "store/feedback/reminder/ajax/".$product_id."/".$order_id) . '", success: function(data) {
			$(".order_action_brn_"+data.order_id).remove();
			$(".order_action_"+data.order_id).html(data.action_btn_1 + data.action_btn_2);
			$(".order_status_"+data.order_id).html(data.status);
		}, error: function(data){alert("error: "+data);}
});
});
</script>';
}

function getOrderNumber($order_id){
	$info = StoreOrder::select("order_number")->where('id', $order_id)->first();
	if(isset($info->order_number)){
		return $info->order_number;
	}
	return "N/A";
}

function giveFeedbackFormHtml($order_id=null, $product_id=null, $storeName=null){
	$html = '
<div id="giveFeedbackInfo_' .$order_id.$product_id .'" class="cssPopup_overlay">
	<div class="cssPopup_popup" style="width:430px">
	 	<a class="cssPopup_close cssPopup_close_' .$order_id.$product_id .'" href="#">&times;</a>
			<div class="sd-popup">
			<div class="sdp-header">Give Feedback</div>
                <div class="sd-orderId">Order ID:'.	getOrderNumber($order_id).'</div>
                ' . Form::open( [ 'url'     => url( ),
	                  "id"      => "giveFeedbackForm_".$order_id.$product_id,
	                  "enctype" => "multipart/form-data"
	] ) . '
                <input type="hidden" value="'.$order_id.'" name="order_id" />
                <input type="hidden" value="'.$storeName.'" name="store_name" />
                   ';
	$inputStars = '<div>
                    <input type="text" style="display: none" id="stars_rating_' .$order_id. $product_id . '" name="stars_rating">
                    <img class="rating_stars_' .$order_id. $product_id . '" src="'.asset('local/public/assets/images/star.png').'" alt="Rating" />
                    <img class="rating_stars_' .$order_id. $product_id . '" src="'.asset('local/public/assets/images/star.png').'" alt="Rating" />
                    <img class="rating_stars_' .$order_id. $product_id . '" src="'.asset('local/public/assets/images/star.png').'" alt="Rating" />
                    <img class="rating_stars_' .$order_id. $product_id . '" src="'.asset('local/public/assets/images/star.png').'" alt="Rating" />
                    <img class="rating_stars_' .$order_id. $product_id . '" src="'.asset('local/public/assets/images/star.png').'" alt="Rating" />
                </div>';


	$html .='   <label>How would you revise your rating for this product?</label>
                    <div class="">'.$inputStars.'</div>
                    <label>Comment</label>
                    <textarea name="description"></textarea>
                    <button type="button" id="giveSubmitBtn_'.$order_id.$product_id.'" class="blueBtn">Submit</button>
                </form>
            </div>
            </div>
            </div>
            <script>
        $("#giveSubmitBtn_' .$order_id.$product_id . '").click(function(evt){
            evt.preventDefault();
        $.ajax({type:"POST", url: "' . url( "store/review/ajax/".$product_id) . '", data:$("#giveFeedbackForm_"+'.$order_id.$product_id.').serialize(), success: function(data) {
			   $(".order_action_brn_"+data.order_id).remove();
                $(".order_action_"+data.order_id).html(data.action_btn_1 + data.action_btn_2);
                $(".order_status_"+data.order_id).html(data.status);
                $("#rating_status_"+data.order_id).html("Rated Successfully");
				$("#giveFeedbackInfo_' .$order_id.$product_id . '").remove();
}, error: function(data){alert("error: "+data);}
});
});

//Rating star script
$(".rating_stars_' .$order_id.$product_id . '").hover(
    // Handles the mouseover
    function() {
        $(this).prevAll().andSelf().attr("src" , "'. asset('local/public/assets/images/rattingstar.png').'");
        $(this).nextAll().attr("src" , "'.asset('local/public/assets/images/star.png').'");
    },
    // Handles the mouseout
    function() {
        $(this).prevAll().andSelf().attr("src" , "'. asset('local/public/assets/images/rattingstar.png').'");
    },

    $(".rating_stars_' .$order_id.$product_id . '").click(function(){
        var count =  $(this).prevAll().length;
        document.getElementById("stars_rating_' .$order_id.$product_id . '").value = count;
        var var1= document.getElementById("stars_rating_' .$order_id.$product_id . '").value;
    })
);

        </script >
            ';
	return $html;
}
function reviseFeedbackFormHtml($order_id, $review, $storeName, $product_id, $reasons=[1,2,3]){
	$html = '
<div id="reviseFeedbackInfo_' .$order_id .$product_id.'" class="cssPopup_overlay">
	<div class="cssPopup_popup" style="width:430px">
	 	<a class="cssPopup_close cssPopup_close_'.$order_id.$product_id.'" style="top: 5px; right: 3px;" href="#">&times;</a>
<div class="sd-popup">
                <div class="sdp-header">Revise Feedback</div>
                <div class="sd-orderId">Order ID:'.getOrderNumber($order_id)
	.'</div>
                ' . Form::open( [ 'url'     => url( ),
	                  "id"      => "reviseFeedbackForm_".$order_id .$product_id,
	                  "enctype" => "multipart/form-data"
	] ) . '
                <input type="hidden" value="'.$product_id.'" name="product_id" />
                <input type="hidden" value="'.$order_id.'" name="order_id" />
                <input type="hidden" value="'.$review->id.'" name="review_id" />
                <input type="hidden" value="'.$storeName.'" name="store_name" />
                    <label>*Select a reason why you need to revise feedback</label>
                    <div class="field-item">
                        <select name="reason">
                            ';
	foreach($reasons as $reason) {
		$html .= '<option value="">Reason goes here</option>';
	}

	/*$starsHtml = $ratingStars = '';

	if($review->rating == 0){
		 $starsHtml = '<img class="rated_stars_'. $order_id . '" src="'.asset("local/public/assets/images/star.png") .'" alt="Rating" />';
	 }

     for($i=1;$i<=$review->rating;$i++){
         $ratingStars .= '<img class="rated_stars_' . $order_id . '" src="'. asset("local/public/assets/images/rattingstar.png").'" alt="Rating" />';
     }*/
	$inputStars = '<div>
                    <input type="text" style="display: none" id="stars_rating_' . $order_id .$product_id. '" name="stars_rating">
                    <img class="rating_stars_' . $order_id .$product_id. '" src="'.asset('local/public/assets/images/star.png').'" alt="Rating" />
                    <img class="rating_stars_' . $order_id .$product_id. '" src="'.asset('local/public/assets/images/star.png').'" alt="Rating" />
                    <img class="rating_stars_' . $order_id .$product_id. '" src="'.asset('local/public/assets/images/star.png').'" alt="Rating" />
                    <img class="rating_stars_' . $order_id .$product_id. '" src="'.asset('local/public/assets/images/star.png').'" alt="Rating" />
                    <img class="rating_stars_' . $order_id .$product_id. '" src="'.asset('local/public/assets/images/star.png').'" alt="Rating" />
                </div>';


	$html .='</select>
                    </div>
                    <label>How would you revise your rating for this product?</label>
                    <div class="">'.$inputStars.'</div>
                    <label>Comment</label>
                    <textarea name="description">'.$review->description.'</textarea>
                    <button type="button" id="reviseSubmitBtn_'.$order_id.$product_id.'" class="blueBtn">Submit</button>
                </form>
            </div>
            </div>
            </div>
            <script>
        $("#reviseSubmitBtn_' . $order_id .$product_id. '").click(function(evt){
            evt.preventDefault();
        $.ajax({type:"POST", url: "' . url( "store/".$storeName."/reviseFeedback/".$order_id ) . '", data:$("#reviseFeedbackForm_"+'.$order_id.$product_id.').serialize(), success: function(data) {

   				$(".order_action_brn_"+data.order_id).remove();
   				$("#reviseFeedbackInfo_'.$order_id.$product_id.'").remove();

                $(".order_action_"+data.order_id).html(data.action_btn_1 + data.action_btn_2);
                $(".order_status_"+data.order_id).html(data.status);
                $("#rating_status_"+data.order_id).html("Rating revised");
}, error: function(data){alert("error: "+data);}
});
});

//Rating star script
$(".rating_stars_' . $order_id .$product_id. '").hover(
    // Handles the mouseover
    function() {
        $(this).prevAll().andSelf().attr("src" , "'. asset('local/public/assets/images/rattingstar.png').'");
        $(this).nextAll().attr("src" , "'.asset('local/public/assets/images/star.png').'");
    },
    // Handles the mouseout
    function() {
        $(this).prevAll().andSelf().attr("src" , "'. asset('local/public/assets/images/rattingstar.png').'");
    },

    $(".rating_stars_' . $order_id . $product_id.'").click(function(){
        var count =  $(this).prevAll().length;
        document.getElementById("stars_rating_' . $order_id .$product_id. '").value = count;
        var var1= document.getElementById("stars_rating_' . $order_id .$product_id. '").value;
    })
);

        </script >
            ';
	return $html;
}
function getProductUrlByIdAndOwnerId( $id, $ownerId ) {
	//http://localhost/kinnect2/store/cairo/product/2228.
	//Get Product owner username
	$username = getUserNameByUserId( $ownerId );
	if ( isset( $username ) ) {
		return url( 'store/' . $username . '/product/' . $id );
	} else {
		return url( 'javascript:void(0);' );
	}
}

function getProductPhotoSrc( $file_id = null, $photo_id = null, $owner_id = null, $image_size_type = null ) {

//	if ( isset( $owner_id ) ) {
//		$photo = StoreAlbumPhotos::where( 'owner_id', $owner_id )
//		                         ->where( 'owner_type', 'product' )
//		                         ->select( 'file_id' )
//		                         ->orderBy( 'album_id', 'DESC' )
//		                         ->first();
//
//		if ( isset( $photo->file_id ) ) {
			if ( $image_size_type != null ) {
				$file = StoreStorageFiles::where( 'type', $image_size_type )->where( 'parent_id', $owner_id )->first();
			} else {
				$file = StoreStorageFiles::where(  'parent_id', $owner_id  )->first();
			}

			if ( isset( $file->storage_path ) ) {
				return \Config::get( 'constants_activity.PHOTO_URL' ) . $file->storage_path . '?type=' . urlencode( $file->mime_type );
			}

			return '';
		//}
	//}
}

function getPreviousStoreUrl() {
	$url = \Config::get( "constants_brandstore.PREVIOUS_STORE_URL" );
	if ( $url != '' ) {
		return 'store/' . $url;
	} else {
		return '';
	}
}

function setPreviousStoreUrl( $storeName ) {
	\Config::set( "constants_brandstore.PREVIOUS_STORE_URL", $storeName );

	return 1;
}

function product_image_src( $product_id = null ) {
	if ( isset( $product_id ) ) {
//		$photo = StoreAlbumPhotos::where( 'owner_id', $product_id )
//		                         ->where( 'owner_type', 'product' )
//		                         ->select( 'file_id' )
//		                         ->first();

		//if ( isset( $photo->file_id ) ) {
			$file = StoreStorageFiles::where( 'parent_id', $product_id )->where( 'type', 'product_profile' )->first();

			if ( isset( $file->storage_path ) ) {
				return \Config::get( 'constants_activity.PHOTO_URL' ) . $file->storage_path . '?type=' . urlencode( $file->mime_type );
			}
		//}
	}

	return asset( '/local/packages/kinnect2Store/assets/images/no_photo.png' );
}

function getUserDetail( $id ) {
	$User = User::where( 'id', $id )->orWhere( 'username', $id )->first();
	if ( isset( $User ) ) {
		return $User;
	} else {
		return false;
	}
}

function getUserEmailAndUsername( $id ) {
	$User = User::select('email', 'username','displayname')->where( 'id', $id )->orWhere( 'username', $id )->first();
	if ( isset( $User ) ) {
		return $User;
	} else {
		return false;
	}
}
function getUserNameByUserId( $id ) {
	$User = User::select( 'username' )->where( 'id', $id )->first();
	if ( isset( $User ) ) {
		return $User->username;
	} else {
		return false;
	}
}

function getDisplayNameByUserId( $id ) {
	$User = User::select( 'displayname' )->where( 'id', $id )->first();
	if ( isset( $User ) ) {
		return $User->displayname;
	} else {
		return false;
	}
}
function profileAddress( $ownerInfo ) {
	if ( isset( $ownerInfo->user_type ) ) {
		if ( $ownerInfo->user_type == \Config::get( 'constants.BRAND_USER' ) ) {
			return $profileUrl = url( 'brand' . '/' . $ownerInfo->username );
		} else {
			return $profileUrl = url( 'profile' . '/' . $ownerInfo->username );
		}
	}

	return $profileUrl = url( 'home' );

}

function getThumbSrcWithProductId( $product_id = 0, $thumb_type = 'product_thumb' ) {
//	$productPhoto = StoreAlbumPhotos::where( 'owner_id', $product_id )
//	                                ->where( 'owner_type', 'product' )
//	                                ->select( 'photo_id' )
//	                                ->first();
//	if ( ! empty( $productPhoto ) ) {
//		return getPhotoUrl( $productPhoto->photo_id, '', 'product', $thumb_type );
//	}
//

	$file = StoreStorageFiles::where( 'parent_id', $product_id )
			->where( 'type', $thumb_type )->first();
	if ( isset( $file->storage_path ) ) {
		return \Config::get( 'constants_activity.PHOTO_URL' ) . $file->storage_path . '?type=' . urlencode( $file->mime_type );
	} else {
		return asset( '/local/public/images/login-page/no_image.jpg' );
	}

}

function getPhotoUrl( $photo_id = null, $user_id, $type = null, $thumb_type = null ) {
//		return asset('/local/public/images/login-page/upload-img.png');

	if ( isset( $photo_id ) ) {
//		$photo                = StoreAlbumPhotos::where( 'photo_id', $photo_id )
//		                                        ->select( 'file_id' )
//		                                        ->first();
		$tryForPhotFromFileId = 0;
		if ( ! isset( $photo ) ) {
			$tryForPhotFromFileId = 1;
			$file                 = StoreStorageFiles::where( 'file_id', $photo_id )->first();
		}//try once more to find if it it file_id only

		if ( isset( $thumb_type ) AND isset( $photo->file_id ) AND $tryForPhotFromFileId == 0 ) {
			$file = StoreStorageFiles::where( 'parent_file_id', $photo->file_id )
			                         ->where( 'type', $thumb_type )->first();
		} else if ( isset( $photo->file_id ) AND $tryForPhotFromFileId == 0 ) {
			$file = StoreStorageFiles::where( 'file_id', $photo->file_id )->first();
		}

		if ( ! isset( $file->storage_path ) ) {
			if ( $type == 'ads' ) {
				return asset( '/local/storage/images/ads/ad-default 170x170.png' );
			} else if ( $type == 'brand' ) {
				return asset( '/local/public/brands/thumb_brand_default.jpg' );
			} else if ( $type == 'event' ) {
				return asset( '/local/public/assets/images/left-menu-img-header.jpg' );
			} else {
				return asset( '/local/public/images/login-page/upload-img.png' );
			}
		}

		if ( isset( $file->storage_path ) ) {
			return \Config::get( 'constants_activity.PHOTO_URL' ) . $file->storage_path . '?type=' . urlencode( $file->mime_type );
		} else {
			return asset( '/local/public/images/login-page/no_image.jpg' );
		}
	}

	return asset( '/local/public/images/login-page/no_image.jpg' );
}

function getPhotoUrlRegularUser( $photo_id = null, $user_id, $type = null, $thumb_type = null ) {
//		return asset('/local/public/images/login-page/upload-img.png');
//dd($photo_id .' <> '. $user_id .' <> '. $type .' <> '. $thumb_type .' <> ');
	if ( isset( $photo_id ) ) {
		$photo                = AlbumPhoto::where( 'photo_id', $photo_id )
		                                        ->select( 'file_id' )
		                                        ->first();
		$tryForPhotFromFileId = 0;
		if ( ! isset( $photo ) ) {
			$tryForPhotFromFileId = 1;
			$file                 = StorageFile::where( 'file_id', $photo_id )->first();
		}//try once more to find if it it file_id only

		if ( isset( $thumb_type ) AND isset( $photo->file_id ) AND $tryForPhotFromFileId == 0 ) {
			$file = StorageFile::where( 'parent_file_id', $photo->file_id )
					->where( 'type', $thumb_type )->first();
		} else if ( isset( $photo->file_id ) AND $tryForPhotFromFileId == 0 ) {
			$file = StorageFile::where( 'file_id', $photo->file_id )->first();
		}

		if ( ! isset( $file->storage_path ) ) {
			if ( $type == 'ads' ) {
				return asset( '/local/storage/images/ads/ad-default 170x170.png' );
			} else if ( $type == 'brand' ) {
				return asset( '/local/public/brands/thumb_brand_default.jpg' );
			} else if ( $type == 'event' ) {
				return asset( '/local/public/assets/images/left-menu-img-header.jpg' );
			} else {
				return asset( '/local/public/images/login-page/upload-img.png' );
			}
		}

		if ( isset( $file->storage_path ) ) {
			return \Config::get( 'constants_activity.PHOTO_URL' ) . $file->storage_path . '?type=' . urlencode( $file->mime_type );
		} else {
			return asset( '/local/public/images/login-page/no_image.jpg' );
		}
	}

	return asset( '/local/public/images/login-page/no_image.jpg' );
}

function product_images_src( $product_id = null ) {
	$productImagesSrc = '';

	if ( isset( $product_id ) ) {
//		$fileIds = StoreAlbumPhotos::where( 'owner_id', $product_id )
//		                           ->where( 'owner_type', 'product' )
//		                           ->lists( 'file_id' );

//		if ( count( $fileIds ) > 0 ) {

			$data['mainImageFiles'] = StoreStorageFiles::select( 'file_id', 'type', 'storage_path', 'mime_type' )
					->where( 'type', null)
					->where( 'parent_id', $product_id)
					->orderBy('file_id','ASC')
//					->whereIn( 'file_id', $fileIds )
					->get();
//			$data['thumbImageFiles'] = StorageFile::select('file_id', 'type', 'storage_path', 'mime_type')->where('type', '=', 'product_thumb')->whereIn( 'parent_file_id', $fileIds )->get();
//			return array_merge( array($data['thumbImageFiles']), array($data['mainImageFiles'])) ;

			return $data;
//		}
	}

	$productImagesSrc[0] = asset( '/local/packages/kinnect2Store/assets/images/no_photo.png' );

	return $productImagesSrc;
}

function product_images_edit_src( $product_id = null ) {
	$productImagesSrc = '';

	if ( isset( $product_id ) ) {
//		$fileIds = StoreAlbumPhotos::where( 'owner_id', $product_id )
//		                           ->where( 'owner_type', 'product' )
//		                           ->lists( 'file_id' );
//
//		if ( count( $fileIds ) > 0 ) {

//			$files = StoreStorageFiles::whereIn( 'file_id', $fileIds )->get();
			$files = StoreStorageFiles::where( 'type', 'product_profile')
				->where( 'parent_id', $product_id)->get();

			foreach ( $files as $file ) {
				if ( isset( $file->storage_path ) ) {
					$productImagesSrc[ $file->parent_file_id ] = \Config::get( 'constants_activity.PHOTO_URL' ) . $file->storage_path . '?type=' . urlencode( $file->mime_type );
				}
			}

			return $productImagesSrc;
		//}
	}

	$productImagesSrc[0] = asset( '/local/packages/kinnect2Store/assets/images/no_photo.png' );

	return $productImagesSrc;
}

function isProductRatingExist( $product_id ) {
	$rate = StoreProductReview::select('id')->where( 'product_id', $product_id )->first();

	if ( isset($rate->id)) {
		return 1;
	} else {
		return 0;
	}
}

function getRatings( $product_id ) {
	$rate = StoreProductReview::where( 'product_id', $product_id )->first();
	if ( $rate == null ) {
		return 0;
	} else {
		$ratings = StoreProductReview::where( 'product_id', $product_id )->get();
		$sum     = 0;
		$count   = sizeof( $ratings );
		foreach ( $ratings as $rating ) {
			$sum = $sum + $rating->rating;
		}

		return ( $sum / $count );
	}
}

function getRatingOfUserById( $user_id, $product_id ) {
	$review = StoreProductReview::where( 'product_id', $product_id )->where( 'owner_id', $user_id )->first();
	if ( isset( $review->id ) ) {
		return $review;
	} else {
		return 0;
	}
}

function isStoreBrand( $brand_id ) {
	$brand = User::select( [ 'user_type' ] )->select( 'id' )->where( 'id', $brand_id )->orWhere( 'username', $brand_id )->first();
}

function getUserIpAddress() {
	// Get user IP address
	if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
	}

	$ip = filter_var( $ip, FILTER_VALIDATE_IP );

	return $ip = ( $ip === false ) ? '0.0.0.0' : $ip;
}

function getUserGender()
{
	$user = \App\Consumer::select('id', 'gender')->where('id', Auth::user()->userable_id)->first();
	if (isset($user->gender)) {
		return $user->gender;
	}
	return 0;
}

function getUserAge() {
	$userDob = \App\Consumer::select( 'id', 'birthdate' )->where( 'id', Auth::user()->userable_id )->first();

	if (isset($userDob->birthdate)) {
		return Carbon::now()->toDateString() - $userDob->birthdate;
	}

	return 0;

}

function getUserAgeCarbon() {
	return \App\Consumer::select( 'birthdate' )->where( 'id', Auth::user()->userable_id )->first();
}

//====================== End of Zahid code =====================

//=======================Ubaid code ============================


//====================== End of Ubaid code =====================

//=======================Mustabeen code ============================

function getCatByID( $id ) {
	// User id => $id
	$owner = getUserDetail( $id );

	$category_ids = StoreProduct::where( 'owner_id', $owner['id'] )
                                    ->whereNull('deleted_at')
                                    ->where('quantity','!=',0)
									->orderByRaw( "RAND()" )
									->take( 5 )
									->distinct()
									->lists('category_id','category_id');
	if($category_ids->isEmpty()){
		return $category_ids;
	}else{
		return Category::whereIn('id',$category_ids)->get();
	}
}

function getSubByCatID( $id ) {
	return Category::where( 'category_parent_id', $id )->orderBy( "name", 'ASC' )->get();
}

function myBrands( $take = null ) {
	if ( is_null( $take ) ) {
		$take = 6;
	}
	$userFollowingBrandIds = DB::table( 'brand_memberships' )
	                           ->where( 'user_approved', 1 )
	                           ->where( 'brand_approved', 1 )
	                           ->where( 'user_id', Auth::user()['id'] )
	                           ->lists( 'brand_id' );
	if ( count( $userFollowingBrandIds ) > 0 ) {
		return $brands = User::where( 'user_type', \Config::get( 'constants.BRAND_USER' ) )
		                     ->with( 'brand_detail' )
		                     ->whereIn( 'id', $userFollowingBrandIds )
		                     ->orderByRaw( "RAND()" )->take( $take )->get();
	}

	return false;
}

function recomendedBrands( $take = null ) {
	if ( is_null( $take ) ) {
		$take = 6;
	}
	$userFollowingBrandIds = DB::table( 'brand_memberships' )
	                           ->where( 'user_approved', 1 )
	                           ->where( 'brand_approved', 1 )
	                           ->where( 'user_id', Auth::user()['id'] )
	                           ->lists( 'brand_id' );

	return $brands = User::where( 'user_type', \Config::get( 'constants.BRAND_USER' ) )
	                     ->whereNotIn( 'id', $userFollowingBrandIds )
	                     ->with( 'brand_detail' )
	                     ->take( $take )->get();

	return false;
}

function getProductDetailsByID( $product_id ) {
	return DB::table( 'store_products' )->where( 'id', $product_id )->first();
}

function isStoreHaveProducts( $store_name ) {
	$storeOwner = getUserDetail($store_name);
	if(!isset($storeOwner->id)){
		return 0;
	}
	$product = DB::table( 'store_products' )->where( 'owner_id', $storeOwner->id )->first();

	if(isset($product->id)){
		return 1;
	}else{
		return 0;
	}
}

function getBrandDetailsByProductID( $product_id ) {
	$brand = DB::table( 'store_products' )->where( 'id', $product_id )->first();

	return DB::table( 'users' )->where( 'id', $brand->owner_id )->first();
}

function CheckIfReviewAlreadyGiven( $product_id, $user_id ) {
	$review = DB::table( 'store_product_reviews' )->where( 'product_id', $product_id )->where( 'owner_id', $user_id )->first();
	if ( $review == [ ] ) {
		return 1;
	} else {
		return 0;
	}
}

function getOrderAllProducts( $order_id = 0 ) {
	return $orderAllProductsIds = StoreOrderItems::where( 'order_id', $order_id )->get();
}

function getRegionName( $countryId ) {
	$country = DB::table( 'countries' )->select( 'nicename' )->where( 'id', $countryId )->first();

	if(isset($country->nicename)){
		return $country->nicename;
	}
	return '';
}

function getCountryIso( $countryId ) {
	$country = DB::table( 'countries' )->select( 'iso' )->where( 'id', $countryId )->first();

	if(isset($country->iso)){
		return $country->iso;
	}
	return '';
}

function getRegionId( $countryId ) {
	$country = DB::table( 'countries' )->select( 'region' )->where( 'id', $countryId )->first();

	if(isset($country->region)){
		$regionName = strtolower($country->region);
		$region = StoreShippingRegion::where('name', $regionName)->first();

		if(isset($region->id)){
			return $region->id;
		}
	}
	return '';
}

function getReviewStatusForBuyer( $review=null, $storeName=null, $order_id=null , $product_id=null) {
	$data['class']        = '';
	$data['status']       = '';
	$data['action_btn_1'] = '';
	$data['action_btn_2'] = '';
	$data['popUpHtml']    = '';

	if(!isset($review->id)){
		$data['class']        = 'not_reviewd';
		$data['status']       = '';
		$data['action_btn_1'] = 'Awaiting for Feedback<br /><a class="blueBtn" id="" href="#giveFeedbackInfo_'.$order_id.$product_id.'">Give Feedbacks</a>';
		$data['action_btn_2'] = '';
		$data['popUpHtml'] = giveFeedbackFormHtml($order_id, $product_id, $storeName);
	}

	if(isset($review->id)){
		if($review->is_revise_request == 1 AND $review->rating < 5){
			$data['class']        = 'reviewed_not_revised';
			$data['status']       = '';
			$data['action_btn_1'] = 'Active<br /><a class="greyBtn" id="" href="#reviseFeedbackInfo_'.$order_id.$product_id.'">Revise Feedback</a>';
			$data['action_btn_2'] = '';
			$data['popUpHtml'] = reviseFeedbackFormHtml($order_id, $review, $storeName, $product_id);
		}else{
			$data['class']        = 'reviewed';
			$data['status']       = '';
			$data['action_btn_1'] = 'Active';
			$data['action_btn_2'] = '';
			$data['popUpHtml'] ='';
		}
	}

	return $data;
}

function getReviewStatusForSeller( $review, $orderBuyer, $storeName, $order_id, $product_id=null  ){
	$data['class']        = '';
	$data['status']       = '';
	$data['action_btn_1'] = '';
	$data['action_btn_2'] = '';
	$data['popUpHtml']    = '';

	if(!isset($review->id)){
		$data['class']        = 'not_reviewd';
		$data['status']       = 'Awaiting for Feedback';
		$data['action_btn_1'] = '';
		$data['action_btn_2'] = '<a class="blueBtn" id="sendFeedbackReminder_'.$order_id.$product_id.'" href="javascript:void(0);">Send Reminder</a>';
		$data['popUpHtml'] = sendFeedbackReminder($order_id, $product_id, $storeName);
	}

	if(isset($review->id)){
		if($review->is_revise_request == 0 AND $review->rating < 5){
			$data['class']        = 'reviewed_not_revised';
			$data['status']       = 'Active';
			$data['action_btn_1'] = '<a class="greyBtn" id="" href="'.url("store/".$storeName."/admin/send-request-revise-feedback/".$review->id).'">Request to Revise</a>';
			$data['action_btn_2'] = '';
		}else
			if($review->is_revise_request == 1 AND $review->is_revised == 0){
			$data['class']        = 'request_sent';
			$data['status']       = 'Request Sent';
			$data['action_btn_1'] = '';
			$data['action_btn_2'] = '';
		}else{
			$data['class']        = 'reviewed';
			$data['status']       = 'Active';
			$data['action_btn_1'] = '';
			$data['action_btn_2'] = '';
		}
	}

	return $data;
}

function confirmationOfOrderReceivedBuyerFormHtml($order_id){
	$html = '
<div id="confirmationOfOrderReceived_' .$order_id . '" class="cssPopup_overlay">
	<div class="cssPopup_popup" style="width:430px;">
	 	<a class="cssPopup_close" href="#">&times;</a>
<div class="sd-popup">
                <div class="sdp-header">Confirmation Of Order Received</div>
                <div class="sd-orderId">Order ID: ' .getOrderNumber($order_id) . '</div>
                ' . Form::open( [ 'url'     => url( ),
					"id"      => "confirmationOfOrderReceived_".$order_id,
					"class"      => "form-container wA",
					"enctype" => "multipart/form-data"
			] ) . '
                <p>If you have issue with received goods, you can request refund now.</p>
                    <a href="javascript:void(0);" class="blueBtn order_status_btn order_action_brn_' . $order_id . ' confirm_order_btns" id="approve_6_order_' . $order_id . '">Order Received</a>
                    <a href="javascript:void(0);" class="greyBtn confirm_order_btns" id="disputeBtn_'.$order_id.'">Request Refund</a>
                    <a href="#" class="blueBtn confirm_order_btns">Cancel</a>
                </form>
            </div>
</div>
</div>
<script>
        $("#disputeBtn_'.$order_id.'").click(function(evt){
            evt.preventDefault();
            var urlToSubmit = "'.url("store/order/dispute/".$order_id).'";
        	window.location.href = urlToSubmit;
		});
</script >
            ';
	return $html;
}

function cancelOrderBuyerFormHtml($order_id){
	$html = '
<div id="orderCancelationInfo_' .$order_id . '" class="cssPopup_overlay">
	<div class="cssPopup_popup" style="width:430px;">
	 	<a class="cssPopup_close" href="#">&times;</a>
<div class="sd-popup">
                <div class="sdp-header">Order Cancellation</div>
                <div class="sd-orderId">Order ID: ' .getOrderNumber($order_id) . '</div>
                ' . Form::open( [ 'url'     => url( ),
	                  "id"      => "cancelOrderForm_".$order_id,
	                  "class"      => "form-container wA",
	                  "enctype" => "multipart/form-data"
	] ) . '
                <p>If you have made payment for this order but not arrived to Kinnect2 yet, please do not cancel this order.</p>
                    <input type="hidden" name="order_id" value = "'.$order_id.'" />
                    <label>*Select a reason For cancellation:</label>
                    <div class="field-item">
                        <select name="reason">';
							$cancel_reasons = \Config::get('constants_brandstore.ORDER_CANCEL_REASONS');
							foreach ($cancel_reasons as $key => $val){
								$html .= '<option value="'.$key.'">'.$val.'</optio>';
							}
						$html .= '</select>';
                $html .= '</div>
                    <input type="button" class="btn" value="Submit" id="cancelOrderBtn_'.$order_id.'" />
                </form>
            </div>
</div>
</div>
<script>
        $("#cancelOrderBtn_'.$order_id.'").click(function(evt){
            evt.preventDefault();
        $.ajax({type:"POST", url: "'.url( "store/cancelOrder/".$order_id ).'", data:$("#cancelOrderForm_"+'.$order_id.').serialize(), success: function(data) {
        		
        		if(data.status == "success"){
   					$(".order_action_brn_"+data.order_id).remove();
                	$(".order_action_"+data.order_id).html(data.action_btn_1 + data.action_btn_2);
                	$(".order_status_"+data.order_id).html(data.status);
                	$("#orderCancelationInfo_'.$order_id.'").remove();
                }else{
                	jQuery("#error_mesage_"'.$order_id.').text(data.message_text).show().css("color","#FF0000");
                }

}, error: function(data){alert("error: "+data);}
});
});
        </script >
            ';
	return $html;
}

function cancelOrderSellerFormHtml($order_id, $storeName){
	$html = '
<div id="orderCancelationInfo_' .$order_id . '" class="cssPopup_overlay">
	<div class="cssPopup_popup" style="width:430px;">
	 	<a class="cssPopup_close" href="#">&times;</a>
<div class="sd-popup">
                <div class="sdp-header">Order Cancellation</div>
                <div class="sd-orderId">Order ID: ' .getOrderNumber($order_id) . '</div>
                ' . Form::open( [ 'url'     => url( ),
					"id"      => "cancelOrderForm_".$order_id,
					"class"      => "form-container wA",
					"enctype" => "multipart/form-data"
			] ) . '
                <p>Please note if you cancel an order that was paid, the purchase amount will be credited to buyer.</p>
                    <input type="hidden" name="order_id" value = "'.$order_id.'" />
                    <label>*Select a reason For cancellation:</label>
                    <div class="field-item">
                        <select name="reason">';
							$cancel_reasons = \Config::get('constants_brandstore.ORDER_CANCEL_REASONS');
							foreach ($cancel_reasons as $key => $val){
								$html .= '<option value="'.$key.'">'.$val.'</optio>';
							}
							$html .= '</select>
                    </div>
                    <div>
                    	<p id="error_mesage_'.$order_id.'" style="display:none;color;#f00000;"></p>
                    </div>
                    <input type="button" class="btn" value="Submit" id="cancelOrderBtn_'.$order_id.'" />
                </form>
            </div>
</div>
</div>
<script>
        $("#cancelOrderBtn_'.$order_id.'").click(function(evt){
            evt.preventDefault();
        $.ajax({type:"POST", url: "'.url( "store/".$storeName."/cancelOrder/".$order_id ).'", data:$("#cancelOrderForm_"+'.$order_id.').serialize(), success: function(data) {
        	
  			if(data.status == "success"){
  				$(".order_action_brn_"+data.order_id).remove();
                $(".order_action_"+data.order_id).html(data.action_btn_1 + data.action_btn_2);
                $(".order_status_"+data.order_id).html(data.status);
                $("#orderCancelationInfo_'.$order_id.'").remove();
            }else{
            	jQuery("#error_mesage_"+'.$order_id.').text(data.message_text).show().css("color","#FF0000");
            }

}, error: function(data){alert("error: "+data);}
});
});
        </script >
            ';
	return $html;
}
function countryNameById($id) {
	$country = DB::table('countries')->where('id', $id)->first();

	if(isset($country->name)){
		return $country->name;
	}

	return '';
}

//====================== End of mustabeen code =====================


//====================== Start of yasir's code =====================

function getPhotoUrlByFile( $file ) {
	if(isset($file->storage_path)){
		return $path = url() . '/local/storage/app/photos' . "/" . $file->storage_path;
	}

	return '';
}

function dispute_status( $status, $user_type)  {
	if ( is_null( $status ) ) {
		if($user_type == Config::get('constants.REGULAR_USER')){
			return 'You request a refund for this order. Please wait for the supplier to respond.';
		}
		return 'Buyer request a refund against this order';
	} else {
		return Config::get( 'constants_brandstore.DISPUTE_STATUS_STRING.' . $status );
	}
}
function timeRemainingPopUp($time, $order_id){
	return '<div id="courierServiceInfo_'.$order_id.'" class="cssPopup_overlay">
        <div class="cssPopup_popup">
            <a class="cssPopup_close" href="#">&times;</a>

            <div class="courierServiceInfoWrap">

                <div class="addProduct">
                    <h1>Time Remaining</h1>


                    <div id="delivery_info_form_wrap" class="selectdiv delivery_info_form_wrap">

						You cannot Open a dispute against your order. Order reached you '.$time.'.
                    </div>
                </div>
            </div>
        </div>
    </div>';
}

function getOrderAllProductsDetail($order_id = 0) {
	$orderAllProductsIds = StoreOrderItems::where('order_id', $order_id)->lists('product_id');
	return $orderAllProducts = StoreProduct::whereIn('id', $orderAllProductsIds)->get();
}
//====================== End of yasir's code =====================
function getAvailableBalance($user_id){
	$srObj = new \kinnect2Store\Store\Repository\StoreRepository();
	return $srObj->getAvailableBalance($user_id);
}
function getPendingOrders($user_id){
	$order_status = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_PAYMENT_VERIFIED');
	$soObj = new \kinnect2Store\Store\StoreOrder();
	return $soObj->where('seller_id',$user_id)->where('status',$order_status)->count();
}
function getBrandInfo($brand_id){
	return User::where('id',$brand_id)->select(['displayname','username'])->first();
}

function countRequestToReviseCurrentUser()
{
	$productReviewCount = 0;

	$usder_id = Auth::user()->id;
	$orderIds = StoreOrder::where('status', \Config::get("constants_brandstore.ORDER_STATUS.ORDER_DELIVERED"))->where('customer_id', $usder_id)->lists('id');

	$orderProductsIds = StoreOrderItems::whereIn('order_id', $orderIds)->groupBy("product_id")->lists('product_id');

	foreach ($orderProductsIds as $orderProductsId) {
		$review = StoreProductReview::where('product_id', $orderProductsId)->where('owner_id', $usder_id)->first();
		if (isset($review->id)) {
			if ($review->is_revised == 0 AND $review->is_revise_request == 1) {
				$productReviewCount++;
			}
		} else {
			$productReviewCount++;
		}
	}
	return $productReviewCount;
}
function getProductAttribute($id){
	
	return \kinnect2Store\Store\StoreProductAttribute::where('id',$id)->first();
}
function getStoreItemAttributes($order_item_id){
	return \kinnect2Store\Store\StoreOrderItemAttribute::where('store_order_item_id',$order_item_id)->lists('store_product_attribute_id','store_product_attribute_id');
}
