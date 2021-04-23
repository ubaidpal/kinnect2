@extends('admin.layout.store-admin')
@section('content')
        <!-- Post Div-->
@include('admin.layout.arbitrator-leftnav')

<div class="ad_main_wrapper">
    <div class="task_inner_wrapper">
        <div class="main_heading">
            <h1>Update User</h1>
        </div>

        <div class="assigned-task-wrapper">
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
            {!! Form::model($users , ['method' => 'PATCH', 'url' => "admin/users/update/".$users->id, "enctype"=>"multipart/form-data"]) !!}


            <div class="add-form-block">
                <?php $roll = array_except($rolls, ['id', '1']);?>
                <div class="user-title">User Type * :</div>
                <div class="user-input">
                    <select name="roll">
                        @foreach($roll as $key => $row)

                            <option @if($key == $myRole->role_id) selected
                                    @endif value="{{$key.'-'.$row}}">{{$row}}</option>

                        @endforeach
                    </select>
                </div>
            </div>
            <div class="add-form-block">
                <div class="user-title">Permissions * :</div>
                <div class="user-input">
                    @foreach($allPermissions as $perm)
                        {!!  Form::checkbox('permissions[]', $perm->id  ,(isset($permissions[$perm->id])?'checked':''),['class' => 'user-input' , 'id' => 'user-input'])!!}
                        <span>
                            {{$perm->name}}
                        </span>
                    @endforeach
                </div>
            </div>
            <div class="add-form-block">
                <div class="user-title">Country or region * :</div>
                <div class="user-input">{!!  Form::select('countries', $countries , $users->country ,['class' => 'user-input' , 'id' => 'user-input' ,'required' => 'required'])!!}</div>
            </div>
            <div class="add-form-block">
                <div class="user-title">First Name * :</div>
                <div class="user-input"><input name="first_name" value="{{$users->first_name}}" type="text"></div>
                <br/>
            </div>

            <div class="add-form-block">
                <div class="user-title">Last Name * :</div>
                <div class="user-input"><input name="last_name" value="{{$users->last_name}}" type="text"></div>
                <br/>
            </div>
            <div class="add-form-block">
                <div class="user-title">Email * :</div>
                <div class="user-input"><input name="email" value="{{$users->email}}" type="text"></div>
                <br/>
            </div>
            <div class="add-form-block">
                <div class="user-title">Password * :</div>
                <div class="user-input"><input name="password" value="" type="password"></div>
                <br/>
            </div>
            <div class="add-form-block">
                <div class="user-title">Retype Password * :</div>
                <div class="user-input"><input name="retype_password" value="" type="password"></div>
                <br/>
            </div>

            <div class="add-form-block">
                <div class="user-title">&nbsp;</div>
                <div class="user-input">
                    <button id="btn-proceed" class="orngBtn mr10" type="submit">Save</button>
                    <a href="{{url('admin/users')}}" id="btn-proceed" class="greyBtn" type="submit">Cancel</a>
                    <input type="hidden" name="_token" value="{{Session::token()}}">
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>

@endsection
