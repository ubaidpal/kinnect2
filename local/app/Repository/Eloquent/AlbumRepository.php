<?php

namespace App\Repository\Eloquent;

use App\AuthorizationAllow;
use App\Facades\AuthorizationAllowClassFacade;
use App\StorageFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\AlbumCategory;
use App\AlbumPhoto;
use App\Album;
use App\User;
use Request;

class AlbumRepository extends Repository
{

    protected $album;
    /**
     * @var AlbumPhoto
     */
    private $albumPhoto;

    public function __construct(Album $album, AlbumPhoto $albumPhoto) {
        parent::__construct();

        $this->album      = $album;
        $this->albumPhoto = $albumPhoto;
    }

    public function getMyAlbums($user_id) {
        $album = Album::where('owner_id', $user_id)->where('owner_type', 'user')->with(['AlbumPhotos', 'cover_photo'])->get();
        $data  = $album;

        return $data;
    }

    public function getAlbumById($id) {

    }

    public function createAlbum() {
        $categories = AlbumCategory::orderBy('category_name', 'ASC')->lists('category_name', 'category_id');

        return $categories;
    }

    public function storeAlbum($newAlbum, $user_id) {
        $newAlbum->owner_id    = $user_id;
        $newAlbum->category_id = (Input::get('category_id'));
        $newAlbum->owner_type  = (Input::get('owner_type'));
        $newAlbum->save();
        $comment = (Input::get('auth_allow_comment'));
        $view    = (Input::get('auth_allow_view'));
        $tag     = (Input::get('auth_allow_tagging'));
        AuthorizationAllowClassFacade::Setting('album', $newAlbum->album_id, $view, 'view');
        AuthorizationAllowClassFacade::Setting('album', $newAlbum->album_id, $comment, 'comment');
        AuthorizationAllowClassFacade::Setting('album', $newAlbum->album_id, $tag, 'tag');

        return $newAlbum->album_id;

    }

    public function showAlbum($id, $user_id) {
        $album          = DB::table('albums')->where('album_id', $id)->first();
        $photos         = AlbumPhoto::where('album_id', $id)->get();
        $data['album']  = $album;
        $data['photos'] = $photos;

        return $data;
    }

    public function editAlbum($id) {
        $album = Album::where('album_id', $id)->first();

        return $album;
    }

    public function updateAlbum($data, $id) {
        $album = Album::findOrFail($id);

        $album->title       = $data['title'];
        $album->description = $data['description'];
        $album->category_id = $data['category_id'];
        $album->save();

        AuthorizationAllowClassFacade::changeSetting('album', $album->album_id, $data['auth_allow_view'], 'view');
        AuthorizationAllowClassFacade::changeSetting('album', $album->album_id, $data['auth_allow_comment'], 'comment');
        AuthorizationAllowClassFacade::changeSetting('album', $album->album_id, $data['auth_allow_tagging'], 'tag');

    }

    public function destroyAlbum($id, $user_id) {
        $album = Album::find($id);
        if(empty($album)) {
            return 1;
        }
        if($album->owner_id == $user_id) {
            $albumPhotos = AlbumPhoto::whereAlbumId($id)->get();

            foreach ($albumPhotos as $photo) {
                $this->delete_photo($photo->photo_id);
            }
            AuthorizationAllowClassFacade::deleteResource($id, 'album');
            $album->delete();
        } else {
            return 2;
        }
    }

    public function addPhotosToAlbum($id, $user_id) {
        $album = DB::table('albums')->where('album_id', $id)->first();

        return $album;
    }

    public function save_description($data) {
        //dd($data);
        $photo              = AlbumPhoto::findOrNew($data['photo_id']);
        $photo->title       = $data['title'];
        $photo->description = $data['description'];
        $photo->save();

        if(isset($data['is_cover'])) {
            $album_photo     = AlbumPhoto::findOrNew($data['photo_id']);
            $file_id         = $album_photo->file_id;
            $album           = Album::findOrNew($album_photo->album_id);
            $album->photo_id = $file_id;
            $album->save();
        }

    }

    public function create_album($title = 'New Album', $owner_type, $description = 'Default description', $type = 'wall_photos', $owner_id) {
        $album              = new Album();
        $album->title       = $title;
        $album->description = $description;
        $album->owner_type  = $owner_type;
        $album->owner_id    = $owner_id;
        $album->category_id = 0;
        $album->type        = $type;
        $album->photo_id    = 0;
        $album->save();

        return $album->album_id;

    }

    public function _get_albums_detail($albums) {
        $dataAll = [];
        foreach ($albums as $album) {
            $data      = $this->_get_single_album_detail($album);
            $dataAll[] = $data;
            unset($album->AlbumPhotos);
            unset($album->cover_photo);
        }

        return $dataAll;
    }

    /**
     * @param $AlAlbumPhotos
     */
    private function _get_album_photos($AlbumPhotos) {
        $AllPhotos = [];
        foreach ($AlbumPhotos as $photo) {
            $photoD['id']          = $photo->photo_id;
            $photoD['title']       = $photo->title;
            $photoD['description'] = $photo->description;
            $photoD['photo_url']   = \Kinnect2::getAlbumPhotoUrl($photo->file_id, 'album', FALSE);
            $AllPhotos[]           = $photoD;
        }

        return $AllPhotos;
    }

    public function _get_single_album_detail($album) {
        $data                  = $album;
        $data['category_name'] = '';
        $category              = AlbumCategory::whereCategoryId($album->category_id)->first();
        if($category) {
            $data['category_name'] = $category->category_name;
        }
        $data['photo_count']     = count($album->AlbumPhotos);
        $data['photos']          = $this->_get_album_photos($album->AlbumPhotos);
        $data['cover_photo_url'] = (isset($album->cover_photo->storage_path) ? \Config::get('constants_activity.PHOTO_URL') . $album->cover_photo->storage_path . '?type=' . urlencode($album->cover_photo->mime_type) : '');
        $data['permission']      = $this->_get_privacy($album->album_id);
        return $data;
    }

    public function delete_photo($id) {
        $photo = AlbumPhoto::findOrNew($id);

        if($photo->owner_id == $this->user_id) {
            $storagePath = \Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
            $files       = StorageFile::whereFileId($photo->file_id)->orWhere('parent_file_id', $photo->file_id)->get();

            foreach ($files as $file) {
                $img = $storagePath . 'photos/' . $file->storage_path;
                if(\File::exists($img)) {
                    unlink($img);
                }
                StorageFile::findOrNew($file->file_id)->delete();
            };
            $photo->delete();
        }
    }

    public function _get_privacy($id) {
        $privacy = AuthorizationAllow::whereResourceId($id)->whereResourceType('album')->lists('permission', 'action');

        $permissions['view']    = '';
        $permissions['comment'] = '';
        $permissions['tag']     = '';

        if(!empty($privacy)) {
            if(isset($privacy['view'])) {
                $permissions['view'] = \Config::get('constants.PERMISSION.' . \AuthorizationAllowClassFacade::getSettingPermissionValue('album', $id, 'view'));
            }
            if(isset($privacy['comment'])) {
                $permissions['comment'] = \Config::get('constants.PERMISSION.' . \AuthorizationAllowClassFacade::getSettingPermissionValue('album', $id, 'comment'));
            }
            if(isset($privacy['tag'])) {
                $permissions['tag'] = \Config::get('constants.PERMISSION.' . \AuthorizationAllowClassFacade::getSettingPermissionValue('album', $id, 'tag'));
            }

        }

        return $permissions;

    }
}
