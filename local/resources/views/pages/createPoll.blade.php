@extends('layouts.default')
@section('content')
<!--Create Poll-->
    <div class="content-gray-title mb10">
        <h4>Create Poll</h4>
    </div>
     <div class="form-container">
 <form action="">

  <div class="field-item">
   <label for="">Poll Title</label>
   <input type="text" placeholder="Title">
  </div>

  <div class="field-item">
   <label for="">Question &sol; Statement</label>
   <textarea name="" id="" placeholder="Write questions here"></textarea>
  </div>

  <div class="field-item">
   <label for="">Poll Between</label>
   <input type="text" placeholder="Option 1" class="fltL">
   <input type="text" placeholder="Option 2" class="fltR">
  </div>
  
  <div class="upload-photo clrfix mt15">
   <a href="javascript:();" class="btn btn-add"> Add</a>
  </div>

  <div class="field-item">
   <label for="">Privacy</label>
   <select name="" id="">
    <option value="">Who may see this poll?</option>
    <option value="">Album - 1</option>
    <option value="">Album - 2</option>
   </select>
  </div>

  <div class="field-item">
   <label for="">Comment Privacy</label>
   <select name="" id="">
    <option value="">Who may post comments on this poll?</option>
    <option value="">Album - 1</option>
    <option value="">Album - 2</option>
   </select>
  </div>

  <div class="field-item-checkbox mt20">
   <input type="checkbox" id="show-album" />
   <label for="show-album">Show this poll in search results</label>
  </div>

  <div class="save-changes">
   <a href="javascript:();" class="btn">Create Poll</a>
   <a href="javascript:();" class="btn btn-grey ml10">Cancel</a>
  </div>

 </form>
</div>
@endsection