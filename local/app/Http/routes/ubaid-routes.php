<?php
Route::group(array('middleware' => ['auth', 'data']), function() {
	Route::post( 'pages/save_setting', 'UsersController@saveSetting' );
	//Route::get( 'checkbox{id}', 'UsersController@getSettingRecord' );

	Route::get( 'settings/general', 'UsersController@generalSetting' );
	Route::post( 'settings/generalSettingSave', 'UsersController@generalSettingSave' );

	Route::get( 'settings/privacy', 'UsersController@privacySetting' );
	Route::post( 'privacySetting', 'UsersController@postSetting' );

	Route::get( 'settings/notification', 'UsersController@notificationSetting' );

	Route::get( 'settings/change-password', 'UsersController@changePassword' );
	Route::post( 'settings/password_change', 'UsersController@userPasswordChange' );


	Route::get('settings/delete-account', 'UsersController@deleteAccountpage');
	Route::post('delete_account', 'UsersController@postDeleteAccount');

	Route::get('notification', 'NotificationController@showNotification');
	Route::post('notification/notificationDetailViewMore/', 'NotificationController@showMoreNotification');



});
Route::get( 'policy/terms', 'pagesController@terms' );
Route::get( 'policy/condition', 'pagesController@condition' );
Route::get('policy/faq', 'pagesController@faq');
?>
