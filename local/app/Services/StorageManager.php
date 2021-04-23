<?php
/**
 *
 */
namespace App\Services;

use Carbon\Carbon;
use Dflydev\ApacheMimeTypes\PhpRepository;
use Illuminate\Support\Facades\Storage;
use App\StorageFile;
use App\AlbumPhoto;

class StorageManager
{
    protected $disk;
    protected $File;

    function __construct() {
        $this->disk = Storage::disk('local');
    }

    public function saveAlbumPhoto($owner_id, $owner_type, $file, $storage_type, $title = '', $album_id = 0, $parent_file_id = NULL, $child_type = NULL, $parent_photo_id = NULL) {
        if(empty($owner_id) || empty($owner_type) || empty($file)) {
            return FALSE;
        }

        $file_id = NULL;

        if($data = $this->storeFile($owner_id, $file, $storage_type, $parent_file_id, $child_type, $parent_photo_id)) {
            $sfObj = new StorageFile();

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
            $sfObj->width          = isset($data['width']) ? $data['width'] : NULL;
            $sfObj->height         = isset($data['height']) ? $data['height'] : NULL;
            $sfObj->hash           = $data['hash'];

            if(!$sfObj->save()) {
                return FALSE;
            }

            $file_id  = $sfObj->file_id;
            $photo_id = 0;

            if($parent_file_id == NULL) {
                $photoObj = new AlbumPhoto();

                $photoObj->owner_type = $owner_type;
                $photoObj->owner_id   = $owner_id;
                $photoObj->file_id    = $file_id;
                $photoObj->title      = $title;
                $photoObj->album_id   = $album_id;

                if(!$photoObj->save()) {
                    return FALSE;
                }
                $photo_id = $photoObj->photo_id;
            }

            if($photo_id == 0 AND $parent_photo_id > 0) {
                $photo_id = $parent_photo_id;
            }

            $sfObj->parent_id = $photo_id;

            if(!$sfObj->save()) {
                return FALSE;
            }

            return $photo_id;
        }
        return FALSE;

    }

    public function storeFile($user_id, $file, $type, $parent_file_id = NULL, $child_type = NULL) {
        if(empty($user_id) || empty($type) || empty($file)) {
            return FALSE;
        }

        $path = $this->getPath($user_id, $type);

        if(isset($file->basename)) {
            $data['name']      = $file->basename;
            $data['extension'] = $file->extension;
            $data['size']      = rand(123, 999);
            $data['mime_type'] = $file->mime;
            $data['hash']      = $file->encoded;
            if($type == 'photos' || $type == 'poll_photo' || $type == 'album_photo') {
                $data['width']  = $file->width();
                $data['height'] = $file->height();
            }
        } else {
            $data['name']      = $file->getClientOriginalName();
            $data['extension'] = $this->getFileExtension($file);
            $data['size']      = $file->getClientSize();
            $data['mime_type'] = $file->getMimeType();
            $data['hash']      = sha1(file_get_contents($file));
            if($type == 'photos' || $type == 'poll_photo' || $type == 'album_photo') {
                $img = \Image::make($file);
                $data['width']  = $img->width();
                $data['height'] = $img->height();
                $img->destroy();
            }
        }

        $name = $this->getFilename($data['extension']);

        $data['storage_path'] = $user_id . '/' . $name;

        $data['user_id']     = $user_id;
        $data['parent_type'] = $type;

        $data['parent_file_id'] = $parent_file_id;
        $data['type']           = $child_type;

        if(!$this->folderExists($path)) {
            $this->createDirectory($path);
        }

        $this->saveFile($path . $name, $file, $parent_file_id);
        //die("acha2");
        return $data;
    }

    public function saveVideoThumbnail($user_id, &$image, $video_id) {
        $path = $this->getPath($user_id, 'video');
        $path = $path . 'thumbs' . DIRECTORY_SEPARATOR;
        if(!$this->folderExists($path)) {
            $this->createDirectory($path);
        }
        $info                 = pathinfo($image);
        $file_name            = $this->getFilename('jpg');
        $path                 = $path . $file_name;
        $data['parent_type']  = 'video';
        $data['parent_id']    = $video_id;
        $data['storage_path'] = $path;
        $data['extension']    = $info['extension'];
        $data['name']         = 'video thumb';
        $data['mime_type']    = 'image/jpeg';
        $data['user_id']      = $user_id;
        $data['size']         = filesize($image);
        $data['hash']         = hash('sha256', $image);
        $data['type']         = 'video_thumb';

        $this->saveFile($path, $image);

        return $data;
    }

    public function getFileExtension($file) {
        $extension = $file->getClientOriginalExtension();
        if(!$extension) {
            $extension = $file->guessClientExtension();
        }
        if(!$extension) {
            $extension = $file->getExtension();
        }
        return $extension;
    }

    public function copyFromURL($user_id, $link, $type) {
        if(empty($user_id) || empty($type) || empty($link)) {
            return FALSE;
        }

        $path_info = pathinfo($link);
        $path      = $this->getPath($user_id, $type);

        $img = \Image::make($link);
        $jpg = $img->resize(300,null,function($constraint){
            $constraint->aspectRatio();
        })->encode('jpg',90);
        $string = $jpg->__toString();

        $data['name']      = $path_info['basename'];
        $data['extension'] = @$path_info['extension'];

        $name                 = $this->getFilename('jpg');
        $data['storage_path'] = $user_id . '/' . $name;

        $this->saveFile($path . $name, $string,1);

        $data['size'] = $this->getFileSize($path . $name);

        $data['mime_type'] = 'image/jpeg';//$this->getMimeType($path . $name);

        $data['user_id'] = $user_id;

        $data['parent_type'] = 'link';

        $data['hash'] = sha1(file_get_contents($link));

        $img->destroy();

        return $data;
    }

    public function getPath($user_id, $type) {
        if($type == 'audio') {
            $path = 'audios';
        } elseif($type == 'video') {
            $path = 'videos';
        } elseif($type == 'album_photo' || $type == 'link') {
            $path = 'photos';
        } elseif($type == 'attachment') {
            $path = 'attachments';
        } elseif($type == 'deposit_slip') {
            $path = 'deposit_slips';
        } elseif($type == 'attachment_thumb') {
            $path = 'attachments' . DIRECTORY_SEPARATOR . 'thumbs';
        }elseif($type == 'poll_photo'){
            $path = 'photos'.DIRECTORY_SEPARATOR.'polls';
        }elseif ($type == 'conversation'){
            $path = 'conversation';
        }

        return $path . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR;
    }

    public function getFile($user_id, $name, $type) {

        if(empty($user_id) || empty($name) || empty($type)) {
            return FALSE;
        }
        if($type == 'audio') {
            $path = 'audios';
        } elseif($type == 'video') {
            $path = 'videos';
        } elseif($type == 'album_photo' || $type == 'link') {
            $path = 'photos';
        } elseif($type == 'group') {
            $path = 'groups';
        } elseif($type == 'attachment') {
            $path = 'attachments';
        } elseif($type == 'video_thumb') {
            $path = '';
        } elseif($type == 'deposit_slip') {
            $path = 'deposit_slips';
        } elseif($type == 'attachment_thumb') {
            $path = 'attachments' . DIRECTORY_SEPARATOR . 'thumbs';
        }elseif ($type == 'poll_photo'){
            $path = 'photos'.DIRECTORY_SEPARATOR.'polls';
        }elseif ($type == 'poll_thumb'){
            $path = 'photos'.DIRECTORY_SEPARATOR.'polls'.DIRECTORY_SEPARATOR.'thumbs';
        }elseif ($type == 'conversation'){
            $path = 'conversation';
        }

        $path = $path . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR . $name;

        return $this->disk->get($path);
    }

    public function getFileByPath($path) {
        return $this->disk->get($path);
    }

    public function getFilename($extension) {
        return str_replace('.', '_', time() . uniqid(30, TRUE)) . '.' . $extension;
    }

    protected function folderExists($path) {
        return $this->disk->exists($path);
    }

    public function pathExists($path) {
        return $this->disk->exists($path);
    }

    protected function cleanFolder($folder) {
        return '/' . trim(str_replace('..', '', $folder), '/');
    }

    public function createDirectory($folder, $mode = 0777, $recursive = TRUE) {
        return $this->disk->makeDirectory($folder, $mode, $recursive);
    }

    public function getMimeType($path) {
        return $this->disk->mimeType($path);
    }

    public function getFileSize($path) {
        return $this->disk->size($path);
    }

    public function saveFile($path, $content, $parent_file_id = 0) {

        $path = $this->cleanFolder($path);

        if($this->disk->exists($path)) {
            return "File already exists.";
        }

        if($parent_file_id > 0) {
            return $this->disk->put($path, $content);
        } else {
            return $this->disk->put($path, file_get_contents($content));
        }

    }

    public function deletFile($path) {
        return $this->disk->delete($path);
    }
}
