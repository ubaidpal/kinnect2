@extends('layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('includes.user-profile-banner')

<div class="mainCont">
    @include('includes.main-left-side')
    <div class="profile-content">
        <div class="title-bar">
            <span>Personal Information</span>
        </div>
        <div class="details-list">
            <div class="detail-item">
                <div class="dtl-item">
                    <span>First Name &ast;</span>
                </div>
                <div class="dtl-value">
                    <span>{{$consumer->user->first_name}}</span>
                </div>
            </div>

            <div class="detail-item">
                <div class="dtl-item">
                    <span>Last Name &ast;</span>
                </div>
                <div class="dtl-value">
                    <span>{{$consumer->user->last_name}}</span>
                </div>
            </div>

            <div class="detail-item">
                <div class="dtl-item">
                    <span>Gender &ast;</span>
                </div>
                <div class="dtl-value">
                    <span>
                        @if($consumer->gender == 1)
                            Male
                        @else
                            Female
                        @endif</span>
                </div>
            </div>

            <div class="detail-item">
                <div class="dtl-item">
                    <span>Birthday</span>
                </div>
                <div class="dtl-value">
                    <span>{{$consumer->birthdate}}</span>
                </div>
            </div>
        </div>
        <div class="content-gray-title mb10">
            <h4>About</h4>
        </div>
        <p class="formating">
            About me data field => {{$consumer->about_me}} </p>
    </div>
    @include('includes.ads-right-side')
</div>
@endsection