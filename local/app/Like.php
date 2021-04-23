<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{

	public static function boot(){
		parent::boot();

		static::created( function($data){

			\Kinnect2::update_skore(\Config::get('constants_sKore.LIKE'), $data->poster_id);
		});
		static::deleted( function($data){

			\Kinnect2::update_skore(\Config::get('constants_sKore.UNLIKE'), $data->poster_id);
		});

		static::updated( function($data){
			//\Kinnect2::update_skore('brand_follow', $data->user_id);
		});
	}
    protected $table = "likes";

    protected $primaryKey = 'like_id';
}
