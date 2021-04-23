<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $primaryKey = 'album_id';
    protected $table = 'albums';
    //public $timestamps = false;
    protected $fillable = ['title','description','photo_id','owner_type','search','view_count','comment_count','he_featured','category_id'];

    const UPDATED_AT = 'updated_date';
    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function AlbumCategory()
    {
        return $this->belongsTo('App\AlbumCategory');
    }

    public function AlbumPhotos()
    {
        return $this->hasMany('App\AlbumPhoto');
    }

	public function cover_photo()
	{
		return $this->belongsTo('App\StorageFile','photo_id');
	}

}
