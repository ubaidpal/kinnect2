<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{

    protected $table = 'user_membership';
	protected $primaryKey = 'user_id';

	public function resource(  ) {
		return $this->belongsTo('App\User', 'id');
	}

	public function user(  ) {
		return $this->belongsTo('App\User','user_id', 'id' );
	}
}
