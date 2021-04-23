@extends('layouts.default')
@section('content')
<!-- Create Event-->
<div class="content-gray-title mb10">
	<h4>Create Battle</h4>
    <a href="javascript:();" class="btn fltR" title="Browse">Browse Battles</a>
</div>
<div class="form-container">
 <form action="">
  <div class="field-item">
   <label for="">Battle Title</label>
   <input type="text" placeholder="Battle Title">
  </div>

  <div class="field-item">
   <label for="">Battle Description</label>
   <textarea name="" id="" placeholder="Write Detail here..."></textarea>
  </div>

  <div class="field-item">
   <label for="">Battle Between</label>
   <input type="text" placeholder="Brand 1">
   <b>VS</b> 
   <input type="text" placeholder="Brand 2">
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
  
   <div class="field-item-checkbox mt20">
   <input type="checkbox" id="invited-guest" />
   <label for="invited-guest">Show this battle in search results</label>
  </div>

  <div class="save-changes">
   <a href="javascript:();" class="btn">Create Battle</a>
   <a href="javascript:();" class="btn btn-grey ml10">Cancel</a>
  </div>



 </form>
</div>
@endsection