@extends('layouts.default')
@section('content')
<!--Create Album-->
<div class="content-gray-title mb10">
    <h4>Create New Group</h4>
    <a href="javascript:();" title="Browse" class="btn fltR">Manage Groups</a>
</div>
 <div class="form-container">
<form action="">


<div class="field-item">
 <label for="">Group Title</label>
 <input type="text" placeholder="Group Title">
</div>

<div class="field-item">
 <label for="">Description</label>
 <textarea name="" id="" placeholder="Write detail here..."></textarea>
</div>

<div class="upload-photo mt20">
	<a href="javascript:();" class="btn">Upload Photo</a>
	<span>No Photos Selected Yet</span>
</div>

<div class="field-item">
 <label for="">Category</label>
 <select name="" id="">
  <option value="">choose one category?</option>
  <option value="">Group - 1</option>
  <option value="">Group - 2</option>
  <option value="">Group - 3</option>
 </select>
</div>
<div class="mt20">
	<div>
    	<input type="radio" name="search" id="" value="0">
    	<label for="search-0">Yes, include in search results.</label>
    </div>
    <div class="mt5">
    	<input type="radio" name="search" id="" value="0">
    	<label for="search-0">No, hide from search results.</label>
    </div>
</div>

<div class="mt20">
	<div>
    	<input type="radio" name="member" id="" value="0">
    	<label for="search-0">Yes, members can invite other people.</label>
    </div>
    <div class="mt5">
    	<input type="radio" name="member" id="" value="0">
    	<label for="search-0">No, only officers can invite other people.</label>
    </div>
</div>

<p>When people try to join this group, should they be allowed to join immediately, or should they be forced to wait for approval?</p>

<div class="mt20">
	<div>
    	<input type="radio" name="new" id="" value="0">
    	<label for="search-0">New members can join immediately.</label>
    </div>
    <div class="mt5">
    	<input type="radio" name="new" id="" value="0">
    	<label for="search-0">New member must be approved</label>
    </div>
</div>

<div class="field-item">
 <label for="">Privacy</label>
 <select name="" id="">
  <option value="">Who may see this group?</option>
  <option value="">Group - 1</option>
  <option value="">Group - 2</option>
 </select>
</div>

<div class="field-item">
 <label for="">Comment Privacy</label>
 <select name="" id="">
  <option value="">Who may post comments on this Group?</option>
  <option value="">Group - 1</option>
  <option value="">Group - 2</option>
 </select>	
</div>

<div class="field-item">
 <label for="">Photo Uploads</label>
 <select name="" id="">
  <option value="">Who may upload photo to this Group?</option>
  <option value="">Registered Members</option>
  <option value="">All group memebers</option>
  <option value="">Officers and Owner Only</option>
 </select>
</div>

<div class="field-item">
 <label for="">Event Creation</label>
 <select name="" id="">
  <option value="">Who may create events for this Group?</option>
  <option value="">Registered Members</option>
  <option value="">All group memebers</option>
  <option value="">Officers and Owner Only</option>
 </select>
</div>

<div class="save-changes">
 <a href="javascript:();" class="btn">Save Changes</a>
</div>
</form>
</div>
@endsection