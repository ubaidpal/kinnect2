<div class="full-width-banner">
<div class="banner-container">

    <div class="change-cover">
        <span></span>
        <a href="#" class="brand-cover-dp" title="{{ ucwords( $brand->user->name ) }}"></a>
        <form class="form-cover-dp" method="post" action="{!! url('changeCoverPhoto') !!}" enctype="multipart/form-data">
            <input type="file" id="file1"  name="photo" style="display:none" />
        </form>
    </div>
    <div class="baner-nameNpic">
        <div class="change-dp">

            <span></span>
            <a href="javascript:void(0);" title="{{ ucwords( $brand->user->name ) }}"></a>
        </div>
    @endif
    <div class="baner-nameNpic">
        @if($brand->user->id == Auth::user()->id)
            <div class="change-dp">
                <span></span>
                <a href="javascript:void(0);" id="change_dp_btn" class="change_dp_btn" title="Hey! {{ ucwords( @$brand->user->name ) }} click here to change your profile photo."></a>
            </div>
        @endif
        <img src="{{Kinnect2::getPhotoUrl($brand->user->photo_id, $brand->user->id, 'user', 'thumb_normal')}}" alt="Profile Image" title="{{ ucwords( $brand->user->name ) }}'s profile photo." />
        <div class="name-set">{{ ucwords( $brand->user->name ) }}</div>
    </div>
    <div class="banner-links">
            <a href="javascript:void(0);" title="What's New" class="active">What's New</a>
            <a href="javascript:void(0);" title="Activity Log">Activity Log (70)</a>
            <a href="{{url('brand/info')}}/{{ $brand->user->username }}" title="Info">Info</a>
            <a href="{{url('brand/kinnectors')}}/{{ $brand->user->username }}" title="Kinnectors">Kinnectors ({{Kinnect2::brand_kinnectors($user->id)}})</a>
            <a href="javascript:void(0);" title="Album">Album (70)</a>
            <a href="javascript:void(0);" title="More">More <span></span></a>
        </div>
</div>
<div class="banner-bottom-bg"></div>
<div id="cover_photo_div" style="display:none;">
     <img src="{!! asset('local/public/assets/images/profile-cover.jpg') !!}" width="100%" height="300" alt="cover" title="cover" class="cover-photo-orignal" />
</div>
<div style="width:100%;height:300px;" id="crop_div">
    <img src="{!! asset('local/public/assets/images/profile-cover.jpg') !!}" alt="cover" title="cover" class="cover-image" />
    <div class="p_btn_div cover-p-btn" style="display:none;">
    <a href="#" class="save-btn-cover">Save</a>
    </div>
</div>
</div>

<div class="croppr-overly" style="display: none"></div>

<div id="light" class="white_content">
    <div id="edit_profile_photo_inline" class="edit_profile_photo_inline" tabindex='1' style="display:none;">
        <div class="image-editor">
            <div class="feed_viewmore" id="feed_loading" style="display: none;">
                <img src='{!! asset('local/public/images/loading.gif') !!}' />
            </div>

            <div id="select_profile_image" class="select_profile_image"><em>Browse</em> or drop photo</div>
            <input type="file" id="select_profile_file" onchange="show_edit_photo();" class="cropit-image-input" style="display:none;">
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

<script src="{!! asset('local/public/js/jquery-2.1.3.js') !!}"></script>
<script src="{!! asset('local/public//assets/js/jquery-ui.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('local/public/assets/js/imagesloaded.pkgd.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('/local/public/cropit/cropit.js') !!}"></script>
<script src="{!! asset('/local/public/assets/js/jquery.form.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('/local/public/assets/js/jquery.drag-n-crop.js') !!}" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="{!! asset('local/public/assets/css/jquery-ui.min.css')!!}">
<link rel="stylesheet" type="text/css" href="{!! asset('local/public/assets/css/jquery.drag-n-crop.css') !!}">
<script>
    var offset = null;
    var token = null;
    jQuery(document).on('click','.brand-cover-dp',function (e) {
        e.preventDefault();
         $("#file1").trigger('click');
    });
    jQuery(document).on('click','.save-btn-cover',function (e) {
        e.preventDefault();

        jQuery.ajax({
            url : '{!! url("saveCoverPhoto") !!}',
            type : 'POST',
            data : {offset : offset,token : token}
        }).done(function (data) {
            if(data)
            {
                //console.log(jQuery('.cover-image').dragncrop('destroy'));
                jQuery('#crop_div').css('display','none');
                jQuery('#cover_photo_div').css('display','');
                jQuery('.cover-photo-orignal').attr('src',data.path);
            }
        });
    })
    jQuery(document).on('change','#file1',function (e) {
        var fileName = $(this).val();
        if(fileName != '')
        { 
            jQuery('#cover_photo_div').css('display','none');
            jQuery('#crop_div').css('display','');
            jQuery('.form-cover-dp').submit();
        }
    })
    jQuery(document).ready(function (e) {
        jQuery(this).ajaxForm({
            success : function (responseText, statusText, xhr, $form) {
                
                jQuery('.cover-image').attr('src',responseText.token.path);
                token = responseText.token.token;
                jQuery('.cover-image').dragncrop({
                    centered: true,
                    start: function() {

                    },
                    drag : function (event,position) {
                        jQuery('.cover-p-btn').css('display','');
                        offset  = position.offset;
                    }
                });
            }
        });
    })
    function cancel_profile_light_box(){
        jQuery("body").css('height', "auto");
        jQuery("#edit_profile_photo_inline").hide();
        jQuery("#feed_loading").hide();
        jQuery("#light").hide();
        jQuery("#fade").hide();
        jQuery(".croppr-overly").hide();
    }//cancel_profile_light_box

    function show_edit_photo(){
        var currentWindowHeight = jQuery( window ).height();
        jQuery('body').css('height', currentWindowHeight);
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display  = 'block';
        jQuery("#edit_profile_photo_inline").show();
        jQuery(".croppr-overly").show();
    }//show_edit_photo()

    function changeMimeType()
    {
    }//changeMimeType()

    jQuery("#change_dp_btn").click(function () {
        show_edit_photo();
    });

    jQuery("#select_profile_image").click(function () {
        $(".export").show();
        jQuery("#select_profile_file").trigger('click');
    });

    jQuery(function() {
        jQuery('.image-editor').cropit({
            exportZoom: 1.25,
            imageBackground: true,
            originalSize: true,
            smallImage:"allow",
            onFileChange: changeMimeType(),
            imageBackgroundBorderWidth: 20,
            imageState: {
                src: '<?php echo Kinnect2::getPhotoUrl($brand->user->photo_id, $brand->user->id, 'user', 'thumb_normal'); ?>',
            },
        });

        jQuery('.export').click(function() {
            var imageData = jQuery('.image-editor').cropit('export');
            function dataURItoBlob2(dataURI) {
                var binary = atob(dataURI.split(',')[1]);
                var array = [];
                for(var i = 0; i < binary.length; i++) {
                    array.push(binary.charCodeAt(i));
                }
                return new Blob([new Uint8Array(array)], {type: 'image/jpeg'});
            }
            canvas = dataURItoBlob2(imageData);
            blob = canvas;

//            var url = (window.URL || window.webkitURL).createObjectURL(canvas);
            var url = (window.webkitURL).createObjectURL(canvas);
            console.log(url);
            $(".export").html('saving..');

            var filename = 'imageData';

            var data = new FormData();
            data.append('file', blob);

            $.ajax({
                url :  "{{url('user/change/profile/')}}",
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    $('#profile_dp_img').attr('src', data);
                    cancel_profile_light_box();
                },
                error: function() {
                    $(".export").html('save');
                    alert("something bad happened! Try again.");
                }
            });

            return;
        });// click .export
    });
</script>
