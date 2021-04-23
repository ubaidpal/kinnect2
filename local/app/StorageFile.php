<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StorageFile extends Model
{
    protected $table = "storage_files";
    protected $primaryKey = 'file_id';

	public function album_photo(  ) {
		//return $this->hasOne('App\AlbumPhoto');
		return $this->hasManyThrough('App\AlbumPhoto','App\Album');
	}

	public function album_cover_photo(  ) {
		return $this->hasOne('App\Album');
	}


}
