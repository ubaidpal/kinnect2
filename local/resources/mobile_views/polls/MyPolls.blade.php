@extends('layouts.default')
@section('content')
    @include('includes.polls_subnav');
<!-- Post Div-->
 @if($poll[0] == [])

 @else
    @foreach($poll as $polls)
            <div class="comment-item">
                <div class="comment-img">
                    <a class="comnt-imgc" href="{{url('view/poll/'.$polls->id)}}">
                        <img src="{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_profile')}}" alt="img">
                    </a>
                </div>
                <div class="comment-txt">
                    <div class="cmnt-title">
                        <a href="{{url('view/poll/'.$polls->id)}}">{{$polls->title}}</a>
                    </div>
                </div>
            </div>

    @endforeach
 @endif
@stop()
