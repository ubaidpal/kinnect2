<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 1/6/2016
 * Time: 9:31 PM
 */
namespace kinnect2Store\Store\Repository\admin;

use kinnect2Store\Store\DeliveryCourier;
use kinnect2Store\Store\StoreDeliveryAddress;
use kinnect2Store\Store\StoreOrder;
use kinnect2Store\Store\StoreOrderDispute;
use kinnect2Store\Store\StoreOrderItems;
use kinnect2Store\Store\StoreOrderStatusLog;
use kinnect2Store\Store\StoreOrderTransaction;
use kinnect2Store\Store\StoreProductReview;
use kinnect2Store\Store\StoreTransaction;
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


class StoreAdminOrderRepository {
	protected $store;

	protected $data;
	protected $user_id;
	protected $is_api;

	/**
	 *
	 */
	public function __construct() {

		$this->is_api = UrlFilter::filter();
		if ( $this->is_api ) {
			$this->user_id = Authorizer::getResourceOwnerId();
			@$this->data->user = User::findOrNew( $this->user_id );
		} else {
			if ( Auth::check() ) {
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
		return $allOrders = StoreOrder::where('seller_id', $this->user_id)->orderBy('id', 'DESC')->get();
	}
	public function paginateUserOrders($user_id,$status = null){

		$query = StoreOrder::where('seller_id', $this->user_id)->orderBy('id', 'DESC');
		if($status != null){
			$query->whereIn('status',$status);
		}
		return $query->paginate(10);
	}
	public function getOrderById($order_id)
	{
		return $order = StoreOrder::where('id', $order_id)->first();
	}

	public function getOrderAllProducts($order_id = 0) {
		$orderAllProductsIds = StoreOrderItems::where('order_id', $order_id)->lists('product_id');
		return $orderAllProducts = StoreProduct::whereIn('id', $orderAllProductsIds)->get();
	}

	public function getOrderProductItemQuantity($order_id = 0, $product_id) {
		return $getOrderProductItemQuantity = StoreOrderItems::select('quantity')
			->where('order_id', $order_id)
			->where('product_id', $product_id)
			->first();
	}

	public function paymentReceivedInfo() {
		return $allOrders = StoreOrderTransaction::where('user_id', $this->user_id)->get();

	}
	public function getOrderPaymentByOrderId($order_id) {
		return $orderPayment = StoreOrderTransaction::where('order_id', $order_id)->get();
	}

	public function getOrderStatus($order_id) {
		return StoreOrder::select('status')->where('id', $order_id)->first();
	}
	public function getOrderAddressesByOrderId($id)
	{
		return StoreDeliveryAddress::where('id', $id)->paginate(5);
	}
	public function getOrderCourierByOrderId($order_id)
	{
		return DeliveryCourier::where('order_id', $order_id)->first();
	}

	public function updateOrderStatus($order_id, $status, $subject,$is_refunded = 0, $refund_amount = 0) {

		$is_authorized = $this->is_authorized_to_change_status($status, $subject);
		
		if($is_authorized === 0){
			return 0;
		}

		$order = StoreOrder::find($order_id);

		if(isset($order->id)){

			$orderStatusFrom   = $order->status;
			$orderStatusTo     = $status;

			if($order->status > 0){

				$nowTimeCarbon = Carbon::now()->toDateTimeString();
				if(\Config::get( "constants_brandstore.ORDER_STATUS.ORDER_AWAITING_SHIPMENT" ) == $status){
					$order->approved_date = $nowTimeCarbon;
				}

				if(\Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DISPATCHED" ) == $status){
					$order->shiping_date = $nowTimeCarbon;
				}

				if(\Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DELIVERED" ) == $status){
					$order->received_date = $nowTimeCarbon;
				}

				if($is_refunded > 0) {
					$order->is_refunded = $is_refunded;
				}
				if($refund_amount > 0){
					$order->refund_amount = $refund_amount;
				}

				$order->status = $status;
				$order->updated_by = $subject;
				$order->save();
			}

			$user_id = Auth::user()->id;
			$ip = $this->GetUserIpAddress();

			$this->addOrderStatusLog($user_id, $ip, $orderStatusFrom, $orderStatusTo);
			return 1;
		}
		return 0;
	}

	public function is_authorized_to_change_status($status, $subject) {
		if($subject === 'buyer'){
			$valid_status['buyer'] = [ 0, 1, 6];
			if(in_array($status, $valid_status['buyer'])){
				return 1;
			}
			return 0;
		}

		if($subject === 'seller'){
			$valid_status['seller'] = [ 0, 4, 5];
			if(in_array($status, $valid_status['seller'])){
				return 1;
			}
			return 0;
		}
		if($subject == 'system'){
			$deliverd = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_DELIVERED');
			if($status == $deliverd){
				return 1;
			}
			return 0;
		}

	}
	public function addDisputeRecord($request){


		$order_dispute = new StoreOrderDispute();

		$order_dispute->receive_order    = $request->order_receive;
		$order_dispute->payment_claimed  = $request->full_refund;
		$order_dispute->reason           = $request->reason;
		$order_dispute->detail           = $request->detail;
		$order_dispute->order_id         = 2;//reference order id to be changed
		$order_dispute->owner_id         = Auth::user()->id;
		$order_dispute->save();

		if(isset($order_dispute->id)){

			$album              = new Album();

			$album->title       = 'order dispute';
			$album->description = $order_dispute->title."'s album'";
			$album->owner_type  = 'order_dispute';
			$album->owner_id    = $order_dispute->id;
			$album->category_id = 0;
			$album->type        = 'order dispute attachment';
			$album->photo_id    = 0;

			$album->save();
			//end of album creation

			$fileIds = explode(",", $request->myImageIds);
			foreach($fileIds as $fileId)
			{
				$file = StorageFile::where('file_id', $fileId)->first();

				$file_name = time().rand(111111111,9999999999);

				$folder_path = "local/storage/app/photos/".Auth::user()->id;
				$file_name_new = Auth::user()->id."_".$file_name.".".$file->extension;

				if(isset($file->file_id) ){

					if(file_exists("local/storage/app/photos/".$file->storage_path) == true) {

						if ( ! file_exists( $folder_path ) ) {
							if ( ! mkdir( $folder_path, 0777, true ) ) {
								$folder_path = '';
							}
						}

						rename( "local/storage/app/photos/".$file->storage_path, $folder_path . "/" . $file_name_new );
					}

					// Saving photos
					$photoObj = new AlbumPhoto();

					$photoObj->owner_type = 'order_dispute';
					$photoObj->owner_id = $order_dispute->id;
					$photoObj->file_id = $file->file_id;
					$photoObj->title = $order_dispute->title;
					$photoObj->album_id = $album->album_id;

					if($photoObj->save()){
						$file->parent_id = $photoObj->photo_id;//photo_id
						$file->user_id = $order_dispute->owner_id;
						$file->storage_path = $order_dispute->owner_id."/".$file_name_new;
						$file->name = $file_name;
						$file->mime_major = 'image';

						$file->save();

						$imageFilePath = $order_dispute->owner_id."/".$file_name_new;

						/*$this->resizeProductImage($imageFilePath, $file->file_id, $file->user_id, 'product', 'product_profile', '151', '210');
						$this->resizeProductImage($imageFilePath, $file->file_id, $file->user_id, 'product', 'product_thumb', '170', '170');
						$this->resizeProductImage($imageFilePath, $file->file_id, $file->user_id, 'product', 'product_icon', '54', '80');*/
					}

				}

			}

			return $order_dispute->id;
		}

		return 0;
	}

	public function isOrderCustomer($order_id, $customer_id) {
		$cusdtomerOrder = StoreOrder::select('id')->where('id', $order_id)->where('customer_id', $customer_id)->first();
		if(isset($cusdtomerOrder->id) ){
			return $cusdtomerOrder->id;
		}
		return 0;
	}

	public function isOrderSeller($order_id, $seller_id) {
		$sellerOrder = StoreOrder::select('id')->where('id', $order_id)->where('seller_id', $seller_id)->first();
		return $sellerOrder->id;
	}

	public function addOrderStatusLog($user_id = '', $ip = '', $status_changed_from = '', $status_changed_to = '') {

		$statusLog = new StoreOrderStatusLog();

		$statusLog->user_id = $user_id;
		$statusLog->ip = $ip;
		$statusLog->status_changed_from = $status_changed_from;
		$statusLog->status_changed_to = $status_changed_to;

		$statusLog->save();
	}

	public function GetUserIpAddress (  ) {
		// Get user IP address
		if ( isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
		}

		$ip = filter_var($ip, FILTER_VALIDATE_IP);
		return $ip = ($ip === false) ? '0.0.0.0' : $ip;
	}

	public function softDeleteOrder($order_id) {
		$order = StoreOrder::find($order_id);

		if(isset($order->id)){
			$order->is_deleted = 1;

			$order->save();

			return $order->id;
		}

		return 0;
	}

	public function searchMyOrders( $order_number, $product_name )
	{

		 $allOrders = \DB::table('store_orders')
				->select('store_orders.id', 'order_number', 'store_orders.customer_id', 'store_orders.seller_id', 'delivery_address_id', 'payment_type', 'status', 'is_deleted', 'total_price', 'total_shiping_cost', 'total_discount', 'total_quantity', 'store_orders.created_at', 'approved_date', 'shiping_date', 'received_date')
				->join('store_order_items', 'store_order_items.order_id', '=', 'store_orders.id')
				->join('store_products', 'store_products.id', '=', 'store_order_items.product_id')
				->where('store_orders.order_number', 'like', $order_number.'%')
				->where('store_orders.seller_id', '=', $this->user_id)
				->where('store_orders.is_deleted', '!=', 1)
				->where('store_products.title', 'like', "%".$product_name.'%')
				->get();
		$html = '';

		if(count($allOrders) == 0){
			return $html = '<h1 style="margin-top:20px;" id="nothing_found">Nothing found try again.</h1>';
		}

		foreach($allOrders as $order):
			$html .='
			<div class="orderb-item orderBox order_item_'.$order->id.'">
				<div class="product-header ph-pset">';
							$orderAllProducts = getOrderAllProducts($order->id);
						$html .='<div class="oi-header">
	<div class="oi-image">';
		foreach($orderAllProducts as $orderProduct):
			$product = getProductDetailsByID($orderProduct->product_id);//Complete detail of product
		if (!isset($product->id)) {
			continue;
		}
		$discountedPercented = ($orderProduct->product_price / 100 ) * $orderProduct->product_discount ;
		$oi_price =format_currency($orderProduct->product_price - $discountedPercented);

		$html .= '<div class="oi-product">
                                        <div class="oi-product-item pdt">

			<a class="product-img" href="'.getProductUrlByIdAndOwnerId($orderProduct->product_id, $product->owner_id).'">';
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
			<div class="oi-product-item order_product_qty_'.$order->id.'">'.$orderProduct->quantity.'</div>
			<div class="oi-product-item opi-txtb">$'.$oi_price.'</div>
			</div>';
		endforeach;

	$html .= '</div>
</div>
<div class="oi-footer">
	<div class="oi-detail">
		<p class="mb5">';
			$storeName = getUserNameByUserId($product->owner_id);
			$buyer     = getUserDetail($order->customer_id);
			$html .='Order ID: '.$order->order_number.' <a
					href="'.url('store/'.$storeName.'/admin/order-invoice/'.$order->id).'">View
				Detail</a>
		</p>

		<p>
			Order time & date: '.$order->created_at.'
		</p>
	</div>
	<div class="oi-profile w230x">';
		if(isset($product->owner_id)):

		$html .= '<p class="mb5">
			Buyer Name: <a
					target="_blank"
					href="'.profileAddress( $buyer ).'">'.$buyerName = ucfirst($buyer->displayname).'</a>
		</p>';
		endif;
	$html .='</div>';
	$productPrice = $order->total_price - $order->total_discount;
	$productPrice = format_currency($productPrice);
	//$productPrice = "$" . $productPrice;
			$html .='
			<div class="oi-amount-container">
				<div class="oi-ship-cost">
					<div class="oi-sc-txt">Shipping Cost:</div>
					<div class="oi-sc-value">$'.format_currency($order->total_shiping_cost).'</div>
				</div>
				<div class="oi-amount-total">
					<div class="oi-amount-t-txt">Order Amount:</div>
					<div class="oi-amount-t-value">&dollar;'.$productPrice.'</div>
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
		$data = getOrderStatusForSeller($order->id, $order->status);

	}
	$html .= '<div class="oi-action order_action_'.$order->id.' '.$data['class'].'">'.$data['action_btn_1'].$data['action_btn_2'] ;
			$html .='</div>
	<div class="oi-status order_status_'.$order->id.'"><span>Order Status</span>'.$data['status'].'</div>
</div>
</div>';

			endforeach;
		return $html;
	}
	public function searchMyReviews( $order_number, $product_name )
	{
		 $allOrders = \DB::table('store_orders')
				->select('store_orders.id', 'order_number', 'store_orders.customer_id', 'store_orders.seller_id', 'delivery_address_id', 'payment_type', 'status', 'is_deleted', 'total_price', 'total_shiping_cost', 'total_discount', 'total_quantity', 'store_orders.created_at', 'approved_date', 'shiping_date', 'received_date')
				->join('store_order_items', 'store_order_items.order_id', '=', 'store_orders.id')
				->join('store_products', 'store_products.id', '=', 'store_order_items.product_id')
				->where('store_orders.order_number', 'like', $order_number.'%')
				->where('store_orders.seller_id', '=', $this->user_id)
				->where('store_orders.is_deleted', '!=', 1)
				->where('store_orders.status', '=', \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DELIVERED" ))
				->where('store_products.title', 'like', $product_name.'%')
				->get();
		$html = '';

		if(count($allOrders) == 0){
			return $html = '<h1 style="margin-top:20px;" id="nothing_found">Nothing found try again.</h1>';
		}

		foreach($allOrders as $order):
			$html .='<div class="orderb-item order_item_{{$order->id}}">
                ';
			$orderAllProducts = getOrderAllProducts($order->id);
			$orderBuyer = getUserDetail( $order->customer_id );
			$html.='
<div class="oi-header">
	<div class="oi-image">';
			foreach($orderAllProducts as $orderProduct):
				$product = getProductDetailsByID($orderProduct->product_id);//Complete detail of product

				$review = getRatingOfUserById( $order->customer_id, $product->id );
				$storeName = getUserNameByUserId($product->owner_id);

				$html .='<div class="oi-product">
			<a  class="product-img" href="'.getProductUrlByIdAndOwnerId($orderProduct->product_id, $product->owner_id).'">';
				$imageThumb = getThumbSrcWithProductId($product->id, 'product_thumb') ;
				$html.='<img width="100" height="100" src="'.$imageThumb.'" alt="IMAGE"></a>
			<div class="oi-title">'.$product->title.'</div>
		</div>';
			endforeach;

			if (!isset($product->id)) {
				$data['class']        = '';
				$data['action_btn_1'] = '';
				$data['action_btn_2'] = '';
				$data['status']       = 'Product Deleted';
				$productPrice         = '';
			} else {
				$data         = getReviewStatusForSeller( $review, $orderBuyer, $storeName, $order->id, $product->id );
				$productPrice = "$" . $order->total_price;
			}

			$html .='</div>
	<div class="oi-action order_action_'.$order->id.$data['class'].'">'.
					$data['action_btn_1'] . $data['action_btn_2'].'
	</div>
	<div class="oi-status order_status_'.$order->id.'">'.$data['status'].'</div>
	<div class="oi-amount"><p class="oi-price">'.$productPrice.'</p></div>
</div>

<div class="oi-footer">
	<div class="oi-detail">
		<p class="mb5">
			Order ID: '.$order->order_number.' <a href="'.url('order-invoice/'.$order->id).'">View
				Detail</a>
		</p>
		<p>
			Order time & date: '.$order->created_at.'
		</p>
	</div>
	<div class="oi-profile">
		<p class="mb5">
			';
			if(isset($product->owner_id)){

				$html	.= 'Store Name: ';

				$html .='<a
					target="_blank"
					href="';
				url('store/'.$storeName);
				$html .= '"> '.$storeName = ucfirst($storeName).'</a>
		</p>

		<p>
			<a href="'.url('brand/'.$storeName).'">View Profile</a>
		</p>
		';
				if(isset($order->delivery)){
					$html .='<b class="mb5">
			Time Remaining: '.date_difference_human($order->delivery->date_to_be_delivered).'
		</b>';
				}
			}
			$html .='</div>
</div>
</div>';
		endforeach;
		return $html;
	}

	public function countOrdersStatusWise($seller_id)
	{
		$data = [];
		$order_status = \Config::get( 'constants_brandstore.ORDER_STATUS');
		$data['All'] = StoreOrder::where('seller_id',$seller_id)->where('is_deleted',0)->count();
		foreach ($order_status as $key => $value){
			if($key == 'ORDER_AWAITING_PAYMENT' || $key == 'ORDER_PAYMENT_BEING_VERIFIED'){
				continue;
			}
			if($key == 'ORDER_DISPUTED' || $key == 'ORDER_DISPUTED_CANCELLED' || $key == 'ORDER_DISPUTED_REJECTED' || $key == 'ORDER_DISPUTE_ACCEPTED'){
				if(isset($data['ORDER_DISPUTED'])){
					$data['ORDER_DISPUTED'] += StoreOrder::where('status', $value)->where('seller_id', $seller_id)->where('is_deleted', 0)->count();
				}else {
					$data['ORDER_DISPUTED'] = StoreOrder::where('status', $value)->where('seller_id', $seller_id)->where('is_deleted', 0)->count();
				}
			}else {
				$data[$key] = StoreOrder::where('status', $value)->where('seller_id', $seller_id)->where('is_deleted', 0)->count();
			}
		}

		return $data;
	}
}
