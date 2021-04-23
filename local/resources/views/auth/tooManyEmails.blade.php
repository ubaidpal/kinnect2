@extends('layouts.login')
@section('content')
	<!--  Login Container  -->
	<div class="login-container fltR">
    <table class="login-form" width="100%">
        <tr>
            <td>
                <h2 class="account-title">Too many emails sent.</h2>
                <p class="description">
                    We have sent too many emails to {{$email}}, Please click the link in it to activate your account.
                </p>
            </td>
        </tr>
    </table>
</div>
	<!--  Login Container - Ends -->

@endsection

