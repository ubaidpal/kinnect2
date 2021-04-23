@extends('layouts.default')
@section('content')
<!-- Polls-->
<div class="content-gray-title mb10">
    <h4>Poll</h4>
    <a class="btn fltR mr10" title="Create Poll" href="{{ URL::to('polls/create')}}">Create Poll</a>
</div>
<div class="search-box">
    <form method="get" action="{{url('polls/manage')}}">
        <input type="text" name="title" value="{{$title}}">
        <input type="hidden" value="1" name="search_init">
        <button type="submit" class="orngBtn">Search</button>
        @if(!empty($title))
            <a href="{{url('polls/manage')}}">Clear</a>
        @endif
    </form>
</div>
<!-- Post Div-->
 @if($poll[0] == [] && empty($search_init))
    <div style="margin-bottom: 20px;margin-top: 20px;margin-left: 10px;">
        <h4>There are no polls yet. Why don't you
            <a style="padding: 2px 8px;background-color: #EE4B08;color: #FFF;border-radius: 10px;" title="Create Poll" href="{{ URL::to('polls/create')}}">
                Create One
            </a>?
        </h4>
    </div>
 @elseif($poll[0] == [] && !empty($search_init))
     <div style="margin-bottom: 20px;margin-top: 20px;margin-left: 10px;">
         <h4>No polls found matching search criteria</h4>
     </div>
 @else
    @foreach($poll as $polls)
        <div class="my-battles">
          <div class="img">
           <a href="{{ URL::to('view/poll/'.$polls->id) }}" title="{{$polls->title}}">
            <img src="{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_profile')}}" alt="image">
           </a>
          </div>
          <div class="tag-post">
           <div class="tag"><a href="{{ URL::to('view/poll/'.$polls->id) }}" title="{{$polls->title}}">{{$polls->title}}</a></div>
           <?php $Owner = Kinnect2::groupOwner($polls->user_id); ?>
           <div class="posted-by">Posted by <a href="{{url(Kinnect2::profileAddress($Owner))}}">{{$Owner->displayname}}</a></div>
           <div class="post-date">{{$polls->created_at}}</div>
          </div>
          <div class="battle-vote">
           {{$polls->vote_count}} Vote
          </div>
          <div class="battles-btn"  style="left: 310px;">
           <a class="btn" href="{{ URL::to('view/poll/'.$polls->id.'/edit') }}" title="Edit Privacy"><span class="edit"></span>Edit Privacy</a>
           @if ($polls->is_closed==0)
            <a class="btn js-open-modal" title="Clsoe {{$polls->title}}" data-modal-id="popup-{{$polls->id}}" id="open_battle"><span class="close-battle"></span>
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
              <a class="btn open-battle" title="{{$polls->title}}" href="{{ URl::to('polls/closed/'.$polls->id) }}"><span class="close-battle"></span>Open Poll</a>
           @endif
              <a class="btn js-open-modal" data-modal-id="popup1-{{$polls->id}}" title="Delete {{$polls->title}}"><span class="del-battle"></span>
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
{!! str_replace('/?', '?', $poll->appends(['title' => $title])->render()) !!}
@stop()