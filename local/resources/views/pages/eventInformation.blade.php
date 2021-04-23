@extends('layouts.masterDynamic')
@section('content')
@include('includes.event-left-nav')
<div class="content">
<div class="content-gray-title mb10">
    <h4>Event Detail</h4>
    <a href="javascript:();" title="Create Battel" class="btn fltR">Event Information</a>
    <a href="javascript:();" title="Create Battel" class="btn fltR mr10">Photos (1)</a>
    <a href="javascript:();" title="Create Battel" class="btn fltR mr10">Guests (5)</a>
    <input type="text" class="searchGuest" placeholder="Search Guests" />
</div>
    <!-- Post Div-->
<div class="content-content">
   <div class="details">
   <h4>Event Details</h4>
   <p>
    I am going to perform live in &quot;Jai Pur&quot;, are you ready to Jhoom? I hope you are ready :( You missed it.
   </p>



   <div class="details-list">
    <div class="detail-item">
     <div class="dtl-item">
      <span>Date&colon;</span>
     </div>
     <div class="dtl-value">
      <span>3&sol;31&sol;15</span>
     </div>
    </div>

    <div class="detail-item">
     <div class="dtl-item">
      <span>Time&colon;</span>
     </div>
     <div class="dtl-value">
      <span>10&colon;30 PM - 12&colon;30 PM</span>
     </div>
    </div>

    <div class="detail-item">
     <div class="dtl-item">
      <span>Venue&colon;</span>
     </div>
     <div class="dtl-value">
      <span>Jai Pur Cricket Stadium, Link Road India. <a href="javascript:();">Map</a></span>
     </div>
    </div>

    <div class="detail-item">
     <div class="dtl-item">
      <span>Host&colon;</span>
     </div>
     <div class="dtl-value">
      <span>Javeria Abbasi</span>
     </div>
    </div>

    <div class="detail-item">
     <div class="dtl-item">
      <span>Led By&colon;</span>
     </div>
     <div class="dtl-value">
      <span><a href="javascript:();">Ali Zafar</a></span>
     </div>
    </div>

    <div class="detail-item">
     <div class="dtl-item">
      <span>Category&colon;</span>
     </div>
     <div class="dtl-value">
      <span><a href="javascript:();">Just for Fun</a></span>
     </div>
    </div>

    <div class="detail-item">
     <div class="dtl-item">
      <span>RSVPs&colon;</span>
     </div>
     <div class="dtl-value">
      <span class="mb5"><a href="javascript:();">2</a> Attending</span>
      <span class="mb5"><a href="javascript:();">0</a> May be attending</span>
      <span class="mb5"><a href="javascript:();">0</a> Not attending</span>
      <span class="mb5"><a href="javascript:();">0</a> Awaiting reply</span>
     </div>
    </div>


   </div>


  </div>
 </div>    
</div>
@include('includes.ads-right-side')
    
@endsection