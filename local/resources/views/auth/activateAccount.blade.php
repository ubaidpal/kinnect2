@extends('layouts.login')
@section('content')
	<!--  Login Container  -->
	<div class="login-container fltR">
		<table class="login-form" width="100%">
			<tr>
				<td>
                	<h2 class="account-title">Resend Email</h2>
                    <p class="description">
                        We have sent an email to {{$email}}, Please click the link in it to activate your account.
                    </p>
				</td>
			</tr>
		</table>
	</div>
	<!--  Login Container - Ends -->
@endsection
