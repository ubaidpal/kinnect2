@extends('admin.layout.store-admin')
@section('content')
        <!-- Post Div-->
@include('admin.layout.arbitrator-leftnav')
<div class="ad_main_wrapper">
    <div class="task_inner_wrapper">
        <div class="main_heading">
            <h1>Update Password</h1>
        </div>
        @include('admin.alert.alert')
        <div class="assigned-task-wrapper">
            {!! Form::model(null , ['method' => 'PATCH', 'url' => "admin/changePassword/update/".Auth::user()->id, "enctype"=>"multipart/form-data"]) !!}
            @if (count($errors) > 0)
                <div class="alert alert-danger" style="width: 300px;margin: auto;margin-top: 8px;margin-left: 220px;line-height: 20px;
                color: #ff0000;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <span>
                        <li>{{ $error }}</li>
                        </span>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="add-form-block">
                <div class="user-title">Old Password * :</div>
                <div class="user-input"><input type="password" name="old_password" placeholder="old password"></div>
                <br/>
            </div>
            <div class="add-form-block">
                <div class="user-title">Password * :</div>
                <div class="user-input"><input type="password" name="password" placeholder="new password format e.g.Kinnect2#"></div>
                <br/>
            </div>
            <div class="add-form-block">
                <div class="user-title">Retype Password * :</div>
                <div class="user-input"><input type="password" name="retype_password" placeholder="retype password"></div>
                <br/>
            </div>

            <div class="add-form-block">
                <div class="user-title">&nbsp;</div>
                <div class="user-input">
                    <button id="btn-proceed" class="orngBtn mr10" type="submit">Save</button>
                    <a href="{{URL::previous()}}" id="btn-proceed" class="greyBtn" type="submit">Cancel</a>
                    <input type="hidden" name="_token" value="{{Session::token()}}">
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>

@endsection
