<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : kinnect2
 * Product Name : PhpStorm
 * Date         : 24-Feb-2016 4:34 PM
 * File Name    : StoreFile.php
 */

namespace kinnect2Store\Store\traits;


use App\Album;
use App\AlbumPhoto;
use kinnect2Store\Store\StoreAlbumPhotos;
use kinnect2Store\Store\StoreAlbums;
use kinnect2Store\Store\StoreStorageFiles;

trait StoreFile
{
    public function store_file($album_id, $fileId, $user_id, $options) {
        $file = StoreStorageFiles::where('file_id', $fileId)->first();

        $file_name = time() . rand(111111111, 9999999999);

        $folder_path   = "local/storage/app/photos/" . $user_id;

        if(isset($file->extension)){
            $extension = $file->extension;
        }else{
            $extension = '.jpeg';
        }

        $file_name_new = $user_id . "_" . $file_name . "." . $extension;

        if (isset($file->file_id)) {

            if (file_exists("local/storage/app/photos/" . $file->storage_path) == TRUE) {

                if (!file_exists($folder_path)) {
                    if (!mkdir($folder_path, 0777, TRUE)) {
                        $folder_path = '';
                    }
                }

                rename("local/storage/app/photos/" . $file->storage_path, $folder_path . "/" . $file_name_new);
            }

            // Saving photos
            $photoObj = new StoreAlbumPhotos();

            $photoObj->owner_type = 'order_dispute';
            $photoObj->owner_id   = $options['owner_id'];
            $photoObj->file_id    = $file->file_id;
            $photoObj->title      = $options['title'];;
            $photoObj->album_id = $album_id;

            if ($photoObj->save()) {
                $file->parent_id    = $photoObj->photo_id;//photo_id
                $file->user_id      = $options['user_id'];
                $file->storage_path = $options['user_id'] . "/" . $file_name_new;
                $file->name         = $file_name;
                $file->mime_major   = 'image';

                $file->save();

                return $imageFilePath = $options['user_id'] . "/" . $file_name_new;

                /*$this->resizeProductImage($imageFilePath, $file->file_id, $file->user_id, 'product', 'product_profile', '151', '210');
                $this->resizeProductImage($imageFilePath, $file->file_id, $file->user_id, 'product', 'product_thumb', '170', '170');
                $this->resizeProductImage($imageFilePath, $file->file_id, $file->user_id, 'product', 'product_icon', '54', '80');*/
            }

        }
    }


    public function create_album($order_dispute) {

        $album              = new StoreAlbums();
        $album->title       = 'order dispute';
        $album->description =  "Order Dispute's album'";
        $album->owner_type  = 'order_dispute';
        $album->owner_id    = $order_dispute;
        $album->category_id = 0;
        $album->type        = 'order dispute attachment';
        $album->photo_id    = 0;

        $album->save();

        return $album->album_id;
    }
}
