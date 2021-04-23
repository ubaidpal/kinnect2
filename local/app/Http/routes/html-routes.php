<?php 

Route::get('pages/signUp', 'pagesController@signUp');
Route::get('pages/index', 'pagesController@index');
Route::get('pages/activityLog', 'pagesController@activityLog');
Route::get('pages/userProfile', 'pagesController@userProfile');
Route::get('pages/album', 'pagesController@album');
Route::get('pages/albumPhotos', 'pagesController@albumPhotos');
Route::get('pages/battles', 'pagesController@battles');
Route::get('pages/battleBrowse', 'pagesController@battleBrowse');
Route::get('pages/battleDetail', 'pagesController@battleDetail');
Route::get('pages/brands', 'pagesController@brands');
Route::get('pages/createAlbum', 'pagesController@createAlbum');
Route::get('pages/myPolls', 'pagesController@myPolls');
Route::get('pages/createPoll', 'pagesController@createPoll');

Route::get('pages/createDiscussionTopic', 'pagesController@createDiscussionTopic');
Route::get('pages/createEvent', 'pagesController@createEvent');
Route::get('pages/createMusic', 'pagesController@createMusic');
Route::get('pages/friendRequests', 'pagesController@friendRequests');
Route::get('pages/wallPhotos', 'pagesController@wallPhotos');
Route::get('pages/communityAd', 'pagesController@communityAd');

Route::get('pages/discussions', 'pagesController@discussions');
Route::get('pages/kinnectors', 'pagesController@kinnectors');
Route::get('pages/createBattle', 'pagesController@createBattle');
Route::get('pages/storeAdmin', 'pagesController@storeAdmin');
Route::get('pages/storeProductDetail', 'pagesController@storeProductDetail');

Route::get('pages/storeProductRange', 'pagesController@storeProductRange');
Route::get('pages/userProfileInfo', 'pagesController@userProfileInfo');
Route::get('pages/storeFeatured', 'pagesController@storeFeatured');
Route::get('pages/createGroup', 'pagesController@createGroup');
Route::get('pages/storeManagePro', 'pagesController@storeManagePro');
Route::get('pages/storeProCategory', 'pagesController@storeProCategory');
Route::get('pages/storeProSubCategory', 'pagesController@storeProSubCategory');
Route::get('pages/storeAddProduct', 'pagesController@storeAddProduct');
Route::get('pages/editProfile', 'pagesController@editProfile');
Route::get('pages/shippingAddress', 'pagesController@shippingAddress');
Route::get('pages/shoppingCart', 'pagesController@shoppingCart');
Route::get('pages/addBankAccount', 'pagesController@addBankAccount');
Route::get('pages/storeOrderSuccessful', 'pagesController@storeOrderSuccessful');
Route::get('pages/storeTotalEarnings', 'pagesController@storeTotalEarnings');
Route::get('pages/storeManagerPanelOrders', 'pagesController@storeManagerPanelOrders');


Route::get('pages/pollsDetail', 'pagesController@pollsDetail');
Route::get('pages/reviewOrder', 'pagesController@reviewOrder');

Route::get('pages/privacySetting', 'pagesController@privacySetting');
Route::get('pages/groupDetail', 'pagesController@groupDetail');
Route::get('pages/generalSetting', 'pagesController@generalSetting');
Route::get('pages/networkSetting', 'pagesController@networkSetting');
Route::get('pages/notificationSetting', 'pagesController@notificationSetting');
Route::get('pages/changePassword', 'pagesController@changePassword');
Route::get('pages/deleteAccount', 'pagesController@deleteAccount');
Route::get('pages/event', 'pagesController@event');
Route::get('pages/eventDetail', 'pagesController@eventDetail');
Route::get('pages/eventInformation', 'pagesController@eventInformation');
Route::get('pages/myCampaigns', 'pagesController@myCampaigns');
Route::get('pages/reports', 'pagesController@reports');
Route::get('pages/help_center', 'pagesController@help_center');
Route::get('pages/error_404', 'pagesController@error_404');
Route::get('pages/error_505', 'pagesController@error_505');
Route::get('pages/maintenance', 'pagesController@maintenance');
Route::get('pages/storeDisputeDetail', 'pagesController@storeDisputeDetail');
Route::get('pages/storePaymentMethod', 'pagesController@storePaymentMethod');
Route::get('pages/storeArbitrator', 'pagesController@storeArbitrator');
Route::get('pages/storeUserManagement', 'pagesController@storeUserManagement');
Route::get('pages/storeAddUser', 'pagesController@storeAddUser');
Route::get('pages/storeDisputePopup', 'pagesController@storeDisputePopup');
Route::get('pages/storeUnassigned', 'pagesController@storeUnassigned');
Route::get('pages/storeOrderDetail', 'pagesController@storeOrderDetail');
Route::get('pages/storeShippingMethod', 'pagesController@storeShippingMethod');
Route::get('pages/storeDisputeCase', 'pagesController@storeDisputeCase');
Route::get('pages/storeAdminStoreManagement', 'pagesController@storeAdminStoreManagement');
Route::get('pages/storeWithdrawlRequest', 'pagesController@storeWithdrawlRequest');
Route::get('pages/storeRequestWithdrawls', 'pagesController@storeRequestWithdrawls');
Route::get('pages/storeStatement', 'pagesController@storeStatement');
Route::get('pages/storeBankDetailPopup', 'pagesController@storeBankDetailPopup');
Route::get('pages/shippingAddressMultiple', 'pagesController@shippingAddressMultiple');
Route::get('pages/storeEnterInfo', 'pagesController@storeEnterInfo');
Route::get('pages/storeWithdrawalPopup', 'pagesController@storeWithdrawalPopup');
Route::get('pages/storeCreatePopup', 'pagesController@storeCreatePopup');


/* mobile routes*/

Route::get('pages/index', 'mobileController@index');
Route::get('pages/signup', 'mobileController@signup');
Route::get('pages/signin', 'mobileController@signin');
Route::get('pages/signupConsumer', 'mobileController@signupConsumer');
Route::get('pages/signupBrand', 'mobileController@signupBrand');
Route::get('pages/changePassword', 'mobileController@changePassword');
Route::get('pages/dashboard', 'mobileController@dashboard');
Route::get('pages/dashboardPostview', 'mobileController@dashboardPostview');
Route::get('pages/miscellaneous', 'mobileController@miscellaneous');
Route::get('pages/leftNav', 'mobileController@leftNav');
Route::get('pages/friends', 'mobileController@friends');
Route::get('pages/brands', 'mobileController@brands');
Route::get('pages/createPoll', 'mobileController@createPoll');
Route::get('pages/pollAll', 'mobileController@pollAll');
Route::get('pages/pollMy', 'mobileController@pollMy');
Route::get('pages/pollResults', 'mobileController@pollResults');
Route::get('pages/pollVote', 'mobileController@pollVote');
Route::get('pages/battleView', 'mobileController@battleView');
Route::get('pages/messages', 'mobileController@messages');
Route::get('pages/notAvailable', 'mobileController@notAvailable');
Route::get('pages/lostPassword', 'mobileController@lostPassword');
Route::get('pages/resetPassword', 'mobileController@resetPassword');
Route::get('pages/faq', 'mobileController@faq');

?>