@extends('layouts.login')
@section('content')
	<!--  Login Container  -->
	<div class="login-container fltR">
		<table class="login-form" width="100%">
			<tr>
				<td>
                	<h2 class="account-title">Forgot Password</h2>
                    <p class="description">If you cannot login because you have forgotten your password, please enter your email address in the field below.</p>
					@if (session('status'))
						<p class="description">
							{{ session('status') }}
						</p>
					@endif

					
					
					<form class="form-horizontal mb10 mt10" role="form" method="POST" action="{{ url('/password/email') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<label class="email-label">{{ Lang::get('auth.email') }}</label>
						<div class="forgot_password">
							<input type="email" class="form-control m0" name="email" value="{{ old('email') }}" placeholder="Please enter valid email address...">
						</div>
						@if (count($errors) > 0)
						<div class="alert alert-danger emailError">
							<!--<strong>{{ Lang::get('auth.whoops') }}</strong> {{ Lang::get('auth.someProblems') }}<br><br>-->
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
						@endif
						<div class="forgot_password">
							<button type="submit" class="orngBtn">
								{{ Lang::get('auth.sendResetLink') }}
							</button>
						</div>
					</form>
				</td>
			</tr>
		</table>
	</div>
	<!--  Login Container - Ends -->
@endsection
