<?php

// ==================== Ubaid code ============================
if(env("STORE_ENABLED", TRUE)){

    Route::get('store/managerPanel/orders', 'kinnect2Store\Store\Http\StoreOrderController@getOrderMangerPanel');


    Route::post('store/order/dispute/complain', 'kinnect2Store\Store\Http\StoreOrderController@orderDispute');
    Route::get('store/my-orders/', 'kinnect2Store\Store\Http\StoreOrderController@getMyOrders');
    Route::post('store/cart/add-product', 'kinnect2Store\Store\Http\StoreController@addCartProduct');
    Route::get('store/pay/{sellerBrandId}/{message?}', 'kinnect2Store\Store\Http\PaymentController@pay');
    Route::get('store/reviewOrder/{sellerBrandId}/{message?}', 'kinnect2Store\Store\Http\StoreController@reviewOrder');
    Route::post('store/makePayment/{sellerBrandId}/{message?}', 'kinnect2Store\Store\Http\PaymentController@makePayment');



    Route::group(['middleware' => ['auth', 'data']], function () {
        Route::get('store/order/dispute/{id}', 'kinnect2Store\Store\Http\StoreOrderController@getOrderDispute');
        Route::get('store/managerPanel/orders', 'kinnect2Store\Store\Http\StoreOrderController@getOrderMangerPanel');

        Route::get('store/order/dispute', 'kinnect2Store\Store\Http\StoreOrderController@getOrderDispute');
        Route::get('store/my-orders/', 'kinnect2Store\Store\Http\StoreOrderController@getMyOrders');
        Route::get('store/manage-feedbacks', 'kinnect2Store\Store\Http\StoreController@manageFeedbacks');
        Route::get('store/shipping-address/', 'kinnect2Store\Store\Http\StoreController@getShippingAddress');
        Route::get('store/cart/add-product/{product_id}', 'kinnect2Store\Store\Http\StoreController@addCartProduct');
        Route::get('store/{storeBrandId}/shipping/address/{sellerBrandId}', 'kinnect2Store\Store\Http\StoreController@getShippingInfo');
        Route::get('store/{storeBrandId}/shipping/address/{sellerBrandId}', 'kinnect2Store\Store\Http\StoreController@getShippingInfo');
        Route::post('store/{storeBrandId}/add/shipping/address/{sellerBrandId}', 'kinnect2Store\Store\Http\StoreController@addShippingInfo');
        Route::get('store/bankAccount', 'kinnect2Store\Store\Http\StoreManagementController@getBankAccount');
        Route::post('store/addBankAccount', 'kinnect2Store\Store\Http\StoreManagementController@addBankAccount');
        Route::get('store/withdrawals', 'kinnect2Store\Store\Http\StoreManagementController@requestWithdrawal');
        Route::post('store/sendWithdrawalRequest', 'kinnect2Store\Store\Http\StoreManagementController@sendWithdrawalRequest');
        Route::get('store/cancelWithdrawalRequest/{id}', 'kinnect2Store\Store\Http\StoreManagementController@cancelWithdrawalRequest');
    // ==================== End of Ubaid code ============================

    // ==================== Zahid code ===================================

        Route::post('store/feedback/reminder/ajax/{product_id}/{order_id}/{message?}', 'kinnect2Store\Store\Http\StoreController@feedbackReminder');
        Route::post('stores/review/{product_id}/', 'kinnect2Store\Store\Http\admin\StoreAdminController@ProductReview');
        Route::post('store/review/ajax/{product_id}/', 'kinnect2Store\Store\Http\StoreController@ProductReviewAjax');
        Route::get('store/cart/delete-product/{product_id}', 'kinnect2Store\Store\Http\StoreController@deleteCartProduct');
        Route::post('store/cart/quantityUpdate', 'kinnect2Store\Store\Http\StoreController@UpdateQuantityCartProduct');
        Route::patch('store/edit/ProductReview/{review_id}', 'kinnect2Store\Store\Http\StoreController@editProductReview');

        Route::get('store/order/completed/{order_id?}', 'kinnect2Store\Store\Http\StoreController@getOrderCompleted');
        Route::get('store/cart/{message?}', 'kinnect2Store\Store\Http\StoreController@getCart');
        Route::post('cart/product-quantity-check/{message?}', 'kinnect2Store\Store\Http\StoreController@cartProductQuantityCheck');
        Route::get('store/{storeBrandId}/{message?}', 'kinnect2Store\Store\Http\StoreController@index');
        Route::get('store/{storeBrandId}/products/{category_name}/{sub_category_id}', 'kinnect2Store\Store\Http\StoreController@subCategoryProducts');
        Route::get('store/{storeBrandId}/product/{product_id}/{message?}', 'kinnect2Store\Store\Http\StoreController@getProductDetail');
        Route::post('store/order/update/order-status/{message?}', 'kinnect2Store\Store\Http\StoreController@updateOrderStatusAjax');
        Route::post('store/order/delete/{message?}', 'kinnect2Store\Store\Http\StoreController@softDeleteOrder');
        Route::post('store/{storeBrandId}/reviseFeedback/{order_id}/{message?}', 'kinnect2Store\Store\Http\StoreController@reviseFeedback');
        Route::post('store/cancelOrder/{order_id}/{message?}', 'kinnect2Store\Store\Http\StoreController@cancelOrder');
        Route::get('order-invoice/{order_id}/{message?}', 'kinnect2Store\Store\Http\StoreController@getOrderInvoice');
        Route::post('store/checkProductShippingCountry/{message?}', 'kinnect2Store\Store\Http\StoreController@checkProductShippingCountry');
        Route::post('store/checkProductShippingCountryByISO/{message?}', 'kinnect2Store\Store\Http\StoreController@checkProductShippingCountryByISO');
        Route::post('store/getEditAddressFormInfo/{message?}', 'kinnect2Store\Store\Http\StoreController@getEditAddressFormInfo');
        Route::post('store/sofDeleteAddressInfo/{message?}', 'kinnect2Store\Store\Http\StoreController@sofDeleteAddressInfo');
        Route::post('store/serach-my-orders/{message?}', 'kinnect2Store\Store\Http\StoreController@searchMyOrders');
        Route::post('store/reviews/serach-my-reviews/{message?}', 'kinnect2Store\Store\Http\StoreController@searchMyReviews');

        Route::get('product/{product_id?}', 'kinnect2Store\Store\Http\StoreController@getProductById');

    //Admin Routes

        include(__DIR__ . DIRECTORY_SEPARATOR . 'admin_routes.php');
    //Paypal Routes
        include(__DIR__ . DIRECTORY_SEPARATOR . 'paypal_routes.php');

        include(__DIR__ . DIRECTORY_SEPARATOR . 'yasir_routes.php');

    });

// ==================== End of Zahid code ============================


}
