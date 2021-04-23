<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLike extends Model
{

	public static function boot(){
		parent::boot();

		static::created( function($data){

			\Kinnect2::update_skore(\Config::get('constants_sKore.ACTIVITY_LIKE'), $data->poster_id);
		});
		static::deleted( function($data){

			\Kinnect2::update_skore(\Config::get('constants_sKore.ACTIVITY_REMOVE_LIKE'), $data->poster_id);
		});

		static::updated( function($data){
			//\Kinnect2::update_skore('brand_follow', $data->user_id);
		});
	}
    protected $table = "activity_likes";

    protected $primaryKey = 'like_id';

	public function activity_action(  ) {
		return $this->belongsTo('App\ActivityAction');
	}
	public function liker(){
		return $this->belongsTo('App\User','poster_id')->select(['id','first_name','last_name','user_type','displayname','username','photo_id']);
	}
}
