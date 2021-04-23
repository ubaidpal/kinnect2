<!--  Page - 2  -->
<div class="signup-personal-info">
    <div class="signup-label">Brand Information:</div>
    <form action="#" id="stepTwoBrand">
        <div class="fltL mr10">
            <input type="text" name="brandname" id="brandname" placeholder="Brand Name *" value="{{session('brandname')}}">
            @if($errors->first('brandname'))
                <span>{{ $errors->first('brandname') }}</span>
            @endif
        </div>

        <div class="fltL">
            {!!  Form::select('brand_industry',
                        $industries, session('brand_industry'), ['id' => 'brand_industry'])!!}

        @if($errors->first('brand_industry'))
            <span>{{ $errors->first('brand_industry') }}</span>
        @endif
        </div>
        <div class="clrfix"></div>
        <div class="signup-label mt20">Brand Manager Name:</div>
        <div class="fltL mr10">
            <input type="text" placeholder="First Name *" name="first_name" id="first_name" value="{{session('first_name')}}">
            @if($errors->first('first_name'))
                <span>  {{ $errors->first('first_name') }}</span>
            @endif
        </div>
        <div class="fltL">
            <input type="text" placeholder="Last Name *" name="last_name" id="last_name" value="{{session('last_name')}}">
            @if($errors->first('last_name'))
                <span>  {{ $errors->first('last_name') }}</span>
            @endif
        </div>
        {{--<div class="signup-contant-info">
            <div><textarea name="brand_history" id="brand_history" placeholder="History of your brand."></textarea></div>
            <div><textarea name="description" id="description" placeholder="Tell to your users about your brand."></textarea></div>
        </div>--}}
        <div class="clrfix"></div>
        <div>
            {!!  Form::select('country',
                            $countries, session('country'), ['id' => 'country'])!!}
            @if($errors->first('country'))
                <span>{{ $errors->first('country') }}</span>
            @endif
        </div>
    </form>
    {{-- <div class="signup-contant-info">
         <div class="signup-label">Contact Information:</div>
         <div><input type="text" placeholder="website" name="website" id="website"></div>
         <div><input type="text" placeholder="Twitter" name="twitter" id="twitter"></div>
         <div><input type="text" placeholder="Facebook" name="facebook" id="facebook"></div>
     </div>--}}

    <div class="signup-pager clrfix">
        <div class="circle-pager"></div>
        <div class="circle-pager circle-pager-active"></div>
        <div class="circle-pager"></div>
    </div>
    <div>
        <a class="btn btn-inactive fltL" href="javascript:void(0);" id="back">Back</a>
        <a class="btn fltL" href="javascript:void(0);" id="next">Next</a>
    </div>
</div>
<script src="{!! asset('local/public/assets/js/jquery.validate.min.js') !!}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        jQuery('#stepTwoBrand').validate({
            errorElement : 'span',
            errorPlacement : function (error,element) {
                if(element.next().is('span')){
                    element.next('span').remove();
                }
                error.insertAfter( element);
            },
            rules : {
                'brandname' : {required:true},
                'brand_industry' : {required:true},
                'first_name' : {required:true},
                'last_name' : {required:true},
                'country' : {required:true}
            },
            messages : {
                'brandname' : {
                    required:'{{trans('validation.required', ['attribute' => 'brand name'])}}',
                },
                'brand_industry' : {
                    required : '{{trans('validation.required',['attribute' => 'brand industry'])}}'
                },
                'first_name' : {
                    required : '{{trans('validation.required',['attribute' => 'first name'])}}'
                },
                'last_name' : {
                    required : '{{trans('validation.required',['attribute' => 'last name'])}}'
                },
                'country' : {
                    required: '{{trans('validation.required',['attribute' => 'country'])}}'
                }
            }
        });
        $('#next').click(function () {
            if($('#next').html() == 'Please wait..' || !jQuery('#stepTwoBrand').valid()){
                return false;
            }
            $('#next').html('Please wait..');

            var brandname = $("#brandname").val();
            var country = $("#country").val();
            var first_name = $("#first_name").val();
            var last_name = $("#last_name").val();
            var brand_industry = $("#brand_industry").val();
            var website = $("#website").val();
            var twitter = $("#twitter").val();
            var facebook = $("#facebook").val();
            var brand_history = $("#brand_history").val();
            var description = $("#description").val();


            var dataString = "brandname=" + brandname + "&brand_industry=" + brand_industry + "&last_name=" + last_name + "&first_name=" + first_name + "&website=" + website + "&twitter=" + twitter + "&facebook=" + facebook + "&description=" + description + "&brand_history=" + brand_history + "&country=" + country;
            $.ajax({
                type: 'POST',
                url: '{{url("auth/stepTwoBrand")}}',
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
    });
</script>
