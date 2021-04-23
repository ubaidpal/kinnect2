<?php

namespace App\Http\Middleware;

use Closure;
use App\Classes\UrlFilter;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use App\User;
use Auth;
use App\Repository\Eloquent\FriendshipRepository as Friend;

class DataProcess {

    /*protected $koins = [
        'event/create/*','oauth/access_token','shareStatus','addComment/{id}'
    ];*/
    private $user;
    private $user_id;
    /**
     * @var Friend
     */
    private $friendshipRepository;

    /**
     * DataProcess constructor.
     *
     * @param Friend $friendshipRepository
     *
     * @internal param $user_id
     */
    public function __construct(Friend $friendshipRepository) {

        $this->friendshipRepository = $friendshipRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param Friend $friendshipRepository
     *
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $data['middleware']['is_api']  = self::get_url();
        $data['middleware']['user_id'] = self::get_user_detail()['user_id'];
        $data['middleware']['user']    = self::get_user_detail()['user'];

        $request->merge($data);
        $data['middleware']['user_detail'] = $data['middleware']['user']['user_detail'];
        \View::share($data['middleware']);
        \View::share(['current_user' => $data['middleware']['user']]);

        // \View::share(['friend_request'=> $friend_request]);
        $this->setTimeZone();

        if($data['middleware']['user']->user_type != 1 && $data['middleware']['user']->user_type != 2) {
            $admin_route = \Config::get('constants.ADMIN_URL_PREFIX');
            $user        = Auth::user();
            if($user->is('super.admin')) { // you can pass an id or slug
                //echo 'Other'; die;
                return redirect()->route('admin.home');
            } elseif($user->is('dispute.manager') || $user->is('arbitrator')) {
                return redirect('admin/super-admin/claims-unassigned');
            } else {
                return redirect('admin/withdrawalRequests');
            }

        }
        return $next($request);

        /*foreach ($this->koins as $except) {
            if ($request->is(trim($except, '/'))) {
                dd(trim($except, '/'));
            }else{
                return $next($request);
            }
        }*/

    }

    private function get_url() {
        return UrlFilter::filter();
    }

    /**
     *
     */
    private function get_user_detail() {
        $is_api = self::get_url();

        if($is_api) {
            $user_id = Authorizer::getResourceOwnerId();
            $user    = User::findOrNew($user_id);
        } else {
            if(Auth::check()) {
                $user = Auth::user();
                if($user->user_type == \Config::get('constants.BRAND_USER')) {
                    $user['user_detail'] = $user->brand_detail;
                } else {
                    $user['user_detail'] = $user->consumer_detail;
                }
                $user_id = $user->id;
            }
        }

        if(isset($user_id)) {
            return ['user_id' => $user_id, 'user' => $user,];
        }
    }

    /**
     *
     */
    private function setTimeZone() {
        //'<tt><pre>'; print_r(self::get_user_detail()['user']); die;
        if(Auth::check()) {
            $timeZone = self::get_user_detail()['user']->timezone;
            if(!empty($timeZone)) {
                \Config::set('constants.USER_TIME_ZONE', $timeZone);
            } else {
                \Config::set('constants.USER_TIME_ZONE', \Config::get('app.timezone'));
            }
        }
    }
}
