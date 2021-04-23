@extends('layouts.signup')
@section('content')
    <div class="hd-txt">{{ Lang::get('titles.resetPword') }}</div>
    <p class="p-txt">
        Password should be a minimum of 7 characters, and must consist of at least
    </p>
    <p class="help-txt">- 1 Special Character</p>
    <p class="help-txt">- 1 Alphanumeric</p>
    <!-- Form Container -->
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
    {!! Form::open(['url' => 'password/reset']) !!}
    <div class="form-container mt15">

            <input type="hidden" name="token" value="{{ $token }}">
            <!-- Form Block -->
            <div class="form-block">
                <label for="">{{ Lang::get('auth.email') }} *</label>
                <input class="form-item" type="email" name="email" value="{{ old('email') }}" placeholder="{{ Lang::get('auth.email') }}">
            </div>
            <!-- Form Block -->
            <div class="form-block">
                <label for="">{{ Lang::get('auth.password') }} *</label>
                <input class="form-item" type="password" name="password" value="" placeholder="{{ Lang::get('auth.password') }}">
            </div>
            <!-- Form Block -->
            <div class="form-block">
                <label for="">{{ Lang::get('auth.confirmPassword') }} *</label>
                <input class="form-item" type="password" name="password_confirmation" value="" placeholder="{{ Lang::get('auth.confirmPassword') }}">
            </div>

    </div>

    <!-- btn Container -->
    <div class="btnSingle">
        <button type="submit" class="btn">{{ Lang::get('auth.resetPassword') }}</button>
    </div>
    {!! Form::close() !!}
@endsection
