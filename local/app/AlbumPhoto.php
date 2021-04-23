<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlbumPhoto extends Model
{
    protected $table = "album_photos";
    protected $primaryKey = 'photo_id';

    protected $fillable = ['title','description','order','owner_type','view_count','comment_count','he_featured'];

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function Albums()
    {
        return $this->belongsTo('App\Album');
    }

	public function storage_file()
	{
		return $this->belongsTo('App\StorageFile', 'file_id');

	}
}
