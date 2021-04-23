<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Bican\Roles\Traits\HasRoleAndPermission;
use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract,HasRoleAndPermissionContract {

	use Authenticatable, CanResetPassword, HasRoleAndPermission, SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
    protected $dates = ['deleted_at'];
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function accountIsActive($code) {

		$user = User::where('activation_code', '=', $code)->first();
		$user->active = 1;
		$user->activation_code = '';
		if($user->save()) {
			\Auth::login($user);
		}
		return true;
	}
	public function userable()
	{
		return $this->morphTo();
	}
	public function consumer()
	{
		return $this->morphOne('\App\Consumer', 'consumerable');
	}
	public function brand()
	{
		return $this->morphOne('\App\Brand', 'brandable');
	}
	public function Poll()
    {
        return $this->hasMany('App\Poll');
    }
    public function PollVote()
    {
        return $this->hasOne('App\PollVote');
    }

    public function Battle()
    {
        return $this->hasMany('App\Battle');
    }
    public function BattleVote()
    {
        return $this->hasOne('App\BattleVote');
    }

    public function GroupMembership()
    {
        return $this->hasMany('App\GroupMembership');
    }


    public function resource()
    {
        return $this->hasOne('App\Friendship', 'resource_id');
    }

    public function user_detail()
    {
        return $this->hasOne('App\Friendship', 'user_id');
    }

    public function consumer_detail()
    {
        return $this->hasOne('App\Consumer', 'id', 'userable_id');
    }

    public function brand_detail()
    {
        return $this->hasOne('App\Brand', 'id', 'userable_id');

    }

    public function notifications()
    {
        return $this->hasMany('App\ActivityNotification');
    }

    public function object_detail()
    {
        return $this->hasOne('App\ActivityAction', 'id');
    }

    public function storage_file()
    {
        return $this->belongsTo('App\StorageFile', 'file_id');

    }
    public function album_photo()
    {
        return $this->belongsTo('App\AlbumPhoto', 'photo_id');

    }

}

