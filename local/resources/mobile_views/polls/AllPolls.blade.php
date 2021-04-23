@extends('layouts.default')
@section('content')
    @include('includes.polls_subnav')
<div class="content-gray-title title-bar">
    <h3>My Polls</h3>
</div>


    @if($user_poll == [])

    @else
        @foreach($user_poll as $polls)

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
<div class="content-gray-title title-bar">
    <h3>Other Polls</h3>
</div>


    @foreach($poll as $polls)

         <div class="comment-item">
             <div class="comment-img">
                 <a class="comnt-imgc" href="javascript:void(0)">
                     <img src="{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_profile')}}" alt="img">
                 </a>
             </div>
             <div class="comment-txt">
                 <div class="cmnt-title">
                     <a href="javascript:void(0)">{{$polls->title}}</a>
                 </div>
             </div>
         </div>
   @endforeach

@stop()
