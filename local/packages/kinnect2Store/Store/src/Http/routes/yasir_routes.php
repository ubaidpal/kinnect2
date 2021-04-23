<?php
Route::group(['middleware' => ['auth', 'data']], function () {
    Route::group(['prefix' => 'store'], function () {
        Route::group(['prefix' => 'dispute'], function () {
            Route::post('complain', 'kinnect2Store\Store\Http\DisputeController@orderDispute');
            Route::get('detail/{id}', 'kinnect2Store\Store\Http\DisputeController@get_dispute');
            Route::get('modify/{id}', 'kinnect2Store\Store\Http\DisputeController@edit_dispute');
            Route::get('cancel/{id}', 'kinnect2Store\Store\Http\DisputeController@cancel_dispute');
            Route::get('accept/{id}', 'kinnect2Store\Store\Http\DisputeController@accept_dispute');
            Route::post('message', 'kinnect2Store\Store\Http\DisputeController@message');
        });
        Route::post('claim/store', 'kinnect2Store\Store\Http\DisputeController@claim_store');

    });
    Route::post('dispute-image', 'kinnect2Store\Store\Http\DisputeController@product_image_ajax');
    Route::post('delete-dispute-image', 'kinnect2Store\Store\Http\DisputeController@delete_product_image');
    Route::get('claimFee/{id}','kinnect2Store\Store\Http\DisputeController@claimFee');
    Route::post('store/payClaimFee/{id}','kinnect2Store\Store\Http\DisputeController@payClaimFee');
});
