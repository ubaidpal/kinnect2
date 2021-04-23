<?php

namespace kinnect2Store\Store\Http;

use App\Country;
use App\Events\SendEmail;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use app\Http\Requests;
use App\StorageFile;
use App\User;
use Illuminate\Support\Facades\Auth;
use kinnect2Store\Store\StoreOrder;
use kinnect2Store\Store\StoreProduct;
use kinnect2Store\Store\StoreProductStat;
use Vinkla\Hashids\Facades\Hashids;
use Session;
use App\ActivityAction;

class StoreController extends Controller {
	protected $storeRepository;
	protected $storeAdminRepository;
	protected $storeOrderRepository;
	protected $storeAdminOrderRepository;
	protected $storeProductStatRepository;

	/**
	 * @param \kinnect2Store\Store\Repository\StoreRepository      $storeRepository
	 * @param \kinnect2Store\Store\Repository\StoreAdminRepository $storeAdminRepository
	 * @param Request                                              $middleware
	 */
	public function __construct(
		\kinnect2Store\Store\Repository\StoreRepository $storeRepository,
		\kinnect2Store\Store\Repository\admin\StoreAdminRepository $storeAdminRepository,
		\kinnect2Store\Store\Repository\StoreProductStatRepository $storeProductStatRepository,
		\kinnect2Store\Store\Repository\StoreOrderRepository $storeOrderRepository,
		\kinnect2Store\Store\Repository\admin\StoreAdminOrderRepository $storeAdminOrderRepository,
		Request $middleware
	) {
		$this->storeRepository      = $storeRepository;
		$this->storeAdminRepository = $storeAdminRepository;
		$this->storeProductStatRepository = $storeProductStatRepository;
		$this->storeOrderRepository = $storeOrderRepository;
		$this->storeAdminOrderRepository = $storeAdminOrderRepository;
		$this->user_id = Auth::user()->id;
		/* $this->user_id = $middleware['middleware']['user_id'];
		 @$this->data->user = $middleware['middleware']['user'];
		 $this->is_api = $middleware['middleware']['is_api'];*/
	}

	/**
	 * @param null $brand_id
	 *
	 * @return mixed
	 */
	public function index( $brand_id = null ) {

		$brand = $this->storeRepository->isStoreBrand( $brand_id );
		if ( $brand['user_type'] == 2 ) {
			$data['url_user_id'] = $brand->id;

			$data['featuredProducts']    = $this->storeRepository->currentBrandFeaturedProducts( $brand->id );
			$data['bestSellingProducts'] = $this->storeRepository->currentBrandBestSellingProducts( $brand->id );
			$data['allProducts'] 	     = $this->storeRepository->currentBrandProducts( $brand->id, $data['featuredProducts'], $data['bestSellingProducts'] );

			$user_id = $this->user_id;
			$data['brand'] = $this->storeRepository->isStoreBrand( $user_id );

			//Adding profile view stat
			$this->storeRepository->addProfilePageStat($brand->id);

			return view( 'Store::index', $data );
		} else {
			return redirect()->back()->with( 'info', 'Record Saved Successfully.' );
		}

	}

	public function getProductDetail ($id, $product_id) {
		$user_id    = Auth::user()->id;
        $user_type  = Auth::user()->user_type;
        $country    = Auth::user()->country;
		$ip         = getUserIpAddress();
		$userGender = getUserGender();
		$userAge    = getUserAge();
		$brand = $this->storeAdminRepository->isStoreBrand($id);
		$data['isStoreOwner']   = $this->storeAdminRepository->is_product_owner($product_id);
		$data['storeName']   = $id;
		$data['productDetail']  = $product =   $this->storeAdminRepository->getProductDetail($product_id);
		$data["user"]["country"] = Country::where("id", $country)->first();

		if(!isset($product->id)){
			return redirect("store/".$id.'/no-product-found')->with('info', 'no product found.');
		}

		$data['key_feature']    =   $this->storeAdminRepository->key_feature($product_id);
		$data['tech_spechs']    =   $this->storeAdminRepository->tech_spechs($product_id);
		$data['reviews']        =   $this->storeAdminRepository->getReviews($product_id);
		$data['isAbleToReview'] =   $this->storeAdminRepository->isAbleToReview($user_id, $product_id);
		$data['isReviewed']     =   $this->storeAdminRepository->isReviewed($user_id, $product_id);
		
		$productAttributes = $data['productAttributes'] = $this->storeAdminRepository->getProductAttributes($product_id);
		$data['attributes'] = [];
		if($productAttributes != 0){
			foreach($productAttributes as $productAttribute){
				if(!empty($productAttribute->value)) {
					$data['attributes'][$productAttribute->attribute][] = $productAttribute;
				}
			}
		}

		$data['url_user_id']    =   $brand['id'];

		$data['product_post']    =  ActivityAction::where( 'object_id', $product_id )->where("type", "product_create")->first();

		//Add statics
		$this->storeProductStatRepository->addProductStat('view', $user_id, $user_type, $userAge, $userGender, $country, $ip, $product->owner_id, $product->id);

		return view("Store::products.storeProductDetail" ,$data);
	}

	public function getProductById ($id) {
		$product = $this->storeAdminRepository->getProductDetail($id);
		if(!isset($product->id)){
			return redirect("store/".$id.'/no-product-found')->with('info', 'no product found.');
		}
		$user = User::where("id", $product->owner_id)->first();
		return redirect('store/'.$user->username.'/product/'.$id.'/'.preg_replace('/\s+/', '-', $product->title) );
	}

	/**
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function  subCategoryProducts( Request $request ) {
		$data['url_user_id']  = $request->storeBrandId;
		$data['sub_category_id'] = $sub_category_id = $request->sub_category_id;
		$data['categoryName'] = $request->category_name;

		$data['parent_category_id'] = $this->storeRepository->getPrarentCategory( $sub_category_id );
		$data['allProducts'] = $allProducts = $this->storeRepository->subCategoryProducts( $sub_category_id );

		$user_id = $this->user_id;
		$data['brand'] = $this->storeRepository->isStoreBrand( $user_id );

		return view( 'Store::products.sub_category_products', $data )->with( 'info', 'Subcategory Products' );
	}
	public function getShippingInfo(Request $request) {
		

		$data['cartProductsCount'] = Session::get('cart.total_items');
		if($request->sellerBrandId != "buy-all"){
			$data['sellerBrandIdEncoded'] = $request->sellerBrandId;

			$data['sellerBrandId'] = Hashids::decode($request->sellerBrandId);
			$data['sellerBrandId'] = $data['sellerBrandId'][0];

			$data['cartProducts'][$data['sellerBrandId']]  = $this->storeRepository->getCartProducts($data['sellerBrandId']);

		}else{
			$data['sellerBrandIdEncoded'] = $request->sellerBrandId;

			$data['sellerBrandId'] = $request->sellerBrandId;

			$data['cartProducts']= $this->storeRepository->getCartProducts();
		}

		if(count($data['cartProducts']) < 1){
			redirect('store/cart/your-session-expired');
		}

		$default       = [ '0' => 'Select Country' ];
		$countriesList = DB::table( 'countries' )->lists( 'name', 'id' );
		$countriesList = $default + $countriesList;

		$data['previousAddresses'] = $this->storeRepository->getAddressesOfUserById( $this->user_id );
		$address_id = $this->storeRepository->getCartDeliverAddress();
		$data['addressData'] = [];
		if($address_id){
			$data['addressData'] = $this->storeRepository->getDeliveryAddressByID($address_id);
		}
		return view( "Store::Cart.shippingAddress", $data )->with( 'countries', $countriesList );

	}


	/**
	 * @param $product_id
	 *
	 * @return mixed
	 */
	public function addCartProduct() {

		$product_id = Input::get('product_id');
		if ( $product_id ) {
            $quantity = \Request::get('quantity');
            $size_id  = \Request::get('productSizeId');
            $color_id = \Request::get('productColorId');

            $response = $this->storeRepository->updateCart($product_id, $quantity, $size_id, $color_id);
			$product_count = $this->storeRepository->getCartProductsCount();
			$response['total_items'] = [$product_count];
			return response()->json($response);
		}
	}
	public function cartProductQuantityCheck() {

		$product_id = Input::get('product_id');
		$qtyToCheck = Input::get('qtyToCheck');
		$record = StoreProduct::where('id', $product_id)->first();
		if($record->quantity < $qtyToCheck ){
			return 0;
		}else{
			return 1;
		}

	}

	/**
	 * @return mixed
	 */
	public function getCart() {
		//Not show cart to Admin of store
		if(Auth::user()->user_type == 2){
			return redirect("/store/".Auth::user()->username."/admin/orders");
		}
		$data['url_user_id'] = Auth::user()->username;
		$data['cartProducts'] = Session::get('cart.products');
		$data['countCartProducts'] = Session::get('cart.total_items');

		return view( 'Store::Cart.shoppingCart', $data );
	}

	/**
	 * @return mixed
	 */
	public function addShippingInfo( Request $request ) {

		$data['user_id'] = $this->user_id;

		if($request->sellerBrandId != "buy-all"){
			$data['sellerBrandIdEncoded'] = $request->sellerBrandId;

			$data['sellerBrandId'] = Hashids::decode($request->sellerBrandId);
			$data['sellerBrandId'] = $data['sellerBrandId'][0];
		}else{
			$data['sellerBrandIdEncoded'] = $request->sellerBrandId;

			$data['sellerBrandId'] = $request->sellerBrandId;
		}

		$this->validate($request, [
			'countries' => 'required',
			'first_name' => 'required',
			'last_name' => 'required',
			'address1' => 'required',
			//'address2' => 'required',
			'city' => 'required',
			'state_province_region' => 'required',
			'zip_code' =>  'required|numeric',
			'email_address'=> 'required|email',
			're_enter_email'=> 'required|email',

		]);

		$data['address_id']  = $orderDeliveryAddressId = $request->address_id;
		$data['cartAddress'] = $_POST;
		
		if($request->address_id < 1) {
			$orderDeliveryAddressId = $this->storeRepository->storeDeliveryAddress($data);
		}

		if($request->address_id > 0){
			$this->storeRepository->updateExistingStoreOrderDeliveryAddress($request, $data['address_id']);
			$orderDeliveryAddressId = $data['address_id'];
		}

		Session::put( 'cart.order_address',$orderDeliveryAddressId);

		$data['cartProducts'] = $this->storeRepository->getCartProducts();
        $data['cartProductsCount'] = $this->storeRepository->getCartProductsCount();

		if(count($data['cartProductsCount']) > 0){
			return redirect("store/pay/".$data['sellerBrandIdEncoded'].'?payment_type=card');
		}else{
			return redirect("store/cart/Session-expired-please-shop-again.");
		}
	}

	public function reviewOrder(Request $request){

		if($request->sellerBrandId != "buy-all"){
			$data['sellerBrandIdEncoded'] = $request->sellerBrandId;

			$data['sellerBrandId'] = Hashids::decode($request->sellerBrandId);
			$data['sellerBrandId'] = $data['sellerBrandId'][0];
		}else{
			$data['sellerBrandIdEncoded'] = $request->sellerBrandId;

			$data['sellerBrandId'] = $request->sellerBrandId;
		}

		$data['cartProducts']      = Session::get( 'cart.products' );
		$data['address']           = Session::get('cart.order_address');
		$data['address']           = end($data['address']);

		$data['totalShippingCost'] = $this->storeOrderRepository->getOrderTotalShippingCost( $data['cartProducts'], $data['address']['countries'], $data['sellerBrandId'] );
		return view( "Store::Cart.reviewOrder", $data );
	}

	// ==================== End of Ubaid code ============================


	// ==================== Mustabeen code ============================
	public function searchMyOrders(Request $request)
	{
		$is_address_owner = $this->storeOrderRepository->searchMyOrders( $request->order_number, $request->product_name );

		return $is_address_owner;
	}

	public function searchMyReviews(Request $request)
	{
		$serchedOrders = $this->storeOrderRepository->searchMyReviews( $request->order_number, $request->product_name );

		return $serchedOrders;
	}

	public function sofDeleteAddressInfo(Request $request)
	{
		if(!isset($request->address_id)){
			return 0;
		}

		$id = $request->address_id;
		$is_address_owner = $this->storeRepository->isAddressOwner( $id );

		if($is_address_owner > 0){
			return $is_address_owner = $this->storeRepository->sofDeleteAddressInfo($id);
		}

		return 0;
	}
	/**
	 * @param $product_id
	 *
	 * @return mixed
	 */
	public function deleteCartProduct( $product_id ) {

		$this->storeRepository->deleteProductFromCart( $product_id );
		$total_items = $this->storeRepository->getCartProductsCount();
        $response = ['status' => 1,'total_items' => $total_items];
        return response()->json($response);
	}

	// ==================== End of Mustabeen code ============================

	/**
	 * @param Request $request
	 */
	public function UpdateQuantityCartProduct( Request $request ) {
		$product_id = $request->get('product_id');
		$quantity   = $request->get('quantity');
		$response = $this->storeRepository->updateCart( $product_id, $quantity );
		$total_items = $this->storeRepository->getCartProductsCount();
        $response['total_items'] = $total_items;

        return response()->json($response);
	}

	/**
	 * @param $review_id
	 *
	 * @return mixed
	 */
	public function editProductReview( $review_id ) {
		$description = Input::get( 'edited_review_description' );
		$rating      = Input::get( 'stars_rating_updated' );
		$review      = $this->storeRepository->getReview( $review_id );

		if ( $this->storeRepository->is_review_owner( $review_id ) ) {
			$this->storeRepository->editProductReview( $description, $rating, $review_id );

			$storeName = $this->storeAdminRepository->getProductStoreName($review->product_id);

			return redirect( 'store/'.$storeName.'/product/' . $review->product_id );
		}

		return redirect()->back();
	}

	public function getOrderCompleted($order_id) {
		$data['order_id'] = $order_id;
		$data['url_user_id'] = Auth::user()->username;

		return view( 'Store::orders.storeOrderSuccessful', $data );
    }
	// ==================== Zahid code ============================
	public function updateOrderStatusAjax(Request $request) {
		$order_info     = explode('_', $request->order_info);
		$status   = $order_info[1];
		$order_id = $order_info[3];

		$isOrderCustomer = $this->storeAdminOrderRepository->isOrderCustomer($order_id, Auth::user()->id);
		if($isOrderCustomer < 1){
			return 'This order does not belongs to you? Please contact to Admin if problem persists.';
		}

		$newStatusData = $this->updateOrderStatus($order_id, $status, 'buyer');
		
		if(is_array($newStatusData)){
			$sale = \Config::get('constants_brandstore.STATEMENT_TYPES.SALE');
			$this->storeAdminRepository->updateStatement($sale,'store_order',$order_id,'credit','USD');
			$fee = \Config::get('constants_brandstore.STATEMENT_TYPES.ORDER_SHIPPING_FEE');
			$this->storeAdminRepository->updateStatement($fee,'store_order',$order_id,'credit','USD');
			return array_merge($newStatusData, ['order_id'=> $order_id]);
		}else{
			return 'something wrong happened try again.';
		}
	}

	public function updateOrderStatus($order_id, $status, $subject) {
		$is_updated = $this->storeAdminOrderRepository->updateOrderStatus($order_id, $status, $subject);
		$order = $this->storeAdminOrderRepository->getOrderStatus($order_id);

		if($is_updated > 0){
			if(Auth::user()->user_type == 1){
				$user_type = 1;
				$data = getOrderStatusForBuyer($order_id, $order->status, $order);
			}else{
				$user_type = 2;
				$data = getOrderStatusForSeller($order_id, $order->status);
			}

			if($order->status == \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_CANCELED" )) {
				//updating product amount on cancel
				$soldProducts = $this->storeAdminOrderRepository->getOrderAllProducts($order_id);
				foreach ($soldProducts as $soldProduct) {
					$soldQuantity = $this->storeAdminOrderRepository->getOrderProductItemQuantity($order_id, $soldProduct->id);
					$this->storeAdminRepository->updateProductQuantityByOperation($soldProduct->id, '+', $soldQuantity->quantity);
				}
			}
			//end of updating product amount on cancel

			//Update product record and add statistics if product status is finished
			if($order->status == \Config::get( "constants_brandstore.ORDER_STATUS.ORDER_DELIVERED" )){
				$country    = Auth::user()->country;
				$ip         = getUserIpAddress();
				$userGender = getUserGender();
				$userAge    = getUserAge();

				$soldProducts = $this->storeAdminOrderRepository->getOrderAllProducts($order_id);
				foreach($soldProducts as $soldProduct){
					$this->storeProductStatRepository->addProductStat('sale', Auth::user()->id, $user_type, $userAge, $userGender, $country, $ip, $soldProduct->owner_id, $soldProduct->id);

					$soldQuantity = $this->storeAdminOrderRepository->getOrderProductItemQuantity($order_id, $soldProduct->id);

//					$this->storeAdminRepository->updateProductQuantityByOperation($soldProduct->id, '-', $soldQuantity->quantity);
					//if($status == 0) {
						//$this->storeAdminRepository->updateProductSoldProductByOperation($soldProduct->id, '+', $soldQuantity->quantity);
					//}
				}
			}
			return $data;
		}

		return 'Please try again.';
	}

	public function softDeleteOrder(Request $request ) {
		$order_info     = explode('_', $request->order_info);
		$order_id   = $order_info[1];

		$isOrderCustomer = $this->storeAdminOrderRepository->isOrderCustomer($order_id, Auth::user()->id);
		if($isOrderCustomer < 1){
			return 'This order does not belongs to you? Please contact to Admin if problem persists.';
		}

		$isOrderDeleted = $this->storeAdminOrderRepository->softDeleteOrder($order_id);

		if($isOrderDeleted > 0){
			return $isOrderDeleted;
		}else{
			return "Something wrong happened please try again.";
		}
	}

	public function manageFeedbacks(  ) {
		$data['url_user_id'] = $user_id = Auth::user()->id;

		$data['allOrders']   = $this->storeAdminRepository->getFinishedOrdersCurrentUserBuyer($user_id);
		$data['countRequestToRevise'] = $this->storeOrderRepository->countRequestToReviseCurrentUser();
		$data['reviews']     = $reviews = $this->storeAdminRepository->getCurrentBuyerUserProductsReviews($user_id);

		return view( 'Store::reviews.manageReviews', $data );
	}

	public function feedbackReminder(Request $request)
	{
		$data['class']        = '';
		$data['action_btn_1'] = '';
		$data['action_btn_2'] = 'Awaiting for Feedback<br /><a class="btn btng" href="javascript:void(0);">Reminder Sent</a>';

		$data['order_id'] = $request->order_id.$request->product_id;

		return $data;
	}
	public function reviseFeedback(Request $request)
	{
		$review = $this->storeAdminRepository->updateFeedBack($request);
		$data = getReviewStatusForBuyer( $review, $request->store_name, $request->order_id );
		$data['order_id'] = $request->order_id.$request->product_id;
		return $data;
	}

	public function cancelOrder(Request $request)
	{
		$status   = 0;
		$order_id = $request->order_id;
		$reason = $request->get('reason');

		$isOrderCustomer = $this->storeAdminOrderRepository->isOrderCustomer($order_id, Auth::user()->id);
		if($isOrderCustomer < 1){
			return 'This order does not belongs to you? Please contact to Admin if problem persists.';
		}

		$newStatusData = $this->updateOrderStatus($order_id, $status, 'buyer');
		if(is_array($newStatusData)){
			return array_merge($newStatusData, ['order_id'=> $order_id]);
		}else{
			return 'something wrong happened try again.';
		}
	}

	public function getOrderInvoice(Request $request)
	{
		$data['order_id']       = $order_id = $request->order_id;
		$data['url_user_id']    = $store_owner = Auth::user()->username;

		$data['order']          = $this->storeAdminOrderRepository->getOrderById($order_id);
		$data['orderCourier']   = $order = $this->storeAdminOrderRepository->getOrderCourierByOrderId($order_id);
		$data['orderAddresses'] = $order = $this->storeRepository->getOrderAddressesByOrderId($data['order']->delivery_address_id);
		$data['orderPayments']  = $order = $this->storeAdminOrderRepository->getOrderPaymentByOrderId($order_id);

		return view( 'Store::orders.orderInvoice', $data );
	}

	public function checkProductShippingCountry(Request $request)
	{
		$data = $this->storeOrderRepository->checkProductShippingCountry($request->products_ids, $request->country_id,$request->sub_total);

		return $data;
	}

	public function checkProductShippingCountryByISO(Request $request)
	{ //echo $request->country_iso;
		$country = Country::where("iso", $request->country_iso)->first();
		//echo "<pre>";
		//echo ($country->id);
		//die(000);
		//$request->products_ids, $request->country_id,$request->sub_total
		$data = $this->storeOrderRepository->checkProductShippingCountry($request->products_ids, $country->id);

		return $data;
	}

	public function getEditAddressFormInfo(Request $request)
	{
		$data['userAddressesInfo'] = $order = $this->storeRepository->getEditAddressFormInfo($request);

		return $data;
	}
	// ==================== End of Zahid code ============================

	public function ProductReviewAjax(Request $request, $product_id = NULL) {

		$review = $this->storeAdminRepository->storeReview($request, $product_id, 1);

		$data             = getReviewStatusForBuyer($review, $request->store_name, $request->order_id);
		$data['order_id'] = $request->order_id . $product_id;

		return $data;
	}

}
