<?php

namespace kinnect2Store\Store\Http\admin;

use App\AlbumPhoto;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use App\Services\StorageManager;
use Illuminate\Http\Request;
use app\Http\Requests;
use App\StorageFile;
use App\User;
use File;
use Auth;
use DB;
use kinnect2Store\Store\Repository\StoreRepository;
use kinnect2Store\Store\StoreAlbumPhotos;
use kinnect2Store\Store\StoreOrder;
use kinnect2Store\Store\StoreOrderTransaction;
use kinnect2Store\Store\StoreShippingCost;
use kinnect2Store\Store\StoreStorageFiles;
use App\Events\ActivityLog;
use App\Classes\Worldpay;
use App\Classes\WorldpayException;
use App\Country;

class StoreAdminController extends Controller
{
    protected $storeAdminRepository;
    protected $storeAdminOrderRepository;
    protected $storeProductStatRepository;

    /**
     * @param \kinnect2Store\Store\Repository\StoreAdminRepository $storeAdminRepository
     * @param Request                                              $middleware
     */
    public function __construct(
        \kinnect2Store\Store\Repository\admin\StoreAdminRepository $storeAdminRepository,
        \kinnect2Store\Store\Repository\StoreProductStatRepository $storeProductStatRepository,
        \kinnect2Store\Store\Repository\admin\StoreAdminOrderRepository $storeAdminOrderRepository, Request $middleware
    ) {
        $this->storeAdminRepository       = $storeAdminRepository;
        $this->storeAdminOrderRepository  = $storeAdminOrderRepository;
        $this->storeProductStatRepository = $storeProductStatRepository;

        /* if(isset($middleware->storeBrandId)){
             if(ucwords($middleware->storeBrandId) != ucwords($this->user->username)) {
                 Redirect::to('/store/'.$middleware->storeBrandId)->send();
             }
         }*/
        $this->user_id = Auth::id();
        $this->user    = Auth::user();
        if($this->user->user_type != \Config::get('constants.BRAND_USER')){
            abort(404);
        }
        /*@$this->data->user = $middleware['middleware']['user'];
        $this->is_api = $middleware['middleware']['is_api'];*/
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public function getCategories($id) {
        $brand = $this->storeAdminRepository->isStoreBrand($id);

        $data['allCategories'] = $this->storeAdminRepository->get_category($brand->id);

        $data['url_user_id'] = $brand->id;

        return view('Store::admin.Category.categories', $data);

    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return mixed
     */
    public function storeCategories(Request $request, $id) {

        $user_id = Auth::user()->id;
        $existingCategoryName = $this->storeAdminRepository->existingCategory($request,$user_id);
        if ($existingCategoryName === TRUE) {
            return redirect('store/' . $this->user->username . '/admin/categories/Category-not-created')->with('info', 'Category or Subcategory already exist try another one.');
        } else {
            $this->storeAdminRepository->store_category($request);

            return redirect('store/{storeBrandId}/admin/categories/' . $id)->with("info", "Successfully Add");
        }

        //return redirect('store/{storeBrandId}/admin/categories/' . $id)->with("info", "No Record Added.");
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function deleteCategory(Request $request) {
        $category_id = $request->category_id;
        // $owner_id = $this->storeAdminRepository->getCatOwnerId($category_id);

        if ($this->storeAdminRepository->is_category_owner($category_id)) {
            $this->storeAdminRepository->deleteCategory($category_id);

            return redirect('store/' . $this->user->username . '/admin/categories/Category-deleted');

        }

        return redirect('store/' . $this->user->username . '/admin/categories/Category-deleted');
    }

    // ==================== Ubaid code ============================
    /**
     * @return mixed
     */
    public function getAddProduct() {
        $data['categories']  = $this->storeAdminRepository->getAllCategories($this->user->id);
        $data['url_user_id'] = $this->user_id;

        return view('Store::admin.products.addProduct', $data);
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    public function storeProduct(Request $request) {

        $product_id = $this->storeAdminRepository->addProduct($request);
        if ($product_id > 0) {
            $options = array(
                        'type'         => \Config::get('constants_activity.OBJECT_TYPES.PRODUCT.ACTIONS.CREATE'),
                        'subject'      => $this->user_id,
                        'subject_type' => 'user',
                        'object'       => $product_id,
                        'object_type'  => \Config::get('constants_activity.OBJECT_TYPES.PRODUCT.NAME'),
                        'body'         => '',
                    );

            \Event::fire(new ActivityLog($options));
            return $product_id;
        }
        return 0;
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    public function storeProductUpdate(Request $request) {


        $product_id = $this->storeAdminRepository->updateProduct($request, 'update');

        if ($product_id > 0) {
            return $product_id;
        }

        return 0;
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return bool|int
     */

    public function product_image_ajax(Request $request) {

        $product_file = $request->file('product_image');
        $product_id = $request->get('product_id');

        $sm   = new StorageManager();
        $data = $sm->storeFile($this->user_id, $product_file, 'album_photo');

        $sfObj                 = new StoreStorageFiles();
        $sfObj->parent_file_id = !empty($data['parent_file_id']) ? $data['parent_file_id'] : NULL;
        $sfObj->type           = !empty($data['type']) ? $data['type'] : NULL;
        $sfObj->parent_id      = isset($data['parent_id']) ? $data['parent_id'] : $product_id;
        $sfObj->parent_type    = $data['parent_type'];
        $sfObj->user_id        = $data['user_id'];
        $sfObj->storage_path   = $data['storage_path'];
        $sfObj->extension      = $data['extension'];
        $sfObj->name           = $data['name'];
        $sfObj->mime_type      = $data['mime_type'];
        $sfObj->size           = $data['size'];
        $sfObj->hash           = $data['hash'];

        if (!$sfObj->save()) {
            $message =  ['status' => 0];
        } else {

            if(!empty($product_id)){
                $this->storeAdminRepository->resizeProductImage( $sfObj->storage_path, $sfObj->file_id, $sfObj->user_id, 'product', 'product_profile', '151', '210', $product_id );
                $this->storeAdminRepository->resizeProductImage( $sfObj->storage_path, $sfObj->file_id, $sfObj->user_id, 'product', 'product_thumb', '170', '170', $product_id);
                $this->storeAdminRepository->resizeProductImage( $sfObj->storage_path, $sfObj->file_id, $sfObj->user_id, 'product', 'product_icon', '54', '80', $product_id );
            }

            $message = [
                    'id' => $sfObj->file_id,
                    'path' => \Config::get('constants_activity.PHOTO_URL').$sfObj->storage_path. '?type=' . urlencode( $sfObj->mime_type ),
                    'status' => 1
            ];
        }
        return response()->json($message);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return int
     */
    public function delete_product_image(Request $request, $id) {
        $file_id = $request->get('file_id');

        if ($file_id > 0) {

            $file = StoreStorageFiles::where('file_id', $file_id)
                                       ->where('user_id',$this->user_id)
                                       ->select(['file_id','storage_path'])
                                       ->first();

            $sm = new StorageManager();

            if(!empty($file->storage_path) && $sm->pathExists(('photos/' . $file->storage_path))){
                $sm->deletFile(('photos/' . $file->storage_path));
                StoreStorageFiles::where('parent_file_id',$file_id)->delete();
                $file->delete();
                $status = 1;
            }else{
                $status = 0;
            }
        }else{
            $status = 0;
        }

        return response()->json(['status' => $status]);
    }

    /**
     * @return int|string
     */
    public function getSubCategory() {
        $category_id = Input::get("category");
        if ($category_id > 0) {
            $users_record = DB::table('store_product_categories')
                ->where('category_parent_id', $category_id)
                ->where('deleted_at', '=' , null)
                ->select('name', 'id')
                ->get();

            return json_encode($users_record);
        }

        return 0;
    }

    /**
     * @param $id
     * @param $product_id
     *
     * @return mixed
     */
    public function getProductDetail($id, $product_id) {

//        $id = $this->user_id;
        $country    = Auth::user()->country;
        $brand                 = $this->storeAdminRepository->isStoreBrand($id);
        $data['isStoreOwner']  = $this->storeAdminRepository->is_product_owner($product_id);
        $data['storeName']     = $id;
        $data['productDetail'] = $product = $this->storeAdminRepository->getProductDetail($product_id);
        if (!isset($product->id)) {
            return redirect()->back()->with('info', 'no product found.');
        }
        $data['key_feature'] = $this->storeAdminRepository->key_feature($product_id);
        $data['tech_spechs'] = $this->storeAdminRepository->tech_spechs($product_id);
        $data['reviews']     = $this->storeAdminRepository->getReviews($product_id);
        $data['url_user_id'] = $brand['id'];

        $data["user"]["country"] = Country::where("id", $country)->first();

        $productAttributes = $data['productAttributes'] = $this->storeAdminRepository->getProductAttributes($product_id);

        $data['productAttributeColors'] = [];
        $data['productAttributeSizes']  = [];

        if($productAttributes != 0){
            foreach($productAttributes as $productAttribute){
                if($productAttribute->attribute === 'size'){
                    array_push($data['productAttributeSizes'], $productAttribute->value);
                }

                if($productAttribute->attribute === 'color'){
                    array_push($data['productAttributeColors'], $productAttribute->value);
                }
            }
        }

        return view("Store::admin.products.storeProductDetail", $data);
    }

    /**
     * @param $id
     *
     * @return int
     */
    public function deleteProductAjax($id) {
        $brand      = $this->storeAdminRepository->isStoreBrand($id);
        $product_id = Input::get('product_id');

        if ($this->storeAdminRepository->is_product_owner($product_id)) {
            $this->storeAdminRepository->deleteProduct($product_id);

            return 1;
        }

        return 0;
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    public function delete_edit_product_image(Request $request) {
        $file_id = $request->get('id');

        if ($file_id > 0) {

            $files = StoreStorageFiles::select('file_id')->where('file_id', $file_id)->orWhere('parent_file_id', $file_id)->get();

            foreach ($files as $file):
                $file = StoreStorageFiles::where('file_id', $file->file_id)->first();

                $sm = new StorageManager();

                if (File::delete('local/storage/app/photos/' . $file->storage_path)) {
                    $file->delete();
                }
            endforeach;

            return 1;
        }

        return 0;
    }

    // ==================== End of Ubaid code ============================


    // ==================== Mustabeen code ============================
    public function searchMyOrders(Request $request) {
        $serchedOrders = $this->storeAdminOrderRepository->searchMyOrders($request->order_number, $request->product_name);

        return $serchedOrders;
    }

    public function searchMyReviews(Request $request) {
        $serchedOrders = $this->storeAdminOrderRepository->searchMyReviews($request->order_number, $request->product_name);

        return $serchedOrders;
    }

    /**
     * @param $category_id
     *
     * @return mixed
     */
    public function editCategory(Request $request) {
        $name        = Input::get('edited_name');
        $category_id = $request->category_id;

        //  $owner_id = $this->storeAdminRepository->getCatOwnerId($category_id);
        if ($this->storeAdminRepository->is_category_owner($category_id)) {
            $this->storeAdminRepository->editCat($name, $category_id);

            return redirect('store/' . $this->user->username . '/admin/categories/Category-updated');


        }

        return redirect('store/' . $this->user->username . '/admin/categories/Category-not-updated');
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getSubCategories($id, $message = NULL) {

        $previousAddedMainCategoryId = explode('_', $message);
        if (isset($previousAddedMainCategoryId[1])) {
            $previousAddedMainCategoryId = $previousAddedMainCategoryId[1];
        } else {
            $previousAddedMainCategoryId = 0;
        }

        $data['previousAddedMainCategoryId'] = $previousAddedMainCategoryId;

        $brand = $this->storeAdminRepository->isStoreBrand($id);

        $data['allSubCategories'] = $this->storeAdminRepository->getSubCategories($brand->id);
        $data['allCategories']    = $this->storeAdminRepository->getCategoriesList($brand->id);
        $data['url_user_id']      = $brand->id;
        if ($data != 0) {
            return view('Store::admin.Category.subCategories', $data);
        }

        return redirect()->back();
    }

    public function getSubCategoriesAjax() {

        $sub_category = Input::get('sub_category');
        $id           = Auth::user()->id;
        $data         = $this->storeAdminRepository->getSubCategoriesAjaxById($id, $sub_category);

        return $data;
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return mixed
     */
    public function storeSubCategories(Request $request, $id) {

        $user_id = Auth::user()->id;
        $existingSubCategoryName = $this->storeAdminRepository->existingCategory($request,$user_id);
        if ($existingSubCategoryName === TRUE) {
            return redirect('store/' . $this->user->username . '/admin/Subcategories/Sub-Category-not-created')->with('info', 'Subcategory or Category already exist try another one.');
        } else {
            // return redirect('store/Subcategories/'.$id.'/Sub-Category-created');
            $this->storeAdminRepository->store_sub_category($request);

            return redirect('store/' . $this->user->username . '/admin/Subcategories/Sub-Category-created_' . $request->category_parent_id)
                ->with('info', 'Record Saved Successfully.');

        }

        //  return redirect('store/' . $this->user->username . '/admin/Subcategories/Sub-Category-not-created');
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function deleteSubCategory(Request $request) {
        $category_id = $request->category_id;

        //  $owner_id = $this->storeAdminRepository->getCatOwnerId($category_id);

        if ($this->storeAdminRepository->is_category_owner($category_id)) {
            $this->storeAdminRepository->deleteCategory($category_id);

            return redirect("store/" . $this->user->username . "/admin/Subcategories");

        }

        return redirect("store/" . $this->user->username . "/admin/Subcategories");
    }


    /**
     * @param $category_id
     *
     * @return mixed
     */
    public function editSubCategory(Request $request) {
        $category_id = $request->category_id;
        $name        = Input::get('edited_name');

        $parent_id = Input::get('category_parent_id');

        // $owner_id = $this->storeAdminRepository->getCatOwnerId($category_id);
        if ($this->storeAdminRepository->is_category_owner($category_id)) {
            $this->storeAdminRepository->editSubCat($name, $category_id, $parent_id);

            return redirect("store/" . $this->user->username . "/admin/Subcategories/Sub-Categories-updated");


        }

        return redirect("store/" . $this->user->username . "/admin/Subcategories/Sub-Categories-not-updated");
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getProducts($id, $message='') {
        $data['info'] = $message;
        $brand               = $this->storeAdminRepository->isStoreBrand($id);
        $data['categories']  = $this->storeAdminRepository->getAllCategories($brand->id);
        $data['AllProducts'] = $this->storeAdminRepository->getAllProductByBrandId($brand->id);
        $data['url_user_id'] = $brand->id;
        if ($data['categories'] != 0) {
            return view('Store::admin.products.manageProduct', $data);
        }

        return redirect()->back();
    }

    /**
     * @param $id
     *
     * @return int|string
     */
    public function getProductsForSelection($id) {
        $brand          = $this->storeAdminRepository->isStoreBrand($id);
        $category_id    = Input::get("category");
        $Subcategory_id = Input::get("sub_category");
        if ($category_id > 0) {
            if ($Subcategory_id > 0) {
                $products = $this->storeAdminRepository->filtersProducts($category_id, $Subcategory_id);

                return json_encode($products);
            }
        }

        return 0;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function deleteProduct(Request $request) {
        $product_id = $request->product_id;
        //$owner_id = $this->storeAdminRepository->getProOwnerId($product_id);

        if ($this->storeAdminRepository->is_product_owner($product_id)) {
            $this->storeAdminRepository->deleteProduct($product_id);

            return redirect('store/' . $this->user->username . '/admin/manage-product/Product-deleted');
        }

        return redirect('store/' . $this->user->username . '/admin/manage-product/Product-not-deleted');
    }

    public function checkIfAlreadySubCatAjax(Request $request)
    {
        return $this->storeAdminRepository->getSameNameSubCategory( $request->owner_id, $request->category_id, $request->subcategory_name );
    }
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function editProduct(Request $request) {

        if ($this->storeAdminRepository->is_product_owner($request->product_id)) {
            $owner_id            = $this->storeAdminRepository->getProOwnerId($request->product_id);
            $data['url_user_id'] = $this->user->username;
            $data['product']     = $this->storeAdminRepository->getProductDetail($request->product_id);

            $data['features'] = $this->storeAdminRepository->getStoreProductKeyFeature($request->product_id);
            $data['techs']    = $this->storeAdminRepository->getStoreProductTechSpec($request->product_id);

            $productAttributes = $this->storeAdminRepository->getStoreProductAttributes($request->product_id);

            $data['productAttributeColors'] = [];
            $data['productAttributeSizes']  = [];

            if($productAttributes){
                foreach($productAttributes as $productAttribute){
                    if($productAttribute->attribute === 'size'){
                        $data['productAttributeSizes'][$productAttribute->id] = $productAttribute->value;
                    }

                    if($productAttribute->attribute === 'color'){
                        $data['productAttributeColors'][$productAttribute->id] = $productAttribute->value;
                    }
                }
            }

            $data['categories'] = $this->storeAdminRepository->getAllCategories($owner_id);

            // echo '<tt><pre>'; print_r($data); die;
            return view('Store::admin.products.addProduct', $data);
        }

        return redirect()->back()->with('info', 'you are not authorized.');
    }

    /**
     * @param Request $request
     * @param null    $product_id
     *
     * @return int
     */
    public function updateProduct(Request $request, $product_id = NULL) {
        $this->validate($request, [
            'category'     => 'required|integer',
            'sub_category' => 'required|integer',
            'title'        => 'required',
            'price'        => array('required', 'regex:/^\d*(\.\d{2})?$/'),
            'discount'     => array('required', 'regex:/^\d*(\.\d{2})?$/'),
            'quantity'     => 'required|min:1',
        ]);

        if ($this->storeAdminRepository->updateProduct($request, $product_id) > 0) {
            return 1;
        }

        return 0;
    }

    /**
     * @param Request $request
     * @param null    $product_id
     *
     * @return mixed
     */
    public function ProductReview(Request $request, $product_id = NULL) {
        $this->storeAdminRepository->storeReview($request, $product_id);

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param null    $product_id
     *
     * @return mixed
     */
    public function ProductReviewAjax(Request $request, $product_id = NULL) {

        $review = $this->storeAdminRepository->storeReview($request, $product_id, 1);

        $data             = getReviewStatusForBuyer($review, $request->store_name, $request->order_id);
        $data['order_id'] = $request->order_id . $product_id;

        return $data;
    }

    public function getStoreEarnings() {
        $data['url_user_id'] = $this->user->username;

        $storeRepo = new StoreRepository();
        $data['availableBalance'] = $storeRepo->getAvailableBalance($this->user->id);

        $data['totalSales']        = $this->storeAdminRepository->getTotalSalesCurrentUser($this->user->id);
        $data['currentMonthSales'] = $this->storeAdminRepository->getCurrentMonthSalesCurrentUser($this->user->id);

        return view('Store::admin.sales.storeTotalEarnings', $data);
    }

    public function updateOrderStatusAjax(Request $request) {
        $order_info = explode('_', $request->order_info);
        $status     = $order_info[1];
        $order_id   = $order_info[3];

        $isOrderSeller = $this->storeAdminOrderRepository->isOrderSeller($order_id, $this->user_id);
        if ($isOrderSeller < 1) {
            return 'not authorized';
        }

        $newStatusData = $this->updateOrderStatus($order_id, $status, "seller");
        if (is_array($newStatusData)) {
            return array_merge($newStatusData, ['order_id' => $order_id]);
        } else {
            return 'something wrong happened try again.';
        }
    }

    public function updateOrderStatus($order_id, $status, $subject,$is_refunded = 0,$refund_amount = 0) {

        $is_updated = $this->storeAdminOrderRepository->updateOrderStatus($order_id, $status, $subject,$is_refunded,$refund_amount);
        $order      = $this->storeAdminOrderRepository->getOrderStatus($order_id);
        if ($is_updated != '') {
            if ($this->user->user_type == 1) {
                $data = getOrderStatusForBuyer($order_id, $order->status, $order);
            } else {
                $data = getOrderStatusForSeller($order_id, $order->status);
            }

            return $data;
        }

        return 'Please try again.';
    }

    public function cancelOrder(Request $request) {
        $status   = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_CANCELED');
        $order_id = $request->order_id;
        $reason = $request->get('reason');

        $isOrderCustomer = $this->storeAdminOrderRepository->isOrderSeller($order_id, $this->user_id);
        if ($isOrderCustomer < 1) {
            return 'This order does not belongs to you? Please contact to Admin if problem persists.';
        }
        $worldpay = new Worldpay(\Config::get('constants_brandstore.WORLDPAY_SERVICE_KEY'));
        $transaction = StoreOrderTransaction::where('order_id',$order_id)
                    ->select(['id','amount','total_amount','state','gateway_transaction_id'])
                    ->first();
        
        if(empty($transaction->id)){
            return ['message_text' => 'There is no transaction for this order','status' => 'error'];
        }
        $order = StoreOrder::where('id',$order_id)
                            ->select(['id','total_price','total_discount','customer_id','seller_id'])
                            ->first();
        if(empty($order->id)){
            return ['message_text' => 'Invalid order','status' => 'error'];
        }
        $order_amount = $order->total_price - $order->total_discount;
        $order_code = $transaction->gateway_transaction_id;
        $amount = str_replace(',','',$transaction->amount);
        $refund_amount =  $amount * 100;
        $newStatusData = null;
        try {
            if($order_amount == $amount){
                $worldpay->refundOrder($order_code);
            }else{
                $worldpay->refundOrder($order_code,$refund_amount);
            }
            $is_refunded = 1;
            $newStatusData = $this->updateOrderStatus($order_id, $status, 'seller',$is_refunded,$amount);

            $order->cancellation_reason = $reason;
            $order->save();

            $stRepositoryObj = new StoreRepository();

            $reversal['parent_type'] = 'store_order';
            $reversal['parent_id'] = $order_id;
            $reversal['user_id'] = $order->customer_id;
            $reversal['seller_id'] = $order->seller_id;
            $reversal['amount'] = $refund_amount;

            $stRepositoryObj->logStoreReversal($reversal);

        } catch (WorldpayException $e) {
            return ['message_text' => $e->getMessage(),'status' => 'error'];
        } catch (Exception $e) {
            return ['message_text' => $e->getMessage(),'status' => 'error'];
        }
        if (is_array($newStatusData)) {
            //updating product amount on cancel
            $soldProducts = $this->storeAdminOrderRepository->getOrderAllProducts($order_id);
            foreach($soldProducts as $soldProduct){
                $soldQuantity = $this->storeAdminOrderRepository->getOrderProductItemQuantity($order_id, $soldProduct->id);
				$this->storeAdminRepository->updateProductQuantityByOperation($soldProduct->id, '+', $soldQuantity->quantity);
            }
            //end of updating product amount on cancel


            return array_merge($newStatusData, ['order_id' => $order_id,'status' => 'success']);
        } else {
            return 'something wrong happened try again.';
        }
    }

    public function softDeleteOrder(Request $request) {
        $order_info = explode('_', $request->order_info);
        $order_id   = $order_info[1];

        $isOrderCustomer = $this->storeAdminOrderRepository->isOrderSeller($order_id, $this->user_id);
        if ($isOrderCustomer < 1) {
            return 'This order does not belongs to you? Please contact to Admin if problem persists.';
        }

        $isOrderDeleted = $this->storeAdminOrderRepository->softDeleteOrder($order_id);

        if ($isOrderDeleted > 0) {
            return $isOrderDeleted;
        } else {
            return "Something wrong happened please try again.";
        }
    }

    public function productListingAnalytics($id) {
        $brand               = $this->storeAdminRepository->isStoreBrand($id);
        $data['categories']  = $this->storeAdminRepository->getAllCategories($brand->id);
        $data['AllProducts'] = $this->storeAdminRepository->getAllProductByBrandId($brand->id);
        $data['url_user_id'] = $brand->id;
        if ($data['categories'] != 0) {
            return view('Store::admin.product_analytics.productListingAnalytics', $data);
        }

        return redirect()->back();
    }

    public function getProductAnalytics(Request $request) {

        $data['product_id']  = $product_id = $request->product_id;
        $data['url_user_id'] = $store_owner = $this->user->username;

        $is_owner = $this->storeAdminRepository->is_product_owner($product_id);
        if ($is_owner < 1) {
            return redirect('store/' . $store_owner . '/admin/manage-product/Not-authorized')->with('info', 'Not authorized');
        }

        $data['product'] = $product = getProductDetailsByID($product_id);
        if ($product->quantity == 0) {
            $productQuantity = 1;
        } else {
            $productQuantity = $product->quantity;
        }
        if ($product->sold == 0) {
            $productSold = 0;
        } else {
            $productSold = $product->sold;
        }

        $data['salePercent'] = ($productSold / $productQuantity) * 100;

        $product_owner_id = $this->user_id;

        $product_statics      = $this->storeProductStatRepository->getProductViewStatics($product_id, $product_owner_id);
        $product_statics_hour = $this->storeProductStatRepository->getProductViewStaticsByHour($product_id, $product_owner_id);

        $product_statics_by_region = $this->storeProductStatRepository->getProductViewStaticsByRegion($product_id, $product_owner_id);

        $data['myAllCountries']         = $this->_preparedStatByRegion($product_statics_by_region);

        $data['preparedStatViews']      = $this->_preparedStatViews($product_statics);
        $data['preparedStatViewsHours'] = $this->_preparedStatViewsHours($product_statics_hour);
//echo '<tt><pre>'; print_r( $data['preparedStatViewsHours']); die;
        $data['product_statics_by_age'] = $this->storeProductStatRepository->getProductViewStaticsByAge($product_id, $product_owner_id);

        $data['region']   = $this->storeAdminRepository->getRegion();
        $data['now_date'] = Carbon::now()->toDateString();

        return view('Store::admin.product_analytics.productAnalytics', $data);

    }

    public function getPageAnalytics(Request $request) {
        $data['product_id']  = $product_id = $request->product_id;
        $data['url_user_id'] = $store_owner = $this->user->username;

        if ($request->storeBrandId != $store_owner) {
            return redirect('store/' . $store_owner . '/admin/manage-product/Not-authorized')->with('info', 'Not authorized');
        }

        $data['owner_id'] = $owner_id = $this->user_id;

        $data['product_statics_by_age'] = $this->storeProductStatRepository->getPageViewStaticsByAge($owner_id);
        $data['totalProductSoldCount']  = count($this->storeAdminRepository->getFinishedOrdersCurrentUser($owner_id));
        $data['totalProductQuantityCount']  = $this->storeAdminRepository->getTotalQuantityOfProductsCurrentUser($owner_id);

        $product_statics_by_region = $this->storeProductStatRepository->getPageViewStaticsByRegion($owner_id);

        $data['myAllCountries']         = $this->_preparedStatByRegion($product_statics_by_region);

        $product_statics           = $this->storeProductStatRepository->getPageViewStatics($owner_id);
        $data['preparedStatViews'] = $this->_preparedStatViews($product_statics);

        $product_statics_hour = $this->storeProductStatRepository->getPageViewStaticsByHour($owner_id);
        $data['preparedStatViewsHours'] = $this->_preparedStatViewsHours($product_statics_hour);


        $data['now_date'] = Carbon::now()->toDateString();

        return view('Store::admin.product_analytics.pageAnalytics', $data);
    }

    public function manageReviews($id, $message = NULL) {
        $data['url_user_id'] = $user_id = $this->user_id;

        $data['allOrders'] = $this->storeAdminRepository->getFinishedOrdersCurrentUser($user_id);

        $data['reviews'] = $reviews = $this->storeAdminRepository->getCurrentUserProductsReviews($user_id);

        return view('Store::admin.reviews.manageReviews', $data)->with('info', $message);
    }

    private function _preparedStatViews($product_statics) {
        $count             = 1;
        $preparedStatViews = '';
        foreach ($product_statics as $product_stat_view) {

            if (isset($product_stat_view->date)) {
                $preparedStatViews .= '{label: "' . Carbon::parse($product_stat_view->date)->format('M d') . '" , y: ' . $product_stat_view->count . ' },';
            }
            $count++;
        }
        $count = 0;

        return $preparedStatViews;
    }

    public function _preparedStatByRegion($product_statics_by_region) {
        $count          = 1;
        $myAllCountries = '';
        foreach ($product_statics_by_region as $region):
            if ($count < 6) {
                if ($count % 2 == 0) {
                    $color = "#78acc1";
                } else {
                    $color = "#c5d6dd";
                }
                $myAllCountries .= '{color: "' . $color . '", label: "' . $region->region . '",  y: ' . $region->count . '  }, ';
            }
            $count++;
        endforeach;

        return $myAllCountries;
    }

    public function addCourierServiceInfo(Request $request) {

        $request->seller_id = $this->user_id;

        $status   = 5;
        $order_id = $request->order_id;

        $isOrderSeller = $this->storeAdminOrderRepository->isOrderSeller($order_id, $this->user_id);
        if ($isOrderSeller < 1) {
            return 'not authorized';
        }

        $deliverCourier = $this->storeAdminRepository->AddDeliverCourierInfo($request);

        $newStatusData = $this->updateOrderStatus($order_id, $status, "seller");

        if (is_array($newStatusData)) {
            return array_merge($newStatusData, ['order_id' => $order_id]);
        } else {
            return 'something wrong happened try again.';
        }

        return $deliverCourier;
    }

    public function getOrderInvoice(Request $request) {

        $data['order_id']       = $order_id = $request->order_id;
        $data['url_user_id']    = $store_owner = Auth::user()->username;

        $data['order']          = $this->storeAdminOrderRepository->getOrderById($order_id);
        $data['orderCourier']   = $order = $this->storeAdminOrderRepository->getOrderCourierByOrderId($order_id);
        $data['orderAddresses'] = $order = $this->storeAdminOrderRepository->getOrderAddressesByOrderId($data['order']->delivery_address_id);
        $data['orderPayments']  = $order = $this->storeAdminOrderRepository->getOrderPaymentByOrderId($order_id);

        return view('Store::admin.orders.orderInvoice', $data);
    }

    public function getAddProductShippingCost(Request $request) {
        $data['url_user_id'] = $store_owner = $this->user->username;
        $data['user']        = $store_owner = $this->user;
        $product_id          = $request->product_id;

        $data['product']    = $product = $this->storeAdminRepository->getProductDetail($product_id);
        $data['allRegions'] = $this->storeAdminRepository->getAllRegions();
        $data['countries'] = $this->storeAdminRepository->getAllCountries();

        return view('Store::admin.products.addProductShippingCost', $data);

    }

    public function addProductShippingCost(Request $request) {
        $data['url_user_id'] = $store_owner = $this->user->username;


        $isAdded = $this->storeAdminRepository->addRegionCost($request);


        if ($isAdded == 1) {
            return redirect('store/' . $store_owner . '/admin/manage-product/shipping cost added')->with('info', 'Shipping Cost(s) added successfully.');
        } else {
            return redirect('store/' . $store_owner . '/admin/add-product-shipping-cost/' . $request->product_id . '/shipping cost not added')->with('info', 'Shipping Cost(s) not added.');
        }
    }

    public function sendRequestToRvise(Request $request) {
        $isRequestSend = $this->storeAdminRepository->sendReviewReviseRequest($request->review_id);

        return redirect('store/' . $this->user->username . '/admin/manage_reviews/request sent successfully');

    }

    public function statement(Request $request) {
        $transaction_type = \Input::get('transaction_type');
        $data                = $this->storeAdminRepository->statement($request->storeBrandId,$transaction_type);
        $data['url_user_id'] = $store_owner = $this->user->username;

        return view('Store::admin.sales.statement', $data);
    }

    private function _preparedStatViewsHours($product_statics) {


        /*$preparedStatViews = '';
        foreach ($product_statics as $product_stat_view) {

            $y = Carbon::parse($product_stat_view->created_at)->format('Y');
            $m = Carbon::parse($product_stat_view->created_at)->format('m');
            $d = Carbon::parse($product_stat_view->created_at)->format('d');
            $h = Carbon::parse($product_stat_view->created_at)->format('H');
            $i = Carbon::parse($product_stat_view->created_at)->format('i');
            if (isset($product_stat_view->hour)) {
                $preparedStatViews .= "{x: new Date(Date.UTC (" . $y . ", " . $m . ", " . $d . ", " . $h . ",0) ), y: " . $product_stat_view->count . " },";
            }

        }

        return $preparedStatViews;*/

        $preparedStatViews = [];
        foreach ($product_statics as $product_stat_view) {

            $y = Carbon::parse($product_stat_view->created_at)->format('Y');
            $m = Carbon::parse($product_stat_view->created_at)->format('m');
            $d = Carbon::parse($product_stat_view->created_at)->format('d');
           $h = ltrim(Carbon::parse($product_stat_view->created_at)->format('H'), '0');

            $i = Carbon::parse($product_stat_view->created_at)->format('i');
            if (isset($product_stat_view->hour)) {

                $data['year'] = $y;
                $data['month'] = $m;
                $data['days'] = $d;
                $data['hours'] = $h;
                $data['min'] = $i;
                $data['y'] = $product_stat_view->count;
                $preparedStatViews[$h] = $data;
                // $preparedStatViews .= "{x: new Date(Date.UTC (" . $y . ", " . $m . ", " . $d . ", " . $h . ",0) ), y: " . $product_stat_view->count . " },";
            }
        }


        $data = '';
        for($i=1; $i<= 24; $i++){
            if($i <= 12){
                $amPm = $i;//.' am';
            }else{
                $amPm = $i;//.' pm';
            }
            $hourData['label'] = $amPm;
            $hourData['y'] = 0;

            if(isset($preparedStatViews[$i])){
                $hourData['y'] =$preparedStatViews[$i]['y'];
            }
            //$data[] = $hourData;
            $data .='{label: "' . $hourData['label'] . '",  y: ' . $hourData['y'] . '  }, ';
        }
        //echo '<tt><pre>'; print_r($data); die;

        return $data;
    }

    public function number_views($product_owner_id, $product_id) {
        $product_owner_id = $this->user_id;
        $product_statics  = $this->storeProductStatRepository->getProductViewStatics($product_id, $product_owner_id);
        //echo '<tt><pre>'; print_r($product_statics);
        $data = [];
        foreach ($product_statics as $product_stat_view) {

            if (isset($product_stat_view->date)) {
                $preparedStatViews['label'] = Carbon::parse($product_stat_view->date)->format('M d');
                $preparedStatViews['y']     = $product_stat_view->count;
                $data[]                     = $preparedStatViews;
            }
        }

        return $data; //$this->_preparedStatViews($product_statics);
    }

    public function number_sales($product_owner_id, $product_id) {
        $product_owner_id = $this->user_id;
        $product_statics  = $this->storeProductStatRepository->getProductViewStatics($product_id, $product_owner_id);
        //echo '<tt><pre>'; print_r($product_statics);
        $data = [];
        foreach ($product_statics as $product_stat_view) {

            if (isset($product_stat_view->date)) {
                $preparedStatViews['label'] = Carbon::parse($product_stat_view->date)->format('M d');
                $preparedStatViews['y']     = $product_stat_view->count;
                $data[]                     = $preparedStatViews;
            }
        }

        return $data; //$this->_preparedStatViews($product_statics);
    }
    public function age_view($product_owner_id ,$product_id) {
        $product_owner_id = $this->user_id;
        $product_statics_by_age = $this->storeProductStatRepository->getProductViewStaticsByAge($product_id, $product_owner_id);

       $data['label'] ='10-25';
       $data['color'] ='#c5d6dd';
       $data['y'] =$product_statics_by_age['firstCountView'];
        $setData[] = $data;

        $data['label'] ='25-35';
        $data['color'] ='#78acc1';
        $data['y'] =$product_statics_by_age['secondCountView'];
        $setData[] = $data;

        $data['label'] ='35-45';
        $data['color'] ='#c5d6dd';
        $data['y'] =$product_statics_by_age['thirdCountView'];
        $setData[] = $data;

        $data['label'] ='45-55';
        $data['color'] ='#78acc1';
        $data['y'] =$product_statics_by_age['fourthCountView'];
        $setData[] = $data;

        $data['label'] ='> 55';
        $data['color'] ='#c5d6dd';
        $data['y'] =$product_statics_by_age['fifthCountView'];
        $setData[] = $data;
        return $setData; //$this->_preparedStatViews($product_statics);
    }

    public function gender_view($product_owner_id ,$product_id) {
        $product_owner_id = $this->user_id;
         $product_statics_by_age = $this->storeProductStatRepository->getProductViewStaticsByAge($product_id, $product_owner_id);

        $data['color'] ='#c5d6dd';
        $data['y'] =$product_statics_by_age['maleCountView'];
        $data['legendText'] ="Male ".round($product_statics_by_age['maleCountViewPercent']).'%';
        $data['indexLabel'] ="Male ".round($product_statics_by_age['maleCountViewPercent']).'%';
        $setData[] = $data;

        $data['color'] ='#dbbcce';
        $data['y'] =$product_statics_by_age['femaleCountView'];
        $data['legendText'] ="Female ".round($product_statics_by_age['femaleCountViewPercent']).'%';
        $data['indexLabel'] ="Female ".round($product_statics_by_age['femaleCountViewPercent']).'%';
        $setData[] = $data;

        return $setData; //$this->_preparedStatViews($product_statics);
    }
    public function country_view($product_owner_id ,$product_id) {
        $product_owner_id = $this->user_id;
       $product_statics_by_region = $this->storeProductStatRepository->getProductViewStaticsByRegion($product_id, $product_owner_id);
        $myAllCountries = [];
        $count = 1;
        foreach ($product_statics_by_region as $region):

                if ($count % 2 == 0) {
                    $color = "#78acc1";
                } else {
                    $color = "#c5d6dd";
                }
            $country['color'] = $color;
            $country['label'] = $region->region;
            $country['y'] = $region->count;
            $myAllCountries[] = $country;

            $count++;
        endforeach;

        return $myAllCountries;

    }

    public function peak_view($product_owner_id ,$product_id) {
        $product_owner_id = $this->user_id;
         $product_statics_hour = $this->storeProductStatRepository->getProductViewStaticsByHour($product_id, $product_owner_id);

        $preparedStatViews = [];
        foreach ($product_statics_hour as $product_stat_view) {

            $y = Carbon::parse($product_stat_view->created_at)->format('Y');
            $m = Carbon::parse($product_stat_view->created_at)->format('m');
            $d = Carbon::parse($product_stat_view->created_at)->format('d');
            $h = ltrim(Carbon::parse($product_stat_view->created_at)->format('H'), '0');

            $i = Carbon::parse($product_stat_view->created_at)->format('i');
            if (isset($product_stat_view->hour)) {

                $data['year'] = $y;
                $data['month'] = $m;
                $data['days'] = $d;
                $data['hours'] = $h;
                $data['min'] = $i;
                $data['y'] = $product_stat_view->count;
                $preparedStatViews[$h] = $data;
               // $preparedStatViews .= "{x: new Date(Date.UTC (" . $y . ", " . $m . ", " . $d . ", " . $h . ",0) ), y: " . $product_stat_view->count . " },";
            }
        }
        $data = [];
        for($i=01; $i<= 24; $i++){
            if($i <= 12){
                $amPm = $i;//.' am';
            }else{
                $amPm = $i;//.' pm';
            }
            $hourData['label'] = $amPm;
            $hourData['y'] = 0;

            if(isset($preparedStatViews[$i])){
                $hourData['y'] =$preparedStatViews[$i]['y'];
            }
            $data[] = $hourData;
        }
        //echo '<tt><pre>'; print_r($data); die;

        return $data;
    }

        public function number_views_page_stat($product_owner_id, $product_id) {
            $product_owner_id = $this->user_id;
            $product_statics  = $this->storeProductStatRepository->getPageViewStatics($product_owner_id);
            //echo '<tt><pre>'; print_r($product_statics);
            $data = [];
            foreach ($product_statics as $product_stat_view) {

                if (isset($product_stat_view->date)) {
                    $preparedStatViews['label'] = Carbon::parse($product_stat_view->date)->format('M d');
                    $preparedStatViews['y']     = $product_stat_view->count;
                    $data[]                     = $preparedStatViews;
                }
            }

            return $data; //$this->_preparedStatViews($product_statics);
        }

        public function number_sales_page_stat($product_owner_id, $product_id) {
            $product_owner_id = $this->user_id;
            $product_statics  = $this->storeProductStatRepository->getPageViewStatics($product_owner_id);
            //echo '<tt><pre>'; print_r($product_statics);
            $data = [];
            foreach ($product_statics as $product_stat_view) {

                if (isset($product_stat_view->date)) {
                    $preparedStatViews['label'] = Carbon::parse($product_stat_view->date)->format('M d');
                    $preparedStatViews['y']     = $product_stat_view->count;
                    $data[]                     = $preparedStatViews;
                }
            }

            return $data; //$this->_preparedStatViews($product_statics);
        }
        public function age_view_page_stat($product_owner_id ,$product_id) {
            $product_owner_id = $this->user_id;
            $product_statics_by_age = $this->storeProductStatRepository->getPageViewStaticsByAge($product_owner_id);

            $data['label'] ='10-25';
            $data['color'] ='#c5d6dd';
            $data['y'] =$product_statics_by_age['firstCountView'];
            $setData[] = $data;

            $data['label'] ='25-35';
            $data['color'] ='#78acc1';
            $data['y'] =$product_statics_by_age['secondCountView'];
            $setData[] = $data;

            $data['label'] ='35-45';
            $data['color'] ='#c5d6dd';
            $data['y'] =$product_statics_by_age['thirdCountView'];
            $setData[] = $data;

            $data['label'] ='45-55';
            $data['color'] ='#78acc1';
            $data['y'] =$product_statics_by_age['fourthCountView'];
            $setData[] = $data;

            $data['label'] ='> 55';
            $data['color'] ='#c5d6dd';
            $data['y'] =$product_statics_by_age['fifthCountView'];
            $setData[] = $data;
            return $setData; //$this->_preparedStatViews($product_statics);
        }

        public function gender_view_page_stat($product_owner_id ,$product_id) {
            $product_owner_id = $this->user_id;
            $product_statics_by_age = $this->storeProductStatRepository->getPageViewStaticsByAge($product_owner_id);

            $data['color'] ='#c5d6dd';
            $data['y'] =$product_statics_by_age['maleCountView'];
            $data['legendText'] ="Male ".round($product_statics_by_age['maleCountViewPercent']).'%';
            $data['indexLabel'] ="Male ".round($product_statics_by_age['maleCountViewPercent']).'%';
            $setData[] = $data;

            $data['color'] ='#dbbcce';
            $data['y'] =$product_statics_by_age['femaleCountView'];
            $data['legendText'] ="Female ".round($product_statics_by_age['femaleCountViewPercent']).'%';
            $data['indexLabel'] ="Female ".round($product_statics_by_age['femaleCountViewPercent']).'%';
            $setData[] = $data;

            return $setData; //$this->_preparedStatViews($product_statics);
        }
        public function country_view_page_stat($product_owner_id ,$product_id) {
            $product_owner_id = $this->user_id;
            $product_statics_by_region = $this->storeProductStatRepository->getPageViewStaticsByRegion($product_owner_id);
            $myAllCountries = [];
            $count = 1;
            foreach ($product_statics_by_region as $region):

                if ($count % 2 == 0) {
                    $color = "#78acc1";
                } else {
                    $color = "#c5d6dd";
                }
                $country['color'] = $color;
                $country['label'] = $region->region;
                $country['y'] = $region->count;
                $myAllCountries[] = $country;

                $count++;
            endforeach;

            return $myAllCountries;

        }

        public function peak_view_page_stat($product_owner_id ,$product_id) {
            $product_owner_id = $this->user_id;
            $product_statics_hour = $this->storeProductStatRepository->getPageViewStaticsByHour($product_owner_id);

            $preparedStatViews = [];
            foreach ($product_statics_hour as $product_stat_view) {

                $y = Carbon::parse($product_stat_view->created_at)->format('Y');
                $m = Carbon::parse($product_stat_view->created_at)->format('m');
                $d = Carbon::parse($product_stat_view->created_at)->format('d');
                $h = ltrim(Carbon::parse($product_stat_view->created_at)->format('H'), '0');

                $i = Carbon::parse($product_stat_view->created_at)->format('i');
                if (isset($product_stat_view->hour)) {

                    $data['year'] = $y;
                    $data['month'] = $m;
                    $data['days'] = $d;
                    $data['hours'] = $h;
                    $data['min'] = $i;
                    $data['y'] = $product_stat_view->count;
                    $preparedStatViews[$h] = $data;
                    // $preparedStatViews .= "{x: new Date(Date.UTC (" . $y . ", " . $m . ", " . $d . ", " . $h . ",0) ), y: " . $product_stat_view->count . " },";
                }
            }
            $data = [];
            for($i=01; $i<= 24; $i++){
                if($i <= 12){
                    $amPm = $i;//.' am';
                }else{
                    $amPm = $i;//.' pm';
                }
                $hourData['label'] = $amPm;
                $hourData['y'] = 0;

                if(isset($preparedStatViews[$i])){
                    $hourData['y'] =$preparedStatViews[$i]['y'];
                }
                $data[] = $hourData;
            }
            //echo '<tt><pre>'; print_r($data); die;

            return $data;
        }
        public function getCountriesByRegion(){
            $region = \Request::get('region');
            $data['countries'] = $this->storeAdminRepository->getCountriesByRegion($region);
            
            return response()->json($data);
        }
}
