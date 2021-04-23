<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityFavourite extends Model
{

    protected $table = "activity_favorites";

	public function activity_favourite(  ) {
		return $this->belongsTo('App\ActivityAction');
	}
}
