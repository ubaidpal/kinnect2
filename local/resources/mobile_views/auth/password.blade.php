@extends('layouts.signup')
@section('content')
    <div class="hd-txt">Lost Password</div>
    <p class="p-txt">If you cannot login because you have forgotten your password, please enter your email address in
                     the field below.</p>

    @if (session('status'))
        <p class="description">
            {{ session('status') }}
        </p>
        @endif
                <!-- Form Container -->
        {!! Form::open(['url' => '/password/email']) !!}
        <div class="form-container">

            <!-- Form Block -->
            <div class="form-block">
                <label for="">{{ Lang::get('auth.email') }} *</label>
                <input class="form-item" type="email" name="email" value="{{ old('email') }}"
                       placeholder="{{ Lang::get('auth.email') }}">
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

        </div>

        <!-- btn Container -->
        <div class="btn-container">
            <button type="submit" class="btn">{{ Lang::get('auth.sendResetLink') }}</button>
            <a href="javascript:void(0)" class="btn btn-grey">Cancel</a>
        </div>
        {!! Form::close() !!}
@endsection

