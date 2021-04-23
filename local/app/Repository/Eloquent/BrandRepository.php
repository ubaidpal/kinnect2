<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/1/2015
 * Time: 12:48 PM
 */

namespace App\Repository\Eloquent;

use App\Brand;
use App\BrandMembership;
use App\Events\ActivityDelete;
use App\Events\ActivityLog;
use App\Events\CreateNotification;
use App\Events\NotificationDelete;
use App\Repository\Eloquent\Repository;
use App\User;
use DB;
use Illuminate\Support\Facades\Input;

class BrandRepository extends Repository {
	private $activity_type;
	/**
	 * @var Brand
	 */
	private $brand;
	/**
	 * @var UsersRepository
	 */
	private $usersRepository;

	public function __construct( Brand $brand, UsersRepository $usersRepository ) {
		parent::__construct();

		$this->activity_type   = \Config::get( 'constants_activity.OBJECT_TYPES.BRAND.NAME' );
		$this->brand           = $brand;
		$this->usersRepository = $usersRepository;
	}

	public static function unfollow( $brand_id, $user_id ) {
		$brand = BrandMembership::whereBrandId( $brand_id )->whereUserId( $user_id )->first();
		//$brand->user_approved = 0;
		$brand->delete();
		$params = [
			'subject_id'  => $user_id,
			'object_id'   => $brand_id,
			'object_type' => \Config::get( 'constants_activity.OBJECT_TYPES.BRAND.NAME' )
		];

		\Event::fire( new ActivityDelete( $params ) );
		$params = [
			'resource_id' => $brand_id,
			'subject_id'  => $user_id,
			'type'        => \Config::get( 'constants_activity.notification.BRAND-FOLLOW' ),
		];
		\Event::fire( new NotificationDelete( $params ) );

		return '1';
	}

	public function profile( $user_id ) {
		$user  = $this->usersRepository->get_user( $user_id );
		$brand = $this->get_brand( $user->userable_id );

		return $brand;
	}

	public function get_brand( $id ) {
		return \Cache::get('_brand_'.$id,function () use ($id){
		    return $this->brand->find( $id );
        });
	}

	public function _profile_info( $user ) {
		$brand = $this->get_brand( $user->userable_id );

		return $brand;
	}

	public function profileInfo( $user_id ) {
		$user  = $this->usersRepository->get_user( $user_id );
		$brand = $this->get_brand( $user->userable_id );

		return $brand;
	}

	public function getBrandKinnectors( $brand_id ) {
		$userFollowingBrandIds = DB::table( 'brand_memberships' )
		                           ->join( 'users', 'users.id', '=', 'brand_memberships.brand_id' )
		                           ->where( 'user_approved', 1 )
		                           ->where( 'brand_approved', 1 )
		                           ->where( 'users.username', $brand_id )
		                           ->orWhere( 'users.id', $brand_id )
		                           ->lists( 'user_id' );
//echo '<tt><pre>'; print_r($userFollowingBrandIds); die;
		return $brandKinnectors = User::whereIn( 'id', $userFollowingBrandIds )
		                              ->orderByRaw( "RAND()" )
		                              ->take( 100 )->get();

		return false;
	}

	public function get_brand_kinnectors($brand_id)
	{
		$kinnectors = BrandMembership::whereBrandId($brand_id)
				->whereBrandApproved(1)
				->whereUserApproved(1)
				->lists('user_id');
		if($this->is_api){
			return $brandKinnectors = User::whereIn( 'id', $kinnectors )
					->orderBy( 'displayname','ASC' )
					->get();
		}
		return $brandKinnectors = User::whereIn( 'id', $kinnectors )
				->orderBy( 'displayname','ASC' )
				->paginate(\Config::get('constants.PER_PAGE'));
	}
	public function get_brand_kinnectors_count($brand_id)
	{
		$kinnectors = BrandMembership::whereBrandId($brand_id)
				->whereBrandApproved(1)
				->whereUserApproved(1)
				->lists('user_id');

		return $brandKinnectors = User::whereIn( 'id', $kinnectors )
				->orderBy( 'displayname','ASC' )
				->count();
	}
	public function follow( $brand_id, $user_id ) {
		$brand           = new BrandMembership();
		$brand->brand_id = $brand_id;
		$brand->user_id  = $user_id;
		$brand->save();
		$attributes = array(
			'resource_id' => $brand_id,
			'subject_id'  => $user_id,
			'object_id'   => $user_id,
			'object_type' => 'user',
			'type'        => \Config::get( 'constants_activity.notification.BRAND-FOLLOW' ),
		);

		\Event::fire( new CreateNotification( $attributes ) );

		$options = array(
			'type'         => \Config::get( 'constants_activity.OBJECT_TYPES.BRAND.ACTIONS.FOLLOW' ),
			'subject'      => $user_id,
			'subject_type' => 'user',
			'object'       => $brand_id,
			'object_type'  => \Config::get( 'constants_activity.OBJECT_TYPES.BRAND.NAME' ),
		);

		\Event::fire( new ActivityLog( $options ) );

		$pushData["title"]               = $this->data->user->displayname . " is followed you";
		$pushData["data"]["sender_id"]   = $user_id;
		$pushData["data"]["sender_name"] = $this->data->user->displayname;
		$pushData["data"]["module"]      = "brand";
		\SNS::sendPushNotification($brand_id, $pushData);
		return 1;
	}

	public function updateFollowing( $brand_id, $user_id ) {
		$brand                = BrandMembership::whereBrandId( $brand_id )->whereUserId( $user_id )->first();
		$brand->user_approved = 1;
		$brand->save();

		return '1';
	}

	public function update( $user_id ) {
		$user              = \Cache::get('_user_'.$user_id,function () use ($user_id){
		    $user = User::findOrNew( $user_id );
            \Cache::forever('_user_'.$user_id,$user);
            return $user;
        });
		$user->first_name  = Input::get( 'first_name' );
		$user->last_name   = Input::get( 'last_name' );
		$user->displayname = Input::get( 'brand_name' );
		$user->name        = Input::get( 'first_name' ) . ' ' . Input::get( 'last_name' );
		$user->country     = Input::get( 'country' );
		$user->save();


		$detail = \Cache::get('_brand_'.$user->userable_id,function () use (&$user){
		    $brand = Brand::find( $user->userable_id );
            \Cache::forever('_brand_'.$user->userable_id,$brand);
            return $brand;
        });

		$detail->description   = Input::get( 'description' );
		$detail->brand_history = Input::get( 'brand_history' );
		$detail->brand_name    = Input::get( 'brand_name' );
		$detail->save();

		$options = array(
			'object_type' => $this->activity_type,
			'type'        => \Config::get( 'constants_activity.OBJECT_TYPES.USER.ACTIONS.UPDATE_PROFILE' ),
			'subject'     => $user_id,
			'object'      => $user_id,

		);
		//\Event::fire( new ActivityLog( $options ) );
	}
}