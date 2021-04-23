<?php

namespace App\Http\Controllers;

use App\AlbumPhoto;
use App\Facades\Kinnect2;
use App\Group;
use App\Repository\Eloquent\EventRepository;
use App\Repository\Eloquent\UsersRepository;
use App\Services\StorageManager;
use App\User;
use Auth;
use Config;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repository\Eloquent\GroupRepository;
use App\StorageFile;
use Illuminate\Support\Facades\Input;
use Image;
use Validator;
use App\Repository\Eloquent\PrivacyRepository;

class GroupController extends Controller {
    protected $groupRepository;
    protected $eventRepository;
    protected $data;

    public function __construct(GroupRepository $groupRepository, EventRepository $eventRepository, Request $middleware, UsersRepository $friend) {
        $this->groupRepository = $groupRepository;
        $this->eventRepository = $eventRepository;
        $this->user_id         = $middleware['middleware']['user_id'];
        @$this->data->user = $middleware['middleware']['user'];
        $this->is_api = $middleware['middleware']['is_api'];
        $this->friend = $friend;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if($this->is_api) {
            $data = $this->_getAllGroupsDetail(\Kinnect2::recomendedAllGroups($this->user_id));
            if($data) {
                return \Api::success($data);
            } else {
                return \Api::result_not_found();

            }
        }
        $data['title'] = Input::get('title');
        $data['search_init'] = Input::get('search_init');
        $groups = \Kinnect2::myAllGroups(null,$data);
        $recomended_groups = \Kinnect2::recomendedAllGroups(null,$data);
        $data['groups'] = $groups;
        $data['recomended_groups'] = $recomended_groups;
        return view('group.browseGroups',$data)->with('page_Title', 'Recommended Groups');
    }

    public function _getAllGroupsDetail($groups) {
        $data['results'] = array();
        foreach ($groups as $row) {
            $data['results'][] = $this->groupRepository->getDetails($row, $this->user_id);
        }

        return $data;
    }

    public function follow(Request $request) {
        if($request->group_id) {

            if($this->groupRepository->isFollowing($request->group_id, $this->user_id) > 0) {
                if($this->is_api) {

                    $this->groupRepository->updateFollowing($request->group_id, $this->user_id);

                    return \Api::success_with_message();
                }

                return $this->groupRepository->updateFollowing($request->group_id, $this->user_id);
            } else {
                if($this->is_api) {
                    $this->groupRepository->follow($request->group_id, $this->user_id);

                    return \Api::success_with_message();
                }

                return $this->groupRepository->follow($request->group_id, $this->user_id);
            }
        }
        if($this->is_api) {
            return \Api::invalid_param();
        }

        return '0';
    }

    public function manageGroups() {
        $data = [];
        $data['title'] = Input::get('title');
        $data['search_init'] = Input::get('search_init');
        $groups = \Kinnect2::myAllGroups(Null,$data);
        
        return view('group.manageGroups',$data)->with('groups',$groups)->with('page_Title', 'Manage Groups');
    }

    public function getGroupMembers($group_id) {
        $data    = $this->groupRepository->getGroupMembers($group_id);
        $group   = $this->groupRepository->find($group_id);
        $friends = $this->Friendlist($group, $group_id);

        return view('group.groupMembers')->with('members', $data)->with('group', $group)->with('friends', $friends);
    }

    public function Friendlist($data, $group_id) {
        $list   = $this->friend->friends($this->user_id);
        $friend = $this->groupRepository->notFollowingFriedns($list, $group_id);
        if($this->is_api){
            return $friend;
        }
        $friends = array();
        foreach ($friend as $a) {
            $friends[] = ['id' => $a->user_id, 'value' => $a->displayname];
        }

        return $friends;
    }

    public function getCreate() {

        $data['categories'] = $this->groupRepository->allCategories();

        if($this->is_api) {
            return \Api::success(['results' => $data['categories']]);
        }

        return view('group.createGroup', $data)->with('page_Title', 'Create Group');
    }

    public function UN_Follow($id = NULL) {

        $this->groupRepository->unfollow($id, $this->user_id);
        if($this->is_api) {

            return \Api::success_with_message();
        }

        if($this->is_api) {
            return \Api::invalid_param();
        }

        return redirect()->back();
    }

    public function unFollow(Request $request) {

        if($request->group_id) {
            $this->groupRepository->unfollow($request->group_id, $this->user_id);
            if($this->is_api) {

                return \Api::success_with_message();
            }
        }

        if($this->is_api) {
            return \Api::invalid_param();
        }

        return '0';
    }

    public function create(Request $requestFormData, $user_id = NULL) {
        if(empty($user_id)) {
            $user_id = $this->user_id;
        }
        $validator = Validator::make(['title' => $requestFormData->title, 'description' => $requestFormData->description,], ['title' => 'required', 'description' => 'required',]);

        if($validator->fails()) {
            if($this->is_api) {
                return \Api::invalid_param();
            }

            return redirect('group/create')->withErrors($validator->errors());
        } else {
            $group_id = $this->groupRepository->create_group($requestFormData, $user_id);
            $group    = Group::find($group_id);

            if($requestFormData->saved_group_image_file_id != '' AND $group_id > 0) {
                $file_id = $requestFormData->saved_group_image_file_id;

                $file = StorageFile::where('file_id', $file_id)->first();
                if(isset($file->file_id)) {
                    if(file_exists("local/storage/app/photos/" . $file->storage_path) == TRUE) {
                        $file_name     = time() . rand(111111111, 9999999999);
                        $folder_path   = "local/storage/app/photos/" . $group->id;
                        $file_name_new = $group->id . "_" . $file_name . "." . $file->extension;
                        if(!file_exists($folder_path)) {
                            if(!mkdir($folder_path, 0777, TRUE)) {
                                $folder_path = '';
                            }
                        }
                        rename("local/storage/app/photos/" . $file->storage_path, $folder_path . "/" . $file_name_new);
                    }

                    $file->parent_id    = $group->group_id;//photo_id
                    $file->user_id      = $group->id;
                    $file->storage_path = $group->id . "/" . $file_name_new;
                    $file->name         = $file_name;
                    $file->save();

                    $group->photo_id = $file_id;
                    $group->save();
                } else {
                    $group->photo_id = 0;
                    $group->save();
                }

            }
            if($this->is_api) {

                $data = $this->getGroup($group_id);

                return $data;
            }

            return redirect('group/' . $group_id);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGroup($group_id = NULL) {

        if(empty($group_id)) {
            $group_id = \Input::get('group_id');
        }

        $data = $this->groupRepository->find($group_id);

        if(empty($data)) {
            if($this->is_api) {
                return \Api::detail_not_found();
            }

            return redirect()->back()->with('info', 'No group was found');
        }

        $friends = $this->Friendlist($data, $group_id);

        $path = $this->groupRepository->getCoverPhoto($data->cover_photo_id);

        if($this->is_api) {
            if($group_id) {
                if($data) {
                    $data                            = $this->_getGroupDetail($data);
                    $data['data']['cover_photo_url'] = $path;
                    $data['friends_list']            = $this->_user_detail($friends);

                    return \Api::success($data);
                } else {
                    return \Api::detail_not_found();
                }
            } else {
                return \Api::invalid_param();
            }

        }

        $privacyObj = new PrivacyRepository();

        $data['upload_permission']  = $privacyObj->is_allowed($data->id, 'group', 'group_photo_upload', $this->user_id, $data->creator_id);
        $data['comment_permission'] = $privacyObj->is_allowed($data->id, 'group', 'comment', $this->user_id, $data->creator_id);
        $data['view_permission']    = $privacyObj->is_allowed($data->id, 'group', 'view', $this->user_id, $data->creator_id);

        $data['path']  = $path;
        $data['group'] = $data;

        return view('group.groupProfile', $data)->with('friends', $friends);
    }

    public function _getGroupDetail($group) {

        $data['data'] = array();
        $data['data'] = $this->groupRepository->getDetails($group, $this->user_id);

        return $data;
    }

    public function get_my_groups() {
        if($this->is_api) {
            $data['groups'] = \Kinnect2::myAllGroups($this->user_id);
            $groups         = $this->_getAllGroupsDetail($data['groups']);

            return \Api::success($groups);

        }

        if($this->is_api) {
            return \Api::invalid_param();
        }

        return '0';
    }

    public function store(Request $request) {
        //
    }

    public function show($id) {
        //
    }

    public function edit($group_id = NULL) {

        if($this->is_api) {
            $group_id = \Input::get('group_id');
        }
        $group = $this->groupRepository->find($group_id);

        if(!$group_id) {
            if($this->is_api) {
                return \Api::invalid_param();
            }
        }

        if((!\Kinnect2::isGroupOwner($group, $this->user_id)) || (!\Kinnect2::isGroupManager($group->id, $this->user_id))) {
            if($this->is_api) {
                return \Api::access_denied();
            }

            redirect('groups')->with('message', ['You are not Authorized to visit
        this page.',]);
        }

        //return $this->groupRepository->getGroupPrivacySettingValue('group', $group->id, 'group_photo_upload');

        $data['categories'] = $this->groupRepository->allCategories();

        if(($this->user_id == $group->creator_id) || (Kinnect2::isGroupManager($group->id, $this->user_id) > 0)) {
            if($this->is_api) {
                $data = ['data' => $this->_getGroupDetail($group), 'categories' => $data['categories'],];

                return \Api::success($data);

            }

            return view('group.editGroup', $data)->with('group', $group);
        } else {
            if($this->is_api) {
                return \Api::access_denied();
            }

            return redirect('groups');
        }
    }

    public function update(Request $requestFormData, $group_id = NULL) {

        if($this->is_api) {
            $group_id = \Input::get('group_id');
        }
        if(!$group_id) {
            if($this->is_api) {
                return \Api::invalid_param();
            }
        }
        $validator = Validator::make(['title' => $requestFormData->title, 'description' => $requestFormData->description,], ['title' => 'required', 'description' => 'required',]);

        if($validator->fails()) {
            if($this->is_api) {
                return \Api::invalid_param();
            }

            return redirect()->back()->withErrors($validator->errors());
        } else {
            $group_id = $this->groupRepository->update($requestFormData, $group_id, $this->user_id);

            //Updating group record with new uploaded image.
            $group = Group::find($requestFormData->group_id);

            $file_id = $requestFormData->saved_group_image_file_id;

            $file = StorageFile::where('file_id', $file_id)->first();

            if(isset($file->file_id)) {
                if(file_exists("local/storage/app/photos/" . $file->storage_path) == TRUE) {
                    $file_name     = time() . rand(111111111, 9999999999);
                    $folder_path   = "local/storage/app/photos/" . $group->id;
                    $file_name_new = $group->id . "_" . $file_name . "." . $file->extension;
                    if(!file_exists($folder_path)) {
                        if(!mkdir($folder_path, 0777, TRUE)) {
                            $folder_path = '';
                        }
                    }
                    rename("local/storage/app/photos/" . $file->storage_path, $folder_path . "/" . $file_name_new);
                }

                $file->parent_id    = $group->id;//photo_id
                $file->user_id      = $group->creator_id;
                $file->storage_path = $group->id . "/" . $file_name_new;
                $file->name         = $file_name;
                $file->save();

                $group->photo_id = $file_id;
                $group->save();
            }

            if($this->is_api) {

                $data = $this->getGroup($group_id);

                return $data;
            }

            return redirect('group/' . $group_id);
        }
    }

    public function destroy($group_id = NULL) {
        if($this->is_api) {
            $group_id = \Input::get('group_id');
        }
        if(!$group_id) {
            if($this->is_api) {
                return \Api::invalid_param();
            }
        }
        $group = Group::find($group_id);

        if(!$group) {
            if($this->is_api) {
                return \Api::detail_not_found();
            } else {
                //return redirect('groups')->with('message', 'group not found or deleted');
            }
        }

        if((\Kinnect2::isGroupOwner($group, $this->user_id) == 1) || (\Kinnect2::isGroupManager($group->id, $this->user_id) == 1)) {
            $this->groupRepository->deleteGroup($group->id, $this->user_id);

        } else {
            if($this->is_api) {
                return \Api::access_denied();
            }

            return redirect()->back()->with('message', ['You are not Authorized to visit
        this page.',]);
        }

        if($this->is_api) {
            return \Api::success_with_message();
        }

        return redirect('groups')->with('message', 'group has been deleted');
    }

    public function getGroupEvents($group_id = NULL) {
        if($this->is_api) {
            $group_id = \Input::get('group_id');
        }

        if(!$group_id) {
            if($this->is_api) {
                return \Api::invalid_param();
            }
        }

        $data['group']  = $this->groupRepository->find($group_id);
        $data['events'] = $this->eventRepository->groupEvents($group_id);
        $friends        = $this->Friendlist($data['group'], $group_id);

        if(!$data['group']) {
            if($this->is_api) {
                return \Api::detail_not_found();
            }
        }
        if($this->is_api) {
            $events    = $this->get_all_events_detail($data['events'], $data['group']);
            $group     = $this->_getGroupDetail($data['group']);
            $allevents = $group;

            $allevents['events'] = $events['events'];

            return \Api::success($allevents);
        }

        return view('event.profileEventsList', $data)->with('friends', $friends);
    }

    public function get_all_events_detail($events, $group) {
        $data['events'] = array();
        foreach ($events as $row) {
            $data['events'][] = $this->eventRepository->get_details($row, $group);
        }

        return $data;
    }

    public function my_groups() {
        $data['groups'] = Group::whereCreatorId($this->user_id)->get();
        $groups         = $this->_getAllGroupsDetail($data['groups']);

        return \Api::success($groups);
    }

    public function removeMember(Request $request) {

        if($request->group_id) {
            $this->groupRepository->removeMember($request->group_id, $request->member_id);
            if($this->is_api) {

                return \Api::success_with_message();
            }
        }

        if($this->is_api) {
            return \Api::invalid_param();
        }

        return '0';
    }

    public function pendingRequest($group_id) {
        $group   = $this->groupRepository->find($group_id);
        $friends = $this->Friendlist($group, $group_id);
        if((Auth::user()->id == $group->creator_id) || (Kinnect2::isGroupManager($group->id, Auth::user()->id) > 0)) {
            $data = $this->groupRepository->PendingApprovalRequests($group_id, $group);

            return view('group.pendingRequest')->with('members', $data)->with('group', $group)
                                               ->with('friends', $friends);
        } else {
            return redirect()->back();
        }
    }

    public function ApproveReq(Request $request) {
        if($request->group_id) {
            $this->groupRepository->ApproveGroupRequest($request->group_id, $request->member_id, $this->user_id);
            if($this->is_api) {

                return \Api::success_with_message();
            }
        }

        if($this->is_api) {
            return \Api::invalid_param();
        }

        return '0';
    }

    public function RejectReq(Request $request) {
        if($request->group_id) {
            $this->groupRepository->RejectGroupRequest($request->group_id, $request->member_id, $this->user_id);
            if($this->is_api) {

                return \Api::success_with_message();
            }
        }

        if($this->is_api) {
            return \Api::invalid_param();
        }

        return '0';
    }

    public function inviteGroup($group_id = NULL) {
        if($this->is_api){
            if(!\Input::has('friends') || !\Input::has('group_id')){
                return \Api::invalid_param();
            }
            $group_id = \Input::get('group_id');
            $invites = \Input::get('friends');
        }else{
            $invites = \Input::get('list_field');
        }
        if($invites == []) {
            if($this->is_api){
                return \Api::invalid_param();
            }
            return redirect()->back();
        } else {
            $this->groupRepository->invitesEntries($invites, $group_id, $this->user_id);
            if($this->is_api){
                return \Api::success_with_message('Invitation sent successfully');
            }
            return redirect()->back();
        }
    }

    public function groupInfo($group_id) {
        $group    = $this->groupRepository->find($group_id);
        $category = $this->groupRepository->catGroup($group->category_id);
        $friends  = $this->Friendlist($group, $group_id);

        return view('group.groupInfo')->with('group', $group)->with('friends', $friends)->with('cat', $category);
    }

    public function pendingInvites($group_id) {
        $group   = $this->groupRepository->find($group_id);
        $friends = $this->Friendlist($group, $group_id);
        if((Auth::user()->id == $group->creator_id) || (Kinnect2::isGroupManager($group->id, Auth::user()->id) > 0)) {
            $data = $this->groupRepository->PendingInvites($group_id);

            return view('group.pendingInvites')->with('members', $data)->with('group', $group)
                                               ->with('friends', $friends);
        } else {
            return redirect()->back();
        }
    }

    public function profile_content(Request $request) {
        $group_id = $request->userId;
        $template = $request->template;
        switch ($template) {
            case 'members':
                return $this->getGroupMembers($group_id);
                break;
            case 'events':
                return $this->getGroupEvents($group_id);
                break;
            case 'info':
                return $this->groupInfo($group_id);
                break;
            case 'pending-requests':
                return $this->pendingRequest($group_id);
                break;
            case 'pending-invites':
                return $this->pendingInvites($group_id);
                break;
        }
    }

    public function createGroupTempImage($self = false) {

        /*if($this->is_api){
            if(!\Input::hasFile('group_image')){
                return \Api::invalid_param();
            }
        }*/
        $group_image_file = \Input::file('group_image');
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

        $data = $sm->storeFile(-1, $group_image_file, 'album_photo');

        $sfObj = new StorageFile();

        $sfObj->parent_file_id = !empty($data['parent_file_id']) ? $data['parent_file_id'] : NULL;
        $sfObj->type           = !empty($data['type']) ? $data['type'] : 'group_thumb';
        $sfObj->parent_id      = isset($data['parent_id']) ? $data['parent_id'] : NULL;
        $sfObj->parent_type    = 'group';
        $sfObj->user_id        = $this->user_id;
        $sfObj->storage_path   = $data['storage_path'];
        $sfObj->extension      = $data['extension'];
        $sfObj->name           = $data['name'];
        $sfObj->mime_type      = $data['mime_type'];
        $sfObj->size           = $data['size'];
        $sfObj->hash           = $data['hash'];
        $sfObj->width          = $data['width'];
        $sfObj->height         = $data['height'];

        if(!$sfObj->save()) {
            if($this->is_api) {
                return \Api::other_error('File not uploaded. Please Try Again');
            }

            return FALSE;
        } else {
            if($this->is_api) {
                if($self){
                    return $sfObj->file_id;
                }
                return \Api::success(['file_id' => $sfObj->file_id]);
            }

            return $sfObj->file_id . "+_+" . $sfObj->storage_path;
        }
    }

    public function editGroupTempImage(Request $request) {

        $group_image_file = $request->file('group_image');

        $sm = new StorageManager();

        $data = $sm->storeFile(-1, $group_image_file, 'album_photo');

        $sfObj = new StorageFile();

        $sfObj->parent_file_id = !empty($data['parent_file_id']) ? $data['parent_file_id'] : NULL;
        $sfObj->type           = !empty($data['type']) ? $data['type'] : 'group_thumb';
        $sfObj->parent_id      = isset($request->group_id) ? $request->group_id : NULL;
        $sfObj->parent_type    = 'group';
        $sfObj->user_id        = $this->user_id;
        $sfObj->storage_path   = $data['storage_path'];
        $sfObj->extension      = $data['extension'];
        $sfObj->name           = $data['name'];
        $sfObj->mime_type      = $data['mime_type'];
        $sfObj->size           = $data['size'];
        $sfObj->hash           = $data['hash'];

        if(!$sfObj->save()) {
            if($this->is_api) {
                return \Api::other_error('File not uploaded. Please Try Again');
            }

            return FALSE;
        }
        if($this->is_api) {
            return \Api::success(['file_id' => $sfObj->file_id]);
        }

        return $sfObj->file_id . "+_+" . $sfObj->storage_path;
    }

    public function MakeManager(Request $request) {
        if($request->group_id) {
            $this->groupRepository->MakeGroupManager($request->group_id, $request->member_id, $this->user_id);
            if($this->is_api) {

                return \Api::success_with_message();
            }
        }

        if($this->is_api) {
            return \Api::invalid_param();
        }

        return '0';
    }

    public function DemoteManager(Request $request) {
        if($request->group_id) {
            $this->groupRepository->DemoteGroupManager($request->group_id, $request->member_id, $this->user_id);
            if($this->is_api) {

                return \Api::success_with_message();
            }
        }

        if($this->is_api) {
            return \Api::invalid_param();
        }

        return '0';
    }

    public function ApproveManagerReq(Request $request) {
        if($request->group_id) {
            $this->groupRepository->ApproveGroupManagerReq($request->group_id, $request->member_id, $this->user_id);
            if($this->is_api) {

                return \Api::success_with_message();
            }
        }

        if($this->is_api) {
            return \Api::invalid_param();
        }

        return '0';
    }

    public function Leave_ManagerShip($id = NULL) {
        $this->groupRepository->LeaveGroupManagership($id, $this->user_id);
        if($this->is_api) {

            return \Api::success_with_message();
        }

        if($this->is_api) {
            return \Api::invalid_param();
        }

        return redirect()->back();
    }

    public function group_members() {
        if(!\Input::has('group_id')) {
            return \Api::invalid_param();
        }
        $group_id = \Input::get('group_id');
        $data     = $this->groupRepository->getGroupMembersByKey($group_id);
        $users    = User::whereIn('id', $data)->get();
        $members  = [];
        foreach ($users as $user) {
            $m['display_name']    = $user->displayname;
            $m['profile_url']     = $user->username;
            $m['profile_picture'] = \Kinnect2::getPhotoUrl($user->photo_id, $user->id, 'user', 'thumb_normal');
            $members[]            = $m;
        }
        return \Api::success_list($members);

    }

    public function update_photo() {
        if(!\Input::has('photo_id') || !\Input::has('group_id') || !\Input::has('type')) {
            return \Api::invalid_param();
        }
        $file_id  = \Input::get('photo_id');
        $group_id = \Input::get('group_id');

        $group = Group::find($group_id);

        $file = StorageFile::where('file_id', $file_id)->first();

        if(isset($file->file_id)) {
            if(file_exists("local/storage/app/photos/" . $file->storage_path) == TRUE) {
                $file_name     = time() . rand(111111111, 9999999999);
                $folder_path   = "local/storage/app/photos/" . $group_id;
                $file_name_new = $group_id . "_" . $file_name . "." . $file->extension;
                if(!file_exists($folder_path)) {
                    if(!mkdir($folder_path, 0777, TRUE)) {
                        $folder_path = '';
                    }
                }
                rename("local/storage/app/photos/" . $file->storage_path, $folder_path . "/" . $file_name_new);
            }

            $file->parent_id    = $group_id;//photo_id
            $file->user_id      = $group->creator_id;
            $file->storage_path = $group_id . "/" . $file_name_new;
            $file->name         = $file_name;
            $file->save();

            if(\Input::get('type') == 'cover_photo') {
                $group->cover_photo_id = $file_id;
            } else {
                $group->photo_id = $file_id;
            }

            $group->save();

        }//\Kinnect2::getPhotoUrl($group->photo_id, $group->id, 'group', 'group_thumb');


        return \Api::success_with_message();
    }

    public function upload_update_photo() {

        if($this->is_api){
            if(!\Input::hasFile('group_image') || !\Input::has('group_id') || !\Input::has('type')){
                return \Api::invalid_param();
            }
        }
        $file_id = $this->createGroupTempImage(TRUE);
        \Input::merge(['photo_id'=> $file_id]);

        $this->update_photo();

        $data['photo_url'] = \Kinnect2::getPhotoUrl($file_id, \Input::get('group_id'), 'group', 'group_thumb');//\Kinnect2::get_photo_path($file_id);

        return \Api::success(['data' => $data]);
    }

    private function _user_detail($friends) {
        $all = [];
        foreach($friends as $row){

            $data['id'] = $row->user_id;
            $data['display_name'] = $row->displayname;
            $data['profile_photo_url'] = \Kinnect2::getPhotoUrl($row->photo_id, $row->user_id, 'user', 'thumb_normal');
            $all[] = $data;
        }
        return $all;
    }
}
