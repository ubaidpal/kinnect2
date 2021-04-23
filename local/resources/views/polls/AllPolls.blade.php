@extends('layouts.default')
@section('content')

<div class="content-gray-title mb10">
    <h4>My Polls</h4>
    <a title="Browse" class="btn fltR" href="{{ URL::to('polls/manage')}}">Manage Polls</a>
</div>
<div class="search-box">
    <form method="get" action="{{url('polls')}}">
        <input type="text" name="title" value="{{$query['title']}}">
        <input type="hidden" value="1" name="search_init">
        <button type="submit" class="orngBtn">Search</button>
        @if(!empty($query['title']))
            <a href="{{url('polls')}}">Clear</a>
        @endif
    </form>
</div>
<ul>
    @if($user_poll->count() <= 0 && empty($query['search_init']))
        <div style="margin-bottom: 20px;margin-top: 20px;margin-left: 10px;">
            <h4>
                There are no poll yet. Why don't you
                <a title="Create Poll" style="padding: 2px 8px;background-color: #EE4B08;color: #FFF;border-radius: 10px;" href="{{ URL::to('polls/create')}}">
                    Create One
                </a>?
            </h4>
        </div>
    @elseif($user_poll->count() <= 0 && !empty($query['search_init']))
        <div style="margin-bottom: 20px;margin-top: 20px;margin-left: 10px;">
            <h4>No polls found matching search criteria</h4>
        </div>
    @else
        @foreach($user_poll as $polls)
            <div class="my-battles">
              <div class="img">
               <a href="{{ URL::to('view/poll/'.$polls->id) }}">
                <img src="{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_profile')}}" alt="image">
               </a>
              </div>
              <div class="tag-post">
               <div class="tag">
               <a href="{{ URL::to('view/poll/'.$polls->id) }}">{{$polls->title}}</a>
               <img id="close_img" style="display: none" src="{!! asset('local/public/assets/images/close.png') !!}" alt="image">
               </div>
               <?php $Owner = Kinnect2::groupOwner($polls->user_id); ?>
               <div class="posted-by">Posted by <a href="{{url(Kinnect2::profileAddress($Owner))}}">{{$Owner->displayname}}</a></div>
               <div class="post-date">{{$polls->created_at}}</div>
              </div>
              <div class="battle-vote">
               {{$polls->vote_count}} Vote
              </div>
              <div class="battles-btn"  style="left: 310px;">
               <a class="btn" href="{{ URL::to('view/poll/'.$polls->id.'/edit') }}"><span class="edit"></span>Edit Privacy</a>
               @if ($polls->is_closed==0)
                <a class="btn js-open-modal" data-modal-id="popup-{{$polls->id}}" id="open_battle"><span class="close-battle"></span>
                    Close Poll
                    {!! Form::open(array('method'=> 'get','url'=> "polls/closed/".$polls->id)) !!}
                      @include('includes.popup',
                          ['submitButtonText' => 'Close Poll',
                          'cancelButtonText' => 'Cancel',
                          'title' => 'Close this Poll',
                          'text' => 'Are You Sure You Want To Close This Poll?',
                          'id' => 'popup-'.$polls->id])
                    {!! Form::close() !!}
                </a>
               @elseif ($polls->is_closed == 1)
                 <a class="btn open-battle" href="{{ URl::to('polls/closed/'.$polls->id) }}"><span class="close-battle"></span>Open Poll</a>
               @endif
                 <a class="btn js-open-modal" data-modal-id="popup1-{{$polls->id}}"><span class="del-battle"></span>
                     Delete Poll
                     {!! Form::open(array('method'=> 'get','url'=> "polls/delete/".$polls->id)) !!}
                       @include('includes.popup',
                           ['submitButtonText' => 'Delete Poll',
                           'cancelButtonText' => 'Cancel',
                           'title' => 'Delete this Poll',
                           'text' => 'Are You Sure You Want To Delete This Poll?',
                           'id' => 'popup1-'.$polls->id])
                     {!! Form::close() !!}
                 </a>
              </div>
            </div>
        @endforeach
    @endif
</ul>

<div class="content-gray-title mb10">
    <h4>Other Polls</h4>
</div>

<ul>
 <div class="browse-battle" style="width: 680px">`
    @foreach($poll as $polls)
     @if (Auth::user()->id != $polls->user_id)
      <?php $user = Kinnect2::groupMember($polls->user_id); ?>
      @if(isset($user))
          <div class="browse-battle-item">
            <?php $Owner = Kinnect2::groupOwner($polls->user_id); ?>
            <a class="browse-battle-img" href="{{ URL::to('view/poll/'.$polls->id) }}">
                <img src="{{Kinnect2::getPhotoUrl($Owner->photo_id, $polls->id, 'user', 'thumb_profile')}}" alt="image">
            </a>
            <div class="battle-item-txt">
             <div class="item-txt-title"><a href="{{ URL::to('view/poll/'.$polls->id) }}">{{$polls->title}}</a></div>
             <div class="item-txt-post">Posted by <a href="{{url(Kinnect2::profileAddress($Owner))}}">{{$Owner->displayname}} </a></div>
             <div class="item-txt-date">{{$polls->created_at}}</div>
            </div>
            <div class="item-vote">{{$polls->vote_count}} Vote</div>
           </div>
       @endif
     @endif
   @endforeach
 </div>
</ul>
@stop()
