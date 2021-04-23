@extends('layouts.signup')
@section('content')
    <title>Register - Kinnect2</title>
    <div id="viewPort" class="mob-container signin">
        <!-- Header Container -->
        <div class="header-container">
            <div class="logo-block">
                <h1>
                    <a href="javascript:void(0)">
                        <img class="k2-logo" src="{!! asset('local/public/assets/images_mobile/k2-logo.png') !!}"
                             alt="logo_k2">
                        <u>Kinnect2 logo</u>
                    </a>
                </h1>
            </div>
        </div>

        <!-- SignUp Text -->
        <div class="signin-txt">
            <h2>Sign Up</h2>
        </div>

        <!-- Form Container -->
        <div class="form-container" id="steps">

            {!! Form::open(['url'=> '/auth/login']) !!}
            {!! Form::hidden('timezone',NULL,['id' => 'timezone']) !!}
            <div class="form-block">
                <input class="form-item" type="email" name="signup_email" value="{{session('email')}}"
                       placeholder="Email *" id="signup_email">

                <div class="error" id="email-error"></div>
            </div>
            <div class="form-block">
                <input class="form-item" type="password" name="password" value="" placeholder="Password"
                       id="password">

                <div class="error" id="password-error"></div>
                <div class="pass-req">
                    <span>
                        Password should be a minimum of 7 characters, and must consist of at least
                    </span>
                    <span>- 1 Special Character</span>
                    <span>- 1 Alphanumeric</span>
                </div>
            </div>
            <div class="form-block">
                <input class="form-item" type="password" name="password_confirmation" value=""
                       placeholder="Password (again)" id="password_confirmation">

                <div class="error" id="password-con-error"></div>
            </div>

            <div class="form-block">
                <label for="">Profile Type</label>
                {!!  Form::select('user_type',
        [   ''=>'Profile Type',
            '1'=>'Regular User',
            '2'=>'Brand'], session('user_type'), ['id' => 'user_type'])!!}
                <div class="error" id="userType-error"></div>
            </div>
            {!! Form::close() !!}


            <div class="btn-signup-container mb20 mt5">
                <a class="btn fL" href="javascript:void(0)" id="next">Next</a>

                {!! HTML::link('/', 'Cancel',['class'=> 'btn btn-grey fR']) !!}
            </div>

        </div>
    </div>
@endsection
@section('footer-scripts')
    {!! HTML::script('local/public/assets/js/timeZone.js') !!}
    <script>
        $('#next').click(function(){
           // if($('#next').html() == 'Please wait..') return false;

            var email                 = $("#signup_email").val();
            var profile_address       = '';//$("#profile_address").val();
            var password              = $("#password").val();
            var password_confirmation = $("#password_confirmation").val();
            var user_type             = $("#user_type").val();
            var timezone              = $("#timezone").val();

            $('#next').html('Please wait..');

            var dataString = "email=" + email + "&username=" + profile_address + "&user_type=" + user_type + "&password=" + password + "&password_confirmation=" + password_confirmation + '&timezone=' + timezone;
            $.ajax({
                type : 'POST', url : '{{url("auth/stepOne")}}', data : dataString, success : function(response){
console.log(response);
                    if(response.email){
                        $('#email-error').text(response.email);
                    }else{
                        $('#email-error').empty();
                    }
                    if(response.password){
                        $('#password-error').text(response.password);
                    }else{
                        $('#password-error').empty();
                    }
                    if(response.password_confirmation){
                        $('#password-con-error').text(response.password_confirmation);
                    }else{
                        $('#password-con-error').empty();
                    }
                    if(response.user_type){
                        $('#userType-error').text(response.user_type);
                    }else{
                        $('#userType-error').empty();
                    }
                    $('#next').html('Next');
                    if(!response.email && !response.password && !response.password_confirmation && !response.user_type){
                        $("#steps").html(response);
                    }

                },error:function(xhr, status, error){

                    console.log(xhr.responseText)
                }
            });
        });

        $(document).ready(function(){
            var tz = jstz.determine(); // Determines the time zone of the browser client
            var timezone = tz.name(); //'Asia/Kolhata' for Indian Time.
            $('#timezone').val(timezone);

        });

    </script>
@endsection
