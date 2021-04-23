@extends('admin.layout.store-admin')
@section('content')
        <!-- Post Div-->
@include('admin.layout.arbitrator-leftnav')
@include('admin.alert.alert')
<div class="ad_main_wrapper">
    <div class="task_inner_wrapper">
        <div class="main_heading">
            <h1>Add User</h1>
        </div>

        <div class="assigned-task-wrapper">
            {!! Form::open(['url' => url("admin/users/store"), 'class' => 'form-block creatingUser', 'id' => 'form-block', "enctype"=>"multipart/form-data"]) !!}
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
                <?php $roll = array_except($rolls, ['id', '5']);?>
                <div class="user-title">User Type * :</div>

                <div class="user-input">
                    <select id="roll" name="roll">
                        <option value="0" selected="selected">Select user type</option>
                        @foreach($roll as $key => $row)
    
                            <option value="{{$row->id.'-'.$row->name}}">{{$row->name}}</option>
    
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="add-form-block">
                <div class="user-title">Country or region * :</div>
                <div class="user-input">{!!  Form::select('countries', $countries , 0, ['class' => 'user-input' , 'id' => 'userCountry' ])!!}</div>

            </div>
            <div class="add-form-block">
                <div class="user-title">First Name * :</div>
                <div class="user-input"><input name="first_name" type="text"></div>
                <br/>
            </div>

            <div class="add-form-block">
                <div class="user-title">Last Name * :</div>
                <div class="user-input"><input name="last_name" type="text"></div>
                <br/>
            </div>
            <div class="add-form-block">
                <div class="user-title">Email * :</div>
                <div class="user-input"><input name="email" type="text"></div>
                <br/>
            </div>
            <div class="add-form-block">
                <div class="user-title">Password * :</div>
                <div class="user-input"><input type="password" name="password"></div>
                <br/>
            </div>
            <div class="add-form-block">
                <div class="user-title">Retype Password * :</div>
                <div class="user-input"><input type="password" name="retype_password"></div>
                <br/>
            </div>

            <div class="add-form-block">
                <div class="user-title">&nbsp;</div>
                <div class="user-input">
                    <button id="btn-proceed" class="orngBtn mr10" type="submit">Save</button>
                    <a href="{{url('admin/users/view')}}" id="btn-proceed" class="greyBtn" type="submit">Cancel</a>
                    <input type="hidden" name="_token" value="{{Session::token()}}">
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>
<script>
    $(".creatingUser").submit(function() {
        if ($("#roll").val() == 0) {
            $("#roll").val('');
        }

        if ($("#userCountry").val() == 0) {
            $("#userCountry").val('');
        }
    });
</script>
@endsection
