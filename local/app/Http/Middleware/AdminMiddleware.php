<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->setTimeZone();
        \View::share(['user_id' => \Auth::id()]);

       $user = \Auth::user();
        if($user->user_type == 1 || $user->user_type == 2){
            return redirect('/');
        }
        return $next($request);
    }

    private function setTimeZone()
    {
        //'<tt><pre>'; print_r(self::get_user_detail()['user']); die;
        if (\Auth::check()) {
            $timeZone = \Auth::user()->timezone;
            if (!empty($timeZone)) {
                \Config::set('constants.USER_TIME_ZONE', $timeZone);
            }else{
                \Config::set('constants.USER_TIME_ZONE', \Config::get('app.timezone'));
            }
        }
    }
}
