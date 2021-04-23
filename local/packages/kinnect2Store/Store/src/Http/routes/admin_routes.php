<?php

Route::group(['middleware' => ['data', 'store_auth']], function () {
    
    Route::get('store/{storeBrandId}/admin/getCountriesByRegion', 'kinnect2Store\Store\Http\admin\StoreAdminController@getCountriesByRegion');
    
    // ==================== Ubaid code ============================
    Route::get('store/{storeBrandId}/admin/add-product/{message?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@getAddProduct');
    Route::get('store/{storeBrandId}/admin/categories/{messages?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@getCategories');
    Route::post('store/{storeBrandId}/admin/categories/{messages?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@storeCategories');
    Route::get('store/{storeBrandId}/admin/delete/category/{category_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@deleteCategory');
    Route::post('store/{storeBrandId}/admin/add-product/', 'kinnect2Store\Store\Http\admin\StoreAdminController@storeProduct');
    Route::post('store/{storeBrandId}/admin/update-product/{product_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@storeProductUpdate');
    Route::post('store/{storeBrandId}/admin/subCategory/', 'kinnect2Store\Store\Http\admin\StoreAdminController@getSubCategory');

    Route::post('store/{storeBrandId}/admin/filteredCategory/', 'kinnect2Store\Store\Http\admin\StoreAdminController@getSubCategoriesAjax');

    Route::post('store/{storeBrandId}/admin/product_image_ajax/', 'kinnect2Store\Store\Http\admin\StoreAdminController@product_image_ajax');
    Route::post('store/{storeBrandId}/admin/delete_product_image/', 'kinnect2Store\Store\Http\admin\StoreAdminController@delete_product_image');
    Route::get('store/{storeBrandId}/admin/product/{product_id}/{message?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@getProductDetail');
    Route::Post('store/{storeBrandId}/admin/product/delete', 'kinnect2Store\Store\Http\admin\StoreAdminController@deleteProductAjax');
    Route::post('store/{storeBrandId}/admin/delete_edit_product_image', 'kinnect2Store\Store\Http\admin\StoreAdminController@delete_edit_product_image');

    Route::get('store/{storeBrandId}/admin/{product_id}/product_analytics', 'kinnect2Store\Store\Http\admin\StoreAdminController@getProductAnalytics');


    Route::get('store/{storeBrandId}/admin/product_analytics', 'kinnect2Store\Store\Http\admin\StoreAdminController@productListingAnalytics');

    Route::get('store/{storeBrandId}/admin/page_analytics', 'kinnect2Store\Store\Http\admin\StoreAdminController@getPageAnalytics');


    // ==================== End of Ubaid code ============================

    Route::post('store/{storeBrandId}/admin/edit/category/{category_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@editCategory');

    Route::get('store/{storeBrandId}/admin/Subcategories/{messages?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@getSubCategories');

    Route::get('store/{storeBrandId}/admin/send-request-revise-feedback/{review_id}/{messages?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@sendRequestToRvise');

    Route::post('store/{storeBrandId}/admin/Subcategories/{messages?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@storeSubCategories');

    Route::post('store/{storeBrandId}/admin/edit/Subcategory/{category_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@editSubCategory');

    Route::get('store/{storeBrandId}/admin/delete/Subcategory/{category_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@deleteSubCategory');

    Route::get('store/{storeBrandId}/admin/manage-product/{messages?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@getProducts');
    Route::post('store/{storeBrandId}/admin/manage-product/{messages?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@updateProducts');
    Route::get('store/{storeBrandId}/admin/delete/product/{product_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@deleteProduct');
    Route::post('store/{storeBrandId}/admin/filteredProducts/', 'kinnect2Store\Store\Http\admin\StoreAdminController@getProductsForSelection');
    Route::get('store/{storeBrandId}/admin/edit/product/{product_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@editProduct');
    Route::patch('store/{storeBrandId}/admin/update/product/{product_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@updateProduct');
    Route::get('store/{storeBrandId}/admin/orders/{messages?}', 'kinnect2Store\Store\Http\admin\StoreAdminOrderController@getOrders');
    Route::get('store/{storeBrandId}/admin/store-earnings/{messages?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@getStoreEarnings');
    Route::post('store/{storeBrandId}/admin/update/order-status/{messages?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@updateOrderStatusAjax');
    Route::get('store/{storeBrandId}/admin/manage_reviews/{messages?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@manageReviews');
    Route::post('store/{storeBrandId}/admin/add-courier-service-info/{order_id}/{order_status}/{messages?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@addCourierServiceInfo');
    Route::post('store/{storeBrandId}/admin/serach-my-orders/{message?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@searchMyOrders');
    Route::post('store/{storeBrandId}/admin/serach-my-reviews/{message?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@searchMyReviews');

    Route::post('store/{storeBrandId}/admin/checkIfAlreadySubCatAjax/{message?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@checkIfAlreadySubCatAjax');

    Route::group(['prefix' => 'store/{storeBrandId}/admin/statement'], function () {
        Route::any('/', 'kinnect2Store\Store\Http\admin\StoreAdminController@statement');
    });


    Route::group(['prefix' => 'store/{storeBrandId}/admin/product-analytics'], function () {

        Route::post('number-views/{product_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@number_views');
        Route::post('number-sales/{product_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@number_sales');
        Route::post('age-view/{product_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@age_view');
        Route::post('gender-view/{product_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@gender_view');
        Route::post('country-view/{product_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@country_view');
        Route::post('peak-view/{product_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@peak_view');

        // start of page stats routes
        Route::post('number-views/page/{owner_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@number_views_page_stat');
        Route::post('number-sales/page/{owner_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@number_sales_page_stat');
        Route::post('age-view/page/{owner_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@age_view_page_stat');
        Route::post('gender-view/page/{owner_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@gender_view_page_stat');
        Route::post('country-view/page/{owner_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@country_view_page_stat');
        Route::post('peak-view/page/{owner_id}', 'kinnect2Store\Store\Http\admin\StoreAdminController@peak_view_page_stat');
        // end of page stats routes

    });
});
Route::post('store/{storeBrandId}/admin/order/delete/{message?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@softDeleteOrder');

Route::post('store/{storeBrandId}/cancelOrder/{order_id}/{message?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@cancelOrder');

Route::get('store/{storeBrandId}/admin/order-invoice/{order_id}/{message?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@getOrderInvoice');

Route::get('store/{storeBrandId}/admin/add-product-shipping-cost/{product_id}/{message?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@getAddProductShippingCost');

Route::post('store/{storeBrandId}/admin/add-product-shipping-cost/{message?}', 'kinnect2Store\Store\Http\admin\StoreAdminController@addProductShippingCost');

?>
