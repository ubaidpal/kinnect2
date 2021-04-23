<?php

use App\Events\GetNotification;
use App\User;
use Goutte\Client;

/**
 * @return string
 */
function get_user() {
    return $user = Input::get('middleware')[ 'user' ];
}

function get_notification_count($id) {
    $data   = ['resource_id' => $id, 'count' => 1];
    $result = Event::fire(new GetNotification($data));

    return $result[ 0 ];
}

/**
 * @param $data
 *
 * @return array|string
 */
function activity_log_string($data) {

    //return $data->type . '-' . $data->object_type;
    $string = Config::get('constants_activity.ACTIVITY_LOG_MESSAGE.' . $data->type);

    $object = get_object($data);

    if(URLFilter::filter()) {
        // $object = '';

        // if(isset($object['object'])){
        $object = $object[ 'object' ];
        //}

    }

    if(URLFilter::filter()) {
        return array(
            'body'        => Config::get('constants_activity.ACTIVITY_LOG_MESSAGE.' . $data->type), // 'url'    => $type_url['url'],
            'activity_id' => $data->action_id,
            'date'        => $data->created_at,
            'object'      => [
                'object_type'  => ($data->object_type == 'brand' ? 'user' : $data->object_type),
                'object_id'    => $data->object_id,
                'object_title' => ($object != '' ? $object : ''),
                'log_type' => $data->type
            ],
        );
    } else {
        $type     = get_type($data->type, $data->object_type);
        $type_url = generate_url($data->action_id, $type);
        $string   = str_replace('$object', $object, $string);
        return $string . ' ' . $type_url;
    }
}

function generate_url($action_id, $name, $type = TRUE) {
    if($type) {
        $url = url('postDetail/' . $action_id);
    } else {
        $url = $action_id;
    }
    if(URLFilter::filter()) {
        return array('url' => $url, 'object' => $name,);
    }

    return $string = "<a href='$url'>$name</a>";
}

function get_type($type, $object_type) {

    $types = ['like_status' => 'post', 'comment_video' => 'video', 'comment_album_photo' => 'photo', 'status' => 'status', 'group' => 'group', //'album'               => 'album',
        //'group_share'         => 'Share Group',
    ];
    if($type == 'share') {
        if($object_type == 'album_photo') {
            return 'Album Photo';
        }
    }
    /* if (isset($types[$type]) && isset($object_types[$object_type])) {
         return $types[$type];
     }*/
    if(isset($types[ $type ])) {
        return $types[ $type ];
    }

    return FALSE;
}

function get_object($data) {

    $object = [];
    $url    = '';

    /*$object['object_type'] = ($data->object_type == 'brand' ? 'user' : $data->object_type);
    $object['object_id']   = $data->object_id;*/

    switch ($data->object_type) {
        case 'user':
            switch ($data->target_type) {
                case 'group':
                    $group      = \App\Group::findOrNew($data->target_id);
                    $group_name = $group->title;
                    $url        = url('group/' . $group->id);
                    $object     = generate_url($url, $group_name, FALSE);
                    break;
                default:
                    //return $data->object_type;
                    $user = User::find($data->object_id);
                    if($user) {
                        $object = $user->displayname . "'s";
                        $url    = Kinnect2::profileAddress($user);
                    }
                    $object = generate_url($url, $object, FALSE);
                    break;

            }
            break;
        case 'brand':
            //$data->object_type;
            $user = User::whereId($data->object_id)->first();

            if($user) {
                $object = $user->displayname . "'s";
                $url    = Kinnect2::profileAddress($user);

                return $object = generate_url($url, $object, FALSE);
            }

            return FALSE;
            break;
        case 'group':
            $group      = \App\Group::findOrNew($data->object_id);
            $group_name = $group->title;
            $url        = url('group/' . $group->id);
            $object     = generate_url($url, $group_name, FALSE);
            break;
        case 'poll':
            $poll      = \App\Poll::findOrNew($data->object_id);
            $poll_name = $poll->title;
            $url       = url('polls/' . $poll->id);
            $object    = generate_url($url, $poll_name, FALSE);
            break;
        case 'event':
            $event      = \App\Event::findOrNew($data->object_id);
            $event_name = $event->title;
            $url        = url('event/' . $event->id);
            $object     = generate_url($url, $event_name, FALSE);
            break;
        case 'battle':
            $battle      = \App\Battle::findOrNew($data->object_id);
            $battle_name = $battle->title;
            $url         = url('postDetail/' . $data->action_id);
            $object      = generate_url($url, $battle_name, FALSE);
            /* $object['object_type'] = 'activity_action';
             $object['object_id']   = $data->action_id;*/
            break;
        case 'link':
            $url    = $data->body;
            $object = generate_url($url, 'Link', FALSE);
            break;
        case 'video':
            $user = json_decode($data->params);

            if($user) {
                //list($s, $user_id) = explode('_', $user->owner_id);
                $user   = User::findOrNew($user->owner_id);
                $object = $user->displayname;
                $url    = Kinnect2::profileAddress($user);
            }

            $object = generate_url($url, $object, FALSE);
            break;
        case 'audio':
            $url    = url('postDetail/' . $data->action_id);
            $object = generate_url($url, 'Audio', FALSE);
            /*$object['object_type'] = 'activity_action';
            $object['object_id']   = $data->action_id;*/
            break;
        case 'album_photo':
            $album_name = '';

            if(!is_null($data->params) && $data->type == 'album') {
                $album_d    = unserialize($data->params);
                $album      = \App\Album::findOrNew($album_d[ 'album_id' ]);
                $album_name = $album->title;
            }

            $url    = url('postDetail/' . $data->action_id);
            $object = generate_url($url, $album_name, FALSE);

            /* $object['object_type'] = 'activity_action';
             $object['object_id']   = $data->action_id;*/

            break;
        case 'album':
            //$user = unserialize($data->params);
            //echo '<tt><pre>'; print_r(json_decode($data->params)); die;
            /*if ($user) {
                list($s, $user_id) = explode('_', $user->owner_id);
                $user = User::findOrNew($user_id);
                $object = $user->displayname . "'s";
                $url = Kinnect2::profileAddress($user);
            }*/
            $url    = url('albums/photos/' . $data->object_id);
            $object = '';//generate_url($url, 'Album Photo', FALSE);

            break;
        case 'cover_photo':
            $url    = url('postDetail/' . $data->action_id);
            $object = generate_url($url, $data->body, FALSE);
            /*$object['object_type'] = 'activity_action';
            $object['object_id']   = $data->action_id;*/

            break;
        case 'activity_action':
            $url    = url('postDetail/' . $data->action_id);
            $object = generate_url($url, $data->body, FALSE);
            /*$object['object_type'] = 'activity_action';
            $object['object_id']   = $data->action_id;*/

            break;
        case 'product':
            $url    = url('postDetail/' . $data->action_id);
            $product = \kinnect2Store\Store\StoreProduct::find($data->object_id);
            $object = generate_url($url, @$product->title, FALSE);
            break;
        default:
            break;
    };

    return $object;
}

/**
 * @param $resource_id
 * @param $resource_type
 * @param $action
 * @param $user_id
 * @param $owner_id
 *
 * @return bool
 */
function is_allowed($resource_id, $resource_type, $action, $user_id, $owner_id) {
    $privacy = new \App\Repository\Eloquent\PrivacyRepository();

    return $privacy->is_allowed($resource_id, $resource_type, $action, $user_id, $owner_id);
}

function is_friend($resource_id, $user_id) {
    return \App\Friendship::whereResourceId($resource_id)->whereUserId($user_id)->whereResourceApproved(1)->whereActive(1)->count();
}

function is_friend_request_sent($resource_id, $user_id) {
    return \App\Friendship::whereResourceId($resource_id)->whereUserId($user_id)->whereUserApproved(0)->whereActive(0)->first();

}

function is_friend_request_received($resource_id, $user_id) {
    return \App\Friendship::whereResourceId($resource_id)->whereUserId($user_id)->whereResourceApproved(0)->whereActive(0)->first();

}

function album_count($user_id) {
    return \App\Album::whereOwnerId($user_id)->where('owner_type', 'user')->count();
}

function activity_log_count($user_id) {
    return \App\Album::whereOwnerId($user_id)->count();
}

function count_all_activity($user_id) {
    $activityAction = new \App\Repository\Eloquent\ActivityActionRepository();

    return $activityAction->count_all_activity($user_id);
}

function country_name($id) {
    $country = DB::table('countries')->where('id', $id)->first();

    return $country->name;
}

function vote_avg($total, $item_vote) {
    if($total == 0) {
        return 0;
    } elseif($item_vote == 0) {
        return 0;
    } else {
        return round(($item_vote / $total) * 100, 1);
    }
}

/**
 * @param $user_id
 * @param $brand_id
 * @param $user_type
 *
 * @return int
 *
 * @Description: Check if user follow brand or brand follow brand
 */

function is_followed($user_id, $brand_id, $user_type = '') {
    /*if($user_type == Config::get('constants.BRAND_USER')) {
        return DB::table('brand_memberships')
            ->where('brand_id', $user_id)
            ->where('user_id', $brand_id)
            ->where('user_approved', 1)
            ->where('brand_approved', 1)
            ->count();
    }else{*/
    return DB::table('brand_memberships')->where('user_id', $user_id)->where('brand_id', $brand_id)->where('user_approved', 1)->where('brand_approved', 1)->count();
    // }
}

function get_chat_group_name($conv_data, $conv_id, $participants, $users) {
    if(!is_null($conv_data[ $conv_id ]->title)):
        return $conv_data[ $conv_id ]->title;
    else:
        $members_name = [];
        foreach ($participants as $participant):

            array_push($members_name, $users[ $participant ]->first_name);
        endforeach;
        $members_name = array_slice($members_name, 0, 2);

        return rtrim(implode(', ', $members_name), ',');
    endif;
}

function get_participant($conv_id) {
    return \DB::table('conv_users')->where('conv_id', $conv_id)->select('conv_id', 'user_id')->lists('user_id');
}

function get_owner_name($user) {
    /*if ($user->user_type == Config::get('constants.BRAND_USER')) {
        return $user->brand_detail->brand_name;
    } else {*/
    return $user->display_name;
    // }
}

function get_photo_by_id($id, $name = FALSE, $wholeData = FALSE, $allowedVideoFiles = []) {

    $file = \App\StorageFile::whereFileId($id)->first();
    $path = isset($file->storage_path) ? $file->storage_path : NULL;

    if(!empty($allowedVideoFiles) && in_array(strtolower($file->extension), $allowedVideoFiles)) {
        $urlPath = \Config::get('constants_activity.ATTACHMENT_VIDEO_URL_MOD');
    } else {
        $urlPath = \Config::get('constants_activity.ATTACHMENT_URL');
    }

    if($name) {
        $path     = $urlPath;//\Config::get('constants_activity.ATTACHMENT_URL');
        $url      = $path . $file[ 'storage_path' ] . '?type=' . urlencode($file[ 'mime_type' ]);
        $fileName = $file[ "name" ];
        if($wholeData) {
            $file[ 'url' ] = $url;
            return $file;
        }
        return ['url' => $url, 'name' => $fileName];
    }
    if(!empty($path)) {
        return $urlPath . $path . '?type=' . urlencode($file->mime_type);
    } else {
        return $temp[ 'object_photo_path' ] = '';
    }
}

function get_photo_by_user_id($id) {
    $user     = User::whereUsername($id)->orWhere('id', $id)->first();
    $photo_id = $user[ 'photo_id' ];

    return Kinnect2::getPhotoUrl($photo_id, $id, 'user', 'thumb_normal');
}

function extractLinkMeta($link) {
    $client  = new Client();
    $crawler = $client->request('GET', $link);

    $title_meta = $crawler->filter('meta[property="og:title"]')->first();

    if($title_meta->count()) {
        $title = $title_meta->attr('content');
    } else {
        $title = $crawler->filter('title')->first()->text();
    }

    $temp[ 'title' ] = $title;

    $description_meta = $crawler->filter('meta[property="og:description"]')->first();

    $description = '';
    if($description_meta->count()) {
        $description = $description_meta->attr('content');
    } elseif($crawler->filter('p')->count()) {
        $description = $crawler->filter('p')->first()->text();
    }

    $temp[ 'description' ] = $description;

    $images = $crawler->filter('meta[property="og:image"]')->each(function (\Symfony\Component\DomCrawler\Crawler $node, $i) {
        return $node->attr('content');
    });

    $temp[ 'images' ] = $images;

    return $temp;

}

/**
 * @param $timestamp
 * @param $format
 *
 * @return string
 *
 * @DESCRIPTION: Convert time by TimeZone. and return with given formatted string
 */
function getTimeByTZ($timestamp, $format) {

    $timeZone = \Config::get('constants.USER_TIME_ZONE');
    $date     = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC');

    return $date->setTimezone($timeZone)->format($format);
}

/**
 * @param $str
 * @param $length
 *
 * @return string
 * @DESCRIPTION: Limit string length
 */
function limit_chr($str, $length) {
    if(strlen($str) <= $length) {
        return $str;
    } else {
        $y = substr($str, 0, $length) . ' ...';

        return $y;
    }
}

function allowed_to_add_kinnector($current_user_id, $logged_in_user_id, $current_user_type, $logged_in_user_type) {
    if($current_user_id == $logged_in_user_id) {
        return FALSE;
    } elseif($logged_in_user_type == Config::get('constants.REGULAR_USER') && $current_user_type == Config::get('constants.BRAND_USER')) {
        return FALSE;
    } else {
        return TRUE;
    }
}

function get_friend_request_noti($user_id) {
    return \DB::table('user_membership')->join('users', 'users.id', '=', 'user_membership.resource_id')->where('user_id', $user_id)->where('user_membership.resource_approved', 1)->where('user_membership.active', 0)->where('is_viewed', '0')->count();
}

function random_id($bytes) {
    $rand =\Illuminate\Support\Str::random($bytes);
    //$rand = mcrypt_create_iv($bytes, MCRYPT_DEV_URANDOM);

    return time() . bin2hex($rand);
}

function date_difference_human($date) {
    $created = new \Carbon\Carbon($date);
    $now     = \Carbon\Carbon::now();

    return $difference = ($created->diff($now)->days < 1) ? 'today' : $created->diffForHumans($now/*, TRUE*/);
}

function date_difference($date) {
    $created = new \Carbon\Carbon($date);
    $now     = \Carbon\Carbon::now();

    return $created->diff($now)->days;
}

function kinnectors_count($user_id) {

    return \DB::table('user_membership')->join('users', 'users.id', '=', 'user_membership.user_id')->where('resource_id', $user_id)// ->where('users.user_type', '=', \Config::get('constants.REGULAR_USER'))
              ->where('user_membership.active', 1)->select('user_membership.*', 'users.name', 'users.username', 'users.displayname', 'users.user_type', 'users.photo_id')->count();
}

function get_brand_kinnectors_count($brand_id) {
    $kinnectors = \App\BrandMembership::whereBrandId($brand_id)->whereBrandApproved(1)->whereUserApproved(1)->lists('user_id');

    return $brandKinnectors = User::whereIn('id', $kinnectors)->orderBy('displayname', 'ASC')->count();
}

function format_currency($number) {
    return number_format($number, 2);
}

function user_name($id) {
    $user = User::find($id);
    return $user->displayname;
}

function getPermissions($userId,$return_array = false) {
    $permissions = DB::table('permission_user')
                     ->join('permissions', 'permissions.id', '=', 'permission_user.permission_id')
                     ->where('permission_user.user_id', $userId)
                     ->select('permissions.name')
                     ->get();
    if($return_array){
        return $permissions;
    }
    if(!empty($permissions)) {
        $perm = [];
        foreach ($permissions as $permission) {
            $perm[] = $permission->name;
        };
        return implode(', ', $perm);
    }
    return 'N/A';
}
