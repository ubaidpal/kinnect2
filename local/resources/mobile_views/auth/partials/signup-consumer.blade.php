{!! Form::open(['url'=> '/auth/login']) !!}
<div class="form-block">
    <input class="form-item" type="text" name="" value="" placeholder="First Name" id="first_name">
    <div class="error" id="first-error"></div>
</div>

<div class="form-block">
    <input class="form-item" type="text" name="" value="" placeholder="Last	 Name" id="last_name">
    <div class="error" id="last-error"></div>
</div>

<div class="form-block">
    {!!  Form::select('gender',
                   [   ''=>'Select Gender *',
                       '1'=>'Male',
                       '2'=>'Female'], session('gender'), ['id' => 'gender'])!!}
    <div class="error" id="gender-error"></div>
</div>

<div class="form-block">
    <label for="">Birthday</label>

    <div class="dob-container">
        <div class="select-dd">
            {!! Form::selectRange('month',1,31,session('date'), ['id' => 'date']) !!}
            <div class="error" id="date-error"></div>
        </div>

        <div class="dob-separator">&sol;</div><!-- separator -->
        <div class="select-dd">
            {!! Form::selectMonth('month', session('month'), ['id' => 'month']) !!}
            <div class="error" id="month-error"></div>
        </div>

        <div class="dob-separator">&sol;</div><!-- separator -->
        <div class="select-dd">
            {!! Form::selectRange('year',date('Y'),1900,session('year'), ['id' => 'year']) !!}
            <div class="error" id="year-error"></div>
        </div>
    </div>
</div>


<div class="form-block">
    {!!  Form::select('country',
                      $countries, session('country'), ['id' => 'country'])!!}
</div>
{!! Form::close() !!}


<div class="btn-signup-container mb20 mt5">
    <a class="btn btn-grey fL" href="{{URL::previous()}}" >Back</a>
    <a class="btn fR" href="javascript:void(0)" id="next">Save</a>

</div>


    <script>
        $('#next').click(function () {
            //if($('#next').html() == 'Please wait..') return false;
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
            if(first_name == ''){
                $('#first-error').text('First name is required');
                return false;
            }else{
                $('#first-error').empty();
            }
            if(last_name == ''){
                $('#last-error').text('Last name is required');
                return false;
            }else{
                $('#last-error').empty();
            }
            if(gender == ''){
                $('#gender-error').text('Gender is required');
                return false;
            }else{
                $('#gender-error').empty();
            }
            if(date == ''){
                $('#date-error').text('Date is required');
                return false;
            }else{
                $('#date-error').empty();
            }
            if(month == ''){
                $('#month-error').text('Month is required');
                return false;
            }else{
                $('#month-error').empty();
            }
            if(year == ''){
                $('#year-error').text('Year is required');
                return false;
            }else{
                $('#year-error').empty();
            }
            if(country == ''){
                $('#country-error').text('Country is required');
                return false;
            }else{
                $('#country-error').empty();
            }
            var dataString = "first_name=" + first_name + "&last_name=" + last_name + "&gender=" + gender + "&date=" + date + "&month=" + month + "&year=" + year + "&website=" + website + "&twitter=" + twitter + "&facebook=" + facebook+ "&country=" + country;
            $.ajax({
                type: 'POST',
                url: '{{url("auth/stepTwo")}}',
                data: dataString,
                success: function (response) {
                    if(response == 'next'){
                        var email = '{!! session('email') !!}';
                        var name = '{!! session('first_name') !!}';
                        var password = '{!! session('password') !!}';
                        var password_confirmation = '{!! session('password_confirmation') !!}';

                        var dataString = "name=" + name + "&email=" + email + "&password=" + password + "&password_confirmation=" + password_confirmation;
                        $.ajax({
                            type: 'POST',
                            url: '{{ url('/auth/register') }}',
                            data: dataString,
                            success: function (data) {
                                $('#steps').html('<div class="line_height_normal">Conglaturation, your account has been registered please check your email ('+email+') to activate it.</div>');
                            }
                        });
                    }
                    //$("#steps").html(response);
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

