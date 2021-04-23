<?php

namespace App\Http\Controllers;

use App\ActivityAction;
use App\Album;
use App\AlbumPhoto;
use App\AlbumCategory;

use App\AuthorizationAllow;
use App\Events\ActivityLog;
use App\Facades\Kinnect2;
use App\Repository\Eloquent\ActivityActionRepository;
use App\Repository\Eloquent\AlbumRepository;
use App\Services\SiteMap;
use App\Services\StorageManager;
use App\StorageFile;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Classes\UrlFilter;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use App\Repository\Eloquent\UsersRepository;

class AlbumsController extends Controller
{

    protected $data;
    protected $is_api;
    private   $user_id;
    private   $album;
    private   $usersRepository;
    private   $activity_type;

    /**
     * @param AlbumRepository $album
     * @param Request $middleware
     * @param UsersRepository $usersRepository
     */
    public function __construct(AlbumRepository $album, Request $middleware, UsersRepository $usersRepository) {
        $this->album           = $album;
        $this->usersRepository = $usersRepository;
        $this->user_id         = $middleware['middleware']['user_id'];
        @$this->data->user = $middleware['middleware']['user'];
        $this->is_api = $middleware['middleware']['is_api'];

        \View::share('friends', $usersRepository->friends($this->user_id));
        $this->activity_type = \Config::get('constants_activity.OBJECT_TYPES.ALBUM.NAME');

    }

    public function index($user_id = NULL) {

        if(\Input::has('user_id')) {
            $user_id = \Input::get('user_id');
        }
        if(empty($user_id)) {
            if($this->is_api) {
                return \Api::invalid_param();
            }

            return redirect()->back();
        }
        $user = $this->usersRepository->get_user($user_id);
        if($this->is_api) {
            if(empty($user)) {
                return \Api::result_not_found();
            }
        }
        $data['albums'] = $this->album->getMyAlbums($user->id);

        if($this->is_api) {
            $response['results'] = $this->_get_albums_detail($data['albums']);
            $response['count']   = count($data['albums']);

            return \Api::success($response);
        }
        $data['user']    = $user;
        $data['friends'] = $this->usersRepository->friends($user->id);

        /*foreach($data['albums'] as $album){
            echo $album->owner_id.'-';
        }*/

        return view('profile.albums.MyAlbums', $data);
    }

    public function getAlbums($id) {
        $data = $this->album->getAlbumById($id);

        return view('profile.albums.Albums');
    }

    public function create() {
        $categories = $this->album->createAlbum();
        if($this->is_api) {
            return \Api::success(['categories' => $categories]);
        }

        return view('profile.albums.CreateAlbum')->with('categories', $categories);
    }

    public function store(Request $request) {

        $newAlbum = new Album($request->all());

        $album_id = $this->album->storeAlbum($newAlbum, $this->user_id);
        if($this->is_api) {
            //return \Api::success(['data'=> $request->except('middleware','_token','action')]);
            return \Api::success_with_message('Album created successfully');
        }
        if(Input::get('action') === 'Create Album') {
            return redirect::to(\Kinnect2::profileAddress($this->data->user) . '#albums');
        }
        if(Input::get('action') === 'Create Album and Add Photos') {
            //return view('profile.albums.AddPhotos');
            return redirect('albums/photos/' . $album_id);
        }
    }

    public function show($id = NULL) {
        $data = $this->album->showAlbum($id, $this->user_id);

        return view('profile.albums.AlbumPhotos', $data);
    }

    /**
     * @param null $album_id
     *
     * @return mixed
     */
    public function edit($album_id = NULL) {
        if(\Input::has('album_id')) {
            $album_id = \Input::get('album_id');
        }
        if(empty($album_id)) {
            if($this->is_api) {
                return \Api::invalid_param();
            }

            return redirect()->back();
        }
        $album = $this->album->editAlbum($album_id);

        //if (\Gate::denies('update', $album)) {
        if($this->user_id != $album->owner_id && $album->owner_type != 'user') {

            if($this->is_api) {
                return \Api::access_denied();
            }

            return redirect()->back();
        }
        $category = $this->album->createAlbum();
        if($this->is_api) {

            $permissions['view']    = \Config::get('constants.PERMISSION.' . \AuthorizationAllowClassFacade::getSettingPermissionValue('album', $album->album_id, 'view'));
            $permissions['comment'] = \Config::get('constants.PERMISSION.' . \AuthorizationAllowClassFacade::getSettingPermissionValue('album', $album->album_id, 'comment'));
            $permissions['tag']     = \Config::get('constants.PERMISSION.' . \AuthorizationAllowClassFacade::getSettingPermissionValue('album', $album->album_id, 'tag'));

            return \Api::success(['data' => $album, 'categories' => $category, 'permissions' => $permissions]);
        }
        $user = $this->usersRepository->get_user($album->owner_id);

        $data['user']    = $user;
        $data['friends'] = $this->usersRepository->friends($user->id);

        return view('profile.albums.EditAlbum', $data)->with('album', $album)->with('categories', $category);

    }

    public function update(Request $request, $album_id = NULL) {
        if(\Input::has('album_id')) {
            $album_id = \Input::get('album_id');
        }
        if(empty($album_id)) {
            if($this->is_api) {
                return \Api::invalid_param();
            }

            return redirect()->back();
        }
        $this->album->updateAlbum($request->all(), $album_id);
        if($this->is_api) {
            return \Api::success_with_message('Album updated successfully');
        }

        //return redirect::to(\Kinnect2::profileAddress($this->data->user) . '#albums');
        return redirect('albums/photos/'.$album_id);
    }

    public function destroy($id = NULL) {
        if($this->is_api) {
            if(!\Input::has('album_id')) {
                return \Api::invalid_param();
            }
            $id = \Input::get('album_id');
        }

        $album_delete = $this->album->destroyAlbum($id, $this->user_id);
        if($album_delete == 1) {
            if($this->is_api) {
                return \Api::other_error('Album not found or already deleted');
            }
        }
        if($album_delete == 2) {
            if($this->is_api) {
                return \Api::access_denied();
            }
        }

        if($this->is_api) {
            return \Api::success_with_message();
        }
        if(\Request::ajax()){
           return response()->json(['status' => $album_delete]);
        }
        return redirect('profile/');
    }

    public function addPhotos($album_id = NULL) {
        $data = $this->album->addPhotosToAlbum($album_id, $this->user_id);

        return view('profile.albums.AddPhotos')->with('album', $data);
    }

    public function store_photo(Request $request, StorageManager $storageManager) {
        if($this->is_api) {
            if(!\Input::has('album_id') || !\Input::hasFile('file')) {
                return \Api::invalid_param();
            }
        }
        $photo_id = $storageManager->saveAlbumPhoto($this->user_id, 'user', $request->file('file'), 'album_photo', '', $request->album_id);
        if($photo_id > 0) {
            $photo = AlbumPhoto::wherePhotoId($photo_id)->select(['file_id'])->first();
            $file  = StorageFile::whereFileId(@$photo->file_id)->first();
            if(@$file->file_id) {
                $ActivityActionRepository = new ActivityActionRepository();
                $ActivityActionRepository->processPhoto($file, $this->user_id, $photo_id, 1, 1);
            }
            //making thumbs
            $folder_path = public_path('storage/temporary/album');
            if(!file_exists($folder_path)) {
                if(!mkdir($folder_path, 0777, TRUE)) {
                    $folder_path = '';
                }
            }
            $parent_photo = AlbumPhoto::find($photo_id);
            $file_name    = time() . rand(111111111, 9999999999);

            // <editor-fold desc="Resize EVENT_THUMB_WIDTH">
            $image1 = \Image::make($request->file('file'))->encode('jpg');
            $image1->resize(\Config::get('constants.ALBUM_THUMB_WIDTH'), \Config::get('constants.ALBUM_THUMB_HEIGHT'), function ($c) {
                //$c->aspectRatio();
            });

            if($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                $photo_id = $storageManager->saveAlbumPhoto($request->album_id, 'event', $image1, 'album_photo', 'Album Profile Photo', $parent_photo->album_id, $parent_photo->file_id, 'thumb_normal', $parent_photo->photo_id);
            }
            // </editor-fold>

            // <editor-fold desc="Resize EVENT_PROFILE_WIDTH">
            $file_name = time() . rand(111111111, 9999999999);
            $image1    = \Image::make($request->file('file'))->encode('jpg');
            $image1->resize(\Config::get('constants.ALBUM_PROFILE_WIDTH'), \Config::get('constants.ALBUM_PROFILE_HEIGHT'), function ($c) {
                //$c->aspectRatio();
            });

            if($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                $photo_id = $storageManager->saveAlbumPhoto($request->album_id, 'event', $image1, 'album_photo', 'Album Profile Photo', $parent_photo->album_id, $parent_photo->file_id, 'album_profile', $parent_photo->photo_id);
            }
        }

        $param   = serialize(['album_id' => $request->album_id]);
        $options = array(
            'object_type'  => \Config::get('constants_activity.OBJECT_TYPES.ALBUM.ACTIONS.ALBUM_PHOTO'),
            'type'         => $this->activity_type,
            'subject'      => $this->user_id,
            'subject_type' => \Config::get('constants_activity.OBJECT_TYPES.USER.NAME'),
            'object'       => $photo_id,
            'params'       => $param,

        );

        \Event::fire(new ActivityLog($options));
        if($this->is_api) {
            return \Api::success_with_message();
        }

        //if($store){
        return redirect()->back();
        //}
    }

    public function add_photo($id = NULL) {
        if(\Input::has('album_id')) {
            $id = \Input::get('album_id');
        }
        if(empty($id)) {
            if($this->is_api) {
                return \Api::invalid_param();
            }

            return redirect()->back();
        }
        //$data['photos'] = AlbumPhoto::whereAlbumId($id)->with('storage_file')->get();
        $data['album'] = Album::whereAlbumId($id)->with('AlbumPhotos.storage_file')->first();
        if(empty($data['album'])) {
            return \Api::detail_not_found();
        }
        if($this->is_api) {
            $response['data'] = $this->album->_get_single_album_detail($data['album']);
            unset($response['data']->AlbumPhotos);
            unset($response['data']->cover_photo);

            return \Api::success($response);
        }
        $user            = $this->usersRepository->get_user($data['album']->owner_id);
        $data['albums']  = $this->album->getMyAlbums($data['album']->owner_id);
        $data['user']    = $user;
        $data['friends'] = $this->usersRepository->friends($user->id);

        /* echo '<tt><pre>';
         print_r($data['album']);die;*/

        return view('profile.albums.AlbumPhotos', $data);
    }

    public function save_description(Request $request) {
        $data = $request->all();
        $this->album->save_description($data);

        return redirect()->back();
    }

    public function delete_photo($id = NULL) {
        if($this->is_api) {
            if(\Input::has('photo_id')) {
                $id = Input::get('photo_id');
            } else {
                return \Api::invalid_param();
            }
        }
        $this->album->delete_photo($id);
        if($this->is_api) {
            return \Api::success_with_message();
        }

        return redirect()->back();
    }

    private function _get_albums_detail($albums) {
        return $this->album->_get_albums_detail($albums);
    }

    public function siteMap(SiteMap $siteMap) {
        $map = $siteMap->getSiteMap();

        return response($map)
            ->header('Content-type', 'text/xml');
    }
}
