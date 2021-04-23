<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : kinnect2
 * Product Name : PhpStorm
 * Date         : 02-1-16 4:00 PM
 * File Name    : MessageRepository.php
 */

namespace App\Repository\Eloquent;

use App\Conversation;
use App\ConversationUser;
use App\Message;
use App\MessageStatus;
use App\StorageFile;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use \TBMsg;
use Tzookb\TBMsg\Exceptions\UserNotInConvException;
use App\Repository\Entities\Message as MessageEntity;
use App\Repository\Entities\Conversation as ConversationEntity;
use Tzookb\TBMsg\Repositories\Contracts\iTBMsgRepository;
use Tzookb\TBMsg\Repositories\EloquentTBMsgRepository;
use SNS;

class MessageRepository extends Repository {
    const GROUP    = 'group';
    const COUPLE   = 'couple';
    const DELETED  = 0;
    const UNREAD   = 1;
    const READ     = 2;
    const ARCHIVED = 3;

    protected $allowedPhotoFiles = ['png', 'jpeg', 'jpg', 'bmp', 'gif','tiff','webp'];
    protected $allowedVideoFiles = ['wmv', 'mp4', 'mkv', 'flv', 'FLV','qt','mov','amv','mpg','swf','webm'];
    protected $allowedAudioFiles = ["mp3",'m4a','aac','flac','mid','ac3','ogg','mka','voc','au','amr','aiff','ra','wma'];
    protected $notAllowedFiles   = ["exe"];
    /**
     * @var iTBMsgRepository
     */
    private $tbmRepo;

    /**
     * MessageRepository constructor.
     */
    public function __construct() {
        parent::__construct();

    }

    public function get_conversations($user_id) {

        $data['conversation'] = $this->getUserConversations($user_id);

        $data['conversation'] = $this->filter_conversation($data['conversation'], $user_id);

        $data['conv_id'] = '';
        $all_conv_id     = [];
        //gathering participants
        $total = count($data['conversation']);
        if($total > 0) {
            $participants = [];
            $l            = 1;
            foreach ($data['conversation'] as $conv) {
                if($l == 1) {
                    $data['conv_id']            = $conv->getId();
                    $data['first_conversation'] = $conv;
                    $l                          = 2;
                }
                array_push($all_conv_id, $conv->getId());
                $participants = array_merge($participants, $conv->getAllParticipants());
            }
            $names = Conversation::whereIn('id', $all_conv_id)->get();

            $data['conv_data']     = [];
            $data['dispute_count'] = 0;
            $data['message_count'] = 0;
            foreach ($names as $name) {
                $data['conv_data'][$name->id] = $name;
                if($name->conv_for == 'dispute') {
                    $data['dispute_count']++;
                } elseif($name->conv_for == 'messages' || $name->conv_for == 0) {
                    $data['message_count']++;
                }
            }

            //making sure each user appears once
            $participants = array_unique($participants);

            $messages          = $this->getConversationMessages($data['conv_id'], $user_id, FALSE);
            $data['messages']  = $messages->getAllMessages();
            $data['conv_type'] = $messages->getType();

            //getting all data of participants
            $viewUsers = [];
            //$participants = array_diff($participants,[$user_id]);

            if(!empty($participants)) {
                $users = User::whereIn('id', $participants)->with('album_photo.storage_file')->get();

            }

            foreach ($users as $key => $user) {
                $viewUsers[$user->id] = $user;
            }

            TBMsg::markReadAllMessagesInConversation($data['conv_id'], $user_id);
            $data['participants'] = $participants;
            $data['users']        = $viewUsers;
        }

        //echo '<tt><pre>'; print_r($data); die;
        return $data;
    }

    public function filter_conversation($all_conversation, $user_id) {
        $my_conversations = \DB::table('conv_users')->where('user_id', $user_id)->lists('conv_id');

        foreach ($all_conversation as $key => $row) {
            $id = $row->getId();
            if(!in_array($id, $my_conversations)) {
                unset($all_conversation[$key]);
            }
        }

        return $all_conversation;
    }

    public function conv_users() {
        return \DB::table('conversations')->join('conv_users', 'conv_users.conv_id', '=', 'conversation.id')
                  ->select('conv_id', 'user_id')->get();
    }

    public function get_messages($user_id, $conv_id, $last_update_time = NULL) {

        TBMsg::markReadAllMessagesInConversation($conv_id, $user_id);

        $data['messages'] = $this->getConversationMessages($conv_id, $user_id, FALSE, $last_update_time);

        $data['conv_type'] = $data['messages']->getType();
        $participants      = $data['messages']->getAllParticipants();
        //$participants = $this->getUsersInConversation($conv_id);
        if(!empty($participants)) {
            $users = User::whereIn('id', $participants)->with('album_photo.storage_file')->get();

        }
        $viewUsers = [];
        foreach ($users as $key => $user) {
            $viewUsers[$user->id] = $user;
        }
        $data['conv_id']  = $conv_id;
        $data['users']    = $viewUsers;
        $data['users_id'] = $participants;
        $data['messages'] = $data['messages']->getAllMessages();

        return $data;
    }

    /**
     * @param $data
     * @param $user_id
     *
     * @return array
     * @throws UserNotInConvException
     *
     * @DESCRIPTION: Save message. If conversation exist then save in it otherwise create new and save in it.
     */
    public function save_message($data, $user_id) {

        //If conversation id is given
        if(isset($data['conv_id']) && !empty($data['conv_id'])) {
            return $this->addMessageToConversation($data['conv_id'], $user_id, $data);
        } else {
            //If more
            if(isset($data['members'])) {
                $data['members'] = array_diff($data['members'], array($user_id));
                $total_members   = count($data['members']);
                if($total_members == 1) {
                    $data['receiver'] = $data['members'][0];
                } else {
                    $members['users'] = $data['members'];
                    array_push($members['users'], $user_id);
                    $conv_id = $this->make_group($members, $user_id);

                    return $this->addMessageToConversation($conv_id['convId'], $user_id, $data);
                }
            }
            if(isset($data['receiver_id'])) {
                $data['receiver'] = $data['receiver_id'];
            }

            $conv_id = $this->check_userin_conversation($user_id, $data['receiver']);

            if($conv_id) {
                return $this->addMessageToConversation($conv_id, $user_id, $data);

            } else {
                $conv_id = $this->createConversation($users_ids = array($user_id, $data['receiver']), $user_id);

                return $this->addMessageToConversation($conv_id['convId'], $user_id, $data);
            }
        }
    }

    public function make_group($data, $user_id) {
        $users = $data['users'];
        if(count($users) > 2) {
            $conv_id = $this->createConversation($users, $user_id);
            unset($conv_id['usersIds']);

            return $conv_id;
        } else {
            return '0';
        }
    }

    public function createConversation($members, $user_id, $type = NULL) {
        if(is_null($type)) {
            if(count($members) > 2) {
                $type = self::GROUP;
            } else {
                $type = self::COUPLE;
            };
        }

        $user_name = \DB::table('users')->whereIn('id', $members)->lists('first_name');

        $conversation             = new Conversation();
        $conversation->type       = $type;
        $conversation->created_by = $user_id;
        //$user_name = array_slice($user_name, 0, 2);
        $conversation->title = rtrim(implode(', ', $user_name), ',');
        $conversation->save();

        $conv_id = $conversation->id;

        $this->add_member_to_group($members, $conv_id);
        $eventData = ['usersIds' => $members, 'convId' => $conv_id, 'title' => $conversation->title,];

        return $eventData;
    }

    public function add_member_to_group($members, $conversation) {
        foreach ($members as $member) {
            $conv = TBMsg::isUserInConversation($conversation, $member);
            if(!$conv) {
                \DB::table('conv_users')->insert(['conv_id' => $conversation, 'user_id' => $member]);
            }
        };

        return TBMsg::getUsersInConversation($conversation);

    }

    public function check_userin_conversation($user_id, $receiver_id) {
        return $conv_id = $this->getConversationByTowUsers($user_id, $receiver_id);
    }

    public function getConversationByTowUsers($user_id, $receiver_id) {
        $results = \DB::select(/** @lang MySQL */
            "SELECT conv_id FROM conv_users, conversations as co WHERE co.id=conv_id and co.type= '" . self::COUPLE . "' and conv_users.conv_id IN(SELECT conv_id FROM conv_users cu WHERE cu.user_id=$user_id OR cu.user_id=$receiver_id group by cu.conv_id HAVING COUNT(cu.conv_id) = 2) group BY conv_id HAVING COUNT(conv_id)  =  2");
        if(count($results) == 1) {
            return (int)$results[0]->conv_id;
        } else {
            return FALSE;
        }
    }

    public function update_name($conv_id, $name, $user_id) {
        \DB::table('conversations')->where('id', $conv_id)
           ->update(['title' => $name, 'updated_by' => $user_id, 'updated_at' => Carbon::now()]);

        return TRUE;
    }

    public function leave_group($conv_id, $user_id) {

        \DB::table('conv_users')->where('user_id', $user_id)->where('conv_id', $conv_id)->delete();

        return TRUE;
    }

    public function getConvMessages($conv_id, $page, $order = 'DESC') {
        $per_page = 25;
        if(\Input::has('page_size')) {
            $per_page = \Input::get('page_size');
        }

        $start_point = ($page * $per_page) - $per_page;

        $data = Message::where('conv_id', $conv_id)->take($per_page)->skip($start_point)->orderBy('id', $order)->get()
                       ->toArray();

        if(empty($data)) {
            if($this->is_api) {
                return '';
            }

            return 'No more Message';
        }
        $users = array_column($data, 'sender_id');
        $users = array_unique($users);

        return $this->messages_detail($data);
    }

    public function getConvAllMessages($conv_id, $order = 'DESC') {

        $data = Message::where('conv_id', $conv_id)->orderBy('id', $order)->get()->toArray();

        if(empty($data)) {
            if($this->is_api) {
                return '';
            }

            return 'No more Message';
        }

        return $this->messages_detail($data);
    }

    /**
     * @param $data
     *
     * @return array
     */
    private function messages_detail($data) {
        $users = array_column($data, 'sender_id');
        $users = array_unique($users);
        $users = User::whereIn('id', $users)->get()->keyBy('id');

        $all_message = [];
        foreach ($data as $row) {
            $row['sender_name'] = $users[$row['sender_id']]->displayname;
            if($this->is_api) {
                $row['sender_url'] = $users[$row['sender_id']]->username;
            } else {
                $row['sender_url'] = \Kinnect2::profileAddress($users[$row['sender_id']]);
            }
            $row['sender_type'] = $users[$row['sender_id']]->user_type;
            $row['profile_pic'] = \Kinnect2::getPhotoUrl($users[$row['sender_id']]->photo_id, $users[$row['sender_id']]->id, 'user', 'thumb_normal');

            if(!is_null($row['file_id'])) {

                $file_data        = get_photo_by_id($row['file_id'], TRUE, TRUE, $this->getAllowedVideoFiles());


                $row['file_name'] = $file_data['name'];

                $row['url']       = $file_data['url'];
                $row['file_meta'] = $this->get_file_meta($file_data);

            } else {
                $row['file_id'] = '';
            }
            $all_message[] = $row;
        }

        return $all_message;
    }

    public function get_user_detail($users) {
        $users = User::whereIn('id', $users)->get();

        return $this->_get_user_info($users);
    }

    public function get_friends($user_id) {
        $users      = \DB::table('user_membership')->join('users', 'users.id', '=', 'user_membership.user_id')
                         ->where('resource_id', $user_id)// ->where('users.user_type', '=', \Config::get('constants.REGULAR_USER'))
                         ->where('user_membership.active', 1)
                         ->select('user_membership.*', 'users.name', 'users.username', 'users.displayname', 'users.user_type', 'users.photo_id')
                         ->get();
        $users_data = [];
        $row        = [];
        foreach ($users as $user) {
            if($user->user_type == \Config::get('constants.REGULAR_USER')) {
                $type = 'user';
            } else {
                $type = 'brand';
            }
            $row['user_id']     = $user->user_id;
            $row['displayname'] = $user->displayname;
            //$row['profile_pic'] = \Kinnect2::get_photo_path($user->photo_id);
            $row['profile_pic'] = \Kinnect2::getPhotoUrl($user->photo_id, $user->user_id, $type, 'thumb_icon');
            $row['user_type']   = $user->user_type;//($user->user_type == \Config::get('constants.REGULAR_USER')?'kinnector':'brand');
            $users_data[]       = $row;
        }

        return $users_data;
    }

    public function _get_user_info($users) {
        $users_data = [];
        $row        = [];
        foreach ($users as $user) {
            if($user->user_type == \Config::get('constants.REGULAR_USER')) {
                $type = 'user';
            } else {
                $type = 'brand';
            }
            $row['id']          = $user->id;
            $row['name']        = $user->displayname;
            $row['profile_pic'] = \Kinnect2::getPhotoUrl($user->photo_id, $user->user_id, $type, 'thumb_icon');

            $users_data[] = $row;
        }

        return $users_data;
    }

    public function get_conv_name($conv_id) {
        return Conversation::findOrFail($conv_id)->title;
    }

    public function get_conv_by_id($conv_id) {
        return Conversation::findOrFail($conv_id);
    }

    public function addMessageToConversation($conv_id, $user_id, $data) {

        //check if user of message is in conversation
        if(!$this->isUserInConversation($conv_id, $user_id))
            throw new UserNotInConvException;

        //if so add new message
        $message            = new Message();
        $message->sender_id = $user_id;
        $message->conv_id   = $conv_id;
        $message->content   = ($data['body'] == '' ? '' : $data['body']);
        $message->file_id   = ($data['file_id'] == '' ? NULL : $data['file_id']);

        $message->save();

        //get all users in conversation
        $usersInConv = $this->getUsersInConversation($conv_id);

        //and add msg status for each user in conversation
        $pushData = [];
        foreach ($usersInConv as $userInConv) {
            $messageStatus          = new MessageStatus();
            $messageStatus->user_id = $userInConv;
            $messageStatus->msg_id  = $message->id;
            if($userInConv == $user_id) {
                //its the sender user
                $messageStatus->self   = 1;
                $messageStatus->status = self::READ;
            } else {
                //other users in conv
                $messageStatus->self   = 0;
                $messageStatus->status = self::UNREAD;
                if(env('PUSH_ENABLED', FALSE)) {
                    $sender                              = User::where('id', $user_id)->select(['name'])->first();
                    $pushData[$userInConv]["title"]                   = $sender->name . " sent you a message";
                    $pushData[$userInConv]["data"]["conversation_id"] = $message->conv_id;
                    $pushData[$userInConv]["data"]["message_body"]    = $message->content;
                    $pushData[$userInConv]["data"]["module"]          = "messaging";
                    //SNS::sendPushNotification($userInConv, $pushData);
                }
            }
            $messageStatus->save();
        }

        return ['senderId' => $user_id, 'convUsersIds' => $usersInConv, 'content' => $data['body'], 'convId' => $conv_id, 'messageId' => $message->id, 'pushData' => $pushData];
    }

    /**
     * @param $conv_id
     *
     * @return array
     */
    public function getUsersInConversation($conv_id) {
        return ConversationUser::whereConvId($conv_id)->lists('user_id');
    }

    /**
     * @param $conv_id
     * @param $user_id
     *
     * @return bool
     */
    public function isUserInConversation($conv_id, $user_id) {
        $res = ConversationUser::whereConvId($conv_id)->whereUserId($user_id)->select('conv_id', 'user_id')->first();

        if(is_null($res))
            return FALSE;

        return TRUE;
    }

    /**
     * @param           $conv_id
     * @param           $user_id
     * @param bool|true $newToOld
     *
     * @return ConversationEntity
     */
    public function getConversationMessages($conv_id, $user_id, $newToOld = TRUE, $last_update_time = NULL) {
        if(is_null($last_update_time)) {
            $results = $this->_getConversationMessages($conv_id, $user_id, $newToOld);
        } else {
            $results = $this->_getConversationMessagesByTime($conv_id, $user_id, $newToOld, $last_update_time);
        }

        $conversation = new ConversationEntity();
        foreach ($results as $row) {
            $msg = new MessageEntity();
            $msg->setId($row->msgId);
            $msg->setContent($row->content);
            $msg->setCreated($row->created_at);
            $msg->setSender($row->userId);
            $msg->setStatus($row->status);
            $msg->setFile($row->file_id);

            $conversation->addMessage($msg);

        }
        $usersInConv = $this->getUsersInConversation($conv_id);
        foreach ($usersInConv as $userInConv)
            $conversation->addParticipant($userInConv);

        return $conversation;
    }

    /**
     * @param           $conv_id
     * @param           $user_id
     * @param bool|true $newToOld
     *
     * @return array
     */
    public function _getConversationMessages($conv_id, $user_id, $newToOld = TRUE) {
        if($newToOld)
            $orderBy = 'desc'; else
            $orderBy = 'asc';

        return \DB::select(/** @lang MySQL */
            'SELECT msg.id as "msgId", msg.file_id,msg.content, mst.status, msg.created_at, msg.sender_id as "userId"
            FROM messages_status mst
            INNER JOIN messages msg
            ON mst.msg_id=msg.id
            WHERE msg.conv_id=?
            AND mst.user_id = ?
            AND mst.status NOT IN (?,?)
            ORDER BY msg.created_at ' . $orderBy . '
            ', [$conv_id, $user_id, self::DELETED, self::ARCHIVED]);
    }

    public function saveTimeZone($TZ, $user_id) {
        $user           = User::find($user_id);
        $user->timezone = $TZ;
        $user->save();
        \Config::set('constants.USER_TIME_ZONE', $TZ);

        return TRUE;
    }

    public function getNumOfUnreadMsgsByConv($user_id, $conv_id) {
        $msgs_id = Message::whereIn('conv_id', $conv_id)->where('sender_id', '<>', $user_id)->lists('id')->toArray();
        $msgs_id = implode(',', $msgs_id);
        if(!empty($msgs_id)) {
            $unread  = [];
            $results = \DB::select('Select count(conv_id) as unread, `messages`.conv_id from messages_status,messages WHERE user_id=' . $user_id . ' and messages.id = messages_status.msg_id AND status=' . self::UNREAD . ' AND msg_id IN (' . $msgs_id . ' ) group by conv_id');
            foreach ($results as $row) {
                $unread[$row->conv_id] = $row->unread;
            }

            return $unread;
        }
    }

    public function getUserConversations($user_id) {
        $return        = [];
        $conversations = new Collection();

        $convs  = $this->getConversations($user_id);
        $convsI = [];
        foreach ($convs as $conv) {
            //this is for the query later
            $convsI[] = $conv->conv_id;
        }
        if(!empty($convsI)) {
            $unread = $this->getNumOfUnreadMsgsByConv($user_id, $convsI);
        }

        $convsIds = [];
        foreach ($convs as $conv) {
            //this is for the query later
            $convsIds[] = $conv->conv_id;

            //this is for the return result
            $conv->users            = [];
            $return[$conv->conv_id] = $conv;

            $conversation = new ConversationEntity();
            $conversation->setId($conv->conv_id);
            if(isset($unread[$conv->conv_id])) {
                $conversation->setUnreadCount($unread[$conv->conv_id]);
            }
            $message = new MessageEntity();
            $message->setId($conv->msgId);
            $message->setCreated($conv->created_at);
            $message->setContent($conv->content);
            $message->setStatus($conv->status);
            $message->setSelf($conv->self);
            $message->setSender($conv->userId);
            $message->setFile($conv->file_id);
            $conversation->addMessage($message);
            $conversations[$conversation->getId()] = $conversation;
        }

        $convsIds = implode(',', $convsIds);

        if($convsIds != '') {

            $usersInConvs = $this->getUsersInConvs($convsIds);

            foreach ($usersInConvs as $usersInConv) {
                if($user_id != $usersInConv->id) {
                    $user     = new \stdClass();
                    $user->id = $usersInConv->id;
                    //this is for the return result
                    $return[$usersInConv->conv_id]->users[$user->id] = $user;
                }
                $conversations[$usersInConv->conv_id]->addParticipant($usersInConv->id);
            }
        }

        return $conversations;
    }

    public function getConversations($user_id) {
        return \DB::select('
            SELECT msg.conv_id as conv_id, msg.created_at, msg.id "msgId", msg.file_id,msg.content, mst.status, mst.self, users.id "userId"
            FROM messages msg
            INNER JOIN (
                SELECT MAX(created_at) created_at
                FROM messages
                GROUP BY conv_id
            ) m2 ON msg.created_at = m2.created_at
            INNER JOIN messages_status mst ON msg.id=mst.msg_id
            INNER JOIN  users ON msg.sender_id=users.id
            WHERE mst.user_id = ? AND mst.status NOT IN (?, ?)
            ORDER BY msg.created_at DESC
            ', [$user_id, self::DELETED, self::ARCHIVED]);
    }
    public function getUnreadConversations($user_id) {

        return \DB::select('
            SELECT msg.conv_id as conv_id, msg.created_at, msg.id "msgId", msg.file_id,msg.content, mst.status, mst.self, users.id "userId"
            FROM messages msg
            INNER JOIN (
                SELECT MAX(created_at) created_at
                FROM messages
                GROUP BY conv_id
            ) m2 ON msg.created_at = m2.created_at
            INNER JOIN messages_status mst ON msg.id=mst.msg_id
            INNER JOIN  users ON msg.sender_id=users.id
            WHERE mst.user_id = ? AND mst.status NOT IN (?, ?,?)
            ORDER BY msg.created_at DESC
            ', [$user_id, self::DELETED, self::ARCHIVED, self::READ]);
    }
    public function getUsersInConvs($convsIds) {
        return \DB::select('
                SELECT cu.conv_id, users.id
                FROM conv_users cu
                INNER JOIN users
                ON cu.user_id=users.id
                WHERE cu.conv_id IN(' . $convsIds . ')
            ', []);
    }

    private function _getConversationMessagesByTime($conv_id, $user_id, $newToOld, $last_update_time) {
        if($newToOld)
            $orderBy = 'desc'; else
            $orderBy = 'asc';

        return \DB::select(/** @lang MySQL */
            'SELECT msg.id as "msgId", msg.file_id,msg.content, mst.status, msg.created_at, msg.sender_id as "userId"
            FROM messages_status mst
            INNER JOIN messages msg
            ON mst.msg_id=msg.id
            WHERE msg.conv_id=?
            AND mst.user_id = ?
            AND mst.status NOT IN (?,?)
            AND msg.created_at > ?
            ORDER BY msg.created_at ' . $orderBy . '
            ', [$conv_id, $user_id, self::DELETED, self::ARCHIVED, $last_update_time]);
    }

    public function change_status($conv_id, $status) {
        $conv         = Conversation::find($conv_id);
        $conv->status = $status;
        $conv->save();

        return TRUE;
    }

    public function is_conv_open($conv_id) {
        $conv = Conversation::find($conv_id);
        if(!$conv){
            return FALSE;
        }
        if($conv->conv_for == 'dispute' && $conv->status == 0) {
            return FALSE;
        }

        return TRUE;
    }

    public function message_counter($user_id) {
        //return Conversation::where
    }

    private function get_file_meta($file_data) {
        $placeholder = '';
        $extension = strtolower($file_data['extension']);
        if(in_array($extension, $this->getAllowedPhotoFiles()) || in_array($extension, $this->getAllowedVideoFiles())){
             $getThumb = $this->get_attachment_thumb($file_data['file_id']);
            if($getThumb){
                $placeholder =  \Kinnect2::get_attachment_thumb($getThumb->file_id);
            }

        }

        $data = array(
            'mime_type' => $file_data['mime_type'],
            'file_type' => $file_data['extension'],
            'size' => $file_data['size'],
            'placeholder' => $placeholder,
            'type' => $this->get_file_type($extension)
        );

        return $data;
    }

    public function get_attachment_thumb($file_id) {
      return $thumb= StorageFile::whereType('attachment_thumb')->whereParentFileId($file_id)->first();
    }
    public function get_thumb($file, $user_id) {
        $ffmpeg = \FFMpeg\FFMpeg::create( [
            'ffmpeg.binaries'  => env('FFMPEG_PATH','/usr/bin/ffmpeg'),
            'ffprobe.binaries' => env('FFPROBE_PATH','/usr/bin/ffprobe'),
            'timeout' => 3600,
            'ffmpeg.threads'  => 12,
        ]);
        $video = $ffmpeg->open( $file );
        $name = str_replace('.','_',time().uniqid(30,TRUE));
        $img = $name.'.jpg';

        $dir = storage_path('app' . DIRECTORY_SEPARATOR . 'attachments/thumbs/'.$user_id); //public_path('storage/attachments/thumbs/'.$user_id);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
            ///die("dsfdsfdasfdas");
        }


        $video
            ->frame( \FFMpeg\Coordinate\TimeCode::fromSeconds( 2 ) )
            ->save($dir."/".$img);

        $data["storage_path"] = $user_id."/".$img;
        $data["name"] = $img;
        $data['extension'] = "jpg";
        $data['mime_type'] = "image/jpeg";
        $data['size'] = "1111111111";
        $data['hash'] = "";

        return $data;
    }
    public function getNotAllowedFiles() {
        return $this->notAllowedFiles;
    }

    public function getAllowedPhotoFiles() {
        return $this->allowedPhotoFiles;
    }

    public function getAllowedVideoFiles() {
        return $this->allowedVideoFiles;
    }

    public function getAllowedAudioFiles() {
        return $this->allowedAudioFiles;
    }

    public function attachment_detail($file_id) {
        $data['file_id'] = '';
        if(!is_null($file_id)){
            $data['file_id'] = $file_id;
            $file_detail = $this->get_attachment($file_id);
            $data['file_type'] = $this->get_file_type(@$file_detail->extension);
        }
        return $data;
    }

    private function get_attachment($file_id) {
        return StorageFile::whereFileId($file_id)->first();
    }

    private function get_file_type($extension) {
        if(in_array($extension, $this->getAllowedPhotoFiles())){
            return 'image';
        }elseif(in_array($extension, $this->getAllowedVideoFiles())){
            return 'video';

        }elseif(in_array($extension,$this->getAllowedAudioFiles())){
            return 'audio';
        }else{
            return 'text';
        }
    }

    public function conversation_detail_notification($user_id) {
        $conversations = $this->getUnreadConversations($user_id);
        $all = [];
        foreach ($conversations as $conversation) {
            $all[] = $this->conv_detail($conversation);
        }
        return $all;
    }
    public function getUnreadMessagesConversation($conv_id) {

        return \DB::select('
            SELECT msg.conv_id as conv_id, msg.created_at, msg.id "msgId", msg.file_id,msg.content, mst.status, mst.self, users.id "userId"
            FROM messages msg
            INNER JOIN messages_status mst ON msg.id=mst.msg_id
            INNER JOIN  users ON msg.sender_id=users.id
            WHERE msg.conv_id = ? AND mst.status = ?
            ORDER BY msg.created_at DESC
            ', [$conv_id, self::UNREAD]);
    }

    private function conv_detail($conversation) {
        $data['id'] = $conversation->conv_id;
        $data['unread_messages'] = count($this->getUnreadMessagesConversation($conversation->conv_id));
        return $data;
    }

}
