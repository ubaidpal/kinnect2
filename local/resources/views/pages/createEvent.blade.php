@extends('layouts.default')
@section('content')
<!-- Create Event-->
<div class="content-gray-title mb10">
	<h4>New Event</h4>
</div>
<div class="form-container">
 <form action="">
  <div class="field-item">
   <label for="">Event Name</label>
   <input type="text" placeholder="Event Name">
  </div>

  <div class="field-item">
   <label for="">Event Description</label>
   <textarea name="" id=""></textarea>
  </div>

  <div class="field-item calendar">
   <label for="">Start Time</label>
   <div class="select-date">
    <span>Select a Date</span>
    <a class="btn-calendar" href="javascript:();"></a>
   </div>
   <div class="select-time">
    <span>Select Time</span>
    <select name="" id="">
     <option value="">1</option>
     <option value="">2</option>
     <option value="">3</option>
    </select>
    <select name="" id="">
     <option value="">00</option>
     <option value="">01</option>
     <option value="">02</option>
    </select>
    <select name="" id="">
     <option value="">AM</option>
     <option value="">PM</option>
    </select>
   </div>
  </div>

  <div class="field-item calendar">
   <label for="">Start Time</label>
   <div class="select-date">
    <span>Select a Date</span>
    <a class="btn-calendar" href="javascript:();"></a>
   </div>
   <div class="select-time">
    <span>Select Time</span>
    <select name="" id="">
     <option value="">1</option>
     <option value="">2</option>
     <option value="">3</option>
    </select>
    <select name="" id="">
     <option value="">00</option>
     <option value="">01</option>
     <option value="">02</option>
    </select>
    <select name="" id="">
     <option value="">AM</option>
     <option value="">PM</option>
    </select>
   </div>
  </div>

  <div class="field-item">
   <label for="">Host</label>
   <input type="text">
  </div>

  <div class="field-item">
   <label for="">Lcation</label>
   <input type="text">
  </div>

  <div class="field-item">
   <label for="">Main Photo</label>
  </div>
  <div class="upload-photo">
   <a href="javascript:();" class="btn">Choose File</a>
   <span>No file choosen</span>
  </div>

  <div class="field-item">
   <label for="">Event Category</label>
   <select name="" id="">
    <option value="">Select</option>
    <option value="">Option - 1</option>
    <option value="">Option - 2</option>
   </select>
  </div>

  <div class="field-item-checkbox mt20">
   <input type="checkbox" id="show-album" />
   <label for="show-album">People can search for this event</label>
  </div>
  <div class="field-item-checkbox mt10">
   <input type="checkbox" id="people" />
   <label for="people">People must be invited to RSVP for this event</label>
  </div>
  <div class="field-item-checkbox mt10">
   <input type="checkbox" id="invited-guest" />
   <label for="invited-guest">Invited guests can invite other people as well</label>
  </div>

  <div class="field-item">
   <label for="">Privacy</label>
   <select name="" id="">
    <option value="">Who may see this playlist?</option>
    <option value="">Album - 1</option>
    <option value="">Album - 2</option>
   </select>
  </div>

  <div class="field-item">
   <label for="">Comment Privacy</label>
   <select name="" id="">
    <option value="">Who may post comments on this playlist?</option>
    <option value="">Album - 1</option>
    <option value="">Album - 2</option>
   </select>
  </div>

  <div class="field-item">
   <label for="">Photo Uploads</label>
   <select name="" id="">
    <option value="">Who may upload photos to this event?</option>
    <option value="">Album - 1</option>
    <option value="">Album - 2</option>
   </select>
  </div>

  <div class="save-changes">
   <a href="javascript:();" class="btn">Save Changes</a>
   <a href="javascript:();" class="btn btn-grey ml10">Cancel</a>
  </div>



 </form>
</div>
@endsection