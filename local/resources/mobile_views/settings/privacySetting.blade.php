@extends('layouts.masterDynamic')
@section('content')
@include('includes.setting-left-nav')


        <!--Create Album-->
<div class="community-ad">
    <div class="form-container settings">
        <form role="form" method="Get" action="{{ url('privacySetting') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="setting-title">
                <span>{{Config::get('constants.PRV')}}</span>
            </div>

            <div class="field-item">
                <label for="">{{Config::get('constants.BLOCKED')}}</label>

                <p class="col-dark">
                    {{Config::get('constants.BLOCKED_PARA')}}
                </p>
            </div>

            <div class="field-item-checkbox mt20 ">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="checkbox" class="activity_privacy" id="DNT_DISPLY_ME_IN_SRCH" name="DNT_DISPLY_ME_IN_SRCH"
                       @if(@$settings['DNT_DISPLY_ME_IN_SRCH']['setting_value'] == '0') checked="checked"
                       @endif value=""/>
                <label for="DNT_DISPLY_ME_IN_SRCH">{{Config::get('constants.DNT_DISPLY_ME_IN_SRCH')}}</label>
            </div>

            <div class="setting-block">
                <div class="setting-block-item">
                    <div class="field-item">
                        <label for="">{{Config::get('constants.P_PRV')}}</label>

                        <p class="col-dark"> {{Config::get('constants.SEE_PROF')}}</p>
                    </div>

                    <label class="btn-radio">
                        <input type="radio" name="WHO_VIEW_PROFILE" id="2" class="profile_item"
                               @if(@$settings['WHO_VIEW_PROFILE']['setting_value'] == Config::get('constants.PERM_PRIVATE')) checked="checked"
                               @endif value="{{Config::get('constants.PERM_PRIVATE')}}">
                        <i></i>
                        <span>{{Config::get('constants.ONLY_ME')}}</span>
                    </label>

                    <label class="btn-radio">
                        <input type="radio" name="WHO_VIEW_PROFILE" id="3" class="profile_item"
                               @if(@$settings['WHO_VIEW_PROFILE']['setting_value'] == Config::get('constants.PERM_FRIENDS')) checked="checked"
                               @endif value="{{Config::get('constants.PERM_FRIENDS')}}">
                        <i></i>
                        <span>{{Config::get('constants.ONLY_MY_FRD')}}</span>
                    </label>

                    <label class="btn-radio">
                        <input type="radio" name="WHO_VIEW_PROFILE" id="4" class="profile_item"
                               @if(@$settings['WHO_VIEW_PROFILE']['setting_value'] == Config::get('constants.PERM_FRIENDS_OF_FRIENDS')) checked="checked"
                               @endif value="{{Config::get('constants.PERM_FRIENDS_OF_FRIENDS')}}">
                        <i></i>
                        <span>{{Config::get('constants.FRD_$_NETWRK')}}</span>
                    </label>

                    <label class="btn-radio">
                        <input type="radio" name="WHO_VIEW_PROFILE" id="5" class="profile_item"
                               @if(@$settings['WHO_VIEW_PROFILE']['setting_value'] == Config::get('constants.PERM_EVERYONE')) checked="checked"
                               @endif value="{{Config::get('constants.PERM_EVERYONE')}}">
                        <i></i>
                        <span>{{Config::get('constants.ALL_REG_MEMBR')}}</span>
                    </label>
                </div>
            </div>

            <div class="setting-block">
                <div class="setting-block-item">
                    <div class="field-item">
                        <label for="">{{Config::get('constants.KIN_PRV')}}</label>

                        <p class="col-dark"> {{Config::get('constants.SEE_KIN')}}</p>
                    </div>

                    <label class="btn-radio">
                        <input type="radio" name="WHO_VIEW_KINNECTOR" id="2" class="profile_item"
                               @if(@$settings['WHO_VIEW_KINNECTOR']['setting_value'] == Config::get('constants.PERM_PRIVATE')) checked="checked"
                               @endif value="{{Config::get('constants.PERM_PRIVATE')}}">
                        <i></i>
                        <span>{{Config::get('constants.ONLY_ME')}}</span>
                    </label>

                    <label class="btn-radio">
                        <input type="radio" name="WHO_VIEW_KINNECTOR" id="3" class="profile_item"
                               @if(@$settings['WHO_VIEW_KINNECTOR']['setting_value'] == Config::get('constants.PERM_FRIENDS')) checked="checked"
                               @endif value="{{Config::get('constants.PERM_FRIENDS')}}">
                        <i></i>
                        <span>{{Config::get('constants.ONLY_MY_FRD')}}</span>
                    </label>

                    <label class="btn-radio">
                        <input type="radio" name="WHO_VIEW_KINNECTOR" id="4" class="profile_item"
                               @if(@$settings['WHO_VIEW_KINNECTOR']['setting_value'] == Config::get('constants.PERM_FRIENDS_OF_FRIENDS')) checked="checked"
                               @endif value="{{Config::get('constants.PERM_FRIENDS_OF_FRIENDS')}}">
                        <i></i>
                        <span>{{Config::get('constants.FRD_$_NETWRK')}}</span>
                    </label>

                    <label class="btn-radio">
                        <input type="radio" name="WHO_VIEW_KINNECTOR" id="5" class="profile_item"
                               @if(@$settings['WHO_VIEW_KINNECTOR']['setting_value'] == Config::get('constants.PERM_EVERYONE')) checked="checked"
                               @endif value="{{Config::get('constants.PERM_EVERYONE')}}">
                        <i></i>
                        <span>{{Config::get('constants.ALL_REG_MEMBR')}}</span>
                    </label>
                </div>
            </div>

            <div class="setting-block">
                <div class="setting-block-item">
                    <div class="field-item">
                        <label for="">{{Config::get('constants.P_POSTING_PRV')}}</label>

                        <p class="col-dark"> {{Config::get('constants.WHO_POST_YUR_PROF')}}</p>
                    </div>

                    <label class="btn-radio">
                        <input type="radio" name="WHO_POST_YUR_PROFILE" id="2" class="profile_item"
                               @if(@$settings['WHO_POST_YUR_PROFILE']['setting_value'] == Config::get('constants.PERM_PRIVATE')) checked="checked"
                               @endif value="{{Config::get('constants.PERM_PRIVATE')}}">
                        <i></i>
                        <span>{{Config::get('constants.ONLY_ME')}}</span>
                    </label>

                    <label class="btn-radio">
                        <input type="radio" name="WHO_POST_YUR_PROFILE" id="3" class="profile_item"
                               @if(@$settings['WHO_POST_YUR_PROFILE']['setting_value'] == Config::get('constants.PERM_FRIENDS')) checked="checked"
                               @endif value="{{Config::get('constants.PERM_FRIENDS')}}">
                        <i></i>
                        <span>{{Config::get('constants.ONLY_MY_FRD')}}</span>
                    </label>

                    <label class="btn-radio">
                        <input type="radio" name="WHO_POST_YUR_PROFILE" id="4" class="profile_item"
                               @if(@$settings['WHO_POST_YUR_PROFILE']['setting_value'] == Config::get('constants.PERM_FRIENDS_OF_FRIENDS')) checked="checked"
                               @endif value="{{Config::get('constants.PERM_FRIENDS_OF_FRIENDS')}}">
                        <i></i>
                        <span>{{Config::get('constants.FRD_$_NETWRK')}}</span>
                    </label>

                    <label class="btn-radio">
                        <input type="radio" name="WHO_POST_YUR_PROFILE" id="5" class="profile_item"
                               @if(@$settings['WHO_POST_YUR_PROFILE']['setting_value'] == Config::get('constants.PERM_EVERYONE')) checked="checked"
                               @endif value="{{Config::get('constants.PERM_EVERYONE')}}">
                        <i></i>
                        <span>{{Config::get('constants.ALL_REG_MEMBR')}}</span>
                    </label>
                </div>

            </div>

            <div class="field-item mt15">
                <label for="">{{Config::get('constants.RECENT_ACTVTY_PRVCY')}}</label>

                <p class="col-dark m0">
                    {{Config::get('constants.RECENT_ACTVTY_PARA')}}
                </p>
            </div>

            <div class="setting-block">
                <div class="setting-block-item">

                    <div class="field-item-checkbox mt20 ">
                        <input type="checkbox" class="activity_privacy" id="NEW_PHTO_ALBM" name="NEW_PHTO_ALBM"
                               @if(@$settings['NEW_PHTO_ALBM']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="NEW_PHTO_ALBM">{{Config::get('constants.NEW_PHTO_ALBM')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="NEW_BATLE" name="NEW_BATLE" class="activity_privacy"
                               @if(@$settings['NEW_BATLE']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="NEW_BATLE">{{Config::get('constants.NEW_BATLE')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="NEW_BLG_ENTRY" name="NEW_BLG_ENTRY" class="activity_privacy"
                               @if(@$settings['NEW_BLG_ENTRY']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="NEW_BLG_ENTRY">{{Config::get('constants.NEW_BLG_ENTRY')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="NEW_BRND" name="NEW_BRND" class="activity_privacy"
                               @if(@$settings['NEW_BRND']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="NEW_BRND">{{Config::get('constants.NEW_BRND')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="JOING_BRND" name="JOING_BRND" class="activity_privacy"
                               @if(@$settings['JOING_BRND']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="JOING_BRND">{{Config::get('constants.JOING_BRND')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="UPLOD_BRND_PHTO" name="UPLOD_BRND_PHTO" class="activity_privacy"
                               @if(@$settings['UPLOD_BRND_PHTO']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="UPLOD_BRND_PHTO">{{Config::get('constants.UPLOD_BRND_PHTO')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="BRND_PROMTION" name="BRND_PROMTION" class="activity_privacy"
                               @if(@$settings['BRND_PROMTION']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="BRND_PROMTION">{{Config::get('constants.BRND_PROMTION')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="BRND_TG" name="BRND_TG" class="activity_privacy"
                               @if(@$settings['BRND_TG']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="BRND_TG">{{Config::get('constants.BRND_TG')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="CRTNG_BRND_DISCUSION_TOPIC" name="CRTNG_BRND_DISCUSION_TOPIC"
                               class="activity_privacy"
                               @if(@$settings['CRTNG_BRND_DISCUSION_TOPIC']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="CRTNG_BRND_DISCUSION_TOPIC">{{Config::get('constants.CRTNG_BRND_DISCUSION_TOPIC')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="REPLY_BRND_DISCUSIN_TOPIC" name="REPLY_BRND_DISCUSIN_TOPIC"
                               class="activity_privacy"
                               @if(@$settings['REPLY_BRND_DISCUSIN_TOPIC']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="REPLY_BRND_DISCUSIN_TOPIC">{{Config::get('constants.REPLY_BRND_DISCUSIN_TOPIC')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="NEW_CLSFIED_LST" name="NEW_CLSFIED_LST" class="activity_privacy"
                               @if(@$settings['NEW_CLSFIED_LST']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="NEW_CLSFIED_LST">{{Config::get('constants.NEW_CLSFIED_LST')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="COMENT_PHTO_ALBM" name="COMENT_PHTO_ALBM" class="activity_privacy"
                               @if(@$settings['COMENT_PHTO_ALBM']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="COMENT_PHTO_ALBM">{{Config::get('constants.COMENT_PHTO_ALBM')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="COMENT_YUR_PHTO" name="COMENT_YUR_PHTO" class="activity_privacy"
                               @if(@$settings['COMENT_YUR_PHTO']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="COMENT_YUR_PHTO">{{Config::get('constants.COMENT_YUR_PHTO')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="COMENT_disabledYUR_BTL" name="COMENT_YUR_BTL"
                               class="activity_privacy"
                               @if(@$settings['COMENT_YUR_BTL']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="COMENT_YUR_BTL">{{Config::get('constants.COMENT_YUR_BTL')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="COMENT_YUR_BLG" name="COMENT_YUR_BLG" class="activity_privacy"
                               @if(@$settings['COMENT_YUR_BLG']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="COMENT_YUR_BLG">{{Config::get('constants.COMENT_YUR_BLG')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="COMENT_YUR_CLSFY_LSTIG" name="COMENT_YUR_CLSFY_LSTIG"
                               class="activity_privacy"
                               @if(@$settings['COMENT_YUR_CLSFY_LSTIG']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="COMENT_YUR_CLSFY_LSTIG">{{Config::get('constants.COMENT_YUR_CLSFY_LSTIG')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="COMENT_YUR_EVNT" name="COMENT_YUR_EVNT" class="activity_privacy"
                               @if(@$settings['COMENT_YUR_EVNT']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="COMENT_YUR_EVNT">{{Config::get('constants.COMENT_YUR_EVNT')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="COMENT_YUR_GRP" name="COMENT_YUR_GRP" class="activity_privacy"
                               @if(@$settings['COMENT_YUR_GRP']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="COMENT_YUR_GRP">{{Config::get('constants.COMENT_YUR_GRP')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="COMENT_YUR_PLYLST" name="COMENT_YUR_PLYLST" class="activity_privacy"
                               @if(@$settings['COMENT_YUR_PLYLST']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="COMENT_YUR_PLYLST">{{Config::get('constants.COMENT_YUR_PLYLST')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="COMENT_YUR_POLL" name="COMENT_YUR_POLL" class="activity_privacy"
                               @if(@$settings['COMENT_YUR_POLL']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="COMENT_YUR_POLL">{{Config::get('constants.COMENT_YUR_POLL')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="COMENT_YUR_VID" name="COMENT_YUR_VID" class="activity_privacy"
                               @if(@$settings['COMENT_YUR_VID']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="COMENT_YUR_VID">{{Config::get('constants.COMENT_YUR_VID')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="NEW_EVTS" name="NEW_EVTS" class="activity_privacy"
                               @if(@$settings['NEW_EVTS']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="NEW_EVTS">{{Config::get('constants.NEW_EVTS')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="JOING_EVT" name="JOING_EVT" class="activity_privacy"
                               @if(@$settings['JOING_EVT']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="JOING_EVT">{{Config::get('constants.JOING_EVT')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="UPL_EVT" name="UPL_EVT" class="activity_privacy"
                               @if(@$settings['UPL_EVT']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="UPL_EVT">{{Config::get('constants.UPL_EVT')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="CRTNG_EVT_TOP" name="CRTNG_EVT_TOP" class="activity_privacy"
                               @if(@$settings['CRTNG_EVT_TOP']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="CRTNG_EVT_TOP">{{Config::get('constants.CRTNG_EVT_TOP')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="REPLY_EVT_DISCUSIN_TOPIC" name="REPLY_EVT_DISCUSIN_TOPIC"
                               class="activity_privacy"
                               @if(@$settings['REPLY_EVT_DISCUSIN_TOPIC']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="REPLY_EVT_DISCUSIN_TOPIC">{{Config::get('constants.REPLY_EVT_DISCUSIN_TOPIC')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="FORUM_PROMTION" name="FORUM_PROMTION" class="activity_privacy"
                               @if(@$settings['FORUM_PROMTION']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="FORUM_PROMTION">{{Config::get('constants.FORUM_PROMTION')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="CRTNG_FORUM_TOPIC" name="CRTNG_FORUM_TOPIC" class="activity_privacy"
                               @if(@$settings['CRTNG_FORUM_TOPIC']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="CRTNG_FORUM_TOPIC">{{Config::get('constants.CRTNG_FORUM_TOPIC')}}</label>
                    </div>
                </div>


                <div class="setting-block-item">
                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="REPLY_FORUM_TOPIC" name="REPLY_FORUM_TOPIC" class="activity_privacy"
                               @if(@$settings['REPLY_FORUM_TOPIC']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="REPLY_FORUM_TOPIC">{{Config::get('constants.REPLY_FORUM_TOPIC')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="WHN_BCME_WTH_SOMONE" name="WHN_BCME_WTH_SOMONE"
                               class="activity_privacy"
                               @if(@$settings['WHN_BCME_WTH_SOMONE']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="WHN_BCME_WTH_SOMONE">{{Config::get('constants.WHN_BCME_WTH_SOMONE')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="NEW_GRP_POLL_ENTRY" name="NEW_GRP_POLL_ENTRY"
                               class="activity_privacy"
                               @if(@$settings['NEW_GRP_POLL_ENTRY']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="NEW_GRP_POLL_ENTRY"> {{Config::get('constants.NEW_GRP_POLL_ENTRY')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="NEW_GRP" name="NEW_GRP" class="activity_privacy"
                               @if(@$settings['NEW_GRP']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="NEW_GRP"> {{Config::get('constants.NEW_GRP')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="JOIN_GRP" name="JOIN_GRP" class="activity_privacy"
                               @if(@$settings['JOIN_GRP']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="JOIN_GRP">{{Config::get('constants.JOIN_GRP')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="UPLOD_GRP_PHTO" name="UPLOD_GRP_PHTO" class="activity_privacy"
                               @if(@$settings['UPLOD_GRP_PHTO']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="UPLOD_GRP_PHTO">{{Config::get('constants.UPLOD_GRP_PHTO')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="GRP_PROMTON" name="GRP_PROMTON" class="activity_privacy"
                               @if(@$settings['GRP_PROMTON']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="GRP_PROMTON">{{Config::get('constants.GRP_PROMTON')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="CRTNG_GRP_DISCUSION_TOPIC" name="CRTNG_GRP_DISCUSION_TOPIC"
                               class="activity_privacy"
                               @if(@$settings['CRTNG_GRP_DISCUSION_TOPIC']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="CRTNG_GRP_DISCUSION_TOPIC">{{Config::get('constants.CRTNG_GRP_DISCUSION_TOPIC')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="REPLY_GRP_DISCUSION_TOPIC" name="REPLY_GRP_DISCUSION_TOPIC"
                               class="activity_privacy"
                               @if(@$settings['REPLY_GRP_DISCUSION_TOPIC']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="REPLY_GRP_DISCUSION_TOPIC">{{Config::get('constants.REPLY_GRP_DISCUSION_TOPIC')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="NEW_PLYLST" name="NEW_PLYLST" class="activity_privacy"
                               @if(@$settings['NEW_PLYLST']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="NEW_PLYLST">{{Config::get('constants.NEW_PLYLST')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="REPLY_COMENT_ALBM" name="REPLY_COMENT_ALBM" class="activity_privacy"
                               @if(@$settings['REPLY_COMENT_ALBM']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="REPLY_COMENT_ALBM">{{Config::get('constants.REPLY_COMENT_ALBM')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="REPLY_COMENT_ALB_PHTO" name="REPLY_COMENT_ALB_PHTO"
                               class="activity_privacy"
                               @if(@$settings['REPLY_COMENT_ALB_PHTO']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="REPLY_COMENT_ALB_PHTO">{{Config::get('constants.REPLY_COMENT_ALB_PHTO')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="REPLY_COMTENT_BLG" name="REPLY_COMTENT_BLG" class="activity_privacy"
                               @if(@$settings['REPLY_COMTENT_BLG']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="REPLY_COMTENT_BLG">{{Config::get('constants.REPLY_COMTENT_BLG')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="REPLY_COMENT_CLSFIED" name="REPLY_COMENT_CLSFIED"
                               class="activity_privacy"
                               @if(@$settings['REPLY_COMENT_CLSFIED']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="REPLY_COMENT_CLSFIED">{{Config::get('constants.REPLY_COMENT_CLSFIED')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="NESTD_COMENT_EVT" name="NESTD_COMENT_EVT" class="activity_privacy"
                               @if(@$settings['NESTD_COMENT_EVT']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="NESTD_COMENT_EVT">{{Config::get('constants.NESTD_COMENT_EVT')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="REPLY_COMENT_GRP" name="REPLY_COMENT_GRP" class="activity_privacy"
                               @if(@$settings['REPLY_COMENT_GRP']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="REPLY_COMENT_GRP">{{Config::get('constants.REPLY_COMENT_GRP')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="REPLY_COMENT_POLL" name="REPLY_COMENT_POLL" class="activity_privacy"
                               @if(@$settings['REPLY_COMENT_POLL']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="REPLY_COMENT_POLL">{{Config::get('constants.REPLY_COMENT_POLL')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="REPLY_COMENT_VID" name="REPLY_COMENT_VID" class="activity_privacy"
                               @if(@$settings['REPLY_COMENT_VID']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="REPLY_COMENT_VID">{{Config::get('constants.REPLY_COMENT_VID')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="JOIN_NETWRK" name="JOIN_NETWRK" class="activity_privacy"
                               @if(@$settings['JOIN_NETWRK']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="JOIN_NETWRK">{{Config::get('constants.JOIN_NETWRK')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="NEW_POLL" name="NEW_POLL" class="activity_privacy"
                               @if(@$settings['NEW_POLL']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="NEW_POLL">{{Config::get('constants.NEW_POLL')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="POSTNG_STATS_UPD_PROFILE" name="POSTNG_STATS_UPD_PROFILE"
                               class="activity_privacy"
                               @if(@$settings['POSTNG_STATS_UPD_PROFILE']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="POSTNG_STATS_UPD_PROFILE">{{Config::get('constants.POSTNG_STATS_UPD_PROFILE')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="CHGNG_PHTO" name="CHGNG_PHTO" class="activity_privacy"
                               @if(@$settings['CHGNG_PHTO']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="CHGNG_PHTO">{{Config::get('constants.CHGNG_PHTO')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="ACTVTY_FEED_ITEM" name="ACTVTY_FEED_ITEM" class="activity_privacy"
                               @if(@$settings['ACTVTY_FEED_ITEM']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="ACTVTY_FEED_ITEM">{{Config::get('constants.ACTVTY_FEED_ITEM')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="ACTVTY_ACTION_TG" name="ACTVTY_ACTION_TG" class="activity_privacy"
                               @if(@$settings['ACTVTY_ACTION_TG']['setting_value'] == '1') checked="checked"
                               @endif value="">
                        <label for="ACTVTY_ACTION_TG">{{Config::get('constants.ACTVTY_ACTION_TG')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="TAG" name="TAG" class="activity_privacy"
                               @if(@$settings['TAG']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="TAG">{{Config::get('constants.TAG')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="TG_BRND" name="TG_BRND" class="activity_privacy"
                               @if(@$settings['TG_BRND']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="TG_BRND">{{Config::get('constants.TG_BRND')}}</label>
                    </div>

                    <div class="field-item-checkbox mt20">
                        <input type="checkbox" id="NEW_VID" name="NEW_VID" class="activity_privacy"
                               @if(@$settings['NEW_VID']['setting_value'] == '1') checked="checked" @endif value="">
                        <label for="NEW_VID">{{Config::get('constants.NEW_VID')}}</label>
                    </div>
                </div>
            </div>
            <div class="success" id="popup" onchange="hideMsg();"></div>
        </form>
    </div>
</div>

<script type="text/javascript">

    $('.activity_privacy:not("#DNT_DISPLY_ME_IN_SRCH")').attr('disabled', true);

    function savingSettingItem(evt){
        $.ajaxSetup({
            headers : {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });//for token purpose in laravel

        var settingValue = evt.target.checked ? 1 : 0;
        var item_id      = evt.target.id;
        var postSetting  = '{{url("privacySetting")}}';//save function
        var Category = 'Privacy';
        if(evt.target.click){

            var category = Category;
            var item     = item_id;
            var value    = settingValue;

            // alert(savingData); return;
            jQuery.ajax({
                url : postSetting,
                data : {category : category, item : item, value : value},
                type : 'POST',
                success : function(data){
                    //alert(data);
                    jQuery("#popup").html(data);
                    document.getElementById("popup").style.visibility = "visible";
                    window.setTimeout("hideMsg()", 2000);
                }
            });
        }
    }

    $('.activity_privacy').click(function(evt){
        savingSettingItem(evt);
    });

    $('.profile_item').click(function(evt){
        $.ajaxSetup({
            headers : {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });//for token purpose in laravel

        // var settingValue = evt.target.checked ? 2 : 0;
        var item_id     = evt.target.name;
        var postSetting = '{{url("privacySetting")}}';//save function
        var Category = 'Privacy';
        if(evt.target.click){

            var category = Category;
            var item     = item_id;
            var value    = evt.target.value;

            // alert(savingData); return;
            jQuery.ajax({
                url : postSetting,
                data : {category : category, item : item, value : value},
                type : 'POST',
                success : function(data){
                    // alert(data);
                    jQuery("#popup").html(data);
                    document.getElementById("popup").style.visibility = "visible";
                    window.setTimeout("hideMsg()", 2000);
                }
            });
        }
    });


</script>
<script type="text/javascript">
    function hideMsg(){
        document.getElementById("popup").style.visibility = "hidden";
    }

</script>
<style>
    #popup {
        height: 20px;
        position: absolute;
        top: 10px;
        visibility: hidden;
        width: 200px;
        z-index: 100;
        border: 1px solid;
        margin: 8px 0px;
        padding: 10px 10px 10px 30px;
        background: rgb(221, 241, 187) no-repeat 10px center;
        color: #ee4b08;
        position: fixed;
    }


</style>
@endsection
