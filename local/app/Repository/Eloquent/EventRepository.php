<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/16/2015
 * Time: 6:27 PM
 */

namespace App\Repository\Eloquent;

use App\Album;
use App\AlbumPhoto;
use App\AuthorizationAllow;
use App\Classes\UrlFilter;
use App\Event;
use App\Classes\Kinnect2;
use App\EventMembership;
use App\Events\ActivityDelete;
use App\Events\ActivityLog;
use App\Events\CreateNotification;
use App\Group;
use App\StorageFile;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use App\User;
use Auth;
use Config;
use DB;
use Intervention\Image\Facades\Image;

class EventRepository extends Repository
{
    protected $event;
    protected $data;
    protected $user_id;
    protected $is_api;
    private   $activity_type;
    /**
     * @var EventMembership
     */
    private $eventMembership;

    public function __construct(Event $event, EventMembership $eventMembership) {
        parent::__construct();
        $this->event = $event;

        $this->activity_type   = \Config::get('constants_activity.OBJECT_TYPES.EVENT.NAME');
        $this->eventMembership = $eventMembership;
    }

    public function createEvent($input, $user_id) {
        $event                    = new Event();
        $event->user_id           = $input['user_id'];
        $event->title             = $input['title'];
        $event->description       = $input['description'];
        $event->parent_type       = $input['parent_type'];
        $event->parent_id         = $input['parent_id'];
        $event->starttime         = $input['starttime'];
        $event->endtime           = $input['endtime'];
        $event->host              = $input['host'];
        $event->location          = $input['location'];
        $event->approval_required = $input['approval_required'];//rsvp
        $event->member_can_invite = $input['member_can_invite'];
        $event->photo_id          = 1; //$input['photo_id'];
        $event->title             = $input['title'];
        $event->category_id       = $input['category'];

        $event->save($input);

        $responses = \Event::fire('event.created');

        $owner = DB::table('groups')->where('id', $input['parent_id'])->first();
        $a     = $this->OwnerAttending($user_id, $event->id, $owner->creator_id);

        \AuthorizationAllowClassFacade::Setting('event', $event->id, $input['view_privacy'], 'view');
        \AuthorizationAllowClassFacade::Setting('event', $event->id, $input['comment_privacy'], 'comment');
        \AuthorizationAllowClassFacade::Setting('event', $event->id, $input['privacy_photo_upload'], 'photo_upload');
        $target_type = '';
        $target_id   = '';

        if($input['parent_type'] == 'group') {
            $target_type = 'group';
            $target_id   = $input['parent_id'];
        }
        $options = array(
            'object_type' => $this->activity_type,
            'type'        => \Config::get('constants_activity.OBJECT_TYPES.EVENT.ACTIONS.CREATE_EVENT'),
            'subject'     => $user_id,
            'object'      => $event->id,
            'target_type' => $target_type,
            'target_id'   => $target_id,
        );

        \Event::fire(new ActivityLog($options));

        return $event->id;
    }

    public function OwnerAttending($guest_id, $event_id, $owner_id) {

        $eventMembership                 = new EventMembership();
        $eventMembership->event_id       = $event_id;
        $eventMembership->user_approved  = 1;
        $eventMembership->event_approved = 1;
        $eventMembership->active         = 1;
        $eventMembership->rsvp           = 1;
        $eventMembership->user_id        = $guest_id;
        $eventMembership->save();
        if($guest_id != $owner_id) {
            $eventMembership                 = new EventMembership();
            $eventMembership->event_id       = $event_id;
            $eventMembership->user_approved  = 1;
            $eventMembership->event_approved = 1;
            $eventMembership->active         = 1;
            $eventMembership->rsvp           = 1;
            $eventMembership->user_id        = $owner_id;
            $eventMembership->save();
        }

        return 1;
    }

    public function update($requestFormData, $event) {

        if($event->id > 0) {
            $approval_required = (isset($requestFormData->approval_required) ? $requestFormData->approval_required : 0);
            $member_can_invite = (isset($requestFormData->member_can_invite) ? $requestFormData->member_can_invite : 0);
            //2015-11-18 16:19:57
            $hour = (isset($requestFormData->start_time_hour) ? $requestFormData->start_time_hour : '00');
            if($requestFormData->start_time_am_pm == 'pm') {
                $hour = $hour + 12;
            }
            $minutes   = (isset($requestFormData->start_time_minutes) ? $requestFormData->start_time_minutes : '00');
            $starttime = $requestFormData->start_date . ' ' . $hour . ':' . $minutes . ':00';

            $hour = (isset($requestFormData->end_time_hour) ? $requestFormData->end_time_hour : '00');
            if($requestFormData->end_time_am_pm == 'pm') {
                $hour = $hour + 12;
            }
            $minutes = (isset($requestFormData->end_time_minutes) ? $requestFormData->end_time_minutes : '00');
            $endtime = $requestFormData->end_date . ' ' . $hour . ':' . $minutes . ':00';

            $event->title       = $requestFormData->title;
            $event->description = $requestFormData->description;
            //$event->parent_type           = $requestFormData->parent_type;
            //$event->parent_id             = $requestFormData->parent_id;
            $event->starttime         = $starttime;
            $event->endtime           = $endtime;
            $event->host              = $requestFormData->host;
            $event->location          = $requestFormData->location;
            $event->approval_required = $approval_required;
            $event->member_can_invite = $member_can_invite;
            //$event->photo_id              = $requestFormData->photo_id;
            $event->category_id = $requestFormData->category;
            /* $event->view_privacy       = $requestFormData->view_privacy;
             $event->comment_privacy      = $requestFormData->comment_privacy;
             $event->privacy_photo_upload = $requestFormData->privacy_photo_upload;*/
            $event->update();

            \AuthorizationAllowClassFacade::changeSetting('event', $event->id, $requestFormData['view_privacy'], 'view');
            \AuthorizationAllowClassFacade::changeSetting('event', $event->id, $requestFormData['comment_privacy'], 'comment');
            \AuthorizationAllowClassFacade::changeSetting('event', $event->id, $requestFormData['privacy_photo_upload'], 'photo_upload');

            return $event->id;
        }

        return 0;
    }

    public function groupEvents($group_id) {
        return $group_events = Event::where('parent_id', $group_id)
                                    ->orderBy("id")
                                    ->get();
    }

    public function allCategories() {
        return DB::table('event_categories')->lists('title', 'id');
    }

    public function eventProfileInfo($event_id) {
        $event = Event::find($event_id);

        $data['isEventOwner']         = $this->isEventOwner($event);
        $data['totalPendingRequests'] = $this->eventPendingRequests($event_id);

        return $data;
    }

    public function isEventOwner($event) {
        if($this->user_id == $event->user_id) {
            return 1;
        }

        return 0;
    }

    public function eventPendingRequests($event_id) {
        $user_ids = EventMembership::where('event_id', $event_id)
                                   ->where('user_approved', 1)
                                   ->where('event_approved', 0)
                                   ->where('active', 0)
                                   ->lists('user_id');

        return $user_ids;
    }

    public function find($event_id) {
        return Event::find($event_id);
    }

    public function eventMaybeAttendingMembers($event_id) {
        $usersIds = EventMembership::where('event_id', $event_id)
                                   ->where('user_approved', 1)
                                   ->where('event_approved', 1)
                                   ->where('active', 1)
                                   ->where('rsvp', 2)
                                   ->lists('user_id');
        if(count($usersIds) > 0) {
            return User::whereIn('id', $usersIds)
                       ->orderByRaw("RAND()")->take(100)->get();
        }

        return [];
    }

    public function eventAllMembers($event_id) {
        $usersIds = EventMembership::where('event_id', $event_id)
                                   ->where('user_approved', 1)
                                   ->where('event_approved', 1)
                                   ->where('active', 1)
                                   ->lists('user_id');
        if(count($usersIds) > 0) {
            return User::whereIn('id', $usersIds)
                       ->get();
        }

        return [];
    }

    public function eventAttendingMembers($event_id) {
        $usersIds = EventMembership::where('event_id', $event_id)
                                   ->where('user_approved', 1)
                                   ->where('event_approved', 1)
                                   ->where('active', 1)
                                   ->where('rsvp', 1)
                                   ->lists('user_id');
        if(count($usersIds) > 0) {
            return User::whereIn('id', $usersIds)
                       ->orderByRaw("RAND()")->take(100)->get();
        }

        return [];
    }

    public function eventWaitingMembers($event_id) {
        $usersIds = EventMembership::where('event_id', $event_id)
                                   ->where('user_approved', 1)
                                   ->where('event_approved', 0)
                                   ->lists('user_id');
        if(count($usersIds) > 0) {
            return User::whereIn('id', $usersIds)
                       ->orderByRaw("RAND()")->take(100)->get();
        }

        return [];
    }

    public function eventNotAttendingMembers($event_id) {
        $usersIds = EventMembership::where('event_id', $event_id)
                                   ->where('user_approved', 1)
                                   ->where('event_approved', 1)
                                   ->where('active', 1)
                                   ->where('rsvp', 3)
                                   ->lists('user_id');
        if(count($usersIds) > 0) {
            return User::whereIn('id', $usersIds)
                       ->orderByRaw("RAND()")->take(100)->get();
        }

        return [];
    }

    public function isAttending($guest_id, $event_id) {
        return DB::table('event_memberships')
                 ->where('event_id', $event_id)
                 ->where('user_id', $guest_id)
                 ->count();
    }

    public function attending($guest_id, $rsvp, $event_id) {
        $event = DB::table('events')->where('id', $event_id)->first();
        if($event->approval_required == 1) {
            $this->eventMembership->event_id       = $event_id;
            $this->eventMembership->user_approved  = 1;
            $this->eventMembership->event_approved = 0;
            $this->eventMembership->active         = 1;
            $this->eventMembership->rsvp           = $rsvp;
            $this->eventMembership->user_id        = $guest_id;
            $this->eventMembership->save();

        } else {
            $this->eventMembership->event_id       = $event_id;
            $this->eventMembership->user_approved  = 1;
            $this->eventMembership->event_approved = 1;
            $this->eventMembership->active         = 1;
            $this->eventMembership->rsvp           = $rsvp;
            $this->eventMembership->user_id        = $guest_id;
            $this->eventMembership->save();
        }

        return 1;
    }

    public function deleteAttendRequest($guest_id, $event_id) {
        DB::table('event_memberships')
          ->where('event_id', $event_id)
          ->where('user_id', $guest_id)
          ->delete();
        $event      = Event::findOrFail($event_id);
        $attributes = array(
            'resource_id' => $event->user_id,
            'subject_id'  => $guest_id,
            'object_id'   => $event_id,
            'object_type' => \Config::get('constants_activity.OBJECT_TYPES.EVENT.NAME'),
            'type'        => \Config::get('constants_activity.OBJECT_TYPES.EVENT.ACTIONS.REJECTED_REQUEST'),
        );

        \Event::fire(new CreateNotification($attributes));

        return 1;
    }

    public function approveRequest($guest_id, $event_id) {
        if(EventMembership::where('event_id', $event_id)
                          ->where('user_id', $guest_id)
                          ->update(['rsvp' => 1, 'user_approved' => 1, 'event_approved' => 1, 'active' => 1])
        ) {
            return 1;
        }

        return 0;
    }

    public function cancelRequest($guest_id, $event_id) {
        if(EventMembership::where('event_id', $event_id)
                          ->where('user_id', $guest_id)
                          ->delete()
        ) {

            return 1;
        }

        return 0;
    }

    public function event_Memberships($event_id) {
        return EventMembership::whereEventId($event_id)->get();
    }

    public function deleteEvent($event_id = NULL, $user_id) {
        if($event_id > 0) {
            Event::destroy($event_id);
            $memberships = $this->event_Memberships($event_id);
            foreach ($memberships as $membership) {
                $membership->delete();
            }
            $params = [
                'subject_id'  => $user_id,
                'object_id'   => $event_id,
                'object_type' => \Config::get('constants_activity.OBJECT_TYPES.EVENT.NAME'),
            ];
            \Event::fire(new ActivityDelete($params));
        }
    }

    public function updateAttending($user_id, $rsvp, $event_id) {
        $this->eventMembership->whereEventId($event_id)->whereUserId($user_id)->update(['rsvp' => $rsvp]);

        /*DB::table('event_memberships')
            ->where('event_id', '=', $event_id)
            ->where('user_id',  '=', $user_id)
            ->update(['rsvp' => $rsvp]);*/

        return '1';
    }

    public function uploadingPhotos($tmp_file_path, $event_id) {
        $event       = Event::find($event_id);
        $folder_path = public_path('events/event_' . $event_id);
        if(!file_exists($folder_path)) {
            if(!mkdir($folder_path, 0777, TRUE)) {
                $folder_path = '';
            }
        }

        $file_name = time() . rand(111111111, 9999999999);
        $image1    = Image::make($tmp_file_path);
        $image1->resize(Config::get('constants.EVENT_PROFILE_WIDTH'), Config::get('constants.EVENT_PROFILE_HEIGHT'));

        if($image1->save($folder_path . '/' . $file_name . '.JPEG')) {
            // save image record to db, if it is saved well enough.
            $path_photo      = $file_name . '.JPEG';
            $album_id        = $this->insertDefaultAlbum($event_id, $path_photo);
            $photo_id        = $this->insertPhotoIntoAlbum($event_id, $album_id, $path_photo);
            $event->photo_id = $photo_id;
            $event->save();
        }

        $file_name = time() . rand(111111111, 9999999999);
        $image2    = Image::make($tmp_file_path);
        $image2->resize(Config::get('constants.WALL_IMAGE_WIDTH'), Config::get('constants.WALL_IMAGE_HEIGHT'));

        if($image2->save($folder_path . '/' . $file_name . '.JPEG')) {
            // save image record to db, if it is saved well enough.
            $path_photo = $file_name . '.JPEG';
            $this->insertPhotoIntoAlbum($event_id, $album_id, $path_photo);
        }
    }

    public function insertDefaultAlbum($event_id, $photo_address = NULL) {
        $event = \DB::table('albums')
                    ->select('album_id')
                    ->where('owner_id', $event_id)
                    ->where('owner_type', 'event')
                    ->where('type', 'event-profile')
                    ->first();

        // save image record to db, if it is saved well enough.
        if(!$event) {
            $album              = new Album();
            $album->title       = 'Event';
            $album->description = 'Event default profile album';
            $album->owner_type  = 'event';
            $album->owner_id    = $event_id;
            $album->category_id = 0;
            $album->type        = 'event-profile';
            $album->photo_id    = 0;
            $album->save();

            return $album->album_id;
            /*return $album_id = DB::table('albums')->insertGetId(
                [
                    'title' => 'Event',
                    'description' => 'Event default profile album',
                    'owner_type' => 'event',
                    'owner_id' => $event_id,
                    'category_id' => 0,
                    'type' => 'event-profile',
                    'photo_id' => 0
                ]
            );*/
        } else {
            return $event->album_id;
        }
    }

    public function insertPhotoIntoAlbum($event_id, $album_id, $path_photo) {
        $albumPhoto              = new AlbumPhoto();
        $albumPhoto->album_id    = $album_id;
        $albumPhoto->title       = 'Event default profile photo';
        $albumPhoto->description = $path_photo;
        $albumPhoto->owner_type  = 'event';
        $albumPhoto->owner_id    = $event_id;
        // $albumPhoto->file_id = $file_id;
        $albumPhoto->photo_id = 0;
        $albumPhoto->save();

        return $albumPhoto->photo_id;
        /*return $photo_id = DB::table('album_photos')->insertGetId(
            ['album_id' => $album_id,
                'title' => 'Event default profile photo',
                'description' => $path_photo,
                'owner_type' => 'event',
                'owner_id' => $event_id,
                //'file_id' => $file_id,
                'photo_id' => 0
            ]
        );*/
    }

    public function getDefaultAlbum($id, $type) {
        // save image record to db, if it is saved well enough.
        // return AlbumPhoto::where('photo_id', $photo_id)->first(['album_id']);
        return Album::whereOwnerType($type)->whereOwnerId($id)->first(['album_id']);
    }

    public function get_details($event, $group = NULL) {
        $user                  = User::findOrNew($event->user_id);
        $event['creator_name'] = $user->displayname;
        $event['creator_url']  = $user->username;
        if($group) {
            $event['parent_name'] = $group->title;
        } else {
            if($event->parent_type == 'group') {
                $parent               = Group::findOrNew($event->parent_id);
                $event['parent_name'] = $parent->title;
            }
        }

        $category               = DB::table('event_categories')->where('id', $event->category_id)->select('title')->first();
        $event['category_name'] = '';
        if($category) {
            $event['category_name'] = $category->title;
        }

        $event['photo_url'] = \Kinnect2::getPhotoUrl($event->photo_id, $event->id, 'event', 'event_thumb');
        $event['guests']    = \Kinnect2::countEventGuest($event->id);
        $event['privacy']   = $this->_get_privacy($event->id);
        return $event;

    }

    private function _get_privacy($id) {
        $privacy                          = AuthorizationAllow::whereResourceId($id)->whereResourceType('event')->lists('permission', 'action');
        $privacyR['view_privacy']         = '';
        $privacyR['comment_privacy']      = '';
        $privacyR['privacy_photo_upload'] = '';
        if(!empty($privacy)) {
            if(isset($privacy['view'])) {
                $privacyR['view_privacy'] = \Config::get('constants.PERMISSION.' . $privacy['view']);
            }
            if(isset($privacy['comment'])) {
                $privacyR['comment_privacy'] = \Config::get('constants.PERMISSION.' . $privacy['comment']);
            }
            if(isset($privacy['photo_upload'])) {
                $privacyR['privacy_photo_upload'] = \Config::get('constants.PERMISSION.' . $privacy['photo_upload']);
            }

        }

        return $privacyR;

    }

    public function notFollowingFriends($friends, $event_id) {
        $list = array();
        foreach ($friends as $friend) {
            if(Kinnect2::isFollowingEvent($event_id, $friend->user_id) == 0) {
                if(DB::table('event_memberships')->where('event_id', $event_id)->where('user_id', $friend->user_id)->where('event_approved', 1)->where('user_approved', 0)->first() == []) {
                    if(DB::table('event_memberships')->where('event_id', $event_id)->where('user_id', $friend->user_id)->where('event_approved', 0)->where('user_approved', 1)->first() == []) {
                        $list[] = $friend;
                    }
                }
            }
        }

        return $list;
    }

    public function invitesEntries($invites, $event_id, $user_id) {
        foreach ($invites as $key => $invite) {

            $check = DB::table('event_memberships')->where('event_id', $event_id)->where('user_id', $invites[$key])->first();
            if($check == []) {

                $this->event_invitation($event_id, $invites[$key], $user_id);

            } else {
                $eventMembership = EventMembership::whereEventId($event_id)->whereUserId($invites[$key])->first();

                $eventMembership->event_approved = 1;
                $eventMembership->active         = 1;
                $eventMembership->save();
            }

        }

        return '1';
    }

    public function event_invitation($event_id, $user_id, $owner_id) {

        $event_membership                 = new EventMembership();
        $event_membership->event_id       = $event_id;
        $event_membership->active         = 1;
        $event_membership->event_approved = 1;
        $event_membership->user_approved  = 0;
        $event_membership->user_id        = $user_id;
        $event_membership->save();

        $attributes = array(
            'resource_id' => $user_id,
            'subject_id'  => $owner_id,
            'object_id'   => $event_id,
            'object_type' => \Config::get('constants_activity.OBJECT_TYPES.EVENT.NAME'),
            'type'        => \Config::get('constants_activity.OBJECT_TYPES.EVENT.ACTIONS.INVITATION_SENT'),
        );

        \Event::fire(new CreateNotification($attributes));

        return TRUE;
    }

    public function ApproveInvite($user_id, $rsvp, $event_id) {
        EventMembership::whereEventId($event_id)
                       ->whereUserId($user_id)
                       ->update([
                           'event_approved' => 1,
                           'user_approved'  => 1,
                           'active'         => 1,
                           'rsvp'           => 1,
                       ]);

        $event      = Event::findOrFail($event_id);
        $attributes = array(
            'resource_id' => $event->user_id,
            'subject_id'  => $user_id,
            'object_id'   => $event_id,
            'object_type' => \Config::get('constants_activity.OBJECT_TYPES.EVENT.NAME'),
            'type'        => \Config::get('constants_activity.OBJECT_TYPES.EVENT.ACTIONS.APPROVED_REQUEST'),
        );

        \Event::fire(new CreateNotification($attributes));
    }

    public function get_photo($event_id) {
        return Event::whereId($event_id)->with(['Album' => function ($query) {
            $query->where('albums.owner_type', 'event');
            $query->with('AlbumPhotos.storage_file');
        },
        ])->first();
        //echo '<tt><pre>'; print_r($data);
    }

    public function eventInvitesSentToMembers($event_id) {
        $usersIds = EventMembership::where('event_id', $event_id)
                                   ->where('user_approved', 0)
                                   ->where('event_approved', 1)
                                   ->where('active', 1)
                                   ->where('rsvp', 0)
                                   ->lists('user_id');
        if(count($usersIds) > 0) {
            return User::whereIn('id', $usersIds)
                       ->orderByRaw("RAND()")->take(100)->get();
        }

        return [];
    }

    public function _get_event_detail($data) {
        $user                = User::findOrFail($data->user_id);
        $data['creator_id']  = $user->displayname;
        $data['creator_url'] = $user->username;

        $category         = DB::table('event_categories')->where('id', $data->category_id)->first();
        $data['category'] = $category->title;

        $photo = AlbumPhoto::wherePhotoId($data->photo_id)->with('storage_file')->first();
        if(!empty($photo)) {
            $data['photo_url'] = $photo->storage_path;
            $data['mime_type'] = $photo->mime_type;
        }

        return $data;
    }

    public function removeMemberFromGust($event_id, $user_id) {
        $eventMemberShip = EventMembership::whereUserId($user_id)->whereEventId($event_id)->delete();
    }
}
