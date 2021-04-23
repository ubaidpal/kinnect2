<?php

namespace App\Http\Controllers;

use App\Events\GetNotification;
use App\Friendship;
use App\Repository\Eloquent\FriendshipRepository;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Classes\UrlFilter;
use App\User;
use App\Repository\Eloquent\SkoreRepository;
use SNS;

class FriendshipController extends Controller
{

    protected $data;
    protected $is_api;
    protected $userRepository;
    private   $user_id;
    private   $friend;

    /**
     * @param FriendshipRepository $friend
     * @param Request              $middleware
     */
    public function __construct(FriendshipRepository $friend, Request $middleware) {
        $this->friend = $friend;

        $this->user_id = $middleware['middleware']['user_id'];
        @$this->data->user = $middleware['middleware']['user'];
        $this->is_api = $middleware['middleware']['is_api'];
    }

    /**
     * Display a listing of the resource.
     *
     * @param SkoreRepository $skoreRepository
     * @param null            $skip_id
     *
     * @return \Illuminate\Http\Response
     */

    public function index($skip_id = NULL) {

        $this->friend->updateNotification($this->user_id);
        if (!$this->is_api) {
            /*if ($this->data->user->user_type == \Config::get('constants.BRAND_USER')) {
                    return redirect('brands');
            }*/
        }

        if ($this->is_api) {
            $data = $this->friend->get_all_info($this->user_id, $skip_id,'requests');
            $result = $this->_get_users_meta($data['requests']);

            return \Api::success_list($result);
        } else {
            
            $data = $this->friend->get_all_info($this->user_id, $skip_id);
            
            $data['title'] = 'People you mau know';

            return view('friends.index', $data)->with('page_Title', 'Friends');
        }
    }

    public function get_all_recommended($take = 10, $skip = 0) {
        if ($this->is_api) {
            $take = \Input::get('take');
            $skip = \Input::get('skip');
        }
        $data = $this->friend->all_recommended($this->user_id, NULL, $take, $skip);
        if ($this->is_api) {
            if (!empty($data)) {
                $data = $this->_get_users_meta($data);

                return \Api::success_list($data);
            }

            return \Api::result_not_found();
        } else {

            $data_all['all_recommended'] = $data;
            $data_all['type']            = 'all_recommended';

            return view('templates/partials/paginate/users', $data_all);
        }
    }
    public function get_all_recommended_limit($take = 10, $skip = 0) {
        if ($this->is_api) {
            $take = \Input::get('take');
            $skip = \Input::get('skip');
        }
        $data = $this->friend->all_recommended_limit($this->user_id, NULL, $take, $skip);
        if ($this->is_api) {
            if (!empty($data)) {
                $data = $this->_get_users_meta($data);

                return \Api::success_list($data);
            }

            return \Api::result_not_found();
        } else {

            $data_all['all_recommended'] = $data;
            $data_all['type']            = 'all_recommended';

            return view('templates/partials/paginate/users', $data_all);
        }
    }
    public function _get_users_meta($data) {
        $result = [];
        foreach ($data as $row) {
            if(empty($row->id)){
                continue;
            }
            $result[] = $this->friend->_get_user_meta($row);
        }

        return $result;
    }

    public function sent_request() {
        $data = $this->friend->sent_friends_request($this->user_id);
        if ($this->is_api) {
            if (count($data) > 0) {
                $users = $this->_get_users_meta($data);

                return \Api::success_list($users);
            } else {
                return \Api::result_not_found();
            }
        } else {
            return view('friends.request-sent', $data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_friend($resource_id = NULL) {

        if ($this->is_api) {
            $user_id = \Input::get('friend_id');
            if (empty($user_id)) {
                return \Api::invalid_param();
            }
            $this->friend->add_friend($user_id, $this->user_id);
            $pushData = [];
            $sender   = User::where('id', $user_id)
                ->select(['name'])
                ->first();

            $pushData["title"]               = $this->data->user->displayname . " sent you friend request";
            $pushData["data"]["sender_id"]   = $this->user_id;
            $pushData["data"]["sender_name"] = $this->data->user->displayname;
            $pushData["data"]["module"]      = "friendship_requests";
            \SNS::sendPushNotification($user_id, $pushData);

            return \Api::success_with_message();
        } else {
            $this->friend->add_friend($resource_id, $this->user_id);
            $pushData["title"]               = $this->data->user->displayname . " sent you friend request";
            $pushData["data"]["sender_id"]   = $this->user_id;
            $pushData["data"]["sender_name"] = $this->data->user->displayname;
            $pushData["data"]["module"]      = "friendship_requests";
            \SNS::sendPushNotification($resource_id, $pushData);
            if (\Request::ajax()) {
                return 'success';
            } else {
                return redirect()->back();
            }

        }
    }

    public function confirm($resource_id = NULL) {


        if ($this->is_api) {
            $resource_id = \Input::get('friend_id');
            if (empty($resource_id)) {
                return \Api::invalid_param();
            }
            $this->friend->confirm($resource_id, $this->user_id);

            return \Api::success_with_message();
        } else {
            $this->friend->confirm($resource_id, $this->user_id);
            if (\Request::ajax()) {
                return 'success';
            } else {
                return redirect()->back();
            }
        }
    }

    /**
     * @param $user_id
     * @param $resource_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unfollow($resource_id = NULL) {

        if ($this->is_api) {
            $resource_id = \Input::get('friend_id');
            if (empty($resource_id)) {
                return \Api::invalid_param();
            }
            $this->friend->unfollow($resource_id, $this->user_id);

            return \Api::success_with_message();
        } else {
            $this->friend->unfollow($resource_id, $this->user_id);

            return redirect()->back();
        }
    }

    /**
     * @param $user_id
     * @param $resource_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function follow($resource_id = NULL) {


        if ($this->is_api) {
            $resource_id = \Input::get('friend_id');
            if (empty($resource_id)) {
                return \Api::invalid_param();
            }
            $this->friend->follow($resource_id, $this->user_id);

            return \Api::success_with_message();
        } else {
            $this->friend->follow($resource_id, $this->user_id);

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param null $user_id
     *
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy($user_id = NULL) {

        if ($this->is_api) {
            $user_id = \Input::get('friend_id');
            if (empty($user_id)) {
                return \Api::invalid_param();
            }
            $this->friend->destroy($user_id, $this->user_id);

            return \Api::success_with_message();
        } else {
            $this->friend->destroy($user_id, $this->user_id);

            if (\Request::ajax()) {
                return 'success';
            } else {
                return redirect()->back();
            }
        }
    }

    /**
     * @param       $data
     * @param array $params
     *
     * @return mixed
     */
    private function return_response($data, array $params) {
        $data['error'] = $params['error'];
        $data['code']  = $params['code'];

        return $data;
    }

}
