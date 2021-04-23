@extends('layouts.masterDynamic')
@section('content')
@include('includes.event-left-nav')
<div class="content">
<div class="content-gray-title mb10">
    <h4>Event Detail</h4>
    <a href="javascript:();" title="Create Battel" class="btn fltR">Photos (1)</a>
    <a href="javascript:();" title="Create Battel" class="btn fltR mr10">Guests (5)</a>
    <input type="text" class="searchGuest" placeholder="Search Guests" />
</div>
    <!-- Post Div-->
<div class="browse-battle">
<div class="browse-battle-item">
    <a href="javascript:();" class="browse-battle-img">
     <img alt="image" src="{!! asset('local/public/assets/images/browse-battle.jpg') !!}">
    </a>
    <div class="battle-item-txt">
     <div class="item-txt-title"><a href="#">Zahid Khurshid</a></div>
     <div class="item-txt-post">Attending</div>
    </div>
</div>
<div class="browse-battle-item">
    <a href="javascript:();" class="browse-battle-img">
     <img alt="image" src="{!! asset('local/public/assets/images/browse-battle.jpg') !!}">
    </a>
    <div class="battle-item-txt">
     <div class="item-txt-title"><a href="#">Zahid Khurshid</a></div>
     <div class="item-txt-post">Attending</div>
    </div>
</div>
<div class="browse-battle-item">
    <a href="javascript:();" class="browse-battle-img">
     <img alt="image" src="{!! asset('local/public/assets/images/browse-battle.jpg') !!}">
    </a>
    <div class="battle-item-txt">
     <div class="item-txt-title"><a href="#">Zahid Khurshid</a></div>
     <div class="item-txt-post">Attending</div>
    </div>
</div>
<div class="browse-battle-item">
    <a href="javascript:();" class="browse-battle-img">
     <img alt="image" src="{!! asset('local/public/assets/images/browse-battle.jpg') !!}">
    </a>
    <div class="battle-item-txt">
     <div class="item-txt-title"><a href="#">Zahid Khurshid</a></div>
     <div class="item-txt-post">Attending</div>
    </div>
</div>

</div>    
</div>
@include('includes.ads-right-side')
    
@endsection