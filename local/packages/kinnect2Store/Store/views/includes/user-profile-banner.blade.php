<?php
if ($user->user_type == Config::get('constants.REGULAR_USER')) {
    $detail = $user->consumer_detail;
    $name = $user->displayname;
} else {
    $detail = $user->brand_detail;
    $name = $detail->brand_name;
}

?>
<div class="full-width-banner">
    <div class="banner-container">
        @if($user->id == Auth::user()->id)
            <div class="change-cover">
                <span></span>
                <a href="javascript:void(0);" id="change_cover_btn" class="change_cover_btn"
                   title="Hey! {{ ucwords( @$user->name ) }} click here to change your cover photo."></a>

                <form class="form-cover-dp" method="post" action="{!! url('saveCoverPhoto') !!}"
                      enctype="multipart/form-data">
                    <input type="file" id="file1" name="photo" style="display:none"/>
                    <input type="hidden" name="pos_y" id="pos_y">
                    <input type="hidden" name="pos_x" id="pos_x">
                </form>
            </div>
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
            <div class="store-button-wrapper">
                @if($user->user_type == 2)
                    @if($user->id == Auth::user()->id)
                        @if(env("STORE_ENABLED", true) && isStoreHaveProducts( $user->username ) == 1)
                            <a href="{{url('/store/'.$user->username)}}" title="Preview your store of {{$user->displayname}}">Store Preview</a>
                        @endif
                        <a href="{{url('store/'.$user->username.'/admin/categories/')}}" title="{{$user->displayname}} go to Admin area of your store">Admin</a>
                    @else
                        @if(env("STORE_ENABLED", true) && isStoreHaveProducts( $user->username ) == 1)
                            <a href="{{url('/store/'.$user->username)}}" title="Go to store of {{$user->displayname}}">Store</a>
                        @endif
                    @endif
                @endif
            </div>
        <div class="banner-links" data-user="{{$user->username}}" data-url="/profile/profile-view">

            <a href="javascript:void(0);" title="What's New" class="@if(Request::is('profile/*')) active @endif tab"
               data-target="whats-new" data-ajax="true">What's New</a>
            <a href="javascript:void(0);" title="Activity Log" class="tab" data-target="activity-log" data-ajax="true">Activity
                Log ({{count_all_activity($user->id)}})
            </a>
            <a href="javascript:void(0);" title="Info" class="tab " data-target="info" data-ajax="true">Info</a>
            <a href="javascript:void(0);" title="Kinnectors" class="tab" data-target="kinnectors" data-ajax="true">Kinnectors
                ({{count(@$friends)}})</a>
            <a href="javascript:void(0);" title="Album" class="tab " data-target="albums"
               data-ajax="true">Album({{album_count($user->id)}})</a>

            <a href="javascript:void(0);" title="More" class="tab" data-target="more" data-ajax="true">More
                <span></span></a>

        </div>

        @if($user->user_type == Config::get('constants.REGULAR_USER'))
            @if(Auth::user()->id != $user->id && is_friend_request_sent(Auth::user()->id, $user->id))
                <div class="p_btn_div">
                    <!--<a href="#"><span class="add_frnd">Respond to Friend Request</span></a> Respond Button -->
                    <a class="friend-toggle" href="{{URL::to('friends/delete/'  .$user->id)}}">
                        <span class="check"> Request Sent(Cancel)</span>
                    </a>
                    {{--<a href="#"><span class="check">Following</span></a>--}}
                </div>
            @elseif(Auth::user()->id != $user->id &&  is_friend_request_received(Auth::user()->id, $user->id))
                <div class="p_btn_div">
                    <a class="friend-toggle" href="{{URL::to('friends/confirm/'  .$user->id)}}">
                        <span class="add_frnd">Respond to Friend Request</span>
                    </a>
                    <a class="friend-toggle" href="{{URL::to('friends/delete/'  .$user->id)}}">
                        <span class="check"> Delete Request</span>
                    </a>
                </div>
            @elseif(Auth::user()->id != $user->id &&  !is_friend(Auth::user()->id, $user->id) > 0)
                <div class="p_btn_div">

                    <a class="friend-toggle" href="{{URL::to('friends/add-friend/'  .$user->id)}}">
                        <span class="check"> Add Friend</span>
                    </a>
                </div>
            @elseif(Auth::user()->id != $user->id &&  is_friend(Auth::user()->id, $user->id) > 0)
                <div class="p_btn_div " id="trigger">

                    <a class="" href="#">
                        <span class="check">Friends</span>
                    </a>

                    <div id="drop">

                        <a class="friend-toggle" href="{{URL::to('friends/unfriend/'.$user->id)}}">Un Friend</a>
                    </div>
                </div>
            @endif
        @else
            @if(Auth::user()->id != $user->id && is_followed(Auth::user()->id, $user->id) > 0)

                <div class="p_btn_div " id="trigger">

                    <a class="" href="#">
                        <span class="check">Following</span>
                    </a>

                    <div id="drop">

                        <a class="friend-toggle" href="{{URL::to('unfollow?brand_id='  .$user->id)}}">Un Follow</a>
                    </div>
                </div>
            @else
                <div class="p_btn_div">

                    <a class="friend-toggle" href="{{URL::to('follow?brand_id='  .$user->id)}}">
                        <span class="check"> Follow</span>
                    </a>
                </div>
            @endif
        @endif

    </div>
    <div class="banner-bottom-bg"></div>
    <div class="cover-photo-container" id="cover_photo_div">
        <img src="{{Kinnect2::getPhotoUrl($user->cover_photo_id, $user->id, 'user')}}" id="cover_photo" width="100%"
             height="300" alt="cover" title="cover"/>
    </div>
    <div class="change-cover-photo" style="display:none; width:100%;height:300px;overflow:hidden;" id="crop_div">
        <div id="draggable">
            <img src="" class="cover-photo-alt" width="100%">
        </div>
        <div class="p_btn_div cover-p-btn">
            <a href="#" class="save-btn-cover">Save</a>
        </div>
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
    });
</script>
