<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kinnect2: Login / Signup</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="{!! asset('local/public/js/jquery-2.1.3.js') !!}"></script>
    <script src="{!! asset('/local/public/cropit/cropit.js') !!}"></script>
    <link href="{!! asset('local/public/assets/css/style.css') !!}" rel="stylesheet">
    
</head>
<body>
<!--  BG Slider  -->
<div class="container">
    <div class="main">
        <ul id="cbp-bislideshow" class="cbp-bislideshow">
            <li><img src="{!! asset('local/public/images/login-page/bg-slider/slide-1.jpg') !!}" alt="image01"/></li>
            <li><img src="{!! asset('local/public/images/login-page/bg-slider/slide-2.jpg') !!}" alt="image01"/></li>
            <li><img src="{!! asset('local/public/images/login-page/bg-slider/slide-3.jpg') !!}" alt="image01"/></li>
            <li><img src="{!! asset('local/public/images/login-page/bg-slider/slide-4.jpg') !!}" alt="image01"/></li>
            <li><img src="{!! asset('local/public/images/login-page/bg-slider/slide-5.jpg') !!}" alt="image01"/></li>
            <li><img src="{!! asset('local/public/images/login-page/bg-slider/slide-6.jpg') !!}" alt="image01"/></li>
            <li><img src="{!! asset('local/public/images/login-page/bg-slider/slide-7.jpg') !!}" alt="image01"/></li>
            <li><img src="{!! asset('local/public/images/login-page/bg-slider/slide-8.jpg') !!}" alt="image01"/></li>
        </ul>
    </div>

    @yield('content')
</div>
<!--  BG Slider  -->
<!-- img slider scripts -->
<script src="{!! asset('local/public/js/login-page/modernizr.custom.js') !!}"></script>
<script src="{!! asset('local/public/js/login-page/slider-img-loader.min.js') !!}"></script>
<script src="{!! asset('local/public/js/login-page/bg-slider.min.js') !!}"></script>
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        cbpBGSlideshow.init();

        $('#next').click(function(){
            if($('#next').html() == 'Please wait..') return false;

            var email    = $("#email").val();
            var profile_address = $("#profile_address").val();
            var password = $("#password").val();
            var password_confirmation = $("#password_confirmation").val();
            var user_type = $("#user_type").val();
            $('#next').html('Please wait..');

            var dataString = "email="+email+"&username="+profile_address+"&user_type="+user_type+"&password="+password+"&password_confirmation="+password_confirmation;
            $.ajax({
                type: 'POST',
                url:  '{{url("/auth/stepOne")}}',
                data: dataString,
                success: function(response){
                    $("#steps").html(response);
                    $('#next').html('Next');
                }
            });
        });
    });
</script>


</body>
</html>
