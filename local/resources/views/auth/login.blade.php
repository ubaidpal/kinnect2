@extends('layouts.login')
@section('content')

<?php 
$parts = explode("/", $_SERVER['REQUEST_URI']);
$cls = "";
$lastParam = end($parts);
if($lastParam == "ads" || $lastParam == "createPage"){
	$cls = "center-align";
}
?>
<!--  Login Container  -->
	<div class="login-container fltR <?php echo $cls?>">
		<!--  Login Form  -->
		<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<table class="login-form" width="100%">

				<tr>
					<td width="180"><input type="text" placeholder="{{trans('auth.email')}}" name="email" value="{{ old('email') }}"></td>
					<td width="180"><input type="password" name="password" placeholder="{{trans('auth.password')}}"></td>
					<td><input type="submit" name="btn" class="inputBtn ml10 p5" value="{{trans('auth.login')}}" /></td>
				</tr>
					<tr>
						<th colspan="3" class="login-error">
							@if (Session::has('info'))
									{{Session::get('info')}}
							@endif

							@if(!isset($signup))
								<?php echo $errors->first('email'); ?>
								<?php echo $errors->first('password'); ?>
							@endif
						</th>
					</tr>
				<tr>
					<td>
						<input type="checkbox" id="remember-me" name="remember">
						<label for="remember-me">{{trans('auth.rememberMe')}}</label>
					</td>
					<td colspan="2">
						<a class="forgot-pass" href="{{ url('/password/email') }}">{{trans('auth.forgot')}}</a>
					</td>
				</tr>
			</table>
		</form>
		<!--  Login Form - Ends -->

		<!--  SignUp Container  -->
		<div class="signup-container">
			<div class="signup-header">
				<span>{{trans('auth.new')}}</span>
				<h2>{{trans('auth.signup')}}</h2>
				<span>{{trans('auth.free')}}</span>
			</div>

				<!--  SignUp Form  -->
			<div class="signup-form">
				<div id="steps">
					@include('auth.partials.step_one')
				</div>
			</div>
			<!--  SignUp Form - Ends  -->
		</div>
		<!--  SignUp Container - Ends -->
	</div>
<!--  Login Page Content  -->

@endsection
