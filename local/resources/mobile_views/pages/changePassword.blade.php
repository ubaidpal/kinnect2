@extends('layouts.default')
@section('content')
<div id="viewPort" class="mob-container signin">
   
<!-- Title Bar -->
<div class="title-bar">
	<span>Change Password</span>
</div>

<!-- Form Container -->
<div class="form-container">
	<form action="">
		<!-- Form Block -->
		<div class="form-block">
			<label for="">Old Password</label>
			<input class="form-item" type="password" name="" value="" placeholder="Password">
		</div>
		<!-- Form Block -->
		<div class="form-block">
			<label for="">New Password</label>
			<input class="form-item" type="password" name="" value="" placeholder="Password">
			<div class="help-block">
				<div>Password should be a minimum of 7 characters, and must consist of at least</div>
				<div>-1 Special Character</div>
				<div>-1 Alphanumeric</div>
			</div>
		</div>
		<!-- Form Block -->
		<div class="form-block">
			<label for="">New Password (again)</label>
			<input class="form-item" type="password" name="" value="" placeholder="Password">
			<div class="help-block">
				<div>Enter your password again for confirmation.</div>
			</div>
		</div>
	</form>
</div>

<!-- btn Container -->
<div class="btn-container">
	<a href="javascript:void(0)" class="btn">Save</a>
	<a href="javascript:void(0)" class="btn btn-grey">Cancel</a>
</div>

</div>
@endsection
