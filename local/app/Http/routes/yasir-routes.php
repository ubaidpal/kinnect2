<?php
Route::group(['middleware' => ['auth', 'data']], function () {
    Route::group(['prefix' => 'notification'], function () {
        Route::post('update', 'NotificationController@update_notification');
        Route::post('mark-read', 'NotificationController@mark_read');
    });

    Route::group(['prefix' => 'friends'], function () {
        Route::get('sent-request', 'FriendshipController@sent_request');
    });


    Route::get('goto/{id}', 'NotificationController@mark_clicked');
    Route::get('mark-all-read', 'NotificationController@mark_all_clicked');

    Route::get('all-activity', 'HomeController@all_activity');
    Route::post('activity/view-more', 'HomeController@load_more_activity');

    Route::post('album/add-photo', 'AlbumsController@store_photo');
    Route::get('albums/photos/{id}', 'AlbumsController@add_photo');
    Route::post('albums/save-description', 'AlbumsController@save_description');
    Route::get('albums/photo/delete/{id}', 'AlbumsController@delete_photo');
    Route::get('{username}/albums', 'AlbumsController@index');
    Route::post('group/profile-content', 'GroupController@profile_content');
    Route::post('event/add-photo', 'EventController@add_photo');

    Route::post('profile/brand-update', 'BrandController@update');

    Route::group(['prefix' => 'messages'], function () {
        Route::get('/', 'MessageController@index');

        Route::post('/store', 'MessageController@store');
        Route::post('/create-group', 'MessageController@create_group');
        Route::post('/add-member-to-group', 'MessageController@add_member_to_group');
        Route::post('/new-message', 'MessageController@get_new_message');
        Route::get('new-thread', 'MessageController@get_new_message');
        Route::post('/rename-conversation', 'MessageController@update');
        Route::get('leave-group/{id}', 'MessageController@leave_group');
        Route::post('get-thread', 'MessageController@get_thread');
        Route::post('members-detail', 'MessageController@get_user_detail');
        Route::post('get-group-name', 'MessageController@get_conv_name');
        Route::get('leave-group-api/{id}/{user}', 'MessageController@leave_group');
        Route::post('upload-attachment', 'MessageController@upload_attachment');
        Route::post('friends-detail', 'MessageController@get_friends_detail');
        Route::post('getUserByID', 'MessageController@getUserByID');
        Route::post('save-chat-message', 'MessageController@store');
        Route::post('/{userid}/{id}', 'MessageController@show');
        Route::get('/{userid}/{id}', 'MessageController@get_messages');
    });

    Route::group(['prefix' => 'invitation'], function () {
        Route::post('invite-friends', 'InvitationController@store');
    });



    //Route::get('truncate', 'WelcomeController@index');

    // URLs For pagination
    Route::get('all-recommended', 'FriendshipController@get_all_recommended');
    Route::get('all-recommended-brand', 'BrandController@get_all_recommended');
    Route::get('all-my-brand', 'BrandController@get_all_my_brand');
    Route::get('kinnectors-paginate', 'UsersController@get_all_kinnectors');
    Route::get('brand-followers', 'UsersController@brand_followers');
    Route::get('followers-paginate', 'UsersController@followers_paginate');
    Route::match(['GET','POST'],'search-friends', 'UsersController@search_friends');

    //Mobile routes
    //Search Routes for Mobile

   // Route::post( 'advanced_search', 'HomeController@advancedSearch' );
});
Route::post('save-chat-message', 'MessageController@store');
Route::get('update-time', 'WelcomeController@update_time_zone');
//Route::get('truncate', 'WelcomeController@index');
//Route::get('change-username', 'migrationsController@changeUsername');
//Route::get('slugify-username', 'migrationsController@slugify_username');
Route::get('koins', 'FavouritesController@koins');
get('sitemap.xml', 'AlbumsController@siteMap');
?>
