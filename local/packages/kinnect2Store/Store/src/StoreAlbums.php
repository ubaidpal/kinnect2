<?php
namespace kinnect2Store\Store;

use Illuminate\Database\Eloquent\Model;

class StoreAlbums extends Model
{
    protected $table = 'store_albums';
    protected $primaryKey = 'album_id';

    protected $fillable =  [''];
    public function albumPhoto() {
        return $this->hasMany('kinnect2Store\Store\StoreAlbumPhotos', 'album_id');
    }
}
