<?php

namespace App\Http\Controllers;

use App\AlbumPhoto;
use App\Event;
use App\Facades\Kinnect2;
use App\Group;
use App\Repository\Eloquent\AlbumRepository;
use App\Repository\Eloquent\GroupRepository;
use App\Repository\Eloquent\UsersRepository;
use App\Services\StorageManager;
use App\StorageFile;
use App\User;
use Auth;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repository\Eloquent\EventRepository;
use Image;
use Validator;

class EventController extends Controller
{
    protected $eventRepository;
    protected $data;
    private   $user_id;
    private   $is_api;
    /**
     * @var AlbumRepository
     */
    private $albumRepository;

    /**
     * @param EventRepository $eventRepository
     * @param Request $middleware
     */
    public function __construct(AlbumRepository $albumRepository, EventRepository $eventRepository, Request $middleware, UsersRepository $friend) {
        $this->eventRepository = $eventRepository;
        $this->user_id         = $middleware[ 'middleware' ][ 'user_id' ];
        @$this->data->user = $middleware[ 'middleware' ][ 'user' ];
        $this->is_api          = $middleware[ 'middleware' ][ 'is_api' ];
        $this->friend          = $friend;
        $this->albumRepository = $albumRepository;
    }

    public function index($event_id = NULL) {

        if($this->is_api) {
            $event_id = \Input::get('event_id');
            if(!$event_id) {
                return \Api::invalid_param();
            }
        }
        // gather required supplementary data
        //$data['eventInfo'] = $this->eventRepository->eventProfileInfo($event_id);
        $data[ 'event_photos' ] = $this->eventRepository->get_photo($event_id);
        //       echo '<tt><pre>'; print_r($data['event_photos']->Album->AlbumPhotos); die;
        $data[ 'event' ] = $this->eventRepository->find($event_id);
        if(empty($data[ 'event' ])) {
            return \Api::detail_not_found();
        }
        $privacy = is_allowed($event_id, 'event', 'view', $this->user_id, $data[ 'event' ]->user_id);
        if($privacy) {
            $data[ 'event' ]                      = $this->eventRepository->find($event_id);
            $data[ 'eventMaybeAttendingMembers' ] = $this->eventRepository->eventMaybeAttendingMembers($event_id);
            $data[ 'eventAttendingMembers' ]      = $this->eventRepository->eventAttendingMembers($event_id);
            $data[ 'eventWaitingMembers' ]        = $this->eventRepository->eventWaitingMembers($event_id);
            $data[ 'eventNotAttendingMembers' ]   = $this->eventRepository->eventNotAttendingMembers($event_id);
            $data[ 'eventInvitesSentToMembers' ]  = $this->eventRepository->eventInvitesSentToMembers($event_id);
            $friends                              = $this->Friendlist($data[ 'event' ], $event_id);
            $data[ 'friends' ]                    = $friends;

            if($this->is_api) {
                $response = [];

                $event                                       = $this->eventRepository->get_details($data[ 'event' ]);
                $response[ 'data' ]                          = $event;
                $response[ 'photos' ]                        = $this->_event_photos($data[ 'event_photos' ]);
                $response[ 'attending_members' ]             = $this->_members_details($data[ 'eventAttendingMembers' ]);
                $response[ 'attending_members_total' ]       = count($data[ 'eventAttendingMembers' ]);
                $response[ 'maybe_attending_members' ]       = $this->_members_details($data[ 'eventMaybeAttendingMembers' ]);
                $response[ 'maybe_attending_members_total' ] = count($data[ 'eventMaybeAttendingMembers' ]);
                $response[ 'awaiting_members' ]              = $this->_members_details($data[ 'eventWaitingMembers' ]);
                $response[ 'awaiting_members_total' ]        = count($data[ 'eventWaitingMembers' ]);
                $response[ 'not_attending_members' ]         = $this->_members_details($data[ 'eventNotAttendingMembers' ]);
                $response[ 'not_attending_members_total' ]   = count($data[ 'eventNotAttendingMembers' ]);
                $response[ 'invite_sent_members' ]           = $this->_members_details($data[ 'eventInvitesSentToMembers' ]);
                $response[ 'invite_sent_members_total' ]     = count($data[ 'eventInvitesSentToMembers' ]);
                $response[ 'friends_list' ]                  = $data[ 'friends' ];

                return \Api::success($response);
            }
            $data[ 'is_authorized' ] = TRUE;
        } else {
            if($this->is_api) {
                return \Api::access_denied();
            }
            $data[ 'is_authorized' ] = FALSE;
        }
        $data['title'] = 'Event Detail';
        return view('event.eventProfile', $data);
    }

    public function _event_photos($photos) {
        //return $photos;
        $all      = [];
        $allPhoto = [];
        foreach ($photos->album[ 'AlbumPhotos' ] as $photo) {
            $all[ 'photo_id' ] = $photo->photo_id;
            $all[ 'title' ]    = $photo->title;

            $path               = isset($photo->storage_file->storage_path) ? $photo->storage_file->storage_path : NULL;
            $all[ 'photo_url' ] = NULL;
            if(!empty($path)) {
                $all[ 'photo_url' ] = \Config::get('constants_activity.PHOTO_URL') . $path . '?type=' . urlencode($photo->storage_file->mime_type);
            }

            $allPhoto[] = $all;
        }

        return $allPhoto;
    }

    public function _members_details($members) {
        $all        = [];
        $allMembers = [];

        foreach ($members as $member) {
            $data                       = $this->friend->_get_user_meta($member);
            $all[ 'profile_photo_url' ] = $data[ 'profile_photo_url' ];
            $all[ 'cover_photo_url' ]   = $data[ 'cover_photo_url' ];
            $all[ 'displayname' ]       = $data[ 'displayname' ];
            $all[ 'profile_url' ]       = $data[ 'username' ];
            $allMembers[]               = $all;
        }

        return $allMembers;
    }

    public function Friendlist($data, $event_id) {

        $list    = $this->friend->friends($this->user_id);
        $friend  = $this->eventRepository->notFollowingFriends($list, $event_id);
        $friends = array();
        foreach ($friend as $a) {
            $friends[] = ['id' => $a->user_id, 'displayname' => $a->displayname, 'profile_pic' => \Kinnect2::getPhotoUrl($a->photo_id, $a->id, 'user', 'thumb_normal')];
        }

        return $friends;
    }

    public function approveRequest(Request $request) {
        $event_id = $request->event_id;

        if(!$event_id) {
            if($this->is_api) {
                return \Api::invalid_param();
            } else {
                return redirect()->back();
            }
        }
        $event = Event::find($request->event_id);
        if(!$event) {
            if($this->is_api) {
                return \Api::detail_not_found();
            } else {
                return 0;
            }
        }
        if(Kinnect2::isEventOwner($event) == TRUE) {
            $this->eventRepository->approveRequest($request->guest_id, $request->event_id);
            if($this->is_api) {
                return \Api::success_with_message();
            }

            return url('event/' . $request->event_id . '#tab-guests');
        } else {
            if($this->is_api) {
                return \Api::access_denied();
            } else {
                return 0;
            }
        }

        return 0;
    }

    public function cancelRequest(Request $request) {

        if(empty($request->event_id)) {
            if($this->is_api) {
                return \Api::invalid_param();
            }

            return 0;
        }
        $event = Event::find($request->event_id);
        if(\Kinnect2::isEventOwner($event) == TRUE) {
            $re = $this->eventRepository->cancelRequest($request->guest_id, $request->event_id, $this->user_id);
            if($this->is_api) {
                return \Api::success_with_message();
            }

            return $re;
        } else {
            if($this->is_api) {
                return \Api::access_denied();
            }
        }

        return 0;
    }

    public function removeMember($event_id = NULL, $user_id = NULL) {
        if($this->is_api) {
            if(!\Input::has('event_id') || !\Input::has('user_id')) {
                return \Api::invalid_param();
            }
            $event_id = \Input::get('event_id');
            $user_id  = \Input::get('user_id');
        }

        $event = Event::find($event_id);
        if(\Kinnect2::isEventOwner($event) == TRUE) {
            $this->eventRepository->removeMemberFromGust($event_id, $user_id);
        } else {
            if($this->is_api) {
                return \Api::access_denied();
            }
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function allMembers() {

    }

    public function events(Request $request) {
        return 'all events only';
    }

    /**
     * @param null $event_id
     *
     * @return $this
     */
    public function edit($event_id = NULL) {
        $data[ 'hours' ] = $this->hours();
        $minutes         = $this->minutes();
        if($this->is_api) {
            $event_id = \Input::get('event_id');
            if(!$event_id) {
                return \Api::invalid_param();
            }
        }

        $data[ 'now_date' ] = Carbon::now()->toDateString();

        $data[ 'event' ] = $this->eventRepository->find($event_id);

        if(empty($data[ 'event' ])) {
            if($this->is_api) {
                return \Api::detail_not_found();
            }

            return redirect('events')->withErrors(['not_found' => 'Nothing found.'], 'not_found');
        }
        if(\Kinnect2::isEventOwner($data[ 'event' ]) == FALSE) {
            if($this->is_api) {
                return \Api::access_denied();
            }

            return redirect('events')->withErrors(['not_allowed' => 'You are not allowed to visit this page.'], 'not_allowed');
        }

        $data[ 'categories' ] = $this->eventRepository->allCategories();
        if($this->is_api) {
            return \Api::success(['data' => $data[ 'event' ], 'categories' => $data[ 'categories' ], 'minutes' => $minutes,]);
        }

        return view('event.editEvent', $data)->with('minutes', $minutes);
    }

    public function hours() {
        return $hours = ['' => 'Hours', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12'];
    }

    public function minutes() {
        $minutes = [];

        for ($i = 0; $i <= 59; $i++) {
            if($i < 10) {
                $minutes[ $i ] = '0' . $i;
            } else {
                $minutes[ $i ] = $i;
            }
        }

        return $minutes;
    }

    public function update(Request $requestFormData, $event_id = NULL) {

        $photo_image_file_id = $requestFormData->saved_event_image_file_id;

        if($this->is_api) {
            $event_id = \Input::get('event_id');
            if(!$event_id) {
                return \Api::invalid_param();
            }
        }
        $event = Event::find($event_id);
        //if(\Gate::denies('update', $event)) {
        if($event->user_id != $this->user_id) {
            if($this->is_api) {
                return \Api::access_denied();
            }
            return redirect()->back();
        }
        $validator = Validator::make(['title' => $requestFormData->title, 'description' => $requestFormData->description,], ['title' => 'required', 'description' => 'required',]);

        if($validator->fails()) {
            if($this->is_api) {
                return \Api::invalid_param();
            }

            return redirect()->back()->withErrors($validator->errors());
        } else {

            $event_id = $this->eventRepository->update($requestFormData, $event);

            $event = Event::find($event_id);
            if($photo_image_file_id > 0 AND $event_id > 0) {
                if($event->photo_id > 0) {
                    $albumPhoto = $this->eventRepository->getDefaultAlbum($event_id, 'event');
                    $album_id   = $albumPhoto->album_id;
                } else {
                    $album_id = $this->eventRepository->insertDefaultAlbum($event->id);
                }

                if($photo_image_file_id > 0) {

                    $file = StorageFile::where('file_id', $photo_image_file_id)->first();

                    if(isset($file->file_id)) {
                        if(file_exists("local/storage/app/photos/" . $file->storage_path) == TRUE) {
                            $file_name     = time() . rand(111111111, 9999999999);
                            $folder_path   = "local/storage/app/photos/" . $event->id;
                            $file_name_new = $event->id . "_" . $file_name . "." . $file->extension;
                            if(!file_exists($folder_path)) {
                                if(!mkdir($folder_path, 0777, TRUE)) {
                                    $folder_path = '';
                                }
                            }
                            rename("local/storage/app/photos/" . $file->storage_path, $folder_path . "/" . $file_name_new);
                        }

                        $file->parent_id    = $event->id;//photo_id
                        $file->user_id      = $event->parent_id;
                        $file->storage_path = $event->id . "/" . $file_name_new;
                        $file->name         = $file_name;
                        $file->save();
                    }

                    $photo = new AlbumPhoto();

                    $photo->file_id     = $file->file_id;
                    $photo->album_id    = $album_id;
                    $photo->title       = 'event profile picture';
                    $photo->description = 'first event profile picture';
                    $photo->owner_type  = 'event';
                    $photo->owner_id    = $event_id;

                    $photo->save();

                    if($photo_image_file_id > 0 AND $event_id > 0) {
                        $event = Event::find($event_id);

                        $event->photo_id = $file->file_id;
                        $event->save();

                    }
                }

            }
            if($this->is_api) {
                return \Api::success(['data' => $this->eventRepository->get_details($event)]);
            }

            return redirect('event/' . $event_id);
        }
    }

    public function searchMembers($event_id, $search_value) {
        $eventMemebers = $this->eventRepository->eventAllMembers($event_id);

        foreach ($eventMemebers as $key => $eventMemeber) {
            $isUser = strpos(strtoupper($eventMemeber->displayname), strtoupper($search_value));
            if($isUser === FALSE) {
                unset($eventMemebers[ $key ]);
            }
        }

        return json_encode($eventMemebers);
    }

    public function getCreateEvent(Request $groupParams) {

        $data[ 'hours' ] = $this->hours();
        $minutes         = $this->minutes();

        $data[ 'parent_type' ] = $groupParams->parent_type;
        $data[ 'parent_id' ]   = $groupParams->parent_id;
        $data[ 'now_date' ]    = Carbon::now()->toDateString();

        $data[ 'categories' ] = $this->eventRepository->allCategories();
        if($this->is_api) {

            return \Api::success(['data' => $data[ 'categories' ], 'minutes' => $minutes,]);
        }

        return view('event.createEvent', $data)->with('minutes', $minutes);

    }

    public function create(Request $requestFormData) {
        $photo_id_file = $requestFormData->saved_event_image_file_id;

        $approval_required = (isset($requestFormData->approval_required) ? $requestFormData->approval_required : 0);
        $member_can_invite = (isset($requestFormData->member_can_invite) ? $requestFormData->member_can_invite : 0);
        //2015-11-18 16:19:57
        $hour = (isset($requestFormData->start_time_hour) ? $requestFormData->start_time_hour : '00');
        if($requestFormData->start_time_am_pm == 'pm') {
            $hour = $hour + 12;
        }

        $minutes   = isset($requestFormData->start_time_minutes) ? $minutes = $requestFormData->start_time_minutes : '00';
        $starttime = $requestFormData->start_date . ' ' . $hour . ':' . $minutes . ':00';

        $hour = (isset($requestFormData->end_time_hour) ? $requestFormData->end_time_hour : '00');
        if($requestFormData->end_time_am_pm == 'pm') {
            $hour = $hour + 12;
        }

        $minutes = isset($requestFormData->end_time_minutes) ? $minutes = $requestFormData->end_time_minutes : '00';
        $endtime = $requestFormData->end_date . ' ' . $hour . ':' . $minutes . ':00';

        $input[ 'user_id' ]              = $this->user_id;
        $input[ 'title' ]                = $requestFormData->title;
        $input[ 'description' ]          = $requestFormData->description;
        $input[ 'parent_type' ]          = $requestFormData->parent_type;
        $input[ 'parent_id' ]            = $requestFormData->parent_id;
        $input[ 'starttime' ]            = $starttime;
        $input[ 'endtime' ]              = $endtime;
        $input[ 'host' ]                 = $requestFormData->host;
        $input[ 'location' ]             = $requestFormData->location;
        $input[ 'approval_required' ]    = $approval_required;
        $input[ 'member_can_invite' ]    = $member_can_invite;
        $input[ 'photo_id' ]             = $requestFormData->photo_id;
        $input[ 'category' ]             = $requestFormData->category;
        $input[ 'view_privacy' ]         = $requestFormData->view_privacy;
        $input[ 'comment_privacy' ]      = $requestFormData->comment_privacy;
        $input[ 'privacy_photo_upload' ] = $requestFormData->privacy_photo_upload;

        $validator         = Validator::make(
            [
                'title' => $input[ 'title' ],
                'description' => $input[ 'description' ],
                'category' => $input[ 'category' ],
                'location' => $input[ 'location' ],
                'starttime' => $input[ 'starttime' ],
                'endtime' => $input[ 'endtime' ],
                'parent_id' => $input[ 'parent_id' ],
                'parent_type' => $input[ 'parent_type' ],
            ],
            [
                'title'     => 'required',
                'description' => 'required',
                'category' => 'required',
                'location' => 'required',
                //  'starttime' => 'required',
                // 'endtime' => 'required',
                'parent_id' => 'required',
                'parent_type' => 'required',
            ]);
        $data[ 'hours' ]   = $this->hours();
        $data[ 'minutes' ] = $this->minutes();

        $data[ 'parent_type' ] = $requestFormData->parent_type;
        $data[ 'parent_id' ]   = $requestFormData->parent_id;

        $data[ 'categories' ] = $this->eventRepository->allCategories();

        if($validator->fails()) {
            if($this->is_api) {
                return \Api::invalid_param();
            }

            return redirect()->back()->withInput()->withErrors($validator->errors());
        } else {
            if($input[ 'starttime' ] > $input[ 'endtime' ]) {
                if($this->is_api) {
                    return \Api::other_error('Please enter valid date, e.g: Start date and End date.');
                }

                return redirect()->back()->withInput()
                                 ->withErrors(['isDateValid' => 'Please enter valid date, e.g: Start date and End date.'], 'isDateValid');
            }

            $data[ 'hours' ]   = $this->hours();
            $data[ 'minutes' ] = $this->minutes();

            $event_id = $this->eventRepository->createEvent($input, $this->user_id);
            $event    = Event::find($event_id);
            $album_id = $this->albumRepository->create_album('Event Profile', 'event', '', 'profile', $event_id);

            $file          = StorageFile::where('file_id', $photo_id_file)->first();
            $file_name_new = '';
            $file_name     = '';
            if(isset($file->file_id)) {
                if(file_exists("local/storage/app/photos/" . $file->storage_path) == TRUE) {
                    $file_name     = time() . rand(111111111, 9999999999);
                    $folder_path   = "local/storage/app/photos/" . $event->id;
                    $file_name_new = $event->id . "_" . $file_name . "." . $file->extension;
                    if(!file_exists($folder_path)) {
                        if(!mkdir($folder_path, 0777, TRUE)) {
                            $folder_path = '';
                        }
                    }
                    rename("local/storage/app/photos/" . $file->storage_path, $folder_path . "/" . $file_name_new);
                }

                $file->parent_id    = $event->id;//photo_id
                $file->user_id      = $event->parent_id;
                $file->storage_path = $event->id . "/" . $file_name_new;
                $file->name         = $file_name;
                $file->save();

                $photo = new AlbumPhoto();

                $photo->file_id     = $file->file_id;
                $photo->album_id    = $album_id;
                $photo->title       = 'event profile picture';
                $photo->description = 'first event profile picture';
                $photo->owner_type  = 'event';
                $photo->owner_id    = $event->id;

                $photo->save();

                if($photo_id_file > 0 AND $event_id > 0) {
                    $event->photo_id = $photo_id_file;
                    $event->save();

                }
            }

            if($this->is_api) {
                $data = $this->eventRepository->find($event_id);

                return \Api::success(['data' => $this->eventRepository->get_details($data)]);
            }

            return redirect('event/' . $event_id);
        }
    }

    public function rsvpAjax(Request $request) {
        $event_id = $request->event_id;
        $rsvp     = $request->rsvp;
        if($this->is_api) {
            if(empty($event_id) || empty($rsvp)) {
                return \Api::invalid_param();
            }
        }
        if($this->user_id) {
            if($event_id) {
                if($this->eventRepository->isAttending($this->user_id, $event_id) > 0) {
                    $return = $this->eventRepository->updateAttending($this->user_id, $rsvp, $event_id);
                    if($this->is_api) {
                        return \Api::success_with_message();
                    }

                    return $return;
                } else {
                    $return = $this->eventRepository->attending($this->user_id, $rsvp, $event_id);
                    if($this->is_api) {
                        return \Api::success_with_message();
                    }

                    return $return;
                }
            }
        }

        return '0';
    }

    private function return_response($data, array $params) {
        $data[ 'error' ] = $params[ 'error' ];
        $data[ 'code' ]  = $params[ 'code' ];

        return $data;
    }

    public function destroy($event_id = NULL) {
        if($this->is_api) {
            $event_id = \Input::get('event_id');
            if(!$event_id) {
                return \Api::invalid_param();
            }
        }
        $event = $this->eventRepository->find($event_id);
        if($event) {
            $parent_id = \Kinnect2::isEventOwner($event);
            if($parent_id > 0) {

                $this->eventRepository->deleteEvent($event->id, $this->user_id);
                if($this->is_api) {
                    return \Api::success_with_message();
                }

                return redirect('group/' . $parent_id)->with('message', 'event has been deleted');
            } else {
                if($this->is_api) {
                    return \Api::access_denied();
                }

                return redirect('group/' . $parent_id)->with('message', 'Event not found');
            }

        } else {
            if($this->is_api) {
                return \Api::detail_not_found();
            }
        }

        return redirect('events')->with('message', 'event has been deleted');
    }

    public function attend(Request $request) {
        if(empty($request->event_id) || empty($request->rsvp)) {
            if($this->is_api) {
                return \Api::invalid_param();
            }
        }
        if($request->event_id) {
            if($this->eventRepository->isAttending($this->user_id, $request->event_id) > 0) {

                $return = $this->eventRepository->updateAttending($this->user_id, $request->rsvp, $request->event_id);
            } else {
                $return = $this->eventRepository->attending($this->user_id, $request->rsvp, $request->event_id);
            }

            if($this->is_api) {
                return \Api::success_with_message();
            }

            return $return;
        }

        return '0';
    }

    public function unFollow(Request $request, GroupRepository $groupRepository) {

        if($this->is_api) {
            if(empty($request->group_id)) {
                return \Api::invalid_param();
            }
        }
        if($request->group_id) {
            $groupRepository->unfollow($request->group_id, $this->user_id);
        }
        if($this->is_api) {
            return \Api::success_with_message();
        }
    }

    public function deleteAttendRequest(Request $request) {
        if($request->event_id) {
            $this->eventRepository->deleteAttendRequest($this->user_id, $request->event_id);
        }

        return '0';
    }

    public function _get_all_events_detail($events, $group = NULL) {

        $data[ 'results' ] = array();
        foreach ($events as $row) {
            $data[ 'results' ][] = $this->eventRepository->get_details($row, $group);
        }

        return $data;
    }

    public function inviteEvent($event_id = NULL) {

        if($this->is_api) {
            if(!\Input::has('event_id') || !\Input::has('list_field')) {
                return \Api::invalid_param();
            }
            $event_id = \Input::get('event_id');
        }

        $invites = \Input::get('list_field');

        if($invites == []) {
            if($this->is_api) {
                return \Api::invalid_param();
            } else {
                return redirect()->back();
            }

        } else {

            $this->eventRepository->invitesEntries($invites, $event_id, $this->user_id);
            if($this->is_api) {
                return \Api::success_with_message('Invitation sent successfully');
            }
            return redirect()->back();
        }
    }

    public function approveInvite(Request $request) {
        if($request->event_id) {
            $return = $this->eventRepository->ApproveInvite($this->user_id, $request->rsvp, $request->event_id);
            if($this->is_api) {
                $params = ['error' => "0", "code" => "0"];

                return $this->return_response(['msg' => "Success"], $params);
            }

            return $return;
        }

        return '0';
    }

    public function add_photo(Request $request) {
        $event_id = $request->event_id;
        $event    = Event::find($event_id);

        $album = $this->eventRepository->getDefaultAlbum($event_id, 'event');

        $album_id = $album->album_id;

        $sm = new StorageManager();

        $photo_id = $sm->saveAlbumPhoto($event_id, 'event', $request->file('file'), 'album_photo', 'Event Profile Photo', $album_id);

        $extension = $request->file('file')->getClientOriginalExtension();
        //$event->photo_id = $photo_id;

        //$event->save();

        if($photo_id > 0) {
            //making thumbs
            $folder_path = public_path('storage/temporary/events');
            if(!file_exists($folder_path)) {
                if(!mkdir($folder_path, 0777, TRUE)) {
                    $folder_path = '';
                }
            }
            $parent_photo = AlbumPhoto::find($photo_id);
            $file_name    = time() . rand(111111111, 9999999999);

            // <editor-fold desc="Resize EVENT_THUMB_WIDTH">
            $image1 = Image::make($request->file('file'))->encode($extension);
            $image1->resize(Config::get('constants.EVENT_THUMB_WIDTH'), Config::get('constants.EVENT_THUMB_HEIGHT'));

            if($image1->save($folder_path . '/' . $file_name . "." . $extension)) {
                $photo_id = $sm->saveAlbumPhoto($event->id, 'event', $image1, 'album_photo', 'Event Profile Photo', $parent_photo->album_id, $parent_photo->file_id, 'event_thumb', $parent_photo->photo_id);
            }
            $image1 = Image::make($request->file('file'))->encode($extension);
            $image1->resize(Config::get('constants.ALBUM_THUMB_WIDTH'), Config::get('constants.ALBUM_THUMB_HEIGHT'));

            if($image1->save($folder_path . '/' . $file_name . "." . $extension)) {
                $photo_id = $sm->saveAlbumPhoto($event->id, 'event', $image1, 'album_photo', 'Event Profile Photo', $parent_photo->album_id, $parent_photo->file_id, 'thumb_normal', $parent_photo->photo_id);
            }
            // </editor-fold>

            // <editor-fold desc="Resize EVENT_PROFILE_WIDTH">
            $image1 = Image::make($request->file('file'))->encode('jpg');;
            $image1->resize(Config::get('constants.EVENT_PROFILE_WIDTH'), Config::get('constants.EVENT_PROFILE_HEIGHT'));

            if($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                $photo_id = $sm->saveAlbumPhoto($event->id, 'event', $image1, 'album_photo', 'Event Profile Photo', $parent_photo->album_id, $parent_photo->file_id, 'event_profile', $parent_photo->photo_id);
            }
            // </editor-fold>
        }

        return redirect('event/' . $event_id . '#tab-photos');

    }

    public function createEventTempImage($self = FALSE) {

        $event_image_file = \Input::file('event_image');
        //		$group_original_image = $request->file('group_original_image');
        //
        //		//Saving originale image
        //		$sm = new StorageManager();
        //
        //		$data = $sm->storeFile(-1, $group_original_image, 'album_photo');
        //
        //		$sfObj = new StorageFile();
        //
        //		$sfObj->parent_file_id = !empty($data['parent_file_id']) ? $data['parent_file_id'] : NULL;
        //		$sfObj->type = !empty($data['type']) ? $data['type'] : 'Group Profile Photo';
        //		$sfObj->parent_id = isset($data['parent_id']) ? $data['parent_id'] : NULL;
        //		$sfObj->parent_type = 'group';
        //		$sfObj->user_id = $this->user_id;
        //		$sfObj->storage_path = $data['storage_path'];
        //		$sfObj->extension = $data['extension'];
        //		$sfObj->name = $data['name'];
        //		$sfObj->mime_type = $data['mime_type'];
        //		$sfObj->size = $data['size'];
        //		$sfObj->hash = $data['hash'];
        //
        //		$sfObj->save();
        //		//Saving orignal image

        $sm = new StorageManager();

        $data = $sm->storeFile(-1, $event_image_file, 'album_photo');

        $sfObj = new StorageFile();

        $sfObj->parent_file_id = !empty($data[ 'parent_file_id' ]) ? $data[ 'parent_file_id' ] : NULL;
        $sfObj->type           = !empty($data[ 'type' ]) ? $data[ 'type' ] : 'event_profile';
        $sfObj->parent_id      = isset($data[ 'parent_id' ]) ? $data[ 'parent_id' ] : NULL;
        $sfObj->parent_type    = 'event';
        $sfObj->user_id        = $this->user_id;
        $sfObj->storage_path   = $data[ 'storage_path' ];
        $sfObj->extension      = $data[ 'extension' ];
        $sfObj->name           = $data[ 'name' ];
        $sfObj->mime_type      = $data[ 'mime_type' ];
        $sfObj->size           = $data[ 'size' ];
        $sfObj->hash           = $data[ 'hash' ];
        $sfObj->width          = isset($data[ 'width' ]) ? $data[ 'width' ] : NULL;
        $sfObj->height         = isset($data[ 'height' ]) ? $data[ 'height' ] : NULL;

        if(!$sfObj->save()) {
            if($this->is_api) {
                return \Api::other_error('File not uploaded. Please Try Again');
            }
            return FALSE;
        } else {
            if($this->is_api) {
                if($self) {
                    return $sfObj->file_id;
                }
                return \Api::success(['file_id' => $sfObj->file_id]);
            }
            return $sfObj->file_id . "+_+" . $sfObj->storage_path;
        }
    }

    public function upload_update_photo() {

        if($this->is_api) {
            if(!\Input::hasFile('event_image') || !\Input::has('event_id')) {
                return \Api::invalid_param();
            }
        }
        $file_id = $this->createEventTempImage(TRUE);
        \Input::merge(['photo_id' => $file_id]);

        $this->update_photo();

        $data[ 'photo_url' ] = \Kinnect2::getPhotoUrl($file_id, \Input::get('event_id'), 'event', 'event_profile');//\Kinnect2::get_photo_path($file_id);

        return \Api::success(['data' => $data]);
    }

    public function update_photo() {
        if(!\Input::has('photo_id') || !\Input::has('event_id')) {
            return \Api::invalid_param();
        }
        $file_id  = \Input::get('photo_id');
        $group_id = \Input::get('event_id');

        $event = Event::find($group_id);

        $file          = StorageFile::where('file_id', $file_id)->first();
        $file_name_new = '';
        $file_name     = '';
        if(isset($file->file_id)) {
            if(file_exists("local/storage/app/photos/" . $file->storage_path) == TRUE) {
                $file_name     = time() . rand(111111111, 9999999999);
                $folder_path   = "local/storage/app/photos/" . $event->id;
                $file_name_new = $event->id . "_" . $file_name . "." . $file->extension;
                if(!file_exists($folder_path)) {
                    if(!mkdir($folder_path, 0777, TRUE)) {
                        $folder_path = '';
                    }
                }
                rename("local/storage/app/photos/" . $file->storage_path, $folder_path . "/" . $file_name_new);
            }

            $file->parent_id    = $event->id;//photo_id
            $file->user_id      = $event->parent_id;
            $file->storage_path = $event->id . "/" . $file_name_new;
            $file->name         = $file_name;
            $file->save();

            $event->photo_id = $file_id;

            $event->save();

            $albumPhoto = $this->eventRepository->getDefaultAlbum($event->id, 'event');
            $album_id   = $albumPhoto->album_id;
            $photo      = new AlbumPhoto();

            $photo->file_id     = $file->file_id;
            $photo->album_id    = $album_id;
            $photo->title       = 'event profile picture';
            $photo->description = 'first event profile picture';
            $photo->owner_type  = 'event';
            $photo->owner_id    = $event->id;

            $photo->save();
        }//\Kinnect2::getPhotoUrl($group->photo_id, $group->id, 'group', 'group_thumb');

        return \Api::success_with_message();
    }
}
