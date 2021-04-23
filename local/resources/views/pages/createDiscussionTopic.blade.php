@extends('layouts.default')
@section('content')
<!-- Create Discussion Topic-->
<div class="content-gray-title mb10">
	<h4>Post Discussion Topic</h4>
</div>
<div class="form-container">
 <form action="">

  <div class="field-item">
   <label for="">Title</label>
   <input type="text">
  </div>

  <div class="field-item">
   <label for="">Description</label>
   <textarea name="" id=""></textarea>
  </div>

  <div class="upload-files mt15">
   <div class="upload-btn fltL">
    <span>Uploads : </span>
    <a class="upload-image" href="javascript:();"></a>
    <a class="upload-audio" href="javascript:();"></a>
    <a class="upload-video" href="javascript:();"></a>
   </div>
   <div class="field-item-checkbox fltR">
    <input type="checkbox" id="show-album" />
    <label for="show-album">Send me notifications when other users reply to this topic</label>
   </div>
  </div>

  <div class="save-changes">
   <a href="javascript:();" class="btn">Save Changes</a>
   <a href="javascript:();" class="btn btn-grey ml10">Cancel</a>
  </div>

 </form>
</div>
@endsection