@extends('layouts.default')
@section('content')
<!-- Title Bar -->
<div class="title-bar">
	<span>Create Poll</span>
</div>

<!-- Form Container -->
<div class="form-container">
	<form action="">
		<!-- Form Block -->
		<div class="form-block">
			<label for="">Poll Title</label>
			<input class="form-item" type="text" name="" value="" placeholder="Email">
		</div>
		<!-- Form Block -->
		<div class="form-block">
			<label for="">Question/Statement</label>
			<textarea name="" placeholder="Write questions here..."></textarea>
		</div>
		<!-- Form Block -->
		<div class="form-block">
			<label for="">Poll Between</label>
			<div class="formd-container">				
				<input class="form-item" type="text" name="" value="" placeholder="Option 1">
				<div>
					<a class="btn-formd" href="javascript:void(0)"></a>
				</div>
			</div>
			<div class="formd-container">
				<input class="form-item" type="text" name="" value="" placeholder="Option 2">
				<div>
					<a class="btn-formd" href="javascript:void(0)"></a>
				</div>
			</div>
		</div>
		<div class="btnc-container">
			<a class="btnc" href="javascript:void(0)">Add another option</a>
		</div>
	</form>
</div>

<!-- Title Bar -->
<div class="title-bar">
	<span>Privacy</span>
</div>

<!-- Radio3 Item -->
<div class="radio3-item">
	<div class="radio3-title">
		<span>Poll</span>
	</div>
	<div class="r3i-container">
		<div class="rad3-itm">
			<label class="rad">
				<input type="radio" name="first-name" value="a" />
				<i></i> Everyone
			</label>
		</div>
		<div class="rad3-itm">
			<label class="rad">
				<input type="radio" name="first-name" value="a" />
				<i></i> Friends
			</label>
		</div>
		<div class="rad3-itm">
			<label class="rad">
				<input type="radio" name="first-name" value="a" />
				<i></i> Only Me
			</label>
		</div>
	</div>
	
	<p class="r3help-txt">Who may see this poll?</p>
</div>

<!-- Radio3 Item -->
<div class="radio3-item">
	<div class="radio3-title">
		<span>Comment</span>
	</div>
	<div class="r3i-container">
		<div class="rad3-itm">
			<label class="rad">
				<input type="radio" name="first-name" value="a" />
				<i></i> Everyone
			</label>
		</div>
		<div class="rad3-itm">
			<label class="rad">
				<input type="radio" name="first-name" value="a" />
				<i></i> Friends
			</label>
		</div>
		<div class="rad3-itm">
			<label class="rad">
				<input type="radio" name="first-name" value="a" />
				<i></i> Only Me
			</label>
		</div>
	</div>
	
	<p class="r3help-txt">Who may comment on this poll?</p>
</div>

<!-- Check Box -->
<div class="ckbox-container">
	<div class="ckbox-item">
	    <input type="checkbox" id="ckbox" name="" value="">
	    <label for="ckbox"></label>
	    <span class="ckbx">Show this poll in search results</span>
	</div>
</div>

<!-- btn Container -->
<div class="btn-container">
	<a href="javascript:void(0)" class="btn">Create Poll</a>
	<a href="javascript:void(0)" class="btn btn-grey">Cancel</a>
</div>
@endsection
