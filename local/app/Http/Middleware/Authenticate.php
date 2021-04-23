<?php namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Classes\UrlFilter;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use App\User;
use Auth;

class Authenticate {

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
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		if (\Auth::check() && !\Auth::user()->active) {

			if(isset(\Auth::user()->deleted)){
	            if(\Auth::user()->deleted == 1){
		            return view('auth.guest_activate')
			            ->with( 'deleted', 'deleted' )
			            ->with( 'email', \Auth::user()->email )
			            ->with( 'date', \Auth::user()->updated_at->format('Y-m-d') )
			            ->with( 'expired_token', '' );
	            }
	        }

			$created = new Carbon(\Auth::user()->token_expiry_date);
			$now = Carbon::now();
			if($created->diff($now)->days > 30){
				return view('auth.guest_activate')
					->with( 'deleted', '' )
					->with( 'expired_token', 'expired_token' );
			}// if token is expired

			//\Session::flash('message', 'Please activate your account to proceed.');
			//return redirect()->guest('auth.guest_activate');
			return view('auth.guest_activate')
				->with( 'email', \Auth::user()->email )
				->with( 'deleted', '' )
				->with( 'date', \Auth::user()->created_at->format('Y-m-d') )
				->with( 'expired_token', '' );
		}

		if ($this->auth->guest())
		{
			if ($request->ajax())
			{
				return response('Unauthorized.', 401);
			}
			else
			{
				return view('auth.login');
				//return redirect()->guest('auth/login');
			}
		}
		return $next($request);
	}

}
