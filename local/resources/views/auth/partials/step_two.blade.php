<!--  Page - 2  -->
<div class="signup-personal-info">
    <form action="#" id="stepTwo">
        <div class="signup-label">Personal Information:</div>
        <div class="fltL mr10">
            <input type="text" placeholder="First Name *" name="first_name" id="first_name" value="{{session('first_name')}}">
        @if($errors->first('first_name'))
            <span>  {{ $errors->first('first_name') }}</span>
        @endif
        </div>

        <div>
            <input type="text" placeholder="Last Name *" name="last_name" id="last_name" value="{{session('last_name')}}">

            @if($errors->first('last_name'))
                <span>  {{ $errors->first('last_name') }}</span>
            @endif
        </div>

        {!!  Form::select('gender',
                        [   ''=>'Select Gender *',
                            '1'=>'Male',
                            '2'=>'Female'], session('gender'), ['id' => 'gender'])!!}

        @if($errors->first('gender'))
            <span> {{ $errors->first('gender') }}</span>
        @endif


        <div class="signup-dob">
            <div class="signup-label">Date of Birth</div>
            {!!  Form::select('date',
                        $dates, session('date'), ['id' => 'date'])!!}
            {!!  Form::select('month',
                        $months, session('month'), ['id' => 'month'])!!}
            {!!  Form::select('year',
                         $years, session('year'), ['id' => 'year'])!!}
            <div class="dobError">
                @if($errors->first('date') || $errors->first('month') || $errors->first('year'))
                    <span>Select valid date of birth</span>
                @endif
            </div>

        </div>
        <div>
            {!!  Form::select('country',
                         $countries, session('country'), ['id' => 'country'])!!}
            @if($errors->first('country'))
                <span>{{ $errors->first('country') }}</span>
            @endif
        </div>
        {{--<div class="signup-contant-info">
            <div class="signup-label">Contact Information:</div>
            <div><input type="text" placeholder="website" name="website" id="website" value="{{session('website')}}"></div>
            <div><input type="text" placeholder="Twitter" name="twitter" id="twitter" value="{{session('twitter')}}"></div>
            <div><input type="text" placeholder="Facebook" name="facebook" id="facebook" value="{{session('facebook')}}">
            </div>
        </div>--}}

        <div class="signup-pager cf">
            <div class="circle-pager"></div>
            <div class="circle-pager circle-pager-active"></div>
            <div class="circle-pager"></div>
        </div>
        <div>
            <a class="btn btn-inactive fltL" href="javascript:void(0);" id="back">Back</a>
            <a class="btn fltL" href="javascript:void(0);" id="next">Next</a>
        </div>
    </form>
</div>
<script src="{!! asset('local/public/assets/js/jquery.validate.min.js') !!}"></script>
<script type="text/javascript">
    jQuery(document).ready(function (e) {
        jQuery.validator.addMethod("alpha", function(value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
        });
        jQuery('#stepTwo').validate({
            errorElement : 'span',
            groups : {
                dob : "date month year"
            },
            errorPlacement: function(error, element) {
                if(element.next().is('span')){
                    element.next('span').remove();
                }
                var element_name = element.attr('name');
                if(element_name == 'date' || element_name == 'month' || element_name == 'year'){
                    jQuery('.dobError').html('<span>Select valid date of birth</span>');
                }else{
                    error.insertAfter( element);
                }
            },
            rules : {
                'first_name' : {required:true,alpha:true},
                'last_name' : {required:true,alpha:true},
                'gender' : {required:true},
                'country' : {required:true},
                'date' : {required:true},
                'month' : {required:true},
                'year' : {required:true},
            },
            messages : {
                'first_name' : {
                    required:"{{trans('validation.required',['attribute' => 'first name'])}}",
                    alpha : "{{trans('validation.alpha',['attribute' => 'first name'])}}"
                },
                'last_name' : {
                    required:"{{trans('validation.required',['attribute' => 'last name'])}}",
                    alpha : "{{trans('validation.alpha',['attribute' => 'last name'])}}"
                },
                'gender' : {
                    required:"{{trans('validation.required',['attribute' => 'gender'])}}"
                },
                'country' : {
                    required:"{{trans('validation.required',['attribute' => 'country'])}}"
                }
            }
        });
    });
    $('.signup-header h2').html("Step 2");

    $('#next').click(function () {
        if($('#next').html() == 'Please wait..' || !jQuery('#stepTwo').valid()){
            return false;
        }
        $('#next').html('Please wait..');

        var first_name = $("#first_name").val();
        var last_name = $("#last_name").val();
        var gender = $("#gender").val();
        var date = $("#date").val();
        var month = $("#month").val();
        var year = $("#year").val();
        var website = '';//$("#website").val();
        var twitter = '';//$("#twitter").val();
        var facebook = '';//$("#facebook").val();
        var country = $("#country").val();

        var dataString = "first_name=" + first_name + "&last_name=" + last_name + "&gender=" + gender + "&date=" + date + "&month=" + month + "&year=" + year + "&website=" + website + "&twitter=" + twitter + "&facebook=" + facebook+ "&country=" + country;
        $.ajax({
            type: 'POST',
            url: '{{url("auth/stepTwo")}}',
            data: dataString,
            success: function (response) {
                $("#steps").html(response);
                $('#next').html('Next');
            }
        });
    });
    $('#back').click(function () {
        var dataString = 'back=1';
        $.ajax({
            type: 'POST',
            url: '{{url("auth/stepOne")}}',
            data: dataString,
            success: function (response) {
                $("#steps").html(response);
            }
        });
    });
</script>