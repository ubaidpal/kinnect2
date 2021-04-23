@extends('layouts.default')
@section('content')
<!-- Battles-->
<div class="content-gray-title mb10 title-bar">
    <h3>Battles</h3>
</div>
    <!-- Post Div-->
    @if($battle[0] == [])

    @else
        @foreach($battle as $battles)
            <div class="comment-item">
                <div class="comment-img">
                    <a class="comnt-imgc" href="{{url('view/battle/'.$battles->id)}}">
                        <img src="{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_profile')}}" alt="img">
                    </a>
                </div>
                <div class="comment-txt">
                    <div class="cmnt-title">
                        <a href="{{url('view/battle/'.$battles->id)}}">{{$battles->title}}</a>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@stop()
