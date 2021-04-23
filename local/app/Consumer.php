<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Consumer extends Model
{
    protected $table = "users_consumers";

    public function user()
    {
        return $this->morphOne('\App\User', 'userable');
    }

    public static function profile($user_id)
    {
        $user = DB::table('users')
            ->select('userable_id')
            ->where('username', '=', $user_id)
            ->orWhere('id', '=', $user_id)
            ->first();
        $consumer = Consumer::find($user->userable_id);
        return $consumer;
    }
    public static function profileInfo($user_id)
    {
        $user = DB::table('users')
            ->select('userable_id')
            ->where('username', '=', $user_id)
            ->orWhere('id', '=', $user_id)
            ->first();
        $consumer = Consumer::find($user->userable_id);
        return $consumer;
    }
    public static function profileSetting()
    {
        return 'View is not created';

    }
    public function user_detail()
    {
        return $this->belongsTo('\App\User');

    }
}
