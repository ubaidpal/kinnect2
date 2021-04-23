<?php
Route::get('chat/list', 'kinnect2Messages\Messages\Http\MessageController@getChatList');
Route::post('chat/online/add-user', 'kinnect2Messages\Messages\Http\MessageController@onlineAddUser');
Route::post('chat/online/add-message', 'kinnect2Messages\Messages\Http\MessageController@onlineAddMessage');

Route::get('chat/online/get-messages/{user_id}', 'kinnect2Messages\Messages\Http\MessageController@onlineGetMessages');

