<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/16/2015
 * Time: 6:27 PM
 */

namespace App\Repository\Eloquent;

use App\ActivityNotification;
use App\AlbumPhoto;
use App\AuthorizationAllow;
use App\Classes\Kinnect2;
use App\Event;
use App\Events\ActivityDelete;
use App\Events\ActivityLog;
use App\Events\CreateNotification;
use App\Facades\AuthorizationAllowClassFacade;
use App\Group;
use App\GroupMembership;
use App\StorageFile;
use App\User;
use Auth;
use Config;
use DB;
use Intervention\Image\Facades\Image;

class GroupRepository extends Repository
{
    protected $group;
    private   $activity_type;

    public function __construct(Group $group) {
        parent::__construct();
        $this->group         = $group;
        $this->activity_type = \Config::get('constants_activity.OBJECT_TYPES.GROUP.NAME');
    }

    public static function isFollowing($group_id, $user_id) {
        return DB::table('group_membership')
            ->where('group_id', $group_id)
            ->where('user_id', $user_id)
            ->count();
    }

    public static function updateFollowing($group_id, $user_id) {
        $group = Group::find($group_id);
        if ($group->approval_required == 0) {
            DB::table('group_membership')
                ->where('group_id', '=', $group_id)
                ->where('user_id', '=', $user_id)
                ->update(['user_approved' => 1, 'group_owner_approved' => 1, 'active' => 1]);

        } else {
            DB::table('group_membership')
                ->where('group_id', '=', $group_id)
                ->where('user_id', '=', $user_id)
                ->update(['user_approved' => 1, 'group_owner_approved' => 0, 'active' => 1]);

        }


        return '1';
    }

    public static function unfollow($group_id, $user_id) {
        DB::table('group_membership')
            ->where('group_id', '=', $group_id)
            ->where('user_id', '=', $user_id)
            ->delete();

        return '1';
    }

    public function getGroupPrivacySettingValue($type, $type_id, $action) {
        return AuthorizationAllowClassFacade::getSettingPermissionValue($type, $type_id, $action);
    }

    public function create_group($groupFormData, $user_id) {


        $group = new Group();
        // Save data for resource group
        $group->creator_id         = $user_id;
        $group->title              = $groupFormData->title;
        $group->description        = $groupFormData->description;
        $group->category_id        = $groupFormData->category;
        $group->search             = $groupFormData->search;
        $group->members_can_invite = $groupFormData->members_can_invite;
        $group->approval_required  = $groupFormData->approval_required;
        //$group->photo_id = $groupFormData->title;
        //$group->cover_photo_id = $groupFormData->title;
        $group->save();

        AuthorizationAllowClassFacade::Setting('group', $group->id, $groupFormData->view_privacy, 'view');
        AuthorizationAllowClassFacade::Setting('group', $group->id, $groupFormData->comment_privacy, 'comment');
        AuthorizationAllowClassFacade::Setting('group', $group->id, $groupFormData->post_privacy, 'group_post_privacy');
        AuthorizationAllowClassFacade::Setting('group', $group->id, $groupFormData->privacy_event_creation, 'group_event_create');

        $this->follow($group->id, $user_id, 1);

        $options = array(
            'object_type' => $this->activity_type,
            'type'        => \Config::get('constants_activity.OBJECT_TYPES.GROUP.ACTIONS.CREATE'),
            'subject'     => $user_id,
            'object'      => $group->id,
        );

        \Event::fire(new ActivityLog($options));

        if ($this->is_api) {
            if (isset($groupFormData->friend_list)) {
                foreach ($groupFormData->friend_list as $row) {
                    $this->group_invitation($group->id, $row, $user_id);
                }
            }
        }

        return $group->id;
    }

    public static function follow($group_id, $user_id, $isNewGroup = 0) {
        $group = Group::find($group_id);


        if ($group->approval_required == 1 and $isNewGroup == 0) {

            $group_membership           = new GroupMembership();
            $group_membership->group_id = $group_id;
            $group_membership->active   = 0;
            if ($group->creator_id == $user_id) {
                $group_membership->is_moderator            = 1;
                $group_membership->user_approved_moderator = 1;
            } else {
                $group_membership->is_moderator            = 0;
                $group_membership->user_approved_moderator = 0;
            }
            $group_membership->group_owner_approved = $isNewGroup;
            $group_membership->user_approved        = 1;
            $group_membership->user_id              = $user_id;
            $group_membership->save();

            $attributes = array(
                'resource_id' => $group->creator_id,
                'subject_id'  => $user_id,
                'object_id'   => $group_id,
                'object_type' => \Config::get('constants_activity.OBJECT_TYPES.GROUP.NAME'),
                'type'        => \Config::get('constants_activity.OBJECT_TYPES.GROUP.ACTIONS.REQUEST_SENT'),
            );

            \Event::fire(new CreateNotification($attributes));
        } else {
            $group_membership           = new GroupMembership();
            $group_membership->group_id = $group_id;
            $group_membership->active   = 1;
            if ($group->creator_id == $user_id) {
                $group_membership->is_moderator            = 1;
                $group_membership->user_approved_moderator = 1;
            } else {
                $group_membership->is_moderator            = 0;
                $group_membership->user_approved_moderator = 0;
            }
            $group_membership->group_owner_approved = 1;
            $group_membership->user_approved        = 1;
            $group_membership->user_id              = $user_id;
            $group_membership->save();
        }

        return 1;
    }

    public function group_invitation($group_id, $user_id, $owner_id) {

        //$this->send_invitation($group_id, $user_id, $owner_id);

        $groupMem = new GroupMembership();
        $groupMem->group_id = $group_id;
        $groupMem->active = 1;
        $groupMem->group_owner_approved = 1;
        $groupMem->user_approved = 0;
        $groupMem->user_id = $user_id;
        $groupMem->save();

        $attributes = array(
            'resource_id' => $user_id,
            'subject_id'  => $owner_id,
            'object_id'   => $group_id,
            'object_type' => \Config::get('constants_activity.OBJECT_TYPES.GROUP.NAME'),
            'type'        => \Config::get('constants_activity.OBJECT_TYPES.GROUP.ACTIONS.INVITATION_SENT'),
        );

        \Event::fire(new CreateNotification($attributes));

        return TRUE;
    }

    public function allCategories() {
        return DB::table('group_categories')->lists('title', 'category_id');
    }

    public function update($groupFormData, $group_id, $user_id) {
        $group = Group::find($group_id);

        //$group->creator_id         = $user_id;
        $group->title              = $groupFormData->title;
        $group->description        = $groupFormData->description;
        $group->category_id        = $groupFormData->category;
        $group->search             = $groupFormData->search;
        $group->members_can_invite = $groupFormData->members_can_invite;
        $group->approval_required  = $groupFormData->approval_required;
        //$group->photo_id = $groupFormData->title;
        //$group->cover_photo_id = $groupFormData->title;
        $group->update();

        if (AuthorizationAllowClassFacade::getSettingPermissionValue('group', $group->id, 'view')) {
            AuthorizationAllowClassFacade::changeSetting('group', $group->id, $groupFormData->view_privacy, 'view');
        } else {
            AuthorizationAllowClassFacade::Setting('group', $group->id, $groupFormData->view_privacy, 'view');
        }

        if ((AuthorizationAllowClassFacade::getSettingPermissionValue('group', $group->id, 'comment'))) {
            AuthorizationAllowClassFacade::changeSetting('group', $group->id, $groupFormData->comment_privacy, 'comment');
        } else {
            AuthorizationAllowClassFacade::Setting('group', $group->id, $groupFormData->comment_privacy, 'comment');
        }
        if ((AuthorizationAllowClassFacade::getSettingPermissionValue('group', $group->id, 'group_post_privacy'))) {
            AuthorizationAllowClassFacade::changeSetting('group', $group->id, $groupFormData->post_privacy, 'group_post_privacy');
        } else {
            AuthorizationAllowClassFacade::Setting('group', $group->id, $groupFormData->post_privacy, 'group_post_privacy');
        }
        if ((AuthorizationAllowClassFacade::getSettingPermissionValue('group', $group->id, 'group_event_create'))) {
            AuthorizationAllowClassFacade::changeSetting('group', $group->id, $groupFormData->privacy_event_creation, 'group_event_create');
        } else {
            AuthorizationAllowClassFacade::Setting('group', $group->id, $groupFormData->privacy_event_creation, 'group_event_create');
        }
        if ($this->is_api) {
            if (isset($groupFormData->friend_list)) {
                foreach ($groupFormData->friend_list as $row) {
                    $this->send_invitation($group->id, $row, $user_id);
                }
            }
        }
        return $group_id;
    }

    public function groups() {
        return Group::get();
    }

    public function myGroups() {
        return Group::get();
    }

    public function find($group_id) {
        return Group::find($group_id);
    }

    public function group_membership($group_id) {
        return GroupMembership::whereGroupId($group_id)->get();
    }

    public function deleteGroup($group_id = NULL, $user_id) {
        $members = $this->group_membership($group_id);
        AuthorizationAllowClassFacade::deleteResource($group_id, 'group');
        if ($group_id > 0) {
            Group::destroy($group_id);
            foreach ($members as $member) {
                $member->delete();
            }
            $params = [
                'subject_id'  => $user_id,
                'object_id'   => $group_id,
                'object_type' => \Config::get('constants_activity.OBJECT_TYPES.GROUP.NAME'),
            ];
            \Event::fire(new ActivityDelete($params));
        }
    }

    public function groupEvents($group_id) {
        return Event::whereParentId($group_id)->get();
    }

    public function uploadingPhotos($tmp_file_path, $group_id) {
        $group       = Group::find($group_id);
        $folder_path = public_path('groups/group_' . $group_id);
        if (!file_exists($folder_path)) {
            if (!mkdir($folder_path, 0777, TRUE)) {
                $folder_path = '';
            }
        }

        $file_name = time() . rand(111111111, 9999999999);
        $image1    = Image::make($tmp_file_path);
        $image1->resize(Config::get('constants.PROFILE_THUMB_WIDTH'), Config::get('constants.PROFILE_THUMB_HEIGHT'));

        if ($image1->save($folder_path . '/' . $file_name . '.JPEG')) {
            // save image record to db, if it is saved well enough.
            $path_photo      = $file_name . '.JPEG';
            $album_id        = $this->insertDefaultAlbum($group_id, $path_photo);
            $photo_id        = $this->insertPhotoIntoAlbum($group_id, $album_id, $path_photo);
            $group->photo_id = $photo_id;
            $group->save();
        }

        $file_name = time() . rand(111111111, 9999999999);
        $image2    = Image::make($tmp_file_path);
        $image2->resize(Config::get('constants.WALL_IMAGE_WIDTH'), Config::get('constants.WALL_IMAGE_HEIGHT'));

        if ($image2->save($folder_path . '/' . $file_name . '.JPEG')) {
            // save image record to db, if it is saved well enough.
            $path_photo = $file_name . '.JPEG';
            $this->insertPhotoIntoAlbum($group_id, $album_id, $path_photo);
        }
    }

    public function insertDefaultAlbum($group_id, $photo_address) {
        $group = \DB::table('albums')
            ->select('album_id')
            ->where('owner_id', $group_id)
            ->where('owner_type', 'group')
            ->where('type', 'group-profile')
            ->first();

        // save image record to db, if it is saved well enough.
        if (!$group) {
            return $album_id = DB::table('albums')->insertGetId(
                [
                    'title'       => 'Group',
                    'description' => 'Group default profile album',
                    'owner_type'  => 'group',
                    'owner_id'    => $group_id,
                    'category_id' => 0,
                    'type'        => 'group-profile',
                    'photo_id'    => 0,
                ]
            );
        } else {
            return $group->album_id;
        }
    }

    public function insertPhotoIntoAlbum($group_id, $album_id, $file_id) {
        return $photo_id = DB::table('album_photos')->insertGetId(
            [
                'album_id'    => $album_id,
                'title'       => 'Group default profile photo',
                'description' => 'Group default profile photo',
                'owner_type'  => 'group',
                'owner_id'    => $group_id,
                'file_id'     => $file_id,
                'photo_id'    => 0,
            ]
        );
    }

    public function getDetails($group, $user_id) {
        $creator = User::find($group->creator_id);
        if (!empty($creator)) {
            $group['creator_name'] = $creator->displayname;
            $group['creator_url']  = $creator->username;
        } else {
            $group['creator_name'] = '';
            $group['creator_url']  = '';
        }

        $cat = DB::table('group_categories')->where('category_id', $group->category_id)->first();
        if (!empty($cat)) {
            $group['category_name'] = $cat->title;
        } else {
            $group['category_name'] = '';
        }
        $group['profile_photo_url'] = \Kinnect2::getPhotoUrl($group->photo_id, $group->id, 'group', 'group_thumb');
        $group['cover_photo_url']   = $this->getCoverPhoto($group->cover_photo_id);
        $group['is_joined']         = (\Kinnect2::isFollowingGroup($group->id, $user_id) != 0 ? 'yes' : 'no');
        $group['event_count']       = \Kinnect2::countEvents('group', $group->id);
        $group['member_count']       = \Kinnect2::followers($group->id);
        $group['privacy'] = $this->_get_privacy($group->id);


        return $group;
    }

    private function _get_privacy($id) {
        $privacy                        = AuthorizationAllow::whereResourceId($id)->whereResourceType('group')->lists('permission', 'action');
        $privacyR['view_privacy']    = '';
        $privacyR['comment_privacy'] = '';
        $privacyR['post_privacy'] = '';
        $privacyR['privacy_event_creation'] = '';
        if (!empty($privacy)) {
            if (isset($privacy['view'])) {
                $privacyR['view_privacy'] = \Config::get('constants.PERMISSION.' . $privacy['view']);
            }
            if (isset($privacy['comment'])) {
                $privacyR['comment_privacy'] = \Config::get('constants.PERMISSION.' . $privacy['comment']);
            }
            if (isset($privacy['group_post_privacy'])) {
                $privacyR['post_privacy'] = \Config::get('constants.PERMISSION.' . $privacy['group_post_privacy']);
            }
            if (isset($privacy['group_event_create'])) {
                $privacyR['privacy_event_creation'] = \Config::get('constants.PERMISSION.' . $privacy['group_event_create']);
            }

        }
        return $privacyR;
    }
    public function getGroupMembers($group_id) {
        $members = DB::table('group_membership')
                ->where('group_id', $group_id)
                ->where('user_approved', 1)
                ->where('group_owner_approved', 1)
                ->orderBy('created_at','DESC')
                ->get();

        return $members;
    }
    public function getGroupMembersByKey($group_id) {
        $members = GroupMembership::where('group_id', $group_id)->where('user_approved', 1)->where('group_owner_approved', 1)->lists('user_id');

        return $members;
    }
    public function findCreator($group_id) {
        $creator = DB::table('groups')->where('id', $group_id)->first();

        return $creator;
    }

    public function removeMember($group_id, $user_id) {
        DB::table('group_membership')->where('group_id', $group_id)->where('user_id', $user_id)->delete();

        return '1';
    }

    public function PendingApprovalRequests($group_id, $group) {
        if ($group->approval_required == 1) {
            $members = DB::table('group_membership')->where('group_id', $group_id)->where('user_approved', 1)->where('group_owner_approved', 0)->get();

            return $members;
        }

        return [];
    }

    public function ApproveGroupRequest($group_id, $member_id, $owner_id) {

        GroupMembership::whereGroupId($group_id)
            ->whereUserId($member_id)
            ->update([
                'group_owner_approved' => 1,
                'user_approved'        => 1,
                'active'               => 1,
            ]);
        if ($member_id != $owner_id) {
            $attributes = array(
                'resource_id' => $member_id,
                'subject_id'  => $owner_id,
                'object_id'   => $group_id,
                'object_type' => \Config::get('constants_activity.OBJECT_TYPES.GROUP.NAME'),
                'type'        => \Config::get('constants_activity.OBJECT_TYPES.GROUP.ACTIONS.APPROVED_REQUEST'),
            );

            \Event::fire(new CreateNotification($attributes));
        }
        $options = array(
            'object_type' => $this->activity_type,
            'type'        => \Config::get('constants_activity.OBJECT_TYPES.GROUP.ACTIONS.JOIN'),
            'subject'     => $member_id,
            'object'      => $group_id,

        );
        \Event::fire(new ActivityLog($options));

        return '1';
    }

    public function RejectGroupRequest($group_id, $member_id, $owner_id) {

        GroupMembership::whereGroupId($group_id)
            ->whereUserId($member_id)
            ->delete();
        $group = Group::findOrNew($group_id);
        //echo $member_id .'-'. $owner_id .'-'. $owner_id .'-'. $group->creator_id; die;
        if ($group->creator_id == $owner_id) {

            ActivityNotification::whereResourceId($member_id)
                ->whereSubjectId($owner_id)
                ->whereObjectId($group_id)
                ->whereType(\Config::get('constants_activity.OBJECT_TYPES.GROUP.ACTIONS.INVITATION_SENT'))
                ->delete();
            $attributes = array(
                'resource_id' => $member_id,
                'subject_id'  => $owner_id,
                'object_id'   => $group_id,
                'object_type' => \Config::get('constants_activity.OBJECT_TYPES.GROUP.NAME'),
                'type'        => \Config::get('constants_activity.OBJECT_TYPES.GROUP.ACTIONS.REJECTED_REQUEST'),
            );
        } else {
            $attributes = array(
                'resource_id' => $group->creator_id,
                'subject_id'  => $owner_id,
                'object_id'   => $group_id,
                'object_type' => \Config::get('constants_activity.OBJECT_TYPES.GROUP.NAME'),
                'type'        => \Config::get('constants_activity.OBJECT_TYPES.GROUP.ACTIONS.REJECTED_REQUEST'),
            );
        }

        \Event::fire(new CreateNotification($attributes));

    }

    public function invitesEntries($invites, $group_id, $user_id) {

        foreach ($invites as $key => $invite) {
            $check = DB::table('group_membership')->where('group_id', $group_id)->where('user_id', $invites[$key])->first();
            if ($check == []) {
                $this->group_invitation($group_id, $invites[$key], $user_id);
            } else {
                DB::table('group_membership')->where('group_id', $group_id)->where('user_id', $invites[$key])->update([
                    'group_owner_approved' => 1,
                    'active'               => 1,
                ]);
            }

        }

        return '1';
    }

    public function notFollowingFriedns($friends, $group_id) {
        $list = array();
        foreach ($friends as $friend) {
            if (Kinnect2::isFollowingGroup($group_id, $friend->user_id) == 0) {
                if (DB::table('group_membership')->where('group_id', $group_id)->where('user_id', $friend->user_id)->where('group_owner_approved', 1)->where('user_approved', 0)->first() == []) {
                    if (DB::table('group_membership')->where('group_id', $group_id)->where('user_id', $friend->user_id)->where('group_owner_approved', 0)->where('user_approved', 1)->first() == []) {
                        $list[] = $friend;
                    }
                }
            }
        }

        return $list;
    }

    public function catGroup($category_id) {
        return (DB::table('group_categories')->where('category_id', $category_id)->first());
    }

    public function PendingInvites($group_id) {
        $members = DB::table('group_membership')->where('group_id', $group_id)->where('user_approved', 0)->where('group_owner_approved', 1)->get();
        if ($members == []) {
            return [];
        }

        return $members;
    }

    public function MakeGroupManager($group_id, $member_id, $owner_id) {

        GroupMembership::whereGroupId($group_id)
            ->whereUserId($member_id)
            ->where('group_owner_approved', 1)
            ->where('user_approved', 1)
            ->update([
                'is_moderator' => 1,
            ]);

        if ($member_id != $owner_id) {
            $attributes = array(
                'resource_id' => $member_id,
                'subject_id'  => $owner_id,
                'object_id'   => $group_id,
                'object_type' => \Config::get('constants_activity.OBJECT_TYPES.GROUP.NAME'),
                'type'        => \Config::get('constants_activity.OBJECT_TYPES.GROUP.ACTIONS.GROUP_MANAGER'),
            );

            \Event::fire(new CreateNotification($attributes));
        }
//        $options = array(
//            'object_type' => $this->activity_type,
//            'type'        => \Config::get( 'constants_activity.OBJECT_TYPES.GROUP.ACTIONS.JOIN' ),
//            'subject'     => $member_id,
//            'object'      => $group_id,
//
//        );
//        \Event::fire( new ActivityLog( $options ) );

        return '1';
    }

    public function DemoteGroupManager($group_id, $member_id, $owner_id) {

        GroupMembership::whereGroupId($group_id)
            ->whereUserId($member_id)
            ->where('group_owner_approved', 1)
            ->where('user_approved', 1)
            ->update([
                'is_moderator'            => 0,
                'user_approved_moderator' => 0,
            ]);
//        if ( $member_id != $owner_id ) {
//            $attributes = array(
//                'resource_id' => $member_id,
//                'subject_id'  => $owner_id,
//                'object_id'   => $group_id,
//                'object_type' => \Config::get( 'constants_activity.OBJECT_TYPES.GROUP.NAME' ),
//                'type'        => \Config::get( 'constants_activity.OBJECT_TYPES.GROUP.ACTIONS.APPROVED_REQUEST' ),
//            );
//
//            \Event::fire( new CreateNotification( $attributes ) );
//        }
//        $options = array(
//            'object_type' => $this->activity_type,
//            'type'        => \Config::get( 'constants_activity.OBJECT_TYPES.GROUP.ACTIONS.JOIN' ),
//            'subject'     => $member_id,
//            'object'      => $group_id,
//
//        );
//        \Event::fire( new ActivityLog( $options ) );

        return '1';
    }

    public function ApproveGroupManagerReq($group_id, $member_id, $owner_id) {

        GroupMembership::whereGroupId($group_id)
            ->whereUserId($member_id)
            ->where('group_owner_approved', 1)
            ->where('user_approved', 1)
            ->where('is_moderator', 1)
            ->update([
                'user_approved_moderator' => 1,
            ]);
//        if ( $member_id != $owner_id ) {
//            $attributes = array(
//                'resource_id' => $member_id,
//                'subject_id'  => $owner_id,
//                'object_id'   => $group_id,
//                'object_type' => \Config::get( 'constants_activity.OBJECT_TYPES.GROUP.NAME' ),
//                'type'        => \Config::get( 'constants_activity.OBJECT_TYPES.GROUP.ACTIONS.APPROVED_REQUEST' ),
//            );
//
//            \Event::fire( new CreateNotification( $attributes ) );
//        }
//        $options = array(
//            'object_type' => $this->activity_type,
//            'type'        => \Config::get( 'constants_activity.OBJECT_TYPES.GROUP.ACTIONS.JOIN' ),
//            'subject'     => $member_id,
//            'object'      => $group_id,
//
//        );
//        \Event::fire( new ActivityLog( $options ) );

        return '1';
    }

    public static function LeaveGroupManagership($group_id, $user_id) {
        DB::table('group_membership')
            ->where('group_id', '=', $group_id)
            ->where('user_id', '=', $user_id)
            ->update([
                'is_moderator'            => 0,
                'user_approved_moderator' => 0,
            ]);

        return '1';
    }

    public function getCoverPhoto($id) {
        $path = asset('/local/public/assets/images/defaults/default_group_cover.jpg');
        if($this->is_api){
          $path = '';
        }

        if (!empty($id)) {

            //$photo = AlbumPhoto::find($data->cover_photo_id);

            $file_id = $id;


            $file = StorageFile::whereFileId($file_id)->select(['storage_path'])->first();


            //if(isset($photo->file_id)){
            $file_id = $id;

            $file = StorageFile::find($file_id);

            $path = \Config::get('constants_activity.PHOTO_URL') . $file->storage_path;
            //}

            $path = \Config::get('constants_activity.PHOTO_URL') . @$file->storage_path;


        }

        return $path;
    }

    private function send_invitation($group_id, $user_id, $owner_id) {

       $check_request =  $this->check_is_request_sent($group_id, $user_id);
        if($check_request){
            return $this->group_invitation($group_id, $user_id, $owner_id);
        }
        return TRUE;
    }

    private function check_is_request_sent($group_id, $user_id) {
        $check_request = GroupMembership::whereGroupId($group_id)->whereUserId($user_id)->count();

        if($check_request > 0 ){
            return FALSE;
        }
        return TRUE;
    }

}
