<form action="">
    <div class="form-block">
        <input class="form-item" type="text" name="first_name" id="first_name" value="{{session('brandname')}}" placeholder="Brand Manager First Name" >
        <div class="error" id="first-error"></div>
    </div>

    <div class="form-block">
        <input class="form-item" type="text" name="" value="" placeholder="Brand Manger Last	 Name" id="last_name">
        <div class="error" id="last-error"></div>
    </div>

    <div class="form-block">
        <input class="form-item" type="text" name="brandname" id="brandname" value="{{session('brandname')}}" placeholder="Brand Name">
        <div class="error" id="brand-error"></div>
    </div>

    <div class="form-block">
        {!!  Form::select('brand_industry',
                     $industries, session('brand_industry'), ['id' => 'brand_industry'])!!}
        <div class="error" id="industry-error"></div>
    </div>

    <div class="form-block">
        {!!  Form::select('country',
                   $countries, session('country'), ['id' => 'country'])!!}
        <div class="error" id="country-error"></div>
    </div>
</form>


<div class="btn-signup-container mb20 mt5">
    <a class="btn btn-grey fL" href="{{URL::previous()}}" >Back</a>
    <a class="btn fR" href="javascript:void(0)" id="next">Save</a>
</div>

<script>
    $('#next').click(function () {
        //if($('#next').html() == 'Please wait..') return false;
        $('#next').html('Please wait..');

        var brandname = $("#brandname").val();
        var country = $("#country").val();
        var first_name = $("#first_name").val();
        var last_name = $("#last_name").val();
        var brand_industry = $("#brand_industry").val();
        var website = '';//$("#website").val();
        var twitter = '';//$("#twitter").val();
        var facebook = '';//$("#facebook").val();
        var brand_history = $("#brand_history").val();
        var description ='';// $("#description").val();

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
        if(brandname == ''){
            $('#brand-error').text('Brand Name is required');
            return false;
        }else{
            $('#brand-error').empty();
        }
        if(brand_history == ''){
            $('#industry-error').text('Brand Industry is required');
            return false;
        }else{
            $('#industry-error').empty();
        }
        if(country == ''){
            $('#country-error').text('Country is required');
            return false;
        }else{
            $('#country-error').empty();
        }
        var dataString = "brandname=" + brandname + "&brand_industry=" + brand_industry + "&last_name=" + last_name + "&first_name=" + first_name + "&website=" + website + "&twitter=" + twitter + "&facebook=" + facebook + "&description=" + description + "&brand_history=" + brand_history + "&country=" + country;
        $.ajax({
            type: 'POST',
            url: '{{url("auth/stepTwoBrand")}}',
            data: dataString,
            success: function (response) {
                if(response == 'next'){
                    var email = '{!! session('email') !!}';
                    var name = '{!! session('brandname') !!}';
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
                //$('#next').html('Next');
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
