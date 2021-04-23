<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class users_membership extends Model
{
    public static function isFollowing($brand_id)
    {
        return DB::table('users_memberships')
            ->where('resource_id', $brand_id)
            ->where('user_id', Auth::user()->id)
            ->count();
    }

    public static function follow($resource_id)
    {
         DB::table('users_memberships')->insert([
            ['resource_id' => $resource_id, 'user_id' => Auth::user()->id, 'resource_type' => 'AppBrand'],
            ['resource_id' => Auth::user()->id, 'user_id' => $resource_id, 'resource_type' => 'AppBrand']
        ]);

        return '1';
    }
    public static function updateFollowing($resource_id)
    {
        DB::table('users_memberships')
            ->where('resource_id', '=', $resource_id)
            ->where('user_id',  '=', Auth::user()->id)
            ->update(['user_approved' => 1]);

        DB::table('users_memberships')
            ->where('resource_id', '=', Auth::user()->id)
            ->where('user_id',  '=', $resource_id)
            ->update(['resource_approved' => 1]);
        return '1';
    }
    public static function unfollow($resource_id)
    {
        DB::table('users_memberships')
            ->where('resource_id', '=', $resource_id)
            ->where('user_id',  '=', Auth::user()->id)
            ->update(['user_approved' => 0]);

        DB::table('users_memberships')
            ->where('resource_id', '=', Auth::user()->id)
            ->where('user_id',  '=',$resource_id)
            ->update(['resource_approved' => 0]);

        return '1';
    }
}
