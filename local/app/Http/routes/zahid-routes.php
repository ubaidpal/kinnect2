<?php
Route::get('/activate/resendEmail', 'Auth\AuthController@resendEmail');

//Migrations routes
Route::get('users-files', 'migrationsController@getFilesOfUser');
Route::get('users-albums', 'migrationsController@Albums');
Route::get('album-photos', 'migrationsController@AlbumsPhotos');

Route::get('groups-membership', 'migrationsController@groupMembership');
Route::get('users-membership', 'migrationsController@userMembership');

Route::get('groups-export', 'migrationsController@groupsExport');
Route::get('users-export', 'migrationsController@usersExport');
Route::get('users-brands-export', 'migrationsController@usersBrandsExport');

Route::get('setting_allow_for_old_users', 'migrationsController@setting_allow_for_old_users');

// Migrating messages, step by step
Route::get('messages-conversations', 'migrationsController@messagesConversations');
Route::get('messages-messages', 'migrationsController@messagesMessages');
Route::get('messages-recipients', 'migrationsController@messagesRecipients');
Route::get('messages-atLastAmmendAccordingNewSystem', 'migrationsController@atLastAmmendAccordingNewSystem');
// End of Migrating messages

//End of migrations routes
Route::post( 'events/create_event_temp_image', 'EventController@createEventTempImage' );

Route::post( 'groups/create_group_temp_image', 'GroupController@createGroupTempImage' );
Route::post( 'groups/edit_group_temp_image', 'GroupController@editGroupTempImage' );

Route::group(['middleware' => ['auth','data']], function () {
    Route::get('after_activation_follow_brands', 'UsersController@afterActivationFollowBrands');
    Route::get('/getBrandsToFollow/{search_term}', 'UsersController@getBrandsToFollow');

    Route::get('/event_guest_search/{event_id}/{search_value}', 'EventController@searchMembers');
    Route::post('/event/approve/request', 'EventController@approveRequest');
    Route::post('/event/cancel/request', 'EventController@cancelRequest');
    Route::get('/event/remove-member/{event_id}/{user_id}', 'EventController@removeMember');

    Route::post('/ads/report/ajax', 'AdsController@ajaxReportAd');
    Route::post('ads/ajax/upload_image', 'AdsController@ajaxImageUpload');
    Route::get('/ads/create-ad/{package_id}/{message?}', 'AdsController@createAd');
    Route::post('/ads/create-ad/{package_id}', 'AdsController@createAdStore');

    Route::get('/ads/targetting/{ad_id}/{message?}', 'AdsController@createAdTargetting');
    Route::post('/ads/targetting/{ad_id}/{message?}', 'AdsController@createAdTargettingPost');

    Route::get('/ads/create/package', 'AdsController@createAdPackage');
    Route::post('/ads/create/package', 'AdsController@store');
    Route::get('/ads/ad-board', 'AdsController@index');
    Route::get('ads/my-campaigns/{message?}', 'AdsController@myCampaigns');
    Route::post('ads/my-campaigns/{message?}', 'AdsController@myCampaignsStatistics');

    Route::post('ads/ad_profile_temp_image', 'AdsController@ad_profile_temp_image');

    Route::get('/ads/manage/campaign/{campaign_id}/{message?}', 'AdsController@manageCampaign');
    Route::post('/ads/manage/campaign/{campaign_id}/{message?}', 'AdsController@manageCampaignStatistics');

    Route::get('ads/manage/ad/{ad_id}/{message?}', 'AdsController@manageAd');
    Route::post('ads/manage/ad/{ad_id}/{message?}', 'AdsController@manageAdStatistics');

    Route::get('/ads/pause/ad/{message?}', 'AdsController@pauseAd');
    Route::get('/ads/activate/ad/{message?}', 'AdsController@activateAd');

    Route::get('/ads/edit/campaign/{campaign_id}/{message?}', 'AdsController@editCampaign');
    Route::post('/ads/edit/campaign/{campaign_id}', 'AdsController@updateCampaign');
    Route::get('/ads/delete/campaign/{campaign_id}', 'AdsController@deleteCampaign');

    Route::get('/ads/delete/ad/{ad_id}', 'AdsController@deleteAd');
    Route::get('/ads/edit/ad/{ad_id}/{message?}', 'AdsController@editAd');
    Route::patch('/ads/edit/ad/{ad_id}', 'AdsController@updateAd');

    Route::post('/ads/incrementAdView', 'AdsController@incrementAdView');
    Route::get('/ads/incrementAdClick/{ad_id}', 'AdsController@incrementAdClick');

    // Add this route for checkout or submit form to pass the item into paypal
    Route::get('/paypal/ad{ad_id?}/{message?}', 'PaymentController@postPayment');

    Route::get('payment', array(
        'as' => 'payment',
        'uses' => 'PaymentController@postPayment',
    ));

// this is after make the payment, PayPal redirect back to your site
    Route::get('payment/status/{ad_id?}', array(
        'as' => 'payment.status',
        'uses' => 'PaymentController@getPaymentStatus',
    ));

    Route::post( '/advanced_search/{search_term?}', 'HomeController@advancedSearchPost' );

    Route::post('/campaigns/delete/campaign/ajax/{message?}', 'AdsController@deleteCampaignsAjax');
    Route::post('/ads/delete/ad/ajax', 'AdsController@deleteAdsAjax');

    Route::get('ads/reports/generator', 'AdsController@getReportGenerator');
    Route::post('ads/reports/generator', 'AdsController@reportGenerator');

    Route::get('/event/members/{event_id}', 'EventController@allMembers');
    //Help pages
    Route::get('/ads/help/overview', 'AdsController@helpOverview');
    Route::get('/ads/help/get-started', 'AdsController@helpGetStarted');
    Route::get('/ads/help/improve-your-ads', 'AdsController@helpImproveYourAds');
    Route::get('/ads/help/contact-sales', 'AdsController@helpContactSales');
    Route::get('/ads/help/general-faq', 'AdsController@helpGeneralFaq');
    Route::get('/ads/help/targeting-faq', 'AdsController@helpTargetingFaq');
    Route::get('/ads/help/ad-design-faq', 'AdsController@helpAdDesignFaq');

    Route::post('user/change/profile/', 'UsersController@changeProfilePicture');
    Route::post('user/change/cover/', 'UsersController@changeCoverPicture');


});

?>
