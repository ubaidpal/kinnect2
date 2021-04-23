<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = "feedback";

	/**
	 *
	 */
	public static function boot(){
        parent::boot();

        static::saving(function($model){
            //echo '<tt><pre>'; print_r($model); die;
        });
    }
}
