@extends('layouts.default')
@section('content')
<!-- Battle Browse-->
<div class="content-gray-title mb10">
    <h4>My Polls</h4>
    <a title="Browse" class="btn fltR" href="javascript:();">Manage Polls</a>
</div>
<div class="my-battles">
      <div class="img">
       <a href="javascript:();">
        <img src="{!! asset('local/public/assets/images/my-battle-logo-img.jpg') !!}" alt="image">
       </a>
      </div>
      <div class="tag-post">
       <div class="tag"><a href="javascript:();">Tag Test</a></div>
       <div class="posted-by">Posted by <a href="javascript:();">Apple</a></div>
       <div class="post-date">March 6</div>
      </div>
      <div class="battle-vote">
       1 Vote
      </div>
      <div class="battles-btn">
       <a class="btn" href="javascript:();"><span class="edit"></span>Edit Privacy</a>
       <a class="btn open-battle" href="javascript:();"><span class="close-battle"></span>Open Poll</a>
       <a class="btn" href="javascript:();"><span class="del-battle"></span>Delete Poll</a>
      </div>
</div>
<div class="my-battles">
      <div class="img">
       <a href="javascript:();">
        <img src="{!! asset('local/public/assets/images/my-battle-logo-img.jpg') !!}" alt="image">
       </a>
      </div>
      <div class="tag-post">
       <div class="tag"><a href="javascript:();">Tag Test</a></div>
       <div class="posted-by">Posted by <a href="javascript:();">Apple</a></div>
       <div class="post-date">March 6</div>
      </div>
      <div class="battle-vote">
       1 Vote
      </div>
      <div class="battles-btn">
       <a class="btn" href="javascript:();"><span class="edit"></span>Edit Privacy</a>
       <a class="btn" href="javascript:();"><span class="close-battle"></span>Close Poll</a>
       <a class="btn" href="javascript:();"><span class="del-battle"></span>Delete Poll</a>
      </div>
    </div>
<div class="my-battles">
      <div class="img">
       <a href="javascript:();">
        <img src="{!! asset('local/public/assets/images/my-battle-logo-img.jpg') !!}" alt="image">
       </a>
      </div>
      <div class="tag-post">
       <div class="tag"><a href="javascript:();">Tag Test</a></div>
       <div class="posted-by">Posted by <a href="javascript:();">Apple</a></div>
       <div class="post-date">March 6</div>
      </div>
      <div class="battle-vote">
       1 Vote
      </div>
      <div class="battles-btn">
       <a class="btn" href="javascript:();"><span class="edit"></span>Edit Privacy</a>
       <a class="btn" href="javascript:();"><span class="close-battle"></span>Close Poll</a>
       <a class="btn" href="javascript:();"><span class="del-battle"></span>Delete Poll</a>
      </div>
    </div>
<div class="my-battles">
      <div class="img">
       <a href="javascript:();">
        <img src="{!! asset('local/public/assets/images/my-battle-logo-img.jpg') !!}" alt="image">
       </a>
      </div>
      <div class="tag-post">
       <div class="tag"><a href="javascript:();">Tag Test</a></div>
       <div class="posted-by">Posted by <a href="javascript:();">Apple</a></div>
       <div class="post-date">March 6</div>
      </div>
      <div class="battle-vote">
       1 Vote
      </div>
      <div class="battles-btn">
       <a class="btn" href="javascript:();"><span class="edit"></span>Edit Privacy</a>
       <a class="btn" href="javascript:();"><span class="close-battle"></span>Close Poll</a>
       <a class="btn" href="javascript:();"><span class="del-battle"></span>Delete Poll</a>
      </div>
    </div> 

<div class="content-gray-title mb10">
    <h4>Other Polls</h4>
</div>
<div class="browse-battle">
   <div class="browse-battle-item">
    <a class="browse-battle-img" href="javascript:();">
     <img src="{!! asset('local/public/assets/images/browse-battle.jpg') !!}" alt="image">
    </a>
    <div class="battle-item-txt">
     <div class="item-txt-title">Which one is better?</div>
     <div class="item-txt-post">Posted by <a href="javascript:();">Paul Smith</a></div>
     <div class="item-txt-date">March 6</div>
    </div>
    <div class="item-vote">1 Vote</div>
   </div>

   <div class="browse-battle-item">
    <a class="browse-battle-img" href="javascript:();">
     <img src="{!! asset('local/public/assets/images/browse-battle.jpg') !!}" alt="image">
    </a>
    <div class="battle-item-txt">
     <div class="item-txt-title">Which one is better?</div>
     <div class="item-txt-post">Posted by <a href="javascript:();">Paul Smith</a></div>
     <div class="item-txt-date">March 6</div>
    </div>
    <div class="item-vote">1 Vote</div>
   </div>

   <div class="browse-battle-item">
    <a class="browse-battle-img" href="javascript:();">
     <img src="{!! asset('local/public/assets/images/browse-battle.jpg') !!}" alt="image">
    </a>
    <div class="battle-item-txt">
     <div class="item-txt-title">Which one is better?</div>
     <div class="item-txt-post">Posted by <a href="javascript:();">Paul Smith</a></div>
     <div class="item-txt-date">March 6</div>
    </div>
    <div class="item-vote">1 Vote</div>
   </div>

   <div class="browse-battle-item">
    <a class="browse-battle-img" href="javascript:();">
     <img src="{!! asset('local/public/assets/images/browse-battle.jpg') !!}" alt="image">
    </a>
    <div class="battle-item-txt">
     <div class="item-txt-title">Which one is better?</div>
     <div class="item-txt-post">Posted by <a href="javascript:();">Paul Smith</a></div>
     <div class="item-txt-date">March 6</div>
    </div>
    <div class="item-vote">1 Vote</div>
   </div>
</div>
@endsection






<ul>
    @foreach($poll as $polls)
        @if ($polls->user_id !=1)
        <div class="browse-battle">
          <div class="browse-battle-item">
            <a class="browse-battle-img" href="{{ action('PollsController@show', [$polls->id] ) }}">
             <img src="{!! asset('local/public/assets/images/browse-battle.jpg') !!}" alt="image">
            </a>
            <div class="battle-item-txt">
             <div class="item-txt-title"><a href="{{ action('PollsController@show', [$polls->id] ) }}">{{$polls->title}}</a></div>
             <div class="item-txt-post">Posted by <a href="javascript:();">user name aye ga</a></div>
             <div class="item-txt-date">{{$polls->created_at}}</div>
            </div>
            <div class="item-vote">{{$polls->vote_count}} Vote</div>
           </div>
        </div>
        @endif
    @endforeach
</ul>