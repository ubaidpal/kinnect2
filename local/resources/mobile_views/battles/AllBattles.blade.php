@extends('layouts.default')
@section('content')

<!-- Battle Browse-->
<div class="content-gray-title mb10">
    <h4>My Battles</h4>
    <a title="Browse" class="btn fltR" href="{{ URL::to('battles/manage')}}">Manage Battles</a>
</div>

<ul>
    @if($user_battle == [])
        <div style="margin-bottom: 20px;margin-top: 20px;margin-left: 10px;">
            <h4>There are no poll yet. Why don't you
                <a style="padding: 2px 8px;background-color: #EE4B08;color: #FFF;border-radius: 10px;"title="Create Battle" href="{{ URL::to('battles/create')}}">
                    Create One
                </a>?
            </h4>
        </div>
    @else
        @foreach($user_battle as $battles)
            <div class="my-battles">
              <div class="img">
               <a href="{{ URL::to('battles/'.$battles->id) }}">
                <img src="{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_profile')}}" alt="image">
               </a>
              </div>
              <div class="tag-post">
               <div class="tag">
               <a href="{{ URL::to('battles/'.$battles->id) }}">{{$battles->title}}</a>
               <img id="close_img" style="display: none" src="{!! asset('local/public/assets/images/close.png') !!}" alt="image">
               </div>
                <?php $Owner = Kinnect2::groupOwner($battles->user_id); ?>
               <div class="posted-by">Posted by <a href="{{url(Kinnect2::profileAddress($Owner))}}">{{$Owner->displayname}}</a></div>
               <div class="post-date">{{$battles->created_at}}</div>
              </div>
              <div class="battle-vote">
               {{$battles->vote_count}} Vote
              </div>
              <div class="battles-btn" style="left: 310px;">

               <a class="btn" href="{{ URL::to('battles/'.$battles->id.'/edit') }}">
                   <span class="edit"></span>
                   Edit Privacy
               </a>

               @if ($battles->is_closed==0)
                <a class="btn js-open-modal" data-modal-id="popup-{{$battles->id}}" id="open_battle"><span class="close-battle"></span>
                    Close Battle
                    {!! Form::open(array('method'=> 'get','url'=> "battles/closed/".$battles->id)) !!}
                      @include('includes.popup',
                          ['submitButtonText' => 'Close Battle',
                          'cancelButtonText' => 'Cancel',
                          'title' => 'Close this Battle',
                          'text' => 'Are You Sure You Want To Close This Battle?',
                          'id' => 'popup-'.$battles->id])
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
                        'id' => 'popup1-'.$battles->id])
                {!! Form::close() !!}
               </a>
              </div>
            </div>
        @endforeach
    @endif
</ul>

<div class="content-gray-title mb10">
    <h4>Other Battles</h4>
</div>

<ul>
 <div class="browse-battle" style="width: 680px">
`   @foreach($battle as $battles)
     @if (Auth::user()->id != $battles->user_id)
       <?php $user = Kinnect2::groupMember($battles->user_id); ?>
       @if(isset($user))
          <div class="browse-battle-item">
            <?php $Owner = Kinnect2::groupOwner($battles->user_id); ?>
            <a class="browse-battle-img" href="{{ URL::to('battles/'.$battles->id) }}">
             <img src="{{Kinnect2::getPhotoUrl($Owner->photo_id, $battles->id, 'user', 'thumb_profile')}}" alt="image">
            </a>
            <div class="battle-item-txt">
             <div class="item-txt-title"><a href="{{ URL::to('battles/'.$battles->id) }}">{{$battles->title}}</a></div>
             <div class="item-txt-post">Posted by <a href="{{url(Kinnect2::profileAddress($Owner))}}">{{$Owner->displayname}}</a></div>
             <div class="item-txt-date">{{$battles->created_at}}</div>
            </div>
            <div class="item-vote">{{$battles->vote_count}} Vote</div>
           </div>
       @endif
     @endif
    @endforeach
 </div>
</ul>


@stop()
