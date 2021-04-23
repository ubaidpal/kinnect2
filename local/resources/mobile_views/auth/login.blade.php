@extends('layouts.signup')
@section('content')
    <div id="viewPort" class="mob-container signin">
        <!-- Header Container -->
        <div class="header-container">
            <div class="logo-block">
                <h1>
                    <a href="javascript:void(0)">
                        <img class="k2-logo" src="{!! asset('local/public/assets/images_mobile/k2-logo.png') !!}"
                             alt="logo_k2">
                        <u>Kinnect2 logo</u>
                    </a>
                </h1>
            </div>
        </div>
        <!-- SignIn Text -->
        <div class="signin-txt">
            <h2>Sign In</h2>
        </div>
        <!-- Form Container -->
        {!! Form::open(['url'=> '/auth/login']) !!}
        <div class="form-container">
            @if(!isset($signup))
                @if(!is_null($errors->first('email')))
                    <div class="alert alert-danger" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>

                        {{$errors->first('email')}}
                    </div>
                @endif
                    @if(!is_null($errors->first('password')))
                        <div class="alert alert-danger" role="alert">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            {{$errors->first('password')}}
                        </div>
                    @endif

            @endif
            <div class="form-block">
                <input class="form-item p-icon" type="email" name="email" value="{{ old('email') }}"
                       placeholder="Email">
                <span class="email-icon"></span>
            </div>
            <div class="form-block">
                <input class="form-item p-icon" type="password" name="password" value="" placeholder="Password">
                <span class="password-icon"></span>
            </div>

        </div>

        <!-- Forgot btn -->
        <div class="frgt-pass mb20">
            <a href="{{ url('/password/email') }}">Forgot your password?</a>
        </div>

        <!-- SignIn btn -->
        <div class="btn-signin">
            <input type="submit" class="btn" value="Sign In">
        </div>

        <!-- SignUp btn -->
        <div class="btn-signin">
            {!! HTML::link('auth/register','Sign Up',['class' => 'btn btn-signup']) !!}
        </div>
        {!! Form::close() !!}
        <div class="sign-terms-privacy">
            <ul class="breadcrumb">
                <li><a href="javascript:void(0)">About</a></li>
                <li><a href="javascript:void(0)">Terms</a></li>
                <li><a href="javascript:void(0)">Privacy</a></li>
            </ul>
        </div>

    </div>
@endsection
