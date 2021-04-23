<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 1/6/2016
 * Time: 9:31 PM
 */
namespace kinnect2Store\Store\Repository;

use kinnect2Store\Store\Category;
use kinnect2Store\Store\StoreDeliveryAddress;
use kinnect2Store\Store\StoreOrder;
use kinnect2Store\Store\StoreOrderItems;
use kinnect2Store\Store\StoreOrderItemAttribute;
use kinnect2Store\Store\StoreProductReview;
use kinnect2Store\Store\StoreWithdrawal;
use LucaDegasperi\OAuth2Server\Authorizer;
use kinnect2Store\Store\StoreProduct;
use App\Facades\UrlFilter;
use App\StorageFile;
use App\AlbumPhoto;
use Carbon\Carbon;
use App\Album;
use App\User;
use kinnect2Store\Store\StoreReversal;
use Session;
use Auth;
use DB;
use Vinkla\Hashids\Facades\Hashids;
use kinnect2Store\Store\StoreWithdrawalMethod;
use kinnect2Store\Store\StoreTransaction;

class StoreRepository
{
    protected $store;

    protected $data;
    protected $user_id;
    protected $is_api;

    /**
     *
     */
    public function __construct()
    {

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

    public function getPrarentCategory($sub_category_id)
    {
         $cat = Category::select('category_parent_id')->where( 'id', $sub_category_id )->first();
        if(isset($cat->category_parent_id)){
            return $cat->category_parent_id;
        }
    }
    /**
     * @param $sub_category_id
     *
     * @return int
     */
    public function subCategoryProducts($sub_category_id)
    {

        $products = StoreProduct::select('id', 'title', 'description', 'price', 'category_id as image', 'discount', 'owner_id')->where('sub_category_id', $sub_category_id)->get();

        if (is_object($this->returnValidData($products))) {
            foreach ($products as $product) {
                $product->image = getProductPhotoSrc('', '', $product->id, 'product_profile');
            }

            return $products;
        }

        return 0;
    }


    /**
     * @param $data
     *
     * @return int
     */
    public function returnValidData($data)
    {
        if (count($data) > 0) {
            return $data;
        } else {
            return 0;
        }
    }

    /*public function getShippingInfo($cartProducts) {

        $data = StoreProduct::where('id' , $cartProducts )->get();
        return $this->returnValidData($data);
    }*/
    public function storeOrder($brand_id = 0,$user_id)
    {
        $address_id = $this->getCartDeliverAddress();
        $country_id = $this->getAddressFieldByID($address_id,'country_id');
        $order_ids = [];
        if($brand_id == 0){
            $allProducts = $this->getCartProducts();
            foreach ($allProducts as $brand_id => $products){
                $orderCost = $orderQuantity = $orderDiscount = $orderShippingCost = 0;

                $status = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_PAYMENT_VERIFIED');
                $store_order_id = $this->saveOrder($user_id,$brand_id,$address_id,$status);
                $order_ids[$store_order_id] = $store_order_id;
                foreach ($products as $product){
                    $orderProductDetail = StoreProduct::where('id', $product['product_id'])->select(['id','price','discount'])->first();
                    $orderQuantity = $product['quantity'] + $orderQuantity;
                    $this->storeOrdersItem($product['product_id'], $store_order_id, $product['quantity'], $orderProductDetail->price, $orderProductDetail->discount,$product['size_id'],$product['color_id']);

                    $productCost = $this->getCartProductCost($product['product_id'],$product['quantity']);
                    $orderCost = $productCost + $orderCost;

                    $shippingCost = $this->getProductRegionShippingCost($country_id,$product['product_id']);
                    $orderShippingCost = $orderShippingCost + ($shippingCost * $product['quantity']);

                    $productDiscount = $this->getCartProductDiscount($product['product_id'],$product['quantity']);
                    $orderDiscount = $productDiscount + $orderDiscount;
                }
                $orderCost = $orderCost + $orderShippingCost;
                $this->updateStoreOrders($store_order_id, $orderCost, $orderQuantity, $orderDiscount, $orderShippingCost);
                $this->addOrderNumber($store_order_id);
            }
            
            $this->empryCart();
            
        }else{
            $brandProducts = $this->getCartProducts($brand_id);
            $status = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_PAYMENT_VERIFIED');
            $store_order_id = $this->saveOrder($user_id,$brand_id,$address_id,$status);
            $order_ids[$store_order_id] = $store_order_id;

            $orderCost = $orderQuantity = $orderDiscount = $orderShippingCost = 0;
            foreach ($brandProducts as $product_id => $product){
                $orderProductDetail = StoreProduct::where('id', $product['product_id'])->select(['id','price','discount'])->first();
                $orderQuantity = $product['quantity'] + $orderQuantity;

               $this->storeOrdersItem($product['product_id'], $store_order_id, $product['quantity'], $orderProductDetail->price, $orderProductDetail->discount,$product['size_id'],$product['color_id']);

                $productCost = $this->getCartProductCost($product['product_id'],$product['quantity']);
                $orderCost = $productCost + $orderCost;

                $shippingCost = $this->getProductRegionShippingCost($country_id,$product['product_id']);

                if($product['quantity'] > 0) {
                    $shippingCost = $shippingCost * $product['quantity'];
                }

                $orderShippingCost = $orderShippingCost + $shippingCost;

                $productDiscount = $this->getCartProductDiscount($product['product_id'],$product['quantity']);
                $orderDiscount = $productDiscount + $orderDiscount;

            }

            $this->deleteCartBrandProducts($brand_id);
            $orderCost = $orderCost + $orderShippingCost;
            $this->updateStoreOrders($store_order_id, $orderCost, $orderQuantity, $orderDiscount, $orderShippingCost);
            $this->addOrderNumber($store_order_id);
        }
        return $order_ids;
    }
    public function saveOrder($user_id,$brand_id,$address_id,$status){
        $soObj = new StoreOrder();
        $soObj->customer_id = $user_id;
        $soObj->seller_id = $brand_id;
        $soObj->delivery_address_id = $address_id;
        $soObj->payment_type = 1;
        $soObj->status = $status;
        $soObj->total_price = 0;
        $soObj->total_discount = 0;
        $soObj->total_quantity = 0;
        $soObj->save();
        return $soObj->id;
    }
    public function getOrderTotal($brand_id = 0){

        $address_id = $this->getCartDeliverAddress();
        $country_id = $this->getAddressFieldByID($address_id,'country_id');
        $orderCost = 0;
        if($brand_id == 0){
            $allProducts = $this->getCartProducts();
            foreach ($allProducts as $brand_id => $products){
                foreach ($products as $product){
                    $productCost = $this->getCartProductCost($product['product_id'],$product['quantity']);
                    $shippingCost = $this->getProductRegionShippingCost($country_id,$product['product_id']);
                    if($product['quantity'] > 0) {
                        $shippingCost = $shippingCost * $product['quantity'];
                    }
                    $productDiscount = $this->getCartProductDiscount($product['product_id'],$product['quantity']);
                    $orderCost += (($productCost - $productDiscount) + $shippingCost);
                }
            }
        }else{
            $brandProducts = $this->getBrandCartProducts($brand_id);
            foreach ($brandProducts as $product){
                $productCost = $this->getCartProductCost($product['product_id'],$product['quantity']);
                $shippingCost = $this->getProductRegionShippingCost($country_id,$product['product_id']);

                if($product['quantity'] > 0){
                    $shippingCost = $shippingCost * $product['quantity'];
                }

                $productDiscount = $this->getCartProductDiscount($product['product_id'],$product['quantity']);
                $orderCost += (($productCost - $productDiscount) + $shippingCost);
            }
        }
        return $orderCost;
    }
    public function getCartProductDiscount($product_id,$quantity){
        $orderProductDetail = StoreProduct::where('id', $product_id)->select(['id','price','discount'])->first();
        if(isset($orderProductDetail->id)){
            return ($orderProductDetail->price * $orderProductDetail->discount / 100) * $quantity;
        }
        return 0;
    }
    public function getCartProductCost($product_id,$quantity){
        $orderProductDetail = StoreProduct::where('id', $product_id)->select(['id','price'])->first();
        if(isset($orderProductDetail->id)){
            return ($orderProductDetail->price * $quantity);
        }
        return 0;
    }
    public function getBrandCartProducts($brand_id){
        return Session::get('cart.products.'.$brand_id);
    }
    public function getProductRegionShippingCost($country_id, $product_id)
    {
        // Calculate shipping cost every time, whenever it is needed.
        $region_id = getRegionId($country_id);

        $regionCostInfo = getRegionCostByProductId($region_id, $product_id);

        if (isset($regionCostInfo->shipping_cost)) {
            return $regionCostInfo->shipping_cost;
        }

        return 0;
    }

    protected function addOrderNumber($order_id)
    {
        if (empty($order_id)) {
            return false;
        }
        $order = StoreOrder::where('id', $order_id)->select(['id', 'order_number'])->first();

        if (!empty($order->id)) {
            $order->order_number = Hashids::encode($order->id, 10, 10);
            $order->save();
        }

    }

    public function storeOrdersItem($product_id = '', $store_order_id, $quantity = '', $price = '', $discount = '',$size_id = null,$color_id = null)
    {
        $soObj = new StoreOrderItems();
        $soObj->product_price = $price;
        $soObj->product_discount = $discount;
        $soObj->quantity = $quantity;
        $soObj->product_id = $product_id;
        $soObj->order_id = $store_order_id;
        if($soObj->save()){

            if(!empty($size_id)) {
                $spItemObj = new StoreOrderItemAttribute();
                $spItemObj->store_order_item_id = $soObj->id;
                $spItemObj->store_product_attribute_id = $size_id;
                
                $spItemObj->save();
            }
            if(!empty($color_id)) {
                $spItemObj = new StoreOrderItemAttribute();
                $spItemObj->store_order_item_id = $soObj->id;
                $spItemObj->store_product_attribute_id = $color_id;
                $spItemObj->save();
            }
        }

        return $soObj->id;
    }

    public function updateStoreOrders($order_id, $total_price, $quantity, $discount, $totalShippingCostOfThisOrder, $delivery_address_id = '')
    {
        $storeOrder = StoreOrder::where('id',$order_id)->first();

        if(!empty($storeOrder->id)){
            $storeOrder->total_price = $total_price;
            $storeOrder->total_quantity = $quantity;
            $storeOrder->total_discount = $discount;
            $storeOrder->total_shiping_cost = $totalShippingCostOfThisOrder;
            $storeOrder->save();
            return $storeOrder->id;
        }

        return False;
    }

    public function updateStoreOrderAddress($order_id, $orderDeliveryAddressId)
    {

        $updateAddress = DB::table('store_orders')->where('id', $order_id)->update(['delivery_address_id' => $orderDeliveryAddressId]);

        return $updateAddress;
    }

    public function storeDeliveryAddress($data)
    {
        $newAddress = $data['cartAddress'];
        $orderDeliveryAddressId = DB::table('store_delivery_addresses')->insertGetId(
            [
                'country_id' => $newAddress['countries'],
                'first_name' => $newAddress['first_name'],
                'user_id' => $this->user_id,
                'last_name' => $newAddress['last_name'],
                'st_address_1' => $newAddress['address1'],
                'st_address_2' => $newAddress['address2'],
                'city' => $newAddress['city'],
                'state' => $newAddress['state_province_region'],
                'zip_code' => $newAddress['zip_code'],
                'phone_number' => $newAddress['phone_number'],
                'email' => $newAddress['email_address']
            ]);

        return $orderDeliveryAddressId;
    }

    public function updateExistingStoreOrderDeliveryAddress($request, $address_id)
    {

        $orderDeliveryAddressId = DB::table('store_delivery_addresses')->where('id', $request->address_id)->update(
            [
                'country_id' => $request->countries,
                'first_name' => $request->first_name,
                'user_id' => $this->user_id,
                'last_name' => $request->last_name,
                'st_address_1' => $request->address1,
                'st_address_2' => $request->address2,
                'city' => $request->city,
                'state' => $request->state_province_region,
                'zip_code' => $request->zip_code,
                'phone_number' => $request->phone_number,
                'email' => $request->email_address
            ]);

        return $orderDeliveryAddressId;
    }
// ==================== end of Ubaid Code =====================


// ==================== Mustabeen code ============================

    /**
     * @param $product_id
     *
     * @return mixed
     */
    public function deleteProductFromCart($product_id)
    {
        $owner_id = $this->getProductOwnerIDByProductID($product_id);
        $quantity = Session::get('cart.products.'.$owner_id.'.'.$product_id.'.quantity');
        Session::forget('cart.products.'.$owner_id.'.'.$product_id);
        if(empty(Session::get('cart.products.'.$owner_id))){
            Session::forget('cart.products.'.$owner_id);
        }
        $total_items = Session::get('cart.total_items');

        $total_items = $total_items - $quantity;
        Session::put('cart.total_items',$total_items);
    }
    public function empryCart(){
        return Session::forget('cart');
    }
    public function deleteCartBrandProducts($brand_id){
        $brandProducts = $this->getCartProducts($brand_id);

        foreach ($brandProducts as $product){
            $this->deleteProductFromCart($product['product_id']);
        }
        return Session::forget('cart.products.'.$brand_id);
    }
    /**
     * @param $product_id
     * @param $quantity
     */
    public function updateCart($product_id, $quantity, $size_id = 0, $color_id = 0)
    {
        $productInf = getProductDetailsByID($product_id);

        if($quantity > $productInf->quantity){
            return ['message' => 'quantity_overflow','units_available' => $productInf->quantity,'message_text' => 'There are maximum '.$productInf->quantity.' unit(s) available of this product'];
        }

        $quantity_old = Session::get('cart.products.'.$productInf->owner_id.'.'.$productInf->id.'.quantity');

        Session::put('cart.products.'.$productInf->owner_id.'.'.$productInf->id.'.product_id',$product_id);
        Session::put('cart.products.'.$productInf->owner_id.'.'.$productInf->id.'.quantity',$quantity);
        Session::put('cart.products.'.$productInf->owner_id.'.'.$productInf->id.'.discount',$productInf->discount);
        Session::put('cart.products.'.$productInf->owner_id.'.'.$productInf->id.'.price',$productInf->price);
        Session::put('cart.products.'.$productInf->owner_id.'.'.$productInf->id.'.size_id', $size_id);
        Session::put('cart.products.'.$productInf->owner_id.'.'.$productInf->id.'.color_id', $color_id);

        $product_count = Session::get('cart.total_items');
        
        $product_count = ($product_count - $quantity_old) + $quantity;
        Session::put('cart.total_items',$product_count);
        
        return ['message' => 'added_to_cart'];
    }
    public function getCartProductsCount(){
        return Session::get('cart.total_items');
    }
    public function getCartProducts($brand_id = 0){
        if($brand_id > 0){
            return Session::get('cart.products.'.$brand_id);
        }
        return Session::get('cart.products');
    }
    /**
     * @param $review_id
     *
     * @return null
     */
    public function is_review_owner($review_id)
    {
        $review = StoreProductReview::where('id', $review_id)->first();

        if (isset($review->id)) {
            if ($review->owner_id == Auth::user()->id) {
                return $review->id;
            }
        } else {
            return null;
        }
    }

    /**
     * @param $description
     * @param $rating
     * @param $review_id
     */
    public function editProductReview($description, $rating, $review_id)
    {
        $review = StoreProductReview::where('id', $review_id)->first();
        if ($description == "") {
        } else {
            $review->description = $description;
        }

        $review->rating = $rating;
        $review->updated_at = Carbon::now();
        $review->save();
    }

    /**
     * @param $review_id
     *
     * @return null
     */
    public function getReview($review_id)
    {
        $review = StoreProductReview::where('id', $review_id)->first();
        if (isset($review->id)) {
            if ($review->owner_id == Auth::user()->id) {
                return $review;
            }
        } else {
            return null;
        }
    }


// ==================== End of Mustabeen code ============================


// ==================== Zahid code ============================


    /**
     * @param $user_id
     *
     * @return mixed
     */
    public function currentBrandCategories($user_id)
    {
       $category_ids = DB::table('store_products')
            ->where('owner_id', $user_id)
            ->orderByRaw("RAND()")
            ->take(3)
            ->lists('category_id');

        return DB::table('store_product_categories')
            ->where('owner_id', $user_id)
            ->where('category_parent_id', 0)
            ->whereIn('id', $category_ids)
            ->whereNull('deleted_at')
            ->orderByRaw("RAND()")
            ->take(3)
            ->get();
    }

    public function productHavingShippingCosts()
    {

    }

    public function currentBrandFeaturedProducts($owner_id)
    {
        return StoreProduct::where('is_featured', 1)
            ->where('quantity', '!=', 0)
            ->where('owner_id', $owner_id)
            ->orderByRaw("RAND()")
            ->whereNull('deleted_at')
            ->take(3)->get();
    }

    public function currentBrandBestSellingProducts($owner_id)
    {
        return StoreProduct::where('sold', '>', 0)
            ->where('owner_id', $owner_id)
            ->where('quantity', '!=', 0)
            ->whereNull('deleted_at')
            ->orderBy('sold', 'DESC')
            ->take(3)->get();
    }
    public function currentBrandRecord($owner_id)
    {
        return StoreProduct::where('owner_id', $owner_id)->first();
    }
    /**
     * @param $user_id
     *
     * @return string
     */
    public function currentBrandProducts($user_id, $featuredProducts = '', $bestSellingProducts= '')
    {
        $notInProductIds = '';

        foreach($featuredProducts as $featuredProduct){
            $notInProductIds .= $featuredProduct->id.',';
        }

        foreach($bestSellingProducts as $bestSellingProduct){
            $pos = strpos($notInProductIds, $bestSellingProduct->id.',');

            if($pos === false){
                $notInProductIds .= $bestSellingProduct->id.',';
            }

        }

        $categories = $this->currentBrandCategories($user_id);
        $userRegionId = getCurrentUserRegionId(Auth::user()->timezone);
        $products = '';

        foreach ($categories as $category) {

            $products[$category->id . '_' . $category->name] = DB::table('store_products')
                ->select('store_products.id', 'store_products.quantity','store_products.title', 'store_products.price', 'store_products.description', 'store_products.owner_id', 'store_products.category_id', 'store_products.sub_category_id', 'store_products.discount')
                ->join('store_product_shipping_cost', 'store_products.id', '=', 'store_product_shipping_cost.product_id')
                ->where('quantity', '!=', 0)
                ->where('category_id', $category->id)
                //->where('store_products.is_featured', '!=', 1)
                ->whereNotIn('store_products.id', [$notInProductIds])
                ->where('store_product_shipping_cost.region_id', '=', $userRegionId)
                ->whereNull('store_products.deleted_at')
                ->orderByRaw("RAND()")
                ->take(3)
                ->get();
        }

        return $products;

    }

    /**
     * @param $brand_id
     *
     * @return mixed
     */
    public function isStoreBrand($brand_id)
    {
        return $brand = User::select([
            'user_type',
            'id'
        ])->where('id', $brand_id)->orWhere('username', $brand_id)->first();
    }

    public function addProfilePageStat($owner_id)
    {
        DB::table('profile_page_stats')->insert([
            ['user_id' => \Auth::user()->id, 'owner_id' => $owner_id]
        ]);
    }

    public function getAddressesOfUserById($user_id)
    {
        return StoreDeliveryAddress::where('is_deleted', '!=', 1)->where('user_id', $user_id)->orderBy('id', 'DESC')->paginate(5);
    }

    public function getOrderAddressesByOrderId($id)
    {
        return StoreDeliveryAddress::where('id', $id)->paginate(5);
    }

    public function getOrderAddressByOrderId($order_id)
    {
        return StoreDeliveryAddress::where('order_id', $order_id)->first();
    }

    public function getEditAddressFormInfo($request)
    {
        if (isset($request->address_id)) {
            return StoreDeliveryAddress::where('id', $request->address_id)->first();
        }

        return '';
    }

    public function isAddressOwner($id)
    {
        $isOwner = StoreDeliveryAddress::select('user_id')->where('id', $id)->first();

        if (isset($isOwner->user_id)) {
            if ($isOwner->user_id == $this->user_id) {
                return 1;
            }
        }

        return 0;
    }

    public function sofDeleteAddressInfo($id)
    {
        if (StoreDeliveryAddress::where('id', $id)->update([
                'is_deleted' => 1
            ]) > 0
        ) {
            Session::forget('cart.order_address');
            return $id;
        }

        return 0;
    }
// ==================== End of Zahid code ============================
    public function getAvailableBalance($user_id){
        $debit = StoreTransaction::where('user_id',$user_id)->where('transaction_type','debit')->sum('amount');
        $credit = StoreTransaction::where('user_id',$user_id)->where('transaction_type','credit')->sum('amount');
        $balance = $credit - $debit;
        return $balance;
    }
    public function getDisputedBalance($user_id){
        return StoreTransaction::where('user_id',$user_id)->where('transaction_type','disputed')->sum('amount');
    }
    public function getPendingAmount($user_id){
        $pending_amount = StoreWithdrawal::where('seller_id',$user_id)->where('status','pending')->sum('amount');
        return $pending_amount;
    }
    public function getKinnect2Fee(){
        return \Config::get('constants_brandstore.WITHDRAWAL_FEE_PERCENTAGE');
    }
    public function getDefaultWithdrawalMethod($user_id){
        $method = StoreWithdrawalMethod::where('is_default',1)
            ->select(['id','seller_id'])
            ->where('seller_id',$user_id)
            ->first();
        if(empty($method->id)) {
            $method = StoreWithdrawalMethod::where('seller_id', $user_id)
                ->select(['id','seller_id'])
                ->first();
        }
        return @$method->id;
    }
    public function getDeliveryAddressByID($address_id){
        return StoreDeliveryAddress::where('id',$address_id)->first();
    }
    public function getAddressFieldByID($address_id,$field){
        $select =  StoreDeliveryAddress::where('id',$address_id)->select([$field])->first();
        return @$select->$field;
    }
    public function getCartDeliverAddress(){
        return Session::get( 'cart.order_address');
    }
    public function getProductOwnerIDByProductID($product_id){
        $owner = StoreProduct::where('id',$product_id)->select(['id','owner_id'])->first();
        return @$owner->owner_id;
    }
    public function logStoreReversal($data){
        $srObj = new StoreReversal();
        
        $srObj->parent_type = $data['parent_type'];
        $srObj->parent_id = $data['parent_id'];
        $srObj->user_id = $data['user_id'];
        $srObj->seller_id = $data['seller_id'];
        $srObj->amount = $data['amount'];
        
        return $srObj->save();
    }
}
