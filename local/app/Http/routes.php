<?php

Route::get( '/search/{search_term}', 'HomeController@search' );
Route::get( '/advanced_search/{search_term?}', 'HomeController@advancedSearch' );

Route::get( '/logout', 'Auth\AuthController@getLogout' );
Route::get( '/login', 'Auth\AuthController@getLogin' );
Route::get( '/login/{param}', 'Auth\AuthController@getLogin' );
Route::get( '/register', 'Auth\AuthController@getLogin' );
Route::get( '/signup', 'Auth\AuthController@getLogin' );
Route::post( '/auth/stepOne', 'Auth\AuthController@stepOne' );
Route::post( '/auth/stepTwo', 'Auth\AuthController@stepTwo' );
Route::post( '/auth/stepTwoBrand', 'Auth\AuthController@stepTwoBrand' );
Route::post( '/auth/register', 'Auth\AuthController@postRegister' );

Route::post( 'saveProfilePicture', 'Auth\AuthController@saveProfilePicture' );
Route::get( '/', function () {
	//return redirect( '/auth/login' );
} );


//Route::get( 'home', 'HomeController@index' );
//Route::get('/profile', 'HomeController@profile');


/*
 * BrandController
 * */
//Route::get('/follow', 'BrandController@follow');
//Route::get('/unfollow', 'BrandController@unFollow');
//Route::get('/brands', 'BrandController@brands');
//Route::get('/brands/manage', 'BrandController@myBrands');
//Route::get('/brand/info/{user_id}', 'BrandController@profileInfo');
//Route::get('/brand/settings', 'BrandController@profileSetting');
//Route::get('/brand/{user_id}', 'BrandController@profile');


Route::controllers( [
	'auth'     => 'Auth\AuthController',
	'password' => 'Auth\PasswordController'
] );
Route::get( '/resendEmail', 'Auth\AuthController@resendEmail' );
Route::get( '/activate/{code}', 'Auth\AuthController@activateAccount' );
Route::get( 'user/already-registered', 'Auth\AuthController@userAlreadyRegistered' );

Route::controllers( [
	'auth'     => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
] );
Route::get( '/resendEmail', 'Auth\AuthController@resendEmail' );
Route::get( '/activate/{code}', 'Auth\AuthController@activateAccount' );


Route::group( [ 'middleware' => [ 'auth', 'data' ] ], function () {

	Route::get( 'home', function(){
		return redirect('/');
	});
	Route::get( '/', 'HomeController@index' );
	//Route::get( '/profile', 'HomeController@profile' );
	/*
	 * ConsumerController
	 * */
	Route::get( '/profile/info/{user_id}', 'ConsumerController@profileInfo' );
	Route::get( '/profile/settings/', 'ConsumerController@profileSetting' );
	Route::get( '/profile/{user_id}/{tab?}', 'UsersController@profile' );
	Route::get( '/brand/{user_id}/{tab?}', 'UsersController@profile' );

	Route::get( 'groups', 'GroupController@index' );
	Route::get( 'groups/manage', 'GroupController@manageGroups' );
	Route::get( 'group/create/{user_id?}', 'GroupController@getCreate' );
	Route::get( 'group/edit/{group_id?}', 'GroupController@edit' );
	Route::patch( 'group/update/{group_id}', 'GroupController@update' );
	Route::post( 'group/create/{user_id?}', 'GroupController@create' );
	Route::get( 'group/delete/{group_id?}', 'GroupController@destroy' );
	Route::get( 'group/members/{group_id}', 'GroupController@getGroupMembers' );
	Route::get( 'group/{group_id}', 'GroupController@getGroup' );
	Route::get( 'group/events/{group_id}', 'GroupController@getGroupEvents' );


// Events routes
	Route::post( 'request/event/invite', 'EventController@attend' );
	Route::post( 'request/event/delete', 'EventController@deleteAttendRequest' );
	Route::get( 'event/delete/{event_id?}', 'EventController@destroy' );
	Route::post( '/event/rsvp', 'EventController@rsvpAjax' );
	Route::get( '/events', 'EventController@events' );
	Route::get( '/event/create/parent_type/{parent_type}/parent_id/{parent_id}', 'EventController@getCreateEvent' );
	Route::post( '/event/create/parent_type/{parent_type}/parent_id/{parent_id}', 'EventController@create' );
	Route::get( '/event/edit/{event_id}', 'EventController@edit' );
	Route::patch( '/event/update/{event_id}', 'EventController@update' );
	Route::get( '/event/{event_id}', 'EventController@index' );
	/*
	 * End ConsumerController
	 * */

	/*
	 * BrandController
	 * */
	Route::get( '/follow', 'BrandController@follow' );
	Route::get( '/unfollow', 'BrandController@unFollow' );
	Route::get( '/brands', 'BrandController@brands' );
	Route::get( '/brands/manage', 'BrandController@myBrands' );
	Route::get( '/brand/kinnectors/{brand_id}', 'BrandController@brandsKinnectors' );
	Route::get( '/brand/info/{user_id}', 'BrandController@profileInfo' );
	Route::get( '/brand/settings', 'BrandController@profileSetting' );
	///Route::get( '/brand/{user_id}', 'BrandController@profile' );

	/*
	 * End ConsumerController
	 * */

	Route::get( 'groups', 'GroupController@index' );
	Route::get( 'groups/manage', 'GroupController@manageGroups' );
	Route::get( 'group/create/{user_id?}', 'GroupController@getCreate' );
	Route::get( 'group/edit/{group_id?}', 'GroupController@edit' );
	Route::patch( 'group/update/{group_id}', 'GroupController@update' );
	Route::post( 'group/create/{user_id?}', 'GroupController@create' );
	Route::get( 'group/delete/{group_id?}', 'GroupController@destroy' );
	Route::get( 'group/members/{group_id}', 'GroupController@getGroupMembers' );
	Route::get( 'group/{group_id}', 'GroupController@getGroup' );
	Route::get( 'group/events/{group_id}', 'GroupController@getGroupEvents' );


	Route::post( 'group/follow', 'GroupController@follow' );
	Route::post( 'group/unfollow', 'GroupController@unFollow' );


// Events routes
	Route::post( 'request/event/invite', 'EventController@attend' );
	Route::post( 'request/event/delete', 'EventController@deleteAttendRequest' );
	Route::get( 'event/delete/{event_id?}', 'EventController@destroy' );
	Route::post( '/event/rsvp', 'EventController@rsvpAjax' );
	Route::get( '/events', 'EventController@events' );
	Route::get( '/event/create/parent_type/{parent_type}/parent_id/{parent_id}', 'EventController@getCreateEvent' );
	Route::post( '/event/create/parent_type/{parent_type}/parent_id/{parent_id}', 'EventController@create' );
	Route::get( '/event/edit/{event_id}', 'EventController@edit' );
	Route::patch( '/event/update/{event_id}', 'EventController@update' );
	Route::get( '/event/{event_id}', 'EventController@index' );
	Route::get( 'group/follow', 'GroupController@follow' );
	Route::get( 'group/unfollow', 'GroupController@unFollow' );


//	Route::get('groups', 'GroupController@index');
//	Route::get('groups/manage', 'GroupController@manageGroups');
//	Route::get('group/create', 'GroupController@getCreate');
//	Route::get('groups/create-group', 'GroupController@getCreate');
//	Route::get('group/edit/{group_id?}', 'GroupController@edit');
//	Route::patch('group/update/{group_id}', 'GroupController@update');
//	Route::post('group/create/{user_id?}', 'GroupController@create');
//	Route::get('group/delete/{group_id?}', 'GroupController@destroy');


	/*
 * BrandController
 * */
//	Route::get('/follow', 'BrandController@follow');
//	Route::get('/unfollow', 'BrandController@unFollow');
//	Route::get('/brands', 'BrandController@brands');
//	Route::get('/brands/manage', 'BrandController@myBrands');
//	Route::get('/brand/info/{user_id}', 'BrandController@profileInfo');
//	Route::get('/brand/settings', 'BrandController@profileSetting');
//	Route::get('/brand/{user_id}', 'BrandController@profile');

	/*
	 * End ConsumerController
	 * */
	/*
	 *  PollsController
	 */
	Route::get( 'polls/manage', 'PollsController@manage' );
	Route::resource( 'polls', 'PollsController' );
	Route::get( 'polls/delete/{id}', 'PollsController@destroy' );
	Route::get( 'polls/votes/{id}', 'PollsController@updatesVotes' );
	Route::get( 'polls/closed/{id}', array( 'uses' => 'PollsController@closed', 'as' => 'polls.closed' ) );

	/*
	 * End PollsController
	 */


	/*
	 * BattlesController
	 */

	Route::get( '/abc/{brandName}', 'BattlesController@brandBattleNameSuggestion' );
	Route::get( 'battles/manage', 'BattlesController@manage' );
	Route::resource( 'battles', 'BattlesController' );
	Route::get( 'battles/delete/{id}', 'BattlesController@destroy' );
	Route::get( 'battles/votes/{id}', 'BattlesController@updatesVotes' );
	Route::get( 'battles/closed/{id}', array( 'uses' => 'BattlesController@closed', 'as' => 'battles.closed' ) );

	/*
	 * End BattlesController
	 */


	Route::get( 'pages/signUp', 'pagesController@signUp' );
	Route::get( 'pages/index', 'pagesController@index' );
	Route::get( 'pages/activityLog', 'pagesController@activityLog' );
	Route::get( 'pages/userProfile', 'pagesController@userProfile' );
	Route::get( 'pages/album', 'pagesController@album' );
	Route::get( 'pages/albumPhotos', 'pagesController@albumPhotos' );
	Route::get( 'pages/battles', 'pagesController@battles' );
	Route::get( 'pages/battleBrowse', 'pagesController@battleBrowse' );
	Route::get( 'pages/battleDetail', 'pagesController@battleDetail' );
	Route::get( 'pages/brands', 'pagesController@brands' );
	Route::get( 'pages/createAlbum', 'pagesController@createAlbum' );
	Route::get( 'pages/myPolls', 'pagesController@myPolls' );
	Route::get( 'pages/createPoll', 'pagesController@createPoll' );

	Route::get( 'static/index', 'StaticInterfacesController@index' );


	Route::get( 'pages/createDiscussionTopic', 'pagesController@createDiscussionTopic' );
	Route::get( 'pages/createEvent', 'pagesController@createEvent' );
	Route::get( 'pages/createMusic', 'pagesController@createMusic' );
	Route::get( 'pages/friendRequests', 'pagesController@friendRequests' );
	Route::get( 'pages/wallPhotos', 'pagesController@wallPhotos' );
	Route::get( 'pages/communityAd', 'pagesController@communityAd' );

	Route::get( 'pages/discussions', 'pagesController@discussions' );
	Route::get( 'pages/kinnectors', 'pagesController@kinnectors' );
	Route::get( 'pages/createBattle', 'pagesController@createBattle' );
	Route::get( 'pages/storeAdmin', 'pagesController@storeAdmin' );
	Route::get( 'pages/storeProductDetail', 'pagesController@storeProductDetail' );

	Route::get( 'pages/storeProductRange', 'pagesController@storeProductRange' );
	Route::get( 'pages/userProfileInfo', 'pagesController@userProfileInfo' );
	Route::get( 'pages/storeFeatured', 'pagesController@storeFeatured' );
	Route::get( 'pages/createGroup', 'pagesController@createGroup' );
	Route::get( 'pages/storeManagePro', 'pagesController@storeManagePro' );
	Route::get( 'pages/storeProCategory', 'pagesController@storeProCategory' );
	Route::get( 'pages/storeProSubCategory', 'pagesController@storeProSubCategory' );
	Route::get( 'pages/storeAddProduct', 'pagesController@storeAddProduct' );
	Route::get( 'pages/editProfile', 'pagesController@editProfile' );
	Route::get( 'pages/shippingAddress', 'pagesController@shippingAddress' );
	Route::get( 'pages/shoppingCart', 'pagesController@shoppingCart' );

	Route::get( 'pages/pollsDetail', 'pagesController@pollsDetail' );
	Route::get( 'pages/reviewOrder', 'pagesController@reviewOrder' );


	Route::get( '/pull', 'HomeController@pull' );
	Route::get( '/getComments/{id}', 'HomeController@getComments' );
	Route::get( '/getCommentsThreaded', 'HomeController@getCommentsThreaded' );


	/*
	 *  Leadership View More
	 */

	Route::get( 'leaderboard/consumers', function () {
		return view( 'leaderboard.consumers' );
	} );
	Route::get( 'leaderboard/brands', function () {
		return view( 'leaderboard.brands' );
	} );

	/*
	 * End Leadership
	 */


	Route::get( 'static/index', 'StaticInterfacesController@index' );


	Route::get( '/pull', 'HomeController@pull' );
	Route::post( '/shareStatus', 'HomeController@postStatus' );
	Route::get( '/fetchComments/{id}', 'HomeController@getComments' );
	Route::get( '/fetchCommentsThreaded/{:id}', 'HomeController@getCommentsThreaded' );
	Route::get( '/deleteStatus/{id}', 'HomeController@deleteStatus' );
	Route::post( '/addComment/{id}', 'HomeController@postComment' );
	Route::post('/addCommentThreaded','HomeController@postCommentThreaded' );
	Route::post( '/commentPhoto/{id}/{id1}', 'HomeController@commentPhoto' );
	Route::post( '/editStatus', 'HomeController@editStatus' );
	Route::post( '/uploadImage', 'HomeController@uploadImage' );
	Route::get( '/likeStatus/{id}', 'HomeController@likeStatus' );
	Route::get( '/dislikeStatus/{id}', 'HomeController@dislikeStatus' );
	Route::get( '/undoDislike/{id}', 'HomeController@undoDislike' );
	Route::get( '/likePhoto/{id}', 'HomeController@likePhoto' );
	Route::get( '/unlikeStatus/{id}', 'HomeController@unlikeStatus' );
	Route::get( '/likeComment/{id}', 'HomeController@likeActivityComment' );
	Route::get( '/makeActivityFavourite/{id}', 'HomeController@makeActivityFavourite' );
	Route::get( '/removeActivityFavourite/{id}', 'HomeController@removeActivityFavourite' );
	Route::post( '/shareActivity', 'HomeController@shareActivity' );


	//Friendship Routes
	Route::group( array( 'prefix' => 'friends' ), function () {
		Route::get( 'request/{id?}', 'FriendshipController@index' );
		Route::get( 'add-friend/{id}', 'FriendshipController@add_friend' );
		Route::get( 'confirm/{resource_id}', 'FriendshipController@confirm' );
		Route::get( 'delete/{resource_id}', 'FriendshipController@destroy' );
		Route::get( 'unfriend/{resource_id}', 'FriendshipController@destroy' );
		Route::get( 'unfollow/{resource_id}', 'FriendshipController@unfollow' );
		Route::get( 'follow/{resource_id}', 'FriendshipController@follow' );
		Route::resource( '/', 'FriendshipController' );
	} );

	//Profile Routes
	Route::group( array( 'prefix' => 'profile' ), function () {
		Route::get( 'edit', 'UsersController@edit' );
		Route::post( 'update', 'UsersController@update' );
		Route::post( 'profile-view', 'UsersController@profile_content' );
		Route::resource( '/', 'UsersController' );
	} );

	Route::post( 'feedback', 'HomeController@feedback' );

	Route::get('brand-store-created', 'BrandController@store_created');
} );

//API Routes
include( 'routes/api-routes.php' );


