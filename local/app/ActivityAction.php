<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityAction extends Model
{

    protected $table = "activity_actions";
    protected $primaryKey = 'action_id';

	public function activity_comment(  ) {
		return $this->hasMany('App\ActivityComment', 'resource_id');
	}
	public function activity_likes(  ) {
		return $this->hasOne('App\ActivityLike', 'resource_id');
	}

	public function activity_favourite(  ) {
		return $this->hasOne('App\ActivityFavourite', 'resource_id');
	}

	public function activity_dislike(  ) {
		return $this->hasOne('App\ActivityDislike', 'resource_id');
	}
	public function user(  ) {
		return $this->belongsTo('App\User', 'object_id');
	}
}
