<?php
Route::group(['middleware' => ['auth', 'data']], function () {
    Route::get('postDetail/{id}', 'HomeController@postDetail');
});

Route::get('postDetail/{id}', 'HomeController@publicPostDetail');
?>
