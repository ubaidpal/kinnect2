@extends('layouts.signup')
@section('content')
<div id="viewPort" class="mob-container signin">
    <!-- Header Container -->
    <div class="header-container">
        <div class="logo-block">
            <h1>
                <a href="javascript:void(0)">
                <img class="k2-logo" src="{!! asset('local/public/assets/images_mobile/k2-logo.png') !!}" alt="logo_k2">
                <u>Kinnect2 logo</u>
                </a>
            </h1>
        </div>
    </div>
    
   <!-- SignUp Text -->
	<div class="signin-txt">
		<h2>Sign Up</h2>
	</div>

	<!-- Form Container -->
	<div class="form-container">
		<form action="">
			<div class="form-block">
				<input class="form-item" type="text" name="" value="" placeholder="First Name">
			</div>

			<div class="form-block">
				<input class="form-item" type="text" name="" value="" placeholder="Last Name">
			</div>

			<div class="form-block">
				<input class="form-item" type="text" name="" value="" placeholder="Brand Name">
			</div>

			<div class="form-block">
				<select id="">
					<option>Select Industry</option>
					<option>2</option>
					<option>3</option>
				</select>
			</div>

			<div class="form-block">
				<select id="">
					<option>Select Country</option>
					<option>2</option>
					<option>3</option>
				</select>
			</div>
		</form>
	</div>

	<div class="btn-signup-container mb20 mt5">
		<a class="btn fL" href="javascript:void(0)">Next</a>
		<a class="btn btn-grey fR" href="javascript:void(0)">Cancel</a>
	</div>

</div>
@endsection
