@extends('layouts.masterDynamic')
@section('content')
@include('includes.ads-left-nav')
        <!--Create Album-->
<div class="community-ad ad_main_wrapper">
	<div class="ad_inner_wrapper">
    <div class="main_heading">
         <h1>Ad Board</h1>
    </div>
    @foreach($userAds as $userAd)
        <div class="community-ad-item">
			<a href="{{url('ads/manage/ad/')}}/{{$userAd->id}}" class="ad_title">{{$userAd->cads_title}}</a>
            <a class="community-item-img" href="{{url('ads/manage/ad/')}}/{{$userAd->id}}">
                <img width="170" height="140" alt="image" src="{{Kinnect2::getPhotoUrl($userAd->photo_id, $userAd->id, 'ads')}}" title="{{$userAd->cads_title}}">
            </a>
            <div class="community-ad-txt">
                <p>{{$userAd->cads_body}}</p>
            </div>
		</div>
    @endforeach
</div>
</div>
@endsection
