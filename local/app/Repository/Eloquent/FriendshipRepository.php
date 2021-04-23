<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : local
 * Product Name : PhpStorm
 * Date         : 10-11-15 3:48 PM
 * File Name    : FriendshipRepository.php
 */

namespace App\Repository\Eloquent;

use App\AlbumPhoto;
use App\Event;
use App\Events\ActivityLog;
use App\Events\CreateNotification;
use App\Events\NotificationDelete;
use App\Facades\SNSFacade;
use App\Repository\RepositoryInterface;
use App\Friendship;
use App\StorageFile;
use App\User;
use Cache;
use App\Repository\Eloquent\NotificationRepository;
use Foo\Bar\B;

class FriendshipRepository extends Repository implements RepositoryInterface
{

    protected $friend;
    protected $userRepository;
    private   $activity_type;
    private   $notificationRepository;

    /**
     * FriendshipRepository constructor.
     * @param Friendship                                      $friend
     * @param UsersRepository                                 $usersRepository
     * @param \App\Repository\Eloquent\NotificationRepository $notificationRepository
     */
    public function __construct(Friendship $friend, UsersRepository $usersRepository, NotificationRepository $notificationRepository) {
        parent::__construct();

        $this->friend         = $friend;
        $this->userRepository = $usersRepository;
        $this->activity_type  = \Config::get('constants.ACTIVITY_TYPE_FRIENDSHIP');


        $this->notificationRepository = $notificationRepository;
    }

    public function all() {
        return $this->friend->all();
    }

    public function find($id) {
        return $this->friend->findOrNew($id);
    }

    public function saveOrUpdate($id = NULL) {

    }

    public function get_all_info($user_id, $skip_id = NULL,$return = '') {

        $this->data->requests        = $this->friend_requests($user_id);
        if($return == 'requests'){
            return (array)$this->data;
        }
        $this->data->all_recommended = self::all_recommended($user_id, $skip_id);
        $this->data->friends         = $this->userRepository->friends($user_id);
        $data                        = (array)$this->data;

        return $data;

    }

    /**
     * @param $user_id
     * @return array|static[]
     */
    public function friend_requests($user_id) {
        return \DB::table('user_membership')
            ->join('users', 'users.id', '=', 'user_membership.resource_id')
            ->where('user_id', $user_id)
            ->where('user_membership.resource_approved', 1)
            ->where('user_membership.active', 0)
            ->select('*')
            ->get();
    }

    public function friend_requests_notifications($user_id) {
        return \DB::table('user_membership')
            ->join('users', 'users.id', '=', 'user_membership.resource_id')
            ->where('user_id', $user_id)
            ->where('user_membership.resource_approved', 1)
            ->where('user_membership.active', 0)
            ->where('is_viewed', '0')
            ->count();
    }


    /**
     * @param $user_id
     * @param $skip_id
     *
     * @return mixed
     */
    public function all_recommended($user_id, $skip_id, $take = 10, $skip = 0) {

        $requests_id                 = self::sent_request_friends_id($user_id);
        $friend_list                 = self::get_friends_id($user_id);
        $received_friend_requests_id = self::received_friend_requests_id($user_id);
        $lists                       = array_merge($requests_id, $friend_list);
        $lists                       = array_merge($lists, $received_friend_requests_id);

        $this->data->all = User::where('id', '<>', $user_id)
            ->with('resource')
            ->whereNotIn('id', $lists)
            ->where('user_type', \Config::get('constants.REGULAR_USER'))
            // ->take($take)
            // ->skip($skip)
            ->orderByRaw('RAND()');
        if ($skip_id) {
            $this->data->all = $this->data->all->where('id', '<>', $skip_id);
        }
        if ($this->is_api) {
            return $this->data->all->get();
        } else {
            return $this->data->all->paginate(\Config::get('constants.PER_PAGE'));
        }

    }
    public function all_recommended_limit($user_id, $skip_id, $take = 10, $skip = 0) {

        $requests_id                 = self::sent_request_friends_id($user_id);
        $friend_list                 = self::get_friends_id($user_id);
        $received_friend_requests_id = self::received_friend_requests_id($user_id);
        $lists                       = array_merge($requests_id, $friend_list);
        $lists                       = array_merge($lists, $received_friend_requests_id);

        $this->data->all = User::where('id', '<>', $user_id)
                               ->with('resource')
                               ->whereNotIn('id', $lists)
                               ->where('user_type', \Config::get('constants.REGULAR_USER'))
             ->take($take)
             ->skip($skip)
                               ->orderByRaw('RAND()');
        if ($skip_id) {
            $this->data->all = $this->data->all->where('id', '<>', $skip_id);
        }
        if ($this->is_api) {
            return $this->data->all->get();
        } else {
            return $this->data->all->paginate(\Config::get('constants.PER_PAGE'));
        }

    }
    public function all_recommended_search($user_id, $key) {
        $requests_id                 = self::sent_request_friends_id($user_id);
        $friend_list                 = self::get_friends_id($user_id);
        $received_friend_requests_id = self::received_friend_requests_id($user_id);
        $lists                       = array_merge($requests_id, $friend_list);
        $lists                       = array_merge($lists, $received_friend_requests_id);

        $this->data->all = User::where('id', '<>', $user_id)
            ->with('resource')
            ->whereNotIn('id', $lists)
            ->where('user_type', \Config::get('constants.REGULAR_USER'))
            // ->take($take)
            // ->skip($skip)
            ->where('displayname', 'like', $key . '%')
            ->orderByRaw('RAND()');

        if ($this->is_api) {
            return $this->data->all->get();
        } else {
            $data = $this->data->all->paginate(\Config::get('constants.PER_PAGE'));
            $data->setPath('search');

            return $data;
        }

    }

    public function sent_request_friends_id($user_id) {
        return \DB::table('user_membership')
            ->join('users', 'users.id', '=', 'user_membership.user_id')
            ->where('resource_id', $user_id)
            ->where('user_membership.active', 0)
            ->where('user_membership.user_approved', 0)
            ->where('user_type', \Config::get('constants.REGULAR_USER'))
            ->select('users.id')
            ->lists('id');

    }

    public function get_friends_id($user_id) {
        $friends     = Friendship::whereResourceId($user_id)->whereActive(1)->whereResourceApproved(1)->select('user_id')->get()->toArray();
        $friend_list = array();
        foreach ($friends as $friend) {
            $friend_list[] = $friend['user_id'];
        }

        return $friend_list;
    }

    public function received_friend_requests_id($user_id) {
        return \DB::table('user_membership')
            ->join('users', 'users.id', '=', 'user_membership.resource_id')
            ->where('user_id', $user_id)
            ->where('user_membership.resource_approved', 1)
            ->where('user_membership.active', 0)
            ->select('*')
            ->lists('id');
    }

    public function add_friend($resource_id, $user_id) {
        $resource = new Friendship();
        // Save data for resource user
        $resource->resource_id       = $user_id;
        $resource->user_id           = $resource_id;
        $resource->resource_approved = 1;

        $resource->save();

        $user = new Friendship();
        // Save data for resource user
        $user->resource_id   = $resource_id;
        $user->user_id       = $user_id;
        $user->user_approved = 1;
        $user->save();


        $attributes = array(
            'resource_id' => $resource_id,
            'subject_id'  => $user_id,
            'object_id'   => $user_id,
            'object_type' => 'user',
            'type'        => \Config::get('constants_activity.notification.FRIEND_REQUEST'),
        );

        \Event::fire(new CreateNotification($attributes));

        Cache::forget('friendship-' . $user_id);
        Cache::forget('friends-' . $user_id);
        Cache::forget('friendship-' . $resource_id);


    }

    public function confirm($resource_id, $user_id) {
        $user = Friendship::whereUserId($user_id)->whereResourceId($resource_id)->update(
            ['active' => 1, 'user_approved' => 1]
        );


        $user = Friendship::whereUserId($resource_id)->whereResourceId($user_id)->update([
            'active'            => 1,
            'resource_approved' => 1,
        ]);


        $attributes = array(
            'resource_type' => 'user',
            'resource_id'   => $resource_id,
            'subject_id'    => $user_id,
            'subject_type'  => 'user',
            'object_id'     => $user_id,
            'object_type'   => \Config::get('constants_activity.OBJECT_TYPES.USER.NAME'),
            'type'          => \Config::get('constants_activity.notification.FRIEND_REQUEST_ACCEPTED'),
        );
        \Event::fire(new CreateNotification($attributes));

        $options = array(
            'type'         => $this->activity_type,
            'subject'      => $user_id,
            'subject_type' => 'user',
            'object'       => $resource_id,
            'object_type'  => \Config::get('constants_activity.OBJECT_TYPES.USER.NAME'),
            'body'         => '{item:$object} is now friends with {item:$subject}.',
        );

        \Event::fire(new ActivityLog($options));

        \Kinnect2::update_skore(\Config::get('constants_sKore.ADD_FRIEND'), $user_id);

        $pushData["title"]               = $this->data->user->displayname . " accepted your friend request";
        $pushData["data"]["sender_id"]   = $user_id;
        $pushData["data"]["sender_name"] = $this->data->user->displayname;
        $pushData["data"]["module"]      = "friendship_requests";
        \SNS::sendPushNotification($resource_id, $pushData);

    }

    public function unfollow($resource_id, $user_id) {
        $user                    = $user = Friendship::whereUserId($resource_id)->whereResourceId($user_id)->first();
        $user->resource_approved = 0;
        $user->save();

        $user                = Friendship::whereUserId($user_id)->whereResourceId($resource_id)->first();
        $user->user_approved = 0;
        $user->save();
        Cache::forget('friendship-' . $user_id);
        Cache::forget('friends-' . $user_id);
        Cache::forget('friendship-' . $resource_id);

        return redirect()->back();
    }

    public function follow($resource_id, $user_id) {
        $user = Friendship::whereUserId($resource_id)
            ->whereResourceId($user_id)
            ->update(
                [
                    'resource_approved' => 1,
                ]
            );
        $user = Friendship::whereUserId($user_id)
            ->whereResourceId($resource_id)
            ->update(
                [
                    'user_approved' => 1,
                ]
            );

        Cache::forget('friendship-' . $user_id);
        Cache::forget('friends-' . $user_id);
        Cache::forget('friendship-' . $resource_id);

    }

    public function destroy($resource_id, $user_id) {

        Friendship::whereUserId($resource_id)->whereResourceId($user_id)->delete();
        Friendship::whereUserId($user_id)->whereResourceId($resource_id)->delete();
        // $this->notificationRepository->delete_notification($resource_id, $user_id);
        $params = [
            'resource_id' => $resource_id,
            'subject_id'  => $user_id,
            'type'        => \Config::get('constants_activity.notification.FRIEND_REQUEST'),
        ];
        \Event::fire(new NotificationDelete($params));
        Cache::forget('friendship-' . $user_id);
        Cache::forget('friends-' . $user_id);
        Cache::forget('friendship-' . $resource_id);

    }

    public function is_friend($resource_id, $user_id) {

        return Friendship::whereResourceId($resource_id)
            ->whereUserId($user_id)
            ->whereResourceApproved(1)
            ->whereActive(1)
            ->first();

    }

    public function sent_friends_request($user_id) {
        $users = \DB::table('user_membership')
            ->join('users', 'users.id', '=', 'user_membership.user_id')
            ->where('resource_id', $user_id)
            ->where('user_membership.active', 0)
            ->where('user_membership.user_approved', 0)
            ->where('user_type', \Config::get('constants.REGULAR_USER'))
            ->select('*')
            ->get();
        if ($this->is_api) {
            return $users;
        }
        $this->data->sent_requests = $users;

        return (array)$this->data;

    }

    public function _get_user_meta($user) {
        //$photo    = AlbumPhoto::where( 'photo_id', $user->photo_id )->with('storage_file')->first();
        $user->profile_photo_url = '';
        $photo                   = AlbumPhoto::where('photo_id', $user->photo_id)
            ->select(['file_id'])
            ->first();
        $file_id                 = isset($photo->file_id) ? $photo->file_id : NULL;
        $file                    = StorageFile::where('file_id', $file_id)->first();
        $path                    = isset($file->storage_path) ? $file->storage_path : NULL;

        if (!empty($path)) {
            $user->profile_photo_url = \Config::get('constants_activity.PHOTO_URL') . $path . '?type=' . urlencode($file->mime_type);
        }
        $user->gender = \Kinnect2::get_gender($user);

        return $user;
    }

    public function updateNotification($user_id) {
        \DB::table('user_membership')
            ->where('user_id', $user_id)
            ->where('user_membership.resource_approved', 1)
            ->where('user_membership.active', 0)
            ->where('is_viewed', '0')
            ->update(['is_viewed' => '1']);
    }
}
