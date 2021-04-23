<?php

namespace kinnect2Store\Store\Repository\admin;

use App\ActivityAction;
use App\Country;
use App\Repository\Eloquent\ActivityActionRepository;
use Form;
use Image;
use Input;
use kinnect2Store\Store\DeliveryCourier;
use kinnect2Store\Store\Repository\StoreRepository;
use kinnect2Store\Store\StoreAlbumPhotos;
use kinnect2Store\Store\StoreAlbums;
use kinnect2Store\Store\StoreOrder;
use kinnect2Store\Store\StoreOrderItems;
use kinnect2Store\Store\StoreProductAttribute;
use kinnect2Store\Store\StoreProductFeature;
use kinnect2Store\Store\StoreProductReview;
use kinnect2Store\Store\StoreShippingCost;
use kinnect2Store\Store\StoreShippingCountries;
use kinnect2Store\Store\StoreShippingCountry;
use kinnect2Store\Store\StoreShippingRegion;
use kinnect2Store\Store\StoreStorageFiles;
use kinnect2Store\Store\StoreTransaction;
use LucaDegasperi\OAuth2Server\Authorizer;
use kinnect2Store\Store\StoreProduct;
use kinnect2Store\Store\Category;
use App\Facades\UrlFilter;
use App\StorageFile;
use App\AlbumPhoto;
use Carbon\Carbon;
use App\Album;
use App\User;
use Auth;
use DB;
use App\Events\ActivityLog;

class StoreAdminRepository
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

    /**
     * @param $category_id
     *
     * @return null
     */
    public function is_category_owner($category_id) {
        $category = Category::find($category_id);

        if (isset($category->id)) {
            if ($category->owner_id == Auth::user()->id) {
                return $category->id;
            }
        } else {
            return NULL;
        }
    }

    /**
     * @param $category_id
     */
    public function deleteCategory($category_id) {
        StoreProduct::where('sub_category_id', $category_id)->delete();
        StoreProduct::where('category_id', $category_id)->delete();
        Category::where('category_parent_id', $category_id)->delete();
        Category::where('id', $category_id)->delete();
    }


    /**
     * @param $request
     *
     * @return int
     */
    public function store_category($request) {
        $newCategory = new Category();

        $newCategory->name     = $request->name;
        $newCategory->owner_id = Auth::user()->id;

        if ($newCategory->save()) {
            return $newCategory->id;
        }

        return 0;
    }

    /**
     * @param $id
     *
     * @return int
     */
    public function get_category($id) {
        $categories = Category::where('category_parent_id', 0)->where('owner_id', $id)->get();

        if (count($categories) > 0) {
            return $categories;
        } else {
            return 0;
        }
    }

    /**
     * @param $id
     *
     * @return int
     */
    public function getCategoriesList($id) {

        $categoriesSelect = ['0' => 'Select Category *'];
        $categories       = DB::table('store_product_categories')->where('category_parent_id', 0)
                            ->where('owner_id', $id)
                            ->where('deleted_at', '=', null)
                            ->lists('name', 'id');

        if (count($categories) > 0) {
             $categories       = $categoriesSelect + $categories;
            return $categories;
        } else {
            return 0;
        }

//        $categories = Category::where('category_parent_id', 0)->where('owner_id', $id)->lists('name', 'id');
//        if (count($categories) > 0) {
//            return $categories ;
//        } else {
//            return 0;
//        }
    }
    // ==================== Ubaid code ============================

    /**
     * @param $product_id
     *
     * @return int
     */
    public function getStoreProductKeyFeature($product_id) {

        $feature = DB::table('store_product_features')
            ->where('pr_id', $product_id)
            ->where('is_deleted',0)
            ->where('key_feature_type', 1)
            ->get();
        if (count($feature) > 0) {
            return $feature;
        } else {
            return 0;
        }
    }

    /**
     * @param $product_id
     *
     * @return int
     */
    public function getStoreProductTechSpec($product_id) {

        $feature = DB::table('store_product_features')
            ->where('pr_id', $product_id)
            ->where('is_deleted',0)
            ->where('key_feature_type', 2)
            ->get();
        if (count($feature) > 0) {
            return $feature;
        } else {
            return 0;
        }
    }

    /**
     * @param $product_id
     *
     * @return int
     */
    public function getStoreProductAttributes($product_id) {

        $attributes = DB::table('store_product_attributes')
            ->where('is_deleted',0)
            ->where('product_id', $product_id)
            ->get();
        
        if (count($attributes) > 0) {
            return $attributes;
        } else {
            return 0;
        }
    }

    /**
     * @param $id
     *
     * @return array|int
     */
    public function getAllCategories($id) {
        if (Auth::user()->id == $id) {
            $categoriesSelect = ['0' => 'Select Category *'];
            $categories       = DB::table('store_product_categories')
                ->where('category_parent_id', 0)
                ->where('owner_id', $id)
                ->where('deleted_at', '=' , null)
                ->lists('name', 'id');

//			$categories = array_merge($categoriesSelect, $categories); //This will re-Adjust ids of option in select html tag
            $categories = $categoriesSelect + $categories;

            return $categories;
        }

        return 0;
    }

    /**
     * @param string $file_path
     * @param string $parent_file_id
     * @param string $owner_id
     * @param string $owner_type
     * @param string $image_size_type
     * @param string $image_height
     * @param string $image_width
     *
     * @return int
     */
    public function resizeProductImage($file_path = '', $parent_file_id = '', $owner_id = '', $owner_type = '', $image_size_type = 'NULL', $image_height = '', $image_width = '', $product_id='') {
        //Where file exists
        $file_path = "local/storage/app/photos/" . $file_path;

        if ($file_path != '' AND $parent_file_id != '') {
            //making thumbs
            // File name (To retrieve image with correct params)
            $file_name = time() . rand(111111111, 9999999999);

            //Where file is going to be saved.
            $folder_path   = "local/storage/app/photos/" . $owner_id;
            $file_name_new = $owner_id . "_" . $file_name . ".jpg";

            // <editor-fold desc="resizing product image">
            $image1 = Image::make($file_path)->encode('jpg');
            $image1->resize($image_width, $image_height);

            $file_path = $folder_path . '/' . $file_name_new;

            if ($image1->save($file_path)) {
                $this->addResizePhotoInStorageFile($parent_file_id, $owner_id . '/' . $file_name_new, $file_name_new, $image_size_type, $owner_id, $owner_type, $product_id);
            } else {
                return 0;
            }
            // </editor-fold>
        }
    }

    /**
     * @param $parent_file_id
     * @param $file_path
     * @param $file_name
     * @param $image_size_type
     * @param $owner_id
     * @param $owner_type
     */
    public function addResizePhotoInStorageFile($parent_file_id, $file_path, $file_name, $image_size_type, $owner_id, $owner_type, $product_id='') {

        // $file = new StorageFile();
        $file = new StoreStorageFiles();

        $file->parent_file_id = $parent_file_id;
        $file->type           = $image_size_type;
        $file->parent_id      = $product_id;
        $file->parent_type    = 'album_photo';
        $file->user_id        = $owner_id;
        $file->storage_path   = $file_path;
        $file->name           = $file_name;
        $file->mime_type      = 'image/jpeg';
        $file->extension      = 'jpg';
        $file->mime_major     = 'image';

        $file->save();
    }

    /**
     * @param $request
     *
     * @return int
     */
    public function addProduct($request) {

        $product = new StoreProduct();

        $product->title           = $request->title;
        $product->length          = $request->length;
        $product->width           = $request->width;
        $product->height          = $request->height;
        $product->weight          = $request->weight;
        $product->price           = $request->price;
        $product->discount        = $request->discount;
        $product->quantity        = $request->quantity;
        $product->description     = $request->description;
        $product->category_id     = $request->category;
        $product->sub_category_id = $request->sub_category;
        $product->is_featured     = !empty($request->is_featured) ? 1 : 0;
        $product->owner_id        = Auth::user()->id;

        $product->save();

        $product_features_title  = $request->get('featuretitle');
        $product_features_detail = $request->get('keyfeaturedetail');

        if(!empty($product_features_title)) {
            foreach ($product_features_title as $key => $feature) {
                if(empty($product_features_title[$key]) || empty($product_features_detail[$key])){
                    continue;
                }
                DB::table('store_product_features')->insert([
                    'pr_id' => $product->id,
                    'title' => $product_features_title[$key],
                    'detail' => $product_features_detail[$key],
                    'key_feature_type' => 1,
                ]);
            }
        }

        $product_tech_title      = $request->get('techtitle');
        $product_tech_detail     = $request->get('techspecs');

        if(!empty($product_tech_title)) {
            foreach ($product_tech_title as $key => $feature) {
                if(empty($product_tech_title[$key]) || empty($product_tech_detail[$key])){
                    continue;
                }
                DB::table('store_product_features')->insert([
                    'pr_id' => $product->id,
                    'title' => $product_tech_title[$key],
                    'detail' => $product_tech_detail[$key],
                    'key_feature_type' => 2,
                ]);
            }
        }

        $product_colors_title      = $request->get('colortitle');
        $product_colors_detail     = $request->get('colordetail');

        if(!empty($product_colors_title)) {

            foreach ($product_colors_title as $key => $feature) {
                if(empty($product_colors_title[$key]) || empty($product_colors_detail[$key])){
                    continue;
                }
                DB::table('store_product_attributes')->insert([
                    'product_id' => $product->id,
                    'attribute' => $product_colors_title[$key],
                    'value' => $product_colors_detail[$key],
                ]);
            }
        }

        $product_sizes_title      = $request->get('sizetitle');
        $product_sizes_detail     = $request->get('sizedetail');

        if(!empty($product_sizes_title)) {
            foreach ($product_sizes_title as $key => $feature) {
                if(empty($product_sizes_title[$key]) || empty($product_sizes_detail[$key])){
                    continue;
                }
                DB::table('store_product_attributes')->insert([
                    'product_id' => $product->id,
                    'attribute' => $product_sizes_title[$key],
                    'value' => $product_sizes_detail[$key],
                ]);
            }
        }

        if (isset($product->id)) {
            //Update images records
            // Create album for product

            // $album              = new Album();
//            $album = new StoreAlbums();
//
//            $album->title       = 'Product Album';
//            $album->description = $product->title . "'s album'";
//            $album->owner_type  = 'product';
//            $album->owner_id    = $product->id;
//            $album->category_id = 0;
//            $album->type        = 'product-profile';
//            $album->photo_id    = 0;
//
//            $album->save();

            //end of album creation

            $fileIds = explode(",", $request->images_ids);

            foreach ($fileIds as $fileId) {
                //$file = StorageFile::where('file_id', $fileId)->first();
                $file = StoreStorageFiles::where('file_id', $fileId)->first();
                // File name (To retrieve image with correct params)
                $file_name = time() . rand(1111111111, 9999999999);

                $folder_path   = "local/storage/app/photos/" . $product->owner_id;
                $file_name_new = $product->owner_id . "_" . $file_name . "." . $file->extension;

                if (isset($file->file_id)) {

                    if (file_exists("local/storage/app/photos/" . $file->storage_path) == TRUE) {

                        if (!file_exists($folder_path)) {
                            if (!mkdir($folder_path, 0777, TRUE)) {
                                $folder_path = '';
                            }
                        }

                        rename("local/storage/app/photos/" . $file->storage_path, $folder_path . "/" . $file_name_new);
                    }

                    // Saving photos
                    //$photoObj = new AlbumPhoto();
//                    $photoObj = new StoreAlbumPhotos();
//
//                    $photoObj->owner_type = 'product';
//                    $photoObj->owner_id   = $product->id;
//                    $photoObj->file_id    = $file->file_id;
//                    $photoObj->title      = $product->title;
                   // $photoObj->album_id   = $album->album_id;

//                    if ($photoObj->save()) {
                        $file->parent_id    = $product->id;//photo_id
                        $file->user_id      = $product->owner_id;
                        $file->storage_path = $product->owner_id . "/" . $file_name_new;
                        $file->name         = $file_name;
                        $file->mime_major   = 'image';

                        $file->save();

                        $imageFilePath = $product->owner_id . "/" . $file_name_new;

                        $this->resizeProductImage($imageFilePath, $file->file_id, $file->user_id, 'product', 'product_profile', '151', '210', $product->id);
                        $this->resizeProductImage($imageFilePath, $file->file_id, $file->user_id, 'product', 'product_thumb', '170', '170', $product->id);
                        $this->resizeProductImage($imageFilePath, $file->file_id, $file->user_id, 'product', 'product_icon', '54', '80', $product->id);
//                    }
                    //End of saving photos

                }

            }

//            $options = array(
//                          'type'         => \Config::get('constants_activity.OBJECT_TYPES.PRODUCT.ACTIONS.CREATE'),
//                          'subject'      => Auth::user()->id,
//                          'subject_type' => 'user',
//                          'object'       => $product->id,
//                          'object_type'  => \Config::get('constants_activity.OBJECT_TYPES.PRODUCT.NAME'),
//                          'body'         => '{item:$subject} added new product {item:$object}',
//                      );
//            \Event::fire(new ActivityLog($options));

            return $product->id;
        }

        return 0;
    }


    /**
     * @param $product_id
     *
     * @return mixed
     */
    public function getProductDetail($product_id) {
        return $product = StoreProduct::where('id', $product_id)->first();
    }
    public function getRegionIDByName($name){
        $region = StoreShippingRegion::where('name','like',$name)->select('id','name')->first();

        return @$region->id;
    }
    public function getRegion() {
        /* $region_id = DB::table('store_delivery_addresses')->lists( 'country_id', 'id' );

         foreach($region_id as $region_ids){
         $region = DB::table('countries')->where( 'id', $region_ids )->lists('id' ,'name');

         }*/
        $region = DB::table('countries')->get();

        return $region;
    }


// ==================== end of Ubaid Code =====================


// ==================== Mustabeen code ============================

	/**
	 * @param $id
	 *
	 * @return int
	 */
	public function get_sub_category( $id ) {
		if ( Auth::user()->id == $id ) {
			$data['allCategories']    = Category::where( 'category_parent_id', '!=', 0 )->where( 'owner_id', $id )->lists( 'name', 'id' )->prepend( 'Select a category', '' )->toArray();
			$data['allSubCategories'] = Category::where( 'category_parent_id', '!=', 0 )->where( 'owner_id', $id )->get();

			return $data;
		}

		return 0;
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getSubCategories( $id ) {
		$allSubCategories = Category::where( 'category_parent_id', '!=', 0 )->where( 'owner_id', $id )->get();

		return $allSubCategories;
	}
    public function getSubCategoriesAjaxById( $id,$sub_category) {

        $allSubCategories = Category::where( 'category_parent_id', '=', $sub_category )->where( 'owner_id', $id )->get();
        $allCategories = Category::where( 'category_parent_id', '=', 0 )->where( 'owner_id', $id )->lists('name', 'id');

$html ='';
        foreach($allSubCategories as $Subcategory):
            if(!isset($Subcategory->id)){continue;}
            $html .= '<div class="categoryList" id="categoryList">
    <div>'.$Subcategory->name.'</div>';

            $html .= '<div class="actW">
            <a class="js-open-modal" data-modal-id="popup1-'.$Subcategory->id.'" title="Edit '.$Subcategory->name.'">
                <span class="editProduct ml20 mr20"></span>
            </a>
            <a class="js-open-modal" data-modal-id="popup2-'.$Subcategory->id.'" title="Delete '.$Subcategory->name.'" href="#">
                <span class="deleteProduct"></span>
            </a>
        </div>
        </div>';

 $html .= '
<form method="POST" action="'.url("store/".Auth::user()->username."/admin/edit/Subcategory/".$Subcategory->id).'" accept-charset="UTF-8">
        <div class="modal-box" id="popup1-'.$Subcategory->id.'" style="top: 128.333px; left: 770.5px; display: none;">
         <a href="#" class="js-modal-close close fltR">�</a>
             <div class="modal-body">
                 <div class="edit-photo-poup ">
                     <h3 style="color: #0080e8">Edit Category</h3>
                         <div class="m0">';
                                    $html .='<div class="mb10">
                                         '.Form::select('category_parent_id', $allCategories, $Subcategory->category_parent_id, ['id'=>'select1' ,
                                              'class' => 'selectList m0',
                                              'type' => 'required']).'
                                    </div>';
                         $html .='</div>
                     <h3 style="color: #0080e8" class="mt10">Subcategory Name:</h3>
                     <input required="required" type="text" name="edited_name" value="'.$Subcategory->name.'" placeholder="" style="width:300px" class="storeInput">
                         <div class="form-container mt10">
                                 <div class="saveArea">
                                    '.Form::submit('Update', ['class' => 'btn blue fltL']) .'
                                 </form>
                                 </div>
                         </div>
                 </div>
             </div>
         </div>
    </div>';
            $html .= '<form method="Get" action="'. url( "store/".Auth::user()->username."/admin/delete/Subcategory/".$Subcategory->id ) . '" accept-charset="UTF-8">
<div class="modal-box" id="popup2-'. $Subcategory->id.'" style="top: 128.333px; left: 770.5px; display: none;">
         <a href="#" class="js-modal-close close fltR">�</a>
         <div class="modal-body">
             <div class="edit-photo-poup">
                         <h3 style="color: #0080e8">Delete Category</h3>
                         <p class="mt10" style="width: 315px;line-height: normal">Are You Sure You Want To delete This Sub-category? All the Sub-categories and products will also be deleted</p>
                         <div class="m0">
                                <div class="wall-photos">
                                     <div class="photoDetail">
                                         <div class="form-container">
                                             <div class="saveArea">
                                              ' . Form::submit( 'Delete', [ 'class' => 'btn fltL blue mr10' ] ) . '
                                              ' . Form::submit( 'Cancel', [ 'class' => 'btn blue js-modal-close fltL close' ] ) . '
                                               </form>
                                             </div>
                                         </div>
                                     </div>
                                </div>
                         </div>
                  </div>
            </div>
         </div>';
endforeach;

        return $html;
    }

    public function getSubCategoriesId( $request ,$id ) {
        $allSubCategories = Category::where( 'category_parent_id', '=', $request->category_parent_id )->where( 'owner_id', $id )->get();

        return $allSubCategories;
    }
    public function existingCategory( $request,$user_id ) {

        $existing = Category::where('name', $request->name)->where( 'owner_id', $user_id )->count();

        if($existing > 0){
            return true;
        }

        return false;
}
	/**
	 * @param $request
	 *
	 * @return bool|int
	 */
	public function store_sub_category( $request ) {

		$newSubCategory = new Category();
		$newSubCategory->name     = $request->name;
		$newSubCategory->owner_id = Auth::user()->id;
		if ( $request->category_parent_id == 0 ) {
			return false;
		}
		$newSubCategory->category_parent_id = $request->category_parent_id;

		if ( $newSubCategory->save() ) {
			return $newSubCategory->id;

		}

		return 0;
	}

	/**
	 * @param $product_id
	 *
	 * @return null
	 */
	public function getProductStoreName( $product_id ) {
		$product = StoreProduct::find( $product_id );

		if ( isset( $product->id ) ) {

			$storeName = User::select('username')->where('id', $product->owner_id)->first();

			if(isset($storeName->username)){
				return $storeName->username;
			}
		}

		return null;
	}

	/**
	 * @param $product_id
	 *
	 * @return null
	 */
	public function is_product_owner( $product_id ) {
		$product = StoreProduct::find( $product_id );

		if ( isset( $product->id ) ) {
			if ( $product->owner_id == Auth::user()->id ) {
				return $product->id;
			}
		} else {
			return null;
		}
	}

	/**
	 * @param $product_id
	 *
	 * @return mixed
	 */
	public function deleteProduct( $product_id ) {
	    $aaObj = new ActivityActionRepository();
        $activity_action_id = ActivityAction::whereType('product_create')
                                        ->where('subject_id',$this->user_id)
                                        ->where('object_id',$product_id)
                                        ->value('action_id');
        $aaObj->deleteActivity($this->user_id,$activity_action_id);
		return StoreProduct::where( 'id', $product_id )->delete();
	}

	/**
	 * @param $category_id
	 * @param $Subcategory_id
	 *
	 * @return mixed
	 */
	public function filtersProducts( $category_id, $Subcategory_id ) {
		$products = DB::table( 'store_products' )
		              ->select( 'id', 'title', 'price', 'owner_id', 'quantity', 'sold', 'description as image' )
		              ->where( 'category_id', $category_id )
		              ->where( 'sub_category_id', $Subcategory_id )
		              ->where( 'deleted_at', '=', NULL)
		              ->groupBy( 'store_products.id' )
		              ->orderBy( 'id', 'DESC' )
		              ->get();

		foreach ( $products as $product ) {
			$product->image = getProductPhotoSrc( '', '', $product->id, 'product_icon' );
		}

		return ( $products );
	}

	/**
	 * @param $name
	 * @param $category_id
	 */
	public function editCat( $name, $category_id ) {
		$category             = Category::where( 'id', $category_id )->first();
		$category->name       = $name;
		$category->updated_at = Carbon::now();
		$category->save();
	}

	/**
	 * @param $name
	 * @param $category_id
	 * @param $parent_id
	 */
	public function editSubCat( $name, $category_id, $parent_id ) {
		$category                     = Category::where( 'id', $category_id )->first();
		$category->name               = $name;
		$category->category_parent_id = $parent_id;
		$category->updated_at         = Carbon::now();
		$category->save();
	}

	/**
	 * @param $request
	 *
	 * @return int
	 */
	public function updateProduct( $request, $isUpdate ) {

		$product = $this->_updateProduct( $request );

		if ( count( $product ) > 0 ) {
			$this->_updateProductFeatures( $request );

            $this->_updateProductAttributes( $request );

            return $request->product_id;

		} else {
			//not updated
			return 0;
		}
	}

	/**
	 * @param $request
	 *
	 * @return mixed
	 */
	public function _updateProduct( $request ) {
		return $product = DB::table( 'store_products' )->where( 'id', $request->product_id )->where( 'owner_id', Auth::user()->id )
		                    ->update( [
			                    'title'           => $request->title,
			                    'length'          => $request->length,
			                    'width'           => $request->width,
			                    'height'          => $request->height,
			                    'price'           => $request->price,
			                    'discount'        => $request->discount,
			                    'quantity'        => $request->quantity,
			                    'description'     => $request->description,
			                    'category_id'     => $request->category,
			                    'sub_category_id' => $request->sub_category,
                                'is_featured'     => !empty($request->is_featured) ? 1 : 0
		                    ] );
	}

	public function _uploadProductNewPhotos( $request, $album='' ) {
		$owner_id = Auth::user()->id;

		$fileIds = explode( ",", $request->images_ids );
		foreach ( $fileIds as $fileId ) {
			if ( strpos( $fileId, 'no_deletion_' ) !== false ) {
				continue;
			}

			//$file = StorageFile::where('file_id', $fileId)->first();
			$file          = StoreStorageFiles::where( 'file_id', $fileId )->first();
			$alreadyExists = StoreStorageFiles::where( 'parent_file_id', $fileId )->first();

			if ( isset( $alreadyExists->parent_file_id ) ) {
				continue;
			}

			// File name (To retrieve image with correct params)
			$file_name = time() . rand( 111111111, 9999999999 );

			$folder_path   = "local/storage/app/photos/" . $owner_id;
            if(isset($file->extension)){
                $fileExtension = $file->extension;
            }else{
                $fileExtension = '.jpeg';
            }
			$file_name_new = $owner_id . "_" . $file_name . "." . $fileExtension;

			if ( isset( $file->file_id ) ) {

				if ( file_exists( "local/storage/app/photos/" . $file->storage_path ) == true ) {

					if ( ! file_exists( $folder_path ) ) {
						if ( ! mkdir( $folder_path, 0777, true ) ) {
							$folder_path = '';
						}
					}

					rename( "local/storage/app/photos/" . $file->storage_path, $folder_path . "/" . $file_name_new );
				}

				// Saving photos
//				$photoObj = new StoreAlbumPhotos();
//
//				$photoObj->owner_type = 'product';
//				$photoObj->owner_id   = $request->product_id;
//				$photoObj->file_id    = $file->file_id;
//				$photoObj->title      = $request->title;
//				$photoObj->album_id   = $album->album_id;
//
//				if ( $photoObj->save() ) {
					$file->parent_id    = $request->product_id;//photo_id
					$file->user_id      = $owner_id;
					$file->storage_path = $owner_id . "/" . $file_name_new;
					$file->name         = $file_name;
					$file->mime_major   = 'image';

					$file->save();

					$imageFilePath = $owner_id . "/" . $file_name_new;

					$this->resizeProductImage( $imageFilePath, $file->file_id, $file->user_id, 'product', 'product_profile', '151', '210', $request->product_id );
					$this->resizeProductImage( $imageFilePath, $file->file_id, $file->user_id, 'product', 'product_thumb', '170', '170', $request->product_id);
					$this->resizeProductImage( $imageFilePath, $file->file_id, $file->user_id, 'product', 'product_icon', '54', '80', $request->product_id );
//				}
				//End of saving photos
			}
		}
	}

    /**
     * @param $request
     *
     * @return int
     */
    public function _updateProductAttributes( $request ) {
        $product_color_id = $request->get('colorID');
        $product_colors_title      = $request->get('colortitle');
        $product_colors_detail     = $request->get('colordetail');

        $attributeID = [];
        foreach ($product_colors_title as $key => $feature) {
            $spaOBJ = new StoreProductAttribute();
            $already = null;
            if(isset($product_color_id[$key])){
                $already = $spaOBJ->where('id',$product_color_id[$key])->first();
                if(!empty($already->id)){
                    $already->product_id = $request->get('product_id');
                    $already->attribute = $product_colors_title[$key];
                    $already->value = $product_colors_detail[$key];
                    $already->save();
                    $attributeID[] = $already->id;
                }
            }

            if(empty($already->id)){
                $spaOBJ->product_id = $request->get('product_id');
                $spaOBJ->attribute = $product_colors_title[$key];
                $spaOBJ->value = $product_colors_detail[$key];
                $spaOBJ->save();

                $attributeID[] = $spaOBJ->id;
            }
        }

        $product_size_id = $request->get('sizeID');
        $product_sizes_title      = $request->get('sizetitle');
        $product_sizes_detail     = $request->get('sizedetail');

        foreach ($product_sizes_title as $key => $feature) {
            $spaOBJ = new StoreProductAttribute();
            $already = null;
            if(isset($product_size_id[$key])){
                $already = $spaOBJ->where('id',$product_size_id[$key])->first();
                if(!empty($already->id)){
                    $already->product_id = $request->get('product_id');
                    $already->attribute = $product_sizes_title[$key];
                    $already->value = $product_sizes_detail[$key];
                    $already->save();
                    $attributeID[] = $already->id;
                }
            }

            if(empty($already->id)){
                $spaOBJ->product_id = $request->get('product_id');
                $spaOBJ->attribute = $product_sizes_title[$key];
                $spaOBJ->value = $product_sizes_detail[$key];
                $spaOBJ->save();

                $attributeID[] = $spaOBJ->id;
            }
        }

        if(!empty($attributeID)){
            StoreProductAttribute::where('product_id',$request->get('product_id'))
                ->whereNotIn('id',$attributeID)
                ->update(['is_deleted' => 1]);
        }else{
            StoreProductAttribute::where('product_id',$request->get('product_id'))->update(['is_deleted' => 1]);
        }

        return 1;
    }

	/**
	 * @param $request
	 *
	 * @return int
	 */
	public function _updateProductFeatures( $request ) {

        $product_feature_id = $request->get('featureID');
		$product_features_title  = $request->get('featuretitle');
		$product_features_detail = $request->get('keyfeaturedetail');

        $feature_ids = [];
        if(!empty($product_features_title)) {
            foreach ($product_features_title as $key => $feature) {
                $spfObj = new StoreProductFeature();
                $already = null;
                if(isset($product_feature_id[$key])){
                    $already = StoreProductFeature::where('id',$product_feature_id[$key])->first();

                    if(!empty($already->id)){
                        $already->pr_id = $request->get('product_id');
                        $already->title = $product_features_title[$key];
                        $already->detail = $product_features_detail[$key];
                        $already->key_feature_type = 1;
                        $already->save();

                        $feature_ids[] = $already->id;
                    }
                }

                if(empty($already->id)){

                    $spfObj->pr_id = $request->get('product_id');
                    $spfObj->title = $product_features_title[$key];
                    $spfObj->detail = $product_features_detail[$key];
                    $spfObj->key_feature_type = 1;
                    $spfObj->save();

                    $feature_ids[] = $spfObj->id;
                }
            }
        }

        $product_tech_ids = $request->get('techID');
        $product_tech_title      = $request->get('techtitle');
        $product_tech_detail     = $request->get('techspecs');
        
        if(!empty($product_tech_title)) {
            foreach ($product_tech_title as $key => $feature) {
                $spfObj = new StoreProductFeature();
                $already = null;
                if(isset($product_tech_ids[$key])){
                    $already = StoreProductFeature::where('id',$product_tech_ids[$key])->first();

                    if($already->id){
                        $already->pr_id = $request->get('product_id');
                        $already->title = $product_tech_title[$key];
                        $already->detail = $product_tech_detail[$key];
                        $already->key_feature_type = 2;
                        $already->save();

                        $feature_ids[] = $already->id;
                    }
                }

                if(empty($already->id)){

                    $spfObj->pr_id = $request->get('product_id');
                    $spfObj->title = $product_tech_title[$key];
                    $spfObj->detail = $product_tech_detail[$key];
                    $spfObj->key_feature_type = 2;
                    $spfObj->save();

                    $feature_ids[] = $spfObj->id;
                }
            }
        }
        if(!empty($feature_ids)){
            StoreProductFeature::where('pr_id',$request->get('product_id'))
                                ->whereNotIn('id',$feature_ids)
                                ->update(['is_deleted' => 1]);
        }else{
            StoreProductFeature::where('pr_id',$request->get('product_id'))->update(['is_deleted' => 1]);
        }

		return 1;
	}

	/**
	 * @param $request
	 * @param $product_id
	 *
	 * @return int
	 */
	public function storeReview( $request, $product_id , $returnReviewObject=null) {
		$review = new StoreProductReview();

		if(isset($request->description)){
			$description = $request->description;
		}else{
			$description = $request->review_description;
		}

		$review->description = $description;
		$review->owner_id    = Auth::user()->id;
		$review->product_id  = $product_id;
		$review->rating      = $request->stars_rating;
		if ( $review->save() ) {
			if($returnReviewObject == 1){
				return $review;
			}
			return $review->id;
		}

		return 0;
	}

	/**
	 * @param $id
	 *
	 * @return int
	 */
	public function getReviews( $id ) {
		$reviews = DB::table( 'store_product_reviews' )->where( 'product_id', $id )->get();

		if ( count( $reviews ) > 0 ) {
			return $reviews;
		} else {
			return 0;
		}
	}

	public function getOrdersOfCustomer( $user_id ) {
		return $finishedOrders = DB::table( 'store_orders' )->where( 'customer_id', $user_id)->where( 'status', \Config::get("constants_brandstore.ORDER_STATUS.ORDER_DELIVERED"))->get();
	}

	public function getOrdersOfCustomerIds( $user_id ) {
		return $finishedOrders = DB::table( 'store_orders' )->where( 'customer_id', $user_id)->where( 'status', \Config::get("constants_brandstore.ORDER_STATUS.ORDER_DELIVERED"))->lists('id');
	}

	/**
	 * @param $id
	 *
	 * @return int
	 */
	public function isAbleToReview( $user_id, $product_id ) {
		$finishedOrders = $this->getOrdersOfCustomer($user_id);

		if ( count( $finishedOrders ) > 0 ) {
			foreach($finishedOrders as $finishedOrder){
				$reviewProduct = DB::table( 'store_order_items' )->where( 'product_id', $product_id)->where( 'order_id', $finishedOrder->id)->first();

				if(isset($reviewProduct->id) ){
					return $reviewProduct->id;
				}
			}
		} else {
			return 0;
		}
	}

	/**
	 * @param $id
	 *
	 * @return int
	 */
	public function isReviewed( $owner_id, $product_id ) {
		$productReview = DB::table( 'store_product_reviews' )
				->where( 'owner_id', $owner_id )
				->where( 'product_id', $product_id )
				->first();

		if (isset($productReview->id) ) {
			return $productReview->id;
		} else {
			return 0;
		}
	}

    /**
     * @param $id
     *
     * @return int
     */
    public function getProductAttributes( $product_id ) {
        $productAttributes = DB::table( 'store_product_attributes' )
            ->where( 'product_id', $product_id )
            ->where('is_deleted',0)
            ->get();

        if (count($productAttributes) ) {
            return $productAttributes;
        } else {
            return 0;
        }
    }

	/**
	 * @param $id
	 *
	 * @return int
	 */
	public function key_feature( $id ) {
		$key_features = DB::table( 'store_product_features' )
                        ->where('is_deleted',0)
                        ->where( 'key_feature_type', 1 )
                        ->where( 'pr_id', $id )
                        ->get();

		if ( count( $key_features ) > 0 ) {
			return $key_features;
		} else {
			return 0;
		}
	}

	/**
	 * @param $id
	 *
	 * @return int
	 */
	public function tech_spechs( $id ) {
		$tech_features = DB::table( 'store_product_features' )
                        ->where('is_deleted',0)
                        ->where( 'key_feature_type', 2 )
                        ->where( 'pr_id', $id )
                        ->get();

		if ( count( $tech_features ) > 0 ) {
			return $tech_features;
		} else {
			return 0;
		}
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getCatOwnerId( $id ) {
		$cat = Category::where( 'id', $id )->first();

		return $cat->owner_id;
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getProOwnerId( $id ) {
		$cat = StoreProduct::where( 'id', $id )->first();

		return $cat->owner_id;
	}

	/**
	 * @param $id
	 *
	 * @return int
	 */
	public function getAllProductByBrandId( $id ) {
		if ( Auth::user()->id == $id ) {
			$products = DB::table( 'store_products' )
			              ->where( 'owner_id', $id )
			              ->where( 'deleted_at', '=', NULL )
			              ->orderBy( 'created_at', 'desc' )
			              ->paginate( 50 );

			return $products;
		}

		return 0;
	}


// ==================== End of Mustabeen code ============================


// ==================== Zahid code ============================

    public function isStoreBrand($brand_id) {
        return $brand = User::select([
            'user_type',
            'id',
        ])->where('id', $brand_id)->orWhere('username', $brand_id)->first();
    }

    public function getTotalEarnings() {

        $allOrders = $this->getFinishedOrdersCurrentUser();

        $data['totalSales']     = '';
        $data['thisMonthSales'] = '';
        //To manipulate monthly sale

        $data['now_date'] = Carbon::now()->toDateTimeString();
        $data['to_date']  = Carbon::now()->addMonth(-1)->toDateTimeString();

        foreach ($allOrders as $order) {
            $data['totalSales'] = $data['totalSales'] + $order->total_price;

            $data['created_at'] = $order->created_at->toDateTimeString();
            $data['month_name'] = Carbon::now()->format('F');

            if ($data['created_at'] >= $data['to_date'] AND $data['created_at'] <= $data['now_date']) {
                $data['thisMonthSales'] = $data['thisMonthSales'] + $order->total_price;
            }
        }

        return $data;
    }

    public function getFinishedOrdersCurrentUser($user_id = 0) {
        return StoreOrder::where('seller_id', $this->user_id)->where('status', \Config::get("constants_brandstore.ORDER_STATUS.ORDER_DELIVERED"))->orderBy('id', 'DESC')->get();
    }

    public function getTotalQuantityOfProductsCurrentUser($user_id = 0) {
        return StoreProduct::where('owner_id', $this->user_id)->sum('quantity');
    }

    public function getFinishedOrdersCurrentUserBuyer($user_id = 0) {
        return StoreOrder::where('customer_id', $this->user_id)->where('status', \Config::get("constants_brandstore.ORDER_STATUS.ORDER_DELIVERED"))->orderBy('id', 'DESC')->get();
    }

    public function AddDeliverCourierInfo($formData) {

        $deliverCourier = new DeliveryCourier();

        $deliverCourier->seller_id               = $formData->seller_id;
        $deliverCourier->order_id                = $formData->order_id;
        $deliverCourier->courier_service_name    = $formData->courier_service_name;
        $deliverCourier->courier_service_url     = $formData->courier_service_url;
        $deliverCourier->order_tracking_number   = $formData->order_tracking_number;
        $deliverCourier->delivery_estimated_time = $formData->delivery_estimated_time;

        $originalDate = $formData->date_to_be_delivered;
        $newDate = date("Y-m-d", strtotime($originalDate));

        $deliverCourier->date_to_be_delivered    = $newDate;
        $deliverCourier->delivery_charges_paid   = $formData->delivery_charges_paid;

        $deliverCourier->save();

        return $deliverCourier->id;
    }

    public function getCurrentUserFinishedOrdersIds() {
        return StoreOrder::where('seller_id', $this->user_id)->where('status', \Config::get("constants_brandstore.ORDER_STATUS.ORDER_DELIVERED"))->lists('id');
    }

    public function getCurrentBuyerUserFinishedOrdersIds() {
        return StoreOrder::where('customer_id', $this->user_id)->where('status', \Config::get("constants_brandstore.ORDER_STATUS.ORDER_DELIVERED"))->lists('id');
    }

    public function getCurrentUserFinishedOrderProductIds($orderIds) {
        return StoreOrderItems::whereIn('order_id', $orderIds)->lists('id');
    }

    public function getTotalSalesCurrentUser($user_id) {
        $sale = \Config::get('constants_brandstore.STATEMENT_TYPES.SALE');
        $shipping_fee = \Config::get('constants_brandstore.STATEMENT_TYPES.ORDER_SHIPPING_FEE');
        return  StoreTransaction::where('user_id', $user_id)
                                            ->whereIn('type', [$sale,$shipping_fee])
                                            ->sum('amount');
    }

    public function getCurrentMonthSalesCurrentUser($user_id) {
        $sale = \Config::get('constants_brandstore.STATEMENT_TYPES.SALE');
        $shipping_fee = \Config::get('constants_brandstore.STATEMENT_TYPES.ORDER_SHIPPING_FEE');
        $allSales = StoreTransaction::where('user_id', $user_id)->whereIn('type', [$sale,$shipping_fee])->get();

        $data['thisMonthSales'] = '';
        $data['now_date'] = Carbon::now()->toDateTimeString();
        $data['to_date']  = Carbon::now()->addMonth(-1)->toDateTimeString();

        foreach ($allSales as $sale) {
            $data['created_at'] = $sale->created_at->toDateTimeString();
            $data['month_name'] = Carbon::now()->format('F');

            if ($data['created_at'] >= $data['to_date'] AND $data['created_at'] <= $data['now_date']) {
                $data['thisMonthSales'] = $data['thisMonthSales'] + $sale->amount;
            }
        }

        return $data;
    }

    public function updateProductQuantityByOperation($product_id, $operation, $quantityTobeUpdated) {

        $product = StoreProduct::find($product_id);

        if ($operation == '-') {
            $quantityTobeUpdated = $product->quantity - $quantityTobeUpdated;
        }

        if ($operation == '+') {
            $quantityTobeUpdated = $product->quantity + $quantityTobeUpdated;
        }

        $product->quantity = $quantityTobeUpdated;

        $product->save();
    }

    public function updateProductSoldProductByOperation($product_id, $operation, $quantityTobeUpdated) {

        $product = StoreProduct::find($product_id);

        if ($operation == '-') {
            $quantityTobeUpdated = $product->sold - $quantityTobeUpdated;
        }

        if ($operation == '+') {
            $quantityTobeUpdated = $product->sold + $quantityTobeUpdated;
        }

        $product->sold = $quantityTobeUpdated;

        $product->save();
    }

    public function getCurrentUserProductsReviews($user_id) {
        $orderIds   = $this->getCurrentUserFinishedOrdersIds();
        $productIds = $this->getCurrentUserFinishedOrderProductIds($orderIds);
        $reviews    = DB::table('store_product_reviews')->whereIn('product_id', $productIds)->get();

        if (count($reviews) > 0) {
            return $reviews;
        } else {
            return 0;
        }
    }

    public function getCurrentBuyerUserProductsReviews($user_id) {
        $orderIds   = $this->getCurrentBuyerUserFinishedOrdersIds();
        $productIds = $this->getCurrentUserFinishedOrderProductIds($orderIds);
        $reviews    = DB::table('store_product_reviews')->whereIn('product_id', $productIds)->get();

        if (count($reviews) > 0) {
            return $reviews;
        } else {
            return 0;
        }
    }

    public function updateFeedBack($request) {
        $review = StoreProductReview::find($request->review_id);

        if (isset($review->id)) {

            $review->description = $request->description;
            $review->rating      = $request->stars_rating;
            $review->is_revised  = 1;

            $review->save();

            return $review;
        }

        return 0;
    }

    public function getAllRegions() {
        return $costRegions = StoreShippingRegion::get();
    }

    public function getProductRegionsCost($product_id) {
        return $regionsCost = StoreShippingCost::where('product_id', $product_id)->get();
    }

    public function deleteAllRegionsCostByProductId($product_id) {
        return $regionsCost = StoreShippingCost::where('product_id', $product_id)->delete();
    }

    public function addRegionCost($request) {

        if (isset($request->product_id)) {
            $this->deleteAllRegionsCostByProductId($request->product_id);
            $this->deletShippingCountryByProductID($request->product_id);
            $status = $request->get('status');
            $country = $request->get('country');
            $cost = $request->get('cost');

            foreach ($status as $key => $value){
                if($value == 0){
                    continue;
                }
                $region_id = $this->getRegionIDByName($key);
                if(isset($country[$key])){
                    $countries = $country[$key];
                }else{
                    $countries = $this->getRegionCountries($key);
                }

                foreach ($countries as $index => $country_id){
                    $shippingCountry = new StoreShippingCountry();

                    $shippingCountry->product_id = $request->product_id;
                    $shippingCountry->country_id = $country_id;
                    $shippingCountry->region_id  = $region_id;

                    $shippingCountry->save();
                }
                $shippingCost = new StoreShippingCost();

                $shippingCost->product_id    = $request->product_id;
                $shippingCost->region_id     = $region_id;
                $shippingCost->shipping_cost = $cost[$key];
                $shippingCost->status        = $value;

                $shippingCost->save();
            }
            return 1;
        }

        return 0;

    }
    public function deletShippingCountryByProductID($product_id){
        return StoreShippingCountry::where('product_id',$product_id)->delete();
    }
    public function getRegionCountries($region){

        return Country::where('region','like',$region)->lists('id','id');
    }

    public function sendReviewReviseRequest($review_id) {
        $review = StoreProductReview::find($review_id);

        if (isset($review->id)) {

            $review->is_revise_request = 1;

            $review->save();

            return $review->id;
        }

        return 0;
    }

    public function updateStatement($type,$parent_type,$parent_id,$transaction_type,$currency = NULL,$user_id = NULL,$amount = NULL){
        $sale = \Config::get('constants_brandstore.STATEMENT_TYPES.SALE');
        $shipping_fee = \Config::get('constants_brandstore.STATEMENT_TYPES.ORDER_SHIPPING_FEE');
        if($type == $sale || $type == $shipping_fee){
            $order = StoreOrder::where('id',$parent_id)->first();

            $user_id = $order->seller_id;
            if($type == $sale) {
                $amount = $order->total_price - $order->total_shiping_cost;
                $amount = $amount - $order->total_discount;
            }elseif($type == $shipping_fee){
                $amount = $order->total_shiping_cost;
                if(empty($amount) || $amount == '0.00'){
                    return False;
                }
            }
        }

        $already_exists = StoreTransaction::where('parent_type',$parent_type)
            ->where('type',$type)
            ->where('parent_id',$parent_id)
            ->where('transaction_type',$transaction_type)
            ->where('user_id',$user_id)
            ->count();

        if($already_exists || empty($amount) || $amount == '0.00'){
            return False;
        }

        $stObj = new StoreTransaction();
        $stObj->type = $type;
        $stObj->parent_type = $parent_type;
        $stObj->parent_id = $parent_id;
        $stObj->user_id = $user_id;
        $stObj->amount = str_replace(',','',$amount);
        $stObj->transaction_type = $transaction_type;
        $stObj->currency = $currency;
        $stObj->save();
    }

    public function regionsSelectedCountries($product_id) {

    }

// ==================== End of Zahid code ============================

    public function statement($store_id,$transaction_type) {
        $store    = $this->isStoreBrand($store_id);
        $store_id = $store->id;
        if (Input::has('to')) {
            $to = Carbon::parse(Input::get('to'))->format('Y-m-d H:i:s');
        } else {
            $to = Carbon::now();
        }

        if (Input::has('from')) {
            $from = Carbon::parse(Input::get('from'))->format('Y-m-d H:i:s');
        } else {
            $from = Carbon::now()->subDay(30);
        }

        $data['transactions'] = $this->get_transactions($store_id, $from, $to,$transaction_type);

        $data['earning'] = $this->totalEarning($store_id);
        $data['beginning_balance'] = $this->beginning_balance($store_id,$from);
        $data['from']    = $from;
        $data['to']      = $to;
        $data['transaction_type'] = $transaction_type;

        return $data;
    }

    private function get_transactions($store_id, $from, $to,$transaction_type = '') {

        $query = StoreTransaction::where('user_id', $store_id)
            ->where('created_at', '>', $from)->where('created_at', '<', $to)
            ->orderBy('created_at', 'DESC');
        if(!empty($transaction_type)){
            $query->where('transaction_type','like',$transaction_type);
        }
        return $query->get();
    }

    private function totalEarning($store_id) {

        $storeRepo = new StoreRepository();
        return $storeRepo->getAvailableBalance($store_id);

    }

    private function beginning_balance($store_id, $from) {
        //return StoreOrder::where('seller_id', $store_id)->where('status', \Config::get("constants_brandstore.ORDER_STATUS.ORDER_DELIVERED"))->sum('total_price');
        $earning =StoreTransaction::where('user_id', $store_id)
            ->where('type', \Config::get("constants_brandstore.STATEMENT_TYPES.SALE"))
            ->where('created_at', '<', $from)
            ->sum('amount');
        $withdraw =StoreTransaction::where('user_id', $store_id)
            ->where('type','!=', \Config::get("constants_brandstore.STATEMENT_TYPES.SALE"))
            ->where('created_at', '<', $from)
            ->sum('amount');
        return $earning-$withdraw;
    }

    public function getSameNameSubCategory( $owner_id, $category_id, $subcategory_name ) {
        $sub_categories = Category::where( 'category_parent_id', $category_id )
                            ->where( 'name', '=', $subcategory_name)
                            ->where( 'owner_id', $owner_id)
                            ->get();

        if ( count( $sub_categories ) > 0 ) {
            return 1;
        } else {
            return 0;
        }
    }
    public function getAllCountries(){
        return Country::select(['id','name','iso'])->get();
    }
    public function getCountriesByRegion($region){
        return Country::where('region','like',$region)->select('id','name','iso')->get();
    }
}
