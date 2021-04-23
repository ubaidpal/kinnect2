<?php

namespace App\Http\Controllers;

use App\ActivityNotification;
use App\Repository\Eloquent\ActivityActionRepository;
use App\Repository\Eloquent\MessageRepository;
use App\Repository\Eloquent\NotificationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

class NotificationController extends Controller
{
    protected $data;
    private   $user_id;
    private   $notificationRepository;
    protected $is_api;

    /**
     * @param NotificationRepository $notificationRepository
     * @param Request $middleware
     */
    public function __construct(NotificationRepository $notificationRepository, Request $middleware) {
        $this->notificationRepository = $notificationRepository;

        $this->user_id = $middleware['middleware']['user_id'];
        @$this->data->user = $middleware['middleware']['user'];
        $this->is_api = $middleware['middleware']['is_api'];

    }

    public function mark_read() {

        $this->notificationRepository->mark_read($this->user_id);

        $data = ['resource_id' => $this->user_id];

        $notifications = $this->notificationRepository->get_notifications_detail($data);

        $strings = array();

        foreach ($notifications as $notification) {
            $strings[] = $this->notificationRepository->create_notification_string($notification);
        }
        if($this->is_api) {
            return \Api::success(['results' => $strings]);
        }

        return view('templates.partials.ajax.notification-string', ['strings' => $strings]);
    }

    public function mark_all_read() {

        $this->notificationRepository->mark_read($this->user_id);
        $msgRepo = new MessageRepository();
        $this->notificationRepository->updateFriendShipNotification($this->user_id);
        $convs = $msgRepo->getUnreadConversations($this->user_id);
        if($convs) {
            foreach ($convs as $conv) {
                \TBMsg::markReadAllMessagesInConversation($conv->conv_id, $this->user_id);
            }
        }

        return \Api::success_with_message();

    }

    public function showNotification(Request $request) {
        $agent = new Agent();
        if($agent->isMobile() || $agent->isTablet()) {
            $this->notificationRepository->mark_read($this->user_id);
        }
        $next_page = $request->page;

        $data = ['resource_id' => $this->user_id];

        $notifications = $this->notificationRepository->get_notifications_detail($data);

        $allStrings = array();
        foreach ($notifications as $notification) {
            $allStrings[] = $this->notificationRepository->create_notification_string($notification);
        }

        /* if($next_page > 1 and !$this->is_api){
             return ['notifications' => $allStrings];
         }*/

        if($this->is_api) {
            return ['notifications' => $allStrings];
        }

        return view("notification.notificationDetail", ['allStrings' => $allStrings, 'all' => $notifications])->with('page_Title', 'Notifications');

    }

    public function showMoreNotification(Request $request) {//Show all notification on click view More
        $agent = new Agent();
        if($agent->isMobile() || $agent->isTablet()) {
            $this->notificationRepository->mark_read($this->user_id);
        }
        $data          = ['resource_id' => $this->user_id, 'page' => $request->page];
        $notifications = $this->notificationRepository->get_notifications_detail($data);

        $is_end = 0;
        if(!$notifications->hasMorePages()) {
            $is_end = 1;
        }
        $allStrings = array();

        foreach ($notifications as $notification) {
            $allStrings[] = $this->notificationRepository->create_notification_string($notification);
        }
        $page_id = $request->page + 1;

        $contentHtml = '<span style="display:none;" id="next_page_id">' . $page_id . '</span>';
        $contentHtml .= '<span style="display:none;" id="is_end">' . $is_end . '</span>';

        if($allStrings) {
            foreach ($allStrings as $allString) {
                $url = url('goto/' . $allString['notification_id'] . '/?redirect-uri=' . base64_encode($allString['url']));
                /* if ($allString['is-read'] == 1) {
                     $class = 'notifications_read';
                 } else {
                     $class = 'notifications_unread';
                 }*/
                $contentHtml .= '<li class=" notifications_unread" value="175">
                <!--<span class="notification_subject_photo">
                    <img src="http://localhost/kinnect2/local/public/images/leaderboard-tabs-content.jpg">
                </span>-->
                <span class="notification_item_general notification_type_friend_accepted">
                 <a href="' . $url . '">
                  <span class="item" id="rowdata">' . $allString['string'] . ' </span>
                  </a>
                </span>
            </li>';
            }
        } else {
            return '<li class="notifications_unread" id="no-more" value="1">No More Notifications</li>';
        }

        return $contentHtml;
    }

    public function mark_clicked(Request $request, $id) {
        $notification          = ActivityNotification::findOrNew($id);
        if($notification->type == 'video_processed' || $notification->type == 'audio_processed') {
            $notification->delete();
        }else{
            $notification->clicked = 1;
            $notification->save();
        }

        return redirect(base64_decode($request['redirect-uri']));
    }

    public function mark_all_clicked() {
        $this->notificationRepository->mark_all_read($this->user_id);

        return redirect()->back();
    }

    public function update_notification() {

        $count          = get_notification_count($this->user_id);
        $unread_message = \TBMsg::getNumOfUnreadMsgs($this->user_id);
        $friend_request = get_friend_request_noti($this->user_id);
        $repeat = \Input::get('repeat');

        if($this->is_api) {
            $msgRepo = new MessageRepository();

            return \Api::success(['count' => $count, 'messages' => count($msgRepo->getUnreadConversations($this->user_id)), 'friend_requests' => $friend_request]);
        } else {
            $messages       = 0;
            $is_get_message = \Input::get('conv_id');
            if($is_get_message != 0) {
                $conv_id      = \Input::get('conv_id');
                $last_time = \Input::get('last_update_time');
                $message_repo = new MessageRepository();

                if(!empty($last_time)) {
                    $last_time = date('Y-m-d H:i:s',strtotime($last_time));
                    $data = $message_repo->get_messages($this->user_id, $conv_id, $last_time);
                }else {
                    $data = $message_repo->get_messages($this->user_id, $conv_id);
                }
                $data['repeat'] = $repeat;
                $messages = view('messages.conversation-messages', $data)->render();
            }

            /*header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            echo "data:id:" . $count . " \n";
            echo "data: msg:" . $unread_message . " \n\n";
            echo "retry: 100\n";
            flush();*/
            
            $last_update_time = Carbon::now()->toDateTimeString();
            return [
                    'count' => $count,
                    'messages_content' => $messages,
                    'unread_message' => $unread_message,
                    'friend_request' => $friend_request,
                    'last_update_time' => $last_update_time,
                    'repeat'           => $repeat
            ];
        }
    }

    public function notification_detail() {
        $unread_message = \TBMsg::getNumOfUnreadMsgs($this->user_id);
        $friend_request = get_friend_request_noti($this->user_id);

        $count         = $unread_message + $friend_request;
        $msgRepo       = new MessageRepository();
        $conversations = $msgRepo->conversation_detail_notification($this->user_id);
        $friends       = \Kinnect2::friend_requests($this->user_id);
        $requests      = [];
        if(!empty($friends)) {
            foreach ($friends as $friend) {
                $requests[] = $friend->resource_id;
            }
        }

        $data = [
            'data'                  => $count,
            'unread_messages'       => $unread_message,
            'friend_requests_count' => $friend_request,
            'conversations'         => $conversations,
            'friend_requests'       => $requests
        ];
        return \Api::success($data);
    }
}
