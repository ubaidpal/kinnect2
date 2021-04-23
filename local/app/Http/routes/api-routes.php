<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05-11-15
 * Time: 11:36 AM
 */
/*===============================================================================
 *
 *                  ***********      API Routes       **********
 *
 * ==============================================================================*/

include('html-routes.php');
include('zahid-routes.php');
include('yasir-routes.php');
include('bakar-routes.php');
include('mustabeen-routes.php');
include('ubaid-routes.php');
include('mohsin-routes.php');
include('admin-routes.php');

Route::post('oauth/access_token', function () {
    return Response::json(Authorizer::issueAccessToken());
});

Route::group(array('prefix' => Config::get('app.api_prefix'), 'middleware' => ['oauth', 'data']), function () {

    Route::group(array('prefix' => 'friends'), function () {
        Route::post('request', 'FriendshipController@index');
        Route::post('add-friend', 'FriendshipController@add_friend');
        Route::post('sent-request', 'FriendshipController@sent_request');
        Route::post('confirm', 'FriendshipController@confirm');
        Route::get('delete/{user_id}', 'FriendshipController@destroy');
        Route::post('unfriend', 'FriendshipController@destroy');
        Route::post('unfollow', 'FriendshipController@unfollow');
        Route::post('follow', 'FriendshipController@follow');
        Route::post('recommended', 'FriendshipController@get_all_recommended');
        Route::post('recommended-limit', 'FriendshipController@get_all_recommended_limit');
        Route::resource('/', 'FriendshipController');
    });

    Route::group(array('prefix' => 'brands'), function () {
        Route::post('recommended', 'BrandController@brands');
        Route::post('my-brands', 'BrandController@myBrands');
        Route::post('follow', 'BrandController@follow');
        Route::post('unfollow', 'BrandController@unFollow');
        Route::post('kinnectors', 'BrandController@brandsKinnectors');
        Route::post('user-brands', 'BrandController@user_brands');
    });

    Route::group(array('prefix' => 'profile'), function () {
        Route::post('update-info', 'UsersController@update');
        Route::get('edit', 'UsersController@edit');
        Route::post('/', 'UsersController@index');
        Route::post('kinnectors', 'UsersController@kinnectors');
        Route::post('user', 'UsersController@profile');

        Route::post('profile-view', 'UsersController@profile_content');
        //Route::resource('/', 'UsersController');
        //Route::resource('/', 'UsersController');
    });

    //polls Route
    Route::group(['prefix' => 'polls'], function () {
        Route::post('create', 'PollsController@store_api');
        Route::post('/', 'PollsController@index');
        Route::post('edit', 'PollsController@edit');
        Route::post('update', 'PollsController@update');
        Route::post('get-poll', 'PollsController@show');
        Route::post('delete-poll', 'PollsController@destroy');
        Route::post('voting', 'PollsController@updatesVotes');
        Route::post('close', 'PollsController@closed');
        Route::post('open', 'PollsController@closed');
        Route::post('manage', 'PollsController@manage');
        Route::post('open-poll', 'PollsController@closed');
        Route::post('recommended-polls', 'PollsController@recommended_polls');
        Route::post('post-detail', 'HomeController@poll_post');

        //Route::resource('/', 'PollsController');
    });

    //Battle Route
    Route::group(['prefix' => 'battle'], function () {
        Route::post('/', 'BattlesController@index');
        Route::post('create', 'BattlesController@store');
        Route::post('get-create', 'BattlesController@create');
        Route::post('edit', 'BattlesController@edit');
        Route::post('update', 'BattlesController@update');
        Route::post('get', 'BattlesController@show');
        Route::post('delete', 'BattlesController@destroy');
        Route::post('voting', 'BattlesController@updatesVotes');
        Route::post('close', 'BattlesController@closed');
        Route::post('open', 'BattlesController@closed');
        Route::post('manage', 'BattlesController@manage');
        Route::post('suggestion', 'BattlesController@brandBattleNameSuggestion');
        Route::post('recommended_battles', 'BattlesController@getRecommendBattles');
        Route::post('post-detail', 'HomeController@battle_post');

    });

    Route::group(['prefix' => 'notification'], function () {
        Route::post('count', 'NotificationController@update_notification');
        Route::post('get', 'NotificationController@mark_read');
        Route::post('mark-all-read', 'NotificationController@mark_all_read');
        Route::post('detail', 'NotificationController@notification_detail');
    });
    Route::post('activity-log', 'UsersController@api_activity_log');

    Route::group(['prefix' => 'group'], function () {
        Route::post('/', 'GroupController@index');
        Route::post('get_group', 'GroupController@getGroup');
        Route::post('create', 'GroupController@getCreate');
        Route::post('store', 'GroupController@create');
        Route::post('follow', 'GroupController@follow');
        Route::post('unfollow', 'GroupController@unFollow');
        Route::post('edit', 'GroupController@edit');
        Route::post('update', 'GroupController@update');
        Route::post('delete', 'GroupController@destroy');
        Route::post('group_events', 'GroupController@getGroupEvents');
        Route::post('all-groups', 'GroupController@get_my_groups');
        Route::post('my-groups', 'GroupController@my_groups');
        Route::post('group_temp_image', 'GroupController@createGroupTempImage');
        Route::post('edit_group_temp_image', 'GroupController@editGroupTempImage');
        Route::post('group-members', 'GroupController@group_members');
        Route::post('update-photo', 'GroupController@update_photo');
        Route::post('upload-update-photo', 'GroupController@upload_update_photo');
        Route::post('send-invitation','GroupController@inviteGroup');
    });

    Route::group(['prefix' => 'event'], function () {
        Route::post('get-event', 'EventController@index');
        Route::post('edit', 'EventController@edit');
        Route::post('get-create', 'EventController@getCreateEvent');
        Route::post('create', 'EventController@create');
        Route::post('update', 'EventController@update');
        Route::post('reservation', 'EventController@rsvpAjax');
        Route::post('delete', 'EventController@destroy');
        Route::post('request-invite', 'EventController@attend');
        Route::post('unfollow', 'EventController@unFollow');
        Route::post('approve-request', 'EventController@approveRequest');
        Route::post('cancel-request', 'EventController@cancelRequest');
        Route::post('event_temp_image', 'EventController@createEventTempImage');
        Route::post('upload-update-photo', 'EventController@upload_update_photo');
        Route::post('update-photo', 'EventController@update_photo');
        Route::post('invitations', 'EventController@inviteEvent');

    });

    Route::group(['prefix' => 'time-line'], function () {
        Route::post('user-posts', 'HomeController@getUserPosts');
        Route::post('get-comments', 'HomeController@getComments');
        Route::post('get-comment-thread', 'HomeController@getCommentsThreaded');
        Route::post('post-status', 'HomeController@postStatus');// Pending, Do after discussion
        Route::post('edit-status', 'HomeController@editStatus');
        Route::post('delete-status', 'HomeController@deleteStatus');
        Route::post('post-comment', 'HomeController@postComment');
        Route::post('post-comment-thread', 'HomeController@postCommentThreaded');
        Route::post('like-status', 'HomeController@likeStatus');
        Route::post('unlike-status', 'HomeController@unlikeStatus');
        Route::post('like-comment', 'HomeController@likeActivityComment');
        Route::post('make-activity-favorite', 'HomeController@makeActivityFavourite');
        Route::post('remove-activity-favorite', 'HomeController@removeActivityFavourite');
        Route::post('share-activity', 'HomeController@shareActivity');
        Route::post('like-photo', 'HomeController@likePhoto');
        Route::post('unlike-photo', 'HomeController@unLikePhoto');
        Route::post('comment-photo', 'HomeController@commentPhoto');
        Route::post('dislike-status', 'HomeController@dislikeStatus');
        Route::post('undo-dislike-status', 'HomeController@undoDislike');

        Route::post('unlike-activity-comment', 'HomeController@unlikeActivityComment');
        Route::post('pull-posts', 'HomeController@pull');
        Route::post('group-posts', 'HomeController@getGroupPosts');
        Route::post('upload-photo', 'HomeController@uploadImage');
        Route::post('delete-comment', 'HomeController@deleteActivityComment');
        Route::post('get-post', 'HomeController@getPost');
        Route::post('flag-activity','HomeController@flagActivity');
    });

    Route::group(['prefix' => 'album'], function () {
        Route::post('all', 'AlbumsController@index');
        Route::post('get-create', 'AlbumsController@create');
        Route::post('post-create', 'AlbumsController@store');
        Route::post('get-edit', 'AlbumsController@edit');
        Route::post('update', 'AlbumsController@update');
        Route::post('album-photos', 'AlbumsController@add_photo');
        Route::post('delete-photo', 'AlbumsController@delete_photo');
        Route::post('delete-album', 'AlbumsController@destroy');
        Route::post('upload-photo', 'AlbumsController@store_photo');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::post('all', 'UsersController@privacySetting');
        Route::post('notification', 'UsersController@notificationSetting');
        Route::post('change-password', 'UsersController@userPasswordChange');
        Route::post('delete-account', 'UsersController@postDeleteAccount');
        Route::post('get-general-settings', 'UsersController@generalSetting');
        Route::post('general-settings', 'UsersController@generalSettingSave');
        Route::post('save-settings', 'UsersController@postSetting');
        Route::post('save-noti-settings', 'UsersController@postSetting');

    });

    Route::post('left-sidebar', 'ApiController@left_sidebar');
    Route::post('static', 'ApiController@static_page');
    Route::post('favourite', 'FavouritesController@all_favourite');
    Route::post('version-upgrade', 'ApiController@versionUpgrade');

    // Messaging API
    Route::group(['prefix' => 'messages'], function () {
        Route::post('create-group', 'MessageController@create_group');
        Route::post('add-member-to-group', 'MessageController@add_member_to_group');
        Route::post('leave-group-api', 'MessageController@leave_group');
        Route::post('get-thread', 'MessageController@get_thread');
        Route::post('members-detail', 'MessageController@get_user_detail');
        Route::post('friends-detail', 'MessageController@get_friends_detail');
        Route::post('get-group-name', 'MessageController@get_conv_name');
        Route::post('upload-attachment', 'MessageController@upload_attachment');
        Route::post('/', 'MessageController@index');
        Route::post('store', 'MessageController@store');
        Route::post('all-participants', 'MessageController@all_participants');
        Route::post('/rename-conversation', 'MessageController@update');
        Route::post('/change-display-picture','MessageController@changeChatDP');
    });

    Route::post('leader-boards', 'UsersController@leaderBoards');
    Route::post('feedback', 'HomeController@feedback');
    Route::post('change-profile-picture', 'UsersController@changeProfilePicture');
    Route::post('change-cover-photo', 'UsersController@changeCover');
    Route::post('search', 'HomeController@search');
    Route::post('url-metadata', 'ApiController@url_metadata');
    Route::post('profile-image', 'ApiController@profile_image');

});

Route::post(Config::get('app.api_prefix') . '/login', 'ApiController@login');
Route::post(Config::get('app.api_prefix') . '/get-sign-up', 'ApiController@get_signup');
Route::post(Config::get('app.api_prefix') . '/register-user', 'ApiController@store');
Route::post(Config::get('app.api_prefix') . '/forget-password', 'ApiController@forget_password');
Route::get(Config::get('app.api_prefix') . '/static', 'ApiController@static_page');
Route::post(Config::get('app.api_prefix') . '/register-device-token', 'ApiController@registerPushEndpoint');

Route::group(array('prefix' => Config::get('app.api_prefix')), function () {

});

Route::group(['prefix' => 'web', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'group'], function () {
        Route::post('create', 'GroupController@getCreate');
        Route::post('store', 'GroupController@create');
    });
});

