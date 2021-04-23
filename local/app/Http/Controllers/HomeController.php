<?php

namespace App\Http\Controllers;

use App\Events\SendEmail;
use App\Facades\Kinnect2;
use App\Feedback;
use Carbon\Carbon;
use DB;
use App\User;
use Illuminate\Http\Request;
use App\ActivityAction;
use App\Repository\Eloquent\ActivityActionRepository;
use Auth;
use kinnect2Store\Store\StoreProduct;
use App\Services\StorageManager;

class HomeController extends Controller
{

    protected $ActivityActionRepository;

    protected $data;
    protected $is_api;
    private   $user_id;

    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

    /**
     * Create a new controller instance.
     *
     * @param ActivityActionRepository $ActivityAction
     * @param Request                  $middleware
     */


    public function __construct(ActivityActionRepository $ActivityAction, Request $middleware) {
        $this->ActivityActionRepository = $ActivityAction;
        $this->user_id                  = $middleware['middleware']['user_id'];
        @$this->data->user = $middleware['middleware']['user'];
        $this->is_api = $middleware['middleware']['is_api'];
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index() {

        if (!Auth::check()) {
            return view('auth.login');
        }
        $hashTag = \Request::get('hashTag');
        $data = array('hashTag' => $hashTag);
       
        return view('home', $data);
    }

    public function search($search_term = NULL) {
        if ($this->is_api) {
            if (!\Input::has('query')) {
                return \Api::invalid_param();
            }
            $search_term = \Input::get('query');
        }

        $users = $this->_search($search_term);
        if ($this->is_api) {
            return \Api::success_list($users);
        }

        return json_encode($users);
    }

    public function mobile_search() {
        $search_term = \Input::get('search');
        $users       = $this->_search($search_term);


        return view('advance-search', ['users' => $users]);
    }

    public function _search($search_term) {
        $users = DB::table('users')
            ->select('users.id', 'users.name', 'users.displayname', 'users.username', 'users.userable_type', 'users.photo_id as image', 'users.userable_id', 'users.country')
            ->where('users.search', '1')
            ->where('users.active', '1')
            ->where('users.user_type', 1)
//          ->orWhere('users.username', 'like', $search_term . '%')
            ->where('users.displayname', 'like', $search_term . '%')
            ->get();

        foreach ($users as $user) {
            $user->image  = Kinnect2::getPhotoUrl($user->image, $user->id, 'user', 'thumb_icon');
            $user->gender = $this->getUserGender($user->userable_id);
        }

        // Search for brands
        $userBrands = DB::table('users')
            ->select('users.id', 'users.name', 'users.displayname', 'users.username', 'users.userable_type', 'users.photo_id as image', 'users.userable_id', 'users.country')
            ->join('users_brands', 'users_brands.id', '=', 'users.userable_id')
            ->where('users.search', '1')
            ->where('users.active', '1')
            ->where('users.user_type', 2)
            ->where('users_brands.brand_name', 'like', $search_term . '%')
            ->get();

        foreach ($userBrands as $user) {
            $user->image  = Kinnect2::getPhotoUrl($user->image, $user->id, 'user', 'thumb_icon');
        }

        $users = array_merge($users, $userBrands);

        return $users;
    }

    public function getUserGender($id = NULL) {
        if (is_null($id)) {
            $id = Auth::user()->userable_id;
        }
        $user = \Cache::get('_gender_'.$id,function () use ($id){
            return \App\Consumer::select('id', 'gender')->where('id', $id)->first();
        });

        if (isset($user->gender)) {
            return ($user->gender == 1 ? 'Male' : 'Female');
        }

        return 'Male';

    }

    public function advancedSearch(Request $request) {

        $search_term = $request->search_term;

        $data['profile_type'] = $profile_type = $request->profile_type;


        if ($search_term != '' AND $profile_type > 0) {
            $data['search'] = $search_term;
            $data['users']  = User::orderBy('name')
                ->where('user_type', $profile_type)
                ->where('active', 1)
                ->where('users.displayname', 'like', $search_term . '%')
                ->paginate(25);
        }

        if ($search_term != '' AND $profile_type == '') {
            $data['search'] = $search_term;
            $data['users']  = User::orderBy('name')
                ->where('active', 1)
                ->where('users.displayname', 'like', $search_term . '%')
                ->paginate(25);
        }

        if ($search_term == '' AND $profile_type == '') {
            $data['users'] = User::orderBy('displayname')
                ->where('active', 1)
                ->paginate(25);
        }
        if ($search_term == '' AND $profile_type > 0) {
            $data['users'] = User::orderBy('displayname')
                ->where('active', 1)
                ->where('users.user_type', $profile_type)
                ->paginate(25);
        }
        $data['users']->setPath('advanced_search');

        return view('advance-search', $data);
    }

    public function advancedSearchPost(Request $request) {


        $data['profile_type'] = $profile_type = $request->profile_type;
        $data['search']       = $search = $request->search;
        //$data['users'] = [];
        if ($search != '' AND $profile_type > 0) {
            $data['users'] = User::orderBy('displayname')
                ->where('active', 1)
                ->where('users.user_type', $profile_type)
                ->where('users.displayname', 'like', $search . '%')
                ->paginate(25);
        }

        if ($search == '' AND $profile_type > 0) {
            $data['users'] = User::orderBy('displayname')
                ->where('active', 1)
                ->where('users.user_type', $profile_type)
                ->where('users.displayname', 'like', $search . '%')
                ->paginate(25);
        }

        if ($search != '' AND $profile_type == '') {
            $data['users'] = User::orderBy('displayname')
                ->where('active', 1)
                ->where('users.displayname', 'like', $search . '%')
//                ->orWhere('users.username', 'like', $search . '%')
                ->paginate(25);
        }

        if ($search == '' AND $profile_type == '') {
            $data['users'] = User::orderBy('displayname')
                ->where('active', 1)
                ->paginate(25);
        }

        if($search != '' && $profile_type == 3){
            $data['products'] = StoreProduct::where('title','LIKE',"$search%")->paginate(25);
        }

        $data['users']->setPath('advanced_search');

        return view('advance-search', $data);
    }


    public function getGroupPosts(Request $request, $group_id = NULL) {
        if ($this->is_api) {
            $group_id = \Input::get('group_id');
            if (empty($group_id)) {
                return \Api::invalid_param();
            }
        }
        $take = $request->input('take');
        $skip = $request->input('skip');
        $take = !empty($take) ? $take : 3;
        $skip = !empty($skip) ? $skip : 0;

        $type   = $request->input('type');
        $object = $request->input('object');

        $type   = !empty($type) ? $type : 'all';
        $object = !empty($object) ? $object : NULL;

        $data = $this->ActivityActionRepository->getGroupPosts($this->user_id, $group_id, $take, $skip, $type, $object,$this->is_api);
        if ($this->is_api) {
            return \Api::time_line_posts($data);
        } else {
            return response()->json($data);
        }

    }

    public function getPost($post_id = NULL) {
        if ($this->is_api) {
            if (!\Input::has('post_id')) {
                return \Api::invalid_param();
            }
            $post_id = \Input::get('post_id');
        }
        $is_popup         = \Input::get('popup');
        $data             = $this->ActivityActionRepository->getPostByID($post_id, $this->user_id, $is_popup);
        $data['base_url'] = url();
        if (!empty($data)) {
            $comments = $this->ActivityActionRepository->getComments($this->user_id, $post_id, 1000);

            $data = $comments + $data;
        }

        if ($this->is_api) {
            $client = \Input::get('client');
            if ($client) {
                $temp['posts'][] = $data;
                $temp['base_url'] = url();

                return \Api::time_line_posts($temp);
            } else {
                return \Api::success_data($data);
            }
        }
        if (\Request::ajax()) {
            return response()->json($data);
        } else {
            return $data;
        }
    }

    public function getLatestPosts(Request $request) {

    }

    public function pull(Request $request) {
        $last_id = $request->input('last_id');
        $take    = $request->input('take');
        $skip    = $request->input('skip');
        $take    = !empty($take) ? $take : 5;
        $skip    = !empty($skip) ? $skip : 0;

        $type   = $request->input('type');
        $object = $request->input('object');
        $hashTag = $request->input('hashTag');
        
        $type   = !empty($type) ? $type : 'all';
        $object = !empty($object) ? $object : NULL;

        $data = $this->ActivityActionRepository->getPosts($this->user_id, $take, $skip, $type, $object, NULL, $last_id,$this->is_api,$hashTag);
        if ($this->is_api) {
            //return response()->json($data);
            return \Api::time_line_posts($data);
        } else {
            return response()->json($data);
        }
    }

    public function getUserPosts(Request $request, $user_href = NULL) {
        $take = \Input::get('take');
        $skip = \Input::get('skip');
        $take = !empty($take) ? $take : 5;
        $skip = !empty($skip) ? $skip : 0;
        $hashTag = $request->input('hashTag');
        if ($this->is_api) {
            if (isset($request['user_id'])) {
                $user_id = \Input::get('user_id');
                $user    = User::whereUsername($user_id)->orWhere('id', $user_id)->select(['id'])->first();
                $user_id = $user->id;
            } else {
                $user_id = $this->user_id;
            }

        } elseif (empty($user_href)) {
            $user_id = $this->user_id;
        } else {
            $user = User::where('username', $user_href)->select(['id'])->first();

            $user_id = $user->id;
        }

        $type   = \Request::input('type');
        $object = \Request::input('object');

        $type   = !empty($type) ? $type : 'all';
        $object = !empty($object) ? $object : NULL;

        $data = $this->ActivityActionRepository->getUserPosts($user_id, $take, $skip, $object,$this->is_api,$hashTag);

        if ($this->is_api) {
            return \Api::time_line_posts($data);
        } else {
            return response()->json($data);
        }
    }

    public function getComments($action_id = NULL) {
        if ($this->is_api) {
            $action_id = \Input::get('action_id');
        }

        $take = \Input::get('take');
        $skip = \Input::get('skip');
        $take = !empty($take) ? $take : 10;
        $skip = !empty($skip) ? $skip : 0;

        $data = $this->ActivityActionRepository->getComments($this->user_id, $action_id, $take, $skip);

        if ($this->is_api) {
            return \Api::time_line_posts($data);
        } else {
            return response()->json($data);
        }
    }

    public function getCommentsThreaded(Request $request) {
        $comment_id = $request->get('comment_id');
        if (empty($comment_id)) {
            if ($this->is_api) {
                return \Api::invalid_param();
            }
        }
        $data = $this->ActivityActionRepository->getCommentsThreaded($this->user_id, $comment_id);

        if (!$data) {
            if ($this->is_api) {
                return \Api::result_not_found();
            }
        }
        if ($this->is_api) {
            return \Api::time_line_posts(['comments' => $data, 'comments_count' => count($data)]);
        } else {
            return response()->json($data);
        }
    }

    public function postStatus(Request $request) {
        $photos = $request->input('tokens');

        $video       = $request->file('video');
        $text        = $request->input('text');
        $link        = $request->input('link');
        $target_type = $request->input('target_type');

        $audio = $request->file('audio');

        $target_id = 0;

        if ($target_type == 'group') {
            $target_id = $request->input('target_id');
        }

        $message = $this->ActivityActionRepository->shareStatus($this->user_id, $photos, $video, $text, $audio, $link, $target_type, $target_id, TRUE);

        if ($this->is_api) {
            return \Api::success($message);
        } else {
            return response()->json($message);
        }
    }

    public function uploadImage(Request $request) {
        $value = $request->file('photos');
        $token = [];

        if (!$value->isValid()) {
            if ($this->is_api) {
                return \Api::other_error('Invalid file');
            }

            return response()->json(['message' => 'invalid_file']);
        }
        $file_size    = $value->getSize();
        $allowed_size = 4;
        if ($file_size > ($allowed_size * 1024 * 1024)) {
            return ['message' => 'file_size_exceeded', 'limit' => $allowed_size . 'MB'];
        }
        $data            = $this->ActivityActionRepository->uploadFile($this->user_id, $value, 'album_photo');
        $data['is_temp'] = 1;

        $file_id = $this->ActivityActionRepository->saveFile($data);

        $path = \Config::get('constants_activity.PHOTO_URL');

        $token[] = ['file_type' => 'new','token' => $file_id, 'path' => $path . $data['storage_path'] . '?type=' . urlencode($data['mime_type'])];

        if ($this->is_api) {
            $api['data'] = ['file_type' => 'new','token' => $file_id, 'path' => $path . $data['storage_path'] . '?type=' . urlencode($data['mime_type'])];

            return \Api::success($api);
        }
        $data = ['message' => 'file_uploaded', 'token' => $token];

        return response()->json($data);
    }

    public function shareGroup($group_id) {
        $text = \Input::get('text');

        $message = $this->ActivityActionRepository->shareGroup($this->user_id, $group_id);

        return redirect()->back();
        //return response()->json($message);
    }

    public function shareEvent($event_id) {
        $text = \Input::get('text');

        $message = $this->ActivityActionRepository->shareEvent($this->user_id, $event_id);

        if ($this->is_api) {
            return \Api::success($message);
        } else {
            return redirect()->back();

        }
    }

    public function editStatus(Request $request) {
        $body               = $request->input('post_body');
        $activity_action_id = $request->input('action_id');
        $photos = $request->input('tokens');
        $tokens_old = $request->input('tokens_old');
        
        $message = $this->ActivityActionRepository->editActivity($activity_action_id, $this->user_id, $body,$photos,$tokens_old);
        $post = $this->ActivityActionRepository->getPostByID($activity_action_id,$this->user_id);
        if ($this->is_api) {
            return \Api::_response_data(['status' => $message,'post' => $post]);
        } else {
            return response()->json(['status' => $message,'post' => $post]);
        }

    }

    public function deleteStatus($activity_action_id = NULL) {
        if ($this->is_api) {
            $activity_action_id = \Input::get('action_id');
        }

        $message = $this->ActivityActionRepository->deleteActivity($this->user_id, $activity_action_id);

        if ($this->is_api) {
            return \Api::_response_data($message);
        } else {
            return response()->json($message);
        }
    }

    public function postComment(Request $request, $activity_action_id = NULL) {

        if ($this->is_api) {
            $activity_action_id = \Input::get('action_id');
        }

        $body        = $request->input('comment_body');
        $attachment = $request->file('attachment');
        $poster_type = \Config::get('constants_activity.OBJECT_TYPES.USER.NAME');

         $message = $this->ActivityActionRepository->addComment($activity_action_id, $this->user_id, $poster_type, $body, TRUE,$attachment);

        if ($this->is_api) {
            return \Api::_response_data($message);
        } else {
            return response()->json($message);
        }
    }

    public function deleteActivityComment($comment_id = NULL) {
        if ($this->is_api) {
            if (!\Input::has('comment_id')) {
                return \Api::invalid_param();
            }
            $comment_id = \Input::get('comment_id');
        }
        $message = $this->ActivityActionRepository->deleteActivityComment($comment_id, $this->user_id, 'user');

        if ($this->is_api) {
            return \Api::_response_data($message);
        } else {
            return response()->json($message);
        }

    }

    public function postCommentThreaded(Request $request, $parent_comment_id = NULL, $activity_action_id = NULL) {
        if ($this->is_api) {
            $activity_action_id = \Input::get('action_id');
            $parent_comment_id  = \Input::get('parent_comment_id');
        }else{
            $activity_action_id = \Input::get('action_id');
            $parent_comment_id  = \Input::get('parent_comment_id');
        }

        $body        = $request->input('comment_body');
        $poster_type = \Config::get('constants_activity.OBJECT_TYPES.USER.NAME');

        $message = $this->ActivityActionRepository->addCommentThreaded($parent_comment_id, $activity_action_id, $this->user_id, $poster_type, $body,True);

        if ($this->is_api) {
            return \Api::_response_data($message);
        } else {
            return response()->json($message);
        }
    }

    public function likeStatus($activity_action_id = NULL) {
        if ($this->is_api) {
            $activity_action_id = \Input::get('action_id');
        }
        $poster_type = \Config::get('constants_activity.OBJECT_TYPES.USER.NAME');

        $message = $this->ActivityActionRepository->likeStatus($activity_action_id, $this->user_id, $poster_type, TRUE);

        if ($this->is_api) {
            return \Api::_response_data($message);
        } else {
            return response()->json($message);
        }
    }

    public function unlikeStatus($activity_action_id = NULL) {
        if ($this->is_api) {
            $activity_action_id = \Input::get('action_id');
        }
        $poster_type = \Config::get('constants_activity.OBJECT_TYPES.USER.NAME');

        $message = $this->ActivityActionRepository->unlikeStatus($activity_action_id, $this->user_id, $poster_type, TRUE);
        if ($this->is_api) {
            return \Api::_response_data($message);
        } else {
            return response()->json($message);
        }

    }

    public function likeActivityComment($comment_id = NULL) {
        if ($this->is_api) {
            $comment_id = \Input::get('comment_id');
        }
        $poster_type = \Config::get('constants_activity.OBJECT_TYPES.USER.NAME');


        $message = $this->ActivityActionRepository->likeActivityComment($comment_id, $this->user_id, $poster_type);

        if ($this->is_api) {
            return \Api::_response_data($message);
        } else {
            return response()->json($message);
        }
    }

    public function changeCoverPhoto(Request $request) {
        $value = $request->file('photo');

        if (!$value->isValid()) {
            return FALSE;
        }

        $data = $this->ActivityActionRepository->uploadFile($this->user_id, $value, 'album_photo');

        $data['is_temp'] = 1;

        $file_id = $this->ActivityActionRepository->saveFile($data);

        $path = \Config::get('constants_activity.PHOTO_URL');

        $token = ['token' => $file_id, 'path' => $path . $data['storage_path'] . '?type=' . urlencode($data['mime_type'])];

        $data = ['message' => 'file_uploaded', 'token' => $token];

        return response()->json($data);
    }

    public function saveCoverPhoto(Request $request) {
        $imgUrl = $_POST['imgUrl'];

        $imgInitW = $_POST['imgInitW'];
        $imgInitH = $_POST['imgInitH'];

        $imgW = $_POST['imgW'];
        $imgH = $_POST['imgH'];
        // offsets
        $imgY1 = $_POST['imgY1'];
        $imgX1 = $_POST['imgX1'];
        // crop box
        $cropW = $_POST['cropW'];
        $cropH = $_POST['cropH'];
        // rotation angle
        $angle        = $_POST['rotation'];
        $jpeg_quality = 70;
        $what         = getimagesize($imgUrl);
        switch (strtolower($what['mime'])) {
            case 'image/png':
                $img_r        = imagecreatefrompng($imgUrl);
                $source_image = imagecreatefrompng($imgUrl);
                $type         = '.png';
                break;
            case 'image/jpeg':
                $img_r        = imagecreatefromjpeg($imgUrl);
                $source_image = imagecreatefromjpeg($imgUrl);
                error_log("jpg");
                $type = '.jpeg';
                break;
            case 'image/gif':
                $img_r        = imagecreatefromgif($imgUrl);
                $source_image = imagecreatefromgif($imgUrl);
                $type         = '.gif';
                break;
            default:
                die('image type not supported');
        }

        // resize the original image to size of editor
        $resizedImage = imagecreatetruecolor($imgW, $imgH);
        imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);
        // rotate the rezized image
        $rotated_image = imagerotate($resizedImage, -$angle, 0);
        // find new width & height of rotated image
        $rotated_width  = imagesx($rotated_image);
        $rotated_height = imagesy($rotated_image);
        // diff between rotated & original sizes
        $dx = $rotated_width - $imgW;
        $dy = $rotated_height - $imgH;
        // crop rotated image to fit into original rezized rectangle
        $cropped_rotated_image = imagecreatetruecolor($imgW, $imgH);
        imagecolortransparent($cropped_rotated_image, imagecolorallocate($cropped_rotated_image, 0, 0, 0));
        imagecopyresampled($cropped_rotated_image, $rotated_image, 0, 0, $dx / 2, $dy / 2, $imgW, $imgH, $imgW, $imgH);
        // crop image into selected area
        $final_image = imagecreatetruecolor($cropW, $cropH);
        imagecolortransparent($final_image, imagecolorallocate($final_image, 0, 0, 0));
        imagecopyresampled($final_image, $cropped_rotated_image, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);

        ob_start();

        imagejpeg($final_image, NULL, $jpeg_quality);
        $imageString = ob_get_clean();

        imagedestroy($final_image);

        $group_id = $request->get('group_id');

        $data = $this->ActivityActionRepository->saveCoverPhoto($this->user_id, $imageString, $group_id);

        //return redirect()->back();

        return response()->json($data);
    }

    public function makeActivityFavourite($activity_action_id = NULL) {
        if ($this->is_api) {
            $activity_action_id = \Input::get('action_id');
        }
        $poster_type = \Config::get('constants_activity.OBJECT_TYPES.USER.NAME');
        $message     = $this->ActivityActionRepository->makeActivityFavourite($activity_action_id, $this->user_id, $poster_type);

        if ($this->is_api) {
            return \Api::_response_data($message);
        } else {
            return response()->json($message);
        }
    }

    public function removeActivityFavourite($activity_action_id = NULL) {
        if ($this->is_api) {
            $activity_action_id = \Input::get('action_id');
        }
        $poster_type = \Config::get('constants_activity.OBJECT_TYPES.USER.NAME');
        $message     = $this->ActivityActionRepository->removeActivityFavourite($activity_action_id, $this->user_id, $poster_type);

        return response()->json($message);
    }

    public function shareActivity(Request $request) {

        $text        = $request->input('text');
        $object_id   = $request->input('object_id');
        $object_type = $request->input('object_type');

        $message = $this->ActivityActionRepository->shareActivity($this->user_id, $text, $object_id, $object_type, TRUE);

        return response()->json($message);
    }

    /**
     * @param Request $request
     */
    public function feedback(Request $request) {
        $feedback          = new Feedback();
        $feedback->user_id = $this->user_id;
        if (!$request->has('feedback')) {
            if ($this->is_api) {
                return \Api::invalid_param();
            }
        }
        $feedback->feedback = $request->feedback;
        $data               = array(
            'message'  => $request->feedback,
            'from'     => $this->data->user->email,
            'name'     => $this->data->user->displayname,
            'template' => 'feedback',
        );
        $feedback->save($data);
        //return redirect()->back();


        \Event::fire(new SendEmail($data));
        if ($this->is_api) {
            return \Api::success_with_message('Feedback sent successfully');
        }
        //return $request->all();
    }

    public function likePhoto($item_id = NULL) {
        if ($this->is_api) {
            $item_id = \Input::get('item_id');
        }
        $poster_type = \Config::get('constants_activity.OBJECT_TYPES.USER.NAME');
        $message     = $this->ActivityActionRepository->likeItem($item_id, 'photo', $this->user_id, $poster_type);

        return response()->json($message);

    }

    public function unLikePhoto($item_id = NULL) {
        if ($this->is_api) {
            $item_id = \Input::get('item_id');
        }
        $poster_type = \Config::get('constants_activity.OBJECT_TYPES.USER.NAME');
        $message     = $this->ActivityActionRepository->unLikeItem($item_id, 'photo', $this->user_id, $poster_type);

        return response()->json($message);

    }

    public function commentPhoto($photo_id = NULL) {
        if ($this->is_api) {
            $photo_id = \Input::get('photo_id');
        }
        $poster_type = \Config::get('constants_activity.OBJECT_TYPES.USER.NAME');
        $body        = \Input::get('comment_body');
        $message     = $this->ActivityActionRepository->commentItem($photo_id, 'album_photo', $this->user_id, $poster_type, $body);

        return response()->json($message);
    }

    public function dislikeStatus($action_id = NULL) {
        if ($this->is_api) {
            $action_id = \Input::get('action_id');
        }
        $poster_type = \Config::get('constants_activity.OBJECT_TYPES.USER.NAME');
        $message     = $this->ActivityActionRepository->addActivityDislike($action_id, $this->user_id, $poster_type, TRUE);

        return response()->json($message);
    }

    public function undoDislike($action_id = NULL) {
        if ($this->is_api) {
            $action_id = \Input::get('action_id');
        }
        $poster_type = \Config::get('constants_activity.OBJECT_TYPES.USER.NAME');

        $message = $this->ActivityActionRepository->undoDislike($action_id, $this->user_id, $poster_type, TRUE);

        return response()->json($message);
    }

    public function unlikeActivityComment($comment_id = NULL) {
        if ($this->is_api) {
            $comment_id = \Input::get('comment_id');
        }
        $poster_type = \Config::get('constants_activity.OBJECT_TYPES.USER.NAME');

        $message = $this->ActivityActionRepository->unlikeActivityComment($comment_id, $this->user_id, $poster_type);
        if($this->is_api){
            return $message;
        }
        return response()->json($message);
    }

    public function extractLinkMeta() {
        $link = \Input::get('link');
        $meta = $this->ActivityActionRepository->extractLinkMeta($link);

        return response()->json($meta);
    }

    public function getPhoto($photo_id, $name) {
        $type = request()->get('type');
        $sm   = new StorageManager();
        
        $img = request()->get('img');
        $img = !empty($img) ? $img : 'album_photo';

        $file = $sm->getFile($photo_id, $name, $img);
        
        return response()->make($file)->header('Content-Type', urldecode($type));
    }

    public function getVideoThumb($path) {
        $type = request()->get('type');
        $path = base64_decode(urldecode($path));
        $sm   = new StorageManager();
        $file = $sm->getFileByPath($path);

        return response()->make($file)->header('Content-Type', urldecode($type));
    }

    public function getVideo($user_id, $name) {
        $type = request()->get('type');

        $sm   = new StorageManager();
        $file = $sm->getFile($user_id, $name, 'video');

        return response()->make($file)->header('Content-Type', urldecode($type));
    }

    public function getAudio($user_id, $name) {
        $type = request()->get('type');
        $sm   = new StorageManager();
        $file = $sm->getFile($user_id, $name, 'audio');

        return response()->make($file)->header('Content-Type', urldecode($type));
    }

    public function getAttachment($user_id, $name) {
        $type = request()->get('type');
        $sm   = new StorageManager();
        $file = $sm->getFile($user_id, $name, 'attachment');

        return response()->make($file)->header('Content-Type', urldecode($type));
    }
    public function getAttachmentThumb($user_id, $name) {
        $type = request()->get('type');
        $sm   = new StorageManager();
        $file = $sm->getFile($user_id, $name, 'attachment_thumb');

        return response()->make($file)->header('Content-Type', urldecode($type));
    }

    public function flagActivity(Request $request) {
        $action_id = $request->get('post_id');
        $category  = $request->get('category');
        $text      = $request->get('text');

        $message = $this->ActivityActionRepository->flagActivity($this->user_id, $action_id, $category, $text);
        if($this->is_api){
            return $message;
        }
        return response()->json($message);
    }

    public function load_more_activity(Request $request) {

        $activityActionRepository = new ActivityActionRepository();

        $activities = $activityActionRepository->all_activity($this->user_id);
        $is_end     = 0;
        if (!$activities->hasMorePages()) {
            $is_end = 1;
        }

        if (count($activities) > 0) {
            $contentHtml = '';
            foreach ($activities as $row) {
                $contentHtml .= '<div class="activity-container"><div>
                              <a href="' . Kinnect2::profileAddress(Auth::user()) . '">
                                ' . Auth::user()->displayname . '
                              </a>
                            ' . activity_log_string($row) . '
                            </div>
                            <span>' . Carbon::parse($row->created_at)->format("F d Y") . '</span>
							</div>';
            }
        } else {
            return '<li class="notifications_unread" id="no-more" value="1">No More Activities</li>';
        }

        return $contentHtml;
    }

    public function viewDetails($type, $id) {
        if ($type == 'activity_action') {
            $action_id = $id;
        } else {
            $action_id = $this->ActivityActionRepository->getIDByObject($type, $id);
        }
        
        return redirect('postDetail/'.$action_id);
    }

    public function getPopupPhotos() {
        $object_id = \Input::get('object_id');
        $data      = $this->ActivityActionRepository->getPopupPhotos($object_id);

        return response()->json($data);
    }

    public function postDetail($post_id) {
        $data = $this->getPost($post_id);
        $data = array('post' => base64_encode(json_encode($data)));
        
        return view('detail', $data);
    }

    public function skoring() {
        return view('skoring')->with('page_Title', 'Skore');
    }

    public function poll_post() {
        if ($this->is_api && !\Input::has('poll_id')) {
            return \Api::invalid_param();

        }
        $id = \Input::get('poll_id');

        $action = ActivityAction::where('type', 'like', 'poll_create')
            ->where('object_type', 'like', 'poll')
            ->where('object_id', $id)
            ->select(['action_id'])
            ->first();
        if (!$action) {
            return \Api::detail_not_found();
        }
        \Input::merge(['post_id' => $action->action_id]);

        return $this->getPost($action);
    }

    public function battle_post() {
        if ($this->is_api && !\Input::has('battle_id')) {
            return \Api::invalid_param();

        }
        $id = \Input::get('battle_id');

        $action = ActivityAction::where('type', 'like', 'battle_create')
            ->where('object_type', 'like', 'battle')
            ->where('object_id', $id)
            ->select(['action_id'])
            ->first();
        if (!$action) {
            return \Api::detail_not_found();
        }
        \Input::merge(['post_id' => $action->action_id]);

        return $this->getPost($action);
    }

    public function publicPostDetail($post_id) {
        $anonymousUser = false;
        if(!Auth::user()){
            $anonymousUser = true;
        }
        $data = $this->getPost($post_id);

        $data["anonymousUser"] = $anonymousUser;
        $data = array('post' => base64_encode(json_encode($data)));
        if(!$anonymousUser){
            return view('detail', $data);
        }
        return view('detail-public', $data);
    }
    public function getPostLikes($post_id){
        $likes = $this->ActivityActionRepository->getPostLikes($post_id);

        return response()->json(['likes' => $likes,'base_url' => url()]);
    }
    public function getPostDislikes($post_id){
        $likes = $this->ActivityActionRepository->getPostDislikes($post_id);

        return response()->json(['likes' => $likes,'base_url' => url()]);
    }
    public function getEditPost($post_id){
        $response = $this->ActivityActionRepository->getEditPost($post_id,$this->user_id);
        return response()->json($response);
    }

    public function deleteToken(Request $request)
    {
        $token = $request->get('token');
        
        $response = $this->ActivityActionRepository->deleteToken($token,$this->user_id);
        return response()->json($response);
    }
}