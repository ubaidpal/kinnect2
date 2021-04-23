@extends('layouts.default')

@section('content')
    <style>
        .cropit-image-preview{
            border-radius: 0 ;
            margin-left: 5px;
        }
        div#edit_profile_photo_inline{
            width: 262px;
        }
        .cropit-image-background-container{
            width: 282px !important;
            height: 275px !important;
        }
        button.cancel_profile_light_box{
            margin-right: 30px;
        }
    </style>
   {{-- <link rel="stylesheet" type="text/css" href="{!! asset('local/public/date_picker/css/style.css') !!}">

    <script type="text/javascript" src="{!! asset('local/public/date_picker/js/date.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('local/public/date_picker/js/datepicker.js') !!}"></script>
    <script type="text/javascript" charset="utf-8">
        $(function()
        {
            $('.date-pick').datePicker({clickInput:true})
        });
    </script>--}}

   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

   <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
   <script>

       function check_dates_2(e){

           var now_date = $('#start_date').val();
           var start_date = $('#start_date').val();
           var end_date = $('#end_date').val();

           var isFalse = true;

           if(end_date < start_date || start_date < now_date)
           {
               $('#date_errors').show();
               $('#date_errors').css('color', 'red');
               $('#date_errors').css('margin', '10px 0 17px 0px');
               $('#date_errors').css('font-size', '15px');

               $('#start_date').css('border', '1px solid red');
               $('#end_date').css('border', '1px solid red');

               isFalse = false;
           }

           if(isFalse == false){
               return false;
           }else{
               $('#start_date').css('border', '1px solid #505050');
               $('#end_date').css('border', '1px solid #505050');

               $('#date_errors').hide();
           }
       }

       $(function() {
           $( "#start_date" ).datepicker({
               dateFormat: "yy-mm-dd",
               showOn: 'both',
               buttonImage: '{{asset('local/public/assets/images/img-Start-Time.png')}}',
               minDate: 0,
               onClose: function( selectedDate ) {
                   $( "#end_date" ).datepicker( "option", "minDate", selectedDate );
               }
           });

           $( "#start_date_icon" ).click(function(evt){
               evt.preventDefault();
               $( "#start_date" ).click();
           });

           $( "#end_date" ).datepicker({
               dateFormat: "yy-mm-dd",
               showOn: 'both',
               buttonImage: '{{asset('local/public/assets/images/img-Start-Time.png')}}',
               minDate: 0,
               onClose: function( selectedDate ) {
                   $( "#start_date" ).datepicker( "option", "maxDate", selectedDate );
                   check_dates_2();
               }
           });

           $( "#end_date_icon" ).click(function(evt){
               evt.preventDefault();
               $( "#end_date" ).click();
           });
       });
   </script>
    <!-- Create Event-->
<div class="content-gray-title mb10">
    <h4>New Event</h4>
</div>
<div class="form-container">
    <?php $actionUrl = 'event/create/parent_type/'.$parent_type.'/parent_id/'.$parent_id; ?>
    {!! Form::model($event = new\App\Event,['url' => $actionUrl, "id"=>"event_form_details", "enctype"=>"multipart/form-data"]) !!}

        <div class="field-item">
        {!! Form::label('title' ,'Event Title') !!}
        {!! Form::text('title',null, ['class' => 'form-control','placeholder'=>'Event Title']) !!}
        @if ($errors->has('title'))<p style="color:#ff7158;">{!!$errors->first('title')!!}</p>@endif
    </div>

    <div class="field-item">
        {!! Form::label('description' ,'Description') !!}
        {!! Form::textarea('description',null, ['class' => 'form-control','placeholder'=>'Write detail here...']) !!}
        @if ($errors->has('description'))<p style="color:#ff7158;">{!!$errors->first('description')!!}</p>@endif
    </div>

        <div class="field-item calendar">
            <h3 id="date_errors" style="display: none;">End date must be greater than Start Date, and Start Date must be greater from '{{$now_date}}'</h3>

        @if($errors->isDateValid->any())
                <?php echo $errors->isDateValid->first('isDateValid') ?>
            @endif
            <label for="">Start Time</label>
            <div class="select-date">
                <span>Select a Date</span>
                <input type="text" class="fltL" name="start_date" value="{{$now_date}}" title="Select date to start." id="start_date">
            </div>

            <input type="hidden" value="" name="saved_event_image_file_id" id="saved_event_image_file_id">

            <div class="select-time">
                <span>Select Time</span>
                {!!  Form::select('start_time_hour',
                     $hours, session('start_time_hour'), ['id' => 'start_time_hour'])!!}
                @if($errors->first('start_time_hour'))
                    <span>{{ $errors->first('start_time_hour') }}</span>
                @endif

                {!!  Form::select('start_time_minutes',
                     $minutes, session('start_time_minutes'), ['id' => 'start_time_minutes'])!!}
                @if($errors->first('start_time_minutes'))
                    <span>{{ $errors->first('start_time_minutes') }}</span>
                @endif

                <select name="start_time_am_pm" id="start_time_am_pm">
                    <option value="am">AM</option>
                    <option value="pm">PM</option>
                </select>
            </div>
        </div>

        <div class="field-item calendar">
            <label for="">End Time</label>
            <div class="select-date">
                <span>Select a Date</span>
                <input type="text" name="end_date" class="fltL" title="Select date to end." id="end_date">

            </div>
            <div class="select-time">
                <span>Select Time</span>
                {!!  Form::select('end_time_hour',
                     $hours, session('end_time_hour'), ['id' => 'end_time_hour'])!!}
                @if($errors->first('end_time_hour'))
                    <span>{{ $errors->first('end_time_hour') }}</span>
                @endif

                {!!  Form::select('end_time_minutes',
                     $minutes, session('end_time_minutes'), ['id' => 'end_time_minutes'])!!}
                @if($errors->first('end_time_minutes'))
                    <span>{{ $errors->first('end_time_minutes') }}</span>
                @endif

                <select name="end_time_am_pm" id="end_time_am_pm">
                    <option value="am">AM</option>
                    <option value="pm">PM</option>
                </select>
            </div>
        </div>

        <div class="field-item">
            <label for="">Host</label>
            <input type="text" id="host" name="host" placeholder="Host name" title="Enter event host name.">
        </div>

        <div class="field-item">
            <label for="">Location</label>
            <input type="text" name="location" id="location" placeholder="Location" title="Enter location where event is going to be held." >
        </div>

        <div class="field-item">
            <label for="">Main Photo</label>
            <img id="event_profile_preview" width="250" src="{!! asset('/local/public/events/default.jpg')!!}" title="Pofile photo of this event." alt="image">
        </div>
        <div class="upload-photo mt20">
            <input type="file" style="display: none;" onchange="readURL(this);" name="event_profile_photo" id="event_profile_photo" />
            <a href="javascript:void(0);" title="Select event profile picture."  id="event_profile_photo-btn" class="btn">Upload Photo</a>
            <span id="event_profile_photo_span">No Photos Selected Yet</span>
        </div>

        <div class="field-item">
            <label for="">Category</label>
            {!!  Form::select('category',
                     $categories, session('category'), ['id' => 'category'])!!}
            @if($errors->first('category'))
                <span>{{ $errors->first('category') }}</span>
            @endif
        </div>

        <div class="field-item-checkbox mt10">
            <input type="checkbox" id="approval_required" name="approval_required" value="1" />
            <label for="approval_required">People must be invited to RSVP for this event.</label>
        </div>

        <div class="field-item-checkbox mt10">
            <input type="checkbox" id="member_can_invite" name="member_can_invite" value="1"/>
            <label for="member_can_invite">Invited guests can invite other people as well</label>
        </div>

    <div class="field-item">
        <label for="view_privacy">View Privacy</label>
        <select name="view_privacy" id="view_privacy">
            {{--<option value="{!! Config::get('constants.PERM_EVERYONE') !!}">Who may see this event?</option>--}}
            <option value="PERM_EVERYONE">Registered Members</option>
            <option value="PERM_EVENT_MEMBERS">All event members</option>
            <option value="PERM_PRIVATE">Owner Only</option>
        </select>
        </select>
    </div>

    <div class="field-item">
        <label for="comment_privacy">Comment Privacy</label>
        <select name="comment_privacy" id="comment_privacy">
            {{--<option value="{!! Config::get('constants.PERM_EVERYONE') !!}">Who may post comments on this Event?</option>--}}
            <option value="PERM_EVERYONE">Registered Members</option>
            <option value="PERM_EVENT_MEMBERS">All event members</option>
            <option value="PERM_PRIVATE">Owner Only</option>
        </select>
        </select>
    </div>

    <div class="field-item">
        <label for="privacy_photo_upload">Photo Uploads</label>
        <select name="privacy_photo_upload" id="privacy_photo_upload">
            {{--<option value="{!! Config::get('constants.PERM_EVERYONE') !!}">Who may upload photo to this Event?</option>--}}
            <option value="PERM_EVERYONE">Registered Members</option>
            <option value="PERM_EVENT_MEMBERS">All event members</option>
            <option value="PERM_PRIVATE">Owner Only</option>
        </select>
        </select>
    </div>

    <div class="form-group save-changes">
        {!! Form::button('Create Event', ['class' => 'btn btn-primary form-control btn', 'onclick' => "check_dates(this);", 'id' => "event_create_btn"]) !!}
        {!! HTML::link(URL::previous(),'Cancel', ['class' => 'btn btn-grey ml10' , 'id' => 'Cancel-btn']) !!}
    </div>
    {!! Form::close() !!}

        {{--start of Croppic image tool--}}

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

@endsection
@section('footer-scripts')

    <script>
       function check_dates(e){

            var image_file = $('#saved_event_image_file_id').val();
            var now_date = $('#start_date').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            var isFalse = true;

            if(image_file == ''){
                $("#event_profile_preview").css('border', '1px solid red');
                $("#event_profile_photo_span").html('Please select image.');
                $("#event_profile_photo_span").css('color', 'red');

                isFalse = false;
            }

            if(end_date < start_date || start_date < now_date)
            {
                $('#date_errors').show();
                $('#date_errors').css('color', 'red');
                $('#date_errors').css('margin', '10px 0 17px 0px');
                $('#date_errors').css('font-size', '15px');

                $('#start_date').css('border', '1px solid red');
                $('#end_date').css('border', '1px solid red');
                $('html, body').animate({scrollTop:$('#date_errors').position().top}, 'slow');
                setInterval(check_dates_2, 1000);
                isFalse = false;
            }

           if(isFalse == false){
               return false;
           }else{
               $("#event_form_details").submit();
           }
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#event_profile_preview')
                            .attr('src', e.target.result)
                            .width(240)
                            .height(240);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).ready(function(){
            $("#event_profile_photo-btn").click(function(e){
                e.preventDefault();
//                $('#event_profile_photo').click();
            });
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

        jQuery("#event_profile_photo-btn").click(function () {
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
                width: 250,
                height: 250,
                exportZoom: 1.25,
                imageBackground: true,
                originalSize: true,
                smallImage:"allow",
                onFileChange: changeMimeType(),
                imageBackgroundBorderWidth: 20,
                imageState: {
                    src: '<?php echo asset("/local/storage/app/photos/0/default_group_profile_photo.svg") ?>',
                },
            });

            jQuery('.export').click(function(e) {

                function dataURItoBlob2(dataURI) {
                    var binary = atob(dataURI.split(',')[1]);
                    var array = [];
                    for(var i = 0; i < binary.length; i++) {
                        array.push(binary.charCodeAt(i));
                    }
                    return new Blob([new Uint8Array(array)], {type: 'image/jpeg'});
                }

                var imageData = jQuery('.image-editor').cropit('export');
//                var orignalImgData = $(".cropit-image-loaded").css('background-image');

//                orignalImgData = orignalImgData.split('"');

                canvas          = dataURItoBlob2(imageData);
//                orignalImgData  = dataURItoBlob2(orignalImgData[1]);

                blob = canvas;

                $(".export").html('saving..');

                var filename = 'imageData';

                var data = new FormData();
                data.append('event_image', blob);
//                data.append('event_original_image', orignalImgData);

                $.ajax({
                    url :  "{{url('events/create_event_temp_image')}}",
                    type: 'POST',
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);

                        var imageInfo = data.split('+_+');
                        var fullPathOfImage = "{{asset('local/storage/app/photos/')}}";

                        $('#event_profile_preview').attr('src', fullPathOfImage+"/"+imageInfo[1]);
                        image_file_id = imageInfo[0];
                        $("#saved_event_image_file_id").val(image_file_id);
                        jQuery(".cancel_profile_light_box").trigger('click');
                        $(".export").hide();

                        $("#event_profile_preview").css('border', '');
                        $("#event_profile_photo_span").html('Image uploaded.');
                        $("#event_profile_photo_span").css('color', '#505050');
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
    {{--end of Croppit tool script--}}
@endsection
