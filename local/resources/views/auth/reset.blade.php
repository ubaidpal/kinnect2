@extends('layouts.login')
@section('content')
	<!--  Login Container  -->
	<div class="login-container fltR">
		<table width="100%" class="login-form">
			<tbody><tr>
				<td>
                	<h2 class="account-title">{{ Lang::get('titles.resetPword') }}</h2>
                    <p class="description">
                    	Password should be a minimum of 7 characters, and must consist of at least:                         
                    </p>
                    <ul class="password_restriction">
                        <li>
                        - 1 Special Character 
                        </li>
                        <li>
                        - 1 Alphanumeric
                        </li>
                    </ul>
					
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
					
					<form class="form-horizontal reset_password" role="form" method="POST" action="{{ url('/password/reset') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="token" value="{{ $token }}">

						<div class="form-group">
							<label class="control-label">{{ Lang::get('auth.email') }}</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label">{{ Lang::get('auth.password') }}</label>
							<div class="">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label">{{ Lang::get('auth.confirmPassword') }}</label>
							<div class="">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

						<div class="form-group">
							<div class="">
								<button type="submit" class="orngBtn btn-primary">
									{{ Lang::get('auth.resetPassword') }}
								</button>
							</div>
						</div>
					</form>
				</td>
			</tr>
		</tbody></table>
	</div>
	<!--  Login Container - Ends -->

<!--<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">{{ Lang::get('titles.resetPword') }}</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>{{ Lang::get('auth.whoops') }}</strong> {{ Lang::get('auth.someProblems') }}<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="token" value="{{ $token }}">

						<div class="form-group">
							<label class="col-md-4 control-label">{{ Lang::get('auth.email') }}</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">{{ Lang::get('auth.password') }}</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">{{ Lang::get('auth.confirmPassword') }}</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									{{ Lang::get('auth.resetPassword') }}
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>-->
@endsection
