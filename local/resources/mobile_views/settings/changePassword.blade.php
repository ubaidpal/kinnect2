@extends('layouts.masterDynamic')
@section('content')
@include('includes.setting-left-nav')
        <!--Create Album-->
<div class="community-ad">
    <div class="form-container settings">
        <form  role="form" method="POST" action="password_change" >

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="setting-title">
                <span>Change Password</span>
            </div>

            <div class="field-item">
                <label for="">Old Password</label>
                <input type="password" id="old_password" name="old_password" placeholder="current password"  required class="form-control" value="">
            </div>

            <div class="field-item">
                <label for="">New Password</label>
                <p class="col-dark mb10">
                    Passwords must be at least 7 characters in length.
                </p>
                <input type="password" id="password" name="password" placeholder="new password" required class="form-control" value="">
            </div>

            <div class="field-item">
                <label for="">Re-Enter New Password</label>
                <p class="col-dark mb10">
                    Enter your password again for confirmation.
                </p>
                <input type="password" id="conformed_password" name="conformed_password" required placeholder="Re-enter password" class="form-control" value="">

            </div>


            <div class="save-changes">
                <input type="submit" class="btn" name="submit" value="Reset Password">
                <input type="hidden" name="_token" value="{{Session::token()}}">
            </div>

        </form>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <span style="color: #ff0000;">
                        <li>{{ $error }}</li>
                        </span>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
@endsection
