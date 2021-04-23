@extends('layouts.default')
@section('content')
<style type="text/css">
    .cropit-image-preview{
        border-radius: 0 ;
        margin-left: 5px;
    }
    p.error{color:#ff7158;}
    .cropit-image-background-container {
        height: 165px !important;
    }
</style>
        <!--Create Album-->
<div class="content-gray-title mb10">
    <h4>Create New Group</h4>
    <a href="{{url('groups/manage')}}" title="Browse" class="btn fltR">Manage Groups</a>
</div>
<div class="form-container">
        {!! Form::model($group = new\App\Group,['url' => 'group/create', "enctype"=>"multipart/form-data",'id' => 'createGroup']) !!}
        <div class="field-item">
            {!! Form::label('title' ,'Group Title') !!}
            {!! Form::text('title',null, ['class' => 'form-control','placeholder'=>'Group Title']) !!}
            @if ($errors->has('title'))<p style="color:#ff7158;">{!!$errors->first('title')!!}</p>@endif
        </div>

        <div class="field-item">
            {!! Form::label('description' ,'Description') !!}
            {!! Form::textarea('description',null, ['class' => 'form-control','placeholder'=>'Write detail here...']) !!}
            @if ($errors->has('description'))<p style="color:#ff7158;">{!!$errors->first('description')!!}</p>@endif
        </div>
        <div class="field-item">
            <img id="group_profile_preview" width="150"  src="{!! asset('/local/storage/app/photos/0/default_group_profile_photo.svg')!!}" title="Pofile photo of this group." alt="image">
        </div>
        <div class="upload-photo mt20">
            <input type="file" style="display: none;" onchange="readURL(this);" name="group_profile_photo" id="group_profile_photo" />
            <a href="javascript:void(0);" title="Select group profile picture."  id="group_profile_photo-btn" class="btn">Upload Photo</a>
            <span id="new_photo_name">No Photos Selected Yet</span>
        </div>

        <div class="field-item">
            <label for="">Category</label>
            {!!  Form::select('category',
                     $categories, session('category'), ['id' => 'category'])!!}
            @if($errors->first('category'))
                <span>{{ $errors->first('category') }}</span>
            @endif
        </div>

        <div class="mt20">
            <div>
                <input type="radio" name="search" id="search-1" value="1" checked>
                <label for="search-1">Yes, include in search results.</label>
            </div>
            <div class="mt5">
                <input type="radio" name="search" id="search-0" value="0">
                <label for="search-0">No, hide from search results.</label>
            </div>
        </div>

        <div class="mt20">
            <div>
                <input type="radio" name="members_can_invite" id="members_can_invite-1" value="1" checked>
                <label for="members_can_invite-1">Yes, members can invite other people.</label>
            </div>
            <div class="mt5">
                <input type="radio" name="members_can_invite" id="members_can_invite-0" value="0">
                <label for="members_can_invite-0">No, only officers can invite other people.</label>
            </div>
        </div>

        <p>When people try to join this group, should they be allowed to join immediately, or should they be forced to wait for approval?</p>

        <div class="mt20">
            <div>
                <input type="radio" name="approval_required" id="new_members_1" value="0" checked>
                <label for="new_members_1">New members can join immediately.</label>
            </div>
            <div class="mt5">
                <input type="radio" name="approval_required" id="new_members_0" value="1">
                <label for="new_members_0">New member must be approved</label>
            </div>
        </div>

        <div class="field-item">
            <label for="">View Privacy</label>
            <select name="view_privacy" id="view_privacy">
                {{--<option value="PERM_EVERYONE">Who may see this group?</option>--}}
                <option value="PERM_EVERYONE">Registered Members</option>
                <option value="PERM_GROUP_MEMBERS">All group members</option>
                <option value="PERM_GROUP_OFFICERS_AND_OWNERS">Officers and Owner Only</option>
            </select>
        </div>

        <div class="field-item">
            <label for="">Comment Privacy</label>
            <select name="comment_privacy" id="comment_privacy">
                {{--<option value="PERM_EVERYONE">Who may post comments on this Group?</option>--}}
                <option value="PERM_EVERYONE">Registered Members</option>
                <option value="PERM_GROUP_MEMBERS">All group members</option>
                <option value="PERM_GROUP_OFFICERS_AND_OWNERS">Officers and Owner Only</option>
            </select>
        </div>

        <div class="field-item">
            <label for="">Post Privacy</label>
            <select name="post_privacy" id="post_privacy">
                {{--<option value="PERM_EVERYONE">Who may upload photo to this Group?</option>--}}
                <option value="PERM_EVERYONE">Registered Members</option>
                <option value="PERM_GROUP_MEMBERS">All group members</option>
                <option value="PERM_GROUP_OFFICERS_AND_OWNERS">Officers and Owner Only</option>
            </select>
        </div>

        <div class="field-item">
            <label for="">Event Creation</label>
            <select name="privacy_event_creation" id="privacy_event_creation">
                {{--<option value="PERM_EVERYONE">Who may create events for this Group?</option>--}}
                {{--<option value="PERM_EVERYONE">Everyone</option>--}}
                <option value="PERM_EVERYONE">Registered Members</option>
                <option value="PERM_GROUP_MEMBERS">All group members</option>
                <option value="PERM_GROUP_OFFICERS_AND_OWNERS">Officers and Owner Only</option>
            </select>
        </div>
    <input type="hidden" value="" name="saved_group_image_file_id" id="saved_group_image_file_id">

    <div class="form-group save-changes">
            {!! Form::submit('Create Group', ['class' => 'btn btn-primary form-control btn']) !!}
            <a href="{{URL::previous() }}" class="btn btn-grey ml10" id="Cancel-btn">Cancel</a>
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
    <script type="text/javascript" src="{!! asset('local/public/assets/js/jquery.validate.min.js') !!}"></script>
    <script src="{!! asset('/local/public/cropit/cropit.js') !!}"></script>
    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#group_profile_preview')
                            .attr('src', e.target.result)
                            .width(170)
                            .height(170);
                };

                reader.readAsDataURL(input.files[0]);
                $('#new_photo_name').html($(input).val().split(/\\|\//).pop());
            }
        }

        $(document).ready(function(){
            $("#group_profile_photo-btn").click(function(e){
                e.preventDefault();
//                $('#group_profile_photo').click();
            });

            jQuery('#createGroup').validate({
                errorElement : 'p',
                errorPlacement : function (error,elem) {
                    if(elem.next().is('p')){
                        elem.next().remove();
                    }
                    error.insertAfter(elem);
                },
                rules : {
                    'title' : {required:true},
                    'description' : {required:true}
                },
                messages : {
                    'title' : {
                        required:"{{trans('validation.required',['attribute' => 'title'])}}"
                    },
                    'description' : {
                        required:"{{trans('validation.required',['attribute' => 'description'])}}"
                    }
                }
            });
        });

    <!-- Croppit tool script -->
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

    jQuery("#group_profile_photo-btn").click(function () {
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
            width: 140,
            height: 140,
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
            data.append('group_image', blob);
//                data.append('group_original_image', orignalImgData);

            $.ajax({
                url :  "{{url('groups/create_group_temp_image')}}",
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function(data) {


                    var imageInfo = data.split('+_+');
                    var fullPathOfImage = "{{asset('local/storage/app/photos/')}}";

                    $('#group_profile_preview').attr('src', fullPathOfImage+"/"+imageInfo[1]);
                    image_file_id = imageInfo[0];
                    $("#saved_group_image_file_id").val(image_file_id);
                    jQuery(".cancel_profile_light_box").trigger('click');
                    $(".export").hide();
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