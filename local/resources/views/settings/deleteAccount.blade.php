@extends('layouts.masterDynamic')
@section('content')
@include('includes.setting-left-nav')
        <!--Create Album-->
<div class="community-ad">
    <div class="form-container settings">
        {!! Form::open(array('action' => 'UsersController@deleteAccountpage')) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="setting-title">
            <span>Delete Account</span>
        </div>

        <p class="col-dark mt20">
            Are you sure you want to delete your account? Any content you've uploaded in the past will be permanently deleted. You will be immediately signed out and will no longer be able to sign in with this account.
        </p>

        {!! Form::close() !!}
        <div class="save-changes">
            <button id="submit" class="orngBtn">Yes, Delete My Account</button>
        </div>
        <div id="window" class="window" style="display: none;">
            <input type="password" class="form-control m0" name="password" id="password" placeholder="Please enter your password..." required>
            <input type='submit'  id ="del" value='Confirm, Delete My Account' />
            <button id="exit">Cancel</button>
            <div style="color: red" id="error"></div>
        </div>
    </div>
</div>


<script type="text/javascript">
    (function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });//for token purpose in laravel


        $("#exit").click(function(){
            $(".window").hide();
        });

        $("#submit").click(function(){
            $(".window").show();
        });
    })();
    $("#del").click(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var password = document.getElementById('password').value;
        var records = '{{url("delete_account")}}';
        jQuery.ajax({
            url: records,
            data: {password: password},
            type: 'POST',
            success: function (data) {
                if(data == '/logout'){
                    window.location.href = '{{url("/logout")}}';
                }else{
                    jQuery("#error").html(data);
                }
            }
        });
    });


</script>
@endsection
