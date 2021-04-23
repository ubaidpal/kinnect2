<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : local
 * Product Name : PhpStorm
 * Date         : 11-12-15 4:41 PM
 * File Name    : PrivacyRepository.php
 */

namespace App\Repository\Eloquent;


use App\AuthorizationAllow;
use App\BrandMembership;
use App\EventMembership;
use App\Friendship;
use App\UserBrand;
use App\Usersetting;

/**
 * Class PrivacyRepository
 * @package App\Repository\Eloquent
 */
class PrivacyRepository extends Repository
{
    /**
     * @var UsersRepository
     */

    /**
     * @var FriendshipRepository
     */
    private $friendshipRepository;

    /**
     * PrivacyRepository constructor.
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * @return boolean
     */
    public function isIsApi()
    {
        return $this->is_api;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    public function is_allowed($resource_id, $resource_type, $action, $user_id, $owner_id)
    {
        if($user_id == $owner_id){
            return TRUE;
        }
        $authorization = AuthorizationAllow::whereResourceId($resource_id)
            ->whereResourceType($resource_type)
            ->whereAction($action)->first();

        if ($authorization) {
            $permission = $authorization->permission;
            return $this->check_permission($permission, $user_id, $owner_id, $resource_id);
        } else {
            return true;
        }


    }
    public  function has_permission($category,$setting,$user_id,$owner_id){

        if($user_id == $owner_id){
            return TRUE;
        }

        $setting = Usersetting::whereCategory($category)
                                    ->whereSetting($setting)
                                    ->whereUserId($owner_id)
                                    ->first();

        if(empty($setting->setting_id)){
            return FALSE;
        }

        return $this->check_permission($setting->setting_value,$user_id,$owner_id,$setting->setting_id);

    }

    private function check_permission($permission, $user_id, $owner_id, $resource_id)
    {

        switch ($permission) {
            case \Config::get('constants.PERM_EVERYONE'):
                return true;
                break;
            case \Config::get('constants.PERM_FRIENDS'):
                return $this->check_friend($user_id, $owner_id);
                break;
            case \Config::get('constants.PERM_FRIENDS_OF_FRIENDS'):
                return $this->check_friend_of_friends($user_id, $owner_id);
                break;
            case \Config::get('constants.PERM_PRIVATE'):
                return $this->check_owner($user_id, $owner_id);
                break;
            case \Config::get('constants.PERM_FRIENDS_AND_NETWORK'):
                return $this->check_friends_network($user_id, $owner_id);
                break;
            case \Config::get('constants.PERM_EVENT_MEMBERS'):
                return $this->check_event_member($user_id, $owner_id, $resource_id);
                break;

        }
    }

    public function check_friend($user_id, $owner_id)
    {

        //return 'hjksdfkjsdf';
        $friend = Friendship::whereResourceId($owner_id)
            ->whereUserId($user_id)
            ->whereResourceApproved(1)
            ->whereActive(1)
            ->first();

        /*$brand = BrandMembership::where(function ($query) use ($owner_id, $user_id) {
            $query->where('user_id', $owner_id)->where('brand_id', $user_id);
        })->orWhere(function ($query) use ($owner_id, $user_id) {
            $query->where('brand_id', $owner_id)->where('user_id', $user_id);
        })->first();*/
        if ($friend) {
            return true;
        } else {
            return false;
        }
    }

    private function check_friend_of_friends($user_id, $owner_id)
    {
        return false;
    }

    private function check_owner($user_id, $owner_id)
    {
        if ($user_id === $owner_id) {
            return true;
        } else {
            return false;
        }
    }

    private function check_friends_network($user_id, $owner_id)
    {
        return false;
    }

    private function check_event_member($user_id, $owner_id, $event_id)
    {
        $isMember = EventMembership::whereEventId($event_id)
            ->whereEventApproved(1)
            ->whereUserApproved(1)
            ->whereIn('rsvp', [1])
            ->whereUserId($user_id)
            ->first();
        if ($isMember) {
            return true;
        } else {
            return false;
        }
    }


}
