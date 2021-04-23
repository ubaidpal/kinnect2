<?php
namespace App\Repository\Eloquent;

use App\Consumer;
use App\Event;
use App\GroupMembership;
use App\Hashtag;
use App\Jobs\EncodeAudio;
use App\Jobs\EncodeVideo;
use App\PollOption;
use App\PollVote;
use App\User;
use App\Album;
use App\ActivityAction;
use App\UserMembership;
use App\BrandMembership;
use App\Group;
use App\AlbumPhoto;
use App\StorageFile;
use App\Video;
use App\Poll;
use App\Battle;
use App\BattleOption;
use App\ActivityComment;
use App\ActivityLike;
use App\ActivityDislike;
use App\ActivityFavourite;
use App\Link;
use App\Like;
use App\Event AS GroupEvent;
use App\Events\CreateNotification;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Services\StorageManager;
use Intervention\Image\Facades\Image;
use App\Report;
use App\UserBrand;
use App\BattleVote;
use App\Facades\Kinnect2;
use kinnect2Store\Store\StoreProduct;
use kinnect2Store\Store\StoreStorageFiles;
use kinnect2Store\Store\StoreProductReview;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ActivityActionRepository extends Repository {

	use DispatchesJobs;
	/**
	 * ActivityActionRepository constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	public function deleteActivity( $user_id, $activity_action_id ) {
		$aActionObj = new ActivityAction();
		if ( empty( $activity_action_id ) ) {
			if ( $this->is_api ) {
				return \Api::invalid_param();
			}
		}
		$action = $aActionObj->find( $activity_action_id );

		if ( isset( $action->subject_id ) && $user_id == $action->subject_id ) {
			$this->deleteDependents($action,$user_id);
			$this->deleteFavourites($action->action_id);
			if($action->type == 'share' || $action->type == 'group_share' || $action->type == 'event_share')
			{
				$this->updateObjectShareCount('minus',$action->object_type,$action->object_id);
			}
			$rowDeleted = $action->delete();

			if ( $rowDeleted ) {
				if ( $this->is_api ) {
					return \Api::success_with_message();
				}

				return array( 'message' => 'status_deleted' );
			}
		} else {
			if ( $this->is_api ) {
				return \Api::access_denied();
			}

			return array( 'message' => 'invalid_id' );
		}

	}
	protected function deleteFavourites($action_id){
		if(empty($action_id)){
			return FALSE;
		}
		return ActivityFavourite::where('resource_id',$action_id)
								->delete();
	}
	protected function updateObjectShareCount($operator,$object_type,$object_id){
		$object = \Cache::get('_'.$object_type.'_'.$object_id,function () use($object_id,$object_type){
            $query = $this->buildObjectQuery($object_type,$object_id);
            $object = $query->first();
            \Cache::put('_'.$object_type.'_'.$object_id,$object,3600);
            return $object;
        });
        if($operator == 'minus'){
			$object->share_count = $object->share_count - 1;
		}
		$object->save();
	}
	public function deleteDependents($action,$user_id){
		if($action->object_type == 'poll' && $action->type == \Config::get('constants_activity.OBJECT_TYPES.POLLS.ACTIONS.CREATE')){
			ActivityAction::where('object_type',$action->object_type)
							->where('object_id',$action->object_id)
							->where('type','share')
							->delete();
			$object = Poll::find($action->object_id);
			if(empty($object->id)){
				return FALSE;
			}
			return $object->delete();
		}elseif($action->object_type == 'battle' && $action->type == \Config::get('constants_activity.OBJECT_TYPES.BATTLES.ACTIONS.CREATE')){
			ActivityAction::where('object_type',$action->object_type)
			              ->where('object_id',$action->object_id)
			              ->where('type','share')
			              ->delete();
			$object = Battle::find($action->object_id);
			if(empty($object->id)){
				return FALSE;
			}
			return $object->delete();
		}elseif(($action->type == $action->object_type.'_create' || $action->type == $action->object_type.'_new') && $user_id == $action->subject_id){
			ActivityAction::where('object_type',$action->object_type)
				->where('object_id',$action->object_id)
				->delete();
			if($action->type == 'album_photo_new'){
				$photo_id = $action->object_id;
				$photos = AlbumPhoto::where(function($query) use ($photo_id){
										$query->where('parent_id',$photo_id);
										$query->orWhere('photo_id',$photo_id);
									})
									->get();
				$sm = new StorageManager();
				foreach ($photos as $photo){
					$file_id = $photo->file_id;
					$files = StorageFile::where(function($query) use ($file_id){
												$query->where('file_id',$file_id);
												$query->orWhere('parent_file_id',$file_id);
											})
											->get();
					foreach ($files as $file){
						if($sm->pathExists('photos/'.$file->storage_path)){
							$sm->deletFile('photos/'.$file->storage_path);
						}
						$file->delete();
					}
					$photo->delete();
				}
			}
		}
	}
	public function getGroupPosts( $user_id, $group_id, $take = 3, $skip = 0,$type,$object,$is_api_request = false ) {

        $data = $this->getPosts($user_id,$take,$skip,$type,$object,$group_id,null,$is_api_request);

		return $data;
	}

	public function parsePosts( $posts, $user_id ) {
		$data = [ ];
		foreach ( $posts as $key => $value ) {

			$data[] = $this->makePost($value,$user_id);
		}

		return $data;
	}
	public function makePost($value,$user_id,$popup = false)
	{
		$temp = [ ];

		$temp += $this->getPostMeta( $value, $user_id,$popup );

		$temp += $this->getComments( $user_id, $value->action_id, 1000 );

		$temp += $this->getLikesMetaInfo( $value, $user_id );

		return $temp;
	}

	public function getPostMeta( $value, $user_id,$popup = false ) {

		$user = \Cache::get('_user_'.$value->subject_id,function () use (&$value){
		    $user = User::where( 'id', $value->subject_id )->first();
            \Cache::forever('_user_'.$value->subject_id,$user);
            return $user;
        });

		if ( empty( $user->id ) ) {
			return [ ];
		}

		$temp['post_id']               = $value->action_id;

		if($user->userable_type == 'App\Brand') {
			$user_brand = UserBrand::where('id',$user->userable_id)->select(['brand_name'])->first();
			$temp['subject_name'] = isset($user_brand->brand_name) ? $user_brand->brand_name : '';
			$temp['subject_gender'] = '';
		}else{
			$temp['subject_name'] = $user->first_name . ' ' . $user->last_name;
			$consumer = $user->userable;
			$temp['subject_gender'] = @$consumer->gender;
		}

        $temp['actual_id']             = $user->userable_id;
		$temp['subject_href']          = $user->username;
		$temp['subject_type']          = $user->userable_type;

		$temp['object_id']   = $value->object_id;
		$temp['object_type'] = $value->object_type;
		$temp['object_display_name'] = $this->getObjectDisplayName($value);

		if($value->type == 'status') {
			$temp['object_id']   = $value->action_id;
			$temp['object_type'] = 'activity_action';
		}

		$temp['post_attachment_count'] = $value->attachment_count;
		$temp['post_comment_count']    = $value->comment_count;
		$temp['post_like_count']       = $value->like_count;
		$temp['post_dislike_count']    = $value->dislike_count;
		$temp['post_created_at']       = $value->created_at;
		$temp['post_updated_at']       = $value->updated_at;
		$temp['post_params']           = !empty($value->params) ? $value->params : '';
		$temp['post_body']             = !empty($value->body) ? $value->body : '';
		$temp['post_type']             = $value->type;
        $temp['post_owner_type']       = '';
		$temp['is_owner']			   = $value->subject_id == $this->user_id ? 1 : 0;
		$temp['is_object_owner']       = $this->isObjectOwner($value,$value->object_type,$value->object_id,$this->user_id);
		$temp['post_header']      		= $this->makePostHeader($value);
		$temp['anonymousUser']	   		= true;
		if(!empty($this->user_id)){
			$temp['anonymousUser'] = false;
		}

		$temp['post_liked'] = ActivityLike::where( 'resource_id', $value->action_id )
		                                  ->where( 'poster_id', $user_id )
		                                  ->where( 'poster_type', 'user' )
		                                  ->count();

		$temp['post_fav'] = ActivityFavourite::where( 'resource_id', $value->action_id )
		                                     ->where( 'poster_id', $user_id )
		                                     ->where( 'poster_type', 'user' )
		                                     ->count();

		$temp['post_disliked'] = ActivityDislike::where( 'resource_id', $value->action_id )
		                                        ->where( 'poster_id', $user_id )
		                                        ->where( 'poster_type', 'user' )
		                                        ->count();
		$temp['post_reported'] = Report::where('user_id',$this->user_id)
										->where('action_id',$value->action_id)
										->count();

		$photo_id = $user->photo_id;

        $temp['subject_photo_path'] = Kinnect2::getPhotoUrl($photo_id, $user->id, 'user', 'thumb_icon');

        if($value->type == 'share'){
            $temp += $this->getSharedActivityInfo($value);
        }
		if($value->type == 'cover_photo_update' || $value->type == 'profile_update_photo'){
			$temp['post_body'] = '';
		}
		if ( $value->object_type == 'cover_photo' || $value->type == 'cover_photo_update') {
			$temp += $this->getCoverPhotoMetaInfo( $value->object_id);
		}elseif ( $value->object_type == 'video' ) {
			$temp += $this->getVideoMetaInfo( $value );
		} elseif ( $value->type == 'album' ) {
			$my_temp  = $value->params;
			$album_id = isset( $my_temp['album_id'] ) ? $my_temp['album_id'] : 0;
			$temp += $this->getPhotoMetaInfo( $value,$popup );
			$temp += $this->getAlbumInfo( $album_id );
		}else if($value->object_type == 'event') {
            $temp += $this->getEventMetaInfo($value);
        } elseif ( $value->object_type == 'album_photo' ) {
			$temp += $this->getPhotoMetaInfo( $value,$popup );
		} elseif ( $value->object_type == 'battle' ) {
			$temp += $this->getBattleMetaInfo( $value,$user_id );
		} elseif ( $value->object_type == 'poll' ) {
			$temp += $this->getPollMetaInfo( $value,$user_id );
		} elseif ( $value->object_type == 'group' ) {
			$temp += $this->getGroupMetaInfo( $value );
		} elseif ( $value->type == 'friends' ) {
			$temp += $this->getFriendMetaInfo( $value );
			$temp['post_body'] = "is now friend with ";
		} elseif ( $value->object_type == 'brand' ) {
			$temp += $this->getBrandMetaInfo( $value );
			$temp['post_body'] = "is now following ";
		} elseif ( $value->object_type == 'audio' ) {
			$temp += $this->getAudioMetaInfo( $value );
		} elseif ( $value->object_type == 'link' ) {
			$temp += $this->getLinkMetaInfo( $value );
		}elseif ( $value->object_type == 'product' ) {
			$temp += $this->getProductMetaInfo( $value );
		}

		if($value->type == 'join' || $value->type == 'group_join' || $value->type == 'group_create'){
			$temp['is_group_post']         = 1;
			$temp['group'] = $this->getGroupByID($value->object_id);
			$temp['post_header_group_prefix'] = '';
		}elseif($value->target_type == 'group'){
			$temp['is_group_post']         = 1;
			$temp['group'] = $this->getGroupByID($value->target_id);
			$temp['post_header_group_prefix'] = 'in';
		}else{
			$temp['is_group_post']         = 0;
		}

        if(!isset($temp['object_view_permission']))
        {
            $temp['object_view_permission'] = TRUE;
        }
        if(!isset($temp['object_comment_permission']))
        {
            $temp['object_comment_permission'] = TRUE;
        }
		if(!isset($temp['post_share_count'])){
			$temp['post_share_count']      = $value->share_count;
		}

		return $temp;
	}
	protected function makePostHeader(&$value){
		$str = '';
		if($value->object_type.'_new' == $value->type || $value->type == 'album_photo' || ($value->type == 'album' && $value->object_type == 'album_photo')){
			$str = 'added';
			if($value->attachment_count > 1){
				$str .= ' '.$value->attachment_count.' new';
			}else{
				$str .= ' a new ';
			}
		}elseif($value->object_type.'_create' == $value->type){
			$str = 'created a new';
		}elseif($value->object_type.'_share' == $value->type || $value->type == 'share' || $value->type == 'link'){
			$str = 'shared';
		}elseif($value->type == 'group_join' || $value->type == 'join'){
			$str = 'joined the';
		}
		if($value->type == 'profile_update_photo'){
			$str .= ' updated profile ';
		}elseif($value->type == 'cover_photo_update'){
			$str .= ' updated cover ';
		}
		if($this->is_api){
			return trim($str);
		}
		return $str;
	}
	public function isObjectOwner(&$value,$object_type,$object_id,$user_id){
		if(($value->type == 'share' || $value->type == 'group_share' || $value->type == 'event_share') && ($value->subject_id == $user_id)){
            return 1;
        }elseif($object_type == 'user' || $object_type == 'brand' | $object_type == 'activity_action'){
			if($object_id == $user_id && $value->subject_id == $user_id){
				return 1;
			}
			return 0;
		}
		$query = $this->buildObjectQuery($object_type,$object_id);
		if($object_type == 'link' || $object_type == 'video' || $object_type == 'album' || $object_type == 'album_photo' || $object_type == 'product'){
			return $query->where('owner_id',$user_id)->count();
		}elseif($object_type == 'poll' || $object_type == 'battle' || $object_type == 'event' || $object_type == 'cover_photo' ||$object_type == 'audio'){
			return $query->where('user_id',$user_id)->count();
		}elseif($object_type == 'group'){
			return $query->where('creator_id',$user_id)->count();
		}
	}
	public function getGroupByID($group_id){
		$group = Group::where( 'id', $group_id )->first();

		$temp['id'] = @$group->id;
		$temp['name']         = @$group->title;

		return $temp;
	}
	public function getObjectDisplayName(&$value){
		$str = $value->object_type;
		if($value->object_type == 'activity_action'){
			$str = 'status';
		}elseif($value->object_type == 'album_photo' || $value->type == 'profile_update_photo' || $value->type == 'cover_photo_update'){
			$str = 'photo';
		}elseif($value->object_type == 'cover_photo'){
			$str = 'cover photo';
		}elseif($value->object_type == 'group_join'){
			$str = 'group';
		}elseif($value->type == 'status' && $value->target_type == 'group'){
			$str = 'posted';
		}elseif($value->type == 'status' || $value->type == 'friends' || $value->type == 'follow'){
			$str = '';
		}
		if($value->attachment_count > 1){
			$str = $str.'s';
		}
		return $str;
	}
    public function getSharedActivityInfo($value)
    {
        $temp = [];

	    $object = $this->getObject($value->object_type,$value->object_id);

	    $user_id = $this->getObjectOwnerID($value->object_type,$object);

	    if($value->object_type == 'brand'){
		    $owner = @$object->id;
	    }else {
	        $owner = \Cache::get('_user_'.$user_id,function() use ($user_id){
	            $user = User::where( 'id', $user_id )->first();
                \Cache::forever('_user_'.$user_id,$user);
                return $user;
            });
	    }

		if($user_id == $value->subject_id){
			if(@$owner->userable_type == 'App\Brand') {
				$temp['subject_owner_name'] = 'his';
			}elseif(@$owner->userable_type == 'App\Consumer'){
				$consumer = Consumer::where('id',$owner->userable_id)->select(['gender'])->first();
				$temp['subject_owner_name'] = isset($consumer->gender) && $consumer->gender == 2 ? 'her' : 'his';
			}
			$temp['subject_owner_href'] = 'owner_self';
		} elseif(@$owner->userable_type == 'App\Brand') {
            $owner_brand = UserBrand::where('id',@$owner->userable_id)->select(['brand_name'])->first();
            $temp['subject_owner_name'] = isset($owner_brand->brand_name) ? $owner_brand->brand_name : $owner->displayname;
			$temp['subject_owner_href']     = @$owner->username;
        }else{
            $temp['subject_owner_name'] = @$owner->first_name . ' ' . @$owner->last_name;
			$temp['subject_owner_href']     = @$owner->username;
        }

	    $temp['post_owner_body'] = '';
	    if($value->object_type == 'activity_action'){
		    $temp['post_owner_body'] = @$object->body;
		    $temp['post_share_count'] = @$object->share_count;
	    }

        return $temp;
    }
    protected function getObjectOwnerID($object_type,&$object){

        if($object_type == 'video' || $object_type == 'link' || $object_type == 'album_photo' || $object_type == 'product'){
            $user_id = @$object->owner_id;
        }elseif($object_type == 'poll' || $object_type == 'battle' || $object_type == 'event'){
            $user_id = @$object->user_id;
        }elseif($object_type == 'group'){
            $user_id = @$object->creator_id;
        }elseif($object_type == 'activity_action'){
            $user_id = @$object->subject_id;
        }elseif($object_type == 'audio' || $object_type == 'cover_photo'){
            $user_id = @$object->user_id;
        }
        return $user_id;
    }
	public function getCoverPhotoMetaInfo( $file_id ) {

		$file    = StorageFile::where( 'file_id', $file_id )
								->select(['mime_type','storage_path','share_count'])
								->first();
		$temp['post_share_count'] = @$file->share_count;
		if ( ! empty( $file->storage_path ) ) {
			$temp['object_photo_path'][] = \Config::get( 'constants_activity.PHOTO_URL' ) . @$file->storage_path . '?type=' . urlencode( @$file->mime_type );
		} else {
			$temp['object_photo_path'][] = '';
		}

		return $temp;
	}

	public function getVideoMetaInfo( $value ) {
		$video_id                   = $value->object_id;
		$video                      = \Cache::get('_video_'.$video_id,function () use ($video_id){
            $video = Video::where( 'video_id', $video_id )->first();
            \Cache::put('_video_'.$video_id,$video,3600);
            return $video;
        });
		$temp['object_title']       = @$video->title;
		$temp['object_description'] = @$video->description;
		$temp['object_code']        = @$video->code;
		$temp['post_share_count'] = @$video->share_count;

		if ( ! empty( $video->album_id ) ) {
			$temp += $this->getAlbumInfo( $video->album_id );
		}

		$file_id = @$video->file_id;
		$file    = StorageFile::where( 'file_id', $file_id )->first();
		$path    = isset( $file->storage_path ) ? $file->storage_path : null;

		if ( ! empty( $path ) ) {
			$temp['object_path'] = \Config::get( 'constants_activity.VIDEO_URL_MOD' ) . $path;
		} else {
			$temp['object_path'] = '';
		}

		$file                      = StorageFile::where( 'type', 'video_thumb' )
		                                        ->where('parent_type','video')
			                                    ->where('parent_id',$video_id)
												->select(['storage_path','mime_type'])
												->first();
		if(!empty($file->storage_path) && $this->file_exists($file->storage_path)) {
			$temp['object_photo_path'] = \Config::get( 'constants_activity.VIDEO_THUMB_URL' ) . urlencode( base64_encode( @$file->storage_path ) ) . '?type=' . urlencode( @$file->mime_type );
		}else{
			$temp['object_photo_path'] = FALSE;
		}

		return $temp;
	}

	public function getProductMetaInfo( $value ) {
		$product_id                   = $value->object_id;
		$product                      = StoreProduct::where( 'id', $product_id )->first();
		$temp['object_title']       = @$product->title;
		$temp['object_description'] = @$product->description;
		$temp['object_price']        = @$product->price;
		if(!empty($product->discount)){
			$temp['object_discount_price'] = @$product->price - (@$product->price * @$product->discount)/100;
		}else{
			$temp['object_discount_price'] = @$product->price;
		}
		//$temp['post_share_count'] = @$video->share_count;
		if (!empty($product_id ) ) {
			$temp += $this->getProductPhotos(@$product_id);
			$temp += $this->getProductReview(@$product_id);
		}
		return $temp;
	}

	public function getProductPhotos($product_id){
		$storeStorageObj = new StoreStorageFiles();
		$files = $storeStorageObj->where( 'parent_id', $product_id )->whereNull( 'type')->where( 'parent_type', "album_photo" )->orderBy('file_id','ASC')->get();
		$temp = [];
		$count = 0;
		foreach($files as $file){
			$file->object_photo_path = \Config::get( 'constants_activity.PHOTO_URL' ) . @$file->storage_path . '?type=' . urlencode( @$file->mime_type );
			$temp["object_photos"][$count] = $file;
			$count++;
		}
		return $temp;
	}

	public function getProductReview($product_id){
		$productReviewObj = new StoreProductReview();
		$temp = [];
		$avgRating = $productReviewObj->where("product_id", $product_id)->avg("rating");
		$temp["product_rating"] = $avgRating;  //($avgRating) ? $avgRating : 0;
		$temp["product_rating_count"] = $productReviewObj->where("product_id", $product_id)->count("rating");
		return $temp;
	}


	public function getAlbumInfo( $album_id ) {
		$albumObj = new Album();

		$album = $albumObj->where( 'album_id', $album_id )->first();

		$temp = [ ];
		$temp['album_id']    = @$album->id;
		$temp['album_title'] = @$album->title;
		$temp['description'] = @$album->description;

		return $temp;
	}

	public function getPhotoMetaInfo( &$value,$popup = false ) {
		$photo_id = $value->object_id;
		$post_created_at = date('Y-m-d',strtotime($value->created_at));
		$photo = AlbumPhoto::where( 'photo_id', $photo_id )
							->whereDate('created_at','<=', $post_created_at)
		                   	->select( [ 'photo_id','file_id', 'parent_id', 'album_id','share_count','created_at' ] )
		                   	->first();
		if(!empty($photo->album_id)){
			$album = Album::where('album_id',$photo->album_id)
						->first();
			if(@$album->type != 'profile') {
				$temp['is_album_photo'] = 1;
				$temp['album_title'] = $album->title;
				$temp['album_id'] = $album->album_id;
			}
		}

		$temp['post_share_count'] = @$photo->share_count;
		$file_id = isset( $photo->file_id ) ? $photo->file_id : null;

		$type = $popup == 1 ? 'popup_photo' : 'time_line_thumb';
		$file    = StorageFile::where( 'type', $type )
                                ->where('parent_type','album_photo')
                                ->where('parent_id',@$photo->photo_id)
                                ->select(['storage_path','mime_type'])
                                ->first();
        if(empty($file)){
            $file    = StorageFile::where('file_id',$file_id)
                ->select(['storage_path'])
                ->first();
        }

		$path    = isset( $file->storage_path ) ? $file->storage_path : null;

		if ( ! empty( $path ) ) {
			$temp['object_photo_path'][] = \Config::get( 'constants_activity.PHOTO_URL' ) . $path . '?type=' . urlencode( $file->mime_type );
		} else {
			$temp['object_photo_path'][] = null;
		}
		if($photo_id > 0) {
			$childs = AlbumPhoto::where( 'parent_id', @$photo->photo_id )
			                    ->select( [ 'file_id','photo_id' ] )
			                    ->get();
			if ( ! empty( $childs ) ) {
				foreach ( $childs as $key => $value ) {

                    $file = StorageFile::where( 'type', $type )
                        ->where('parent_type','album_photo')
                        ->where('parent_id',@$value->photo_id)
                        ->select(['storage_path','file_id','mime_type'])
                        ->first();
                    if(empty($file->file_id)) {
                        $file = StorageFile::where('file_id', @$value->file_id)->first();
                    }

                    $path    = isset( $file->storage_path ) ? $file->storage_path : null;

					if ( ! empty( $path ) ) {
						$temp['object_photo_path'][] = \Config::get( 'constants_activity.PHOTO_URL' ) . $path . '?type=' . urlencode( $file->mime_type );
					} else {
						$temp['object_photo_path'][] = '';
					}
				}
			}
		}

		return $temp;
	}

	public function getBattleMetaInfo( $value,$user_id ) {
		$battle                       = Battle::where( 'id', $value->object_id )->first();

		$temp['object_name']          = @$battle->title;
		$temp['object_description']   = @$battle->description;
		$temp['object_is_closed']     = @$battle->is_closed;
		$temp['object_creation_date'] = @$battle->created_at;
		$temp['object_view_count']    = @$battle->view_count;
		$temp['object_comment_count'] = @$battle->comment_count;
		$temp['object_vote_count']    = @$battle->vote_count;
		$temp['post_share_count'] 	  = @$battle->share_count;
		$temp['object_is_voted'] 	  = BattleVote::where('battle_id',@$battle->id)->where('user_id',$this->user_id)->count();

        $privacyObj = new PrivacyRepository();

        $temp['object_view_permission'] 	= $privacyObj->is_allowed($value->object_id,$value->object_type,'view',$this->user_id,@$battle->user_id);
        $temp['object_comment_permission'] 	= $privacyObj->is_allowed($value->object_id,$value->object_type,'comment',$this->user_id,@$battle->user_id);

		$options = BattleOption::where('battle_id',@$battle->id)->select(['*','id AS option_id'])->get();

		foreach ($options as $key => $value) {
			$temp['object_option_'.$key] = UserBrand::where('id',$value->brand_id)->first();
            $temp['object_option_'.$key]['option_id'] = $value->id;
            $temp['object_option_'.$key]['is_voted'] = BattleVote::where('battle_id',$value->battle_id)->where('user_id',$this->user_id)->count();
			$temp['object_option_'.$key]['vote_count'] = @$value->votes;
			$temp['object_option_'.$key]['vote_percentage'] = !empty($value->votes) && !empty($battle->vote_count) ? round(($value->votes / $battle->vote_count) * 100) : 0;

			$photo_id = User::where('userable_id',$value->brand_id)
                            ->where('userable_type','App\Brand')
                            ->select(['cover_photo_id'])
                            ->first();
			$cover_path = $this->getCoverPhoto(@$photo_id->cover_photo_id);
			$temp['object_option_'.$key]['photo_path'] = $cover_path ? $cover_path : asset('/local/public/assets/images/defaults/default_brand_cover.jpg');
		}

		return $temp;
	}
	public function getCoverPhoto($file_id){
		if(empty($file_id))
		{
			return FALSE;
		}
		$file = StorageFile::where('file_id',$file_id)
		                   ->select(['storage_path','mime_type'])
		                   ->first();

		if(!empty($file->storage_path) && $this->file_exists('photos/'.$file->storage_path))
		{
			return \Config::get( 'constants_activity.PHOTO_URL' ).$file->storage_path.'?type=' . urlencode( $file->mime_type );
		}
		return FAlSE;
	}
	protected function file_exists($path){
		$sm = new StorageManager();
		return $sm->pathExists($path);
	}
	public function getPhoto($photo_id)
	{
		if(empty($photo_id))
		{
			return FALSE;
		}
		$photo = AlbumPhoto::where('photo_id',$photo_id)
								->select(['file_id'])
								->first();
		if(empty($photo->file_id))
		{
			return FALSE;
		}
		$file = StorageFile::where('file_id',$photo->file_id)
							->select(['storage_path','mime_type'])
							->first();

		if(!empty($file->storage_path))
		{
			return \Config::get( 'constants_activity.PHOTO_URL' ).$file->storage_path.'?type=' . urlencode( $file->mime_type );
		}
		return FAlSE;

	}

	public function getPollMetaInfo( $value,$user_id ) {
		$poll = Poll::where( 'id', $value->object_id )->first();
		$temp = [];

		$temp['object_name']          = @$poll->title;
		$temp['object_is_closed']     = @$poll->is_closed;
		$temp['object_description']   = @$poll->description;
		$temp['object_creation_date'] = @$poll->created_at;
		$temp['object_view_count']    = @$poll->view_count;
		$temp['object_comment_count'] = @$poll->comment_count;
		$temp['object_vote_count']    = @$poll->vote_count;
		$temp['post_share_count'] = @$poll->share_count;

		$privacyObj = new PrivacyRepository();

		$temp['object_view_permission'] = $privacyObj->is_allowed($value->object_id,$value->object_type,'view',$this->user_id,@$poll->user_id);
		$temp['object_comment_permission'] = $privacyObj->is_allowed($value->object_id,$value->object_type,'comment',$this->user_id,@$poll->user_id);

		$temp['object_is_voted'] = PollVote::where('user_id',$this->user_id)
												->where('poll_id',@$poll->id)
												->count();

		$options = PollOption::where('poll_id',@$poll->id)
                                    ->orderBy('id','DESC')
                                    ->get()
                                    ->toArray();

        if(!empty($options)){
            foreach ($options as $index => $option) {
                $vote_percentage = !empty($poll->vote_count) && !empty($option['votes']) ? round(($option['votes'] / $poll->vote_count) * 100) : 0;
                $options[$index]['vote_percentage'] = $vote_percentage;
				$file =	StorageFile::where('parent_type','poll_option')
								->where('parent_id',@$option['id'])
								->where('type','poll_thumb')
								->select(['storage_path','extension','mime_type'])
								->first();
				if(!empty($file->storage_path)) {
					$options[$index]['photo_path'] = \Config::get('constants_activity.PHOTO_URL') . $file->storage_path . '?type=' . urlencode($file->mime_type).'&img=poll_thumb';
				}else{
					$options[$index]['photo_path'] = url('/local/public/assets/images/polloptions.png');
				}
            }
        }

		$temp['object_options'] = @$options;

		return $temp;

	}

	public function getGroupMetaInfo( $value ) {
		if ( $value->object_type == 'group' ) {
			$group_id = $value->object_id;
		} else {
			$group_id = $value->target_id;
		}
		$group = Group::where( 'id', $group_id )->first();

        $temp['object_id'] = @$group->id;
		$temp['object_name']         = @$group->title;
		$temp['object_description']  = @$group->description;
		$temp['object_member_count'] = @$group->member_count;
		$temp['object_view_count']   = @$group->view_count;
		$temp['post_share_count'] = @$group->share_count;
		$temp['object_photo_path'] = Kinnect2::getPhotoUrl(@$group->photo_id,null,'group');
		return $temp;

	}

	public function getFriendMetaInfo( $value ) {
		$friend_with         = \Cache::get('_user_'.$value->object_id,function() use (&$value) {
            $user = User::where( 'id', $value->object_id )->first();
            \Cache::forever('_user_'.$value->object_id,$user);
            return $user;
        });
		$temp['object_name'] = @$friend_with->first_name . ' ' . @$friend_with->last_name;
		$temp['object_href'] = @$friend_with->username;

		$photo_id = @$friend_with->photo_id;

		$temp['object_photo_path'] = Kinnect2::getPhotoUrl($photo_id, $value->object_id, 'user', 'thumb_icon');

		return $temp;
	}

	public function getBrandMetaInfo( $value ) {
		$user               = \Cache::get('_user_'.$value->object_id,function () use (&$value){
		    $user = User::where( 'id', $value->object_id )->first();
            \Cache::forever('_user_'.$value->object_id,$user);
            return $user;
        });
		$temp['object_name'] = '';
		$temp['object_href'] ='';
		if($user){
			$brand              = \Cache::get('_brand_'.$user->userable_id,function () use (&$user){
			    $userBrand = UserBrand::where('id',$user->userable_id)->select(['brand_name'])->first();
                \Cache::forever('_brand_'.$user->userable_id,$userBrand);
                return $userBrand;
            });
			$temp['object_name'] = @$brand->brand_name;
			$temp['object_href'] = @$user->username;
		}

		return $temp;
	}

	public function getAudioMetaInfo( $value ) {
		$file_id = $value->object_id;

		$file = StorageFile::where( 'file_id', $file_id )->first();
		$path = isset( $file->storage_path ) ? $file->storage_path : null;
        $temp['object_title'] = $file->name;
		$temp['post_share_count'] = @$file->share_count;

		if ( ! empty( $path ) ) {
			$temp['object_path'] = \Config::get( 'constants_activity.AUDIO_URL_MOD' ) . $path;
		} else {
			$temp['object_path'] = '';
		}

		return $temp;
	}

	public function getLinkMetaInfo( $value,$link_id = null ) {
        if(empty($link_id)) {
            $link_id = $value->object_id;
        }

		$link = Link::where( 'link_id', $link_id )->first();

		$temp['object_uri']         = @$link->uri;
		$temp['object_name']        = @$link->title;
		$temp['object_description'] = @$link->description;
		$temp['post_share_count'] = @$link->share_count;

		if(preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", @$link->uri, $matches)){
			$temp['object_uri_id'] = @$matches[0];
			$temp['object_uri_type'] = 'youtube';
		}elseif(preg_match("%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im", @$link->uri, $regs)) {
			$temp['object_uri_id'] = @$regs[3];
			$temp['object_uri_type'] = 'vimeo';
		}

		if ( ! empty( $link->photo_id ) ) {
			$file = StorageFile::where( 'file_id', $link->photo_id )->first();
			$path = isset( $file->storage_path ) ? $file->storage_path : null;

			if ( ! empty( $path ) ) {
				$temp['object_photo_path'] = \Config::get( 'constants_activity.PHOTO_URL' ) . $path . '?type=' . urlencode( $file->mime_type );
			} else {
				$temp['object_photo_path'] = '';
			}
		}

		$temp['link_type'] = $this->getLinkType($temp['object_uri']);
//		if($temp['link_type'] == "youtube"){
//			$temp['link_vid'] = $this->get_youtube_vid($temp['object_uri']);
//		}elseif( $temp['link_type'] == "vimeo"){
//			$temp['link_vid'] = $this->get_vimeo_vid($temp['object_uri']);
//		}

		return $temp;
	}

    public function getEventMetaInfo($value)
    {
        $event = GroupEvent::where('id',$value->object_id)->first();

        $temp['object_name'] = @$event->title;
        $temp['object_description'] = @$event->description;
        $temp['object_id'] = @$event->id;
        $temp['object_start_time'] = @$event->starttime;
        $temp['object_endtime'] = @$event->endtime;
        $temp['object_view_count'] = @$event->view_count;
        $temp['object_member_count'] = @$event->member_count;
		$temp['post_share_count'] = @$event->share_count;
	    $temp['object_photo_path'] = Kinnect2::getPhotoUrl(@$event->photo_id);
        if(isset($event)){
            $privacyObj = new PrivacyRepository();

            $temp['object_view_permission'] = $privacyObj->is_allowed($value->object_id,$value->object_type,'view',$this->user_id,$event->user_id);
            $temp['object_comment_permission'] = $privacyObj->is_allowed($value->object_id,$value->object_type,'comment',$this->user_id,$event->user_id);
        }
        return $temp;
    }

	public function getComments( $user_id, $activity_action_id, $take = 2, $skip = 0 ) {
		if ( empty( $activity_action_id ) ) {
			return false;
		}

		$data['comment_take']    = $take;
		$data['comment_skipped'] = $skip;
		$comments                = ActivityComment::where( 'resource_id', $activity_action_id )
		                                          ->where( 'parent_comment_id', 0 )
		                                          ->take( $take )
		                                          ->skip( $skip )
		                                          ->get();

		foreach ( $comments as $key => $value ) {

			$comments[ $key ] = $this->getCommentMeta( $value, $user_id );
		}

		$count = ActivityComment::where( 'resource_id', $activity_action_id )->where('parent_comment_id',0)->count();

		$has_next_page = 0;
		if ( $count > ( $skip + $take ) ) {
			$has_next_page = 1;
		}

		$data['has_next_page'] = $has_next_page;
		$data['comments']      = $comments;

		$data['comments_count'] = count( $comments );

		return $data;

	}

	public function getCommentMeta( &$commentObj, $user_id ) {
		$poster = \Cache::get('_user_'.$commentObj->poster_id,function () use (&$commentObj){
		    $user = User::where( 'id', $commentObj->poster_id )->first();
            \Cache::forever('_user_'.$commentObj->poster_id,$user);
            return $user;
        });

        if(@$poster->userable_type == 'App\Brand') {
            $user_brand = UserBrand::where('id',$poster->userable_id)->select(['brand_name'])->first();
            $commentObj->poster_name = isset($user_brand->brand_name) ? $user_brand->brand_name : '';
	        $commentObj->subject_gender = '';
        }else{
            $commentObj->poster_name = @$poster->first_name . ' ' . @$poster->last_name;
	        $consumer = @$poster->userable;
	        $commentObj->subject_gender = @$consumer->gender;
        }

		$commentObj->poster_href = url('profile').'/'.@$poster->username;
		$commentObj->poster_type = @$poster->userable_type;
		$commentObj->reply_count = ActivityComment::where('parent_comment_id',@$commentObj->comment_id)->count();

		$commentObj->comment_liked = Like::where( 'resource_type', 'comment' )
		                                 ->where( 'resource_id', @$commentObj->comment_id )
		                                 ->where( 'poster_id', $user_id )
		                                 ->count();
		$commentObj->isAuthenticatedUser = empty($this->user_id) ? false : true;

		$photo_id = @$poster->photo_id;

		$commentObj->poster_photo_path = Kinnect2::getPhotoUrl($photo_id, @$commentObj->poster_id, 'user', 'thumb_icon');
        if(!empty($commentObj->attachment_id)) {
            if($commentObj->attachment_type == 'image') {
                $file = StorageFile::where('parent_id', $commentObj->comment_id)
                    ->where('type', 'attachment_thumb')
                    ->where('parent_type', 'activity_comment')
                    ->first();
                if (!empty($file->storage_path)) {
                    $commentObj->attachment_path = \Config::get('constants_activity.ATTACHMENT_THUMB') . @$file->storage_path . '?type=' . urlencode($file->mime_type);
                }
            }elseif ($commentObj->attachment_type == 'link'){

                $commentObj->link = $this->getLinkMetaInfo(Null,$commentObj->attachment_id);
            }
        }


		return $commentObj;
	}

	public function getLikesMetaInfo( $value, $user_id ) {
		$members   = $this->getMembers( $user_id );
        $members = $members->push($this->user_id);
		$action_id = $value->action_id;

		$users = ActivityLike::whereIn( 'poster_id', $members )
		                     ->where( 'resource_id', $action_id )
		                     ->take( 4 )
		                     ->get()
		                     ->lists( 'poster_id', 'poster_id' );

		$likes['likes']['friends'] = [ ];

		$counter = 0;
		if(!empty($users)) {
			foreach ( $users as $key => $id ) {
				$user = \Cache::get('_user_'.$id,function () use ($id){
				    $user = User::where( 'id', $id )->first();
                    \Cache::forever('_user_'.$id,$user);
                    return $user;
                });

				if(@$user->userable_type == 'App\Brand'){
					$brand = $user->userable;
					$temp['name'] = @$brand->brand_name;
				}else {
					$temp['name'] = @$user->first_name . ' ' . @$user->last_name;
				}
				$temp['href']                = @$user->username;
				$likes['likes']['friends'][] = $temp;
				$counter ++;
			}
		}

		$likes['likes']['others'] = $value->like_count - $counter;
		$likes['likes']['like_count'] = !empty($value->like_count) ? $value->like_count : 0;
		$likes['likes']['dislike_count'] = !empty($value->dislike_count) ? $value->dislike_count : 0;
		return $likes;

	}

	public function getMembers( $user_id ) {
		return UserMembership::where( 'resource_id', $user_id )
		                     ->where( 'active', 1 )
		                     ->where( 'resource_approved', 1 )
		                     ->where( 'user_approved', 1 )
		                     ->lists( 'user_id', 'user_id' );
	}

	public function getPosts( $user_id, $take = 5, $skip = 0, $type = 'all', $object = null,$target_id = null,$last_id = null,$is_api_request = false,$hashTag = Null ) {

        if($type == 'self'){
            $subject_ids = [$user_id => $user_id];
        } elseif ( $type == 'brands' ) {
			$subject_ids = $this->getBrands( $user_id );
		} else {

			$brands      = $this->getBrands( $user_id );
			$members     = $this->getMembers( $user_id );
			$subject_ids = $brands->merge( $members )->push( $user_id );
		}

		$group_ids = $this->getGroups($user_id);

        $object = !empty($object) ? strtolower($object) : NULL;

		if ( $object == 'audio' ) {
			$object_type = 'audio';
		} elseif ( $object == 'videos' ) {
			$object_type = 'video';
		} elseif ( $object == 'battles' ) {
			$object_type = 'battle';
		} elseif ( $object == 'polls' ) {
			$object_type = 'poll';
		} elseif ( $object == 'groups' ) {
            $target_type = 'group';
		}if ( $object == 'product' ) {
			$object_type = 'product';
		}

		$query = ActivityAction::whereIn( 'subject_id', $subject_ids )
								->where('type','<>','friends')
								->where('type','<>','follow')
								->where('is_flagged',0)
								->orderBy( 'action_id', 'DESC' );

		if(!empty($hashTag)){
			$action_ids = Hashtag::where('tag','like',"$hashTag")
									->where('parent_type','activity_action')
									->take($take)
									->skip($skip)
									->lists('parent_id','parent_id');
		}

		if($is_api_request){
			$query->where('object_type','<>','product');
		}

		if(!empty($action_ids)){
			$query->whereIn('action_id',$action_ids);
		}

		$tmp['subject_ids'] = $subject_ids;
		$tmp['group_ids'] = $group_ids;

		if ( ! empty( $object_type ) ) {
			$query->where( 'object_type', $object_type );
		}

		if ( ! empty( $target_type ) ) {
			$query->where( 'target_type', $target_type );
			$query->whereIn('target_id',$tmp['group_ids']);
		}elseif(empty($target_id)){
			$query->where(function($query) use ($tmp){
				$query->whereNull('target_type');
				$query->orWhere('target_type','=','');
				if(!empty($tmp['group_ids'])) {
					$query->orWhereIn('target_id',$tmp['group_ids'] );
				}
			});
		}

        if(!empty($target_id)){
            $query->where('target_id',$target_id);
        }

        if(!empty($last_id)){
            $query->where('action_id','>',$last_id);
        }else{
			$query->take( $take );
            $query->skip( $skip );
		}

		$count_query = ActivityAction::whereIn( 'subject_id', $subject_ids )
										->where('type','<>','friends')
										->where('type','<>','follow');

		if ( ! empty( $object_type ) ) {
			$count_query->where( 'object_type', $object_type );
		}

		if(!empty($action_ids)){
			$count_query->whereIn('action_id',$action_ids);
		}

		if($is_api_request){
			$count_query->where('object_type','<>','product');
		}

		if ( ! empty( $target_type ) ) {
			$count_query->where( 'target_type', $target_type );
			$count_query->whereIn('target_id',$tmp['group_ids']);
		}elseif(empty($target_id)){
			$count_query->where(function($count_query) use ($tmp){
				$count_query->whereNull('target_type');
				$count_query->orWhere('target_type','=','');
				if(!empty($tmp['group_ids'])) {
					$count_query->orWhereIn('target_id',$tmp['group_ids'] );
				}
			});
		}

        if(!empty($target_id)){
            $count_query->where('target_id',$target_id);
        }

        if(!empty($last_id)){
            $count_query->where('action_id','>',$last_id);
        }

		$posts = $query->get();

		$count = $count_query->count();

		$has_next_page = 0;
		if ( $count > ( $skip + $take ) ) {
			$has_next_page = 1;
		}

		$data = [
			'post_take'     => $take,
			'post_skip'     => $skip,
			'base_url'      => url(),
			'post_count'    => count( $posts ),
			'has_next_page' => $has_next_page
		];


		$data['posts'] = $this->parsePosts( $posts, $user_id );

		return $data;

	}

	public function getBrands( $user_id ) {
		return BrandMembership::where( 'user_id', $user_id )
		                      ->where( 'active', 1 )
		                      ->lists( 'brand_id', 'brand_id' );
	}
	public function getGroups($user_id){
		return GroupMembership::where('user_id',$user_id)
								->where('active',1)
								->where('group_owner_approved',1)
								->where('user_approved',1)
								->lists('group_id','group_id');
	}
	public function getUserPosts( $user_id, $take = 3, $skip = 0,$object,$is_api_request = false,$hashTag = null ) {
		return $this->getPosts($user_id,$take,$skip,'self',$object,null,null,$is_api_request,$hashTag);
	}

	public function getLatestPosts( $user_id, $date_time ) {
		$date_time = date( 'Y-m-d H:i:s', $date_time );
		$members   = $this->getMembers( $user_id );
		$brands    = $this->getBrands( $user_id );

		$subject_ids = $brands->merge( $members )->push( $user_id );

		$posts = ActivityAction::whereIn( 'subject_id', $subject_ids )
		                       ->where( 'created_at', '>', $date_time )
		                       ->orderBy( 'action_id', 'DESC' )
		                       ->get();

		$data['posts'] = $this->parsePosts( $posts, $user_id );

		return response()->json( $data );
	}

	public function getTagMetaInfo( $value ) {
		$tagged              = \Cache::get('_user_'.$value->object_id,function () use (&$value){
		    $user = User::where( 'id', $value->object_id )->first();
            \Cache::forever('_user_'.$value->object_id,$user);
            return $user;
        });
		$temp['object_name'] = $tagged->first_name . ' ' . $tagged->last_name;
		$temp['object_href'] = $tagged->username;

		return $temp;
	}

	public function shareGroup( $user_id, $group_id ) {
		if ( empty( $user_id ) || empty( $group_id ) ) {
			return [ 'message' => 'invalid_parameters' ];
		}

		$group = \Cache::get('_group_share_count_id_'.$group_id,function () use ($group_id){
		    return Group::where( 'id', $group_id )->select(['id','share_count'])->first();
        });

		if ( empty( $group->id ) ) {
			return [ 'message' => 'invalid_group_id' ];
		}

		$group->share_count = $group->share_count + 1;

		$data = [
			'type'         => 'group_share',
			'subject_type' => 'user',
			'subject_id'   => $user_id,
			'object_type'  => 'group',
			'object_id'    => $group_id,
			'body'=>\Input::get('text')
		];

		if ( $this->saveActivity( $data ) && $group->save() ) {
			return [ 'message' => 'group_shared' ];
		}

		return [ 'message' => 'error_sharing_group' ];
	}

	public function saveActivity( $data ) {
		$aActionObj = new ActivityAction();

		$aActionObj->subject_type     = $data['subject_type'];
		$aActionObj->subject_id       = $data['subject_id'];
		$aActionObj->type             = $data['type'];
		$aActionObj->body             = isset( $data['body'] ) ? trim($data['body']) : '';
		$aActionObj->object_type      = $data['object_type'];
		$aActionObj->object_id        = $data['object_id'];
		$aActionObj->target_type      = isset( $data['target_type'] ) ? $data['target_type'] : null;
		$aActionObj->target_id        = isset( $data['target_id'] ) ? $data['target_id'] : 0;
		$aActionObj->attachment_count = isset( $data['attachment_count'] ) ? $data['attachment_count'] : '';

		if ( $aActionObj->save() ) {
			if(!empty($data['body']) && !empty($aActionObj->action_id)){
				$this->saveHashTags($data['body'],$aActionObj);
			}
			return $aActionObj->action_id;
		}

		return false;
	}
	public function saveHashTags($text,&$actionObj){
		preg_match_all('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', $text, $matches);
		if(!empty($matches[1])){
			foreach ($matches[1] as $key => $value){
				$hashTagObj = new Hashtag();
				$hashTagObj->tag = $value;
				$hashTagObj->parent_type = 'activity_action';
				$hashTagObj->parent_id = $actionObj->action_id;
				$hashTagObj->save();
			}
		}
	}
	public function shareEvent( $user_id, $event_id ) {
		if ( empty( $user_id ) || empty( $event_id ) ) {
			return [ 'message' => 'invalid_parameters' ];
		}

		$event = \Cache::get('_event_'.$event_id,function () use ($event_id){
		    return GroupEvent::where( 'id', $event_id )->first();
        });

		if ( empty( $event->id ) ) {
			return [ 'message' => 'invalid_event_id' ];
		}

		$event->share_count = $event->share_count + 1;

		$data = [
			'type'         => 'event_share',
			'subject_type' => 'user',
			'subject_id'   => $user_id,
			'object_type'  => 'event',
			'object_id'    => $event_id,
			'body' => \Input::get('text')
		];

		if ( $this->saveActivity( $data ) && $event->save() ) {
			return [ 'message' => 'event_shared' ];
		}

		return [ 'message' => 'error_sharing_event' ];
	}

	public function shareStatus( $user_id, $photos, $video, $text, $audio, $link, $target_type, $target_id = null, $return_posted = false ) {
		if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) && empty(\Input::get('access_token')) &&
		   empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0){
			$post_max_size = ini_get( 'post_max_size' );
			return ['error' => 1,'message' => 'Request exceed max allowed size','max_size' => $post_max_size];
		}
		if((!empty($video) && $video->getError() == 1) || (!empty($audio) && $audio->getError() == 1)){
			$max_size = ini_get( 'upload_max_filesize' );
			return ['error' => 1,'message' => 'File exceed max file size','max_size' => $max_size];
		}

		if ( empty( $photos ) && empty( $video ) && empty( $text ) && empty( $audio ) && empty( $link ) ) {
			return [ 'message' => 'invalid_parameters' ];
		}

		if ( empty( $user_id ) ) {
			return [ 'message' => 'invalid_user' ];
		}

		$temp = [
			'subject_type' => 'user',
			'subject_id'   => $user_id,
			'target_type'  => $target_type,
			'target_id'    => $target_id
		];

		if ( ! empty( $text ) ) {
			$temp['type']        = 'status';
			$temp['body']        = $text;
			$temp['object_type'] = 'user';
			$temp['object_id']   = $user_id;
		}

		if ( ! empty( $photos ) ) {
			$photo_id                 = $this->uploadPhotos( $user_id, $photos );
			$temp['type']             = 'album_photo_new';
			$temp['object_type']      = 'album_photo';
			$temp['object_id']        = $photo_id;
			$temp['attachment_count'] = count( $photos );
		} elseif ( ! empty( $video ) ) {

			$video_id = $this->uploadVideo( $user_id, $video,$temp );

			return ['message' => 'video_uploaded'];

			if ( empty( $video_id ) ) {
				return [ 'message' => 'error_uploading_video' ];
			}
			$temp['type']             = 'video_new';
			$temp['object_type']      = 'video';
			$temp['object_id']        = $video_id;
			$temp['attachment_count'] = 1;
		} elseif ( ! empty( $audio ) ) {

			$audio_id                 = $this->uploadAudio( $user_id, $audio,$temp );

			return ['message' => 'audio_uploaded'];

			$temp['type']             = 'audio_new';
			$temp['object_type']      = 'audio';
			$temp['object_id']        = $audio_id;
			$temp['attachment_count'] = 1;
		} elseif ( ! empty( $link ) ) {

			if ( empty( $link['link'] ) || empty( $link['title'] ) ) {
				return [ 'message' => 'invalid_link' ];
			}

			$link_id             = $this->saveLinkMeta( $user_id, $link );
			$temp['type']        = 'link';
			$temp['object_type'] = 'link';
			$temp['object_id']   = $link_id;
		}

		if ( $action_id = $this->saveActivity( $temp ) ) {
			$message = [ 'error' => 0,'message' => 'status_shared' ];
			if ( $return_posted ) {
				$message['post'] = $this->getPostByID( $action_id, $user_id );
			}

			return $message;
		}

		return [ 'error' => 1,'message' => 'error_status_sharing' ];
	}

	public function uploadPhotos( $user_id, $photos,$edit = False,$old_photos = [],$parent_id = 0 ) {
		$counter   = 1;
		$count = StorageFile::whereIn('file_id',$photos)
							->where('is_temp',1)
							->count();
		if(!empty($old_photos)){
			$count = $count + $old_photos->count();
		}
		if($edit && !empty($old_photos)){
			foreach ($old_photos as $photo_id => $value){
				$file = $this->getStorageFile($value,$user_id,true);
				$this->saveTimeLinePhoto($file,$parent_id,$user_id,$count,$counter,$photo_id);
				$counter ++;
			}
		}
		if(!empty($photos)) {
			foreach ($photos as $index => $value) {
				$file = $this->getStorageFile($value, $user_id);
				$my_data = $this->saveTimeLinePhoto($file, $parent_id, $user_id, $count, $counter);
				if (empty($parent_id) && !empty($my_data['photo_id']) && $counter == 1) {
					$parent_id = $my_data['photo_id'];
				}
				$counter++;
			}
		}

		return $parent_id;
	}
	public function saveTimeLinePhoto(&$file,$parent_id,$user_id,$count,$counter,$photo_id = null){
		if ( empty( $file->file_id ) ) {
			return false;
		}
		$file->is_temp = 0;

		if ( $file->save() ) {
			$temp['photo_id'] = $photo_id;
			if(empty($photo_id)) {
				$temp['parent_id'] = $parent_id;
			}
			$temp['file_id']    = $file->file_id;
			$temp['owner_id']   = $user_id;
			$temp['owner_type'] = 'user';
			$temp['album_id']   = 0;

			$key = $this->saveActivityPhoto( $temp );

			$file->parent_id = $key;
			$file->save();

			$this->processPhoto($file,$user_id,$key,$count,$counter);

			return ['file_id' => $file->file_id,'photo_id' => $key];
		}
		return false;
	}
	public function getStorageFile($value,$user_id,$edit = false){
		$query = StorageFile::where( 'file_id', $value )
					->where( 'user_id', $user_id )
					->select( [ 'file_id', 'is_temp','name','storage_path' ] );
		if(!$edit){
			$query->where( 'is_temp', 1 );
		}
		return $query->first();
	}
    public function processPhoto(&$file,$user_id,$photo_id,$count,$counter){
        $width_res  = \Config::get('constants.TIME_LINE_THUMB_WIDTH');
        $height_res = \Config::get('constants.TIME_LINE_THUMB_HEIGHT');
        $sm = new StorageManager();
        $photo = $sm->getFileByPath('photos/'.$file->storage_path);

        if($photo) {
            if($count == 2){
                $width_res = $width_res / 2;
            }elseif($count == 3) {
                if($counter == 1) {
                    $width_res = round($width_res / 1.8);
                }else{
                    $width_res = $width_res - round($width_res / 1.8);
                }
            }elseif($count == 4){
                $width_res = $width_res / 2;
                $height_res = $height_res /2;
            }elseif($count > 4){
                if($counter <= 2){
                    $width_res = $width_res / 2;
                    $height_res = $height_res / 1.8;
                }else{
                    $width_res = round($width_res / 3);
                    $height_res = round($height_res / 2.4);
                }
            }

			$img = Image::make($photo);
            $width = $img->width();
            $height = $img->height();

			if($width > $width_res || $height > $height_res) {
                if($width >= $height) {
                    $img = $img->resize($width_res,null,function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }else{
                    $img = $img->resize(null,$height_res,function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
            }

            $encoded = $img->encode('jpg',90);
            $string = $encoded->__toString();
            $file_name = $sm->getFilename('jpg');
            $path = $user_id.'/'.$file_name;
            $sm->saveFile('photos/'.$path,$string,1);

            $data['type'] = 'time_line_thumb';
            $data['parent_type'] = 'album_photo';
			$data['parent_id'] = $photo_id;
			$data['parent_file_id'] = $file->file_id;
            $data['user_id'] = $user_id;
            $data['storage_path'] = $path;
            $data['extension'] = 'jpg';
            $data['mime_type'] = 'image/jpeg';
            $data['name'] = $file->name;
			$data['size'] = $img->filesize();
			$data['hash'] = hash('sha256',$img);
			$data['width'] = $img->width();
			$data['height'] = $img->height();
            $data['is_temp'] = 0;
			$this->saveFile($data);

			$this->processPopupPhoto($file,$user_id,$photo_id);

            $img->destroy();
        }
    }
	protected function processPopupPhoto(&$file,$user_id,$photo_id){
		$popup_width = 840;
		$popup_height = 840;

		$sm = new StorageManager();
		$photo = $sm->getFileByPath('photos/'.$file->storage_path);
		$img = Image::make($photo);

		$width = $img->width();
		$height = $img->height();

		if($width > $popup_width || $height > $popup_height) {
			if($width >= $height) {
				$img = $img->resize($popup_width,null,function ($constraint) {
					$constraint->aspectRatio();
				});
			}else{
				$img = $img->resize(null,$popup_height,function ($constraint) {
					$constraint->aspectRatio();
				});
			}
		}

		$encoded = $img->encode('jpg',90);
		$string = $encoded->__toString();
		$file_name = $sm->getFilename('jpg');
		$path = $user_id.'/'.$file_name;
		$sm->saveFile('photos/'.$path,$string,1);

		$data['type'] = 'popup_photo';
		$data['parent_type'] = 'album_photo';
		$data['parent_id'] = $photo_id;
		$data['parent_file_id'] = $file->file_id;
		$data['user_id'] = $user_id;
		$data['storage_path'] = $path;
		$data['extension'] = 'jpg';
		$data['mime_type'] = 'image/jpeg';
		$data['name'] = $file->name;
		$data['size'] = $img->filesize();
		$data['width'] = $img->width();
		$data['height'] = $img->height();
		$data['hash'] = hash('sha256',$img);
		$data['is_temp'] = 0;
		$this->saveFile($data);
		$img->destroy();
	}
	public function saveActivityPhoto( $data ) {
		if(!empty($data['photo_id'])) {
			$photo = AlbumPhoto::where('photo_id', $data['photo_id'])->first();
		}
		if (empty($photo->photo_id)) {
			$photo = new AlbumPhoto();
		}
		$photo->album_id    = $data['album_id'];
		$photo->title       = isset( $data['title'] ) ? $data['title'] : '';
		$photo->description = isset( $data['description'] ) ? $data['description'] : '';
		$photo->owner_type  = $data['owner_type'];
		$photo->owner_id    = $data['owner_id'];
		$photo->file_id     = $data['file_id'];
		if(isset($data['parent_id'])) {
			$photo->parent_id = $data['parent_id'];
		}

		if ( $photo->save() ) {
			return $photo->photo_id;
		}

		return false;
	}

	public function uploadVideo( $user_id, $video, $action = [] ) {

		$data = $this->uploadFile( $user_id, $video, 'video' );

		if ( ! is_array( $data ) ) {
			return false;
		}
		$temp['file'] = $data;
		$temp['action'] = $action;

		return $this->dispatch(new EncodeVideo($temp));

		$sm = new StorageManager();

		$path = \Config::get('constants_activity.STORAGE_PATH');

		$path = $path . '/videos/';
		$storage_path = $data['storage_path'];
		$tmp = $this->encodeVideo($path,$data['storage_path']);

		$temp_video = public_path('storage/').$tmp['video'];
		$path = $sm->getPath($user_id,'video');

		$video_name = $sm->getFilename('mp4');
		$path = $path.$video_name;
		$sm->saveFile($path,$temp_video);
		$sm->deletFile('videos/'.$storage_path);
		@unlink($temp_video);

		$data['storage_path'] = $user_id.'/'.$video_name;
		$data['mime_type'] = 'video/mp4';

		$file = $this->saveFile( $data,TRUE );

		if ( @$file->file_id ) {
			$temp['title'] = $video->getClientOriginalName();
			$temp['file_id']    = $file->file_id;
			$temp['owner_type'] = 'user';
			$temp['owner_id']   = $user_id;
			$temp['album_id']   = 0;
			$temp['code']       = $data['extension'];

			$video_id = $this->saveActivityVideo( $temp );

			$file->parent_id = $video_id;
			$file->save();

			$image = public_path('storage/').$tmp['image'];
			if(file_exists($image)) {
				$photo = $sm->saveVideoThumbnail($user_id, $image, $video_id);
				$this->saveFile($photo);
				@unlink(public_path('storage/').$tmp['image']);
			}
			return $video_id;
		}

	}

	public function uploadFile( $user_id, $file, $type ) {
		if ( empty( $user_id ) || empty( $type ) || empty( $file ) ) {
			return false;
		}

		$sm = new StorageManager();

		return $sm->storeFile( $user_id, $file, $type );
	}

	public function saveFile( $data,$return_object = FALSE ) {
		$file                 = new StorageFile();
		$file->parent_file_id = ! empty( $data['parent_file_id'] ) ? $data['parent_file_id'] : null;

		$file->type         = ! empty( $data['type'] ) ? $data['type'] : null;
		$file->parent_id    = isset( $data['parent_id'] ) ? $data['parent_id'] : null;
		$file->parent_type  = $data['parent_type'];
		$file->user_id      = $data['user_id'];
		$file->storage_path = $data['storage_path'];
		$file->extension    = !empty($data['extension']) ? $data['extension'] : '';
		$file->name         = $data['name'];
		$file->mime_type    = $data['mime_type'];
		$file->size         = $data['size'];
		$file->hash         = $data['hash'];
		$file->width 		= isset($data['width']) ? $data['width'] : Null;
		$file->height		= isset($data['height']) ? $data['height'] : Null;
		$file->is_temp      = isset( $data['is_temp'] ) ? $data['is_temp'] : 0;

		$file->save();

		if($return_object){
			return $file;
		}
		return $file->file_id;

		return false;

	}

	public function saveActivityVideo( $data ) {
		$video = new Video();

		$video->code        = $data['code'];
		$video->title       = isset( $data['title'] ) ? $data['title'] : '';
		$video->description = isset( $data['description'] ) ? $data['description'] : '';
		$video->owner_type  = $data['owner_type'];
		$video->owner_id    = $data['owner_id'];
		$video->file_id     = $data['file_id'];

		if ( $video->save() ) {
			return $video->video_id;
		}

		return false;
	}

	function uploadAudio( $user_id, $audio,$action = [] ) {
		if ( empty( $user_id ) || empty( $audio ) ) {
			return false;
		}

		$data = $this->uploadFile( $user_id, $audio, 'audio' );

		$temp['file'] = $data;
		$temp['action'] = $action;

		return $this->dispatch(new EncodeAudio($temp));

		$path = \Config::get('constants_activity.STORAGE_PATH');

		$path = $path . 'audios/';

		$tmp_path = $this->encodeAudio($path,$data['storage_path']);

		$sm = new StorageManager();
		$audio_path = $sm->getPath($user_id,'audio');
		$f_name = $sm->getFilename('mp3');

		$m_path = $audio_path.$f_name;

		$sm->saveFile($m_path,$tmp_path);
		$sm->deletFile('audios/'.$data['storage_path']);
		@unlink($tmp_path);
		$data['storage_path'] = $user_id.'/'.$f_name;
		$data['parent_type'] = null;

		return $this->saveFile( $data );

	}

	public function saveLinkMeta( $user_id, $link ) {
		$linkObj = new Link();

		$linkObj->uri         = $link['link'];
		$linkObj->title       = $link['title'];
        $linkObj->owner_id = $user_id;
        $linkObj->owner_type = 'user';
		$linkObj->description = isset( $link['description'] ) ? $link['description'] : '';

		$linkObj->save();

		$file_id = null;

		if ( ! empty( $link['image'] ) ) {
			$sm = new StorageManager();

			$data = $sm->copyFromURL( $user_id, $link['image'], 'link' );

			$data['parent_id'] = $linkObj->link_id;

			$file_id = $this->saveFile( $data );
		}

		$linkObj->photo_id = $file_id;

		$linkObj->save();

		return $linkObj->link_id;

	}

	public function getPostByID( $action_id, $user_id,$popup = false ) {
		$post = ActivityAction::where( 'action_id', $action_id )->first();
		if ( $post ) {
			return $this->makePost( $post, $user_id,$popup );
		} else {
			return false;
		}

	}

	public function extractLinkMeta( $link ) {
		$client  = new Client();
		$crawler = $client->request( 'GET', $link );

		$title_meta = $crawler->filter( 'meta[property="og:title"]' )->first();

		if ( $title_meta->count() ) {
			$title = $title_meta->attr( 'content' );
		} else {
			$title = $crawler->filter( 'title' )->first()->text();
		}

		$temp['title'] = $title;

		$description_meta = $crawler->filter( 'meta[property="og:description"]' )->first();

		$description = '';
		if ( $description_meta->count() ) {
			$description = $description_meta->attr( 'content' );
		} elseif ( $crawler->filter( 'p' )->count() ) {
			$description = $crawler->filter( 'p' )->first()->text();
		}

		$temp['description'] = $description;

		$images = $crawler->filter( 'meta[property="og:image"]' )->each( function ( Crawler $node, $i ) {
			return $node->attr( 'content' );
		} );

		if(empty($images)){
			$images = $crawler->filter('img')->each(function (Crawler $node, $i){
				return $node->attr('src');
			});
		}
		$img = [];
		if(!empty($images)){
			foreach ($images as $index => $image){
				$pathInfo = pathinfo($image);
				$extension = strtolower(@$pathInfo['extension']);
				if($extension == 'jpeg' || $extension == 'jpg' || $extension == 'png' || $extension == ''){
					$img[] = $image;
				}
			}
		}
		$temp['images'] = $img;
		return $temp;

	}

	public function editActivity( $activity_id, $user_id, $body,$photos,$tokens_old ) {
		if ( empty( $activity_id ) || empty( $user_id ) ) {
			if ( $this->is_api ) {
				return \Api::invalid_param();
			}

			return [ 'message' => 'invalid_parameters' ];
		}

		$aActionObj = new ActivityAction();

		$activity = $aActionObj->find( $activity_id );

		if ( isset( $activity->subject_id ) ) {
			if ( $user_id == $activity->subject_id ) {
				$activity->body = $body;
				if ( $activity->save() ) {

					if(!empty($photos) || !empty($tokens_old)){
						$this->editUploadPhotos($user_id,$activity,$photos,$tokens_old);
					}

					if ( $this->is_api ) {
						return \Api::success_with_message();
					}

					return [ 'message' => 'status_updated' ];
				}
			} else {
				if ( $this->is_api ) {
					return \Api::access_denied();
				}

				return [ 'message' => 'action_not_permitted' ];
			}

		}
		if ( $this->is_api ) {
			return \Api::detail_not_found();
		}

		return [ 'message' => 'invalid_id' ];
	}
	public function editUploadPhotos($user_id,&$post,$photos,$tokens_old){
		$photo_id = $post->object_id;

		if($post->type == 'album_photo_new'){
			$old_photos = AlbumPhoto::where(function($query) use ($photo_id){
											$query->where('photo_id',$photo_id);
											$query->orWhere('parent_id',$photo_id);
										})->lists('file_id','photo_id');
			if($old_photos->count() > 0){
				$old_files = StorageFile::whereIn('parent_id',$old_photos->keys())
										->get();
				$sm = new StorageManager();
				foreach ($old_files as $old_file){
					if(in_array($old_file->file_id,$tokens_old)){
						continue;
					}
					if(empty($old_file->parent_file_id)){
						if($this->deleteAlbumPhoto($user_id,$old_file->parent_id,$photo_id)){
							$old_photos->forget($old_file->parent_id);
						}
					}
					if($sm->pathExists('photos/'.$old_file->storage_path)){
						$sm->deletFile('photos/'.$old_file->storage_path);
					}
					$old_file->delete();
				}
			}
			$this->uploadPhotos($user_id,$photos,true,$old_photos,$photo_id);
		}else{
			$this->uploadPhotos($user_id,$photos);
		}
	}

	protected function deleteAlbumPhoto($user_id,$photo_id,$parent_photo_id){
		$photo = AlbumPhoto::where('photo_id',$photo_id)
							->where('owner_id',$user_id)
							->first();
		if(!empty($photo->photo_id) && $photo->photo_id != $parent_photo_id){
			return $photo->delete();
		}
		return false;
	}
	public function getCommentsThreaded( $user_id, $parent_id ) {
		if ( empty( $parent_id ) ) {
			return false;
		}

		$comments = ActivityComment::where( 'parent_comment_id', $parent_id )
		                           ->get();

		if ( empty($comments ) ) {
			return false;
		}

		foreach ( $comments as $key => $value ) {

			$comments[$key] = $this->getCommentMeta($value,$user_id);

		}

		return $comments;
	}

	public function addComment( $activity_action_id, $poster_id, $poster_type, $body, $return_posted = false,$attachment = [] ) {
		if ( empty( $activity_action_id ) || empty( $poster_id ) || empty( $poster_type ) || (empty( $body )  && empty($attachment))) {
			if ( $this->is_api ) {
				return \Api::invalid_param();
			}

			return [ 'message' => 'invalid_parameters' ];
		}

		$activity = ActivityAction::where( 'action_id', $activity_action_id )->select( [
			'action_id',
			'subject_id'
		] )->first();

		if ( empty( $activity->action_id ) ) {
			if ( $this->is_api ) {
				return \Api::detail_not_found();
			}

			return [ 'message' => 'invalid_activity_id' ];
		}

		$this->updateActivityCommentsCount( $activity_action_id );

		//$this->addActivityCommentAction($activity_action_id,$poster_id,$poster_type,$body);

		$commentObj              = new ActivityComment();
		$commentObj->resource_id = $activity_action_id;
		$commentObj->poster_id   = $poster_id;
		$commentObj->poster_type = $poster_type;
		$commentObj->body        = trim($body);

		if ( $commentObj->save() ) {

            if(!empty($attachment)){
                $file = $this->uploadFile($poster_id, $attachment, 'attachment');
                $file['type'] = 'attachment';
                $file['parent_type'] = 'activity_comment';
                $file['parent_id'] = $commentObj->comment_id;
                $attachment = $this->saveFile($file,True);

                $commentObj->attachment_id = $attachment->file_id;
                $commentObj->attachment_type = 'image';
                $commentObj->save();

                $this->saveAttachmentThumb($attachment,$poster_id,$commentObj->comment_id);
            }else{
				preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $body, $match);
				if(!empty($match[0])){
					$link = end($match[0]);
					$linkMeta = $this->extractLinkMeta($link);

					$temp['title'] = isset($linkMeta['title']) ? $linkMeta['title'] : '';
					$temp['link'] = $link;
					$temp['description'] = $linkMeta['description'];
					$temp['image'] = !empty($linkMeta['images'][0]) ? $linkMeta['images'][0] : Null;

					$link_id = $this->saveLinkMeta($poster_id,$temp);

					$commentObj->attachment_id = $link_id;
					$commentObj->attachment_type = 'link';

					$commentObj->save();
				}
			}

			$attributes = array(

				'resource_type' => \Config::get( 'constants_activity.OBJECT_TYPES.USER.NAME' ),
				'resource_id'   => $activity->subject_id,
				'subject_id'    => $poster_id,
				'subject_type'  => $poster_type,
				'object_id'     => $activity_action_id,
				'object_type'   => \Config::get( 'constants_activity.notification.OBJECT_TYPE.NAME' ),
				'type'          => \Config::get( 'constants_activity.notification.OBJECT_TYPE.TYPES.COMMENT' ),
			);

			\Event::fire( new CreateNotification( $attributes ) );
			if ( $this->is_api ) {
				//return \Api::success_with_message();
			}
			$message = [ 'message' => 'comment_added' ];

			if ( $return_posted ) {

				$message['comment'] = $this->getCommentByID( $commentObj->comment_id, $poster_id );
				if ( $this->is_api ) {
					return \Api::success( [ 'data' => $message ] );
				}
			}

			return $message;
		}

		return [ 'message' => 'error_adding_comment' ];
	}
    protected function saveAttachmentThumb(&$file,$user_id,$comment_id){
        $thumb_width = \Config::get('constants.COMMENT_THUMB_WIDTH');
        $thumb_height = \Config::get('constants.COMMENT_THUMB_HEIGHT');

        $sm = new StorageManager();
        $photo = $sm->getFileByPath('attachments/'.$file->storage_path);
        $img = Image::make($photo);

        $width = $img->width();
        $height = $img->height();

        if($width > $thumb_width || $height > $thumb_height) {
            if($width >= $height) {
                $img = $img->resize($thumb_width,null,function ($constraint) {
                    $constraint->aspectRatio();
                });
            }else{
                $img = $img->resize(null,$thumb_height,function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }

        $encoded = $img->encode('jpg',90);
        $string = $encoded->__toString();
        $file_name = $sm->getFilename('jpg');
        $path = $sm->getPath($user_id,'attachment_thumb');
        $path = $path.DIRECTORY_SEPARATOR.$file_name;
        $sm->saveFile($path,$string,1);

        $data['type'] = 'attachment_thumb';
        $data['parent_type'] = 'activity_comment';
        $data['parent_id'] = $comment_id;
        $data['parent_file_id'] = $file->file_id;
        $data['user_id'] = $user_id;
        $data['storage_path'] = $user_id.'/'.$file_name;
        $data['extension'] = 'jpg';
        $data['mime_type'] = 'image/jpeg';
        $data['name'] = $file->name;
        $data['size'] = $img->filesize();
        $data['width'] = $img->width();
        $data['height'] = $img->height();
        $data['hash'] = hash('sha256',$img);
        $data['is_temp'] = 0;
        $this->saveFile($data);
        $img->destroy();
    }
	public function updateActivityCommentsCount( $activity_action_id, $operator = 'plus' ) {

		$likes = ActivityAction::find( $activity_action_id, [ 'action_id', 'comment_count' ] );

		if ( $operator == 'plus' ) {
			$likes->comment_count = $likes->comment_count + 1;
		} else {
			$likes->comment_count = $likes->comment_count - 1;
		}

		if ( $likes->save() ) {
			return true;
		}

		return false;

	}
    public function getActivityLikes($action_id,$user_id){

        $activity = ActivityAction::where('action_id',$action_id)->select(['action_id','like_count','dislike_count'])->first();

        return $this->getLikesMetaInfo($activity,$user_id);
    }
	public function getCommentByID( $comment_id, $user_id ) {
		$comment = ActivityComment::where( 'comment_id', $comment_id )->first();

		return $this->getCommentMeta( $comment, $user_id );
	}

	public function addCommentThreaded( $parent_comment_id, $activity_action_id, $poster_id, $poster_type, $body,$return_posted = False ) {
		if ( empty( $parent_comment_id ) || empty( $activity_action_id ) || empty( $poster_id ) || empty( $poster_type ) || empty( $body ) ) {
			if ( $this->is_api ) {
				return \Api::invalid_param();
			}

			return [ 'message' => 'invalid_parameters' ];
		}

		$count = ActivityComment::where( 'comment_id', $parent_comment_id )->count();

		if ( $count < 1 ) {
			if ( $this->is_api ) {
				return \Api::detail_not_found();
			}

			return [ 'message' => 'invalid_parent_id' ];
		}

		//$this->addActivityCommentThreadedAction($activity_action_id,$poster_id,$poster_type,$body);

		$commentObj                    = new ActivityComment();
		$commentObj->resource_id       = $activity_action_id;
		$commentObj->poster_id         = $poster_id;
		$commentObj->poster_type       = $poster_type;
		$commentObj->parent_comment_id = $parent_comment_id;
		$commentObj->body              = $body;

		if ( $commentObj->save() ) {

			$response = [ 'message' => 'comment_added' ];
			if($return_posted){
				$response['comment'] = $this->getCommentByID($commentObj->comment_id,$poster_id);
			}
			if ( $this->is_api ) {
				return \Api::success( [ 'data' => $response ] );
			}
			return $response;
		}

		return [ 'message' => 'error_adding_comment' ];
	}

	public function updateComment( $comment_id, $body ) {
		if ( empty( $comment_id ) ) {
			return false;
		}

		$commentObj = new ActivityComment();
		$comment    = $commentObj->find( $comment_id );

		$comment->body = $body;

		if ( $commentObj->save() ) {
			return true;
		}

		return false;
	}

	public function deleteActivityComment( $comment_id, $poster_id, $poster_type ) {
		if ( empty( $comment_id ) ) {
			return false;
		}

		$commentObj = new ActivityComment();

		$comment = $commentObj
			->where( 'comment_id', $comment_id )
			->select( [ 'comment_id', 'resource_id', 'poster_id', 'poster_type','attachment_id','attachment_type' ] )
			->first();

		if ( empty( $comment->comment_id ) ) {
			if ( $this->is_api ) {
				return \Api::detail_not_found();
			}
			return [ 'message' => 'invalid_id' ];
		}
		if ( $comment->poster_id != $poster_id || $comment->poster_type != $poster_type ) {
			if ( $this->is_api ) {
				return \Api::access_denied();
			}
			return [ 'message' => 'permission_denied' ];
		}

		$activity_action_id = $comment->resource_id;
		if(!empty($comment->attachment_id)){
			$attachment_id = $comment->attachment_id;
			$attachment_type = $comment->attachment_type;
			$this->deleteCommentMeta($comment_id,$attachment_id,$attachment_type,$poster_id);
		}
		if ( $comment->delete() ) {
			$this->updateActivityCommentsCount( $activity_action_id, 'minus' );

			//$this->deleteActivityCommentAction($activity_action_id);
			if ( $this->is_api ) {
				return \Api::success_with_message('comment_deleted');
			}
			return [ 'message' => 'comment_deleted' ];
		}
		if ( $this->is_api ) {
			return \Api::other_error('Error deleting comment');
		}
		return [ 'message' => 'error_deleting_comment' ];

	}
	public function deleteCommentMeta($comment_id,$attachment_id,$attachment_type,$user_id){
		if($attachment_type == 'image'){

			$files = StorageFile::where(function($query) use ($attachment_id){
										$query->where('file_id',$attachment_id);
										$query->orWhere('parent_file_id',$attachment_id);
									})
									->where('user_id',$user_id)
									->get();
			foreach ($files as $file){
				if($file->type == 'attachment' && $this->file_exists('attachments/'.$file->storage_path)){
					$this->deletePath('attachments/'.$file->storage_path);
				}elseif ($file->type == 'attachment_thumb' && $this->file_exists('attachments/thumbs/'.$file->storage_path)){
					$this->deletePath('attachments/thumbs/'.$file->storage_path);
				}
				$file->delete();
			}
		}elseif ($attachment_type == 'link'){
			$link = Link::whereLinkId($attachment_id)
							->whereOwnerId($user_id)
							->first();
			if(!empty($link->photo_id)){
				$file = StorageFile::where('file_id',$link->photo_id)->where('user_id',$user_id)->first();
				if($this->file_exists('photos/'.$file->storage_path)){
					$this->deletePath('photos/'.$file->storage_path);
				}
				$file->delete();
			}
			$link->delete();
		}
	}
	public function likeStatus( $activity_action_id, $poster_id, $poster_type,$return_liked = FALSE ) {
		if ( empty( $activity_action_id ) || empty( $poster_id ) || empty( $poster_type ) ) {
			if ( $this->is_api ) {
				return \Api::invalid_param();
			}

			return [ 'message' => 'invalid_parameters' ];
		}

		$activity = ActivityAction::where( 'action_id', $activity_action_id )->select( [
			'action_id',
			'subject_id',
            'like_count',
		] )->first();

		if ( empty( $activity->action_id ) ) {
			if ( $this->is_api ) {
				return \Api::detail_not_found();
			}

			return [ 'message' => 'invalid_activity_id' ];
		}

		$count = ActivityLike::where( 'resource_id', $activity_action_id )
		                     ->where( 'poster_type', $poster_type )
		                     ->where( 'poster_id', $poster_id )
		                     ->count();

		if ( $count > 0 ) {
			if ( $this->is_api ) {
				return \Api::already_done( 'Status already liked' );
			}

			return [ 'message' => 'already_liked' ];
		}

		if ( $this->isStatusDisliked( $activity_action_id, $poster_id, $poster_type ) ) {
			$this->deleteActivityDislike( $activity_action_id, $poster_id, $poster_type );

			$this->updateActivityDislikesCount( $activity_action_id, 'minus' );
		}

		$this->updateActivityLikesCount( $activity_action_id );
		//$this->addActivityLikeAction($activity_action_id,$poster_id,$poster_type);

		$like = New ActivityLike();

		$like->resource_id = $activity_action_id;
		$like->poster_id   = $poster_id;
		$like->poster_type = $poster_type;

		if ( $like->save() ) {

			$attributes = array(

				'resource_type' => \Config::get( 'constants_activity.OBJECT_TYPES.USER.NAME' ),
				'resource_id'   => $activity->subject_id,
				'subject_id'    => $poster_id,
				'subject_type'  => $poster_type,
				'object_id'     => $activity_action_id,
				'object_type'   => \Config::get( 'constants_activity.notification.OBJECT_TYPE.NAME' ),
				'type'          => \Config::get( 'constants_activity.notification.OBJECT_TYPE.TYPES.LIKE' ),
			);

			\Event::fire( new CreateNotification( $attributes ) );
			if($this->is_api){
				return \Api::success_with_message();
			}
			$message =  [ 'message' => 'status_liked' ];

            if($return_liked){
                $likes = $this->getActivityLikes($activity_action_id,$poster_id);

                $message['likes'] = $likes['likes'];
            }
            return $message;
		}

		return [ 'message' => 'error_status_liking' ];
	}

	public function isStatusDisliked( $resource_id, $poster_id, $poster_type ) {
		return ActivityDislike::where( 'resource_id', $resource_id )
		                      ->where( 'poster_id', $poster_id )
		                      ->where( 'poster_type', $poster_type )
		                      ->count();
	}

	public function deleteActivityDislike( $activity_action_id, $poster_id, $poster_type ) {
		$dislikeObj = New ActivityDislike();

		$dislike =  $dislikeObj
			->where( 'resource_id', $activity_action_id )
			->where( 'poster_id', $poster_id )
			->where( 'poster_type', $poster_type )
			->first();

        if(!empty($dislike->dislike_id)){
            return $dislike->delete();
        }
        return false;
	}

	public function updateActivityDislikesCount( $activity_action_id, $operator = 'plus' ) {

		$dislikes = ActivityAction::find( $activity_action_id, [ 'action_id', 'dislike_count' ] );

		if ( empty( $dislikes->action_id ) ) {
			return false;
		}

		if ( $operator == 'plus' ) {
			$dislikes->dislike_count = $dislikes->dislike_count + 1;
		} else {
			$dislikes->dislike_count = $dislikes->dislike_count - 1;
		}

		if ( $dislikes->save() ) {
			return true;
		}

		return false;
	}

	public function updateActivityLikesCount( $activity_action_id, $operator = 'plus' ) {

		$likes = ActivityAction::find( $activity_action_id, [ 'action_id', 'like_count' ] );

		if ( empty( $likes->action_id ) ) {
			return false;
		}

		if ( $operator == 'plus' ) {
			$likes->like_count = $likes->like_count + 1;
		} else {
			$likes->like_count = $likes->like_count - 1;
		}

		if ( $likes->save() ) {
			return true;
		}

		return false;

	}

	public function unlikeStatus( $activity_action_id, $poster_id, $poster_type,$return_liked = FALSE ) {
		if ( empty( $activity_action_id ) || empty( $poster_id ) || empty( $poster_type ) ) {
			if ( $this->is_api ) {
				return \Api::invalid_param();
			}
			return [ 'message' => 'invalid_parameters' ];
		}

		if ( $this->deleteActivityLike( $activity_action_id, $poster_id, $poster_type ) ) {
			$this->updateActivityLikesCount( $activity_action_id, 'minus' );

			//$this->deleteActivityLikedAction($activity_action_id,$poster_id,$poster_type);
			if ( $this->is_api ) {
				return \Api::success_with_message();
			}
			$message =  [ 'message' => 'status_unliked' ];
            if($return_liked){
                $likes = $this->getActivityLikes($activity_action_id,$poster_id);
                $message['likes'] = $likes['likes'];
            }
            if($this->is_api){
                return \Api::success_with_message('Undone status liked');
            }
            return $message;
		}
		if ( $this->is_api ) {
			return \Api::other_error('Error Unliking Status');
		}
		return [ 'message' => 'error_unliking_status' ];

	}

	public function deleteActivityLike( $activity_action_id, $poster_id, $poster_type ) {
		$likeObj = New ActivityLike();

		$like = $likeObj
			->where( 'resource_id', $activity_action_id )
			->where( 'poster_id', $poster_id )
			->where( 'poster_type', $poster_type )
			->first();

            if(!empty($like->like_id)) {
                return $like->delete();
            }
            return false;
	}

	public function addActivityLikeAction( $activity_action_id, $poster_id, $poster_type ) {

		if ( empty( $activity_action_id ) || empty( $poster_id ) || empty( $poster_type ) ) {
			return false;
		}

		$activity = ActivityAction::find( $activity_action_id );

		if ( empty( $activity->action_id ) ) {
			return false;
		}

		$acObj = new ActivityAction();

		$temp['type']         = 'like_' . $activity->type;
		$temp['subject_type'] = $poster_type;
		$temp['subject_id']   = $poster_id;
		$temp['object_type']  = $activity->object_type;
		$temp['object_id']    = $activity->object_id;

		// $temp['params'] =

		return $this->saveActivity( $temp );
	}

	public function deleteActivityLikedAction( $activity_action_id, $poster_id, $poster_type ) {
		if ( empty( $activity_action_id ) || empty( $poster_id ) || empty( $poster_type ) ) {
			return false;
		}

		$activity = ActivityAction::find( $activity_action_id );

		if ( empty( $activity->action_id ) ) {
			return false;
		}

		$acObj = new ActivityAction();

		return $acObj->where( 'type', 'like_' . $activity->type )
		             ->where( 'subject_id', $poster_id )
		             ->where( 'subject_type', $poster_type )
		             ->where( 'object_type', $activity->object_type )
		             ->where( 'object_id', $activity->object_id )
		             ->delete();

	}

	public function deleteActivityCommentAction( $activity_action_id ) {
		if ( empty( $activity_action_id ) ) {
			return false;
		}

		$activity = ActivityAction::find( $activity_action_id );

		if ( empty( $activity->id ) ) {
			return false;
		}

		$acObj = new ActivityAction();

		return $acObj->where( 'type', 'comment_' . $activity->type )
		             ->where( 'subject_id', $activity->subject_id )
		             ->where( 'subject_type', $activity->subject_type )
		             ->where( 'object_type', $activity->object_type )
		             ->where( 'object_id', $activity->object_id )
		             ->delete();

	}

	public function addActivityCommentAction( $activity_action_id, $poster_id, $poster_type, $body ) {
		if ( empty( $activity_action_id ) || empty( $poster_id ) || empty( $poster_type ) || empty( $body ) ) {
			return false;
		}

		$activity = ActivityAction::find( $activity_action_id );

		if ( empty( $activity->action_id ) ) {
			return false;
		}

		$acObj = new ActivityAction();

		$temp['type']         = 'comment_' . $activity->type;
		$temp['subject_type'] = $poster_type;
		$temp['subject_id']   = $poster_id;
		$temp['object_type']  = $activity->object_type;
		$temp['object_id']    = $activity->object_id;

		return $this->saveActivity( $temp );
	}

	public function addActivityCommentThreadedAction( $activity_action_id, $poster_id, $poster_type, $body ) {
		if ( empty( $activity_action_id ) || empty( $poster_id ) || empty( $poster_type ) || empty( $body ) ) {
			return false;
		}

		$activity = ActivityAction::find( $activity_action_id );

		if ( empty( $activity->action_id ) ) {
			return false;
		}

		$acObj = new ActivityAction();

		$temp['type']         = 'reply_comment_' . $activity->type;
		$temp['subject_type'] = $poster_type;
		$temp['subject_id']   = $poster_id;
		$temp['object_type']  = $activity->object_type;
		$temp['object_id']    = $activity->object_id;

		return $this->saveActivity( $temp );
	}

	public function likeActivityComment( $resource_id, $poster_id, $poster_type ) {
		if ( empty( $resource_id ) || empty( $poster_type ) || empty( $poster_id ) ) {
			if($this->is_api){
				return \Api::invalid_param();
			}
			return [ 'message' => 'invalid_parameters' ];
		}

		$count = Like::where( 'resource_type', 'comment' )
		             ->where( 'resource_id', $resource_id )
		             ->where( 'poster_id', $poster_id )
		             ->where( 'poster_type', $poster_type )
		             ->count();

		if ( $count > 0 ) {
			if($this->is_api){
				return \Api::already_done('Comment already liked');
			}
			return [ 'message' => 'comment_already_liked' ];
		}

		$likeObj = new Like();

		$likeObj->resource_type = 'comment';
		$likeObj->resource_id   = $resource_id;
		$likeObj->poster_type   = $poster_type;
		$likeObj->poster_id     = $poster_id;

		if ( $this->updateActivityCommentLikeCount( $resource_id ) && $likeObj->save() ) {
			if($this->is_api){
				return \Api::success_with_message('comment_liked');
			}
			return [ 'message' => 'comment_liked' ];
		}
		if($this->is_api){
			return \Api::other_error('Error liking comment');
		}
		return [ 'message' => 'error_liking_comment' ];
	}

	public function updateActivityCommentLikeCount( $comment_id, $operator = 'plus' ) {
		$commentObj = new ActivityComment();

		$comment = $commentObj->where( 'comment_id', $comment_id )->first();

		if ( empty( $comment->comment_id ) ) {
			return false;
		}
		if ( $operator == 'plus' ) {
			$comment->like_count = $comment->like_count + 1;
		} else {
			$comment->like_count = $comment->like_count - 1;
		}

		if ( $comment->save() ) {
			return true;
		}

		return false;
	}

	public function unlikeActivityComment( $resource_id, $poster_id, $poster_type ) {
		if ( empty( $resource_id ) || empty( $poster_type ) || empty( $poster_id ) ) {
			return [ 'message' => 'invalid_parameters' ];
		}

		$like = Like::where( 'resource_type', 'comment' )
		            ->where( 'resource_id', $resource_id )
		            ->where( 'poster_id', $poster_id )
		            ->where( 'poster_type', $poster_type )
		            ->first();

		if ( empty( $like->like_id ) ) {
			if($this->is_api){
				return \Api::detail_not_found();
			}
			return [ 'message' => 'invalid_id' ];
		}

		if ( $this->updateActivityCommentLikeCount( $resource_id, 'minus' ) && $like->delete() ) {
			if($this->is_api){
				return \Api::success_with_message('comment_unliked');
			}
			return [ 'message' => 'comment_unliked' ];
		}
		if($this->is_api){
			return \Api::other_error('error_unliking_comment');
		}
		return [ 'message' => 'error_unliking_comment' ];
	}

	public function makeActivityFavourite( $activity_action_id, $poster_id, $poster_type ) {
		if ( empty( $activity_action_id ) || empty( $poster_id ) || empty( $poster_type ) ) {
			if($this->is_api){
				return \Api::invalid_param();
			}
			return [ 'message' => 'invalid_parameters' ];
		}

		$activity_count = ActivityAction::where( 'action_id', $activity_action_id )->count();

		if ( $activity_count < 1 ) {
			if($this->is_api) {
				return \Api::detail_not_found();
			}
			return [ 'message' => 'invalid_resource_id' ];
		}

		$count = ActivityFavourite::where( 'resource_id', $activity_action_id )
		                          ->where( 'poster_type', $poster_type )
		                          ->where( 'poster_id', $poster_id )
		                          ->count();

		if ( $count > 0 ) {
			if($this->is_api) {
				return \Api::already_done('Already Favourite');
			}

			return [ 'message' => 'already_favourite' ];
		}

		$this->updateActivityFavCount( $activity_action_id );

		$fav = New ActivityFavourite();

		$fav->resource_id = $activity_action_id;
		$fav->poster_id   = $poster_id;
		$fav->poster_type = $poster_type;

		if ( $fav->save() ) {
			if($this->is_api) {
				return \Api::success_with_message('Status added to favourite');
			}

			$attributes = array(
			    'resource_type' => \Config::get( 'constants_activity.OBJECT_TYPES.USER.NAME' ),
                'resource_id'   => ActivityAction::whereActionId($activity_action_id)->value('subject_id'),
                'subject_id'    => $poster_id,
                'subject_type'  => $poster_type,
                'object_id'     => $activity_action_id,
                'object_type'   => \Config::get( 'constants_activity.notification.OBJECT_TYPE.NAME' ),
                'type'          => \Config::get( 'constants_activity.notification.OBJECT_TYPE.TYPES.FAV' ),
            );

            \Event::fire( new CreateNotification( $attributes ) );

			return [ 'message' => 'status_fav' ];
		}
		if($this->is_api) {
		}
		return [ 'message' => 'error_making_fav' ];
	}

	public function updateActivityFavCount( $activity_action_id, $operator = 'plus' ) {

		$fav = ActivityAction::find( $activity_action_id, [ 'action_id', 'fav_count' ] );

		if ( empty( $fav->action_id ) ) {
			return false;
		}

		if ( $operator == 'plus' ) {
			$fav->fav_count = $fav->fav_count + 1;
		} else {
			$fav->fav_count = $fav->fav_count - 1;
		}

		if ( $fav->save() ) {
			return true;
		}

		return false;

	}

	public function removeActivityFavourite( $activity_action_id, $poster_id, $poster_type ) {
		if ( empty( $activity_action_id ) || empty( $poster_id ) || empty( $poster_type ) ) {
			return [ 'message' => 'invalid_parameters' ];
		}

		$favObj = New ActivityFavourite();

		$bool = $favObj
			->where( 'resource_id', $activity_action_id )
			->where( 'poster_id', $poster_id )
			->where( 'poster_type', $poster_type )
			->delete();

		if ( $bool ) {
			$this->updateActivityFavCount( $activity_action_id, 'minus' );

			return [ 'message' => 'status_unfav' ];
		}

		return [ 'message' => 'error_unfav_status' ];
	}
	function getObject($object_type,$object_id){
		if($query = $this->buildObjectQuery($object_type,$object_id)){
			return $query->first();
		}
		return FALSE;
	}
	protected function buildObjectQuery($object_type,$object_id){
		$queryObject = NULL;
		if($object_type == 'video'){
			$queryObject = Video::where('video_id',$object_id);
		}elseif($object_type == 'link'){
			$queryObject = Link::where('link_id',$object_id);
		}elseif($object_type == 'activity_action'){
			$queryObject = ActivityAction::where('action_id',$object_id);
		}elseif($object_type == 'battle'){
			$queryObject = Battle::whereId($object_id);
		}elseif($object_type == 'poll'){
			$queryObject = Poll::whereId($object_id);
		}elseif($object_type == 'group'){
			$queryObject = Group::whereId($object_id);
		}elseif($object_type == 'album_photo'){
			$queryObject = AlbumPhoto::wherePhotoId($object_id);
		}elseif($object_type == 'event'){
			$queryObject = GroupEvent::whereId($object_id);
		}elseif($object_type == 'audio' || $object_type == 'cover_photo'){
			$queryObject = StorageFile::whereFileId($object_id);
		}elseif($object_type == 'brand' || $object_type == 'user'){
			$queryObject = \Cache::get('_user_'.$object_id,function () use ($object_id){
			    $user =  User::whereId($object_id);
                \Cache::forever('_user_'.$object_id,$user);
                return $user;
            });
		}elseif($object_type == 'album'){
			$queryObject = Album::whereAlbumId($object_id);
		}elseif($object_type == 'product'){
			$queryObject = StoreProduct::where('id',$object_id);
		}
		return $queryObject;
	}
	public function getIDByObject($object_type,$object_id){
		$query = $this->buildObjectQuery($object_type,$object_id);
		$created_at = $query->value('created_at');

		$created_at = date('Y-m-d',strtotime($created_at));

		$action = ActivityAction::whereObjectId($object_id)
								->whereDate('created_at', '=', $created_at)
								->orderBy('action_id','ASC')
								->select(['action_id'])
								->whereObjectType($object_type)
								->first();

		return $action->action_id;
	}
	public function shareActivity($user_id, $text,$object_id,$object_type,$return_posted = FALSE ) {
		if ( empty( $user_id ) || empty($object_id) || empty($object_type)) {
			return [ 'message' => 'invalid_parameters' ];
		}

		$query = $this->buildObjectQuery($object_type,$object_id);
		$object = $query->first();

		if(isset($object->share_count)){
			$object->share_count = $object->share_count + 1;
		}else{
			return ['message' => 'invalid_object'];
		}

		$acObj = new ActivityAction();

		$acObj->type         = 'share';
		$acObj->subject_id   = $user_id;
		$acObj->subject_type = 'user';
		$acObj->object_id    = $object_id;
		$acObj->object_type  = $object_type;
		$acObj->body         = $text;
		if($object_type == 'album_photo'){
			$acObj->attachment_count = AlbumPhoto::where('photo_id',$object_id)->orWhere('parent_id',$object_id)->count();
		}

		if ( $acObj->save() && $object->update() ) {

			$message = [ 'message' => 'status_shared' ];

			if($return_posted)
			{
				$message['post'] = $this->getPostByID($acObj->action_id,$user_id);
			}

            $attributes = array(

                'resource_type' => \Config::get( 'constants_activity.OBJECT_TYPES.USER.NAME' ),
                'resource_id'   => $this->getObjectOwnerID($object_type,$object),
                'subject_id'    => $user_id,
                'subject_type'  => 'user',
                'object_id'     => $acObj->action_id,
                'object_type'   => \Config::get( 'constants_activity.notification.OBJECT_TYPE.NAME' ),
                'type'          => \Config::get( 'constants_activity.notification.OBJECT_TYPE.TYPES.SHARE' ),
            );

            \Event::fire( new CreateNotification( $attributes ) );

			return $message;
		}

		return [ 'message' => 'error_status_sharing' ];
	}

	public function likeItem( $resource_id, $resource_type, $poster_id, $poster_type ) {
		if ( empty( $resource_id ) || empty( $resource_type ) || empty( $poster_id ) || empty( $poster_type ) ) {
			return [ 'message' => 'invalid_parameters' ];
		}
		$procced = false;

		if ( $resource_type == 'photo' ) {
			$count = AlbumPhoto::where( 'album_photo', $resource_id )->count();

			if ( $count < 1 ) {
				return [ 'message' => 'invalid_resource_id' ];
			}
			$procced = true;
		}

		if ( $procced ) {
			$likeObj = new Like();

			$likeObj->resource_type = $resource_type;
			$likeObj->resource_id   = $resource_id;
			$likeObj->poster_type   = $poster_type;
			$likeObj->poster_id     = $poster_id;

			if ( $likeObj->save() ) {
				return [ 'message' => $resource_type . '_liked' ];
			}
		}

		return [ 'message' => 'invalid_parameters' ];

	}

	public function unLikeItem( $resource_id, $resource_type, $poster_id, $poster_type ) {
		if ( empty( $resource_id ) || empty( $resource_type ) || empty( $poster_id ) || empty( $poster_type ) ) {
			return [ 'message' => 'invalid_parameters' ];
		}
		$procced = false;

		if ( $resource_type == 'photo' ) {
			$count = AlbumPhoto::where( 'album_photo', $resource_id )->count();

			if ( $count < 1 ) {
				return [ 'message' => 'invalid_resource_id' ];
			}
			$procced = true;
		}

		if ( $procced ) {
			$bool = Like::where( 'resource_id', $resource_id )
			            ->where( 'resource_type', $resource_type )
			            ->where( 'poster_id', $poster_id )
			            ->where( 'poster_type', $poster_type )
			            ->first()
			            ->delete();
			if ( $bool ) {
				return [ 'message' => $resource_type . '_unliked' ];
			}
		}

		return [ 'message' => 'invalid_parameters' ];

	}

	public function commentItem( $resource_id, $resource_type, $poster_id, $poster_type, $body ) {
		if ( empty( $resource_id ) || empty( $resource_type ) || empty( $poster_id ) || empty( $poster_type ) || empty( $body ) ) {
			return [ 'message' => 'invalid_parameters' ];
		}
		if ( $resource_type == 'album_photo' ) {

		}

		$commentObj = new comment();

		$commentObj->resource_type = $resource_type;
		$commentObj->resource_id   = $resource_id;
		$commentObj->poster_id     = $poster_id;
		$commentObj->poster_type   = $poster_type;
		$commentObj->body          = $body;

		if ( $commentObj->save() ) {
			return true;
		}

		return false;

	}

	public function addActivityDislike( $activity_action_id, $poster_id, $poster_type,$return_liked = FALSE ) {

		if ( empty( $activity_action_id ) || empty( $poster_id ) || empty( $poster_type ) ) {
			return [ 'message' => 'invalid_parameters' ];
		}

		$activity = ActivityAction::where( 'action_id', $activity_action_id )->select( [
			'action_id',
			'subject_id'
		] )->first();

		if ( empty( $activity->action_id ) ) {
			return [ 'message' => 'invalid_activity_id' ];
		}

		$count = ActivityDislike::where( 'resource_id', $activity_action_id )
		                        ->where( 'poster_type', $poster_type )
		                        ->where( 'poster_id', $poster_id )
		                        ->count();

		if ( $count > 0 ) {
			return [ 'message' => 'already_disliked' ];
		}

		if ( $this->isStatusLiked( $activity_action_id, $poster_id, $poster_type ) ) {
			$this->deleteActivityLike( $activity_action_id, $poster_id, $poster_type );

			$this->updateActivityLikesCount( $activity_action_id, 'minus' );
		}

		$bool = $this->updateActivityDislikesCount( $activity_action_id );

		if ( $bool ) {
			$dislike = New ActivityDislike();

			$dislike->resource_id = $activity_action_id;
			$dislike->poster_id   = $poster_id;
			$dislike->poster_type = $poster_type;

			if ( $dislike->save() ) {
				$attributes = array(

					'resource_type' => \Config::get( 'constants_activity.OBJECT_TYPES.USER.NAME' ),
					'resource_id'   => $activity->subject_id,
					'subject_id'    => $poster_id,
					'subject_type'  => $poster_type,
					'object_id'     => $activity_action_id,
					'object_type'   => \Config::get( 'constants_activity.notification.OBJECT_TYPE.NAME' ),
					'type'          => \Config::get( 'constants_activity.notification.OBJECT_TYPE.TYPES.DISLIKE' ),
				);

				\Event::fire( new CreateNotification( $attributes ) );

				$message = [ 'message' => 'status_disliked' ];
                if($return_liked){
                    $likes = $this->getActivityLikes($activity_action_id,$poster_id);
                    $message['likes'] = $likes['likes'];
                }
                return $message;
			}
		}

		return [ 'message' => 'error_status_disliking' ];

	}

	public function isStatusLiked( $resource_id, $poster_id, $poster_type ) {
		return ActivityLike::where( 'resource_id', $resource_id )
		                   ->where( 'poster_id', $poster_id )
		                   ->where( 'poster_type', $poster_type )
		                   ->count();
	}

	public function undoDislike( $activity_action_id, $poster_id, $poster_type,$return_liked = FALSE ) {

		if ( empty( $activity_action_id ) || empty( $poster_id ) || empty( $poster_type ) ) {
		    if($this->is_api){
		        return \Api::invalid_param();
            }
			return [ 'message' => 'invalid_parameters' ];
		}

		$bool = $this->deleteActivityDislike( $activity_action_id, $poster_id, $poster_type );

		if ( $bool && $this->updateActivityDislikesCount( $activity_action_id, 'minus' ) ) {

			$message = [ 'message' => 'undone_unlike' ];

            if($return_liked){
                $likes = $this->getActivityLikes($activity_action_id,$poster_id);
                $message['likes'] = $likes['likes'];
            }
            if($this->is_api){
                \Api::success_with_message('Undone dislike');
            }
            return $message;
		}
        if($this->is_api){
            \Api::other_error('Error undo dislike');
        }
		return [ 'message' => 'error_undo_unlike' ];

	}

	public function saveCoverPhoto( $user_id, &$image_str,$group_id = NULL ) {
		if ( empty($user_id) || empty($image_str) ) {
			return [ 'message' => 'invalid_parameters' ];
		}

		if(!empty($group_id)){
			$group = Group::where('id',$group_id)
			              ->where('creator_id',$this->user_id)
			              ->select(['id','cover_photo_id'])
			              ->first();
		}else{
			$user = User::where( 'id', $this->user_id )
							->select( [ 'id', 'cover_photo_id' ] )
							->first();
		}

		if(empty($group->id) && empty($user->id)){
			return ['message' => 'access_denied'];
		}

		$smObj = new StorageManager();

		$image = Image::make($image_str);
        $image->stream();
        $string = $image->__toString();
        $file_name = $smObj->getFilename('jpg');
		$path = $user_id . '/' . $file_name;
		$bool = $smObj->saveFile( 'photos/' . $path, $string, 1);

		if ( $bool ) {
			$sfObj = new StorageFile();
			$sfObj->user_id = $user_id;
			$sfObj->storage_path = $path;
			$sfObj->extension = 'jpg';
			$sfObj->type = 'cover_user';
			$sfObj->name = 'cover_photo';
			$sfObj->mime_type = 'image/jpeg';
			$sfObj->size = $smObj->getFileSize('photos/' . $path);
			$sfObj->width = $image->width();
			$sfObj->height = $image->height();
			$sfObj->hash = sha1($smObj->getFileByPath('photos/' . $path));

			$sfObj->save();

			if(!empty($group_id))
			{
				$group->cover_photo_id = $sfObj->file_id;
				$group->save();
			}else{
				$user->cover_photo_id = $sfObj->file_id;
				$user->save();
				$options = array(
					'type' => \Config::get('constants_activity.OBJECT_TYPES.PHOTO.ACTIONS.UPDATE_COVER_PHOTO'),
					'subject_id' => $user_id,
					'subject_type' => 'user',
					'object_id' => @$sfObj->file_id,
					'object_type' => \Config::get('constants_activity.OBJECT_TYPES.PHOTO.NAME'),
					'body' => \Config::get('constants_activity.ACTIVITY_LOG_MESSAGE.cover_photo_update')
				);

				$this->saveActivity($options);
			}

            $image->destroy();

			return [
				'path'    => \Config::get( 'constants_activity.PHOTO_URL' ) . $sfObj->storage_path.'?type=' . urlencode( $sfObj->mime_type ),
				'message' => 'photo_changed',
                'status'  => 'success'
			];
		}

		return ['status' => 'error'];

	}
	public function flagActivity($user_id,$action_id,$category,$text)
	{
		if(empty($user_id) || empty($action_id))
		{
			if($this->is_api){
				return \Api::invalid_param();
			}
			return ['message' => 'invalid_parameters'];
		}
		$count = ActivityAction::where('action_id',$action_id)
								->count();

		if($count < 1)
		{
			if($this->is_api){
				return \Api::detail_not_found();
			}
			return ['message' => 'invalid_activity_id'];
		}

		$already = Report::where('action_id',$action_id)->where('user_id',$user_id)->count();
		if($already){
			if($this->is_api){
				return \Api::other_error('Already reported');
			}
			return ['message' => 'already_reported'];
		}

		$report = new Report();

		$report->user_id = $user_id;
		$report->action_id = $action_id;
		$report->category = $category;
		$report->description = $text;

		if($report->save())
		{
			if($this->is_api){
				return \Api::success_with_message('Status flagged');
			}
			return ['message' => 'status_flaged'];
		}
		if($this->is_api){
			return \Api::other_error('Error occurred');
		}
		return ['message' => 'error_saving'];
	}
	public function getPopupPhotos($photo_id){
		if(empty($photo_id)){
			return ['message' => 'invalid_parameters'];
		}
		$photos = AlbumPhoto::where('parent_id',$photo_id)
								->orWhere('photo_id',$photo_id)
								->select('photo_id','file_id')
								->get();
		$temp = [];
		foreach ( $photos as $key => $value ) {

			$file = StorageFile::where( 'type', 'popup_photo' )
				->where('parent_type','album_photo')
				->where('parent_id',@$value->photo_id)
				->select(['storage_path','file_id','mime_type'])
				->first();
			if(empty($file->file_id)) {
				$file = StorageFile::where('file_id', @$value->file_id)->first();
			}

			$path    = isset( $file->storage_path ) ? $file->storage_path : null;

			if ( ! empty( $path ) ) {
				$temp['object_photo_path'][] = \Config::get( 'constants_activity.PHOTO_URL' ) . $path . '?type=' . urlencode( $file->mime_type );
			} else {
				$temp['object_photo_path'][] = '';
			}
		}
		return $temp;

	}
	public function encodeVideo($path,$file_name) {

		$ffmpeg = \FFMpeg\FFMpeg::create( [
			'ffmpeg.binaries'  => env('FFMPEG_PATH','/usr/bin/ffmpeg'),
			'ffprobe.binaries' => env('FFPROBE_PATH','/usr/bin/ffprobe'),
			'timeout' => 3600,
			'ffmpeg.threads'  => 12,
		]);

		$video = $ffmpeg->open( $path.$file_name );
		$video
			->filters()
			->resize( new \FFMpeg\Coordinate\Dimension( 853, 480 ),\FFMpeg\Filters\Video\ResizeFilter::RESIZEMODE_INSET,true )
            ->framerate(new \FFMpeg\Coordinate\Framerate(24),24)
			->synchronize();

		$name = str_replace('.','_',time().uniqid(30,TRUE));
		$img = $name.'.jpg';
		$video
			->frame( \FFMpeg\Coordinate\TimeCode::fromSeconds( 2 ) )
		 	->save(public_path('storage/'.$img));

		$format = new \FFMpeg\Format\Video\X264();

		$format->setAudioCodec("libfdk_aac");
        $format->setAudioKiloBitrate(48);
        $format->setAudioChannels(2);

		$video_name = $name.'.mp4';

		$video->save($format,public_path('storage/'.$name.'.mp4'));

		return ['video' => $video_name,'image' => $img];
	}
	public function encodeAudio($path,$file_name)
	{
		$ffmpeg = \FFMpeg\FFMpeg::create([
            'ffmpeg.binaries'  => env('FFMPEG_PATH','/usr/bin/ffmpeg'),
            'ffprobe.binaries' => env('FFPROBE_PATH','/usr/bin/ffprobe'),
		]);

		$audio = $ffmpeg->open($path.$file_name);

		$format = new \FFMpeg\Format\Audio\Mp3();

		$format
		    -> setAudioChannels(2)
		    -> setAudioKiloBitrate(128);

		$name = str_replace('.','_',time().uniqid(30,TRUE)).'.mp3';
		$path = public_path('storage/'.$name);
		$audio->save($format,$path);

		return $path;
	}
	public function all_activity( $user_id ) {
		return ActivityAction::whereSubjectId( $user_id )
		                     ->orderBy( 'action_id', 'DESC' )
			// ->with('activity_comment')
			//->with('activity_likes')
			// ->with('activity_favourite')
			//->with('activity_dislike')
			//->with('user')
			                 ->paginate( 10 );
	}

	public function count_all_activity( $user_id ) {
		return ActivityAction::whereSubjectId( $user_id )
			// ->with('activity_comment')
			//->with('activity_likes')
			// ->with('activity_favourite')
			//->with('activity_dislike')
			//->with('user')
			                 ->count();
	}

	function getLinkType($url) {
		if (strpos($url, 'youtube') > 0) {
			return 'youtube';
		} elseif (strpos($url, 'vimeo') > 0) {
			return 'vimeo';
		} else {
			return 'unknown';
		}
	}

	function get_youtube_vid($link){

		$regexstr = '~
			# Match Youtube link and embed code
			(?:				 				# Group to match embed codes
				(?:&lt;iframe [^&gt;]*src=")?	 	# If iframe match up to first quote of src
				|(?:				 		# Group to match if older embed
					(?:&lt;object .*&gt;)?		# Match opening Object tag
					(?:&lt;param .*&lt;/param&gt;)*  # Match all param tags
					(?:&lt;embed [^&gt;]*src=")?  # Match embed tag to the first quote of src
				)?				 			# End older embed code group
			)?				 				# End embed code groups
			(?:				 				# Group youtube url
				https?:\/\/		         	# Either http or https
				(?:[\w]+\.)*		        # Optional subdomains
				(?:               	        # Group host alternatives.
				youtu\.be/      	        # Either youtu.be,
				| youtube\.com		 		# or youtube.com
				| youtube-nocookie\.com	 	# or youtube-nocookie.com
				)				 			# End Host Group
				(?:\S*[^\w\-\s])?       	# Extra stuff up to VIDEO_ID
				([\w\-]{11})		        # $1: VIDEO_ID is numeric
				[^\s]*			 			# Not a space
			)				 				# End group
			"?				 				# Match end quote if part of src
			(?:[^&gt;]*&gt;)?			 			# Match any extra stuff up to close brace
			(?:				 				# Group to match last embed code
				&lt;/iframe&gt;		         	# Match the end of the iframe
				|&lt;/embed&gt;&lt;/object&gt;	        # or Match the end of the older embed
			)?				 				# End Group of last bit of embed code
			~ix';

		preg_match($regexstr, $link, $matches);

		return $matches[1];

	}

	function get_vimeo_vid($link){

		$rgx = 'https?:\/\/(?:www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|ondemand\/|ondemand\/([^\/]*)\/|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)';
		$regexstr = '~
			# Match Vimeo link and embed code
			(?:&lt;iframe [^&gt;]*src=")?		# If iframe match up to first quote of src
			(?:							# Group vimeo url
				https?:\/\/				# Either http or https
				(?:[\w]+\.)*			# Optional subdomains
				vimeo\.com				# Match vimeo.com
				(?:[\/\w]*\/videos?)?	# Optional video sub directory this handles groups links also
				\/						# Slash before Id
				([0-9]+)				# $1: VIDEO_ID is numeric
				[^\s]*					# Not a space
			)							# End group
			"?							# Match end quote if part of src
			(?:[^&gt;]*&gt;&lt;/iframe&gt;)?		# Match the end of the iframe
			(?:&lt;p&gt;.*&lt;/p&gt;)?		        # Match any title information stuff
			~ix';

		preg_match($rgx, $link, $matches);

		return $matches[1];
	}
	public function getPostLikes($post_id){
		$likes = ActivityLike::where('resource_id',$post_id)
						->select(['resource_id','poster_id'])
						->with('liker')
						->get();
		foreach ($likes as $like){
            $like->liker->first_name = ucfirst($like->liker->first_name);
            $like->liker->displayname = ucfirst($like->liker->displayname);
			$like->photo_url = Kinnect2::getPhotoUrl($like->liker->photo_id, $like->liker->id, 'user', 'thumb_icon');
		}
		return $likes;
	}
	public function getPostDislikes($post_id){
		$likes = ActivityDislike::where('resource_id',$post_id)
			->select(['resource_id','poster_id'])
			->with('disliker')
			->get();

		foreach ($likes as $like){
            $like->disliker->first_name = ucfirst($like->disliker->first_name);
            $like->disliker->displayname = ucfirst($like->disliker->displayname);
			$like->photo_url = Kinnect2::getPhotoUrl($like->disliker->photo_id, $like->disliker->id, 'user', 'thumb_icon');
		}
		return $likes;
	}

	public function getEditPost($post_id){
		$post = ActivityAction::where('action_id',$post_id)->first();
		$data['post'] = $post;
		if($post->type == 'album_photo_new'){
			$photos = $this->getEditPhotoMetaInfo($post);
			$data['photos'] = $photos;
		}
		return $data;
	}
	public function getEditPhotoMetaInfo(&$post){
		$photo_id = $post->object_id;
		$parent_ids =AlbumPhoto::where(function($query) use ($photo_id){
											$query->where('photo_id',$photo_id);
											$query->orWhere('parent_id',$photo_id);
										})->lists('photo_id','photo_id');
		$files = StorageFile::where('parent_type','album_photo')
						->where('type','time_line_thumb')
						->whereIn('parent_id',$parent_ids)
						->get();
		$photos = [];
		foreach ($files as $file){
			$temp['uploadName'] = $file->name;
			$temp['status'] = true;
			$temp['progress_id'] = $file->file_id.time();
			$temp['path'] = \Config::get( 'constants_activity.PHOTO_URL' ) . $file->storage_path . '?type=' . urlencode( $file->mime_type );
			$temp['type'] = 'photo';
			$temp['token'] = $file->parent_file_id;
			$temp['file_type'] = 'old';
			$photos[] = $temp;
		}
		return $photos;
	}

	public function deleteToken($token,$user_id)
	{
		$file = StorageFile::where('file_id',$token)
							->where('user_id',$user_id)
							->first();
		if(!empty($file->file_id) && $this->file_exists('photos/'.$file->storage_path)){
			$this->deletePath('photos/'.$file->storage_path);
			$file->delete();
			return ['message' => 'token_deleted'];
		}
		return ['message' => 'invalid_token'];
	}
	protected function deletePath($path){
		$sm = new StorageManager();
		return $sm->deletFile($path);
	}
}