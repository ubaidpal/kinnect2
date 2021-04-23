<?php
Route::group(['middleware' => ['auth', 'data']], function() {

    Route::patch('battles/update/{id}', 'BattlesController@update');
    Route::patch('polls/update/{id}', 'PollsController@update');

    Route::get('favourites', 'FavouritesController@index');
    Route::get('skore', 'HomeController@skoring');

    /*
      * AlbumsController
    */

    Route::resource('albums', 'AlbumsController');
    Route::get('albums/{id}','AlbumsController@getAlbums');
    Route::get('albums/edit/{album_id?}', 'AlbumsController@edit');
    Route::patch('albums/update/{album_id}', 'AlbumsController@update');
    Route::get('albums/delete/{id}', 'AlbumsController@destroy');
    Route::get('albums/addPhotos/{id}', 'AlbumsController@addPhotos');

	/*
  * End AlbumsController
*/

	/*
	 *  Groups Controller
	 */
	Route::post('groups/remove','GroupController@removeMember');
	Route::get('group/pending/{id}','GroupController@pendingRequest');
    Route::get('group/pendingInvites/{id}','GroupController@pendingInvites');
	Route::get('group/info/{id}','GroupController@groupInfo');
	Route::post('groups/approveReq','GroupController@ApproveReq');
	Route::post('groups/rejectReq','GroupController@RejectReq');
	Route::post('inviteGroup/{id}','GroupController@inviteGroup');
    Route::get( 'group/unfollow/{id}', 'GroupController@UN_Follow' );
    Route::post('groups/makeManager','GroupController@MakeManager');
    Route::post('groups/demoteManager','GroupController@DemoteManager');
    Route::post('groups/acceptManagerReq','GroupController@ApproveManagerReq');
    Route::get( 'group/leaveManagership/{id}', 'GroupController@Leave_ManagerShip' );

	/*
	 * End GroupsController
	 */

    /*
	 *  Events Controller
	 */
    //Route::get('group/pending/{id}','GroupController@pendingRequest');
    //Route::get('group/pendingInvites/{id}','GroupController@pendingInvites');
    Route::post('inviteEvent/{id}','EventController@inviteEvent');
    Route::post('request/event/approve','EventController@approveInvite');

    /*
     * End EventsController
     */
});

?>
