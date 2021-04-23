<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : local
 * Product Name : PhpStorm
 * Date         : 25-11-15 6:52 PM
 * File Name    : NotificationRepository.php
 */

namespace App\Repository\Eloquent;

use App\ActivityAction;
use App\ActivityNotification;
use App\BattleOption;
use App\Events\SendEmail;
use App\Facades\Kinnect2;
use App\UserBrand;
use Config;
use App\User;

class NotificationRepository extends Repository
{

    protected $activityNotification;
    protected $userRepository;
    private   $activity_type;

    /**
     * @param ActivityNotification $activityNotification
     * @param UsersRepository      $usersRepository
     *
     * @internal param Friendship $friend
     * @internal param Friendship $poll
     */
    public function __construct(ActivityNotification $activityNotification, UsersRepository $usersRepository)
    {
        parent::__construct();
        $this->activityNotification = $activityNotification;
        $this->userRepository = $usersRepository;
        $this->activity_type = \Config::get('constants.ACTIVITY_TYPE_FRIENDSHIP');
    }

    /**
     * @param $user_id
     */
    public function mark_read($user_id)
    {
        $data = ['resource_id' => $user_id, 'count' => 0];
        $notifications = $this->get_notifications($data);
        foreach ($notifications as $row) {
            $notification = $this->activityNotification->findOrNew($row->id);

            $notification->read = 1;
            $notification->save();
        }
    }

    public function get_notifications($data)
    {
        $attributes = array(
            'read'  => 0,
            'count' => 0,
        );
        $data = array_merge($attributes, $data);

        if ($data['count'] == 1) {
            $resource_id = $data['resource_id'];
            return $this->activityNotification
                ->whereResourceId($data['resource_id'])
                ->where(function($query) use ($resource_id){
                    $query->where('subject_id', '<>', $resource_id);
                    $query->orWhere('type','LIKE','video_processed');
                    $query->orWhere('type','LIKE','audio_processed');
                })
                ->where('type', '<>', 'friend_request')
                ->whereRead($data['read'])
                ->count();
        } else {
            return $this->activityNotification
                ->select('id')
                ->whereResourceId($data['resource_id'])
                ->orderBy('id', 'DESC')
                ->where('type', '<>', 'friend_request')
                ->whereRead($data['read'])
                ->paginate(15);

        }
    }

    public function get_notifications_detail($data)
    {
        $attributes = array(
            'read'  => 0,
            'count' => 0,
        );

        $data = array_merge($attributes, $data);
        $limit = 28;
        if (\Input::has('notification')) {
            $limit = 10;
        }
        $resource_id = $data['resource_id'];
        return $this->activityNotification
            ->whereResourceId($data['resource_id'])
            ->where(function($query) use ($resource_id){
                $query->where('subject_id', '<>', $resource_id);
                $query->orWhere('type','LIKE','video_processed');
                $query->orWhere('type','LIKE','audio_processed');
                $query->orWhere('type','LIKE','activity_share');
            })
            ->where('type', '<>', 'friend_request')
            ->orderBy('id', 'DESC')
            ->paginate($limit);
    }

    /**
     * @param array $data
     */
    public function create_notification(array $data)
    {
        $attributes = array(
            'resource_type' => 'user',
            'resource_id'   => NULL,
            'subject_id'    => NULL,
            'subject_type'  => 'user',
            'object_id'     => NULL,
            'object_type'   => NULL,
            'type'          => NULL,
        );

        $result = array_merge($attributes, $data);

        $note = ActivityNotification::create($result);

        $string = $this->create_notification_string($note);

        $photo = \Kinnect2::getPhotoUrl($string['photo_id'],$string['resource_id'],'user', 'thumb_icon');
        $path_info = pathinfo($photo);
        $extension = $path_info['extension'];
        if(strtolower($extension) == 'svg'){
            $photo =  substr($photo, 0, -3);
            $photo = $photo.'png';
        }
        $attributes = [
            'to' => $string['email'],
            'template' => 'notification',
            'from' => env('MAIL_FROM_EMAIL','no-reply@qa.kinnect2.com'),
            'name' => env('MAIL_FROM_NAME','Kinnect2'),
            'from_name' => strip_tags($string['name']),
            'subject' => strip_tags($string['string']),
            'photo' => url($photo),
            'string' =>  $string
        ];
        \Event::fire(new SendEmail($attributes));
    }

    /**
     * @param $data
     */
    public function group_notification($data)
    {

        $group_id = $data['object_id'];
        $subject_id = $data['subject_id'];


        $friends = \DB::table('user_membership')
            ->where('user_membership.active', 1)
            ->where('user_membership.resource_id', $subject_id)
            ->join('group_membership', 'group_membership.user_id', '=', 'user_membership.user_id')
            ->where('group_membership.group_id', $group_id)
            ->get();

        foreach ($friends as $friend) {
            $data = array(
                'resource_id' => $friend->user_id,
                'subject_id'  => $subject_id,
                'object_id'   => $group_id,
                'object_type' => \Config::get('constants_activity.OBJECT_TYPES.GROUP.NAME'),
                'type'        => \Config::get('constants_activity.notification.GROUP_POST'),
            );
            self::create_notification($data);
        }
    }

    public function delete_notification($resource_id, $subject_id)
    {
        $this->activityNotification->whereResourceId($resource_id)->whereSubjectId($subject_id)->whereRead(0)
            ->whereType(\Config::get('constants_activity.notification.FRIEND_REQUEST'))->delete();
    }


    /**
     * @param $data
     *
     * @return mixed
     */
    public function create_notification_string($data)
    {
        switch ($data->object_type) {
            case Config::get('constants_activity.OBJECT_TYPES.GROUP.NAME'):

                $group = \App\Group::findOrNew($data->object_id);

                $group_name = $group->title;
                $url = url('group/' . $group->id);
                if ($this->is_api) {
                    $object = $group_name;
                    //$object->name = $group_name;
                    //$object->type = Config::get('constants_activity.OBJECT_TYPES.GROUP');
                    //$object->id = "group";
                } else {
                    //$object = $this->generate_url( $url, $group_name );
                    $object = $group_name;
                }
                $params = [
                    'url'    => $url,
                    'object' => $object,
                ];

                return $this->string($data, $params);
                break;
            case Config::get('constants_activity.OBJECT_TYPES.USER.NAME'):
                $params = [
                    'url'    => NULL,
                    'object' => NULL,
                ];

                return $this->string($data, $params);
                break;
            case 'brand':
                $user = User::whereId($data->object_id)->with('brand_detail')->first();

                $params = [
                    'url'    => \Kinnect2::profileAddress($user),
                    'object' => $user->brand_detail->brand_name,
                ];

                return $this->string($data, $params);
                break;
            case Config::get('constants_activity.OBJECT_TYPES.ACTIVITY_ACTION'):
                $url = url('postDetail/' . $data->object_id);
                $params = [
                    'url'    => $url,
                    'object' => NULL,
                ];
                if($data->type == Config::get( 'constants_activity.notification.OBJECT_TYPE.TYPES.SHARE' )){
                    $activity = ActivityAction::find($data->object_id);
                    $params['object'] = $activity->object_type;
                }
                return $this->string($data, $params);
                break;
            case Config::get('constants_activity.OBJECT_TYPES.ACTIVITY_COMMENT'):
                $data = $this->get_comment_detail($data->type, $data->object_type, $data->object_id);

                return $this->string($data->type, $data->resource_id);
                break;
            case Config::get('constants_activity.OBJECT_TYPES.EVENT.NAME'):
                $event = \App\Event::findOrNew($data->object_id);

                $event_name = $event->title;
                $url = url('event/' . $event->id);
                if ($this->is_api) {
                    $object = $event_name;
                    //$object->name = $group_name;
                    //$object->type = Config::get('constants_activity.OBJECT_TYPES.GROUP');
                    //$object->id = "group";
                } else {
                    //$object = $this->generate_url( $url, $group_name );
                    $object = $event_name;
                }
                $params = [
                    'url'    => $url,
                    'object' => $object,
                ];

                return $this->string($data, $params);
                break;
            default:
                $params = [
                    'url'    => NULL,
                    'object' => NULL,
                ];

                return $this->string($data, $params);
                break;
        }
    }

    public function string($data, $params)
    {
        $resource = User::findOrNew($data['subject_id']);
        //if ($resource->user_type == Config::get('constants.REGULAR_USER')) {
        if (!$this->is_api) {
            $subject = "<strong>$resource->displayname</strong>";
        }else{
            $subject = $resource->displayname;
        }
       // } else {
           // $subject = $resource->brand_detail->brand_name;
       // }


        if ($data->object_type == Config::get('constants_activity.OBJECT_TYPES.USER.NAME')) {
            $params['url'] = Kinnect2::profileAddress($resource);
        }
        $string = Config::get('constants_activity.notification_messages.' . $data->type);
        if ($this->is_api) {
            $string = str_replace('$subject', '', $string);
            //$string = str_replace('$object', '', $string);
        } else {

            if (!empty($params['object'])) {
                $string = str_replace('$object', $params['object'], $string);
            }
            $string = str_replace('$subject', $subject, $string);
        }

        if($data->type == Config::get('constants_activity.notification.BATTLE_CREATE_TAG')){
            $brands = BattleOption::where('battle_id',$data->object_id)
                                    ->orderBy('id','ASC')
                                    ->lists('brand_id','brand_id');
            $counter = 1;
            foreach ($brands as $index => $brand_id){
                $brand_name =  UserBrand::where('id',$brand_id)->value('brand_name');
                $string = str_replace('$brand_'.$counter, $brand_name, $string);
                $counter++;
            }
            $params['url'] = url('/view/battle/'.$data->object_id);
        }
        if ($data->object_type == 'user' OR $data->object_type == 'brand') {
            $type = 'user';
        } else {
            $type = $data->object_type;
        }
        if ($this->is_api) {
            return [
                'body'            => $string,
                //'url'             => $params['url'],
                'notification_id' => $data->id,
                'is-read'         => $data->clicked,
                'action_type'     => $data->type,
                'date'     => $data->created_at,
                'subject'         => [
                    'id'   => $data->subject_id,
                    'name' => $subject,
                ],
                'object'          => [
                    'id'          => $data->object_id,
                    'name'        => ($params['object'] ? $params['object'] : ''),
                    'object_type' => $data->object_type,
                ],
            ];
        }
        $user = User::where('id',$data->resource_id)->select(['id','first_name','name','last_name','email','displayname','photo_id'])->first();
        return [
            'string'          => $string,
            'email'           => $user->email,
            'photo_id'        => $resource->photo_id,
            'resource_id'     => $resource->id,
            'name'            => $user->displayname,
            'url'             => $params['url'],
            'notification_id' => $data->id,
            'is-read'         => $data->clicked,
            'date'            => $data->created_at
        ];
    }


    public function generate_url($url, $name)
    {

        return $string = "<a href='$url'>$name</a>";

    }

    public function get_comment_detail($type, $object, $object_id)
    {
        return \DB::table('users')
            ->join($object . "s", $object . '.resource_id', '=', 'users.id')
            ->where($object . '.comment_id', $object_id)
            ->get();
    }


    public function mark_all_read($user_id)
    {
        $this->activityNotification
            ->whereResourceId($user_id)
            ->update(['clicked' => 1]);
    }

    public function updateFriendShipNotification($user_id) {
        \DB::table('user_membership')
           ->where('user_id', $user_id)
           ->where('user_membership.resource_approved', 1)
           ->where('user_membership.active', 0)
           ->where('is_viewed', '0')
           ->update(['is_viewed' => '1']);
    }
}
