@extends('layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('includes.user-brand-profile-banner')

<div class="mainCont">
    @include('includes.main-left-side')
    <div class="profile-content">
        <div class="content-gray-title mb10">
            <h4>Brand Manager</h4>
        </div>
        <div class="details-list">
            <div class="detail-item">
                <div class="dtl-item">
                    <span>First Name &ast;</span>
                </div>
                <div class="dtl-value">
                    <span>{{$brand->user->first_name}}</span>
                </div>
            </div>

            <div class="detail-item">
                <div class="dtl-item">
                    <span>Last Name &ast;</span>
                </div>
                <div class="dtl-value">
                    <span>{{$brand->user->last_name}}</span>
                </div>
            </div>

        </div>
        <div class="content-gray-title mb10">
            <h4>History</h4>
        </div>
        <p class="formating">
            {{$brand->brand_history}}
        </p>
    </div>
    @include('includes.ads-right-side')
</div>
@endsection