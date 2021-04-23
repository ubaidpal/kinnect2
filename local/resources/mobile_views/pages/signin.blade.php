@extends('layouts.signup')
@section('content')
    <title>Login - Kinnect2</title>
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
        <div class="form-container">
            <form action="">
                <div class="form-block">
                    <input class="form-item p-icon" type="text" name="" value="" placeholder="Email">
                    <span class="email-icon"></span>
                </div>
                <div class="form-block">
                    <input class="form-item p-icon" type="password" name="" value="" placeholder="Password">
                    <span class="email-icon"></span>
                </div>
            </form>
        </div>

        <!-- Forgot btn -->
        <div class="frgt-pass mb20">
            <a href="javascript:void(0)">Forgot your password?</a>
        </div>

        <!-- SignIn btn -->
        <div class="btn-signin">
            <a class="btn" href="javascript:void(0)">Sign In</a>
        </div>

        <!-- SignUp btn -->
        <div class="btn-signin">
            <a class="btn btn-signup" href="javascript:void(0)">Sign Up</a>
        </div>

        <div class="sign-terms-privacy">
            <ul class="breadcrumb">
                <li><a href="javascript:void(0)">About</a></li>
                <li><a href="javascript:void(0)">Terms</a></li>
                <li><a href="javascript:void(0)">Privacy</a></li>
            </ul>
        </div>

    </div>
@endsection
