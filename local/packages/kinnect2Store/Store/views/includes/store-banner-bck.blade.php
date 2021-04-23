<?php
if(isset($url_user_id)) {
    $url_user_id = $url_user_id;
} else {
    $url_user_id = Auth::user()->id;
}
$user = getUserDetail($url_user_id);

if($user['user_type'] == Config::get('constants.REGULAR_USER')) {
    $detail = $user->consumer_detail;
    $name   = $user->displayname;
} else {
    $detail = $user['brand_detail'];
    $name   = $detail['brand_name'];
}

?>
<div class="full-width-banner">
    <div class="banner-container">
        <div class="store-button-wrapper">
            <?php $storeLinkLable = "Store"; ?>
            @if($url_user_id == Auth::user()['id'] || $url_user_id == Auth::user()['username'])
                <?php $storeLinkLable = "Store Preview"; ?>
            @endif

            @if(env("STORE_ENABLED", TRUE) && isStoreHaveProducts( $user->username ) > 0)
                @if($user->brand_detail->store_created == 1 and $storeLinkLable != "Store Preview")
                    <a href="{{url('store/'.$user->username)}}"
                       title="Go to store of {{$user->displayname}}">{{$storeLinkLable}}</a>
                @endif
            @endif

            @if(env("STORE_ENABLED", TRUE) && $user['user_type'] == 2)
                @if($url_user_id == Auth::user()['id'] || $url_user_id == Auth::user()['username'])

                    @if(Auth::user()->brand_detail->store_created == 0)
                        <a href="{{url('store/'.$user->username.'/admin/categories')}}"
                           title="{{$user->displayname}} go to Add category and enable your store">
                            Create Store
                        </a>
                    @else
                        <a href="{{url('store/'.$user->username.'/admin/orders/')}}"
                           title="{{$user->displayname}} go to Admin area of your store">
                            Admin</a>
                    @endif
                @endif
            @endif
        </div>
        @if($user->id == Auth::user()->id)
            <div class="change-cover">
                <span></span>
                <a href="javascript:void(0);" id="change_cover_btn" class="change_cover_btn"
                   title="Hey! {{ ucwords( @$user->name ) }} click here to change your cover photo."></a>
            </div>
            <input type="hidden" name="group_id" id="group_id" value="0">
        @endif
        <div class="baner-nameNpic">

            @if($user->id == Auth::user()->id)
                <div class="change-dp">
                    <span></span>
                    <a href="javascript:void(0);" id="change_dp_btn" class="change_dp_btn"
                       title="Hey! {{ ucwords( @$user->name ) }} click here to change your profile photo."></a>
                </div>
            @endif
            <img id="profile_dp_img" src="{{Kinnect2::getPhotoUrl($user->photo_id, $user->id, 'user', 'thumb_normal')}}"
                 alt="Profile Image"
                 title="{{ ucwords( $name) }}'s profile photo."/>

            <div class="name-set">{{ ucwords( $name ) }}</div>

        </div>
        <div class="banner-links store" data-user="{{$user->username}}" data-url="/profile/profile-view"
             data-loggedinusertype="{{Auth::user()->user_type}}" data-profileinusertype="{{$user->user_type}}">

            <a href="{{Kinnect2::profileAddress($user)}}" title="What's New"
               class="@if(Request::is('profile/*') || Request::is('brand/*')) active @endif tab">What's New</a>
            @if(Auth::user()->id == $user->id)
                <a href="{{Kinnect2::profileAddress($user)}}/activity-log" title="Activity Log">Activity Log
                </a>
            @endif
            <a href="{{Kinnect2::profileAddress($user)}}/info" title="Info">Info</a>
            {{--@if($user->user_type == Config::get('constants.REGULAR_USER'))
                <a href="javascript:void(0);" title="Kinnectors" class="tab" data-target="kinnectors" data-ajax="true">Kinnectors
                    ({{count(@$friends)}})</a>
            @endif--}}

            <a href="{{Kinnect2::profileAddress($user)}}/kinnectors" title="Kinnectors">
                Kinnectors({{kinnectors_count($user->id)}})
            </a>
            {{-- @if($user->user_type == Config::get('constants.REGULAR_USER'))--}}
            <a href="{{Kinnect2::profileAddress($user)}}/following" title="Following">
                Following({{\Kinnect2::myAllBrandsCount($user->id)}})
            </a>
            {{-- <@endif--}}
            @if($user->user_type == Config::get('constants.BRAND_USER'))
                <a href="{{Kinnect2::profileAddress($user)}}/followers" title="Followers">
                    Followers({{get_brand_kinnectors_count($user->id)}})
                </a>
            @endif
            <a href="{{Kinnect2::profileAddress($user)}}/albums">Album({{album_count($user->id)}})</a>

            {{--<a href="javascript:void(0);" title="More" class="tab" data-target="more" data-ajax="true">More
                <span></span>
            </a>--}}

        </div>
        <?php
        $regular = Config::get('constants.REGULAR_USER');
        $brand = Config::get('constants.BRAND_USER');
        ?>
        <div class="p_btn_div">

            @if($user->user_type == $regular AND Auth::user()->user_type == $regular or ($user->user_type == $brand AND Auth::user()->user_type == $regular) OR ($user->user_type != $brand AND Auth::user()->user_type != $regular))
            @if(Auth::user()->id != $user->id && is_friend_request_sent(Auth::user()->id, $user->id))

                    <!--<a href="#"><span class="add_frnd">Respond to Friend Request</span></a> Respond Button -->
            <a class="friend-toggle" href="{{URL::to('friends/delete/'  .$user->id)}}">
                <span class="check"> Cancel Request</span>
            </a>
            {{--<a href="#"><span class="check">Following</span></a>--}}

            @elseif(Auth::user()->id != $user->id &&  is_friend_request_received(Auth::user()->id, $user->id))

                <a class="friend-toggle" href="{{URL::to('friends/confirm/'  .$user->id)}}">
                    <span class="add_frnd">Confirm Request</span>
                </a>
                <a class="friend-toggle @if(!allowed_to_add_kinnector($user->id,Auth::user()->id,$user->user_type,Auth::user()->user_type)) noToggleBtn @endif"
                   href="{{URL::to('friends/delete/'  .$user->id)}}">
                    <span class="check"> Delete Request</span>
                </a>

            @elseif(Auth::user()->id != $user->id &&  !is_friend(Auth::user()->id, $user->id) > 0 AND ((Auth::user()->user_type == $regular AND $user->user_type != $brand) OR ((Auth::user()->user_type == $brand AND $user->user_type == $regular))) )


                <a class="friend-toggle" href="{{URL::to('friends/add-friend/'  .$user->id)}}">
                    <span class="check"> Add Friend</span>
                </a>

            @elseif(Auth::user()->id != $user->id &&  is_friend(Auth::user()->id, $user->id) > 0)
                <span class="trigger-wrapper trigger">

                    <a class="" href="#">
                        <span class="check droptip">Friends</span>
                    </a>

                    <div class="drop">

                        <a class="friend-toggle" href="{{URL::to('friends/unfriend/'.$user->id)}}">Un Friend</a>
                    </div>
                </span>
            @endif
            @endif

            @if($user->user_type != Config::get('constants.REGULAR_USER') )
                @if(Auth::user()->id != $user->id && is_followed(Auth::user()->id, $user->id, Auth::user()->user_type) > 0)
                    <span class="trigger-wrapper trigger">
                        <a class="" href="#">
                            <span class="check droptip">Following</span>
                        </a>

                        <div class="drop">
                            <a class="friend-toggle" href="{{URL::to('unfollow?brand_id='  .$user->id)}}">Un Follow</a>
                        </div>
                    </span>
                @elseif(Auth::user()->id != $user->id)

                    <a class="friend-toggle" href="{{URL::to('follow?brand_id='  .$user->id)}}">
                        <span class="check">Follow</span>
                    </a>

                @endif
            @endif

            @if($user->user_type == Config::get('constants.BRAND_USER') && Auth::user()->id != $user->id)

                <a href="javascript:void(0);" title="Invite users to follow this brand" class="js-open-modal"
                   data-modal-id="popup2">
                    @if(Auth::user()->user_type == Config::get('constants.BRAND_USER'))
                        Invite People
                    @else
                        Invite Friends
                    @endif
                </a>

            @endif
        </div>
    </div>
    <div class="banner-bottom-bg"></div>
    <div class="cover-photo-container" id="cover_photo_div">
        <img src="{{Kinnect2::getPhotoUrl($user->cover_photo_id, $user->id, 'user', 'cover_photo')}}" id="cover_photo"
             width="100%"
             height="300" alt="cover" title="cover"/>
    </div>
    <div class="change-cover-photo" style="display:none; width:100%;height:300px;overflow:hidden;" id="crop_div">

    </div>

</div>
<div class="croppr-overly" style="display: none"></div>

<div id="light" class="white_content">
    <div id="edit_profile_photo_inline" class="edit_profile_photo_inline" tabindex='1' style="display:none;">
        <div class="image-editor">
            <div class="feed_viewmore" id="feed_loading" style="display: none;">
                <img src='{!! asset('local/public/images/loading.gif') !!}'/>
            </div>

            <div id="select_profile_image" class="select_profile_image"><em>Browse</em> or drop photo</div>
            <input type="file" id="select_profile_file" onchange="show_edit_photo();" class="cropit-image-input"
                   style="display:none;">
            <!-- .cropit-image-preview-container is needed for background image to work -->

            <div class="cropit-image-preview-container">
                <div class="cropit-image-preview" crossorigin="anonymous"></div>
            </div>
            <div class="image-size-label">
                Resize image
            </div>
            <div id="input-div">
                <input type="range" class="cropit-image-zoom-input" min="0" max="1" step="0.01">
            </div>
            <button class="export" onclick="">Save</button>
            <button class="cancel_profile_light_box" onclick="cancel_profile_light_box();">Cancel</button>
        </div>
    </div>
</div><!-- lightbox -->
<div id="fade" class="black_overlay"></div>
<script src="{!! asset('local/public/assets/js/croppic.min.js') !!}" type="text/javascript "></script>
<link rel="stylesheet" type="text/css" href="{!! asset('local/public/assets/css/croppic.css') !!}">
<script type="text/javascript">

    function cancel_profile_light_box() {
        jQuery("body").css('height', "auto");
        jQuery("#edit_profile_photo_inline").hide();
        jQuery("#feed_loading").hide();
        jQuery("#light").hide();
        jQuery("#fade").hide();
        jQuery(".croppr-overly").hide();
    }//cancel_profile_light_box

    function show_edit_photo() {
        $(".export").html('save');

        var currentWindowHeight = jQuery(window).height();
        jQuery('body').css('height', currentWindowHeight);
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';
        jQuery("#edit_profile_photo_inline").show();
        jQuery(".croppr-overly").show();
    }//show_edit_photo()

    function changeMimeType() {
    }//changeMimeType()

    jQuery("#change_dp_btn").click(function () {
        show_edit_photo();
    });

    jQuery("#select_profile_image").click(function () {
        $(".export").show();
        jQuery("#select_profile_file").trigger('click');
    });

    jQuery(function () {

        jQuery('.export').click(function () {
            var imageData = jQuery('.image-editor').cropit('export');

            function dataURItoBlob2(dataURI) {
                var binary = atob(dataURI.split(',')[1]);
                var array = [];
                for (var i = 0; i < binary.length; i++) {
                    array.push(binary.charCodeAt(i));
                }
                return new Blob([new Uint8Array(array)], {type: 'image/jpeg'});
            }

            canvas = dataURItoBlob2(imageData);
            blob = canvas;

            $(".export").html('saving..');

            var filename = 'imageData';

            var data = new FormData();
            data.append('file', blob);

            $.ajax({
                url: "{{url('user/change/profile/')}}",
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    $('#profile_dp_img').attr('src', data);
                    cancel_profile_light_box();
                },
                error: function () {
                    $(".export").html('save');
                    alert("something bad happened! Try again.");
                }
            });

            return;
        });// click .export

        var cropperOptions = {
            customUploadButtonId: 'change_cover_btn',
            cropData: {
                "group_id": $('#group_id').val(),
            },
            loaderHtml: '<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
            modal: false,
            doubleZoomControls: false,
            rotateControls: false,
            processInline: true,
            imgEyecandy: false,
            cropUrl: '{{url('/saveCoverPhoto')}}',
            onAfterImgUpload: function () {
                jQuery('#cover_photo_div').css('display', 'none');
                jQuery('#crop_div').css({'display': '', 'z-index': '5'});
                jQuery('#group-details-wrapper').css('display', 'none');
                jQuery('.banner-bottom-bg').css('display', 'none');
            },
            onAfterImgCrop: function (res) {
                jQuery('#cover_photo').attr('src', res.path);
                jQuery('#cover_photo_div').css('display', '');
                jQuery('#crop_div').css('display', 'none');
                jQuery('#group-details-wrapper').css('display', '');
                jQuery('.banner-bottom-bg').css('display', '');
                jQuery('#change_cover_btn').css('display', '');
            },
            onReset: function (e) {
                jQuery('#cover_photo_div').css('display', '');
                jQuery('#crop_div').css('display', 'none');
                jQuery('#group-details-wrapper').css('display', '');
                jQuery('.banner-bottom-bg').css('display', '');
                jQuery('#change_cover_btn').css('display', '');
            }
        }

        var cropperHeader = new Croppic('crop_div', cropperOptions);
    });

</script>
