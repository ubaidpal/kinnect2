@extends('layouts.masterDynamic')
@section('content')
@include('includes.setting-left-nav')
        <!--Create Album-->
<div class="community-ad">
    <div class="form-container settings">
        <form  role="form" method="Get" action="{{ url('notificationSetting') }}" >
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="setting-title">
                <span>{{Config::get('constants.NOTIFICATION')}}</span>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>

            <div class="field-item">
                <label for="">{{Config::get('constants.NOTIFICATION')}}</label>
                <p class="col-dark">
                    {{Config::get('constants.EMAIL_ALERT')}}
                </p>
            </div>

            <div class="setting-block cf">
                <div class="setting-block-item">
                    <div class="field-item mt15">
                        <label for="">{{Config::get('constants.GEN')}}</label>
                    </div>

                    <div class="field-item-checkbox">
                        <input type="checkbox" id="WEN_PEOPLE_COMTNS_ON_THING_I_POST" name="WEN_PEOPLE_COMTNS_ON_THING_I_POST" class="setting_item" @if(@$settings['WEN_PEOPLE_COMTNS_ON_THING_I_POST']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_COMTNS_ON_THING_I_POST">{{Config::get('constants.WEN_PEOPLE_COMTNS_ON_THING_I_POST')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_COMTNS_ON_SAME_THING_ME" name="WEN_PEOPLE_COMTNS_ON_SAME_THING_ME" class="setting_item" @if(@$settings['WEN_PEOPLE_COMTNS_ON_SAME_THING_ME']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_COMTNS_ON_SAME_THING_ME">{{Config::get('constants.WEN_PEOPLE_COMTNS_ON_SAME_THING_ME')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_ACPT_MY_FRND_REQUST" name="WEN_PEOPLE_ACPT_MY_FRND_REQUST" class="setting_item" @if(@$settings['WEN_PEOPLE_ACPT_MY_FRND_REQUST']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_ACPT_MY_FRND_REQUST">{{Config::get('constants.WEN_PEOPLE_ACPT_MY_FRND_REQUST')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_I_RECIVE_A_FRND_REQUST" name="WEN_I_RECIVE_A_FRND_REQUST" class="setting_item" @if(@$settings['WEN_I_RECIVE_A_FRND_REQUST']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_I_RECIVE_A_FRND_REQUST">{{Config::get('constants.WEN_I_RECIVE_A_FRND_REQUST')}}</label>
                    </div>


                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_I_LIKE_THING_I_POST" name="WEN_PEOPLE_I_LIKE_THING_I_POST" class="setting_item" @if(@$settings['WEN_PEOPLE_I_LIKE_THING_I_POST']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_I_LIKE_THING_I_POST">{{Config::get('constants.WEN_PEOPLE_I_LIKE_THING_I_POST')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_COMTNS_ON_THING_I_LIKED" name="WEN_PEOPLE_COMTNS_ON_THING_I_LIKED" class="setting_item" @if(@$settings['WEN_PEOPLE_COMTNS_ON_THING_I_LIKED']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_COMTNS_ON_THING_I_LIKED">{{Config::get('constants.WEN_PEOPLE_COMTNS_ON_THING_I_LIKED')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_RPLY_ON_THING_I_LIKED" name="WEN_PEOPLE_RPLY_ON_THING_I_LIKED" class="setting_item" @if(@$settings['WEN_PEOPLE_RPLY_ON_THING_I_LIKED']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_RPLY_ON_THING_I_LIKED">{{Config::get('constants.WEN_PEOPLE_RPLY_ON_THING_I_LIKED')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_I_RECEIVE_MSG" name="WEN_I_RECEIVE_MSG" class="setting_item" @if(@$settings['WEN_I_RECEIVE_MSG']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_I_RECEIVE_MSG">{{Config::get('constants.WEN_I_RECEIVE_MSG')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_POST_ON_MY_PROFILE" name="WEN_PEOPLE_POST_ON_MY_PROFILE" class="setting_item" @if(@$settings['WEN_PEOPLE_POST_ON_MY_PROFILE']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_POST_ON_MY_PROFILE">{{Config::get('constants.WEN_PEOPLE_POST_ON_MY_PROFILE')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_REPLY_ON_MY_PROFILE" name="WEN_PEOPLE_REPLY_ON_MY_PROFILE" class="setting_item" @if(@$settings['WEN_PEOPLE_REPLY_ON_MY_PROFILE']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_REPLY_ON_MY_PROFILE">{{Config::get('constants.WEN_PEOPLE_REPLY_ON_MY_PROFILE')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_REPLY_ON_SAME_THING_ME" name="WEN_PEOPLE_REPLY_ON_SAME_THING_ME" class="setting_item" @if(@$settings['WEN_PEOPLE_REPLY_ON_SAME_THING_ME']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_REPLY_ON_SAME_THING_ME">{{Config::get('constants.WEN_PEOPLE_REPLY_ON_SAME_THING_ME')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_ACTVITY_FEED_ITM_IS_SHARED" name="WEN_ACTVITY_FEED_ITM_IS_SHARED" class="setting_item" @if(@$settings['WEN_ACTVITY_FEED_ITM_IS_SHARED']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_ACTVITY_FEED_ITM_IS_SHARED">{{Config::get('constants.WEN_ACTVITY_FEED_ITM_IS_SHARED')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_I_M_TAG_PHTO_&_OTHR_PLACE" name="WEN_I_M_TAG_PHTO_&_OTHR_PLACE" class="setting_item" @if(@$settings['WEN_I_M_TAG_PHTO_&_OTHR_PLACE']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_I_M_TAG_PHTO_&_OTHR_PLACE">{{Config::get('constants.WEN_I_M_TAG_PHTO_&_OTHR_PLACE')}}</label>
                    </div>



                    <div class="field-item mt15">
                        <label for="">Forum</label>
                    </div>

                    <div class="field-item-checkbox">
                        <input type="checkbox" id="WEN_I_GIVN_MODERATAR_STATUS_IN_FORUM" name="WEN_I_GIVN_MODERATAR_STATUS_IN_FORUM" class="setting_item" @if(@$settings['WEN_I_GIVN_MODERATAR_STATUS_IN_FORUM']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_I_GIVN_MODERATAR_STATUS_IN_FORUM">{{Config::get('constants.WEN_I_GIVN_MODERATAR_STATUS_IN_FORUM')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_REPLY_FORUM_TOPIC_I_REPLIED" name="WEN_PEOPLE_REPLY_FORUM_TOPIC_I_REPLIED" class="setting_item" @if(@$settings['WEN_PEOPLE_REPLY_FORUM_TOPIC_I_REPLIED']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_REPLY_FORUM_TOPIC_I_REPLIED">{{Config::get('constants.WEN_PEOPLE_REPLY_FORUM_TOPIC_I_REPLIED')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_REPLY_FORUM_THAT_I_CREATE" name="WEN_PEOPLE_REPLY_FORUM_THAT_I_CREATE" class="setting_item" @if(@$settings['WEN_PEOPLE_REPLY_FORUM_THAT_I_CREATE']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_REPLY_FORUM_THAT_I_CREATE">{{Config::get('constants.WEN_PEOPLE_REPLY_FORUM_THAT_I_CREATE')}}</label>
                    </div>




                    <div class="field-item mt15">
                        <label for="">Groups</label>
                    </div>

                    <div class="field-item-checkbox">
                        <input type="checkbox" id="WEN_MY_REQUST_TO_JOIN_GRP_APPROVED" name="WEN_MY_REQUST_TO_JOIN_GRP_APPROVED" class="setting_item" @if(@$settings['WEN_MY_REQUST_TO_JOIN_GRP_APPROVED']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_MY_REQUST_TO_JOIN_GRP_APPROVED">{{Config::get('constants.WEN_MY_REQUST_TO_JOIN_GRP_APPROVED')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_SOME_PEOPLE_REQUEST_JOIN_GRP_I_CREATED" name="WEN_SOME_PEOPLE_REQUEST_JOIN_GRP_I_CREATED" class="setting_item" @if(@$settings['WEN_SOME_PEOPLE_REQUEST_JOIN_GRP_I_CREATED']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_SOME_PEOPLE_REQUEST_JOIN_GRP_I_CREATED">{{Config::get('constants.WEN_SOME_PEOPLE_REQUEST_JOIN_GRP_I_CREATED')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED" name="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED" class="setting_item" @if(@$settings['WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED">{{Config::get('constants.WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE" name="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE" class="setting_item" @if(@$settings['WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE">{{Config::get('constants.WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_I_INVITE_TO_JOIN_GRP" name="WEN_I_INVITE_TO_JOIN_GRP" class="setting_item" @if(@$settings['WEN_I_INVITE_TO_JOIN_GRP']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_I_INVITE_TO_JOIN_GRP">{{Config::get('constants.WEN_I_INVITE_TO_JOIN_GRP')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_I_M_GIVN_STATUS_IN_GRP" name="WEN_I_M_GIVN_STATUS_IN_GRP" class="setting_item" @if(@$settings['WEN_I_M_GIVN_STATUS_IN_GRP']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_I_M_GIVN_STATUS_IN_GRP">{{Config::get('constants.WEN_I_M_GIVN_STATUS_IN_GRP')}}</label>
                    </div>
                </div>


                <div class="setting-block-item">
                    <div class="field-item mt15">
                        <label for="">Blogs</label>
                    </div>

                    <div class="field-item-checkbox">
                        <input type="checkbox" id="WEN_NEW_BLG_ENTRY_POST_BY_MEMBR_U_SUBCRIBE" name="WEN_NEW_BLG_ENTRY_POST_BY_MEMBR_U_SUBCRIBE" class="setting_item" @if(@$settings['WEN_NEW_BLG_ENTRY_POST_BY_MEMBR_U_SUBCRIBE']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_NEW_BLG_ENTRY_POST_BY_MEMBR_U_SUBCRIBE">{{Config::get('constants.WEN_NEW_BLG_ENTRY_POST_BY_MEMBR_U_SUBCRIBE')}}</label>
                    </div>



                    <div class="field-item mt15">
                        <label for="">Brands</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_MY_REQUST_TO_JOIN_BRND_APPROVED" name="WEN_MY_REQUST_TO_JOIN_BRND_APPROVED" class="setting_item" @if(@$settings['WEN_MY_REQUST_TO_JOIN_BRND_APPROVED']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_MY_REQUST_TO_JOIN_BRND_APPROVED">{{Config::get('constants.WEN_MY_REQUST_TO_JOIN_BRND_APPROVED')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_SOME_PEOPLE_REQUEST_JOIN_BRND_I_CREATED" name="WEN_SOME_PEOPLE_REQUEST_JOIN_BRND_I_CREATED" class="setting_item" @if(@$settings['WEN_SOME_PEOPLE_REQUEST_JOIN_BRND_I_CREATED']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_SOME_PEOPLE_REQUEST_JOIN_BRND_I_CREATED">{{Config::get('constants.WEN_SOME_PEOPLE_REQUEST_JOIN_BRND_I_CREATED')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED_BRND" name="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED_BRND" class="setting_item" @if(@$settings['WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED_BRND']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED_BRND">{{Config::get('constants.WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED_BRND')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE_BRND" name="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE_BRND" class="setting_item" @if(@$settings['WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE_BRND']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE_BRND">{{Config::get('constants.WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE_BRND')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_I_INVITE_TO_JOIN_BRND" name="WEN_I_INVITE_TO_JOIN_BRND" class="setting_item" @if(@$settings['WEN_I_INVITE_TO_JOIN_BRND']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_I_INVITE_TO_JOIN_BRND">{{Config::get('constants.WEN_I_INVITE_TO_JOIN_BRND')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_I_M_GIVN_STATUS_IN_BRND" name="WEN_I_M_GIVN_STATUS_IN_BRND" class="setting_item" @if(@$settings['WEN_I_M_GIVN_STATUS_IN_BRND']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_I_M_GIVN_STATUS_IN_BRND">{{Config::get('constants.WEN_I_M_GIVN_STATUS_IN_BRND')}}</label>
                    </div>



                    <div class="field-item mt15">
                        <label for="">Events</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_MY_REQUST_TO_ATTND_AN_EVT_APPROVED" name="WEN_MY_REQUST_TO_ATTND_AN_EVT_APPROVED" class="setting_item" @if(@$settings['WEN_MY_REQUST_TO_ATTND_AN_EVT_APPROVED']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_MY_REQUST_TO_ATTND_AN_EVT_APPROVED">{{Config::get('constants.WEN_MY_REQUST_TO_ATTND_AN_EVT_APPROVED')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_SOME_PEOPLE_REQUEST_TO_JOIN_MY_EVNT" name="WEN_SOME_PEOPLE_REQUEST_TO_JOIN_MY_EVNT" class="setting_item" @if(@$settings['WEN_SOME_PEOPLE_REQUEST_TO_JOIN_MY_EVNT']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_SOME_PEOPLE_REQUEST_TO_JOIN_MY_EVNT">{{Config::get('constants.WEN_SOME_PEOPLE_REQUEST_TO_JOIN_MY_EVNT')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED_EVNT" name="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED_EVNT" class="setting_item" @if(@$settings['WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED_EVNT']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED_EVNT">{{Config::get('constants.WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_REPLIED_EVNT')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE_EVNT" name="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE_EVNT" class="setting_item" @if(@$settings['WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE_EVNT']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE_EVNT">{{Config::get('constants.WEN_PEOPLE_REPLY_DISCUS_TOPIC_I_CREATE_EVNT')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_I_INVITE_TO_ATTND_BRND" name="WEN_I_INVITE_TO_ATTND_BRND" class="setting_item" @if(@$settings['WEN_I_INVITE_TO_ATTND_BRND']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_I_INVITE_TO_ATTND_BRND">{{Config::get('constants.WEN_I_INVITE_TO_ATTND_BRND')}}</label>
                    </div>



                    <div class="field-item mt15">
                        <label for="">Videos</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_VID_PROCEED" name="WEN_VID_PROCEED" class="setting_item" @if(@$settings['WEN_VID_PROCEED']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_VID_PROCEED">{{Config::get('constants.WEN_VID_PROCEED')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_VID__FAILED_TO_PROCEED" name="WEN_VID__FAILED_TO_PROCEED" class="setting_item" @if(@$settings['WEN_VID__FAILED_TO_PROCEED']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_VID__FAILED_TO_PROCEED">{{Config::get('constants.WEN_VID__FAILED_TO_PROCEED')}}</label>
                    </div>



                    <div class="field-item mt15">
                        <label for="">YN - Advanced Feed</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_COMENT_THING_I_FOLLOW" name="WEN_PEOPLE_COMENT_THING_I_FOLLOW" class="setting_item" @if(@$settings['WEN_PEOPLE_COMENT_THING_I_FOLLOW']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_COMENT_THING_I_FOLLOW">{{Config::get('constants.WEN_PEOPLE_COMENT_THING_I_FOLLOW')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_PEOPLE_LIKE_THING_I_FOLLOW" name="WEN_PEOPLE_LIKE_THING_I_FOLLOW" class="setting_item" @if(@$settings['WEN_PEOPLE_LIKE_THING_I_FOLLOW']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_PEOPLE_LIKE_THING_I_FOLLOW">{{Config::get('constants.WEN_PEOPLE_LIKE_THING_I_FOLLOW')}}</label>
                    </div>

                    <div class="field-item-checkbox mt15">
                        <input type="checkbox" id="WEN_I_M_TAG_IN_POST" name="WEN_I_M_TAG_IN_POST" class="setting_item" @if(@$settings['WEN_I_M_TAG_IN_POST']['setting_value'] == '1') checked="checked" @endif value="" />
                        <label for="WEN_I_M_TAG_IN_POST">{{Config::get('constants.WEN_I_M_TAG_IN_POST')}}</label>
                    </div>
                </div>

            </div>
            <div class="success" id="popup" onchange="hideMsg();"></div>
        </form>


    </div>
</div>
{{--<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });//for token purpose in laravel
    $(function(){
        var value = '{{$settings[0]->setting_value}}';
        var value2 = value.split(',');
        for(i = 0; i<value.length; i++){
            $('input[class="setting_item"][value="'+ value2[i] +'"]').attr("checked",true);

        }
    })

</script>--}}
<script type="text/javascript">

    function savingSettingItem(evt)
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });//for token purpose in laravel

        var settingValue = evt.target.checked ? 1 : 0;
        var item_id = evt.target.id;
        var postSetting = '{{url("privacySetting")}}';//save function
        var Category    = 'Notification';
        if (evt.target.click) {

            var category =   Category;
            var item     =   item_id;
            var value    =   settingValue;

            // alert(savingData); return;
            jQuery.ajax({
                url: postSetting,
                data: {category: category,item:item,value:value} ,
                type: 'POST',
                success: function (data) {
                    // alert(data);
                    // alert(data);
                    jQuery("#popup").html(data);
                    document.getElementById("popup").style.visibility = "visible";
                    window.setTimeout("hideMsg()", 2000);
                }
            });
        }
    }

    $('.setting_item').click(function(evt){
        savingSettingItem(evt);
    });

</script>
<script type="text/javascript">
    function hideMsg()
    {
        document.getElementById("popup").style.visibility = "hidden";
    }

</script>
<style>
    #popup {
        background-color: #dff2bf;
        height: 20px;
        position: absolute;
        top: 10px;
        visibility: hidden;
        width: 200px;
        z-index: 100;
        border: 1px solid;
        margin: 8px 0px;
        padding: 10px 10px 10px 30px;
        background-repeat: no-repeat;
        background-position: 10px center;
        color: #ee4b08;
        position: fixed;
    }


</style>
@endsection
