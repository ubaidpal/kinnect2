{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 12-11-15 12:48 PM
    * File Name    : 

--}}
@can('consumer' , $user)
{!! Form::model($user, array('url' => array('profile/update'),'id' => 'editUser')) !!}
<div class="edit-profile">
    <div class="edit-profile-block">
        <div class="edit-profile-title">
            <span>Personal Information:</span>
        </div>
        <div class="edit-profile-item">
            <div class="form-label">
                <label for="">First Name*</label>
            </div>
            {!! Form::text('first_name',null,['required']) !!}
            <div class="privacy-selector">
                <a href="javascript:();"></a>
            </div>
        </div>
        <div class="edit-profile-item">
            <div class="form-label">
                <label for="">Last Name*</label>
            </div>
            {!! Form::text('last_name',null,['required']) !!}
            <div class="privacy-selector">
                <a href="javascript:();"></a>
            </div>
        </div>
        <?php
        $detail = $user->consumer_detail;
        $day = Carbon\Carbon::parse( $detail->birthdate )->format( 'd' );
        $month = Carbon\Carbon::parse( $detail->birthdate )->format( 'm' );
        $year = Carbon\Carbon::parse( $detail->birthdate )->format( 'Y' );
        ?>
        <div class="edit-profile-item">
            <div class="form-label">
                <label for="">Gender*</label>
            </div>
            <div>
                {!! Form::select('gender', array('1' => 'Male', '2' => 'Female'), $detail->gender) !!}
            </div>
            <div class="privacy-selector">
                <a href="javascript:();"></a>
            </div>
        </div>
        <div class="edit-profile-item">
            <div class="form-label">
                <label for="">Birthday*</label>
            </div>
            <div>
                {!! Form::selectRange('day', 1, 31, $day) !!}
                {!! Form::selectMonth('month', $month) !!}
                {!! Form::selectRange('year' , 2000, 1900, $year) !!}
            </div>
            <div class="privacy-selector">
                <a href="javascript:();"></a>
            </div>
        </div>
    </div>

    <div class="edit-profile-block">
        <div class="edit-profile-title">
            <span>Contact Information:</span>
        </div>
        <div class="edit-profile-item">
            <div class="form-label">
                <label for="">Select Country*</label>
            </div>
            {!! Form::select('country', $countries) !!}
            <div class="privacy-selector">
                <a href="javascript:();"></a>
            </div>
        </div>
        <div class="edit-profile-item">
            <div class="form-label">
                <label for="">Website</label>
            </div>
            {!! Form::text('website') !!}
            <div class="privacy-selector">
                <a href="javascript:();"></a>
            </div>
        </div>
        <div class="edit-profile-item">
            <div class="form-label">
                <label for="">Twitter</label>
            </div>
            {!! Form::text('twitter') !!}
            <div class="privacy-selector">
                <a href="javascript:();"></a>
            </div>
        </div>
        <div class="edit-profile-item">
            <div class="form-label">
                <label for="">Facebook</label>
            </div>
            {!! Form::text('facebook') !!}
            <div class="privacy-selector">
                <a href="javascript:();"></a>
            </div>
        </div>
    </div>

    <div class="edit-profile-block">
        <div class="edit-profile-title">
            <span>Personal Details:</span>
        </div>
        <div class="edit-profile-item">
            <div class="form-label">
                <label for="">About Me</label>
            </div>
            {!! Form::textarea('personnel_info', $detail->personnel_info) !!}
        </div>
        {!! Form::submit('Save',['class'=>'orngBtn']) !!}
    </div>
</div>
{!! Form::close() !!}
    <script type="text/javascript" src="{!! asset('local/public/assets/js/jquery.form.min.js') !!}"></script>
    <script type="text/javascript">
        jQuery(document).on('submit','#editUser',function (e) {
            e.preventDefault();
            var loading = $('#loading');
            loading.show();
            $(this).ajaxSubmit({
                success : function(responseText, statusText, xhr, $form)  {
                    $.ajax({
                        type : 'POST',
                        url : "{{url('profile/profile-view')}}",
                        data : {template : 'info', userId : $('.banner-links').data('user')},
                        cache : true,
                        dataType : 'html',
                        success : function(data){
                            if(data == 'Unauthorized.'){
                                window.location.href = 'auth/login';
                            }
                            jQuery('#edit-profile').addClass('hide');
                            loading.hide();
                            $('#info').html(data).removeClass('hide');
                            jQuery('a[data-target="info"]').addClass('active');
                        }
                    });
            }
        });
        });
    </script>
@endcan
@can('brand' , $user)
<?php
$detail = $consumer;


?>
{!! Form::model($user, array('url' => array('profile/brand-update'),'id' => 'editBrand')) !!}
<div class="edit-profile">
    <div class="edit-profile-block">
        <div class="edit-profile-title">
            <span>Brand Information:</span>
        </div>
        <div class="edit-profile-item">
            <div class="form-label">
                <label for="">Brand Name*</label>
            </div>
            {!! Form::text('brand_name', $detail->brand_name) !!}
            <div class="privacy-selector">
                <a href="javascript:();"></a>
            </div>
        </div>
        <div class="edit-profile-item">
            <div class="form-label">
                <label for="">Manager First Name*</label>
            </div>
            {!! Form::text('first_name') !!}
            <div class="privacy-selector">
                <a href="javascript:();"></a>
            </div>
        </div>
        <div class="edit-profile-item">
            <div class="form-label">
                <label for="">Manager Last Name*</label>
            </div>
            {!! Form::text('last_name') !!}
            <div class="privacy-selector">
                <a href="javascript:();"></a>
            </div>
        </div>

    </div>

    <div class="edit-profile-block">
        <div class="edit-profile-title">
            <span>Contact Information:</span>
        </div>
        <div class="edit-profile-item">
            <div class="form-label">
                <label for="">Select Country*</label>
            </div>
            {!! Form::select('country', $countries) !!}
            <div class="privacy-selector">
                <a href="javascript:();"></a>
            </div>
        </div>

    </div>

    <div class="edit-profile-block">
        <div class="edit-profile-title">
            <span>Personal Details:</span>
        </div>
        <div class="edit-profile-item">
            <div class="form-label">
                <label for="">Brand History</label>
            </div>
            {!! Form::textarea('brand_history', $detail->brand_history) !!}
        </div>
        <div class="edit-profile-item">
            <div class="form-label">
                <label for="">Description</label>
            </div>
            {!! Form::textarea('description', $detail->description) !!}
        </div>
        {!! Form::submit('Save',['class'=>'orngBtn']) !!}
    </div>
</div>
{!! Form::close() !!}
<script type="text/javascript" src="{!! asset('local/public/assets/js/jquery.form.min.js') !!}"></script>
<script type="text/javascript">
    jQuery(document).on('submit','#editBrand',function (e) {
        e.preventDefault();
        var loading = $('#loading');
        loading.show();
        $(this).ajaxSubmit({
            success : function(responseText, statusText, xhr, $form)  {
                $.ajax({
                    type : 'POST',
                    url : "{{url('profile/profile-view')}}",
                    data : {template : 'info', userId : $('.banner-links').data('user')},
                    cache : true,
                    dataType : 'html',
                    success : function(data){
                        if(data == 'Unauthorized.'){
                            window.location.href = 'auth/login';
                        }
                        jQuery('#edit-profile').addClass('hide');
                        loading.hide();
                        $('#info').html(data).removeClass('hide');
                        jQuery('a[data-target="info"]').addClass('active');
                    }
                });
            }
        });
    });
</script>
@endcan
