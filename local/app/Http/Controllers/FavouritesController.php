<?php

namespace App\Http\Controllers;


use App\ActivityAction;
use App\ActivityFavourite;
use App\Repository\Eloquent\ActivityActionRepository;
use App\Repository\Eloquent\UsersRepository;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes\UrlFilter;
use App\User;


class FavouritesController extends Controller
{
    private   $favourite;
    protected $data;
    protected $user_id;
    /**
     * @var UsersRepository
     */
    private $usersRepository;

    public function __construct(ActivityActionRepository $favourite, UsersRepository $usersRepository) {

        $this->favourite = $favourite;
        @$this->data->user = '';
        $this->is_api = UrlFilter::filter();

        if ($this->is_api) {
            $this->user_id = Authorizer::getResourceOwnerId();
            @$this->data->user = User::findOrNew($this->user_id);
        } else {
            if (Auth::check()) {
                @$this->data->user = Auth::user();
                $this->user_id = $this->data->user->id;
            }
        }
        $this->usersRepository = $usersRepository;
    }

    public function index() {
        $fav    = ActivityFavourite::select('resource_id')->where('poster_id', $this->user_id)->orderBy('created_at', 'desc')->paginate(50);

        $detail = array();
        foreach ($fav as $f) {
            $detail[] = $this->favourite->getPostByID($f->resource_id, $this->user_id);
        }

        return view('favourites')->with('d', $detail)->with('ff', $fav)->with('page_Title', 'Favourites');
    }

    public function all_favourite() {
        $per_page = 10;
        $page     = 1;
        if (\Input::has('page')) {
            $page = \Input::get('page');
        }
        $start_point = ($page * $per_page) - $per_page;

        $fav = ActivityFavourite::select('resource_id')
            ->where('poster_id', $this->user_id)
            ->orderBy('created_at', 'desc')
            ->lists('resource_id');

        $allActivity = ActivityAction::whereIn('action_id', $fav)
            ->orderBy('action_id', 'DESC')
            ->take($per_page)
            ->skip($start_point)
            ->get();

        $response = [];
        if ($allActivity) {
            foreach ($allActivity as $row) {

                $user = $this->usersRepository->get_user($row->subject_id);
                if ($user) {
                    $gender = '';
                    if ($user->user_type == \Config::get('constants.REGULAR_USER')) {
                        $gender = $user->consumer_detail->gender;
                        $gender = ($gender == 1 ? 'male' : 'female');
                    }
                    $data                            = activity_log_string($row);
                    $data['subject']['subject_name'] = $user->displayname;
                    $data['subject']['profile_url']  = $user->username;
                    $data['subject']['profile_pic']  = \Kinnect2::getPhotoUrl($user->photo_id, $user->id, 'user', 'thumb_normal');
                    $data['subject']['gender']       = $gender;

                    $response[] = $data;
                }
            }

            return \Api::success(['results' => $response]);
        } else {
            return \Api::result_not_found();
        }
    }

    public function koins() {
        return view('koins')->with('page_Title', 'Koins');
    }
}