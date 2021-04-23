@extends('layouts.default')
@section('content')
<!--Friend Requests-->
<div class="content-gray-title mb10">
    <h4>My Requests</h4>
    <a class="btn fltR" title="View sent Requests" href="javascript:();">View sent Requests</a>
</div>
<!-- Post Div-->
<div class="my-battles">
    <div class="img">
   <a href="javascript:();">
    <img src="{!! asset('local/public/assets/images/friend-request-1.jpg') !!}" alt="image">
   </a>
  </div>
    <div class="tag-post">
   <div class="tag"><a href="javascript:();">Mirenda Jacob</a></div>
   <div class="posted-by">Professional Model</div>
   <div class="post-date"><a href="javascript:();">Paul</a> s a mutual friend</div>
  </div>
    <div class="battles-btn">
<a class="btn btn-orange" href="javascript:();"><span class="open-confirm"></span>Confirm</a>
<a class="btn" href="javascript:();"><span class="del-battle"></span>Delete Battle</a>
</div>
</div>

<div class="my-battles">
<div class="img">
<a href="javascript:();">
<img src="{!! asset('local/public/assets/images/friend-request-2.jpg') !!}" alt="image">
</a>
</div>
<div class="tag-post">
<div class="tag"><a href="javascript:();">Adam</a></div>
<div class="posted-by">Professional Model</div>
<div class="post-date"><a href="javascript:();">Elizabeth</a> and <a href="javascript:();">40 other mutual friends</a></div>
</div>
<div class="battles-btn">
<a class="btn btn-orange" href="javascript:();"><span class="open-confirm"></span>Confirm</a>
<a class="btn" href="javascript:();"><span class="del-battle"></span>Delete Battle</a>
</div>
</div>

<div class="my-battles">
<div class="img">
<a href="javascript:();">
<img src="{!! asset('local/public/assets/images/friend-request-1.jpg') !!}" alt="image">
</a>
</div>
<div class="tag-post">
<div class="tag"><a href="javascript:();">Jennifer Buckman</a></div>
<div class="posted-by">Artist at <a href="javascript:();">Art Gallery</a></div>
</div>
<div class="battles-btn">
<a class="btn btn-orange" href="javascript:();"><span class="open-confirm"></span>Confirm</a>
<a class="btn" href="javascript:();"><span class="del-battle"></span>Delete Battle</a>
</div>
</div>

<div class="my-battles">
<div class="img">
<a href="javascript:();">
<img src="{!! asset('local/public/assets/images/friend-request-2.jpg') !!}" alt="image">
</a>
</div>
<div class="tag-post">
<div class="tag"><a href="javascript:();">Adam</a></div>
<div class="posted-by">Photographer at <a href="javascript:();">kinnect 2</a></div>
<div class="post-date"><a href="javascript:();">Anderson</a> is a mutual friend</div>
</div>
<div class="battles-btn">
<a class="btn btn-orange" href="javascript:();"><span class="open-confirm"></span>Confirm</a>
<a class="btn" href="javascript:();"><span class="del-battle"></span>Delete Battle</a>
</div>
</div>

<div class="content-gray-title mb10">
    <h4>People You May Know</h4>
</div>

<div class="browse-battle">
<div class="browse-battle-item">
<div class="btn-del">
 <a href="javascript:();" class="btn-delet"></a>
<a class="browse-battle-img" href="javascript:();">
 <img src="{!! asset('local/public/assets/images/browse-battle.jpg') !!}" alt="image">
</a>
</div>
<div class="battle-item-txt">
 <div class="item-txt-title"><a href="javascript:();">Russel Brown</a></div>
</div>
<div class="item-vote"><a class="btn btn-add-friend" href="javascript:();">Add Friend</a></div>
</div>

<div class="browse-battle-item">
<div class="btn-del">
 <a href="javascript:();" class="btn-delet"></a>
<a class="browse-battle-img" href="javascript:();">
 <img src="{!! asset('local/public/assets/images/browse-battle.jpg') !!}" alt="image">
</a>
</div>
<div class="battle-item-txt">
 <div class="item-txt-title"><a href="javascript:();">Russel Brown</a></div>
</div>
<div class="item-vote"><a class="btn btn-add-friend" href="javascript:();">Add Friend</a></div>
</div>

<div class="browse-battle-item">
<div class="btn-del">
 <a href="javascript:();" class="btn-delet"></a>
<a class="browse-battle-img" href="javascript:();">
 <img src="{!! asset('local/public/assets/images/browse-battle.jpg') !!}" alt="image">
</a>
</div>
<div class="battle-item-txt">
 <div class="item-txt-title"><a href="javascript:();">Russel Brown</a></div>
</div>
<div class="item-vote"><a class="btn btn-add-friend" href="javascript:();">Add Friend</a></div>
</div>

<div class="browse-battle-item">
<div class="btn-del">
 <a href="javascript:();" class="btn-delet"></a>
<a class="browse-battle-img" href="javascript:();">
 <img src="{!! asset('local/public/assets/images/browse-battle.jpg') !!}" alt="image">
</a>
</div>
<div class="battle-item-txt">
 <div class="item-txt-title"><a href="javascript:();">Russel Brown</a></div>
</div>
<div class="item-vote"><a class="btn btn-add-friend" href="javascript:();">Add Friend</a></div>
</div>
</div>
@endsection