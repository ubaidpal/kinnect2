<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : local
 * Product Name : PhpStorm
 * Date         : 11-11-15 4:06 PM
 * File Name    : UsersRepository.php
 */

namespace App\Repository\Eloquent;


use App\Album;
use App\AlbumPhoto;
use App\BrandMembership;
use App\Invitation;
use App\Repository\RepositoryInterface;
use App\StorageFile;
use App\User;
use App\Repository\Eloquent\Repository;
use App\Http\Requests\Request;
use App\Consumer;
use App\Usersetting;
use Config;
use DB;
use Input;
use Cache;
use App\Classes\UrlFilter;
use Auth;
use Intervention\Image\Facades\Image;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class UsersRepository extends Repository implements RepositoryInterface
{

    protected $users;
    private   $activity_type;

    /**
     * @param User $users
     */
    public function __construct(User $users) {
        parent::__construct();

        $this->users         = $users;
        $this->activity_type = \Config::get('constants.ACTIVITY_TYPE_PROFILE');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all() {
        return $this->users->all();
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Support\Collection|static
     */
    public function find($id) {
        $user = Cache::get('_user_'.$id,function () use ($id){
            $user = $this->users->findOrNew($id);
            Cache::forever('_user_'.$id,$user);
            return $user;
        });
        return $user;
    }

    /**
     * @param null $id
     */
    public function saveOrUpdate($id = NULL) {

    }

    public function update($user_id) {
        $user              = User::findOrNew($user_id);
        $user->first_name  = Input::get('first_name');
        $user->last_name   = Input::get('last_name');
        $user->displayname = Input::get('first_name') . ' ' . Input::get('last_name');
        $user->name        = Input::get('first_name') . ' ' . Input::get('last_name');
        $user->country     = Input::get('country');
        $user->twitter     = Input::get('twitter');
        $user->website     = Input::get('website');
        $user->facebook    = Input::get('facebook');
        $user->save();


        $detail = Consumer::findOrNew($user->userable_id);

        $detail->gender         = Input::get('gender');
        $detail->birthdate      = Input::get('year') . '-' . Input::get('month') . '-' . Input::get('day');
        $detail->personnel_info = Input::get('personnel_info');
        $detail->save();

    }

    public function friends($user_id) {
        return \DB::table('user_membership')
            ->join('users', 'users.id', '=', 'user_membership.user_id')
            ->where('resource_id', $user_id)
            ->where('user_id','<>',$user_id)
            // ->where('users.user_type', '=', \Config::get('constants.REGULAR_USER'))
            ->where('user_membership.active', 1)
            ->select('user_membership.*', 'users.name', 'users.email', 'users.username', 'users.displayname', 'users.user_type', 'users.photo_id')
            ->orderBy('users.displayname', 'ASC')
            ->paginate(\Config::get('constants.PER_PAGE'));

    }

    public function friends_count($user_id) {
        return \DB::table('user_membership')
            ->join('users', 'users.id', '=', 'user_membership.user_id')
            ->where('resource_id', $user_id)
            // ->where('users.user_type', '=', \Config::get('constants.REGULAR_USER'))
            ->where('user_membership.active', 1)
            ->select('user_membership.*', 'users.name', 'users.username', 'users.displayname', 'users.user_type', 'users.photo_id')
            ->count();

    }

    public function friends_for_invitation($user_id, $friend) {
        return \DB::table('user_membership')
            ->join('users', 'users.id', '=', 'user_membership.user_id')
            ->where('resource_id', $user_id)
            // ->where('users.user_type', '=', \Config::get('constants.REGULAR_USER'))
            ->where('user_membership.active', 1)
            ->whereNotIn('user_membership.user_id', $friend)
            ->select('user_membership.*', 'users.name', 'users.username', 'users.displayname', 'users.user_type', 'users.photo_id')
            ->lists('users.displayname', 'user_membership.user_id');

    }


    // MAKING PROFILE PICTURES
    public function uploadingPhotos($tmp_file_path, $user_id) {
        $user        = User::find($user_id);
        $folder_path = public_path('users/user_' . $user_id);
        if (!file_exists($folder_path)) {
            if (!mkdir($folder_path, 0777, TRUE)) {
                $folder_path = '';
            }
        }

        $file_name = time() . rand(111111111, 9999999999);
        $image1    = Image::make($tmp_file_path);
        $image1->resize(Config::get('constants.PROFILE_NORMAL_WIDTH'), Config::get('constants.PROFILE_NORMAL_HEIGHT'));

        if ($image1->save($folder_path . '/' . $file_name . '.JPEG')) {
            // save image record to db, if it is saved well enough.
            $path_photo   = $file_name . '.JPEG';
            $album_id     = $this->insertDefaultAlbum($user_id, $path_photo);
            $photo_id     = $this->insertPhotoIntoAlbum($user_id, $album_id, $path_photo);
            $savedPhotoId = $user->photo_id = $photo_id;
            $user->save();
        }

        $file_name = time() . rand(111111111, 9999999999);
        $image2    = Image::make($tmp_file_path);
        $image2->resize(Config::get('constants.PROFILE_THUMB_WIDTH'), Config::get('constants.PROFILE_THUMB_HEIGHT'));

        if ($image2->save($folder_path . '/' . $file_name . '.JPEG')) {
            // save image record to db, if it is saved well enough.
            $path_photo = $file_name . '.JPEG';
            $this->insertPhotoIntoAlbum($user_id, $album_id, $path_photo);
        }

        $file_name = time() . rand(111111111, 9999999999);
        $image3    = Image::make($tmp_file_path);
        $image3->resize(Config::get('constants.PROFILE_ICON_WIDTH'), Config::get('constants.PROFILE_ICON_HEIGHT'));

        if ($image3->save($folder_path . '/' . $file_name . '.JPEG')) {
            // save image record to db, if it is saved well enough.
            $path_photo = $file_name . '.JPEG';
            $this->insertPhotoIntoAlbum($user_id, $album_id, $path_photo);
        }

        return $savedPhotoId;
    }

    public function getDefaultAlbum($photo_id) {
        // save image record to db, if it is saved well enough.
        return AlbumPhoto::where('photo_id', $photo_id)->first(['album_id']);
    }

    public function insertDefaultAlbum($user_id) {

        // save image record to db, if it is saved well enough.
        return $album_id = DB::table('albums')->insertGetId(
            [
                'title'       => 'Profile',
                'description' => 'User default profile album',
                'owner_type'  => 'user',
                'owner_id'    => $user_id,
                'category_id' => 0,
                'type'        => 'profile',
                'photo_id'    => 0,
            ]
        );
    }

    public function insertNewDefaultAlbum($title = 'New Album', $owner_type, $description = 'Default description', $type = 'wall_photos') {

        // save image record to db, if it is saved well enough.
        return $album_id = DB::table('albums')->insertGetId(
            [
                'title'       => $title,
                'description' => $description,
                'owner_type'  => $owner_type,
                'owner_id'    => $this->user_id,
                'category_id' => 0,
                'type'        => $type,
                'photo_id'    => 0,
            ]
        );
    }

    public function insertPhotoIntoAlbum($user_id, $album_id, $path_photo) {
        return $photo_id = DB::table('album_photos')->insertGetId(
            ['album_id'    => $album_id,
             'title'       => 'User default profile photo',
             'description' => $path_photo,
             'owner_type'  => 'user',
             'owner_id'    => $user_id,
             //'file_id' => $file_id,
             'photo_id'    => 0,
            ]
        );
    }

    public function get_user($user_id) {
        return Cache::get('_user_'.$user_id,function () use ($user_id){
            $user = User::whereId($user_id)->orWhere('username', $user_id)->first();
            Cache::forever('_user_'.$user_id,$user);
            return $user;
        });
    }

    public static function profile($user) {
        $consumer = Cache::get('_consumer_'.$user->userable_id,function () use (&$user){
            $consumer = Consumer::find($user->userable_id);
            Cache::forever('_consumer_'.$user->userable_id,$consumer);
            return $consumer;
        });

        return $consumer;
    }

    public function post_settings($user_id, $data) {
        $category = $data['category'];
        $item     = $data['item'];
        $value    = $data['value'];

        $setting = Cache::get('_user_settings_'.$user_id,function () use ($item,$category,$user_id){
            $userSettings = Usersetting::whereSetting($item)->whereCategory($category)->whereUserId($user_id)->first();
            Cache::forever('_user_settings_'.$user_id,$userSettings);
            return $userSettings;
        });

        if (count($setting) > 0) {

            /*$setting  = DB::table('user_settings')->where('category', $category)
                          ->where('setting', $item)
                          ->where('user_id', $user_id)
                          ->update(array('setting_value' => $value));*/

            $setting->setting_value = $value;
            $setting->save();

            if ($item == 'DNT_DISPLY_ME_IN_SRCH') {
                $userCHangingSearchSetting = Cache::get('_user_'.$user_id,function () use ($user_id){
                    $user = User::find($user_id);
                    Cache::forever('_user_'.$user_id,$user);
                    return $user;
                });

                if ($value == 1) {
                    $userCHangingSearchSetting->search = 0;
                } else {
                    $userCHangingSearchSetting->search = 1;
                }
                $userCHangingSearchSetting->save();
            }

            return 'Setting Saved Successfully!!!';
        } else {
            $settings                = new Usersetting();
            $settings->category      = $category;
            $settings->setting       = $item;
            $settings->setting_value = $value;
            $settings->user_id       = $user_id;
            if ($settings->save()) {
                return 'Setting Saved Successfully!!!';
            } else {
                return 'Not saved try again.';
            }
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
        if (!empty($path)) {
            $user['profile_photo_url'] = \Config::get('constants_activity.PHOTO_URL') . $path . '?type=' . urlencode($file->mime_type);
        }

        $photo                   = AlbumPhoto::where('photo_id', $user->cover_photo_id)
            ->select(['file_id'])
            ->first();
        $file_id                 = isset($photo->file_id) ? $photo->file_id : NULL;
        $file                    = StorageFile::where('file_id', $file_id)->first();
        $path                    = isset($file->storage_path) ? $file->storage_path : NULL;
        $user['cover_photo_url'] = '';
        if (!empty($path)) {
            $user['cover_photo_url'] = \Config::get('constants_activity.PHOTO_URL') . $path . '?type=' . urlencode($file->mime_type);
        }

        return $user;
    }

    public function friends_to_invite($user_id, $object_type, $object_id, $user_type) {
        $already_invited = Invitation::whereUserId($user_id)->whereObjectId($object_id)->whereObjectType($object_type)->lists('receiver_id');
        if ($user_type == Config::get('constants.BRAND_USER')) {
            $follower = \Kinnect2::get_follower($user_id);

            return $friends = $this->friends_for_invitation($user_id, $already_invited);
            /*return $user = User::where('user_type', \Config::get('constants.REGULAR_USER'))
                ->whereNotIn('id', $follower)
                ->whereNotIn('id', $already_invited)
                ->whereNotIn('id', [$this->user_id])
                ->lists('displayname', 'id');*/

        } else {
            return $friends = $this->friends_for_invitation($user_id, $already_invited);
        }
    }

    public function _get_user_details($user, $user_detail = FALSE) {
        $photo_id = $user->photo_id;
        if ($user_detail) {
            $photo_id = $user->user->photo_id;
        }
        $cover_id = $user->cover_photo_id;
        if ($user_detail) {
            $cover_id = $user->user->cover_photo_id;
        }
        $user['profile_photo_url'] = \Kinnect2::getPhotoUrl($photo_id, $user->user->id, 'user', 'thumb_normal');
        $user['cover_photo_url']   = \Kinnect2::getPhotoUrl($cover_id, $user->user->id, 'user', 'cover_photo');

        return $user;
    }

    public function search_kinnectors($userId, $key) {

        $data = \DB::table('user_membership')
            ->join('users', 'users.id', '=', 'user_membership.user_id')
            ->where('resource_id', $userId)
            // ->where('users.user_type', '=', \Config::get('constants.REGULAR_USER'))
            ->where('displayname', 'like', $key . '%')
            ->where('user_membership.active', 1)
            ->select('user_membership.*', 'users.name', 'users.username', 'users.displayname', 'users.user_type', 'users.photo_id')
            ->orderBy('users.displayname', 'ASC')
            ->paginate(Config::get('constants.PER_PAGE'));
        $data->setPath('search');

        return $data;

    }

    public function searchMyBrands($userId, $key) {

        if (is_null($userId)) {
            $userId = $this->user_id;
        }
        $userFollowingBrandIds = DB::table('brand_memberships')
            ->where('user_id', $userId)
            ->where('user_approved', 1)
            ->where('brand_approved', 1)
            ->lists('brand_id');

        $brands = User::where('user_type', \Config::get('constants.BRAND_USER'))
            ->whereIn('id', $userFollowingBrandIds)
            ->with('brand_detail')
            ->where('displayname', 'like', $key . '%')
            ->orderBy('displayname', 'ASC')
            ->paginate(\Config::get('constants.PER_PAGE'));

        $brands->setPath('search');

        return $brands;
    }

    public function searchBrandKinnectors($userId, $key) {
        $kinnectors = BrandMembership::whereBrandId($userId)
            ->whereBrandApproved(1)
            ->whereUserApproved(1)
            ->lists('user_id');

        $data = $brandKinnectors = User::whereIn('id', $kinnectors)
            ->where('displayname', 'like', $key . '%')
            ->orderBy('displayname', 'ASC')
            ->paginate(\Config::get('constants.PER_PAGE'));

        $data->setPath('search');

        return $data;

    }

    public function save_settings_api($user_id) {
        if (!Input::has('privacy') && !Input::has('notification')) {
            return \Api::invalid_param();
        }
        if (Input::has('privacy')) {
            $privacy = Input::get('privacy');
            $this->_save_settings($privacy, $user_id, 'privacy');
        }
        if (Input::has('notification')) {
            $privacy = Input::get('notification');
            $this->_save_settings($privacy, $user_id, 'notification');
        }

        return \Api::success_with_message('Settings saved successfully ');

    }

    private function _save_settings($privacy, $user_id, $type) {

        foreach ($privacy as $key => $item) {

            $data['category'] = $type;
            $data['item']     = $key;
            $data['value']    = $item;

            $this->post_settings($user_id, $data);
        }
    }


    public function getDefaultCoverAlbum($cover_photo_id)
    {
        return AlbumPhoto::whereFileId($cover_photo_id)->first();
    }
    public function addProfilePageStat( $owner_id ) {
        DB::table('profile_page_stats')->insert([
            ['user_id' => $this->user_id, 'owner_id' => $owner_id]
        ]);
    }
}