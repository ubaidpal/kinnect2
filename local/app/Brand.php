<?php

namespace App;
use App\Repository\Eloquent\UsersRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Brand extends Model
{
    /**
     * @var UsersRepository
     */

    /**
     * @var UsersRepository
     */



    protected $table = "users_brands";

    protected $fillable = ['brand_history', 'brand_name', 'description'];

    public function user()
    {
        return $this->morphOne('\App\User', 'userable');
    }

    public function BattleOption()
    {
        return $this->hasMany('App\BattleOption');
    }


    public static function profileSetting()
    {
        return 'Whatever data we are in need';
    }
    public static function isFollowing($brand_id, $user_id)
    {
        return DB::table('brand_memberships')
            ->where('brand_id', $brand_id)
            ->where('user_id', $user_id)
            ->count();
    }
    public function brand_detail(  ) {
        return $this->hasOne('App\user', 'userable_id');
    }

}
