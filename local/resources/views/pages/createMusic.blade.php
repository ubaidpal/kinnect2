@extends('layouts.default')
@section('content')
<!--Create Music-->
<div class="content-gray-title mb10">
	<h4>My Music</h4>
</div>
<div class="form-container">
 <form action="">

  <div class="field-item">
   <label for="">Add New Songs</label>
   <p>Choose music from your computer to add to this playlist.</p>
  </div>

  <div class="field-item">
   <label for="">Playlist Name</label>
   <input type="text" placeholder="Playlist Name">
  </div>

  <div class="field-item">
   <label for="">Playlist Description</label>
   <textarea name="" id="" placeholder="Write description here"></textarea>
  </div>

  <div class="field-item-checkbox mt20">
   <input type="checkbox" id="show-album" />
   <label for="show-album">Show this poll in search results</label>
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
   <label for="">Playlist Artwork</label>
  </div>
  <div class="upload-photo">
   <a href="javascript:();" class="btn">Choose File</a>
   <span>No file choosen</span>
  </div>

  <p class="form-detail">
   Click "Add Music" to select one or more songs from your computer. After you have selected the songs, they will begin to upload right away. When your upload is finished, click the button below the song list to save them to your playlist.
  </p>

  <div class="save-changes">
   <a href="javascript:();" class="btn">Add Music</a>
   <a href="javascript:();" class="btn btn-grey ml10">Clear List</a>
  </div>

 </form>
</div>
@endsection