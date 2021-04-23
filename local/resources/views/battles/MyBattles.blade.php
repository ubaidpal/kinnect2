@extends('layouts.default')
@section('content')
<!-- Battles-->
<div class="content-gray-title mb10">
    <h4>Battles</h4>
    <a class="btn fltR mr10" title="Create Battle" href="{{ URL::to('battles/create')}}">Create Battle</a>
</div>
<div class="search-box">
    <form method="get" action="{{url('battles/manage')}}">
        <input type="text" name="title" value="{{$title}}">
        <input type="hidden" value="1" name="search_init">
        <button type="submit" class="orngBtn">Search</button>
        @if(isset($title))
        <a href="{{url('battles/manage')}}">Clear</a>
        @endif
    </form>
</div>
<!-- Post Div-->

    @if($battle[0] == [] && empty($search_init))
         <div style="margin-bottom: 20px;margin-top: 20px;margin-left: 10px;">
             <h4>There are no battles yet. Why don't you
                <a style="padding: 2px 8px;background-color: #EE4B08;color: #FFF;border-radius: 10px;"title="Create Battle" href="{{ URL::to('battles/create')}}">
                    Create One
                </a>?
             </h4>
         </div>
    @elseif($battle[0] == [] && !empty($search_init))
        <div style="margin-bottom: 20px;margin-top: 20px;margin-left: 10px;">
            <h4>No battle found matching search criteria.</h4>
        </div>
    @else
        @foreach($battle as $battles)
            <div class="my-battles">
              <div class="img">
               <a href="{{ URL::to('view/battle/'.$battles->id) }}">
                <img src="{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_profile')}}" alt="image">
               </a>
              </div>
              <div class="tag-post">
               <div class="tag"><a href="{{ URL::to('view/battle/'.$battles->id) }}">{{$battles->title}}</a></div>
               <?php $Owner = Kinnect2::groupOwner($battles->user_id); ?>
               <div class="posted-by">Posted by <a href="{{url(Kinnect2::profileAddress($Owner))}}">{{$Owner->displayname}}</a></div>
               <div class="post-date">{{$battles->created_at}}</div>
              </div>
              <div class="battle-vote">
               {{$battles->vote_count}} Vote
              </div>
              <div class="battles-btn" style="left: 310px;">

               <a class="btn" href="{{ URL::to('battles/'.$battles->id.'/edit') }}"><span class="edit"></span>Edit Privacy</a>
               @if ($battles->is_closed==0)
                <a class="btn js-open-modal" data-modal-id="popup-{{$battles->id}}"><span class="close-battle"></span>
                    Close Battle
                    {!! Form::open(array('method'=> 'get','url'=> "battles/closed/".$battles->id)) !!}
                      @include('includes.popup',
                          ['submitButtonText' => 'Close Battle',
                          'cancelButtonText' => 'Cancel',
                          'title' => 'Close this Battle',
                          'text' => 'Are You Sure You Want To Close This Battle?',
                          'id' => 'popup-'.$battles->id ])
                    {!! Form::close() !!}
                </a>
               @elseif ($battles->is_closed == 1)
                <a class="btn open-battle" href="{{ URl::to('battles/closed/'.$battles->id) }}"><span class="close-battle"></span>Open Battle</a>
               @endif
               <a class="btn js-open-modal" data-modal-id="popup1-{{$battles->id}}"><span class="del-battle"></span>
                  Delete Battle
                  {!! Form::open(array('method'=> 'get','url'=> "battles/delete/".$battles->id)) !!}
                      @include('includes.popup',
                          ['submitButtonText' => 'Delete Battle',
                          'cancelButtonText' => 'Cancel',
                          'title' => 'Delete this Battle',
                          'text' => 'Are You Sure You Want To Delete This Battle?',
                          'id' => 'popup1-'.$battles->id ])
                  {!! Form::close() !!}
               </a>
              </div>
            </div>
        @endforeach
    @endif

 <div class="pagination_Cont">{!! str_replace('/?', '?', $battle->appends(['title' => $title])->render()) !!}</div>
@stop()
