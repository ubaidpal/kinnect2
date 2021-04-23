<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityNotification extends Model
{
    protected $fillable = [
	    'resource_type',
	    'resource_id',
	    'subject_id',
	    'subject_type',
	    'object_id',
	    'object_type',
	    'type',
	    'read',
	    'clicked'
    ];

	public function user(  ) {
		return $this->belongsTo('App\User', 'subject_id');
	}
}
