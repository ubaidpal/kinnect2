<?php 

namespace App\Http\Middleware;

use DB;
use App\UserMembership;
use Carbon\Carbon;
use Closure;
use \Cache;
use Illuminate\Contracts\Auth\Guard;

class AppCache {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct()
	{
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */	
	
	function cacheMembers()
	{
		$user_id = \Auth::user()->id;
		
		$members = UserMembership::
				  where('resource_id',$user_id)
				->where('active',1)
				->where('resource_approved',1)
				->where('user_approved',1)
				->lists('user_id','user_id');

		Cache::forever('user_members_'.$user_id, $members);
	}

}