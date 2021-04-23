<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : local
 * Product Name : PhpStorm
 * Date         : 08-12-15 11:39 AM
 * File Name    : SkoreRepository.php
 */

namespace App\Repository\Eloquent;


use App\User;

class SkoreRepository extends Repository {
	public function __construct(  ) {
		parent::__construct();
	}

	protected $skors = [
		'comment' => 10,
		'like' => 5,
		'un_like' => -5,
		'unlike'=> 5,
		'upload_picture'=> 25,
		'upload_video'=> 25,
		'upload_audio'=> 25,
		'flagging_reason'=> 5,
		'receiving_like' => 10,
		'receiving_unlike' => -10,
		'post_flagged' => -5,
		'brand_follow' => 5,
		'activity_like' => 5,
		'activity_remove_like' => -5,
		'add_friend' => 10
	];

	public function get_skore( $type ) {
		return $this->skors[$type];
	}

	public function update_skore( $type, $user_id ) {

		$skore = $this->get_skore($type);

		$user = User::find($user_id);
		if(empty($user->id)){
			return FALSE;
		}
		$user->increment('skore', $skore);



	}
}
