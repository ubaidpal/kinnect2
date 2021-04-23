<?php
/**
 * Created by PhpStorm.
 * User: n0impossible
 * Date: 6/13/15
 * Time: 11:35 AM
 */

namespace App\Classes;

use App\AdCampaign;
use App\AdStatistics;
use App\AdTargets;
use App\AdUserAd;
use App\AlbumPhoto;
use App\AuthorizationAllow;
use App\Battle;
use App\Consumer;
use App\EventMembership;
use App\Group;
use App\Friendship;
use App\GroupMembership;
use App\Poll;
use App\Repository\Eloquent\ActivityActionRepository;
use App\Repository\Eloquent\SkoreRepository;
use App\Repository\Eloquent\UsersRepository;
use App\Services\StorageManager;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use App\StorageFile;


class Kinnect2
{

    protected $data;
    protected $is_api;
    private   $user_id;

    public function __construct() {
        $this->is_api = UrlFilter::filter();
        if($this->is_api) {
            $this->user_id = Authorizer::getResourceOwnerId();
            @$this->data->user = User::findOrNew($this->user_id);
        } else {
            if(Auth::check()) {
                @$this->data->user = Auth::user();
                $this->user_id = $this->data->user->id;
            }
        }
    }

    public static function isFollowingGroup($group_id, $user_id) {
        return DB::table('group_membership')
                 ->where('group_id', $group_id)
                 ->where('user_id', $user_id)
                 ->where('user_approved', 1)
                 ->where('group_owner_approved', 1)
                 ->count();
    }

    public static function isFollowingEvent($group_id, $user_id) {
        return DB::table('event_memberships')
                 ->where('event_id', $group_id)
                 ->where('user_id', $user_id)
                 ->where('user_approved', 1)
                 ->where('event_approved', 1)
                 ->count();
    }

    public function getCampaign($campaign_id) {
        return AdCampaign::find($campaign_id);
    }

    public function getAd($ad_id) {
        return AdUserAd::find($ad_id);
    }

    public function getCountryName($id) {
        return $userFollowingBrandIds = DB::table('countries')
                                          ->where('id', $id)
                                          ->pluck('name');
    }

    public function getCampaignStatistics($created_at, $campaign_id) {
        $date                       = explode(' ', $created_at);
        $stats                      = AdStatistics::where('ad_campaign_id', $campaign_id)
                                                  ->join('users', 'users.id', '=', 'ad_statistics.viewer_id')
                                                  ->groupBy('date')
                                                  ->orderBy('date', 'DESC')
                                                  ->whereBetween('ad_statistics.created_at', array(
                                                      $date[0] . ' 00:00:00',
                                                      $date[0] . ' 23:59:59',
                                                  ))
                                                  ->get(array(
                                                      DB::raw('ad_statistics.created_at as date, ad_statistics.value_click, ad_statistics.value_view, users.country as country'),
                                                  ));
        $data['camp_total_clicks']  = '0';
        $data['camp_total_views']   = '0';
        $data['view_country_list']  = '';
        $data['click_country_list'] = '';

        foreach ($stats as $stat):
            if($stat->value_click == 1):
                $data['camp_total_clicks']++;
                $data['click_country_list'] .= $stat->country . ', ';
            endif;

            if($stat->value_view == 1):
                $data['camp_total_views']++;
                $data['view_country_list'] .= $stat->country . ', ';
            endif;
        endforeach;

        return $data;
    }

    public function profileAddress($ownerInfo) {

        if(isset($ownerInfo->user_type)) {
            if($ownerInfo->user_type == \Config::get('constants.BRAND_USER')) {
                return $profileUrl = url('brand' . '/' . urlencode($ownerInfo->username));
            } else {
                return $profileUrl = url('profile' . '/' . urlencode($ownerInfo->username));
            }
        }

        return $profileUrl = url('/');

    }

    public function get($data = []) {
        echo "foo";
    }

    public function myBrands($take = NULL, $is_sidebar = FALSE) {

        if(is_null($take)) {
            $take = 6;
        }
        $userFollowingBrandIds = DB::table('brand_memberships')
                                   ->where('user_approved', 1)
                                   ->where('brand_approved', 1)
                                   ->where('user_id', $this->user_id)
                                   ->orderBy('id','DESC')
                                   ->lists('brand_id');

        if(count($userFollowingBrandIds) > 0) {
            $brands = User::where('user_type', \Config::get('constants.BRAND_USER'))
                          ->with('brand_detail')
                          ->whereIn('id', $userFollowingBrandIds)
                          ->orderByRaw("RAND()");

            if(!$this->is_api || $is_sidebar) {
                $brands = $brands->where('photo_id', '>', 0)->take($take);
            }

            $data = $brands = $brands->get();

            if($brands->count() < 6) {
                $includedBrandIds = [];

                foreach ($brands as $brand) {
                    array_push($includedBrandIds, $brand->id);
                }

                $remainingBrands = User::where('user_type', \Config::get('constants.BRAND_USER'))
                                       ->whereIn('id', $userFollowingBrandIds)
                                       ->whereNotIn('id', $includedBrandIds)
                                       ->take($take - count($brands))->get();

                if(count($remainingBrands) > 0) {
                    $data = array_merge($brands->toArray(), $remainingBrands->toArray());
                }
            }

            if(!$this->is_api || $is_sidebar) {
                $total = count($data);
                if($total < $take) {

                }
            }

            return $data;

        }

        return FALSE;
    }

    public function recomendedBrands($take = NULL, $is_sidebar = FALSE) {
        if(is_null($take)) {
            $take = 6;
        }
        $userFollowingBrandIds = DB::table('brand_memberships')
                                   ->where('user_approved', 1)
                                   ->where('brand_approved', 1)
                                   ->where('user_id', $this->user_id)
                                   ->lists('brand_id');

        $brands = User::where('user_type', \Config::get('constants.BRAND_USER'))
                      ->whereNotIn('id', $userFollowingBrandIds)
                      ->with('brand_detail');

        if(!$this->is_api || $is_sidebar) {
            $brands = $brands->where('photo_id', '!=', 0)->take($take);
        }

        $brands = $brands->get();

        if($brands->count() < 6) {
            $includedBrandIds = [];

            foreach ($brands as $brand) {
                array_push($includedBrandIds, $brand->id);
            }

            $remainingBrands = User::where('user_type', \Config::get('constants.BRAND_USER'))
                                   ->whereNotIn('id', $userFollowingBrandIds)
                                   ->whereNotIn('id', $includedBrandIds)
                                   ->take($take - count($brands))->get();

            if(count($remainingBrands) > 0) {
                $brands = array_merge($brands->toArray(), $remainingBrands->toArray());
            }
        }

        return $brands;
    }

    public function myAllBrands($user_id = NULL) {
        if(is_null($user_id)) {
            $user_id = $this->user_id;
        }
        $userFollowingBrandIds = DB::table('brand_memberships')
                                   ->where('user_id', $user_id)
                                   ->where('user_approved', 1)
                                   ->where('brand_approved', 1)
                                   ->orderBy('id','DESC')
                                   ->lists('brand_id');

        $brands = User::where('user_type', \Config::get('constants.BRAND_USER'))
                      ->whereIn('id', $userFollowingBrandIds)
                      ->with('brand_detail')
                      ->orderBy('displayname', 'ASC')
                      ->paginate(\Config::get('constants.PER_PAGE'));

        //$brands->setPath('brands');
        return $brands;
    }//followers($group_id)

    public function myTenBrands($user_id = NULL) {
        if(is_null($user_id)) {
            $user_id = $this->user_id;
        }
        $userFollowingBrandIds = DB::table('brand_memberships')
                                   ->where('user_id', $user_id)
                                   ->where('user_approved', 1)
                                   ->where('brand_approved', 1)
                                   ->lists('brand_id');

        $brands = User::where('user_type', \Config::get('constants.BRAND_USER'))
                      ->whereIn('id', $userFollowingBrandIds)
                      ->with('brand_detail')
                      ->orderBy('displayname', 'ASC')
                      ->take(10)
                      ->paginate(\Config::get('constants.PER_PAGE'));

        //$brands->setPath('brands');
        return $brands;
    }//followers($group_id)

    public function myAllBrandsCount($user_id = NULL) {
        if(is_null($user_id)) {
            $user_id = $this->user_id;
        }
        $userFollowingBrandIds = DB::table('brand_memberships')
                                   ->where('user_id', $user_id)
                                   ->where('user_approved', 1)
                                   ->where('brand_approved', 1)
                                   ->orderBy('id','DESC')
                                   ->lists('brand_id');

        $brands = User::where('user_type', \Config::get('constants.BRAND_USER'))
                      ->whereIn('id', $userFollowingBrandIds)
                      ->with('brand_detail')
                      ->orderByRaw("RAND()")
                      ->count();

        //$brands->setPath('brands');
        return $brands;
    }//followers($group_id)

    public function recomendedAllBrands() {
        $userFollowingBrandIds = $this->get_follower($this->user_id);

        $brands = User::where('user_type', \Config::get('constants.BRAND_USER'))
                      ->whereNotIn('id', $userFollowingBrandIds)
                      ->with('brand_detail')
                      ->whereNotIn('id', [$this->user_id])
                      ->orderByRaw("RAND()")
                      ->paginate(\Config::get('constants.PER_PAGE'));

        $brands->setPath('brands');

        //echo '<tt><pre>'; print_r($brands); die;
        return $brands;
    }

    public function recommendedAllBrandsSearch($key) {
        $userFollowingBrandIds = $this->get_follower($this->user_id);

        $brands = User::where('user_type', \Config::get('constants.BRAND_USER'))
                      ->whereNotIn('id', $userFollowingBrandIds)
                      ->with('brand_detail')
                      ->where('displayname', 'like', $key . '%')
                      ->whereNotIn('id', [$this->user_id])
                      ->orderByRaw("RAND()")
                      ->paginate(\Config::get('constants.PER_PAGE'));

        $brands->setPath('search');

        //echo '<tt><pre>'; print_r($brands); die;
        return $brands;
    }

    public function allBrands() {
        $brands = User::where('search', 1)->where('deleted', 0)->where('user_type', \Config::get('constants.BRAND_USER'))
                      ->with('brand_detail')
                      ->orderByRaw("RAND()")
                      ->take(100)->get();

        return $brands;
    }

    public function get_follower($user_id) {
        return $userFollowingBrandIds = DB::table('brand_memberships')
                                          ->where('user_id', $user_id)
                                          ->where('user_approved', 1)
                                          ->where('brand_approved', 1)
                                          ->orderBy('id','DESC')
                                          ->lists('brand_id');
    }

    public function followers($group_id) {
        if($group_id) {
            return DB::table('group_membership')
                     ->where('group_id', $group_id)
                     ->where('user_approved', 1)
                     ->where('group_owner_approved', 1)
                     ->count();
        }

        return 0;
    }

    public function FriendsFollowers($group_id, $user_id) {
        $knownMembers = array();
        $members      = DB::table('group_membership')
                          ->where('group_id', $group_id)
                          ->where('user_id', '!=', $user_id)
                          ->where('user_approved', 1)
                          ->where('group_owner_approved', 1)
                          ->get();
        foreach ($members as $member) {
            $check = DB::table('user_membership')
                       ->where('resource_id', $user_id)
                       ->where('user_id', $member->user_id)
                       ->where('user_approved', 1)
                       ->where('resource_approved', 1)
                       ->first();
            if($check != NULL) {
                $knownMembers[] = $check;
            }
        }

        return $knownMembers;
    }

    public function brand_kinnectors($brand_id) {
        if($brand_id) {
            return DB::table('brand_memberships')
                     ->where('brand_id', $brand_id)
                     ->where('user_approved', 1)
                     ->where('brand_approved', 1)
                     ->count();

        }

        return 0;
    }

    public function myGroups($take = NULL) {
        if(is_null($take)) {
            $take = 6;
        }
        $userFollowingGroupIds = DB::table('group_membership')
                                   ->where('user_approved', 1)
                                   ->where('group_owner_approved', 1)
                                   ->where('user_id', $this->user_id)
                                   ->lists('group_id');

        return $brands = Group::whereIn('id', $userFollowingGroupIds)
                              ->orderByRaw("RAND()")
                              ->take($take)->get();

        return FALSE;
    }

    public function recomendedGroups($take = NULL) {
        if(is_null($take)) {
            $take = 6;
        }
        $userFollowingGroupIds = DB::table('group_membership')
                                   ->where('user_approved', 1)
                                   ->where('group_owner_approved', 1)
                                   ->where('user_id', $this->user_id)
                                   ->lists('group_id');

        return $brands = Group::join('users', 'users.id', '=', 'groups.creator_id')->select('groups.creator_id', 'groups.id', 'groups.title', 'groups.photo_id')->whereNotIn('groups.id', $userFollowingGroupIds)
                              ->orderByRaw("RAND()")
                              ->take($take)->get();

        return FALSE;
    }

    public function myAllGroups($userId = NULL,$query = []) {
        if(!$this->is_api && empty($userId)) {
            $userId = Auth::user()->id;
        }

        $userFollowingGroupIds = DB::table('group_membership')
                                   ->where('user_approved', 1)
                                   ->where('group_owner_approved', 1)
                                   ->where('user_id', $userId)
                                   ->lists('group_id');

        $queryObj = Group::whereIn('id', $userFollowingGroupIds)
                              ->orderBy("id", 'DESC')
                              ->take(30);

        if(!empty($query['title'])){
            $queryObj->where('title','LIKE',"%{$query['title']}%");
        }

        return $queryObj->get();

        $brands->setPath('groups');

        return $groups;
    }

    public function recomendedAllGroups($userId = NULL,$query = []) {
        if(!$this->is_api && empty($userId)) {
            $userId = Auth::user()->id;
        }
        $userFollowingGroupIds = DB::table('group_membership')
                                   ->where('user_approved', 1)
                                   ->where('group_owner_approved', 1)
                                   ->where('user_id', $userId)
                                   ->lists('group_id');

        $queryObj = Group::whereNotIn('id', $userFollowingGroupIds)
                              ->orderByRaw("RAND()")
                              ->take(30);
        if(!empty($query['title'])){
            $queryObj->where('title','LIKE',"%{$query['title']}%");
        }
        return $queryObj->get();

        $brands->setPath('groups');

        return $groups;
    }

    public function groupOwner($groupOwnerId) {
        $groupOwner = User::find($groupOwnerId);
        if(isset($groupOwner)) {
            return $groupOwner;
        } else {
            return FALSE;
        }
    }

    public function isGroupManager($group_id, $member_id) {
        $membership = GroupMembership::where('group_id', $group_id)
                                     ->where('user_id', $member_id)
                                     ->where('group_owner_approved', 1)
                                     ->where('user_approved', 1)
                                     ->where('is_moderator', 1)
                                     ->where('user_approved_moderator', 1)
                                     ->first();
        if($membership == []) {
            return 0;
        } else {
            return 1;
        }
    }

    public function IsGroupManagerReq($group_id, $member_id) {
        $membership = GroupMembership::where('group_id', $group_id)
                                     ->where('user_id', $member_id)
                                     ->where('group_owner_approved', 1)
                                     ->where('user_approved', 1)
                                     ->first();

        return ($membership->is_moderator);
    }

    public function groupManagers($group_id) {
        $group         = Group::where('id', $group_id)->first();
        $groupManagers = array();
        $managers      = GroupMembership::where('group_id', $group_id)
                                        ->where('group_owner_approved', 1)
                                        ->where('user_approved', 1)
                                        ->where('is_moderator', 1)
                                        ->where('user_approved_moderator', 1)
                                        ->get();
        if($managers == []) {
            return 0;
        } else {
            foreach ($managers as $manager) {
                if($manager->user_id == $group->creator_id) {
                } else {
                    $groupManagers[] = User::find($manager->user_id);
                }
            }

            return $groupManagers;
        }
    }

    public function getGroupsPrivacy($group_id, $action) {
        return AuthorizationAllow::where('resource_id', $group_id)->where('resource_type', 'group')->where('action', $action)->first();
    }

    public function isGroupOwner($group, $user_id = NULL) {
        if(empty($user_id)) {
            $user_id = $this->user_id;
        }
        if($user_id == $group->creator_id) {
            return 1;
        }

        return 0;
    }

    public function GroupViewPerm($group, $user_id = NULL) {
        if(empty($user_id)) {
            $user_id = $this->user_id;
        }
        if($user_id == $group->creator_id) {
            return 1;
        }
        $perm = $this->getGroupsPrivacy($group->id, 'view');
        if($perm['permission'] == Config::get('constants.PERM_GROUP_OFFICERS_AND_OWNERS')) {
            if($this->isGroupOwner($group, $user_id) > 0) {
                return 1;
            }
        }
        if($perm['permission'] == Config::get('constants.PERM_GROUP_MEMBERS')) {
            if(Kinnect2::isFollowingGroup($group['id'], $user_id) > 0) {
                return 1;
            }
        }
        if($perm['permission'] == Config::get('constants.PERM_EVERYONE')) {
            return 1;
        }

        return 0;
    }

    public function GroupCommentPerm($group, $user_id = NULL) {
        if(empty($user_id)) {
            $user_id = $this->user_id;
        }
        if($user_id == $group->creator_id) {
            return 1;
        }
        $perm = $this->getGroupsPrivacy($group->id, 'comment');
        if($perm['permission'] == Config::get('constants.PERM_GROUP_OFFICERS_AND_OWNERS')) {
            if($this->isGroupOwner($group, $user_id) > 0) {
                return 1;
            }
        }
        if($perm['permission'] == Config::get('constants.PERM_GROUP_MEMBERS')) {
            if(Kinnect2::isFollowingGroup($group['id'], $user_id) > 0) {
                return 1;
            }
        }
        if($perm['permission'] == Config::get('constants.PERM_EVERYONE')) {
            return 1;
        }

        return 0;
    }

    public function GroupPrivacyPerm($group, $user_id = NULL) {
        if(empty($user_id)) {
            $user_id = $this->user_id;
        }
        if($user_id == $group->creator_id) {
            return 1;
        }
        $perm = $this->getGroupsPrivacy($group->id, 'group_post_privacy');
        if($perm['permission'] == Config::get('constants.PERM_GROUP_OFFICERS_AND_OWNERS')) {
            if($this->isGroupOwner($group, $user_id) > 0) {
                return 1;
            }
        }
        if($perm['permission'] == Config::get('constants.PERM_GROUP_MEMBERS')) {
            if(Kinnect2::isFollowingGroup($group['id'], $user_id) > 0) {
                return 1;
            }
        }
        if($perm['permission'] == Config::get('constants.PERM_EVERYONE')) {
            return 1;
        }

        return 0;
    }

    public function GroupEventPerm($group, $user_id = NULL) {
        if(empty($user_id)) {
            $user_id = $this->user_id;
        }
        if($user_id == $group->creator_id) {
            return 1;
        }
        $perm = $this->getGroupsPrivacy($group->id, 'group_event_create');
        if($perm['permission'] == Config::get('constants.PERM_GROUP_OFFICERS_AND_OWNERS')) {
            if($this->isGroupOwner($group, $user_id) > 0) {
                return 1;
            }
        }
        if($perm['permission'] == Config::get('constants.PERM_GROUP_MEMBERS')) {
            if(Kinnect2::isFollowingGroup($group['id'], $user_id) > 0) {
                return 1;
            }
        }
        if($perm['permission'] == Config::get('constants.PERM_EVERYONE')) {
            return 1;
        }

        return 0;
    }

    public function eventOwner($eventOwnerId) {
        return User::find($eventOwnerId);
    }

    public function isEventOwner($event) {
        $parent_id   = 0;
        $parent_type = $event->parent_type;
        if(!empty($parent_type)) {
            $is_parent = $this->_get_event_parent_owner($event);
        }

        if($this->user_id == $event->user_id || $this->user_id == $is_parent['creator_id']) {
            return $is_parent['parent_id'];
        }

        return 0;
    }

    public function _get_event_parent_owner($event) {
        if($event->parent_type == 'group') {
            $group = Group::findOrNew($event->parent_id);

            return array(
                'creator_id' => $group->creator_id,
                'parent_id'  => $group->id,
            );
        } else {
            return 0;
        }
    }

    public function isEventGroupOwner($event, $user_id) {
        if($event->parent_type == 'group') {
            $owner = DB::table('groups')->where('id', $event->parent_id)->first();
            if($owner) {
                if($owner->creator_id == $user_id) {
                    return 1;
                }
            }

        }

        return 0;
    }

    public function countEvents($parent_type = NULL, $parent_id = NULL) {
        if($parent_id) {
            if($parent_type == NULL) {
                $parent_type = 'user';
            }

            return DB::table('events')
                     ->where('parent_type', $parent_type)
                     ->where('parent_id', $parent_id)
                     ->count();
        }

        return 0;
    }

    public function countEventGuestAwaitingReplyAttending($event_id = NULL) {
        if($event_id) {
            $count = DB::table('event_memberships')
                       ->where('event_id', $event_id)
                       ->where('event_approved', 0)
                       ->where('user_approved', 1)
                       ->count();

//            if($count > 1) return $count - 1; // to deduct owner from guest
            return $count;
        }

        return 0;
    }

    public function checkGroupRequestStatus($group_id, $user_id) {
        return DB::table('group_membership')
                 ->where('group_id', $group_id)
                 ->where('user_id', $user_id)
                 ->first();

    }

    public function countEventGuestNotAttending($event_id = NULL) {
        if($event_id) {
            $count = DB::table('event_memberships')
                       ->where('event_id', $event_id)
                       ->where('event_approved', 1)
                       ->where('user_approved', 1)
                       ->whereIn('rsvp', [3])
                       ->count();

//            if($count > 1) return $count - 1; // to deduct owner from guest
            return $count;
        }

        return 0;
    }

    public function countEventGuestMaybeAttending($event_id = NULL) {
        if($event_id) {
            $count = DB::table('event_memberships')
                       ->where('event_id', $event_id)
                       ->where('event_approved', 1)
                       ->where('user_approved', 1)
                       ->whereIn('rsvp', [2])
                       ->count();

//            if($count > 1) return $count - 1; // to deduct owner from guest
            return $count;
        }

        return 0;

    }

    public function countEventGuestAttending($event_id = NULL) {
        if($event_id) {
            $count = DB::table('event_memberships')
                       ->where('event_id', $event_id)
                       ->where('event_approved', 1)
                       ->where('user_approved', 1)
                       ->whereIn('rsvp', [1])
                       ->count();

//            if($count > 1) return $count - 1; // to deduct owner from guest
            return $count;
        }

        return 0;
    }

    public function countEventGuest($event_id = NULL) {
        if($event_id) {
            $count = DB::table('event_memberships')
                       ->where('event_id', $event_id)
                       ->where('event_approved', 1)
                       ->where('user_approved', 1)
                       ->whereIn('rsvp', [1, 2])
                       ->count();

//            if($count > 1) return $count - 1; // to deduct owner from guest

            return $count;
        }

        return 0;
    }

    public function eventGuests($event_id) {
        $userGuestEventIds = DB::table('event_memberships')
                               ->where('event_id', $event_id)
                               ->where('event_approved', 1)
                               ->where('user_approved', 1)
                               ->lists('user_id');

        $guests = User::where('userable_type', 'App\Brand')
                      ->whereIn('id', $userGuestEventIds)
                      ->orderByRaw("RAND()")
                      ->paginate(30);
        $guests->setPath('guests');

        return $guests;
    }

    public function isAttending($guest_id, $event_id) {
        $rsvp = DB::table('event_memberships')
                  ->where('event_id', $event_id)
                  ->where('user_id', $guest_id)
                  ->lists('rsvp');

        if(!isset($rsvp[0])) {
            return '';
        }

        if($rsvp[0] == 1) {
            return 'Attending';
        }

        if($rsvp[0] == 2) {
            return 'Maybe Attending';
        }

        if($rsvp[0] == 3) {
            return 'Not Attending';
        }

        return $rsvp[0];
    }

    public function isRequestAttending($guest_id, $event_id) {
        $rsvp = DB::table('event_memberships')
                  ->where('event_id', $event_id)
                  ->where('user_id', $guest_id)
                  ->where('user_approved', 1)
                  ->where('event_approved', 0)
                  ->where('active', 1)
                  ->count('rsvp');

        return $rsvp;
    }

    public function isRequestAttendingOpenEvent($guest_id, $event_id) {
        $rsvp = EventMembership::whereUserId($guest_id)
                       ->whereEventId($event_id)
                       ->whereUserApproved(0)
                       ->whereEventApproved(1)
                       ->whereActive(1)
                       ->count('rsvp');

        return $rsvp;
    }

    public function eventPendingRequest($event_id) {
        $user_ids = DB::table('event_memberships')
                      ->where('event_id', $event_id)
                      ->where('user_approved', 1)
                      ->where('event_approved', 0)
                      ->lists('user_id');

        return $user_ids;
    }

    public function getEventCategoryName($category_id) {
        return DB::table('event_categories')->where('id', $category_id)->value('title');
    }

    public function getAuthAllowSetting($resource_type, $resource_id, $action) {
        return DB::table('authorization_allows')
                 ->where('resource_id', $resource_id)
                 ->where('resource_type', $resource_type)
                 ->where('action', $action)
                 ->value('permission');
    }

    public function LeaderboardUsers() {
        $users = DB::table('users')->where('userable_type', 'App\Consumer')->orderBy('skore', 'desc')->take(50)->get();

        return $users;
    }

    public function LeaderboardBrands() {
        $brands = User::whereUserType(\Config::get('constants.BRAND_USER'))
                      ->where('user_type', \Config::get('constants.BRAND_USER'))
                      ->with('brand_detail')
                      ->orderBy('skore', 'desc')
                      ->take(50)
                      ->get();

        return $brands;
    }

    public function is_friend($user_id, $resource_id) {

        $check = Friendship::where('resource_id', $resource_id)
                           ->where('user_id', $user_id)
            // ->where('resource_approved', 1)
                           ->first();

        if(empty($check)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function is_following($user_id, $resource_id) {

        $check = DB::table('brand_memberships')
                   ->where('brand_id', $resource_id)
                   ->where('user_id', $user_id)
                   ->where('active', 1)
                   ->first();
        if($check == []) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getPhotoUrl($photo_id = NULL, $user_id = NULL, $type = NULL, $thumb_type = NULL) {

        $isFileOrNot = $this->isPhotoAvaiable($photo_id, $user_id, $type, $thumb_type);
        $sm          = new StorageManager();
        if(isset($isFileOrNot->storage_path) && $sm->pathExists('photos/' . $isFileOrNot->storage_path)) {
            $file = $isFileOrNot;
            return \Config::get('constants_activity.PHOTO_URL') . $file->storage_path . '?type=' . urlencode($file->mime_type);
        }

        if($this->is_api) {
            return '';
            //return asset('/local/public/assets/images/defaults/no_image_available.png');
        }
        // <editor-fold desc="Getting cover and profile photos">

        if($type == 'user' AND $user_id > 0) {
            $isUserOrBrand = \Cache::get('_user_'.$user_id,function () use ($user_id){
                $user = User::select('user_type', 'userable_id')->where('id', $user_id)->orWhere('username', $user_id)->first();
                \Cache::forever('_user_'.$user_id,$user);
                return $user;
            });

            if(isset($isUserOrBrand->user_type)) {

                //For default cover photo
                if($thumb_type == 'cover_photo') {
                    if($isUserOrBrand->user_type == \Config::get('constants.BRAND_USER')) {
                        return asset('/local/public/assets/images/defaults/default_brand_cover.jpg');
                    } else {
                        return asset('/local/public/assets/images/defaults/default_consumer_cover.jpg');
                    }
                }//end of default cover photo

                // For default Profile photo
                if(isset($isUserOrBrand->user_type) && in_array($isUserOrBrand->user_type, [1, 2])) {
                    if($thumb_type == 'thumb_normal' OR $thumb_type == 'thumb_profile') {

                        if($isUserOrBrand->user_type == \Config::get('constants.BRAND_USER')) {
                            return asset('/local/public/assets/images/defaults/default_brand_profile_photo.svg');
                        } else {
                            $isMaleOrFemale = Consumer::select('gender')->where('id', $isUserOrBrand->userable_id)->first();

                            if($isMaleOrFemale->gender == 1) {
                                return asset('/local/public/assets/images/defaults/default_male_profile_photo.svg');
                            }

                            if($isMaleOrFemale->gender == 2) {
                                return asset('/local/public/assets/images/defaults/default_female_profile_photo.svg');
                            }

                            if($isMaleOrFemale->gender == 0) {
                                return asset('/local/public/assets/images/defaults/default_unknown_gender.svg');
                            }
                        }
                    }
                }
                // End of default Profile photo
            }
        }// for user type
        // </editor-fold>

        if($type == 'ads') {
            return asset('/local/public/assets/images/defaults/default_ad_profile_photo.png');
        } else if($type == 'brand') {
            return asset('/local/public/assets/images/defaults/default_brand_profile_photo.svg');
        } else if($type == 'event') {
            return asset('/local/public/assets/images/defaults/default_event_icon.svg');
        } else if($type == 'group') {
            return asset('/local/public/assets/images/defaults/default_group_profile_photo.svg');
        } else {
            $isUserOrBrand = \Cache::get('_user_'.$user_id,function () use ($user_id){
                $user = User::where('id', $user_id)->orWhere('username', $user_id)->first();
                \Cache::forever('_user_'.$user_id,$user);
                return $user;
            });

            // For default Profile photo
            if(isset($isUserOrBrand->user_type) && in_array($isUserOrBrand->user_type, [1, 2])) {
                if($type == 'user') {

                    if($isUserOrBrand->user_type == \Config::get('constants.BRAND_USER')) {
                        return asset('/local/public/assets/images/defaults/default_brand_profile_photo.svg');
                    } else {
                        $isMaleOrFemale = Consumer::select('gender')->where('id', $isUserOrBrand->userable_id)->first();

                        if($isMaleOrFemale->gender == 1) {
                            return asset('/local/public/assets/images/defaults/default_male_profile_photo.svg');
                        }

                        if($isMaleOrFemale->gender == 2) {
                            return asset('/local/public/assets/images/defaults/default_female_profile_photo.svg');
                        }

                        if($isMaleOrFemale->gender == 0) {
                            return asset('/local/public/assets/images/defaults/default_unknown_gender.svg');
                        }
                    }
                }
            }

            // End of default Profile photo

            return asset('/local/public/assets/images/defaults/default_male_profile_photo.svg');
        }

    }

    public function isPhotoAvaiable($photo_id = NULL, $user_id = NULL, $type = NULL, $thumb_type = NULL) {

        if(isset($photo_id)) {
            $photo = AlbumPhoto::where('photo_id', $photo_id)
                               ->select('file_id')
                               ->first();

            $tryForPhotFromFileId = 0;

            if(!isset($photo)) {

                $tryForPhotFromFileId = 1;
                $file                 = StorageFile::where('file_id', $photo_id)->first();
            }//try once more to find if it it file_id only

            if(isset($thumb_type) AND isset($photo->file_id) AND $tryForPhotFromFileId == 0) {
                $file = StorageFile::where('parent_file_id', $photo->file_id)
                                   ->where('type', $thumb_type)->first();
            } else if(isset($photo->file_id) AND $tryForPhotFromFileId == 0) {
                $file = StorageFile::where('file_id', $photo->file_id)->first();
            }

            if(!isset($file->storage_path) AND $thumb_type != '') {
                return 0;
            }

            return $file;
        }
    }

    public function profilePhoto($photo_id, $user_id, $type = NULL, $thumb_type = NULL) {
        if($photo_id > 0) {
            $photo = AlbumPhoto::where('photo_id', $photo_id)
                               ->select('file_id')
                               ->first();
        }

        if(isset($photo->file_id)) {
            $file = StorageFile::where('file_id', $photo->file_id)->first();
        }

        if(empty($file->storage_path)) {
            if($type == 'ads') {
                return asset('/local/public/assets/images/defaults/default_ad_profile_photo.png');
            } else {
                return asset('/local/public/assets/images/defaults/default_no_photo.svg');
            }

            if($type == 'brand') {
                return asset('/local/public/assets/images/defaults/default_brand_profile_photo.svg');
            } else {
                return asset('/local/public/assets/images/defaults/default_no_photo.svg');
            }

            if($type == 'event') {
                return asset('/local/public/assets/images/defaults/left-menu-img-header.jpg');
            } else {
                return asset('/local/public/assets/images/defaults/default_no_photo.svg');
            }
        }

        return \Config::get('constants_activity.PHOTO_URL') . $file->storage_path . '?type=' . urlencode($file->mime_type);

    }

    public function myPolls($take = NULL) {
        if(is_null($take)) {
            $take = 3;
        }
        $userPollIds = DB::table('polls')
                         ->where('user_id', $this->user_id)
                         ->lists('id');

        return $brands = Poll::whereIn('id', $userPollIds)
                             ->orderByRaw("RAND()")
                             ->take($take)->get();

        return FALSE;
    }

    public function recomendedPolls($take = NULL) {
        if(is_null($take)) {
            $take = 3;
        }
        $userPollIds = DB::table('polls')
                         ->where('user_id', $this->user_id)
                         ->lists('id');

        return $brands = Poll::whereNotIn('id', $userPollIds)
                             ->orderByRaw("RAND()")
                             ->take($take)->get();

        return FALSE;
    }

    public function myBattles($take = NULL) {
        if(is_null($take)) {
            $take = 3;
        }
        $userBattleIds = DB::table('battles')
                           ->where('user_id', $this->user_id)
                           ->lists('id');

        return $brands = Battle::whereIn('id', $userBattleIds)
                               ->orderByRaw("RAND()")
                               ->take($take)->get();

        return FALSE;
    }

    public function recomendedBattles($take = NULL) {
        if(is_null($take)) {
            $take = 3;
        }
        $userBattleIds = DB::table('battles')
                           ->where('user_id', $this->user_id)
                           ->lists('id');

        return $brands = Battle::whereNotIn('id', $userBattleIds)
                               ->orderByRaw("RAND()")
                               ->take($take)->get();
    }

    public function incrementAdView($ad, $is_clicked = NULL) {
        if(isset($ad)) {
            $adStat = new AdStatistics;

            $adStat->user_ad_id     = $ad->id;
            $adStat->ad_campaign_id = $ad->campaign_id;
            $adStat->viewer_id      = $this->user_id;
            $adStat->host_name      = $_SERVER['HTTP_HOST'];
            $adStat->user_agent     = $_SERVER['HTTP_USER_AGENT'];
            $adStat->url            = $ad->cads_url;
            $adStat->value_click    = '';
            $adStat->value_view     = 1;
            $adStat->value_like     = '';

            $adStat->save();
            $end_date = explode(' ', $ad->cads_end_date);

            if($end_date[0] < Carbon::now()->toDateString()) {
                $ad->enable = '0';
                $ad->status = '0';
            }

            if($ad->price_model == 'Pay/view') {

                if($ad->limit_view == 1) {
                    $ad->approved       = '0';
                    $ad->enable         = '0';
                    $ad->status         = '0';
                    $ad->payment_status = '0';
                }

                $ad->limit_view = $ad->limit_view - 1;
            }

            $ad->save();
        }

        return 0;
    }

    public function incrementAdClick($ad) {
        $adStat = new AdStatistics;

        $adStat->user_ad_id     = $ad->id;
        $adStat->ad_campaign_id = $ad->campaign_id;
        $adStat->viewer_id      = $this->user_id;
        $adStat->host_name      = $_SERVER['HTTP_HOST'];
        $adStat->user_agent     = $_SERVER['HTTP_USER_AGENT'];
        $adStat->url            = $ad->cads_url;
        $adStat->value_click    = 1;
        $adStat->value_view     = '';
        $adStat->value_like     = '';

        $adStat->save();

        $end_date = explode(' ', $ad->cads_end_date);

        if($end_date[0] < Carbon::now()->toDateString()) {
            $ad->enable = '0';
            $ad->status = '0';
        }

        if($ad->price_model == 'Pay/click') {
            if($ad->limit_click == 1) {
                $ad->approved       = '0';
                $ad->enable         = '0';
                $ad->status         = '0';
                $ad->payment_status = '0';
            }

            $ad->limit_click = $ad->limit_click - 1;
        }
        $ad->save();
    }

    public function getAdsWidget() {
        if($this->data->user->userable_type == 'App\Consumer') {
            $consumer_id = $this->data->user->userable_id;

            $userConsumer = \Cache::get('_consumer_'.$consumer_id,function () use ($consumer_id){
                $consumer = Consumer::find($consumer_id);
                \Cache::forever('_consumer_'.$consumer_id,$consumer);
                return $consumer;
            });

            $birthdate = explode('-', $userConsumer->birthdate);
            $birthdate = Carbon::createFromDate($birthdate[0], $birthdate[1], $birthdate[2]);

            $adsIdsReportedByThisUser = DB::table('ad_cancels')
                                          ->where('user_id', $this->data->user->id)
                                          ->lists('ad_id');

            $adsIdsCountryByThisUser = DB::table('ad_targets_countries')
                                         ->where('ad_targets_countries.country_id', "=", $this->data->user->country)
                                         ->orWhere('ad_targets_countries.country_id', "=", 0)
                                         ->lists('user_ad_id');

            $adsIdsTargettingByThisUser = DB::table('ad_targets')
                                            ->where('ad_targets.age_max', ">=", $birthdate->diff(Carbon::now())->format('%y'))
                                            ->where('ad_targets.age_min', "<=", $birthdate->diff(Carbon::now())->format('%y'))
                                            ->where('ad_targets.gender', "<=", $userConsumer->gender)
                                            ->where('ad_targets.profile', "<=", Config::get('constants.REGULAR_USER'))
                                            ->lists('user_ad_id');

            return $ads = DB::table('ad_user_ads')
                            ->where('ad_user_ads.enable', 1)
                            ->whereNotIn('ad_user_ads.id', $adsIdsReportedByThisUser)
                            ->whereIn('ad_user_ads.id', $adsIdsCountryByThisUser)
                            ->whereIn('ad_user_ads.id', $adsIdsTargettingByThisUser)
                            ->where('ad_user_ads.payment_status', 1)
                            ->where('ad_user_ads.status', 1)
                            ->orderByRaw("RAND()")
                            ->take(2)
                            ->get();
        }

        return $ads = AdUserAd::where('enable', 1)
                              ->where('payment_status', 1)
                              ->where('status', 1)
                              ->orderByRaw("RAND()")
                              ->take(2)->get();
    }

    public function countCampaignAds($id) {
        return AdUserAd::where(['campaign_id' => $id])->count();
    }

    public function countCampaignAdsTotalViews($id) {
        return AdStatistics::where(['ad_campaign_id' => $id])
                           ->where(['value_view' => 1])->count();
    }

    public function countCampaignAdsTotalClicks($id) {
        return AdStatistics::where(['ad_campaign_id' => $id])
                           ->where(['value_click' => 1])->count();
    }

    public function countAdTotalViews($id) {
        return AdStatistics::where(['user_ad_id' => $id])
                           ->where(['value_view' => 1])->count();
    }

    public function countAdTotalClicks($id) {
        return AdStatistics::where(['user_ad_id' => $id])
                           ->where(['value_click' => 1])->count();
    }

    public function update_skore($type, $user_id) {
        $skoreRepository = new SkoreRepository();
        $skoreRepository->update_skore($type, $user_id);
    }

    public function isAdPaused($ad_id) {
        $ad = AdUserAd::find($ad_id);

        return $ad->status;
    }

    public function groupMember($userId) {
        return \Cache::get('_user_'.$userId,function () use ($userId){
            $user = User::find($userId);
            \Cache::forever('_user_'.$userId,$user);
            return $user;
        });
    }

    public function GroupCategory($id) {
        return DB::table('group_categories')->where('category_id', $id)->first();
    }

    public function getGroupsEventCreationPrivacy($group_id) {
        return AuthorizationAllow::where('resource_id', $group_id)->where('resource_type', 'group')->where('action', 'group_event_create')->first();
    }

    public function checkEventRequestStatus($event_id, $user_id) {
        return DB::table('event_memberships')
                 ->where('event_id', $event_id)
                 ->where('user_id', $user_id)
                 ->first();

    }

    public function countEventInvitesSentTo($event_id = NULL) {
        if($event_id) {
            $count = DB::table('event_memberships')
                       ->where('event_id', $event_id)
                       ->where('event_approved', 1)
                       ->where('user_approved', 0)
                       ->count();

            return $count;
        }

        return 0;
    }

    public function getUserDetailsByUserableId($id) {
        return DB::table('users_consumers')->where('id', $id)->first();
    }

    public function getBrandDetailsByUserableId($id) {
        return DB::table('users_brands')->where('id', $id)->first();
    }

    public function _brand_details($members) {
        $all        = [];
        $allMembers = [];
        if(!empty($members)) {
            foreach ($members as $member) {
                $data = $this->_brand_detail_meta($member);
                if(!empty($data)) {
                    $allMembers[] = $data;
                }
            }
        }

        return $allMembers;
    }

    public function _brand_detail_meta($brand) {
        $data = $this->_get_user_meta($brand);
        if(!empty($data['brand_detail']->brand_name)) {

            $data['brand_name']  = $data['brand_detail']->brand_name;
            $data['profile_url'] = $data['username'];
            unset($data['brand_detail']);

            return $data;
        }
    }

    public function _get_user_meta($user) {
        //$photo    = AlbumPhoto::where( 'photo_id', $user->photo_id )->with('storage_file')->first();
        $user['profile_photo_url'] = $this->getPhotoUrl($user->photo_id, $user->id, 'user', 'thumb_normal'); //$this->get_photo_path($user->photo_id);

        //$user['cover_photo_url'] = $this->get_photo_path($user->cover_photo_id);

        return $user;
    }

    public function get_photo_path($id) {
        $photo   = AlbumPhoto::where('photo_id', $id)
                             ->select(['file_id'])
                             ->first();
        $file_id = isset($photo->file_id) ? $photo->file_id : NULL;
        $file    = StorageFile::where('file_id', $file_id)->first();
        $path    = isset($file->storage_path) ? $file->storage_path : NULL;

        if(!empty($path)) {
            return \Config::get('constants_activity.PHOTO_URL') . $path . '?type=' . urlencode($file->mime_type);
        } else {
            if($this->is_api) {
                //return asset('/local/public/assets/images/defaults/no_image_available.png');
                return '';
            }
            return asset('/local/public/assets/images/defaults/default_no_photo.svg');
        }
    }

    public function get_photo_storage_id($id, $type = NULL) {
        $path = \Config::get('constants_activity.PHOTO_URL');
        if(!is_null($type)) {
            $file = StorageFile::whereFileId($id)->whereType($type)->first();

        } else {
            $file = StorageFile::whereFileId($id)->first();
        }

        if($file) {
            return $path . $file->storage_path . '?type=' . urlencode($file->photo_mime);
        } else {
            return asset('/local/public/assets/images/defaults/default_event_icon.svg');
        }
    }

    public function getBrand($id) {
        return User::whereUserableId($id)->whereUserableType('App\Brand')->first();
    }

    public function get_friends($user_id) {
        return \DB::table('user_membership')
                  ->join('users', 'users.id', '=', 'user_membership.user_id')
                  ->where('resource_id', $user_id)
            // ->where('users.user_type', '=', \Config::get('constants.REGULAR_USER'))
                  ->where('user_membership.active', 1)
                  ->select('user_membership.*', 'users.name', 'users.username', 'users.displayname', 'users.user_type', 'users.photo_id')
                  ->get();

    }

    public function getAlbumPhotoUrl($photo_id = NULL, $type = NULL, $isParent = TRUE) {
        if($isParent) {
            $file = StorageFile::whereParentFileId($photo_id)->whereType($type)->first();
        } else {
            $file = StorageFile::whereFileId($photo_id)->first();
        }

        if(isset($file)) {

            if(!isset($file->storage_path)) {
                if($this->is_api) {
                    return '';
                }
                if($type == 'ads') {
                    return asset('/local/public/assets/images/defaults/default_ad_profile_photo.png');
                } else if($type == 'brand') {
                    return asset('/local/public/assets/images/defaults/default_brand_profile_photo.svg');
                } else if($type == 'event') {
                    return asset('/local/public/assets/images/defaults/default_event_icon.svg');
                } else if($type == 'album') {
                    return asset('/local/public/assets/images/defaults/default_album.svg');
                } else {
                    return asset('/local/public/assets/images/defaults/default_no_photo.svg');
                }
            }

            if(isset($file->storage_path)) {
                return \Config::get('constants_activity.PHOTO_URL') . $file->storage_path . '?type=' . urlencode($file->mime_type);
            } else {
                return asset('/local/public/assets/images/defaults/default_no_photo.svg');
            }
        }

        return asset('/local/public/assets/images/defaults/default_no_photo.svg');
    }

    public function get_gender($user, $gender = FALSE) {
        if(isset($user->user_type) && !$user->user_type == \Config::get('constants.REGULAR_USER')) {
            return '';
        }
        if(!isset($user->user_detail)) {
            $user = User::whereId($user->id)->with('consumer_detail')->first();
        }
       //echo '<tt><pre>'; print_r($user);
        if(!isset($user->consumer_detail) && empty($user->consumer_detail)){
            return '';
        }
        $user = $user->consumer_detail;

        if(isset($user->gender)) {
            if($user->gender == 1) {
                return 'male';
            } else {
                return 'female';
            }
        } else {
            return '';
        }
    }

    public function get_users_meta($users) {
        $allUsers = [];

        foreach ($users as $user) {
            $allUsers[] = $this->_get_user_details($user);
        }

        return $allUsers;
    }

    public function _get_user_details($user, $user_detail = FALSE) {

        $photo_id = $user->photo_id;
        if($user_detail) {
            $photo_id = $user->user->photo_id;
        }
        $cover_id = $user->cover_photo_id;
        if($user_detail) {
            $cover_id = $user->user->cover_photo_id;
        }
        $user['profile_photo_url'] = \Kinnect2::getPhotoUrl($photo_id, $user->user->id, 'user', 'thumb_normal');
        $user['cover_photo_url']   = \Kinnect2::getPhotoUrl($cover_id, $user->user->id, 'user', 'cover_photo');
        if($this->check_user_type($user)) {
            $user['gender'] = $this->get_gender($user);
        }

        return $user;
    }

    //If normal user return true else false
    public function check_user_type($user) {
        if($user->user_type == \Config::get('constants.REGULAR_USER')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_users_meta_from_membership($users, $detailAvailable = FALSE, $user_id = NULL) {
        $allUsers = [];
        if(!empty($users)) {
            foreach ($users as $user) {
                $allUsers[] = $this->get_user_photos($user, $detailAvailable, $user_id);
            }
        }

        return $allUsers;
    }

    public function get_user_photos($user, $detailAvailable = FALSE, $current_user_id = NULL) {

        if($detailAvailable) {
            $userData = User::findOrNew($user->user_id);
            $photo_id = $userData->photo_id;
            $cover_id = $userData->cover_photo_id;
            $user_id  = $user->user_id;
        } else {
            $photo_id = $user->photo_id;
            $cover_id = $user->cover_photo_id;
            $user_id  = $user->id;
        }

        $user->profile_photo_url = \Kinnect2::getPhotoUrl($photo_id, $user_id, 'user', 'thumb_normal');
        $user->cover_photo_url   = \Kinnect2::getPhotoUrl($cover_id, $user_id, 'user', 'cover_photo');
        $user->is_friend         = 0;

        if($user->user_type == 1) {
            $user_detail  = User::whereId($user_id)->with('consumer_detail')->first();
            $user->gender = (@$user_detail->consumer_detail->gender == 1 ? 'male' : 'female');
            if(!is_null($user_id)) {
                $user->is_friend = ($this->is_friend($user_id, $current_user_id) ? 'Yes' : 'No');
            }
        } else {
            $user->is_following = ($this->is_following($current_user_id, $user_id) ? 'yes' : 'no');
        }

        return $user;
    }

    function slugify($str, $options = array()) {
        // Make sure string is in UTF-8 and strip invalid UTF-8 characters
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

        $defaults = array(
            'delimiter'     => '-',
            'limit'         => NULL,
            'lowercase'     => TRUE,
            'replacements'  => array(),
            'transliterate' => FALSE,
        );

        // Merge options
        $options = array_merge($defaults, $options);

        $char_map = array(
            // Latin
            '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'AE', '�' => 'C',
            '�' => 'E', '�' => 'E', '�' => 'E', '�' => 'E', '�' => 'I', '�' => 'I', '�' => 'I', '�' => 'I',
            '�' => 'D', '�' => 'N', '�' => 'O', '�' => 'O', '�' => 'O', '�' => 'O', '�' => 'O', '?' => 'O',
            '�' => 'O', '�' => 'U', '�' => 'U', '�' => 'U', '�' => 'U', '?' => 'U', '�' => 'Y', '�' => 'TH',
            '�' => 'ss',
            '�' => 'a', '�' => 'a', '�' => 'a', '�' => 'a', '�' => 'a', '�' => 'a', '�' => 'ae', '�' => 'c',
            '�' => 'e', '�' => 'e', '�' => 'e', '�' => 'e', '�' => 'i', '�' => 'i', '�' => 'i', '�' => 'i',
            '�' => 'd', '�' => 'n', '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'o', '?' => 'o',
            '�' => 'o', '�' => 'u', '�' => 'u', '�' => 'u', '�' => 'u', '?' => 'u', '�' => 'y', '�' => 'th',
            '�' => 'y',
            // Latin symbols
            '�' => '(c)',
            // Greek
            '?' => 'A', '?' => 'B', '?' => 'G', '?' => 'D', '?' => 'E', '?' => 'Z', '?' => 'H', '?' => '8',
            '?' => 'I', '?' => 'K', '?' => 'L', '?' => 'M', '?' => 'N', '?' => '3', '?' => 'O', '?' => 'P',
            '?' => 'R', '?' => 'S', '?' => 'T', '?' => 'Y', '?' => 'F', '?' => 'X', '?' => 'PS', '?' => 'W',
            '?' => 'A', '?' => 'E', '?' => 'I', '?' => 'O', '?' => 'Y', '?' => 'H', '?' => 'W', '?' => 'I',
            '?' => 'Y',
            '?' => 'a', '?' => 'b', '?' => 'g', '?' => 'd', '?' => 'e', '?' => 'z', '?' => 'h', '?' => '8',
            '?' => 'i', '?' => 'k', '?' => 'l', '?' => 'm', '?' => 'n', '?' => '3', '?' => 'o', '?' => 'p',
            '?' => 'r', '?' => 's', '?' => 't', '?' => 'y', '?' => 'f', '?' => 'x', '?' => 'ps', '?' => 'w',
            '?' => 'a', '?' => 'e', '?' => 'i', '?' => 'o', '?' => 'y', '?' => 'h', '?' => 'w', '?' => 's',
            '?' => 'i', '?' => 'y', '?' => 'y', '?' => 'i',
            // Turkish
            '?' => 'S', '?' => 'I', '�' => 'C', '�' => 'U', '�' => 'O', '?' => 'G',
            '?' => 's', '?' => 'i', '�' => 'c', '�' => 'u', '�' => 'o', '?' => 'g',
            // Russian
            '?' => 'A', '?' => 'B', '?' => 'V', '?' => 'G', '?' => 'D', '?' => 'E', '?' => 'Yo', '?' => 'Zh',
            '?' => 'Z', '?' => 'I', '?' => 'J', '?' => 'K', '?' => 'L', '?' => 'M', '?' => 'N', '?' => 'O',
            '?' => 'P', '?' => 'R', '?' => 'S', '?' => 'T', '?' => 'U', '?' => 'F', '?' => 'H', '?' => 'C',
            '?' => 'Ch', '?' => 'Sh', '?' => 'Sh', '?' => '', '?' => 'Y', '?' => '', '?' => 'E', '?' => 'Yu',
            '?' => 'Ya',
            '?' => 'a', '?' => 'b', '?' => 'v', '?' => 'g', '?' => 'd', '?' => 'e', '?' => 'yo', '?' => 'zh',
            '?' => 'z', '?' => 'i', '?' => 'j', '?' => 'k', '?' => 'l', '?' => 'm', '?' => 'n', '?' => 'o',
            '?' => 'p', '?' => 'r', '?' => 's', '?' => 't', '?' => 'u', '?' => 'f', '?' => 'h', '?' => 'c',
            '?' => 'ch', '?' => 'sh', '?' => 'sh', '?' => '', '?' => 'y', '?' => '', '?' => 'e', '?' => 'yu',
            '?' => 'ya',
            // Ukrainian
            '?' => 'Ye', '?' => 'I', '?' => 'Yi', '?' => 'G',
            '?' => 'ye', '?' => 'i', '?' => 'yi', '?' => 'g',
            // Czech
            '?' => 'C', '?' => 'D', '?' => 'E', '?' => 'N', '?' => 'R', '�' => 'S', '?' => 'T', '?' => 'U',
            '�' => 'Z',
            '?' => 'c', '?' => 'd', '?' => 'e', '?' => 'n', '?' => 'r', '�' => 's', '?' => 't', '?' => 'u',
            '�' => 'z',
            // Polish
            '?' => 'A', '?' => 'C', '?' => 'e', '?' => 'L', '?' => 'N', '�' => 'o', '?' => 'S', '?' => 'Z',
            '?' => 'Z',
            '?' => 'a', '?' => 'c', '?' => 'e', '?' => 'l', '?' => 'n', '�' => 'o', '?' => 's', '?' => 'z',
            '?' => 'z',
            // Latvian
            '?' => 'A', '?' => 'C', '?' => 'E', '?' => 'G', '?' => 'i', '?' => 'k', '?' => 'L', '?' => 'N',
            '�' => 'S', '?' => 'u', '�' => 'Z',
            '?' => 'a', '?' => 'c', '?' => 'e', '?' => 'g', '?' => 'i', '?' => 'k', '?' => 'l', '?' => 'n',
            '�' => 's', '?' => 'u', '�' => 'z',
        );

        // Make custom replacements
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

        // Transliterate characters to ASCII
        if($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }

        // Replace non-alphanumeric characters with our delimiter
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

        // Remove duplicate delimiters
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

        // Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

        // Remove delimiter from ends
        $str = trim($str, $options['delimiter']);

        $slug = $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;

        $result = DB::table($options['table'])
                    ->where($options['field'], 'like', $slug . '%')
                    ->get();

        // echo '<tt><pre>'; print_r($result); die;
        //dd(DB::getQueryLog());
        if(count($result)) {

            $slugs = array();
            $i     = 0;
            foreach ($result as $row) {
                $slugs[$i] = $options['lowercase'] ? mb_strtolower($row->$options['field'], 'UTF-8') : $row->$options['field'];
                $i++;
            }

            if(in_array($slug, $slugs)) {

                $max = 0;

                //keep incrementing $max until a space is found
                while (in_array(($slug . '-' . ++$max), $slugs))
                    ;

                //update $slug with the appendage
                $slug .= '-' . $max;
            }

            return $slug;
        } else {
            return $slug;
        }
    }

    public function get_attachment_thumb($id, $type = NULL) {
        $path = \Config::get('constants_activity.ATTACHMENT_THUMB');
        if(!is_null($type)) {
            $file = StorageFile::whereFileId($id)->whereType($type)->first();

        } else {
            $file = StorageFile::whereFileId($id)->first();
        }

        if($file) {
            return $path . $file->storage_path . '?type=' . urlencode($file->photo_mime);
        } else {
            return asset('/local/public/assets/images/defaults/default_event_icon.svg');
        }
    }

    public function friend_requests($user_id) {
        return \DB::table('user_membership')
                  ->join('users', 'users.id', '=', 'user_membership.resource_id')
                  ->where('user_id', $user_id)
                  ->where('user_membership.resource_approved', 1)
                  ->where('user_membership.active', 0)
                  ->select('*')
                  ->get();
    }
}

