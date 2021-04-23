<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Battle;
use App\ActivityAction;
use App\Repository\Eloquent\ActivityActionRepository;


class BattlesController extends Controller {
	protected $data;
	protected $user_id;
	private   $is_api;

	public function __construct( Request $middleware ) {

		$this->user_id = $middleware['middleware']['user_id'];
		@$this->data->user = $middleware['middleware']['user'];
		$this->is_api = $middleware['middleware']['is_api'];
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
     */
	public function create() {

		if($this->is_api){
			$brands = \BattlesClassFacade::createBattle();
			return \Api::success(['results' => $brands]);
		}
		return view( 'battles.CreateBattle')->with('page_Title', 'Create Battle' );
	}

	public function store( Request $request ) {
		$brands = Input::get( 'brand_name' );
		if ( ! $brands ) {
			if ( $this->is_api ) {
				return \Api::invalid_param();
			}
		}
		if ( $this->is_api ) {
			$request->select1 = \Input::get('brand_name')[0];
			$request->select2 = \Input::get('brand_name')[0];
		}else{

		    $this->validate($request,[
		        'title' => 'required',
                'select1' => 'required',
                'select2' => 'required|different:select1',
            ]);

		    $request->select1 = $request->get('select1');

			$request->select2 = $request->get('select2');
		}


		$battle = new Battle( $request->all() );

		\BattlesClassFacade::storeBattle( $battle, $this->user_id );

		if ( $this->is_api ) {
			return $this->index();
		}

		return redirect::to( 'battles' );
	}

	public function index() {

		if ( $this->is_api ) {
			$battle = $this->_get_battle_details( \BattlesClassFacade::other_battle($this->user_id) );
			if ( ! $battle ) {
				return \Api::result_not_found();
			}
			$my_battles = $this->_get_battle_details(\BattlesClassFacade::user_battle($this->user_id) );
			$battle['my_battles'] = $my_battles['results'];
			return \Api::success( $battle );
		}
		$query['title'] = Input::get('title');
		$query['search_init'] = Input::get('search_init');
		$data = \BattlesClassFacade::getAllBattles( $this->user_id,$query );
		$data['tagged_battles'] = \BattlesClassFacade::getTaggedBattle($this->user_id);
		
		return view( 'battles.AllBattles', $data )->with('query',$query)->with('page_Title', 'Recommended Battles' );
	}
	public function getRecommendBattles() {


		if ( $this->is_api ) {
			$battles = \BattlesClassFacade::other_battle($this->user_id);
			if ( ! $battles ) {
				return \Api::result_not_found();
			}

			$data['results'] = [];
			$aarObj = new ActivityActionRepository();
			foreach($battles as $row){

				$action_id = $aarObj->getIDByObject('battle', $row->id);
				if(!empty($action_id)){
					$data['results'][] = $aarObj->getPostByID($action_id,$this->user_id);
				}
			}

			return \Api::success( $data );
		}
	}
	public function getRecomenedBattles() {


		if ( $this->is_api ) {
			$battle = $this->_get_battle_details( \BattlesClassFacade::other_battle($this->user_id) );
			if ( ! $battle ) {
				return \Api::result_not_found();
			}
			$my_battles = $this->_get_battle_details(\BattlesClassFacade::user_battle($this->user_id) );
			$battle['my_battles'] = $my_battles['results'];
			return \Api::success( $battle );
		}

		return view( 'battles.AllBattles', $data )->with('page_Title', 'Recommended Battles' );
	}

	public function _get_battle_details( $battles ) {
		$data['results'] = [ ];
		foreach ( $battles as $battle ) {
			$privacy = is_allowed($battle->id, 'battle', 'view', $this->user_id, $battle->user_id);
			if($privacy || $this->user_id == $battle->user_id) {
				$data['results'][] = \BattlesClassFacade::get_battle_details($battle);
			}
		}

		return $data;
	}

	public function edit( $id = null ) {
		if ( $this->is_api ) {
			$id = Input::get( 'battle_id' );
		}

		if ( ! $id ) {
			if ( $this->is_api ) {
				return \Api::invalid_param();
			}
		}

		$battle = \BattlesClassFacade::editBattle( $id );

		if ( $this->user_id == $battle->user_id ) {
			if ( $this->is_api ) {
				return \Api::success(['data' => \BattlesClassFacade::get_battle_details( $battle, true ) ] );
			}

			return view( 'battles.EditPrivacy' )->with( 'battle', $battle );
		} else {
			if ( $this->is_api ) {
				return \Api::access_denied();
			}

			return redirect::to( 'battles' );
		}

	}

	public function update( $id = null ) {
		if ( $this->is_api ) {
			$id = Input::get( 'battle_id' );
		}

		if ( ! $id ) {
			if ( $this->is_api ) {
				return \Api::invalid_param();
			}
		}

		$view    = ( Input::get( 'auth_allow_view' ) );
		$comment = ( Input::get( 'auth_allow_comment' ) );
		$search  = ( Input::get( 'search' ) );
		$battle = Battle::findOrFail($id);

		if ($this->user_id != $battle->user_id) {
			if ( $this->is_api ) {
				return \Api::access_denied();
			}
			return redirect()->back();
		}
		 \BattlesClassFacade::updateBattle( $id, $view, $comment, $search, $this->user_id );

		if ( $this->is_api ) {
			return $this->show($id);
			//return $this->index();
		}

		return redirect::to( 'battles' );
	}

	/**
	 * @param null $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function show( $id = null) {

		if ( $this->is_api ) {
			$id = Input::get( 'battle_id' );
			if ( !$id ) {
				return \Api::invalid_param();
			}
		}
		$action = ActivityAction::where('type','like','battle_create')
				->where('object_type','like','battle')
				->where('object_id',$id)
				->select(['action_id'])
				->first();
		if ( $this->is_api ) {
			 $battle = \BattlesClassFacade::editBattle( $id );

			if ( empty( $battle ) ) {
				return \Api::detail_not_found();
			}
			$privacy = is_allowed($battle->id, 'battle', 'view', $this->user_id, $battle->user_id);
			if($privacy  || $this->user_id == $battle->user_id) {
				return \Api::success(['data' => \BattlesClassFacade::get_battle_details($battle, true, $this->user_id), 'action_id'=>$action->action_id]);
			}else{
				return \Api::access_denied();
			}
		}else{
			if(!empty($action->action_id))
			{
				return redirect()->action('HomeController@postDetail',[$action->action_id]);
			}
		}
		$data = \BattlesClassFacade::showBattle( $id, $this->user_id );
		$privacy = is_allowed($data['battle']->id, 'battle', 'view', $this->user_id, $data['battle']->user_id);
		$data['is_authorized'] = $privacy;
		return view( 'battles.BattleDetail', $data );
	}

	public function destroy( $id = null ) {
		if ( $this->is_api ) {
			$id = Input::get( 'battle_id' );
		}

		if ( ! $id ) {
			if ( $this->is_api ) {
				return \Api::invalid_param();
			}
		}
		$result = \BattlesClassFacade::deleteBattle( $id, $this->user_id );

		if ( $this->is_api ) {
			if ( ! $result ) {
				return \Api::access_denied();
			}

			return \Api::success_with_message();
		}

		return redirect()->back();
	}

	public function updatesVotes( $option_id = null ) {
		if ( $this->is_api ) {
			$option_id = Input::get( 'option_id' );
		}
		if ( ! $option_id ) {
			if ( $this->is_api ) {
				return \Api::invalid_param();
			}
		}
		$bool = \BattlesClassFacade::battleVotes( $option_id, $this->user_id );

		if ( $this->is_api ) {
			return \Api::success_with_message();
		}
		if(\Request::ajax())
		{
			if($bool){
				$acObj = new ActivityActionRepository();
				$action_id = \Input::get('postID');
				$message = ['message' => 'success','post' => $acObj->getPostByID($action_id,$this->user_id)];
			}else{
				$message = ['message' => 'error'];
			}

			return response()->json($message);
		}
        return redirect()->back();
	}

	public function closed( $id = null ) {
		if ( $this->is_api ) {
			$id = Input::get( 'battle_id' );
		}
		if ( ! $id ) {
			if ( $this->is_api ) {
				return \Api::invalid_param();
			}
		}
		$result = \BattlesClassFacade::closeBattle( $id, $this->user_id );

		if ( $this->is_api ) {
			if($result){
				return \Api::success_with_message();
			}else{
				return \Api::access_denied();
			}

		}

        return redirect()->back();
	}

	public function manage() {


		if ( $this->is_api ) {
			 $battles = \BattlesClassFacade::user_battle($this->user_id);
			return \Api::success($this->_get_battle_details($battles));
		}
		$query = [];
		$query['title'] = Input::get('title');
		$query['search_init'] = Input::get('search_init');
		$battle = \BattlesClassFacade::manageBattle( $this->user_id,$query );
		return view( 'battles.MyBattles',$query )->with( 'battle', $battle )->with('page_Title' , 'Manage Battle');
	}

	public function brandBattleNameSuggestion() {
		$term    = Input::get( 'searchField' );
		if ( ! $term ) {
			if ( $this->is_api ) {
				return \Api::invalid_param();
			}
		}
		$results = array();
		$queries = DB::table( 'users_brands' )->select( 'brand_name', 'id' )
		             ->where( 'brand_name', 'like', '%' . $term . '%' )->take( 5 )->get();
		foreach ( $queries as $query ) {
			$results[] = [ 'id' => $query->id, 'value' => $query->brand_name ];
		}
		if ( $this->is_api ) {
			return \Api::success(['results' => $results]);
		}

		return json_encode( $results );
	}
	public function showOnTimeline($battle_id){
		\BattlesClassFacade::showBattleOnTimeline($battle_id,$this->user_id);
		return \Redirect::back();
	}

	/**
	 * @param array $beforeFilters
	 */
	public function removeFromTimeline($battle_id)
	{
		\BattlesClassFacade::removeFromTimeline($battle_id,$this->user_id);
		return \Redirect::back();
	}
	public function getBrands()
	{
		$term = \Request::get('term');

		$users = DB::table('users')
			->select('users.userable_id as id', 'users.name', 'users.first_name', 'users.username', 'users.displayname', 'users.photo_id as image')
			->where('users.displayname','LIKE',"$term%")
			->where('users.search', '1')
			->where('users.active', '1')
			->where('users.user_type', 2)
			->get();
		$brands = [];
		foreach ($users as $user) {
			$user->image = \	Kinnect2::getPhotoUrl($user->image, $user->id, 'user', 'thumb_icon');
			$brands[]    = array('id' => $user->id, 'label' => $user->displayname, 'image_src' => $user->image);
		}

		return response()->json($brands);
	}
}