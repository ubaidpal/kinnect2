@extends('layouts.login')
@section('content')

    <!--  Login Container  -->
    <div class="login-container fltR">
            <table class="login-form" width="100%">
                @if( !empty($expired_token) )
                    <tr>
                        <th>Your token is expired so click Here to resend it.</th>
                    </tr>
                    <tr>
                    	<td><a href='resendEmail'>{{ Lang::get('auth.clickHereResend') }}</a></td>
                    </tr>
                @else
                    @if(empty($deleted) )
                        <tr>
                            <th>An email was sent to {{ $email }} on {{ $date }}.</th>
                        </tr>
                        <tr>
                            <td style="text-align:center">{{ Lang::get('auth.clickInEmail') }}</td>
                        </tr>
                        <tr>
                            <td>
                                <a href='{{url('resendEmail')}}' class="btn ml0">{{ Lang::get('auth.clickHereResend') }}</a>
                                <a href='{{url('logout')}}' class="btn ml0">Go Home</a>
                            </td>
                        </tr>
                    @endif

                        @if(! empty($deleted) )
                            <tr>
                                <th>Your account was de-activated by you on {{ $date }}.</th>
                            </tr>
                            <tr>
                                <td style="text-align:center">You can activate your account by receiving activation code.</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href='{{url('resendEmail')}}' class="btn ml0">Click here to send activation code to your email.</a>
                                    <a href='{{url('logout')}}' class="btn ml0">Go Home</a>
                                </td>
                            </tr>
                        @endif
                @endif
            </table>
            <!-- Resend Email Html
            <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
            	<td>
                	@if (session('status'))
                    <div class="alert alert-success">
                     {{ session('status') }}
                    </div>
                   @endif

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

                   <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <label class="eTitle">{{ Lang::get('auth.email') }}</label>
                    <div class="fltL">
                     <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                    </div>
                	<div class="clrfix"></div>
                    <div class="fltR mt10">
                     <button type="submit" class="orngBtn">
                      {{ Lang::get('auth.sendResetLink') }}
                     </button>
                    </div>
                   </form>
                </td>
            </tr>
            </table>
            -->
    </div>
    <!--  Login Container - Ends -->

@endsection
