<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityComment extends Model
{
	public static function boot(){
		parent::boot();

		static::created( function($data){
			\Kinnect2::update_skore(\Config::get('constants_sKore.COMMENT'), $data->poster_id);
		});
		static::updated( function($data){
			//\Kinnect2::update_skore('brand_follow', $data->user_id);
		});
	}
    protected $table = "activity_comments";

    protected $primaryKey = 'comment_id';

	public function comment(  ) {
		return $this->belongsTo('App\ActivityAction');
	}
}
