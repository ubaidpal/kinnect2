@extends('layouts.default')
@section('content')
<!--Create Album-->
<div class="content-gray-title mb10">
    <h4>Manage Photos</h4>
    <a href="javascript:();" title="Browse" class="btn fltR">Add New Photos</a>
    <a href="javascript:();" title="Create Battel" class="btn fltR mr10">My Albums</a>
</div>
 <div class="form-container">
<form action="">
<p>Choose photos on your computer to add to this album.</p>

<div class="field-item">
 <label for="">Choose Album</label>
 <select name="" id="">
  <option value="">Create a New Album</option>
  <option value="">Album - 1</option>
  <option value="">Album - 2</option>
 </select>
</div>

<div class="field-item">
 <label for="">Album Title</label>
 <input type="text">
</div>

<div class="field-item">
 <label for="">Category</label>
 <select name="" id="">
  <option value="">All Categories</option>
  <option value="">Category - 1</option>
  <option value="">Category - 2</option>
 </select>
</div>

<div class="field-item">
 <label for="">Category</label>
 <textarea name="" id=""></textarea>
</div>


<div class="field-item-checkbox mt20">
 <input type="checkbox" id="show-album" />
 <label for="show-album">Show this album in search results</label>
</div>

<div class="field-item">
 <label for="">Privacy</label>
 <select name="" id="">
  <option value="">Who may see this album?</option>
  <option value="">Album - 1</option>
  <option value="">Album - 2</option>
 </select>
</div>

<div class="field-item">
 <label for="">Comment Privacy</label>
 <select name="" id="">
  <option value="">Who may post comments on this album?</option>
  <option value="">Album - 1</option>
  <option value="">Album - 2</option>
 </select>
</div>

<div class="field-item">
 <label for="">Tagging</label>
 <select name="" id="">
  <option value="">Who may tag photos in this album?</option>
  <option value="">Album - 1</option>
  <option value="">Album - 2</option>
 </select>
</div>

<p class="form-detail">
 Click "Add Photos" to select one or more photos from your computer. After you have selected the photos, they will begin to upload right away. When your upload is finished, click the button below your photo list to save them to your album.
</p>

<div class="upload-photo">
 <a href="javascript:();" class="btn">Upload Photo</a>
 <span>No Photos Selected Yet</span>
</div>

<div class="save-changes">
 <a href="javascript:();" class="btn">Save Changes</a>
</div>


</form>
</div>
@endsection