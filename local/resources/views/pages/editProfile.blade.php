@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.user-profile-banner')		

<div class="mainCont">
@include('includes.main-left-side')
<div class="profile-content">    
	<div class="edit-profile">
 <div class="edit-profile-block">
  <div class="edit-profile-title">
   <span>Personal Information:</span>
  </div>
  <div class="edit-profile-item">
   <div class="form-label">
    <label for="">First Name*</label>
   </div>
   <input type="text" placeholder="first name">
   <div class="privacy-selector">
    <a href="javascript:();"></a>
   </div>
  </div>
  <div class="edit-profile-item">
   <div class="form-label">
    <label for="">Last Name*</label>
   </div>
   <input type="text" placeholder="last name">
   <div class="privacy-selector">
    <a href="javascript:();"></a>
   </div>
  </div>
  <div class="edit-profile-item">
   <div class="form-label">
    <label for="">Gender*</label>
   </div>
   <div>
    <select name="" id="">
     <option value="">Male</option>
     <option value="">Female</option>
    </select>
   </div>
   <div class="privacy-selector">
    <a href="javascript:();"></a>
   </div>
  </div>
  <div class="edit-profile-item">
   <div class="form-label">
    <label for="">Birthday*</label>
   </div>
   <div>
    {!! Form::selectRange('day', 1, 31) !!}
    {!! Form::selectMonth('month') !!}
    {!! Form::selectRange('year' , 2000, 1900) !!}
   </div>
   <div class="privacy-selector">
    <a href="javascript:();"></a>
   </div>
  </div>
 </div>

 <div class="edit-profile-block">
  <div class="edit-profile-title">
   <span>Contact Information:</span>
  </div>
  <div class="edit-profile-item">
   <div class="form-label">
    <label for="">Select Country*</label>
   </div>
   <select name="" id="">
    <option value="">afghanistan</option>
    <option value="">Pakistan</option>
   </select>
   <div class="privacy-selector">
    <a href="javascript:();"></a>
   </div>
  </div>
  <div class="edit-profile-item">
   <div class="form-label">
    <label for="">Website</label>
   </div>
   <input type="text" placeholder="website">
   <div class="privacy-selector">
    <a href="javascript:();"></a>
   </div>
  </div>
  <div class="edit-profile-item">
   <div class="form-label">
    <label for="">Twitter</label>
   </div>
   <input type="text" placeholder="twitter">
   <div class="privacy-selector">
    <a href="javascript:();"></a>
   </div>
  </div>
  <div class="edit-profile-item">
   <div class="form-label">
    <label for="">Facebook</label>
   </div>
   <input type="text" placeholder="facebook">
   <div class="privacy-selector">
    <a href="javascript:();"></a>
   </div>
  </div>
 </div>

 <div class="edit-profile-block">
  <div class="edit-profile-title">
   <span>Personal Details:</span>
  </div>
  <div class="edit-profile-item">
   <div class="form-label">
    <label for="">About Me</label>
   </div>
   <textarea name="" id="" placeholder="About Me"></textarea>
  </div>
 </div>
</div>
</div>
@include('includes.ads-right-side')
</div>
@endsection
