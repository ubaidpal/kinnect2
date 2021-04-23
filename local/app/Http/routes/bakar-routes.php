<?php 

Route::group(['middleware' => ['auth', 'data']], function () {

	Route::get('/unlikeComment/{id}','HomeController@unlikeActivityComment');
	Route::get('/deleteComment/{id}','HomeController@deleteActivityComment');
	Route::post('/uploadAudio','HomeController@uploadAudio');
	Route::get('/getUserPosts/{id?}','HomeController@getUserPosts');
	Route::post('/extractLinkMeta','HomeController@extractLinkMeta');
	Route::get('groupPosts/{id}','HomeController@getGroupPosts');
	Route::get('getPost/{id}','HomeController@getPost');
	Route::post('shareGroup/{id}','HomeController@shareGroup');
	Route::post('shareEvent/{id}','HomeController@shareEvent');
	Route::post('changeCoverPhoto','HomeController@changeCoverPhoto');
	Route::post('saveCoverPhoto','HomeController@saveCoverPhoto');
	Route::post('flagActivity/','HomeController@flagActivity');
	Route::get('/getPopupPhotos','HomeController@getPopupPhotos');
	Route::get('/recommended_polls','PollsController@recommended_polls');
	Route::get('/hashTag/{tag}','HomeController@index');
	Route::get('/getPostLikes/{post_id}','HomeController@getPostLikes');
	Route::get('/getEditPost/{post_id}','HomeController@getEditPost');
	Route::get('/getPostDisikes/{post_id}','HomeController@getPostDislikes');
	Route::get('/showOnTimeline/{battle_id}','BattlesController@showOnTimeline');
	Route::get('/removeFromTimeline/{battle_id}','BattlesController@removeFromTimeline' );
	Route::get('/getBrands','BattlesController@getBrands');
	Route::post('/deletToken','HomeController@deleteToken');
});

Route::get('view/{type}/{id}','HomeController@viewDetails');
Route::get('photo/{user_id}/{name}','HomeController@getPhoto');
Route::get('video/{user_id}/{name}','HomeController@getVideo');
Route::get('audio/{user_id}/{name}','HomeController@getAudio');
Route::get('thumb/{path}','HomeController@getVideoThumb');
Route::get('attachment/{user_id}/{name}','HomeController@getAttachment');
Route::get('attachment_thumb/{user_id}/{name}','HomeController@getAttachmentThumb');

Route::get('importActivity','migrationsController@importActivity');
Route::get('importFiles','migrationsController@saveFile');
Route::get('updatePermissions','migrationsController@updatePermissions');
Route::get('deleteStorageFiles','CronjobController@deleteStorageFiles');
Route::get('transferSellerAmount','CronjobController@transferSellerAmount');
?>
