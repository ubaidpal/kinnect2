{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 11-11-15 6:39 PM
    * File Name    : 

--}}
@extends('layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('includes.user-profile-banner')

<div class="mainCont">
    @include('includes.main-left-side')
    <div class="profile-content">
        {!! Form::model($user, array('url' => array('profile/update'))) !!}
        <div class="edit-profile">
            <div class="edit-profile-block">
                <div class="edit-profile-title">
                    <span>Personal Information:</span>
                </div>
                <div class="edit-profile-item">
                    <div class="form-label">
                        <label for="">First Name*</label>
                    </div>
                    {!! Form::text('first_name') !!}
                    <div class="privacy-selector">
                        <a href="javascript:();"></a>
                    </div>
                </div>
                <div class="edit-profile-item">
                    <div class="form-label">
                        <label for="">Last Name*</label>
                    </div>
                    {!! Form::text('last_name') !!}
                    <div class="privacy-selector">
                        <a href="javascript:();"></a>
                    </div>
                </div>
                <?php
                $detail = $user->consumer_detail;
                    $day = Carbon\Carbon::parse($detail->birthdate)->format('d');
                $month = Carbon\Carbon::parse($detail->birthdate)->format('m');
                $year = Carbon\Carbon::parse($detail->birthdate)->format('Y');
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
                    {!! Form::submit('Save') !!}
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    @include('includes.ads-right-side')
</div>
@endsection
@section('footer-scripts')
    <script src="{!! asset('local/public/assets/js/inner-pages.js') !!}"></script>
@endsection
