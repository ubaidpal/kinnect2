<?php
namespace App\Http\Controllers;
use App\ActivityAction;
use App\ActivityComment;
use App\ActivityDislike;
use App\ActivityFavourite;
use App\ActivityLike;
use App\Album;
use App\AlbumPhoto;
use App\Battle;
use App\BattleOption;
use App\BattleVote;
use App\Brand;
use App\BrandMembership;
use App\Consumer;
use App\Conversation;
use App\ConversationUser;
use App\Group;
use App\GroupMembership;
use App\Message;
use App\MessageStatus;
use App\Link;
use App\PollVote;
use App\StorageFile;
use App\User;
use App\Poll;
use App\PollOption;
use App\UserMembership;
use App\Video;
use Config;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use Image;
use App\Repository\Eloquent\ActivityActionRepository;
use Mockery\CountValidator\Exception;
use App\Repository\Eloquent\SettingRepository;

class migrationsController extends Controller
{
	public function Albums(  ) {
	ini_set('max_execution_time', 180);
	$albums = DB::connection('k2_live')->table('engine4_albums_albums')
	            ->get();
	foreach($albums as $info) {
		$album = new Album();

		$album->album_id = $info->album_id;
		$album->title = $info->title;
		$album->description= $info->description;
		$album->owner_type= $info->owner_type;
		$album->owner_id= $info->owner_id;
		$album->category_id= $info->category_id;
		$album->created_at= $info->creation_date;
		$album->updated_date= $info->modified_date;
		$album->photo_id= $info->photo_id;
		$album->view_count= $info->view_count;
		$album->comment_count= $info->comment_count;
		$album->search= $info->search;
		$album->type= $info->type;
		$album->he_featured= $info->he_featured;

		$album->save();
	}
	dd('<h1>All albums are migrated..</h1>h1>');
}

	public function messagesConversations(  ) {
		ini_set('max_execution_time', 180);
		$conversations = DB::table('engine4_messages_conversations')
		                  ->get();
		foreach($conversations as $info) {

			//add new message conversation
			$conversation = new Conversation();

			$conversation->id         = $info->conversation_id;

			if($info->recipients > 1){
				$conversation->type       = "group";
			}else{
				$conversation->type       = "couple";
			}

			$conversation->created_by = $info->user_id;
			$conversation->title      = '';

			$conversation->save();
		}
		dd('Dude all conversations are done');
	}

	public function atLastAmmendAccordingNewSystem() {

		$messages = DB::table('messages')
		    ->select('id', 'conv_id')
			->get();

		foreach($messages as $info) {

			$msgz = MessageStatus::where('msg_id', $info->id)->get();

			if(count($msgz) == 1){

				$allUsersOfConversation = ConversationUser::where('conv_id', $info->conv_id)->get();

				foreach($allUsersOfConversation as $addMsgUser){

					if($msgz[0]->user_id != $addMsgUser->user_id){

						$RmessageStatus = new MessageStatus();

						$RmessageStatus->user_id = $addMsgUser->user_id;
						$RmessageStatus->msg_id  = $msgz[0]->msg_id;

						$RmessageStatus->self = 0;
						$RmessageStatus->status  = 1;

						$RmessageStatus->save();

					}
				}
			}
		}
	}

	public function messagesMessages() {
		ini_set('max_execution_time', 180);
		$messages = DB::table('engine4_messages_messages')
		                   ->get();
		foreach($messages as $info) {

			//Add new message
			$message = new Message();

			$message->id = $info->message_id;
			$message->sender_id = $info->user_id;
			$message->conv_id = $info->conversation_id;
			$message->content = $info->body;

			$message->save();

			//========== Message Status =====================================

			$recipientsMessage = DB::table('engine4_messages_recipients')
							->where('inbox_message_id', $info->message_id)
			                ->get();

			foreach($recipientsMessage as $info2)
			{
				if(!isset($info2->inbox_message_id)){continue;}
				$RmessageStatus = new MessageStatus();

				$RmessageStatus->user_id = $info2->user_id;
				$RmessageStatus->msg_id  = $info2->inbox_message_id;

				$RmessageStatus->self = 0;
				$RmessageStatus->status  = 1;

				$RmessageStatus->save();
			}

			$messageStatus = new MessageStatus();

			$messageStatus->user_id = $info->user_id;
			$messageStatus->msg_id  = $message->id;

			$isOwner = Conversation::where('id', $info->conversation_id)->first();

			$messageStatus->self = 0;
			$messageStatus->status  = 1;

			if(isset($isOwner->created_by)){
				if($isOwner->created_by == $info->user_id){//owner then
					$messageStatus->self = 1;
					$messageStatus->status  = 2;

				}
			}

			$messageStatus->save();

			//========== end of Message Status =====================================

		}
		dd('Dude all messages are done');
	}

	public function messagesRecipients(  ) {
		ini_set('max_execution_time', 180);
		$recipients = DB::table('engine4_messages_recipients')
		              ->get();
		foreach($recipients as $info) {

	//========= Add new conversation user
			$conversationUser = new ConversationUser();

			$conversationUser->conv_id   = $info->conversation_id;
			$conversationUser->user_id = $info->user_id;

			$conversationUser->save();

		}
		dd('Dude all recipients are done');
	}
	public function AlbumsPhotos(  ) {
		ini_set('max_execution_time', 180);
		DB::connection('mysql')->table('album_photos')->truncate();
		$albumsPhotos = DB::connection('k2_live')->table('engine4_album_photos')
		            ->get();
		foreach($albumsPhotos as $info) {

			$albumPhoto = new AlbumPhoto();

			$albumPhoto->photo_id = $info->photo_id;
			$albumPhoto->album_id = $info->album_id;
			$albumPhoto->title = $info->title;
			$albumPhoto->description= $info->description;
			$albumPhoto->owner_type= $info->owner_type;
			$albumPhoto->owner_id= $info->owner_id;
			$albumPhoto->created_at= $info->creation_date;
			$albumPhoto->updated_at= $info->modified_date;
			$albumPhoto->file_id= $info->file_id;
			$albumPhoto->view_count= $info->view_count;
			$albumPhoto->comment_count= $info->comment_count;
			$albumPhoto->he_featured= $info->he_featured;
			$albumPhoto->order= $info->order;

			$albumPhoto->save();
		}
		dd('<h1>All albums photos are migrated..</h1>h1>');
	}
	public function groupMembership(  ) {
		ini_set('max_execution_time', 180);
		$groups = DB::connection('k2_live')->table('engine4_group_membership')
		            ->get();
		foreach($groups as $info) {
			$group = new GroupMembership();
			$group->group_id = $info->resource_id;
			$group->user_id = $info->user_id;
			$group->active= $info->active;
			$group->group_owner_approved= $info->resource_approved;
			$group->user_approved= $info->user_approved;
			$group->message= $info->message;
			$group->title= $info->title;
			$group->save();
		}
		dd('<h1>All group members are Well</h1>h1>');
	}
	public function userMembership(  ) {
		ini_set('max_execution_time', 980);
		$users = DB::connection('k2_live')->table('engine4_user_membership')
		            ->get();

		foreach($users as $key=> $info) {

			$userI  = User::find($info->user_id);
			if(!empty($userI)) {
				if ($userI->user_type == 1) {
					$user = new UserMembership();
					$user->resource_id = $info->resource_id;
					$user->user_id = $info->user_id;
					$user->active = $info->active;
					$user->resource_approved = $info->resource_approved;
					$user->user_approved = $info->user_approved;
					$user->message = $info->message;
					$user->description = $info->description;
					$user->save();

					/*$user = new UserMembership();
					$user->resource_id =$info->user_id;
					$user->user_id = $info->resource_id;
					$user->active = $info->active;
					$user->resource_approved = $info->user_approved;
					$user->user_approved = $info->resource_id;
					$user->message = $info->message;
					$user->description = $info->description;
					$user->save();*/


				} else {
					$user = new BrandMembership();
					$user->active = $info->active;
					$user->brand_approved = $info->resource_approved;
					$user->user_id = $info->resource_id;
					$user->brand_id = $info->user_id;
					$user->user_approved = $info->user_approved;
					$user->save();
				}
				echo 'Row '.$key.'Complete <br>';
			}
		}
		dd('<h1>All users members are Well</h1>h1>');
	}

	public function groupsExport(  ) {
		ini_set('max_execution_time', 180);
		$groups = DB::connection('k2_live')->table('engine4_group_groups')
		            ->get();
		foreach($groups as $info) {
			$group = new Group();
			$group->id = $info->group_id;
			$group->creator_id = $info->user_id;
			$group->title= $info->title;
			$group->description= $info->description;
			$group->category_id= $info->category_id;
			$group->search= $info->search;
			$group->members_can_invite= $info->invite;
			$group->approval_required= $info->approval;
			$group->photo_id= $info->photo_id;
			$group->cover_photo_id= $info->cover_photo_id;
			$group->member_count= $info->user_id;
			$group->view_count= $info->user_id;
			$group->created_at= $info->creation_date;
			$group->updated_at= $info->modified_date;
			$group->save();
		}
		dd('<h1>All is Well</h1>h1>');
	}
	public function usersExport() {
		ini_set('max_execution_time', 180);
		//DB::connection('k2_live');
		// There will be default photo into Album_photos, and storage files, default photo_id is 1 for every user that have no photo selected.
		$users = DB::connection('k2_live')->table('engine4_users')
//		           ->join('engine4_user_fields_values', 'engine4_users.user_id', '=', 'engine4_user_fields_values.item_id')
//		           ->select(
//			           'engine4_users.user_id',
//			           'engine4_users.email',
//			           'engine4_user_fields_search.first_name',
//			           'engine4_user_fields_search.last_name',
//			           'engine4_user_fields_search.gender',
//			           'engine4_user_fields_search.birthdate',
//			           'engine4_user_fields_search.about_me'
//			           )
//			->whereIn('engine4_user_fields_values.field_id', [3,4,5,6,8,9,10,13])
                   ->where('engine4_users.level_id', '!=',6)
		           ->where('engine4_users.level_id', '!=',1)
		           ->get();

		foreach($users as $info){
//			if($info->email == 'zaars59208@gmail.com')continue;
			//saving consumer
			$usersFieldValues = DB::connection('k2_live')->table('engine4_user_fields_values')
			                      ->join('engine4_user_fields_meta', 'engine4_user_fields_values.field_id', '=', 'engine4_user_fields_meta.field_id')
			                      ->select('engine4_user_fields_values.value', 'engine4_user_fields_meta.type')
			                      ->where('engine4_user_fields_values.item_id', $info->user_id)
			                      ->whereIn('engine4_user_fields_values.field_id', [3,4,5,6,8,9,10,13,34])
			                      ->get();
			$first_name = '';
			$last_name = '';
			$birthdate= '';
			$website= '';
			$facebook= '';
			$about_me= '';
			$twitter= '';
			$gender= '';
			$country= '';
			foreach($usersFieldValues as $usersFieldValue){
				if($usersFieldValue->type == 'first_name'){
					$first_name = $usersFieldValue->value;
				}
				if($usersFieldValue->type == 'last_name'){
					$last_name = $usersFieldValue->value;
				}
				if($usersFieldValue->type == 'gender'){
					$gender = $usersFieldValue->value;
					if($gender == 2){
						$gender =1;
					}else{
						$gender =2;
					}
				}
				if($usersFieldValue->type == 'birthdate'){
					$birthdate = $usersFieldValue->value;
				}
				if($usersFieldValue->type == 'website'){
					$website = $usersFieldValue->value;
				}
				if($usersFieldValue->type == 'twitter'){
					$twitter = $usersFieldValue->value;
				}
				if($usersFieldValue->type == 'facebook'){
					$facebook = $usersFieldValue->value;
				}
				if($usersFieldValue->type == 'about_me'){
					$about_me = $usersFieldValue->value;
				}
				if($usersFieldValue->type == 'country'){

					$country = $usersFieldValue->value;

					$countryInfo = DB::table('countries')->where('iso', $country)->get();
					if(isset($countryInfo[0]->id)){
						$country = $countryInfo[0]->id;
					}
				}
			}
			$consumer = new Consumer();
			$consumer->gender = $gender;
			$consumer->birthdate = $birthdate;
			$consumer->about_me = $about_me;
			$consumer->personnel_info = $about_me;
			if($consumer->save()){
				//saving user
				$user = new User;
				$user->id = $info->user_id;
				$user->name = $first_name. ' ' . $last_name;
				$user->email = $info->email;
				$user->photo_id = $info->photo_id;
				$user->cover_photo_id = $info->cover_photo_id;
				$user->mood = $info->status;
				$user->mode_date = $info->status_date;
				$user->locale = $info->locale;
				$user->language = $info->language;
				$user->timezone = $info->timezone;
				$user->search = $info->search;
				$user->show_profileviewers = $info->show_profileviewers;
				$user->invites_used = $info->invites_used;
				$user->enabled     =$info->enabled;
				$user->verified = $info->verified;
				$user->active =1;
				$user->approved =$info->approved;
				$user->creation_ip =$info->creation_ip;
				$lastlogin_date = ($info->lastlogin_date!='')?$info->lastlogin_date:'0000-00-00';
				$user->lastlogin_date=$lastlogin_date;
				$lastlogin_ip = ($info->lastlogin_ip!='')?$info->lastlogin_ip:'000000';
				$user->lastlogin_ip=$lastlogin_ip;
				$user->skore=$info->skore;
				$user->member_count=$info->member_count;
				$user->view_count =$info->view_count;
				$user->first_name = $first_name;
				$user->last_name = $last_name;
				$user->user_type = '1';
				$user->displayname = $first_name . ' ' . $last_name;
				$user->username = $info->username;
				$user->salt = $info->salt;
				$user->website = $website;
				$user->facebook = $facebook;
				$user->twitter = $twitter;
				$user->country = $country;
				$user->userable_id = $consumer->id;
				$user->userable_type = 'App\Consumer';
				$user->save();

				DB::table('user_passwords')->insert(
					array(
						'email' => $info->email,
						'password' => $info->password,
					)
				);
			}else{
				dd('something gone wrong while importing consumers, try again..');
			}
		}
		dd('all is well?');
	}
	public function usersBrandsExport() {
		ini_set('max_execution_time', 180);
		// There will be default photo into Album_photos, and storage files, default photo_id is 1 for every user that have no photo selected.
		$users = DB::connection('k2_live')->table('engine4_users')
//		           ->join('engine4_user_fields_values', 'engine4_users.user_id', '=', 'engine4_user_fields_values.item_id')
//		           ->select(
//			           'engine4_users.user_id',
//			           'engine4_users.email',
//			           'engine4_user_fields_search.first_name',
//			           'engine4_user_fields_search.last_name',
//			           'engine4_user_fields_search.gender',
//			           'engine4_user_fields_search.birthdate',
//			           'engine4_user_fields_search.about_me'
//			           )
//			->whereIn('engine4_user_fields_values.field_id', [3,4,5,6,8,9,10,13])
                   ->where('engine4_users.level_id', '!=',4)
		           ->where('engine4_users.level_id', '!=',1)
		           ->get();
		foreach($users as $info){
			//saving consumer
			$usersFieldValues = DB::connection('k2_live')->table('engine4_user_fields_values')
			                      ->join('engine4_user_fields_meta', 'engine4_user_fields_values.field_id', '=', 'engine4_user_fields_meta.field_id')
			                      ->select('engine4_user_fields_values.value', 'engine4_user_fields_meta.type')
			                      ->where('engine4_user_fields_values.item_id', $info->user_id)
			                      ->whereIn('engine4_user_fields_values.field_id', [23,24,25,28,29,30,35])
			                      ->get();
			$brandName = '';
			$brandDescription = '';
			$website= '';
			$facebook= '';
			$about_me= '';
			$twitter= '';
			$country= '';
			foreach($usersFieldValues as $usersFieldValue){
				if($usersFieldValue->type == 'text'){
					$brandName = $usersFieldValue->value;
				}
				if($usersFieldValue->type == 'textarea'){
					$brandDescription = $usersFieldValue->value;
				}
				if($usersFieldValue->type == 'website'){
					$website = $usersFieldValue->value;
				}
				if($usersFieldValue->type == 'twitter'){
					$twitter = $usersFieldValue->value;
				}
				if($usersFieldValue->type == 'facebook'){
					$facebook = $usersFieldValue->value;
				}
				if($usersFieldValue->type == 'about_me'){
					$about_me = $usersFieldValue->value;
				}
				if($usersFieldValue->type == 'country'){
					$country = $usersFieldValue->value;

					$countryInfo = DB::table('countries')->where('iso', $country)->get();
					if(isset($countryInfo[0]->id)){
						$country = $countryInfo[0]->id;
					}
				}
			}
			$brand = new Brand();
			$brand->brand_name = $info->username;
			$brand->brand_history = $about_me;
			$brand->description = $brandDescription;
			if($brand->save()){
				//saving user
				$user = new User;
				$user->id = $info->user_id;
				$user->name = $brandName;
				$user->email = $info->email;
				$user->photo_id = $info->photo_id;
				$user->cover_photo_id = $info->cover_photo_id;
				$user->mood = $info->status;
				$user->mode_date = $info->status_date;
				$user->locale = $info->locale;
				$user->language = $info->language;
				$user->timezone = $info->timezone;
				$user->search = $info->search;
				$user->show_profileviewers = $info->show_profileviewers;
				$user->invites_used = $info->invites_used;
				$user->active =1;
				$user->enabled     =$info->enabled;
				$user->verified = $info->verified;
				$user->approved =$info->approved;
				$user->creation_ip =$info->creation_ip;
				$lastlogin_date = ($info->lastlogin_date!='')?$info->lastlogin_date:'0000-00-00';
				$user->lastlogin_date=$lastlogin_date;
				$lastlogin_ip = ($info->lastlogin_ip!='')?$info->lastlogin_ip:'000000';
				$user->lastlogin_ip=$lastlogin_ip;
				$user->skore=$info->skore;
				$user->member_count=$info->member_count;
				$user->view_count =$info->view_count;
				$user->first_name = $brandName;
				$user->last_name = '';
				$user->user_type = '2';
				$user->displayname = $info->displayname;
				$user->username = $info->username;
				$user->salt = $info->salt;
				$user->website = $website;
				$user->facebook = $facebook;
				$user->twitter = $twitter;
				$user->country = $country;
				$user->userable_id = $brand->id;
				$user->userable_type = 'App\Brand';
				$user->save();

				DB::table('user_passwords')->insert(
					array(
						'email' => $info->email,
						'password' => $info->password,
					)
				);

			}else{
				dd('something gone wrong while importing brands, try again..');
			}
		}
		dd('all is well?');
	}

	public function getFilesOfUser() {
		ini_set('max_execution_time', 180);

		$ownersIds = DB::connection('k2_live')->table('engine4_storage_files')
			->selectRaw('DISTINCT user_id')->get();

		foreach($ownersIds as $ownersId)
		{
			if($ownersId->user_id > 0){
				$thisOwnerFiles = DB::connection('k2_live')->table('engine4_storage_files')->where('user_id', $ownersId->user_id )->get();

				foreach($thisOwnerFiles as $thisOwnerFile)
				{
					$awienHePath = base_path();

					$awienHePathFile = $awienHePath . $thisOwnerFile->storage_path;

					if($thisOwnerFile->mime_minor == 'png'|| $thisOwnerFile->mime_minor == 'gif' || $thisOwnerFile->mime_minor == 'jpeg' || $thisOwnerFile->extension == '.png' || $thisOwnerFile->extension == 'png' ||  $thisOwnerFile->extension == 'jpg' ||  $thisOwnerFile->extension == '.jpg' ||  $thisOwnerFile->extension == '.jpeg' || $thisOwnerFile->extension == 'gif' || $thisOwnerFile->extension == '.gif') {
						if($thisOwnerFile->extension != ''){
							if (strpos($thisOwnerFile->extension, '.') !== false) {
								$extension = $thisOwnerFile->extension;
							}else{
								$extension = '.'.$thisOwnerFile->extension;
							}
						}else{
							$extension = '.'.$thisOwnerFile->mime_minor;
						}

						$file_name = time().rand(111111111,9999999999);

						$folder_path = "local/storage/app/photos/".$thisOwnerFile->user_id;
						$file_name = $thisOwnerFile->user_id."_".$file_name.$extension;
						if ( ! file_exists( $folder_path ) ) {
							if ( ! mkdir( $folder_path, 0777, true ) ) {
								$folder_path = '';
							}
						}


						if(file_exists($awienHePathFile) == true){
							copy($awienHePathFile,  $folder_path."/".$file_name);

							$stF = new StorageFile();

							$stF->file_id =  $thisOwnerFile->file_id;
							$stF->parent_file_id =  $thisOwnerFile->parent_file_id;
							$stF->type=  $thisOwnerFile->type;
							$stF->parent_type=  $thisOwnerFile->parent_type;
							$stF->parent_id=  $thisOwnerFile->parent_id;
							$stF->user_id=  $thisOwnerFile->user_id;
							$stF->created_at=  $thisOwnerFile->creation_date;
							$stF->updated_at=  $thisOwnerFile->modified_date;
							$stF->storage_path= $thisOwnerFile->user_id."/".$file_name;
							$stF->extension=  $thisOwnerFile->extension;
							$stF->name     =   $file_name;
							$stF->mime_type=  $thisOwnerFile->mime_minor.'/'.$thisOwnerFile->mime_major;
//							$stF->mime_major=  $thisOwnerFile->mime_major;
							$stF->size=  $thisOwnerFile->size;
							$stF->hash=  $thisOwnerFile->hash;
							$stF->is_temp=  0;

							$stF->save();
						}					}

					if($thisOwnerFile->extension == 'mp3') {
						$file_name = time().rand(111111111,9999999999);

						$folder_path = "local/storage/app/audios/".$thisOwnerFile->user_id;
						$file_name = $thisOwnerFile->user_id."_".$file_name.".".$thisOwnerFile->extension;
						if ( ! file_exists( $folder_path ) ) {
							if ( ! mkdir( $folder_path, 0777, true ) ) {
								$folder_path = '';
							}
						}

						if(file_exists($awienHePathFile)){
							copy($awienHePathFile,  $folder_path."/".$file_name);

							$stF = new StorageFile();

							$stF->file_id =  $thisOwnerFile->file_id;
							$stF->parent_file_id =  $thisOwnerFile->parent_file_id;
							$stF->type=  $thisOwnerFile->type;
							$stF->parent_type=  $thisOwnerFile->parent_type;
							$stF->parent_id=  $thisOwnerFile->parent_id;
							$stF->user_id=  $thisOwnerFile->user_id;
							$stF->created_at=  $thisOwnerFile->creation_date;
							$stF->updated_at=  $thisOwnerFile->modified_date;
							$stF->storage_path= $thisOwnerFile->user_id."/".$file_name;
							$stF->extension=  $thisOwnerFile->extension;
							$stF->name     =   $file_name;
							$stF->mime_type=  $thisOwnerFile->mime_minor;
							$stF->mime_major=  $thisOwnerFile->mime_major;
							$stF->size=  $thisOwnerFile->size;
							$stF->hash=  $thisOwnerFile->hash;
							$stF->is_temp=  0;

							$stF->save();
						}					}

					if($thisOwnerFile->extension == 'avi' ||  $thisOwnerFile->extension == 'mp4' || $thisOwnerFile->extension == 'flv') {
						$file_name = time().rand(111111111,9999999999);
						$folder_path = "local/storage/app/videos/".$thisOwnerFile->user_id;
						$file_name = $thisOwnerFile->user_id."_".$file_name.".".$thisOwnerFile->extension;
						if ( ! file_exists( $folder_path ) ) {
							if ( ! mkdir( $folder_path, 0777, true ) ) {
								$folder_path = '';
							}
						}
						if(file_exists($awienHePathFile)){

							copy($awienHePathFile,  $folder_path."/".$file_name);

							$stF = new StorageFile();

							$stF->file_id =  $thisOwnerFile->file_id;
							$stF->parent_file_id =  $thisOwnerFile->parent_file_id;
							$stF->type=  $thisOwnerFile->type;
							$stF->parent_type=  $thisOwnerFile->parent_type;
							$stF->parent_id=  $thisOwnerFile->parent_id;
							$stF->user_id=  $thisOwnerFile->user_id;
							$stF->created_at=  $thisOwnerFile->creation_date;
							$stF->updated_at=  $thisOwnerFile->modified_date;
							$stF->storage_path= $thisOwnerFile->user_id."/".$file_name;
							$stF->extension=  $thisOwnerFile->extension;
							$stF->name     =   $file_name;
							$stF->mime_type=  $thisOwnerFile->mime_minor;
							$stF->mime_major=  $thisOwnerFile->mime_major;
							$stF->size=  $thisOwnerFile->size;
							$stF->hash=  $thisOwnerFile->hash;
							$stF->is_temp=  0;

							$stF->save();
						}
					}

				}

			}

		}
		dd('all copied!');

	}
	public function getConnection(){
		return DB::connection('k2_live')->getPdo();
	}
	public  function importActivity(){
		ini_set('max_execution_time',3600);

		DB::connection('mysql')->table('activity_actions')->truncate();
		DB::connection('mysql')->table('activity_comments')->truncate();
		DB::connection('mysql')->table('activity_likes')->truncate();
		DB::connection('mysql')->table('activity_dislikes')->truncate();
		DB::connection('mysql')->table('activity_favorites')->truncate();
		DB::connection('mysql')->table('battles')->truncate();
		DB::connection('mysql')->table('battle_options')->truncate();
		DB::connection('mysql')->table('battle_votes')->truncate();

		DB::connection('mysql')->table('polls')->truncate();
		DB::connection('mysql')->table('poll_options')->truncate();
		DB::connection('mysql')->table('poll_votes')->truncate();

		DB::connection('mysql')->table('videos')->truncate();
		DB::connection('mysql')->table('album_photos')->truncate();
		DB::connection('mysql')->table('links')->truncate();


		$pdo = $this->getConnection();

		$sql = 'SELECT * FROM engine4_activity_actions ORDER BY action_id ASC';

		foreach ($pdo->query($sql) as $row) {
			$temp = $row;
			$temp['created_at'] = $row['date'];

			if($temp['type'] == 'brand_join'){
				$temp['type'] = 'follow';
				$temp['object_type'] = 'brand';
			}
			if($temp['type'] == 'post'){
				$temp['type'] = 'status';
			}

            $aObj = new ActivityAction();
			$aObj->action_id = $temp['action_id'];
            $aObj->type = $temp['type'];
            $aObj->subject_type = $temp['subject_type'];
            $aObj->subject_id = $temp['subject_id'];
            $aObj->body = $temp['body'];
            $aObj->created_at = $temp['date'];
            $aObj->params = $temp['params'];
            $aObj->attachment_count = $temp['attachment_count'];
            $aObj->comment_count = $temp['comment_count'];
            $aObj->like_count = $temp['like_count'];
            $aObj->object_id = $temp['object_id'];
            $aObj->object_type = $temp['object_type'];

			if($temp['type'] == 'post_self' || $temp['type'] == 'profile_photo_update') {
				$my_sql = "SELECT * FROM engine4_activity_attachments WHERE action_id={$row['action_id']}";
				$res = $pdo->query($my_sql)->fetchObject();

				if(empty($res->type)){
					continue;
				}
				if($res->type == 'album_photo'){
					//$photo_id = $this->savePhoto($res->id);
					$aObj->type = 'album_photo';
                    $aObj->object_id = $res->id;
                    $aObj->object_type = 'album_photo';
				}else if($res->type == 'core_link'){
					$link_id = $this->saveLink($res->id);
					$aObj->type = 'link';
                    $aObj->object_id = $link_id;
                    $aObj->object_type = 'link';
				}elseif($res->type == 'video'){
					continue;
					$video_id = $this->saveVideo($res->id);
					$aObj->type = 'video';
					$aObj->object_type = 'video';
					$aObj->object_id = $video_id;
				}elseif($res->type == 'music_playlist_song'){
					$file_id = $this->saveAudio($res->id);
					$aObj->type = 'audio_new';
                    $aObj->object_id = $file_id;
                    $aObj->object_type = 'audio';
				}else{
					echo '<pre>';
					print_r($res);
					exit;
				}
			}elseif($temp['type'] == 'poll_new'){
                $poll_id = $this->savePoll($temp['object_id']);

                $aObj->object_id = $poll_id;

            }elseif($temp['type'] == 'battle_new'){
                $battle_id = $this->saveBattle($temp['object_id']);
                $aObj->object_id = $battle_id;
            }elseif($temp['type'] == 'video_new'){
                $video_id = $this->saveVideo($temp['object_id']);
                $aObj->object_id = $video_id;
            }elseif(($temp['type'] == 'share' || $temp['object_type'] == 'group') && $temp['attachment_count'] > 0){
				$attachment = $this->saveSharedActivity($temp['action_id']);
				$aObj->object_id = $attachment['object_id'];
				$aObj->object_type = $attachment['object_type'];
            }elseif($temp['object_type'] == 'group' && $temp['attachment_count'] < 1){
				$aObj->object_type = $temp['subject_type'];
				$aObj->object_id = $temp['subject_id'];
			}

			if($temp['object_type'] == 'group'){
				$aObj->target_id = $temp['object_id'];
				$aObj->target_type = $temp['object_type'];
			}
			if($temp['type'] == 'status'){
				echo '<pre>';
				print_r($aObj);
				echo '<pre>';
				print_r($temp);
			}
            $aObj->save();

			$this->saveComments($aObj->action_id);
			$this->saveActivityLikes($aObj->action_id);
			$this->saveActivityDisLikes($aObj->action_id);
			$this->saveActivityfav($aObj->action_id);
		}
		exit;
	}
	function saveSharedActivity($action_id){
		$pdo = $this->getConnection();
		$sql = "SELECT * FROM engine4_activity_attachments WHERE action_id=$action_id";

		$row = $pdo->query($sql)->fetchObject();
		if(!empty($row->id)){
			$type = $row->type;
			if($type == 'core_link'){
				$type = 'link';
			}
			return ['object_type' => $type,'object_id' => $row->id];
		}
	}
	function saveComments($action_id){
		$pdo = $this->getConnection();
		$sql = "SELECT * FROM engine4_activity_comments WHERE resource_id=$action_id";
		foreach ($pdo->query($sql)  as $item ) {
			$comObj = new ActivityComment();
			$comObj->comment_id = $item['comment_id'];
			$comObj->resource_id = $action_id;
			$comObj->poster_type = $item['poster_type'];
			$comObj->poster_id = $item['poster_id'];
			$comObj->body = $item['body'];
			$comObj->created_at = $item['creation_date'];
			$comObj->like_count = $item['like_count'];
			$comObj->parent_comment_id = $item['parent_comment_id'];
			$comObj->attachment_type = $item['attachment_type'];
			$comObj->attachment_id = $item['attachment_id'];
			$comObj->params = $item['params'];
			$comObj->save();
		}

	}
	function saveActivityLikes($action_id){
		$pdo = $this->getConnection();
		$sql = "SELECT * FROM engine4_activity_likes WHERE resource_id=$action_id";
		foreach ($pdo->query($sql)  as $item ) {
			$comObj = new ActivityLike();
			$comObj->like_id = $item['like_id'];
			$comObj->resource_id = $action_id;
			$comObj->poster_type = $item['poster_type'];
			$comObj->poster_id = $item['poster_id'];
			$comObj->save();
		}
	}
	function saveActivityDisLikes($action_id){
		$pdo = $this->getConnection();
		$sql = "SELECT * FROM engine4_activity_dislikes WHERE resource_id=$action_id";
		foreach ($pdo->query($sql)  as $item ) {
			$comObj = new ActivityDislike();
			$comObj->dislike_id = $item['dislike_id'];
			$comObj->resource_id = $action_id;
			$comObj->poster_type = $item['poster_type'];
			$comObj->poster_id = $item['poster_id'];
			$comObj->save();
		}
	}
	function saveActivityfav($action_id){
		$pdo = $this->getConnection();
		$sql = "SELECT * FROM engine4_activity_favorites WHERE resource_id=$action_id";
		foreach ($pdo->query($sql)  as $item ) {
			$comObj = new ActivityFavourite();
			$comObj->favorite_id = $item['favorite_id'];
			$comObj->resource_id = $action_id;
			$comObj->poster_type = $item['poster_type'];
			$comObj->poster_id = $item['poster_id'];
			$comObj->save();
		}
	}
    public  function saveGroup($group_id){
        $pdo = $this->getConnection();
        $sql = "SELECT * FROM engine4_group_groups WHERE group_id=$group_id";
        $group = $pdo->query($sql)->fetchObject();


        $gObj = new Group();
        $gObj->creator_id = $group->user_id;
        $gObj->title = $group->title;
        $gObj->description = $group->description;
        $gObj->category_id = $group->category_id;
        $gObj->search = $group->search;
        $gObj->members_can_invite = $group->invite;
        $gObj->approval_required = $group->approval;
        $phot_id = 0;
        if(!empty($group->photo_id)){
           $phot_id = $this->savePhoto($group->photo_id);
        }
        $gObj->photo_id = $phot_id;

        $c_photo_id = 0;
        if(!empty($group->cover_photo_id)){
            $c_photo_id = $this->savePhoto($group->cover_photo_id);
        }
        $gObj->cover_photo_id = $c_photo_id;
        $gObj->created_at = $group->creation_date;
        $gObj->updated_at = $group->modified_date;
        $gObj->member_count = $group->member_count;
        $gObj->view_count = $group->view_count;

        $gObj->save();


    }
    public function saveBattle($battle_id){
        $pdo = $this->getConnection();
        $sql = "SELECT * FROM engine4_battle_battles WHERE battle_id=$battle_id";
        $battle = $pdo->query($sql)->fetchObject();

        $bObj = new Battle();
		$bObj->id = $battle->battle_id;
        $bObj->user_id = $battle->user_id;
        $bObj->title = $battle->title;
        $bObj->description = $battle->description;
        $bObj->starttime = !empty($battle->starttime) ? $battle->starttime : '0000-00-00 00:00:00';
        $bObj->endtime = !empty($battle->endtime) ? $battle->endtime : '0000-00-00 00:00:00';
        $bObj->view_count = $battle->view_count;
        $bObj->created_at = $battle->creation_date;
        $bObj->comment_count = $battle->comment_count;
        $bObj->vote_count = $battle->vote_count;
        $bObj->search = $battle->search;
        $bObj->is_closed = $battle->is_closed;

        $bObj->save();

        $sql = "SELECT * FROM engine4_battle_options WHERE battle_id=$battle_id";

        $options = $pdo->query($sql)->fetchAll();

        foreach($options as $option){
            $optionObj = new BattleOption();
	        $optionObj->id = $option['battle_option_id'];
            $optionObj->battle_id = $bObj->id;
            $optionObj->votes = $option['votes'];
            $optionObj->brand_id = $option['brand_id'];
            $optionObj->save();

            $sql = "SELECT * FROM engine4_battle_votes WHERE battle_option_id={$option['battle_option_id']}";

            $votes = $pdo->query($sql)->fetchAll();

            foreach($votes as $vote){
                $vObj = new BattleVote();
                $vObj->battle_option_id = $optionObj->id;
                $vObj->battle_id = $bObj->id;
                $vObj->user_id = $vote['user_id'];
                $vObj->created_at = $bObj->created_at;
                $vObj->updated_at = $bObj->updated_at;
                $vObj->save();
            }
        }

        return $bObj->id;
    }
    public  function savePoll($poll_id){
        $pdo = $this->getConnection();
        $sql = "SELECT * FROM engine4_poll_polls WHERE poll_id=$poll_id";
        $poll = $pdo->query($sql)->fetchObject();

        $pObj = new Poll();
		$pObj->id = $poll->poll_id;
        $pObj->user_id = $poll->user_id;
        $pObj->title = $poll->title;
        $pObj->description = $poll->description;
        $pObj->starttime = $poll->starttime;
        $pObj->endtime = $poll->endtime;
        $pObj->view_count = $poll->view_count;
        $pObj->comment_count = $poll->comment_count;
        $pObj->search = $poll->search;
        $pObj->vote_count = $poll->vote_count;
        $pObj->is_closed = $poll->closed;
        $pObj->is_closed = $poll->is_closed;
        $pObj->created_at = $poll->creation_date;

        $pObj->save();

        $sql = "SELECT * FROM engine4_poll_options WHERE poll_id=$poll_id";

        $poll_options = $pdo->query($sql)->fetchAll();

        foreach($poll_options as $option){
            $optionObj = new PollOption();
	        $optionObj->id = $option['poll_option_id'];
            $optionObj->poll_id = $pObj->id;
            $optionObj->poll_option = $option['poll_option'];
            $optionObj->votes = $option['votes'];
            $optionObj->save();

            $option_id = $optionObj->id;

            $sql = "SELECT * FROM engine4_poll_votes WHERE poll_option_id={$option['poll_option_id']}";

            $poll_votes = $pdo->query($sql)->fetchAll();

            foreach($poll_votes as $vote){
                $vObj = new PollVote();
                $vObj->poll_id = $pObj->id;
                $vObj->user_id = $vote['user_id'];
                $vObj->poll_option_id = $option_id;
                $vObj->created_at = $vote['creation_date'];
                $vObj->updated_at = $vote['modified_date'];
                $vObj->save();
            }
        }

        return $pObj->id;

    }
	public function savePhoto($photo_id){
		$pdo = $this->getConnection();
		$photo_sql = "SELECT * FROM engine4_album_photos WHERE photo_id=$photo_id";
		$photo = $pdo->query($photo_sql)->fetchObject();

        if(empty($photo->photo_id)){
			return FALSE;
		}

        $sfObj = $this->saveFile($photo->file_id,'album_photo');

        $apObj = new AlbumPhoto();
        $apObj->file_id = $sfObj->file_id;
        $apObj->album_id = $photo->album_id;
        $apObj->title = $photo->title;
        $apObj->description = $photo->description;
        $apObj->created_at = $photo->creation_date;
        $apObj->updated_at = $photo->modified_date;
        $apObj->order = $photo->order;
        $apObj->owner_type = $photo->owner_type;
        $apObj->owner_id = $photo->owner_id;
        $apObj->view_count = $photo->view_count;
        $apObj->comment_count = $photo->comment_count;
        $apObj->he_featured = $photo->he_featured;

        $apObj->save();

        $sfObj->parent_id = $apObj->photo_id;
        $sfObj->save();

        return $apObj->photo_id;

	}
	public function saveLink($link_id){
		$pdo = $this->getConnection();
		$link_sql = "SELECT * FROM engine4_core_links WHERE link_id=$link_id";
		$link = $pdo->query($link_sql)->fetchObject();
		if(empty($link->link_id)) {
			return FALSE;
		}
        $photo_id = 0;
        if(!empty($link->photo_id)){
            $photo_id = $this->savePhoto($link->photo_id);
        }
        $linkObj = new Link();
		$linkObj->link_id = $link->link_id;
        $linkObj->uri = $link->uri;
        $linkObj->title = $link->title;
        $linkObj->description = $link->description;
        $linkObj->photo_id = $photo_id;
        $linkObj->owner_type = $link->owner_type;
        $linkObj->owner_id = $link->owner_id;
        $linkObj->parent_id = $link->parent_id;
        $linkObj->parent_type = $link->parent_type;
        $linkObj->view_count = $link->view_count;
        $linkObj->created_at = $link->creation_date;
        $linkObj->search = $link->search;

        $linkObj->save();

        return $linkObj->link_id;

	}
	public function saveVideo($video_id){
		$pdo = $this->getConnection();
		$sql = "SELECT * FROM engine4_video_videos WHERE video_id=$video_id";
		$video = $pdo->query($sql)->fetchObject();
		if(empty($video->video_id)){
			return FAlSE;
		}

		$video->created_at = $video->creation_date;
		$video->updated_at = $video->modified_date;

        $vObj = new Video();
		$vObj->video_id = $video->video_id;
        $vObj->title = $video->title;
        $vObj->description = $video->description;
        $vObj->search = $video->search;
        $vObj->owner_id = $video->owner_id;
        $vObj->owner_type = $video->owner_type;
        $vObj->parent_type = $video->parent_type;
        $vObj->parent_id = $video->parent_id;
        $vObj->created_at = $video->creation_date;
        $vObj->updated_at = $video->modified_date;
        $vObj->view_count = $video->view_count;
        $vObj->comment_count = $video->comment_count;
        $vObj->type = $video->type;
        $vObj->code = $video->code;
        //$file = $this->saveFile($video->photo_id,'video');
        $vObj->photo_id = $video->photo_id;
		$vObj->file_id = $video->file_id;
        $vObj->rating = $video->rating;
        $vObj->category_id = $video->category_id;
        $vObj->status = $video->status;
        $vObj->duration = $video->duration;
        $vObj->rotation = $video->rotation;
        $vObj->save();
//
//        $file->parent_id = $vObj->video_id;

 //       $file->save();

        return $vObj->video_id;

	}
	public function saveAudio($id){
		$pdo = $this->getConnection();
		$sql = "SELECT * FROM engine4_music_playlist_songs WHERE song_id=$id";
		$row = $pdo->query($sql)->fetchObject();

		if(empty($row->song_id)){
			return FALSE;
		}

        //$sfObj = $this->saveFile($row->file_id,'song');
        return $row->file_id;
	}
	public function saveFile() {
		DB::connection('mysql')->table('storage_files')->truncate();

		DB::connection('k2_live')->table('engine4_storage_files')->chunk(100,function($rows){
			foreach($rows as $row) {
				$temp = [];
				$temp['file_id']      = $row->file_id;
				$temp['parent_type']  = $row->parent_type;
				$temp['parent_id']    = $row->parent_id;
				$temp['user_id']      = $row->user_id;
				$temp['name']         = $row->name;
				$temp['type']         = $row->type;
				$temp['created_at']   = $row->creation_date;
				$temp['updated_at']   = $row->modified_date;
				$temp['extension']    = $row->extension;
				$temp['mime_type']    = $row->mime_major . '/' . $row->mime_minor;
				$temp['size']         = $row->size;
				$temp['hash']         = $row->hash;
				$path                = explode( '/', $row->storage_path );
				$path                = end( $path );
				$path                = $row->user_id . '/' . $row->file_id.$path;
				$temp['storage_path'] = $path;

				if ( ! file_exists( storage_path( 'app/photos/' . $row->user_id ) ) ) {
					mkdir( storage_path( 'app/photos/' . $row->user_id ) );
				}
				if ( ! file_exists( storage_path( 'app/audios/' . $row->user_id ) ) ) {
					mkdir( storage_path( 'app/audios/' . $row->user_id ) );
				}
				if ( ! file_exists( storage_path( 'app/videos/' . $row->user_id ) ) ) {
					mkdir( storage_path( 'app/videos/' . $row->user_id ) );
				}
				if ( file_exists( storage_path('app/') . $row->storage_path ) ) {
					if ( $row->mime_major == 'image' ) {
						try{
							copy( storage_path('app/'.$row->storage_path), storage_path( 'app/photos/' . $path ) );
						}catch(Exception $e){
							echo "<pre>";
							print_r($e);exit;
						}
					} elseif ( $row->extension == 'mp3' ) {
						try{
							copy( storage_path('app/'.$row->storage_path) , storage_path( 'app/audios/' . $path ) );
						}catch(Exception $e){

						}

					} else {
						try{
							copy( storage_path('app/'.$row->storage_path), storage_path( 'app/videos/' . $path ) );
						}catch(Exception $e){

						}
					}
				}
				$count = DB::connection('mysql')->table('storage_files')->where('file_id',$row->file_id)->count();
				if($count < 1) {
					DB::connection( 'mysql' )->table( 'storage_files' )->insert( $temp );
				}
			}
		});

        dd('wahoooooo');
	}
	function updatePermissions(){
		$users = DB::connection('mysql')->table('users')->select(['id'])->get();

		$sr = new SettingRepository();
		foreach($users as $row){

			$save = $sr->saveAllSetting($row->id);
		}
		dd('I will that boy');
	}
    public function savChildFiles($parent_id,$type){
        $pdo = $this->getConnection();
        $sql = "SELECT * FROM engine4_storage_files WHERE file_id=$parent_id";

        foreach($pdo->query($sql) as $row){

            $sfObj = new StorageFile();
            $sfObj->parent_type = $type;
            $sfObj->parent_id = $row['user_id'];
            $sfObj->type = $row['type'];
            $sfObj->created_at = $row['creation_date'];
            $sfObj->updated_at = $row['modified_date'];
            $sfObj->extension = $row['extension'];
            $sfObj->mime_type = $row['mime_major'].'/'.$row['mime_minor'];
            $sfObj->size = $row['size'];
            $sfObj->hash = $row['hash'];
        }

    }
	function is_url_exist($url){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if($code == 200){
			$status = true;
		}else{
			$status = false;
		}
		curl_close($ch);
		return $status;
	}

	public function setting_allow_for_old_users(  ) {
		
	}

	public function changeUsername()
	{
		$users = User::where('username', 'like','%www%')->orWhere('username', 'like','%http%')->select(['id','first_name','last_name'])->get();
		foreach($users as $user){
			$uObj = User::find($user->id);
			$uObj->username = $user->first_name.'-'.$user->last_name.'-'.$user->id;
			$uObj->save();
		}
		dd('all done');
	}

	public function slugify_username()
	{
		ini_set('max_execution_time', 180);
		$users = User::all();
		foreach($users as $user){
			$uObj = User::find($user->id);
			$uObj->username = \Kinnect2::slugify($user->username);
			$uObj->save();
		}
		dd('all done');
	}
}
?>
