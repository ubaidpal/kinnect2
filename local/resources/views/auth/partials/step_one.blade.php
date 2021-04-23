<form name="stepOne" id="stepOne" action="#" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="timezone" value="" id="timezone">
    <!--  Page - 1  -->
    <div>
        <input type="email" placeholder="{{trans('auth.email')}} *" value="{{session('email')}}" name="email" id="signup_email"
                title="{{trans('titles.signup_email')}}">
    </div>
    <span>
        @if(isset($signup) AND $errors->first('email'))
            {{ $errors->first('email') }}
        @endif
    </span>

    <div>
        <input type="password" placeholder="{{trans('password')}} *" name="password" id="password"
        title="{{trans('titles.signup_password')}}">
    </div>
    <span>
        @if(isset($signup) AND $errors->first('password'))
            @if($errors->first('password') == "The password format is invalid.")
                {{trans('titles.signup_password')}}
            @else
                {{$errors->first('password')}}
            @endif
        @endif

    </span>

    <div>
        <input type="password" placeholder="Re-Enter Password *" name="password_confirmation"
        id="password_confirmation">
    </div>
    <span>
        @if(isset($signup) AND $errors->first('password_confirmation'))
             {{ $errors->first('password_confirmation') }}
         @endif
    </span>
    {!!  Form::select('user_type',trans('titles.user_types'), session('user_type'), ['id' => 'user_type'])!!}
    <span>

    @if(isset($signup) AND $errors->first('user_type'))
    {{ $errors->first('user_type') }}
    @endif
    </span>

    <div class="signup-policy">
        {!! trans('titles.terms_privacy_cookies',['terms_url' => url('policy/terms'),'privacy_url' => url('policy/condition'),'cookies_url' => url('policy/condition#cookies')]) !!}
    </div>
    <div class="signup-pager cf">
        <div class="circle-pager circle-pager-active"></div>
        <div class="circle-pager"></div>
        <div class="circle-pager"></div>
    </div>
    <a class="btn fltN" href="javascript:void(0);" id="next">{{trans('buttons.next')}}</a>

    <?php /*<div class="create-page">
        {!! trans('titles.create_page',['url' => url('login/createPage')])  !!}
    </div> */ ?>
</form>
{!! HTML::script('local/public/assets/js/timeZone.js') !!}
<script src="{!! asset('local/public/assets/js/jquery.validate.min.js') !!}"></script>
<script type="text/javascript">
    jQuery(document).ready(function (e) {
        jQuery.validator.addMethod("regex", function(value, element) {
            return this.optional(element) || value == value.match(/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{7,}$/);
        });
        jQuery('#stepOne').validate({
            errorElement : 'span',
            errorPlacement: function(error, element) {
                if(element.attr('name') == 'user_type'){
                    element.next('span').remove();
                    error.insertAfter(element);
                }else {
                    element.parent('div').next('span').remove();
                    error.insertAfter(element.parent("div"));
                }
            },
            rules : {
                'email' : {required:true,email:true},
                'password' : {required:true,minlength:7,regex:true},
                'password_confirmation' : {required:true,equalTo : "#password"},
                'user_type' : {required:true}
            },
            messages : {
                'email' : {
                    required:"{{trans('validation.required',['attribute' => 'email'])}}",
                    email:"{{trans('validation.email',['attribute' => 'email'])}}"
                },
                'password' : {
                    required : "{{trans('validation.required',['attribute' => 'password'])}}",
                    minlenght : "{{trans('validation.min.numeric',['attribute' => 'password'])}}",
                    regex : "{{trans('titles.signup_password')}}"
                },
                'password_confirmation' : {
                    required : "{{trans('validation.required',['attribute' => 'password confirmation'])}}",
                    equalTo : "{{trans('validation.same',['attribute' => 'password confirmation','other' => 'password'])}}"
                },
                'user_type' : {
                    required: "{{trans('validation.required',['attribute' => 'user type'])}}"
                }
            }
        });
    });
    $('#next').click(function(){
        if($('#next').html() ==  "{{trans('buttons.please_wait')}}" || !jQuery('#stepOne').valid() ){
            return false;
        }

        var email                 = $("#signup_email").val();
        var profile_address       = $("#profile_address").val();
        var password              = $("#password").val();
        var password_confirmation = $("#password_confirmation").val();
        var user_type             = $("#user_type").val();
        var timezone              = $("#timezone").val();

        $('#next').html("{{trans('buttons.please_wait')}}");

        var dataString = "email=" + email + "&username=" + profile_address + "&user_type=" + user_type + "&password=" + password + "&password_confirmation=" + password_confirmation + '&timezone=' + timezone;
        $.ajax({
            type : 'POST', url : '{{url("auth/stepOne")}}', data : dataString, success : function(response){
                $("#steps").html(response);
                $('#next').html("{{trans('buttons.next')}}");
            }
        });
    });

    $(document).ready(function(){
        var tz = jstz.determine(); // Determines the time zone of the browser client
        var timezone = tz.name(); //'Asia/Kolhata' for Indian Time.
        $('#timezone').val(timezone);

    });

</script>