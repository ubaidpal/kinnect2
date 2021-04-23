<?php

namespace App;

use App\Facades\Kinnect2;
use Illuminate\Database\Eloquent\Model;

class BrandMembership extends Model
{

	/**
	 *
	 */
	public static function boot(){
		parent::boot();

		static::created( function($data){
			\Kinnect2::update_skore(\Config::get('constants_sKore.BRAND_FOLLOW'), $data->user_id);
		});
		static::updated( function($data){
			//\Kinnect2::update_skore('brand_follow', $data->user_id);
		});
	}
    protected $table = "brand_memberships";
}

