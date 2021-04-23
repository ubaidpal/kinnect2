<?php
/**
 * Created by   : Ubaud
 * Project Name : Kinnec2
 * Product Name : Kinnec2
 * Date         : 11-20-15 3:48 PM
 * File Name    : SettingRepository.php
 */

namespace App\Repository\Eloquent;


use App\Usersetting;
use Auth;
use DB;
use Illuminate\Support\Facades\Config;

class SettingRepository extends Repository
{

    protected $setting;
    private   $activity_type;

    public function __construct()
    {
        parent::__construct();

        $this->activity_type = \Config::get('constants.ACTIVITY_TYPE_FRIENDSHIP');

    }

    public function getSetting($userId)
    {

        $setting = DB::table('user_settings')->where('user_id', $userId)->get();

        //$setting = DB::table('user_settings')->lists('setting_value', $userId);
        return $setting;
    }

    public function saveAllSetting($userId)
    {

        $setting = DB::table('user_settings')->where('user_id', $userId)->count();

        if ($setting > 0) {
            return FALSE;
        } else {

            $data = array(
                (array('category' => 'Privacy', 'setting' => 'DNT_DISPLY_ME_IN_SRCH', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'WHO_VIEW_KINNECTOR', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'WHO_VIEW_PROFILE', 'setting_value' => \Config::get('constants.PERM_EVERYONE'), 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'WHO_POST_YUR_PROFILE', 'setting_value' => \Config::get('constants.PERM_EVERYONE'), 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'NEW_PHTO_ALBM', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'NEW_BATLE', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'NEW_BLG_ENTRY', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'NEW_BRND', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'JOING_BRND', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'UPLOD_BRND_PHTO', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'BRND_PROMTION', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'BRND_TG', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'CRTNG_BRND_DISCUSION_TOPIC', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'REPLY_BRND_DISCUSIN_TOPIC', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'NEW_CLSFIED_LST', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'COMENT_PHTO_ALBM', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'COMENT_YUR_PHTO', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'COMENT_YUR_BTL', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'COMENT_YUR_BLG', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'COMENT_YUR_CLSFY_LSTIG', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'COMENT_YUR_EVNT', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'COMENT_YUR_GRP', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'COMENT_YUR_PLYLST', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'COMENT_YUR_POLL', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'COMENT_YUR_VID', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'NEW_EVTS', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'JOING_EVT', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'UPL_EVT', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'CRTNG_EVT_TOP', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'REPLY_EVT_DISCUSIN_TOPIC', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'FORUM_PROMTION', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'CRTNG_FORUM_TOPIC', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'REPLY_FORUM_TOPIC', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'WHN_BCME_WTH_SOMONE', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'NEW_GRP_POLL_ENTRY', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'NEW_GRP', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'JOIN_GRP', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'UPLOD_GRP_PHTO', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'GRP_PROMTON', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'CRTNG_GRP_DISCUSION_TOPIC', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'REPLY_GRP_DISCUSION_TOPIC', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'NEW_PLYLST', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'REPLY_COMENT_ALBM', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'REPLY_COMENT_ALB_PHTO', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'REPLY_COMTENT_BLG', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'REPLY_COMENT_CLSFIED', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'NESTD_COMENT_EVT', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'REPLY_COMENT_GRP', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'REPLY_COMENT_POLL', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'REPLY_COMENT_VID', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'JOIN_NETWRK', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'NEW_POLL', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'POSTNG_STATS_UPD_PROFILE', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'CHGNG_PHTO', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'ACTVTY_FEED_ITEM', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'ACTVTY_ACTION_TG', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'TAG', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'TG_BRND', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Privacy', 'setting' => 'NEW_VID', 'setting_value' => 1, 'user_id' => $userId)),

                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_COMTNS_ON_THING_I_POST', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_COMTNS_ON_SAME_THING_ME', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_ACPT_MY_FRND_REQUST', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_I_LIKE_THING_I_POST', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_COMTNS_ON_THING_I_LIKED', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_RPLY_ON_THING_I_LIKED', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_I_RECEIVE_MSG', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_POST_ON_MY_PROFILE', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_REPLY_ON_MY_PROFILE', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_REPLY_ON_SAME_THING_ME', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_ACTVITY_FEED_ITM_IS_SHARED', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_I_M_TAG_PHTO_&_OTHR_PLACE', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_I_GIVN_MODERATAR_STATUS_IN_FORUM', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_REPLY_FORUM_TOPIC_I_REPLIED', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_REPLY_FORUM_THAT_I_CREATE', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_MY_REQUST_TO_JOIN_GRP_APPROVED', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_SOME_PEOPLE_REQUEST_JOIN_GRP_I_CREATED', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_I_INVITE_TO_JOIN_GRP', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_I_M_GIVN_STATUS_IN_GRP', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_NEW_BLG_ENTRY_POST_BY_MEMBR_U_SUBCRIBE', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_MY_REQUST_TO_JOIN_BRND_APPROVED', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_SOME_PEOPLE_REQUEST_JOIN_BRND_I_CREATED', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED_BRND', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE_BRND', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_I_INVITE_TO_JOIN_BRND', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_I_M_GIVN_STATUS_IN_BRND', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_MY_REQUST_TO_ATTND_AN_EVT_APPROVED', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_SOME_PEOPLE_REQUEST_TO_JOIN_MY_EVNT', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED_EVNT', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE_EVNT', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_I_INVITE_TO_ATTND_BRND', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_VID_PROCEED', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_VID__FAILED_TO_PROCEED', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_COMENT_THING_I_FOLLOW', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_PEOPLE_LIKE_THING_I_FOLLOW', 'setting_value' => 1, 'user_id' => $userId)),
                (array('category' => 'Notification', 'setting' => 'WEN_I_M_TAG_IN_POST', 'setting_value' => 1, 'user_id' => $userId)),
            );
            $save = DB::table('user_settings')->insert($data);

            return $save;
        }
    }

}
