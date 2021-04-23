<?php

namespace App\Http\Controllers;

use App\ActivityAction;
use App\AlbumPhoto;
use App\Brand;
use App\Consumer;
use App\Repository\Eloquent\ActivityActionRepository;
use App\StorageFile;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Config;
use Response;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Illuminate\Mail\Message;
use SNS;
use DB;

class ApiController extends Controller
{
    private $user_id;

    /**
     * ApiController constructor.
     *
     * @param Request $middleware
     */
    public function __construct(Request $middleware) {
        if(isset($middleware['middleware']['user_id'])) {
            $this->user_id = $middleware['middleware']['user_id'];
            $this->user    = $middleware['middleware']['user'];
        }
        //$this->middleware = $middleware;
    }

    public static function return_response($data, array $params) {
        $data['error'] = $params['error'];
        $data['code']  = $params['code'];
        return $data;
    }

    public function get_user_id(Request $middleware) {
        return $middleware['middleware']['user_id'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store User from API.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $rules      = array(
            'email'    => 'required|unique:users|email',
            'password' => 'required'
        );
        $validation = \Validator::make($request->all(), $rules);

        if($validation->fails()) {
            $msg = '';
            if($validation->errors()->has('email')) {
                $email = $validation->errors()->get('email');
                $msg .= $email[0];
            }
            if($validation->errors()->has('password')) {
                $password = $validation->errors()->get('password');
                $msg .= '\n' . $password[0];
            }

            return \Api::already_done($msg);
        }
        $activation_code = str_random(60) . $request->input('email');

        $user        = new User();
        $user->email = $request->email;

        $user->name        = $request->first_name . ' ' . $request->last_name;
        $user->first_name  = $request->first_name;
        $user->last_name   = $request->last_name;
        $user->displayname = $request->first_name . ' ' . $request->last_name;

        $user->password        = bcrypt($request->password);
        $user->user_type       = $request->user_type;
        $user->country         = $request->country;
        $user->activation_code = $activation_code;
        $expiry_date           = Carbon::now();
        $expiry_date->addDays(29);
        $user->token_expiry_date = $expiry_date;

        $user->save();

//        $user->username = $request->first_name . '-' . $request->last_name . $user->id;

        if($user->user_type == Config::get('constants.REGULAR_USER')) {
            $username                 = $this->slugify($request->first_name . '-' . $request->last_name, ['table' => 'users', 'field' => 'username']);
            $user->username  = $username;
            $consumer                 = new Consumer();
            $consumer->gender         = $request->gender;
            $consumer->birthdate      = (int)$request->year . '-' . (int)$request->month . '-' . (int)$request->date;
            $consumer->about_me       = $request->about_me;
            $consumer->personnel_info = $request->personnel_info;

            $consumer->save();
            $consumer->user()->save($user);
        } else {
            $username          = $this->slugify($request->brand_name, ['table' => 'users', 'field' => 'username']);
            $user->username    = $username;
            $brand                = new Brand();
            $brand->brand_name    = $request->brand_name;
            $brand->brand_history = $request->brand_history;
            $brand->description   = $request->description;
            $user->displayname    = $request->brand_name;
            //$brand->page_manager = $request->page_manager;
            $brand->save();
            $brand->user()->save($user);
        }

        $this->sendEmail($user);
        return \Api::success_with_message('Registered Successfully. Please Verify your email');
        //return array('success' => 'Registered Successfully');

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

    public function sendEmail(User $user) {

        $data = array(
            'name' => $user->name,
            'code' => $user->activation_code,
        );

        \Mail::queue('emails.activateAccount', $data, function ($message) use ($user) {
            $message->subject(\Lang::get('auth.pleaseActivate'));
            $message->to($user->email);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function login() {

        if(Auth::attempt(['email' => \Input::get('email'), 'password' => \Input::get('password'), 'active' => 1])) {
            $access_token = Authorizer::issueAccessToken();
            $data         = array(
                'base_url'     => url(),
                'data'         => $this->_get_user_meta(Auth::user()),
                'access_token' => $access_token
            );
            return \Api::success($data);
        } else {
            return \Api::invalid_param('Please provide valid credentials!');

        }
    }

    public function _get_user_meta($user) {
        //$photo    = AlbumPhoto::where( 'photo_id', $user->photo_id )->with('storage_file')->first();
        $photo                     = AlbumPhoto::where('photo_id', $user->photo_id)
                                               ->select(['file_id'])
                                               ->first();
        $file_id                   = isset($photo->file_id) ? $photo->file_id : NULL;
        $file                      = StorageFile::where('file_id', $file_id)->first();
        $path                      = isset($file->storage_path) ? $file->storage_path : NULL;
        $user['profile_photo_url'] = '';
        if(!empty($path)) {
            $user['profile_photo_url'] = \Config::get('constants_activity.PHOTO_URL') . $path . '?type=' . urlencode($file->mime_type);
        }

        $file_id                 = isset($user->cover_photo_id) ? $user->cover_photo_id : NULL;
        $file                    = StorageFile::where('file_id', $file_id)->first();
        $path                    = isset($file->storage_path) ? $file->storage_path : NULL;
        $user['cover_photo_url'] = '';
        if(!empty($path)) {
            $user['cover_photo_url'] = \Config::get('constants_activity.PHOTO_URL') . $path . '?type=' . urlencode($file->mime_type);
        }

        return $user;
    }

    public function left_sidebar() {
        //return \Kinnect2::myBattles();
        $response['my_brands']           = $this->_get_detail(\Kinnect2::myBrands(4, $is_sidebar = TRUE));
        $response['recommended_brands']  = $this->_get_detail(\Kinnect2::recomendedBrands(4, $is_sidebar = TRUE));
        $response['my_groups']           = $this->get_group_detail(\Kinnect2::myGroups(4));
        $response['recommended_groups']  = $this->get_group_detail(\Kinnect2::recomendedGroups(4));
        $response['my_polls']            = $this->get_poll_detail(\Kinnect2::myPolls(3));
        $response['recommended_polls']   = $this->get_poll_detail(\Kinnect2::recomendedPolls(5), TRUE);
        $response['recommended_battles'] = $this->get_poll_detail(\Kinnect2::recomendedBattles(5), TRUE, 'battle');
        $response['my_battles']          = $this->get_poll_detail(\Kinnect2::myBattles(3));

        return \Api::success($response);

    }

    public function _get_detail($brands) {
        return \Kinnect2::_brand_details($brands);
    }

    public function get_group_detail($groups) {
        $all       = [];
        $allGroups = [];
        if(!empty($groups)) {
            foreach ($groups as $group) {
                $allGroups[] = $this->_group_detail_meta($group);
            }
        }
        return $allGroups;
    }

    public function _group_detail_meta($group) {
        $group['photo_url']         = \Kinnect2::getPhotoUrl($group->photo_id, $group->id, 'group', 'group_thumb');
        $group['profile_photo_url'] = \Kinnect2::getPhotoUrl($group->photo_id, $group->id, 'group', 'group_thumb');//\Kinnect2::get_photo_path($group->photo_id);
        $group['cover_photo_url']   = \Kinnect2::getPhotoUrl($group->cover_photo_id, $group->id, 'group', 'group_thumb');
        return $group;
    }

    /**
     * @param $polls
     * @param bool $privacy_check
     * @param string $type
     *
     * @return array
     * @internal param Request $middleware
     * @internal param bool|false $privacy
     */
    public function get_poll_detail($polls, $privacy_check = FALSE, $type = 'poll') {

        $all      = [];
        $allPolls = [];
        $privacy  = TRUE;
        $l        = 0;
        if(!empty($polls)) {
            foreach ($polls as $poll) {
                if($privacy_check) {
                    if($l > 0) {
                        break;
                    }
                    $privacy = is_allowed($poll->id, $type, 'view', $this->user_id, $poll->user_id);
                }
                if($privacy) {
                    $l++;
                    /*$all['title'] = $poll->title;
                    $all['id'] = $poll->id;*/
                    $poll['profile_photo_url'] = get_photo_by_user_id($poll->user_id);
                    $allPolls[]                = $poll;
                }
            }
        }
        return $allPolls;
    }

    public function get_signup() {
        $data['countries']  = \DB::table('countries')->orderBy('name', 'ASC')->lists('name', 'id');
        $data['industries'] = \DB::table('brand_industries')->orderBy('name', 'ASC')->lists('name', 'id');
        return \Api::success($data);
    }

    public function forget_password(Request $request) {
        if(!isset($request->email) || empty($request->email)) {
            return \Api::invalid_param();
        }
        $response = \Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject('Your Password Reset Link');
        });
        switch ($response) {
            case \Password::RESET_LINK_SENT:
                return \Api::success_with_message(trans($response));

            case \Password::INVALID_USER:
                return \Api::other_error('Invalid Email');
        }
    }

    public function static_page() {
        $page = \Input::get('page');
        return view('includes.static.' . $page)->render();
    }

    public function url_metadata() {
        if(!\Input::has('url')) {
            return \Api::invalid_param();
        }

        $url  = \Input::get('url');
        $actvityActionObj = new ActivityActionRepository();
        $data = $actvityActionObj->extractLinkMeta($url);
        return \Api::success_data($data);
    }

    public function registerPushEndpoint() {
        SNS::createPlatformEndpoint(\Input::get('user_id'), \Input::get('device_token'), \Input::get('user_data'), \Input::get('platform'), []);
        return \Api::success_data(array("is" => "OK", "reason" => "test"));

    }

    public function profile_image() {
        $user     = $this->user;
        $photo_id = $user->photo_id;
        $cover_id = $user->cover_photo_id;

        $data['profile_photo_url'] = \Kinnect2::getPhotoUrl($photo_id, $user->id, 'user', 'thumb_normal');
        $data['cover_photo_url']   = \Kinnect2::getPhotoUrl($cover_id, $user->id, 'user', 'cover_photo');
        return \Api::success_data($data);
    }

    public function versionUpgrade() {
        $data = [
            'update-available' => 0,
            'display-alert'    => 0,
            'force-upgrade'    => 0,
            'app-version'      => 0.1,
        ];
        return \Api::success_data($data);
    }
}
