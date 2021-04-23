@extends('layouts.default')
@section('content')
        <!-- Cover Photo Container -->
<div class="page-cover-container">
    <!-- Cover Image -->
    <div class="cover-img">
        <img src="{{Kinnect2::getPhotoUrl($user->cover_photo_id, $user->id, 'user', 'cover_photo')}}" alt="image">
        <a class="btn-cover" href="javascript:void(0)"></a>
    </div>
    <!-- Profile Image -->
    <div class="profile-img-container">
        <div class="prfl-img-block">
            <a class="profile-img" href="javascript:void(0)">
                <img src="{{Kinnect2::getPhotoUrl($user->photo_id, $user->id, 'user', 'thumb_normal')}}" alt="img">
            </a>

            <a class="btn-cover btn-prfl-set" href="javascript:void(0)"></a>
        </div>

        <div class="profile-img-title">
            <a class="profile-title" href="javascript:void(0)">
                {{ ucwords( $user->displayname ) }}
            </a>
        </div>
    </div>
</div>

<!-- Sub Nav -->
@include('includes.user-profile-navigation')
<div style="text-align: center;display: none " id="loading">
    <img alt="Loading..." src="{{asset('local/public/images/loading.gif')}}" id="loading-image">
</div>
<!-- New Post Container -->
@section('mvc-app')
    @include('includes.client-side-mvc')
@show
<div id="mvc-main" data-screen="userProfile" class="profile-content target">

</div>
@include('profile.profile-view-links')
@endsection
@section('footer-scripts')
    {!! HTML::script('local/public/assets/mobile-js/pages/profile.js') !!}
    <style>
        .hide{
            display: none;
        }
    </style>
@endsection
