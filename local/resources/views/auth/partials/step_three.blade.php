<!--  Page - 3  -->
<div class="signup-upload-img">
    <div class="signup-label">Add Your Photo</div>
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
                    <button class="export" onclick="">Save</button>
                    <button class="cancel_profile_light_box" onclick="cancel_profile_light_box();">Cancel</button>
                </div>
            </div>
    </div><!-- lightbox -->
        <div id="fade" class="black_overlay"></div>

        <div id='user_profile_photo'>
            <div class="user_profille_dp">
                @if(session('user_type') == 1)
                    @if(session('gender') == 1)
                        <img src="{!! asset('local/storage/app/photos/0/default_male_user_profile_photo.svg') !!}" alt="{{session('last_name').' '.session('last_name')}} select your photo." title="{{session('first_name').' '.session('last_name')}} select your photo." width="170" height="170" class="thumb_profile item_photo_user  thumb_profile" />
                    @endif

                    @if(session('gender') == 2)
                            <img src="{!! asset('local/storage/app/photos/0/default_female_user_profile_photo.svg') !!}" alt="{{session('last_name').' '.session('last_name')}} select your photo." title="{{session('first_name').' '.session('last_name')}} select your photo." width="170" height="170" class="thumb_profile item_photo_user  thumb_profile" />
                    @endif
                @endif

                @if(session('user_type') == 2)
                        <img src="{!! asset('local/storage/app/photos/0/default_brand_profile_photo.svg') !!}" alt="{{session('last_name').' '.session('last_name')}} select your photo." title="{{session('first_name').' '.session('last_name')}} select your photo." width="170" height="170" class="thumb_profile item_photo_user  thumb_profile" />
                @endif
            </div><!-- user_profille_dp -->
        </div><!-- user_profile_photo -->
    </div>
    <!-- wrapper-user-profile-photo --></div>
    <div class="chose-file">
    <?php echo Form::open(array('url' => 'foo/bar', 'files' => true)) ?>
    <?php echo Form::file('image',array('id'=>'profile_photo', 'style' => 'display:none'))  ?>
        <a class="btn btn-inactive fltL" id="select_profile_photo_1" href="javascript:void(0);">Chose File</a>
        <span id="msgFileChosen" style="padding-top:11px;">No file chosen</span>
    <?php Form::close() ?>
    </div>
    <div class="signup-pager cf">
        <div class="circle-pager"></div>
        <div class="circle-pager"></div>
        <div class="circle-pager circle-pager-active"></div>
    </div>
    <div>
        <a class="btn fltL" href="javascript:void(0);" id="save">Finish</a>
        <a class="btn btn-inactive fltL" href="javascript:void(0);" id="skip">Skip </a>  <a class="btn btn-inactive fltL" id="back" href="javascript:void(0);">Back</a>
    </div>
</div>
<script>
     function upload_profile_photo(elemId) {
        var elem = document.getElementById(elemId);
        if(elem && document.createEvent) {
            var evt = document.createEvent("MouseEvents");
            evt.initEvent("click", true, false);
            elem.dispatchEvent(evt);
        }
     }

    function saveUser(saveOrSkip)
    {
        if(saveOrSkip == 'save'){
            if($('#save').html() == 'Please wait..') return false;
            $('#save').html('Please wait..');
        }

        if(saveOrSkip == 'skip'){
            if($('#skip').html() == 'Please wait..') return false;
            $('#skip').html('Please wait..');
        }

        $(".export").html('Please wait..');

        var email = '{!! session('email') !!}';
        var name = '{!! session('username') !!}';
        var password = '{!! session('password') !!}';
        var password_confirmation = '{!! session('password_confirmation') !!}';

        var dataString = "name=" + name + "&email=" + email + "&password=" + password + "&password_confirmation=" + password_confirmation;
        $.ajax({
            type: 'POST',
            url: '{{ url('/auth/register') }}',
            data: dataString,
            success: function (data) {
                    $('.signup-form').html('<div class="line_height_normal">Congratulations, your account has been registered. Please check your email ('+email+') to activate it.</div>');
            }
        });
    }//saveUser()

    function backToStepTwo()
    {
        var dataString = 'back=1';
        if($('#back').html() == 'Please wait..') return false;
        $('#back').html('Please wait..');

        $.ajax({
            type: 'POST',
            url: '{{url("auth/stepTwo")}}',
            data: dataString,
            success: function (response) {
                $("#steps").html(response);
            }
        });
    }//backToStepTwo()

    function backToStepTwoBrand()
    {
        if($('#back').html() == 'Please wait..') return false;
        $('#back').html('Please wait..');

        var dataString = 'back=1';
        $.ajax({
            type: 'POST',
            url: '{{url("auth/stepTwoBrand")}}',
            data: dataString,
            success: function (response) {
                $("#steps").html(response);
            }
        });
    }//backToStepTwoBrand()

    $('#save').click(function () {
        saveUser('save');
    });

    $('#skip').click(function () {
        saveUser('skip');
    });

    $('#back').click(function () {
        var user_type = '{{session('user_type')}}';

        if(user_type == 2)
        {
            backToStepTwoBrand();
        }

        if(user_type == 1)
        {
            backToStepTwo();
        }
    });
</script>

<script src="{!! asset('/local/public/cropit/cropit.js') !!}"></script>
<script type="text/javascript">
    $('.signup-header h2').html("Step 3");

    function cancel_profile_light_box(){
//        jQuery("body").css('height', "auto");
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

    jQuery("#select_profile_photo_1").click(function () {
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
            exportZoom: 1.25,
            imageBackground: true,
            originalSize: true,
            smallImage:"allow",
            onFileChange: changeMimeType(),
            imageBackgroundBorderWidth: 20,
            imageState: {
                src: '<?php echo asset("/local/public/images/login-page/upload-img.png") ?>',
            },
        });

        jQuery('.export').click(function() {
            var imageData = jQuery('.image-editor').cropit('export');
            /*alert("Testing more items");
            console.log(imageData);
            alert("abc");
            console.log()*/
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

            var url = (window.URL || window.webkitURL).createObjectURL(canvas);
            console.log(url);
            $(".export").html('saving..');
            $("#msgFileChosen").html('');

            var filename = 'imageData';
            var email = '{!! session('email') !!}';
            var name = '{!! session('username') !!}';
            var password = '{!! session('password') !!}';
            var password_confirmation = '{!! session('password_confirmation') !!}';

            var data = new FormData();
            data.append('file', blob);
            data.append('email', email);
            data.append('name', name);
            data.append('password', password);
            data.append('password_confirmation', password_confirmation);

            $.ajax({
                url :  "{{url("auth/register")}}",
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    $('.thumb_profile').attr('src', data);
                    cancel_profile_light_box();
                    $(".export").hide();
                    //$('.signup-header').html(data);
                    //$('.signup-form').html('');

                },
                error: function() {
                    $(".export").html('save');
                    alert("something bad happened! Try again.");
                }
            });

            return;
/////////////////////////////////////////
/*console.log(imageData);
            window.open(imageData);
            jQuery("#feed_loading").show();
            $(".export").html('Please wait..');

            var email = '{!! session('email') !!}';
            var name = '{!! session('username') !!}';
            var password = '{!! session('password') !!}';
            var password_confirmation = '{!! session('password_confirmation') !!}';

            var dataString = "imageData=" + imageData + "&name=" + name + "&email=" + email + "&password=" + password + "&password_confirmation=" + password_confirmation;
            $.ajax({
                type: 'POST',
                data: dataString,
                url: '{{ url('/auth/register') }}',
                success: function (response) {
                    alert(response);
                }
            });*/
        });// click .export
    });
</script>