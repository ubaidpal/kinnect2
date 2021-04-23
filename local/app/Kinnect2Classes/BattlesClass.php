<?php
namespace App\Kinnect2Classes;


use App\ActivityNotification;
use App\AlbumPhoto;
use App\AuthorizationAllow;
use App\Events\ActivityDelete;
use App\Events\ActivityLog;
use App\Facades\AuthorizationAllowClassFacade;
use App\StorageFile;
use App\BrandTaggingLog;
use App\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\BattleOption;
use App\BattleVote;
use Carbon\Carbon;
use App\Battle;
use App\Brand;
use Kinnect2;
use App\Events\CreateNotification;
use phpDocumentor\Reflection\DocBlock\Tag;

class BattlesClass implements BattlesClassInterface
{

    public function __construct() {
        $this->activity_type = \Config::get('constants_activity.OBJECT_TYPES.BATTLES.NAME');
    }

    public function getAllBattles($user_id,$query = []) {

        $users_battle        = $this->user_battle($user_id,$query);
        $battle              = $this->other_battle($user_id,$query);
        $data['user_battle'] = $users_battle;
        $data['battle']      = $battle;

        return ($data);
    }

    public function user_battle($user_id,$query = []) {
        $queryObject = Battle::whereUserId($user_id)
                                ->orderBy('created_at', 'desc')
                                ->take(10);
        if(!empty($query['title'])){
            $queryObject->where('title','LIKE',"%{$query['title']}%");
        }

        return $queryObject->get();
    }

    public function other_battle($user_id,$query = []) {
        $queryObject = Battle::where('user_id', '<>', $user_id)
                                ->orderBy('created_at', 'desc')
                                ->take(10);
        if(!empty($query['title'])){
            $queryObject->where('title','LIKE',"%{$query['title']}%");
        }
        return $queryObject->get();
    }

    public function all_battles() {
        return Battle::orderBy('created_at', 'desc')
            ->get();
    }

    public function get_battle_details($battle, $detail = FALSE, $user_id = NULL) {

        $user                   = User::findOrNew($battle->user_id);
        $battle['creator_name'] = $user->displayname;
        $battle['creator_url']  = $user->username;
        $battle['profile_photo_url'] = \Kinnect2::getPhotoUrl($user->photo_id, $battle->user_id, 'user', 'thumb_normal');
        if ($detail) {
            $battle['options'] = $this->_battle_options_detail($battle->id);
            if ($user_id) {
                //$battle['votes'] = $this->battle_votes($battle->id, $user_id);
            }
        }
        $battle['is_voted'] = $this->_is_voted($user_id, $battle->id);
        $battle['privacy']  = $this->_get_privacy($battle->id);

        return $battle;
    }

    public function _battle_options_detail($battle_id) {
        $options                  = BattleOption::whereBattleId($battle_id)->with('brand.brand_detail')->get();
        $optionVal                = [];
        $optionVal['brand_photo'] = '';
        foreach ($options as $option) {

            $optionVal['option_id'] = $option->id;
            $optionVal['votes']     = $option->votes;

            if (!empty($option->brand)) {
                $optionVal['brand_id']   = $option->brand->brand_detail->id;
                $optionVal['brand_name'] = $option->brand->brand_name;
                if ($option->brand->brand_detail->photo_id) {
                    $photo   = AlbumPhoto::where('photo_id', $option->brand->brand_detail->photo_id)
                        ->select(['file_id'])
                        ->first();
                    $file_id = isset($photo->file_id) ? $photo->file_id : NULL;
                    $file    = StorageFile::where('file_id', $file_id)->first();
                    $path    = isset($file->storage_path) ? $file->storage_path : NULL;
                    if (!empty($path)) {
                        $optionVal['brand_photo'] = \Config::get('constants_activity.PHOTO_URL') . $path . '?type=' . urlencode($file->mime_type);
                    }
                } else {
                    $optionVal['brand_photo'] = '';
                }
            } else {
                $optionVal['brand_id']    = '';
                $optionVal['brand_name']  = '';
                $optionVal['brand_photo'] = '';
            }

            $options_detail[] = $optionVal;
        }

        return $options_detail;
    }

    public function _is_voted($user_id, $id) {
        $data = DB::table('battle_votes')->where('battle_id', $id)->where('user_id', $user_id)->count();
        if ($data > 0) {
            return 'yes';
        } else {
            return 'no';
        }

    }

    public function createBattle() {
        // $brands = Brand::lists('brand_name', 'id')->prepend('Select an option', '')->toArray();;

        $users = DB::table('users')
            ->select('users.userable_id as id', 'users.name', 'users.first_name', 'users.username', 'users.displayname', 'users.photo_id as image')
            ->where('users.search', '1')
            ->where('users.active', '1')
            ->where('users.user_type', 2)
            ->get();

        if (!\URLFilter::filter()) {
            $brands[] = array('brand_id' => '', 'brand_name' => 'Select Brand *', 'image_src' => asset('/local/public/assets/images/defaults/default_brand_profile_photo.svg'));

        }

        foreach ($users as $user) {
            $user->image = Kinnect2::getPhotoUrl($user->image, $user->id, 'user', 'thumb_icon');
            $brands[]    = array('brand_id' => $user->id, 'brand_name' => $user->displayname, 'image_src' => $user->image);
        }

        return ($brands);
    }

    public function storeBattle($battle, $user_id) {
        $battle->starttime = Carbon::now();
        $battle->user_id   = $user_id;
        $battle->save();
        $brands = [];
        if (\URLFilter::filter()) {
            foreach (Input::get('brand_name') as $row) {

                $option            = new BattleOption();
                $option->brand_id  = $row;
                $option->battle_id = $battle->id;
                $option->save();
                $brands[] = $row;
            }
        } else {
            //Saving option for brand 1
            $option = new BattleOption();

            $getBrandId1 = Input::get('select1');

            $option->brand_id  = $getBrandId1;
            $option->battle_id = $battle->id;

            $option->save();

            $brands[] = $getBrandId1;
            //End Saving option for brand 1

            //Saving option for brand 2
            $option = new BattleOption();

            $getBrandId2 = Input::get('select2');

            $option->brand_id  = $getBrandId2;
            $option->battle_id = $battle->id;

            $option->save();

            $brands[] = $getBrandId2;
        }

        //End Saving option for brand 2

//        $i = 1;
//        if ($i >= 0) {
//            $option = new BattleOption();
//            $option->brand_id = Input::get('brandtextfield')[$i];
//            $option->battle_id = $battle->id;
//            $option->save();
//            $i = $i - 1;
//        }

        $view    = (Input::get('auth_allow_view'));
        $comment = (Input::get('auth_allow_comment'));

        AuthorizationAllowClassFacade::Setting('battle', $battle->id, $view, 'view');
        AuthorizationAllowClassFacade::Setting('battle', $battle->id, $comment, 'comment');
        $options = array(
            'object_type' => $this->activity_type,
            'type'        => \Config::get('constants_activity.OBJECT_TYPES.BATTLES.ACTIONS.CREATE'),
            'subject'     => $user_id,
            'object'      => $battle->id,

        );
        \Event::fire(new ActivityLog($options));

        foreach ($brands as $index => $brand_id) {
            $attributes = array(
                'resource_type' => \Config::get('constants_activity.OBJECT_TYPES.USER.NAME'),
                'resource_id' => User::where('userable_id',$brand_id)->where('userable_type','App\Brand')->value('id'),
                'subject_id' => $user_id,
                'subject_type' => 'user',
                'object_id' => $battle->id,
                'object_type' => \Config::get('constants_activity.OBJECT_TYPES.BATTLE'),
                'type' => \Config::get('constants_activity.notification.BATTLE_CREATE_TAG'),
            );
            
            \Event::fire(new CreateNotification($attributes));
        }
    }

    public function editBattle($id) {
        $battles = Battle::find($id);

        return ($battles);
    }

    public function updateBattle($id, $view, $comment, $search, $user_id) {
        $battle = Battle::findOrFail($id);
        if ($user_id == $battle->user_id) {
            $battle->search = $search;
            $battle->save();
            \AuthorizationAllowClassFacade::changeSetting('battle', $battle->id, $view, 'view');
            \AuthorizationAllowClassFacade::changeSetting('battle', $battle->id, $comment, 'comment');
        }
    }

    public function showBattle($id, $user_id) {
        $battle  = Battle::find($id);
        $options = $this->battle_options($id);
        $option1 = $options[0];
        $option2 = $options[1];
        $brand1  = $this->get_brand($option1->brand_id);
        $brand2  = $this->get_brand($option2->brand_id);
        $brand6  = $this->get_brand_name($option1->brand_id);
        $brand7  = $this->get_brand_name($option2->brand_id);


        $votes           = $this->battle_votes($id, $user_id);
        $data["brand1"]  = (array)$brand1;
        $data['brand2']  = (array)$brand2;
        $data["brand3"]  = $brand1;
        $data['brand4']  = $brand2;
        $data["brand6"]  = $brand6;
        $data['brand7']  = $brand7;
        $data['option1'] = $option1;
        $data['option2'] = $option2;
        $data['battle']  = $battle;
        $data['votes']   = $votes;

        return ($data);
    }

    public function battle_options($id) {
        return BattleOption::whereBattleId($id)->get();
    }

    public function get_brand($id) {
        return User::whereUserableId($id)->whereUserableType('App\Brand')->first();
    }

    public function get_brand_name($id) {
        return DB::table('users_brands')->where('id', $id)->first();
    }

    public function battle_votes($id, $user_id) {
        $votes = DB::table('battle_votes')->where('battle_id', $id)->where('user_id', $user_id)->first();
        if (!empty($votes)) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    public function deleteBattle($id, $user_id) {
        $battle = Battle::findOrFail($id);
        if ($user_id == $battle->user_id) {
            AuthorizationAllowClassFacade::deleteResource($id, 'battle');
            $options = $this->battle_options($id);
            $votes   = $this->battle_all_votes($id);
            $battle->delete();

            foreach ($options as $option) {
                $option->delete();
            }
            foreach ($votes as $vote) {
                $vote->delete();
            }
            $params = [
                'subject_id'  => $user_id,
                'object_id'   => $id,
                'object_type' => \Config::get('constants_activity.OBJECT_TYPES.BATTLE'),
            ];
            \Event::fire(new ActivityDelete($params));

            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function battle_all_votes($id) {
        return BattleVote::whereBattleId($id)->get();
    }

    public function battleVotes($option_id, $user_id) {
        if (empty($option_id) || empty($user_id)) {
            return FALSE;
        }
        $option = BattleOption::find($option_id);

        if (empty($option->id)) {
            return FALSE;
        }

        $battle = Battle::find($option->battle_id);
        if (empty($battle->id)) {
            return FALSE;
        }
        $checkVote = BattleVote::where('battle_id', $battle->id)->where('user_id', $user_id)->first();

        $battle->vote_count++;
        $option->votes++;

        if ($battle->is_closed == 0) {
            if ($checkVote == NULL) {
                if ($battle->user_id != $user_id) {
                    $option->save();
                    $battle->save();
                    BattleVote::create([
                        'battle_id'        => $battle->id,
                        'user_id'          => $user_id,
                        'battle_option_id' => $option_id,
                    ]);

//            $options = array(
//                'object_type' => $this->activity_type,
//                'type' => \Config::get('constants_activity.OBJECT_TYPES.BATTLES.ACTIONS.VOTE'),
//                'subject' => $user_id,
//                'object' => $battle->id,
//
//            );
//            \Event::fire(new ActivityLog($options));
                    return TRUE;
                }
            }
        }

        return FALSE;
    }

    public function closeBattle($id, $user_id) {
        $battle = Battle::find($id);
        if ($user_id == $battle->user_id) {
            $battles           = $battle->check_closed;
            $battle->is_closed = $battles;
            if ($battle->is_closed == 1) {
                $battle->endtime = Carbon::now();
            } else {
                $battle->endtime = 0;
            }
            $battle->save();
//            $options = array(
//                'object_type' => $this->activity_type,
//                'type'        => \Config::get('constants_activity.OBJECT_TYPES.BATTLES.ACTIONS.CLOSE'),
//                'subject'     => $user_id,
//                'object'      => $id,
//
//            );
//            \Event::fire(new ActivityLog($options));

            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function manageBattle($user_id,$query = []) {
        $l      = (Config::get('constants.DISPLAY_LIMIT'));
        $queryObject = DB::table('battles')
                        ->where('user_id', $user_id)
                        ->orderBy('created_at', 'desc');
        
        if(!empty($query['title'])) {
            $queryObject->where('title', 'LIKE', "%{$query['title']}%");
        }
        
        $battle = $queryObject->paginate($l);

        return ($battle);
    }

    private function _get_privacy($id) {
        $privacy                        = AuthorizationAllow::whereResourceId($id)->whereResourceType('battle')->lists('permission', 'action');
        $privacyR['auth_allow_view']    = '';
        $privacyR['auth_allow_comment'] = '';
        if (!empty($privacy)) {
            if (isset($privacy['view'])) {
                $privacyR['auth_allow_view'] = \Config::get('constants.PERMISSION.' . $privacy['view']);
            }
            if (isset($privacy['comment'])) {
                $privacyR['auth_allow_comment'] = \Config::get('constants.PERMISSION.' . $privacy['comment']);
            }

        }


        return $privacyR;

    }
    public function getTaggedBattle($user_id){
        $battle_ids = ActivityNotification::where('resource_id',$user_id)
                                ->where('object_type','battle')
                                ->where('type',\Config::get('constants_activity.notification.BATTLE_CREATE_TAG'))
                                ->take(10)
                                ->lists('object_id','object_id');
        $battles =  Battle::where('user_id', '<>', $user_id)
                        ->whereIn('id',$battle_ids)
                        ->orderBy('created_at', 'desc')
                        ->get();
        foreach ($battles as $battle){
            $battle->allowed_on_timeline = BrandTaggingLog::where('object_id',$battle->id)
                                                        ->where('user_id',$user_id)
                                                        ->where('object_type','battle')
                                                        ->where('allowed_on_timeline',1)
                                                        ->count();

        }
        return $battles;
    }

    public function showBattleOnTimeline($battle_id,$user_id)
    {
        $options = array(
            'object_type' => \Config::get('constants_activity.OBJECT_TYPES.BATTLES.NAME'),
            'type'        => 'share',
            'subject'     => $user_id,
            'object'      => $battle_id,

        );
        $toObj = new BrandTaggingLog();

        $toObj->object_id = $battle_id;
        $toObj->object_type = \Config::get('constants_activity.OBJECT_TYPES.BATTLES.NAME');
        $toObj->allowed_on_timeline = 1;
        $toObj->user_id = $user_id;

        $toObj->save();

        \Event::fire(new ActivityLog($options));
    }
    public function removeFromTimeline($battle_id,$user_id){
        $params = [
            'subject_id'  => $user_id,
            'object_id'   => $battle_id,
            'type'        => 'share',
            'object_type' => \Config::get('constants_activity.OBJECT_TYPES.BATTLES.NAME'),
        ];
        \Event::fire(new ActivityDelete($params));

        $toObj = new BrandTaggingLog();
        
        $toObj->where('object_id',$battle_id)
                ->where('object_type',\Config::get('constants_activity.OBJECT_TYPES.BATTLES.NAME'))
                ->where('allowed_on_timeline',1)
                ->where('user_id',$user_id)
                ->delete();
    }
}
