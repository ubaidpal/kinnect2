<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class AlbumCategory extends Model
{
    protected $table = 'album_categories';

    protected $fillable = ['category_name'];

    public function Albums()
    {
        return $this->hasMany('App\Album');
    }

}
