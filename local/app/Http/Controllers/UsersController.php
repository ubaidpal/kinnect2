<?php

namespace App\Http\Controllers;

use App\ActivityAction;
use App\AlbumPhoto;
use App\Brand;
use App\Consumer;
use App\Classes\Kinnect2;
use App\Events\ActivityDelete;
use App\Repository\Eloquent\ActivityActionRepository;
use App\Repository\Eloquent\AlbumRepository;
use App\Repository\Eloquent\BrandRepository;
use App\Repository\Eloquent\FriendshipRepository;
use App\Repository\Eloquent\MessageRepository;
use App\Repository\Eloquent\NotificationRepository;
use App\Repository\Eloquent\UsersRepository;
use App\Repository\Eloquent\PrivacyRepository;
use App\Services\StorageManager;
use App\User;
use Carbon\Carbon;
use Doctrine\Common\Annotations\Annotation\Attribute;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Repository\Eloquent\SettingRepository;
use App\Timezone;
use App\Usersetting;
use Config;
use DB;
use App\Http\Middleware\Authenticate;
use App\Classes\UrlFilter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Image;
use Jenssegers\Agent\Agent;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Validator;
use App\Event;
use App\Events\ActivityLog;
use App\StorageFile;

class UsersController extends Controller
{
    protected $usersRepository;
    protected $data;
    protected $is_api;
    private   $user_id;
    private   $users;
    private   $setting;
    /**
     * @var AlbumRepository
     */
    private $albumRepository;
    /**
     * @var BrandRepository
     */
    private $brandRepository;
    /**
     * @var FriendshipRepository
     */
    private $friendshipRepository;

    public function __construct(UsersRepository $users, Request $middleware, AlbumRepository $albumRepository, BrandRepository $brandRepository, FriendshipRepository $friendshipRepository)
    {
        $this->users   = $users;
        $this->user_id = $middleware['middleware']['user_id'];
        @$this->data->user = $middleware['middleware']['user'];
        $this->is_api               = $middleware['middleware']['is_api'];
        $this->albumRepository      = $albumRepository;
        $this->brandRepository      = $brandRepository;
        $this->friendshipRepository = $friendshipRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = NULL)
    {

        $this->data->user = $this->users->get_user($this->user_id);
        if($this->is_api) {
            return \Api::success($this->data->user);
        } else {
            $this->data->title   = $this->data->user->name . ' - ' . 'Profile';
            $this->data->friends = $this->users->friends($this->user_id);
            $data                = (array)$this->data;

            return view('user.index', $data);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $this->data->countries = \Cache::rememberForever('countries', function () {
            return \DB::table('countries')->lists('name', 'id');
        });
        $this->data->title     = $this->data->user->name . ' - ' . 'Profile Edit';
        $data                  = (array)$this->data;

        return view('user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->users->update($this->user_id);
        $user_id = $this->user_id;
        if($this->is_api) {
            return \Cache::get('_user_'.$this->user_id,function () use ($user_id){
                $user = User::findOrNew($user_id);
                \Cache::forever('_user_'.$user_id,$user);
                return $user;
            });
        } else {

            //return redirect()->back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function profile_content(Request $request)
    {
        $type = $request->template;
        $user = $this->users->get_user($request->userId);

        $data['permission']['view_permissions'] = 1;

        if($type == 'kinnectors') {
            $privacyRepository                      = new PrivacyRepository();
            $data['permission']['view_permissions'] = $privacyRepository->has_permission('Privacy', 'WHO_VIEW_KINNECTOR', $this->user_id, $user->id);
        }

        if($data['permission']['view_permissions']) {
            switch ($type) {
                case 'whats-new':

                    $data['data'] = $this->whats_new($user->id);
                    break;
                case 'activity-log':
                    $data['data'] = $this->activity_log($user->id);
                    break;
                case 'info':
                    if($user->user_type == Config::get('constants.REGULAR_USER')) {
                        $data['consumer'] = $this->_get_consumer_info($user);
                    } else {
                        $data['consumer'] = $this->_get_brand_info($user);
                    }
                    $data['user'] = $user;
                    break;
                case 'edit-profile':
                    if($user->user_type == Config::get('constants.REGULAR_USER')) {
                        $data['consumer'] = $this->_get_consumer_info($user);
                    } else {
                        $data['consumer'] = $this->_get_brand_info($user);
                    }
                    $data['user']      = $user;
                    $data['countries'] = \Cache::rememberForever('countries', function () {
                        return \DB::table('countries')->lists('name', 'id');
                    });
                    break;
                case 'albums':

                    $data['albums'] = $this->albumRepository->getMyAlbums($user->id);
                    break;
                case 'followers':

                    $data['data'] = $this->followers($user->id, $user->user_type);
                    break;
                case 'following':

                    $data['following'] = $this->get_following($user->id, $user->user_type);
                    break;
                default:
                    $data['data'] = $this->kinnectors($user->id, $user->user_type);
                    break;
            };
        }
        $data['user'] = $user;

        return view('templates.partials.ajax.' . $type, $data);
    }

    public function afterActivationFollowBrands()
    {

        return view('afterActivationFollowBrands')->with('info', 'S');
    }

    public function getBrandsToFollow($search_term)
    {

        $userFollowingBrandIds = DB::table('brand_memberships')
                                   ->where('user_approved', 1)
                                   ->where('brand_approved', 1)
                                   ->where('user_id', $this->user_id)
                                   ->lists('brand_id');

        if($search_term != -1) {
            $users = DB::table('users')
                       ->select('users.id', 'users.name', 'users.displayname', 'users.username', 'users.user_type', 'users.photo_id as image')
                       ->whereNotIn('id', $userFollowingBrandIds)
                       ->where('users.displayname', 'like', $search_term . '%')
                       ->where('users.search', '1')
                       ->where('users.user_type', '!=', 1)
                       ->take(100)
                       ->get();
        } else {
            $users = DB::table('users')
                       ->select('view_count as followers', 'users.id', 'users.name', 'users.displayname', 'users.username', 'users.user_type', 'users.photo_id as image')
                       ->whereNotIn('id', $userFollowingBrandIds)
                       ->where('users.search', '1')
                       ->where('users.user_type', '!=', 1)
                       ->take(100)
                       ->get();
        }

        foreach ($users as $user) {
            $user->image     = \Kinnect2::getPhotoUrl($user->image, $user->id, 'user', 'thumb_icon');
            $user->followers = \Kinnect2::brand_kinnectors($user->id);
        }

        return json_encode($users);
    }

    public function whats_new($userId)
    {
        return 'this';
    }

    public function activity_log($userId)
    {
        $activityActionRepository = new ActivityActionRepository();

        return $activityActionRepository->all_activity($userId);
    }

    public function _get_consumer_info($user)
    {
        return $this->users->profile($user);

    }

    public function _get_brand_info($user)
    {
        return $this->brandRepository->_profile_info($user);
    }

    public function kinnectors($userId = NULL, $user_type = NULL)
    {

        if(empty($userId)) {
            $userId = \Input::get('user_id');
        }
        if($this->is_api) {
            if(empty($userId)) {
                return \Api::invalid_param();
            } else {
                $user = \Cache::get('_user_'.$userId,function () use ($userId){
                    $user = User::find($userId);
                    \Cache::forever('_user_'.$userId,$user);
                    return $user;
                });
                if($user) {
                    $user_type = $user->user_type;
                }
            }
        }
        // if ($user_type == Config::get('constants.REGULAR_USER')) {
        $this->data->friends = $this->users->friends($userId);
        //} else {
        // $this->data->friends = $this->brandRepository->getBrandKinnectors($userId);

        // }
        if($this->is_api) {

            if(count($this->data->friends) > 0) {
                return \Api::success_list(\Kinnect2::get_users_meta_from_membership($this->data->friends, TRUE, $this->user_id));
            } else {
                return \Api::result_not_found();
            }
        } else {
            $data = (array)$this->data;

            return $data;
        }
    }

    public function followers($userId = NULL, $user_type = NULL)
    {

        if(empty($userId)) {
            $userId = \Input::get('user_id');
        }
        if($this->is_api) {
            if(empty($userId)) {
                return \Api::invalid_param();
            } else {
                $user = \Cache::get('_user_'.$userId,function () use ($userId){
                    $user = User::find($userId);
                    \Cache::forever('_user_'.$userId,$user);
                    return $user;
                });
                if($user) {
                    $user_type = $user->user_type;
                }
            }
        }

        $this->data->friends = $this->brandRepository->get_brand_kinnectors($userId);
        if($this->is_api) {
            if(count($this->data->friends) > 0) {
                return \Api::success(['results' => $this->data->friends]);
            } else {
                return \Api::result_not_found();
            }

        } else {
            $data = (array)$this->data;

            return $data;
        }
    }

    public function get_following($user, $user_type)
    {
        return $brands = \Kinnect2::myAllBrands($user);
    }

    public function api_activity_log()
    {
        $per_page = 10;
        $page     = 1;
        if(\Input::has('page')) {
            $page = \Input::get('page');
        }
        $start_point      = ($page * $per_page) - $per_page;
        $activities       = ActivityAction::whereSubjectId($this->user_id)
                                          ->orderBy('action_id', 'DESC')
                                          ->take($per_page)
                                          ->skip($start_point)
                                          ->get();
        $activity_strings = [];
        $i                = 1;
        foreach ($activities as $row) {
            // if($i > 1) break;
            $string             = activity_log_string($row);
            $string['subject']  = [
                'subject_name' => $this->data->user->displayname,
                'subject_url'  => $this->data->user->username,
            ];
            $activity_strings[] = $string;
            $i++;
        }

        return \Api::success(['results' => $activity_strings]);
    }

    public function profile(UsersRepository $usersRepository, $user_id = NULL, $tab = NULL)
    {

//      $this->data->user->id => profile owner id
        if(empty($user_id)) {
            $user_id = \Input::get('user_id');
        }

        if(!$user_id) {
            return \Api::invalid_param();
        }
        // $user = $this->users->get_user($user_id);
        $this->data->consumer = $this->_get_user_info($user_id);
        if(!empty($this->data->consumer)) {
            $this->data->user = $this->data->consumer->user;
        } else {
            if($this->is_api) {
                return \Api::detail_not_found();
            }
            $this->data->user = '';
        }

        //Adding profile view stat
        $this->users->addProfilePageStat($this->data->user->id);

        $privacyRepository = new PrivacyRepository();

        $this->data->user->can_view_profile = $privacyRepository->has_permission('Privacy', 'WHO_VIEW_PROFILE', $this->user_id, $this->data->user->id);

        // if ($this->data->user->user_type == Config::get('constants.REGULAR_USER')) {
        $this->data->friends = $usersRepository->friends_count($this->data->user->id);

        // } else {
        //$this->data->friends = $this->brandRepository->getBrandKinnectors($this->data->user->id);
        //}
        if($this->data->user->user_type == Config::get('constants.BRAND_USER')) {
            $this->data->followers = $this->brandRepository->get_brand_kinnectors_count($this->data->user->id);
            $currentUser           = User::find($this->user_id);
            //$this->data->friends = $this->brandRepository->getBrandKinnectors($this->data->user->id);
            if($currentUser->user_type == Config::get('constants.BRAND_USER')) {
                $this->data->friends_to_invite = $this->users->friends_to_invite($this->user_id, 'brand', $this->data->user->id, $currentUser->user_type);

            } else {
                $this->data->friends_to_invite = $this->users->friends_to_invite($this->user_id, 'brand', $this->data->user->id, $currentUser->user_type);

            }

        }//else{
        $this->data->following = \Kinnect2::myAllBrandsCount($this->data->user->id);
        //}
        if($this->is_api) {
            $this->data->user_detail = $this->data->consumer;

            //unset($this->data->consumer, $this->data->user);
//return $this->brandRepository->get_brand_kinnectors($this->data->user->id);
            return \Api::success([
                'data'            => $this->users->_get_user_details($this->data->user_detail, TRUE),
                'is_friend'       => (\Kinnect2::is_friend($this->data->user->id, $this->user_id) ? 'yes' : 'no'),
                'friends_count'   => count($usersRepository->friends($this->data->user->id)),
                'friends'         => \Kinnect2::get_users_meta_from_membership($usersRepository->friends($this->data->user->id), TRUE, $this->user_id),
                'following_count' => \Kinnect2::myAllBrandsCount($this->data->user->id),
                'following'       => \Kinnect2::get_users_meta_from_membership(\Kinnect2::myAllBrands($this->data->user->id), FALSE, $this->user_id),
                'is_following'    => (\Kinnect2::is_following($this->user_id, $this->data->user->id) ? 'yes' : 'no'),
                'follower_count'  => $this->brandRepository->get_brand_kinnectors_count($this->data->user->id),
                'followers'       => \Kinnect2::get_users_meta_from_membership($this->brandRepository->get_brand_kinnectors($this->data->user->id), FALSE, $this->user_id)

            ]);
            ///return (array)$this->data;
        }
        if($this->data->user->user_type == Config::get('constants.REGULAR_USER')) {
            $name = $this->data->user->displayname;
        } else {
            $name = $this->data->user->brand_detail;
            $name = $name->brand_name;
        }
        $this->data->title = $name . ' - ' . 'Profile';
        $this->data->tab   = $tab;
        $data              = (array)$this->data;

        return view('user.index', $data);

    }

    public function _get_user_info($user_id)
    {
        $user = $this->users->get_user($user_id);

        if($user->user_type == Config::get('constants.REGULAR_USER')) {
            return $this->_get_consumer_info($user);
        } else {
            return $this->_get_brand_info($user);
        }
    }

    public function generalSetting()
    {

        $defaultTimezone = ['0' => 'Select Timezone'];
        $timezonesList   = DB::table('time_zone')->lists('country', 'value');
        $timezonesList   = $defaultTimezone + $timezonesList;
        if($this->is_api) {
            $data['email']       = $this->data->user->email;
            $data['username']    = $this->data->user->username;
            $data['displayname'] = $this->data->user->displayname;
            $data['timezone']    = (isset($this->data->user->timezone) ? $this->data->user->timezone : '');
            $data['birthdate']   = Consumer::find($this->data->user->userable_id)->birthdate;
            $data['timezones']   = $timezonesList;

            return \Api::success_data($data);
        }
        $currentUser = User::find($this->user_id);
        $consumer    = Consumer::find($currentUser->userable_id);

        return view('settings.generalSetting', ['consumer' => $consumer])
            ->with('timezonesList', $timezonesList)
            ->with('userTimezone', '')
            ->with('current', $currentUser)
            ->with('title', 'General Settings');
    }

    public function generalSettingSave(Request $request)
    {
        $currentUser = User::find($this->user_id);
        if(!\Input::has('timezone')) {
            if($this->is_api) {
                return \Api::invalid_param();
            }
        }
        $currentUser->timezone = $request->timezone;

        if($currentUser->user_type == Config::get('constants.REGULAR_USER')) {
            // consumer
            $consumer = Consumer::find($currentUser->userable_id);
            if($this->is_api) {
                $consumer->birthdate = $request->birthdate;
            } else {
                $consumer->birthdate = $request->datepicker;
            }

            $consumer->save();

            if($currentUser->user_type == Config::get('constants.BRAND_USER')) {
                // brand
                $brand = Consumer::find($currentUser->userable_id);
                $brand->save();
                //end for brand

            }
        }

        $currentUser->save();
        if($this->is_api) {
            return \Api::success_with_message();
        }

        return Redirect::back();

    }

    public function notificationSetting(SettingRepository $setting)
    {
        $this->setting = $setting;
        $userId        = $this->user_id;
        $records       = $this->setting->getSetting($userId);
        $all_data      = array();
        foreach ($records as $key => $record) {
            $all_data[$record->setting] = array(
                'category'      => $record->category,
                'setting'       => $record->setting,
                'setting_value' => $record->setting_value,
            );
        }
        if($this->is_api) {
            /*foreach(Config::get('constants_setting.privacy') as $key => $row){
                $data['privacy'][$key] = [
                    'name' => $key,
                    'body' => $row,
                    'value' => (isset($all_data[$key])?$all_data[$key]['setting_value']:'')
                ];
            }
            return $data;*/
            foreach ($records as $key => $record) {
                $settings[$record->category][$record->setting] = array(
                    'setting'       => $record->setting,
                    'setting_value' => $record->setting_value,
                );
            }

            return \Api::success(['results' => $settings]);
        }
        $data['settings'] = $all_data;

        return view('settings.notificationSetting', $data)->with('title', 'Notifications Settings');

    }

    public function changePassword()
    {

        return view("settings.changePassword")->with('title', 'Change Password');
    }

    public function userPasswordChange(Request $request)
    {

        if($this->is_api) {
            $validation = Validator::make($request->all(), [
                'old_password'       => 'required',
                'password'           => 'required|min:7|different:old_password|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!@#$%^&*]).*$/',
                'conformed_password' => 'required|min:7',
            ]);
            if($validation->fails()) {
                return \Api::invalid_param();
            }
        } else {
            $this->validate($request, [
                'old_password'       => 'required',
                'password'           => 'required|min:7|different:old_password|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!@#$%^&*]).*$/',
                'conformed_password' => 'required|min:7',
            ]);
        }

        $oldPassword      = Input::get('old_password');
        $id               = $this->user_id;
        $user             = User::find($id);
        $current_password = $user->password;
        $new_password     = Input::get('password');
        $confirm_password = Input::get('conformed_password');

        if(\Hash::check($oldPassword, $current_password)) {

            if($new_password != $confirm_password) {
                if($this->is_api) {
                    return \Api::other_error('New password and conformed password does not match');
                }

                return Redirect::back()->withErrors('New password and conformed password does not match');
            } else {
                $user->password = bcrypt($new_password);

                $user->save();
                if($this->is_api) {
                    return \Api::success_with_message();
                }

                return Redirect::to('/logout');
            }
        } else {
            if($this->is_api) {
                return \Api::other_error('Old password is incorrect');
            }

            return Redirect::back()->withErrors('Old password is incorrect');
        }
    }

    public function deleteAccountpage()
    {

        return view("settings.deleteAccount")->with('title', 'Delete Account');
    }

    public function postDeleteAccount()
    {

        if(!\Input::has('password')) {
            if($this->is_api) {
                return \Api::invalid_param();
            }
        }
        $currentPassword = Input::get('password');
        $id              = $this->user_id;
        $userInfo        = DB::table('users')->select('password')->where('id', $id)
                             ->first();
        if($currentPassword == "") {
            if($this->is_api) {
                return \Api::other_error('Please fill the password field');
            }

            return 'Please fill the password field';
        }
        if(Hash::check($currentPassword, $userInfo->password)) {
            $update = User::where('id', $id)
                          ->update(['deleted' => 1, 'search' => 0, 'active' => 0, 'resent' => 0]);
            if($this->is_api) {
                return \Api::success_with_message();
            }

            return '/logout';
        } else {
            if($this->is_api) {
                return \Api::other_error('Password incorrect');
            }

            return 'Password incorrect';
        }
    }

    /**
     * @param SettingRepository $setting
     *
     * @return $this|\Illuminate\Http\JsonResponse
     *
     */
    public function privacySetting(SettingRepository $setting)
    {

        $this->setting = $setting;
        $userId        = $this->user_id;
        $records       = $this->setting->getSetting($userId);
        $all_data      = array();
        foreach ($records as $key => $record) {
            $all_data[$record->setting] = array(
                'category'      => $record->category,
                'setting'       => $record->setting,
                'setting_value' => $record->setting_value,
            );
        }

        if($this->is_api) {
            $data['privacy']['title'] = 'Privacy';
            foreach (Config::get('constants_setting.privacyApi') as $key => $row) {
                $data['privacy']['options'][] = [
                    'title'       => $row['TITLE'],
                    'description' => $row['DESCRIPTION'],
                    'type'        => $row['TYPE'],
                    'options'     => $this->get_setting_options($row, $all_data),
                    'value'       => (isset($all_data[$key]) ? $all_data[$key]['setting_value'] : ''),
                ];
            }
            $data['notification']['title']       = 'Notification Settings';
            $data['notification']['subtitle']    = 'Notification Settings';
            $data['notification']['description'] = 'Which of the these do you want to receive email alerts about?';
            foreach (Config::get('constants_setting.notificationApi') as $key => $row) {
                $data['notification']['options'][] = [
                    'title'       => $row['TITLE'],
                    'description' => $row['DESCRIPTION'],
                    'type'        => $row['TYPE'],
                    'options'     => $this->get_setting_options($row, $all_data),
                ];
            }

            return \Api::success(['results' => $data]);
        }
        $data['settings'] = $all_data;

        return view('settings.privacySetting', $data)->with('title', 'Privacy Settings');
    }

    public function postSetting()
    {
        if($this->is_api) {

            return $this->users->save_settings_api($this->user_id);
        }
        $data['category'] = Input::get('category');
        $data['item']     = Input::get('item');
        $data['value']    = Input::get('value');

        return $this->users->post_settings($this->user_id, $data);
    }

    public function saveSetting(SettingRepository $setting)
    {//Save all setting in db

        $this->setting = $setting;
        $userId        = Auth::user()->id;
        $this->setting->saveAllSetting($userId);

    }

    public function changeProfilePicture(Request $request, Kinnect2 $k2)
    {

        if(!$request->hasFile('file')) {
            if($this->is_api) {
                return \Api::invalid_param();
            }
        }
        if($this->user_id > 0) {
            if($this->data->user->photo_id > 0) {
                $albumPhoto = $this->users->getDefaultAlbum($this->data->user->photo_id);

                if(!isset($albumPhoto->album_id)) {
                    $album_id = $this->users->insertDefaultAlbum($this->data->user->id);
                } else {
                    $album_id = $albumPhoto->album_id;
                }
            } else {
                $album_id = $this->users->insertDefaultAlbum($this->data->user->id);
            }

            $sm       = new StorageManager();
            $photo_id = $sm->saveAlbumPhoto($this->data->user->id, 'user', $request->file('file'), 'album_photo', 'Profile Photo', $album_id);

            //Making Thumbs
            if($photo_id > 0) {
                $photo = AlbumPhoto::wherePhotoId($photo_id)->select(['file_id'])->first();
                $file  = StorageFile::whereFileId(@$photo->file_id)->first();
                if(@$file->file_id) {
                    $ActivityActionRepository = new ActivityActionRepository();
                    $ActivityActionRepository->processPhoto($file, $this->user_id, $photo_id, 1, 1);
                }
                //making thumbs
                $folder_path = public_path('storage/temporary/users');
                if(!file_exists($folder_path)) {
                    if(!mkdir($folder_path, 0777, TRUE)) {
                        $folder_path = '';
                    }
                }
                $parent_photo = AlbumPhoto::find($photo_id);
                $file_name    = time() . rand(111111111, 9999999999);

                // <editor-fold desc="PROFILE_THUMB_WIDTH">
                $image1 = Image::make($request->file('file'))->encode('jpg');;
                $image1->resize(Config::get('constants.PROFILE_THUMB_WIDTH'), Config::get('constants.PROFILE_THUMB_HEIGHT'));

                if($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                    $photo_id = $sm->saveAlbumPhoto($this->data->user->id, 'user', $image1, 'album_photo', 'Profile Photo', $album_id, $parent_photo->file_id, 'thumb_profile', $parent_photo->photo_id);
                }
                // </editor-fold>

                //<editor-fold desc="PROFILE_ICON_HEIGHT">
                $image1 = Image::make($request->file('file'))->encode('jpg');;
                $image1->resize(Config::get('constants.PROFILE_ICON_WIDTH'), Config::get('constants.PROFILE_ICON_HEIGHT'));

                if($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                    $photo_id = $sm->saveAlbumPhoto($this->data->user->id, 'user', $image1, 'album_photo', 'Profile Photo', $album_id, $parent_photo->file_id, 'thumb_icon', $parent_photo->photo_id);
                }
                //</editor-fold>

                // <editor-fold desc="PROFILE_NORMAL_HEIGHT">
                $image1 = Image::make($request->file('file'))->encode('jpg');;
                $image1->resize(Config::get('constants.PROFILE_NORMAL_WIDTH'), Config::get('constants.PROFILE_NORMAL_HEIGHT'));

                if($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                    $photo_id = $sm->saveAlbumPhoto($this->data->user->id, 'user', $image1, 'album_photo', 'Profile Photo', $album_id, $parent_photo->file_id, 'thumb_normal', $parent_photo->photo_id);
                }
                //</editor-fold>

                // <editor-fold desc="WALL_IMAGE_HEIGHT">
                $image1 = Image::make($request->file('file'))->encode('jpg');;
                $image1->resize(Config::get('constants.WALL_IMAGE_WIDTH'), Config::get('constants.WALL_IMAGE_HEIGHT'));

                if($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                    $photo_id = $sm->saveAlbumPhoto($this->data->user->id, 'user', $image1, 'album_photo', 'Profile Photo', $album_id, $parent_photo->file_id, 'thumb_wall', $parent_photo->photo_id);
                }
                //</editor-fold>
            }
            //End of Making thumbs

            $user           = User::find($this->data->user->id);
            $user->photo_id = $photo_id;
            $user->save();

            $options = array(
                'type'         => \Config::get('constants_activity.OBJECT_TYPES.ALBUM_PHOTO.ACTIONS.UPDATE_PROFILE_PHOTO'),
                'subject'      => $this->user_id,
                'subject_type' => 'user',
                'object'       => $photo_id,
                'object_type'  => \Config::get('constants_activity.OBJECT_TYPES.ALBUM_PHOTO.NAME'),
                'body'         => \Config::get('constants_activity.ACTIVITY_LOG_MESSAGE.profile_update_photo'),
            );
            \Event::fire(new ActivityLog($options));
            $photo_url = $k2->getPhotoUrl($photo_id, $user->id, $type = NULL, 'thumb_normal');
            if($this->is_api) {
                return \Api::success(['profile_photo_url' => $photo_url]);
            }

            return $photo_url;
        }
    }

    public function changeCoverPicture(Request $request, Kinnect2 $k2)
    {

        if($this->is_api) {
            if(!$request->hasFile('file')) {
                return \Api::invalid_param();
            }
        }

        if($this->user_id > 0) {
            if($this->data->user->cover_photo_id > 0) {
                $albumPhoto = $this->users->getDefaultCoverAlbum($this->data->user->cover_photo_id);
                $album_id   = $albumPhoto->album_id;
                lbum_id;
            } else {
                $album_id = $this->users->insertNewDefaultAlbum('Cover Photos Album', 'user', 'Default Cover Photos Album', $type = 'cover_photos');
            }

            $sm             = new StorageManager();
            $cover_photo_id = $sm->saveAlbumPhoto($this->data->user->id, 'user', $request->file('file'), 'album_photo', 'User Profile Cover Photo', $album_id);

            //Making Thumbs
            if($cover_photo_id > 0) {
                //making thumbs
                $folder_path  = public_path('storage/temporary/users');
                $parent_photo = AlbumPhoto::find($cover_photo_id);
                $file_name    = time() . rand(111111111, 9999999999);

                // <editor-fold desc="WALL_IMAGE_HEIGHT">
                $image1 = Image::make($request->file('file'))->encode('jpg');;
                $image1->resize(Config::get('constants.WALL_IMAGE_WIDTH'), Config::get('constants.WALL_IMAGE_HEIGHT'));

                if($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                    $photo_id = $sm->saveAlbumPhoto($this->data->user->id, 'user', $image1, 'album_photo', 'Profile Photo', $album_id, $parent_photo->file_id, 'thumb_wall', $parent_photo->photo_id);
                }
                //</editor-fold>
            }

            //End of Making thumbs

            $user                 = User::find($this->data->user->id);
            $user->cover_photo_id = $photo_id;
            $user->save();
            if($this->is_api) {
                return \Api::success_data(['cover_photo_url' => $k2->getPhotoUrl($photo_id, $user->id, $type = NULL, 'thumb_normal')]);
            }

            return $k2->getPhotoUrl($photo_id, $user->id, $type = NULL, 'thumb_normal');
        }
    }

    public function get_all_kinnectors()
    {
        $user_id               = Input::get('userId');
        $user                  = $this->users->get_user($user_id);
        $friends['kinnectors'] = $this->users->friends($user->id);
        $friends['type']       = 'kinnectors';

        return view('templates.partials.paginate.users', $friends);

    }

    public function followers_paginate()
    {
        $user_id              = Input::get('userId');
        $user                 = $this->users->get_user($user_id);
        $friends['followers'] = $this->brandRepository->get_brand_kinnectors($user->id);
        $friends['type']      = 'followers';
        $friends['userId']    = $user->id;

        return view('templates.partials.paginate.users', $friends);
    }

    public function search_friends()
    {
        $userId         = \Input::get('userId');
        $searchType     = \Input::get('srchType');
        $key            = \Input::get('key');
        $data['type']   = $searchType;
        $data['userId'] = $userId;
        switch ($searchType) {
            case 'kinnectors':
                if(empty($key)) {
                    $data['kinnectors'] = $this->users->friends($userId);
                } else {
                    $data['kinnectors'] = $this->search_kinnectors($userId, $key);
                }
                break;
            case 'following':
                if(empty($key)) {
                    $data['brands'] = \Kinnect2::myAllBrands($userId);
                } else {
                    $data['brands'] = $this->searchMyBrands($userId, $key);
                }
                break;
            case 'followers':
                if(empty($key)) {
                    $data['followers'] = $this->brandRepository->get_brand_kinnectors($userId);
                } else {
                    $data['followers'] = $this->searchBrandKinnectors($userId, $key);
                }
                break;
            case 'all_recommended':
                if(empty($key)) {
                    $data['all_recommended'] = $this->friendshipRepository->all_recommended($userId, NULL);
                } else {
                    $data['all_recommended'] = $this->friendshipRepository->all_recommended_search($userId, $key);
                }
                break;
            case 'recommended-brands':
                if(empty($key)) {
                    $data['brands'] = \Kinnect2::recomendedAllBrands();
                } else {
                    $data['brands'] = \Kinnect2::recommendedAllBrandsSearch($key);
                }

        }

        return view('templates.partials.paginate.users', $data);

    }

    private function search_kinnectors($userId, $key)
    {
        return $this->users->search_kinnectors($userId, $key);
    }

    private function searchMyBrands($userId, $key)
    {
        return $this->users->searchMyBrands($userId, $key);
    }

    private function searchBrandKinnectors($userId, $key)
    {
        return $this->users->searchBrandKinnectors($userId, $key);
    }

    private function get_setting_options($row, $all_data)
    {
        $options = [];
        if($row['TYPE'] == 'multi-check') {
            foreach ($row['OPTIONS'] as $optKey => $option) {
                $options[] = [
                    'description' => $option,
                    'id'          => $optKey,
                    'value'       => (isset($all_data[$optKey]) ? $all_data[$optKey]['setting_value'] : '0'),
                ];
            }
        } else {
            foreach ($row['OPTIONS'] as $optKey => $option) {
                $options[] = [
                    'description' => $option,
                    'id'          => $optKey,
                ];
            }
        }

        return $options;
    }

    public function leaderBoards()
    {
        $users                = \Kinnect2::LeaderboardUsers();
        $leaderBoard['users'] = $this->leaderBoardsDetail($users);

        $leaderBoard['brands'] = $this->leaderBoardsDetail(\Kinnect2::LeaderboardBrands());

        return \Api::success_list($leaderBoard);
    }

    private function leaderBoardsDetail($users)
    {
        $allUser = [];
        foreach ($users as $user) {
            //$data['name']              = $user->displayname;
            //$data['id']              = $user->id;
            $user->profile_url       = $user->username;
            $user->profile_photo_url = \Kinnect2::getPhotoUrl($user->photo_id, $user->id, 'user', 'thumb_normal');

            if($user->user_type == Config::get('constants.REGULAR_USER')) {
                $is_friend       = \Kinnect2::is_friend($user->id, $this->user_id);
                $user->is_friend = ($is_friend ? '1' : '0');
            } else {
                $is_following       = \Kinnect2::is_following($this->user_id, $user->id);
                $user->is_following = ($is_following ? '1' : '0');
            }

            $allUser[] = $user;
        }

        return $allUser;
    }

    public function changeCover(Request $request)
    {
        if($this->is_api) {
            if(!$request->hasFile('file')) {
                return \Api::invalid_param();
            }
        }
        $action = new ActivityActionRepository();
        $data   = $action->uploadFile($this->user_id, $request->file('file'), 'album_photo');
        $sfObj  = new StorageFile();

        $sfObj->parent_file_id = !empty($data['parent_file_id']) ? $data['parent_file_id'] : NULL;
        $sfObj->type           = !empty($data['type']) ? $data['type'] : NULL;
        $sfObj->parent_id      = isset($data['parent_id']) ? $data['parent_id'] : NULL;
        $sfObj->parent_type    = $data['parent_type'];
        $sfObj->user_id        = $data['user_id'];
        $sfObj->storage_path   = $data['storage_path'];
        $sfObj->extension      = $data['extension'];
        $sfObj->name           = $data['name'];
        $sfObj->mime_type      = $data['mime_type'];
        $sfObj->size           = $data['size'];
        $sfObj->hash           = $data['hash'];
        $sfObj->save();
        $photo_id             = $sfObj->file_id;
        $user                 = User::find($this->data->user->id);
        $user->cover_photo_id = $photo_id;
        $user->save();
        if($this->is_api) {
            return \Api::success_data(['cover_photo_url' => \Kinnect2::getPhotoUrl($photo_id, $user->id, $type = NULL, 'thumb_normal')]);
        }
    }

}