<?php

namespace kinnect2Messages\Messages\Http;

use App\Http\Controllers\Controller;
use App\User;
use App\users_membership;
use Auth;
use DB;
use kinnect2Messages\Messages\Message;
use kinnect2Messages\Messages\MessageRecipient;
use kinnect2Messages\Messages\MessagesConversation;
use kinnect2Messages\Messages\OnlineUser;
use Request;

class MessageController extends Controller
{
    public function getChatList()
    {
        $myFriendsIds = $this->myFriendsIds();

        $data['onlineUsers'] = DB::table('users')
            ->join('online_users', 'users.id', '=', 'online_users.user_id')

            ->join('album_photos', 'users.photo_id', '=', 'album_photos.photo_id')
            ->join('storage_files', 'album_photos.file_id', '=', 'storage_files.parent_file_id')

            ->select('users.name', 'users.username', 'users.userable_type', 'storage_files.storage_path as image')

            ->where('online_users.user_id', '!=', Auth::user()->id)
            ->where('storage_files.type', 'thumb_icon')
            ->whereIn('users.id', $myFriendsIds)
            ->get();

        return view('Messages::chat-list', $data);
    }

    public function myFriendsIds()
    {
        $ids =  users_membership::where('user_id', Auth::user()->id)
            ->lists('resource_id');
        return $ids;
    }

    public function onlineAddUser(Request $request)
    {
        $username = $_POST['username'];
        $user = User::where('username', '=', $username)->first();

        OnlineUser::where('user_id', $user->id)->delete();

        $online = new OnlineUser();

        $online->user_id = $user->id;
        $online->status  = 1;

        $online->save();

        return $online->id;
    }

    public function onlineAddMessage(Request $request ) {

        $username           = $_POST['username'];
        $receiver_username  = $_POST['receiver_username'];
        $message_body       = $_POST['msg'];

        $sender   = User::where('username', '=', $username)->first();
        $receiver = User::where('username', '=', $receiver_username)->first();

        if($receiver_username != $sender->username){

            $conversation = Message::select('conversation_id')
                       ->where('user_id', $receiver->id)
                       ->where('receiver_id', Auth::user()->id)
                       ->orWhere('user_id', Auth::user()->id)
                       ->orWhere('receiver_id', $receiver->id)
                   ->first();

            $conversation_id = 0;

            if(isset($conversation->conversation_id)){
                $conversation_id = $conversation->conversation_id;
            }

            if(!isset($conversation->conversation_id))
            {
                // Create Conversation
                $conversation = new MessagesConversation();

                $conversation->user_id = $receiver->id;
                $conversation->recipients = 1;
                $conversation->title = $receiver->displayname.' to '.$sender->displayname;
                $conversation->locked = 0;
                $conversation->resource_type = 'user';
                $conversation->resource_id   = $receiver->id;

                $conversation->save();

                $conversation_id = $conversation->id;

            }


            // Create message
            $message = new Message();

            $message->conversation_id = $conversation_id;
            $message->user_id         = $receiver->id;
            $message->receiver_id     = $sender->id;
            $message->title           = $receiver->displayname.' to '.$sender->displayname;
            $message->body            = $message_body;
            $message->attachment_type = 1;
            $message->attachment_id   = 0;

            $message->save();

            // Create sender read/Unread

            $messageStatus = new MessageRecipient();

            $messageStatus->user_id         = $sender->id;
            $messageStatus->conversation_id = $conversation_id;
            $messageStatus->inbox_message_id= $message->id;
            $messageStatus->inbox_updated   = 0;
            $messageStatus->inbox_read      = 0;
            $messageStatus->inbox_deleted   = 0;
            $messageStatus->outbox_deleted  = 0;
            $messageStatus->outbox_message_id = 1;
            $messageStatus->outbox_updated    =0;

            $messageStatus->save();

            // Create $receiver read/Unread

            $messageStatus = new MessageRecipient();

            $messageStatus->user_id         = $receiver->id;
            $messageStatus->conversation_id = $conversation_id;
            $messageStatus->inbox_message_id= $message->id;
            $messageStatus->inbox_updated   = 0;
            $messageStatus->inbox_read      = 0;
            $messageStatus->inbox_deleted   = 0;
            $messageStatus->outbox_deleted  = 0;
            $messageStatus->outbox_message_id = 0;
            $messageStatus->outbox_updated    =0;

            $messageStatus->save();
            return $sender->displayname;
        }

    }

    public function onlineGetMessages($user_id) {

        $receiver   = User::where('username', '=', $user_id)->first();

        $users = DB::table('messages_conversations')
            ->join('messages', 'messages_conversations.id', '=', 'messages.conversation_id')
            ->join('users', 'messages.user_id', '=', 'users.id')
                   ->select('messages.id','messages.body', 'messages_conversations.user_id as receiver_id', 'users.displayname as user_name', 'users.username as profile_address')
            ->where('messages.user_id', $receiver->id)
            ->where('messages.receiver_id', Auth::user()->id)
            ->orWhere('messages.user_id', Auth::user()->id)
            ->orWhere('messages.receiver_id', $receiver->id)
//            ->orderBy('messages.id', 'DESC')
            ->take(15)
           ->get();

        if(count($users) > 0){
            return $users;
        }else{
            return 0;
        }
    }
}
