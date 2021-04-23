@extends('layouts.default')
@section('content')
<!-- Battle Browse-->
<div class="content-gray-title mb10">
    <h4>My Battles</h4>
    <a title="Browse" class="btn fltR" href="javascript:();">Manage Battles</a>
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
           <a class="btn open-battle" href="javascript:();"><span class="close-battle"></span>Open Battle</a>
           <a class="btn" href="javascript:();"><span class="del-battle"></span>Delete Battle</a>
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
       <a class="btn" href="javascript:();"><span class="close-battle"></span>Close Battle</a>
       <a class="btn" href="javascript:();"><span class="del-battle"></span>Delete Battle</a>
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
       <a class="btn" href="javascript:();"><span class="close-battle"></span>Close Battle</a>
       <a class="btn" href="javascript:();"><span class="del-battle"></span>Delete Battle</a>
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
       <a class="btn" href="javascript:();"><span class="close-battle"></span>Close Battle</a>
       <a class="btn" href="javascript:();"><span class="del-battle"></span>Delete Battle</a>
      </div>
    </div> 

<div class="content-gray-title mb10">
    <h4>Other Battles</h4>
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