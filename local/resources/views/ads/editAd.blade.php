@extends('layouts.masterDynamic')
@section('header-styles')
        <!--added for ads module-->
<link rel="stylesheet" href="{!! asset('local/public/assets/css/jquery-ui.min.css') !!}">
<script src="{!! asset('local/public/assets/js/jquery-ui.min.js') !!}"></script>

<link href="{{ asset('/local/public/css/jquery.multiselect.css') }}" rel="stylesheet">
<script src="{{ asset('/local/public/js/jquery.multiselect.js') }}"></script>
<!--end of added for ads module-->
@endsection
@section('content')

    <style>
        .adsError {
            color: red;
            font-size: 14px;
            font-weight: bold;
        }

        .cropit-image-preview{
            border-radius: 0 ;
            margin-left: 5px;
        }

        .cropit-image-background-container {
            height: 165px !important;
        }
    </style>
    @include('includes.ads-left-nav')
            <!--Create Album-->
    <div class="ad_main_wrapper">
        <div class="add_breadcrumb">
            <div class="create_p visited">Create Package</div>
            <div class="design_ad active">Design Your Ad</div>
            <div class="target_sch">Targeting and Scheduling</div>
        </div>
        {!! Form::model($ad , ['method' => 'PATCH', 'url' => "/ads/edit/ad/".$ad->id, "id" => "ad-detail-form", "enctype"=>"multipart/form-data"]) !!}
        <div class="cmad_acrad_wrapper form-wrapper">
            <div>
                <div id="titlebodydiv" class="cmad_ad_steps" style="margin: 0px; overflow: hidden;">
                    <div class="global_form">
                        <div class="cmad_form_left">
                            <div class="form-elements">
                                <div class="main_heading">
                                    <h1>Design Your Ad</h1>
                                    <span>Step 2/3</span>
                                </div>

                                <input type="hidden" name="module_id" value="0" id="module_id">

                                <!-- <div id="package_name-wrapper" class="form-wrapper">
                                    <div id="package_name-label" class="form-label">
                                        <label for="package_name" class="optional">
                                            Ad Package
                                        </label>
                                    </div>
                                    <div id="package_name-element" class="form-element"><p class="description">
                                            {{--<a href="javascript:void(0);"--}}
                                               {{--onclick="Smoothbox.open('/communityad/index/packge-detail/id/59/onlydetails/1')">{{$package->title}}</a>--}}

                                        </p>
                                    </div>
                                </div>-->
                                <input type="hidden" name="package_id" id="package_id" value="{{$package->id}}"/>
                                <input type="hidden" name="ad_id" id="ad_id" value="{{$ad->id}}"/>
                                <div id="campaign_id-wrapper" class="form-wrapper">
                                    <div id="campaign_id-label" class="form-label">
                                        <label for="campaign_id" class="optional">Select Campaign</label>
                                    </div>
                                    {!!  Form::select('campaign_id', $campaigns, $ad->campaign_id, ['id' => 'campaign_id', 'onchange' => 'updateTextFields(this.value);'])!!}                                    @if($errors->first('campaign_id'))
                                        <span>{{ $errors->first('campaign_id') }}</span>
                                    @endif

                                </div>
                                <div style="display: none;" id="campaign_name-wrapper" class="form-wrapper">
                                    <div id="campaign_name-label" class="form-label">
                                        <label for="campaign_name" class="optional">Campaign Name</label>
                                    </div>
                                    <div id="campaign_name-element" class="form-element"><p class="description">
                                            This is only for your indicative purpose and not visible to
                                            viewers.</p>

                                        <input type="text" name="campaign_name" id="campaign_name" value=""
                                               maxlength="100"></div>
                                </div>

                                <div id="cads_url-wrapper" class="form-wrapper">
                                    <div id="cads_url-label" class="form-label"><label for="cads_url" class="required">Your URL</label></div>
                                    <div id="cads_url-element" class="form-element"><p class="description">Example: http://www.yourwebsite.com/</p>

                                        <input type="text" name="cads_url" id="cads_url" value="{{$ad->cads_url}}">
                                </div>
                                <div id="name-wrapper" class="form-wrapper">
                                    <div id="name-label" class="form-label"><label for="name"
                                                                                   class="required">Ad Title</label>
                                    </div>
                                    <div id="name-element" class="form-element"><p class="description"><span
                                                    id="profile_address"><span
                                                        id="profile_address_text">25</span></span> characters
                                            limit.</p>

                                        <input type="text" name="name" id="name" value="{{$ad->cads_title}}" maxlength="25"></div>
                                </div>
                                <div id="cads_body-wrapper" class="form-wrapper">
                                    <div id="cads_body-label" class="form-label"><label for="cads_body"
                                                                                        class="required">Ad Body
                                            Text</label></div>
                                    <div id="cads_body-element" class="form-element"><p class="description">
                                            <span id="profile_address1"><span
                                                        id="profile_address_text1">170</span></span> characters
                                            limit.</p>

                                        <textarea name="cads_body" id="cads_body" maxlength="170" cols="45"
                                                  rows="6"
                                                  style="width: 20em; height: 6em;"
                                                  wrap="hard">{{$ad->cads_body}}</textarea>
                                    </div>
                                </div>
                                <div id="image-wrapper" class="form-wrapper">
                                    <div id="image-label" class="form-label"><label for="image"
                                                                                    class="optional">Ad
                                            Image</label></div>
                                    <div id="image-element" class="form-element">
                                        <p class="description mb10">Max file size allowed : 10 MB. File types allowed: jpg, jpeg, png, gif.<span id="loading_image"
                                                                                                                                                 style="display:none;"></span>
                                            <span id="remove_image_link" style="display:none;"><a
                                                        href="javascript:void(0);" onclick="removeImage();">Remove
                                                    uploaded image.</a></span></p>

                                        <input type="hidden" name="MAX_FILE_SIZE" value="10485760"
                                               id="MAX_FILE_SIZE">
                                        <button name="continue_target" type="button" id="image_upload_btn" class="image_upload_btn grey_btn fltL">
                                            Browse
                                        </button>

                                        <input style="display: none" type="file" name="image" id="image" onchange="readURL(this);">
                                        <!--Ad Preview Start here-->
                                        <div class="cmaddis_preview_wrapper fltR">
                                            <div class="cadcp_preview">
                                                <div class="cmaddis">
                                                    <div class="cmad_addis">
                                                        <div class="cmad_show_tooltip_wrapper">
                                                            <div class="cmaddis_title" id="ad_title"><a href="javascript:void(0);">{{$ad->cads_title}}</a></div>
                                                        </div>
                                                        <div class="cmad_show_tooltip_wrapper">
                                                            <div class="cmaddis_image cmaddis_sample" id="ad_photo">
                                                                <img id="image_preview"
                                                                     src="{{Kinnect2::getPhotoUrl($ad->photo_id, $ad->id, 'ads', 'ad_profile')}}">
                                                            </div>
                                                        </div>
                                                        <div class="cmad_show_tooltip_wrapper">
                                                            <div class="cmaddis_body cmad_show_tooltip_wrapper" id="ad_body">{{$ad->cads_body}}</div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--Ad Preview End here-->
                                    </div>

                                </div>
                                <div class="save_area">
                                    <div id="continue_target-wrapper" class="fltR">
                                        <div id="continue_target-element" class="form-element">
                                            <input type="hidden" value=""  name="saved_ad_image_file_id" id="saved_ad_image_file_id" />
                                            <button name="continue_target" id="continue_target" type="button" class="orngBtn">
                                                Next
                                            </button>
                                        </div>
                                    </div>
                                    {{--<button name="back" type="button" id="" class="light_grey_btn fltL">Back</button>--}}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
            </form>
            <!--start of Croppic image tool -->

            <div id="wrapper-user-profile-photo">
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
                            <button class="export" >Save</button>
                            <button class="cancel_profile_light_box" >Cancel</button>
                        </div>
                    </div>
                </div><!-- lightbox -->
                <div id="fade" class="black_overlay"></div>
                <!-- end of croppic tool-->
            </div>

        </div>
        </div>
        @endsection

        @section('footer-scripts')
            <script type="text/javascript">
                var image_file_id = '';

                function check_dates(e) {
                    var now_date = '<?php echo $now_date; ?>';
                    var start_date = $('#start_date').val();
                    var end_date = $('#end_date').val();

                    if (end_date < start_date || start_date < now_date) {
                        $('#date_errors').show();
                        $('#date_errors').css('color', 'red');
                        $('#date_errors').css('margin', '0 0 17px 0px');

                        $('#start_date').css('border', '1px solid red');
                        $('#end_date').css('border', '1px solid red');
                        $('html, body').animate({scrollTop: $('#date_errors').position().top}, 'slow');

                        return false;
                    }

                    $("#ad-detail-form").submit();
                }

                //        function validCadsUrl(s) {
                //            return /((http|https?):\/\/)?()?[a-z0-9\.\-]$/.test(s);
                //        }
                function validCadsUrl(s){
                    var message;
                    var myRegExp =/^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
                    var urlToValidate = s;
                    if (!myRegExp.test(urlToValidate)){
                        return false;
                    }else{
                        return true;
                    }
                    alert(message);
                }

                function adStepOneValidation(value) {

                    $('.adsError').remove();
                    var campaign_name = $('#campaign_name').val();
                    var cads_url = $('#cads_url').val();
                    var name = $('#name').val();
                    var cads_body = $('#cads_body').val();
                    var image = $('#image').val();
                    var campaign_id = $('#campaign_id').val();

                    var is_false = false;
                    if (campaign_name.length < 1 && $('#campaign_id').val() == 0) {
                        $('#campaign_name').after('<p class="adsError">please fill campaign name to continue.</p>');
                        is_false = true;
                    }

                    if (validCadsUrl(cads_url) == false) {
                        $('#cads_url').after('<p class="adsError">please enter proper url to continue.</p>');
                        is_false = true;
                    }

                    if (name.length < 1) {
                        $('#name').after('<p class="adsError">please enter title to continue.</p>');
                        is_false = true;
                    }

                    if (cads_body.length < 3) {
                        $('#cads_body').after('<p class="adsError">please enter body text to continue.</p>');
                        is_false = true;
                    }

                    if (is_false == true) {
                        return false;
                    } else {
                        return true;
                    }

                }

                // Remaining characters
                var nameHtml = $('#name').val();
                var remainingCharacters = 25 - nameHtml.length;
                $('#profile_address_text').html(remainingCharacters);

                var bodyHtml = $('#cads_body').val();
                var remainingCharacters = 170 - bodyHtml.length;
                $('#profile_address_text1').html(remainingCharacters);

                $('#name').keyup(function (e) {
                    var nameHtml = $('#name').val();
                    var remainingCharacters = 25 - nameHtml.length;
                    $('#profile_address_text').html(remainingCharacters);
                    $("#ad_title").html('<a href="">' + nameHtml + '</a>');
                });

                $('#cads_body').keyup(function (e) {
                    var bodyHtml = $('#cads_body').val();
                    var remainingCharacters = 170 - bodyHtml.length;

                    $('#profile_address_text1').html(remainingCharacters);

                    $("#ad_body").html(bodyHtml);
                });

                $('#continue_target').click(function (e) {
                    var is_valid_data = adStepOneValidation(1);
                    if (is_valid_data) {
                        $("#ad-detail-form").submit();
                    }
                    else {
                        alert('please fill all necessary fields for step 2.');
                    }
                });

                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            $('#image_preview')
                                    .attr('src', e.target.result)
                                    .width(170)
                                    .height(170);
                        };

                        reader.readAsDataURL(input.files[0]);
                    }
                }

                function updateTextFields(campaignId) {
                    if (campaignId == 0) {
                        $("#campaign_name-wrapper").show();
                    }
                    else {
                        $("#campaign_name-wrapper").hide();
                    }
                }
                $('select[multiple]').multiselect({
                    columns: 1,
                    search: true,
                    placeholder: 'Select options'
                });


            </script>
            <!-- Croppit tool script -->
            <script src="{!! asset('/local/public/cropit/cropit.js') !!}"></script>

            <script>

                $(".cancel_profile_light_box").click(function(e){
                    e.preventDefault();

                    jQuery("body").css('height', "auto");
                    jQuery("#edit_profile_photo_inline").hide();
                    jQuery("#feed_loading").hide();
                    jQuery("#light").hide();
                    jQuery("#fade").hide();
                    jQuery(".croppr-overly").hide();

                    return false;
                });

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

                jQuery("#image_upload_btn").click(function () {
                    $(".export").html('save');
                    $(".export").show();
                    jQuery("#select_profile_file").trigger('click');
                });

                jQuery("#select_profile_image").click(function () {
                    $(".export").show();
                    jQuery("#select_profile_file").trigger('click');
                });

                jQuery(function() {
                    jQuery('.image-editor').cropit({
                        width: 170,
                        height: 140,
                        exportZoom: 1.25,
                        imageBackground: true,
                        originalSize: true,
                        smallImage:"allow",
                        onFileChange: changeMimeType(),
                        imageBackgroundBorderWidth: 20,
                        imageState: {
                            src: '<?php echo asset("/local/public/assets/images/blankImage.png") ?>',
                        },
                    });

                    jQuery('.export').click(function(e) {
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

//                var url = (window.URL || window.webkitURL).createObjectURL(canvas);
//                console.log(url);
                        $(".export").html('saving..');

                        var filename = 'imageData';

                        var data = new FormData();
                        data.append('ad_image', blob);

                        $.ajax({
                            url :  "{{url('ads/ad_profile_temp_image')}}",
                            type: 'POST',
                            data: data,
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                console.log(data);

                                var imageInfo = data.split('+_+');
                                var fullPathOfImage = "{{asset('local/storage/app/photos/')}}";

                                $('#image_preview').attr('src', fullPathOfImage+"/"+imageInfo[1]);
                                image_file_id = imageInfo[0];
                                $("#saved_ad_image_file_id").val(image_file_id);
                                jQuery(".cancel_profile_light_box").trigger('click');
                                $(".export").hide();

                            },
                            error: function() {
                                $(".export").html('save');
                                alert("something bad happened! Try again.");
                            }
                        });

                        return false;
                    });// click .export
                });
            </script>
            <!-- end of Croppit tool script -->
@endsection
