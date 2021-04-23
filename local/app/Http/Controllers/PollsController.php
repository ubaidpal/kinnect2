<?php

namespace App\Http\Controllers;

use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Facades\PollsClassFacade;
use App\Classes\UrlFilter;
use App\Http\Requests;
use App\Poll;
use App\User;
use Illuminate\Http\Request;
use App\ActivityAction;
use App\Repository\Eloquent\ActivityActionRepository;

class PollsController extends Controller
{
    protected $data;
    protected $user_id;
    private   $is_api;

    public function __construct(Request $middleware) {
        $this->user_id = $middleware['middleware']['user_id'];
        @$this->data->user = $middleware['middleware']['user'];
        $this->is_api        = $middleware['middleware']['is_api'];
        $this->activity_type = \Config::get('constants_activity.OBJECT_TYPES.POLLS');
    }

    public function index() {
        $query = [];
        $query['title'] = Input::get('title');
        $query['search_init'] = Input::get('search_init');
        $pols = \PollsClassFacade::getAllPolls($this->user_id,$query);

        if ($this->is_api) {
            $all_polls       = \PollsClassFacade::get_all_polls();
            $data['results'] = $this->_get_polls_detail($all_polls);

            return \Api::success($data);
        } else {
            return view('polls.AllPolls', $pols)->with('query',$query)->with('page_Title', 'Recommended Polls');
        }

    }


    public function create() {
        return view('polls.CreatePoll')->with('page_Title', 'Create Poll');
    }

    public function store(Request $request) {

        if(!$this->is_api){
            $this->validate($request,[
                'title' => 'required',
                'poll_option.0' => 'required',
                'poll_option.1' => 'required',
            ]);
        }

        $poll = new Poll($request->all());

        \PollsClassFacade::storePoll($poll, $this->user_id);
        if ($this->is_api) {
            $all_polls       = \PollsClassFacade::get_all_polls();
            $data['results'] = $this->_get_polls_detail($all_polls);

            return redirect::to('polls');
        } else {
            return redirect::to('polls');
        }

    }

    public function store_api(Request $request) {
        $title              = Input::get('title');
        $poll_option        = Input::get('poll_option');
        $auth_allow_view    = Input::get('auth_allow_view');
        $auth_allow_comment = Input::get('auth_allow_comment');

        if (empty($title) || empty($poll_option) || empty($auth_allow_view) || empty($auth_allow_comment)) {
            return \Api::invalid_param();
        }
        $poll = new Poll($request->all());

        \PollsClassFacade::storePoll($poll, $this->user_id);


        if ($this->is_api) {
            $all_polls       = \PollsClassFacade::get_all_polls();
            $data['results'] = $this->_get_polls_detail($all_polls);

            return \Api::success($data);
        } else {
            return redirect::to('polls');
        }

    }

    /**
     * @param null $id
     *
     * @return $this|array
     */
    public function edit($id = NULL) {
        if ($this->is_api) {
            $id = \Input::get('poll_id');
        }
        if (!$id) {
            return \Api::invalid_param();
        }

        $poll = \PollsClassFacade::editPoll($id);

        if ($this->user_id == $poll->user_id) {
            if ($this->is_api) {
                return \Api::success(['data' => \PollsClassFacade::get_poll_details($poll, TRUE)]);
            } else {
                $poll = PollsClassFacade::editPoll($id);

                return view('polls.EditPrivacy')->with('poll', $poll);
            }
        } else {
            if ($this->is_api) {
                return \Api::access_denied();
            } else {
                return redirect::to('polls');
            }
        }
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function update($id = NULL) {
        $view    = (Input::get('auth_allow_view'));
        $comment = (Input::get('auth_allow_comment'));
        $search  = (Input::get('search'));
        if ($this->is_api) {
            $id = Input::get('poll_id');
        }
        if (!$id || empty($comment)) {
            return \Api::invalid_param();
        }

        \PollsClassFacade::updatePoll($id, $view, $comment, $search, $this->user_id);
        if ($this->is_api) {
            return $this->show();
        } else {
            return redirect::to('polls');
        }

    }

    public function show($id = NULL) {
        if ($this->is_api) {
            $id = Input::get('poll_id');
            if (!$id) {
                return \Api::invalid_param();
            }
        }

        $action = ActivityAction::where('type', 'like', 'poll_create')
            ->where('object_type', 'like', 'poll')
            ->where('object_id', $id)
            ->select(['action_id'])
            ->first();
        $data   = \PollsClassFacade::showPoll($id, $this->user_id);
        if (!$data) {
            return \Api::detail_not_found();
        }
        $privacy = is_allowed($data['poll']->id, 'poll', 'view', $this->user_id, $data['poll']->user_id);
        if ($this->is_api) {

            if ($privacy || $this->user_id == $data['poll']->user_id) {
                return \Api::success(['data' => \PollsClassFacade::get_poll_details($data['poll'], TRUE, $this->user_id), 'action_id' => $action->action_id]);

            }

            return \Api::access_denied();

        } else {

            if (!empty($action->action_id)) {
                return redirect()->action('HomeController@postDetail', [$action->action_id]);
            }
        }
        $data['is_authorized'] = $privacy;

        return view('polls.PollDetail', $data);
    }

    public function destroy($id = NULL) {
        if ($this->is_api) {
            $id = Input::get('poll_id');
        }
        if (!$id) {
            return \Api::invalid_param();
        }

        \PollsClassFacade::deletePoll($id, $this->user_id);

        if ($this->is_api) {
            return \Api::success_with_message();
        } else {
            return redirect()->back();
        }

    }

    public function updatesVotes($option_id = NULL) {
        if ($this->is_api) {
            $option_id = Input::get('option_id');
        }
        if (!$option_id) {
            return \Api::invalid_param();
        }

        $result = \PollsClassFacade::pollVotes($option_id, $this->user_id);
        if ($this->is_api) {
            if ($result) {
                return \Api::success_with_message();
            } else {
                return \Api::detail_not_found();
            }

        } elseif (\Request::ajax()) {
            if ($result) {
                $acObj     = new ActivityActionRepository();
                $action_id = \Input::get('postID');
                $message   = ['message' => 'success', 'post' => $acObj->getPostByID($action_id, $this->user_id)];
            } else {
                $message = ['message' => 'error'];
            }

            return response()->json($message);

        } else {
            return redirect()->back();
        }

    }

    public function closed($id = NULL) {
        if ($this->is_api) {
            $id = Input::get('poll_id');
        }
        if (!$id) {
            return \Api::invalid_param();
        }
        \PollsClassFacade::closePoll($id, $this->user_id);

        if ($this->is_api) {
            return \Api::success_with_message();
        } else {
            return redirect()->back();
        }

    }

    public function manage() {
        $query = [];
        $query['title'] = Input::get('title');
        $query['search_init'] = Input::get('search_init');
        $poll = \PollsClassFacade::managePoll($this->user_id, $this->is_api,$query);
        if ($this->is_api) {
           //$polls = $this->_get_polls_detail($poll);

            $data['results'] = $this->_get_polls_detail($poll);

            return \Api::success($data);
        } else {
            return view('polls.MyPolls',$query)->with('poll', $poll)->with('page_Title', 'Manage Polls');
        }

    }

    public function recommended_polls() {
        $polls = \PollsClassFacade::get_polls($this->user_id);
        if ($this->is_api) {
            $polls = $this->_get_polls_detail($polls);

            $data['results'] = $this->_get_polls_detail($polls);

            return \Api::success($data);
        } else {
            return view('polls.MyPolls')->with('poll', $polls);
        }
    }

    public function _get_polls_detail($all_polls) {
        $polls = array();
        foreach ($all_polls as $poll) {
            $privacy = is_allowed($poll->id, 'poll', 'view', $this->user_id, $poll->user_id);
            if ($privacy || $this->user_id == $poll->user_id) {
                $polls[] = \PollsClassFacade::get_poll_details($poll, FALSE, $this->user_id);
            }
        }

        return $polls;
    }


}