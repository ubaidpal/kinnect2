@extends('admin.layout.store-admin')
@section('content')
        <!-- Post Div-->
@include('admin.layout.arbitrator-leftnav')
@include('admin.alert.alert')
<div class="ad_main_wrapper" id="ad_main_wrapper">
    <div class="task_inner_wrapper">
        <div class="main_heading">
            <h1>Assign Permissions</h1>


        </div>
        <div class="task-tabs">
            <a href="{{route('admin.settings')}}" title="Permissions"
               class="@if(\Request::is('admin/settings')) active @endif">Permissions</a>


        </div>

        {!! Form::open(['route'=> 'admin.assign-permission']) !!}
        <div class="awr-search">

            <div class="fltR">
                <div class="awr-select">

                    {!! Form::select('permission', $permissions, NULL, ['class'=> 'search']) !!}
                </div>
                <div class="awr-btn">
                    <button class="searchFormBtn" type="submit">Assign</button>
                </div>
            </div>

        </div>

        <div class="assigned-task-wrapper">
            <div class="user-table heading">
                <div class="role" style="width: 84px">Select</div>
                <div class="name" style="width: 215px">Name</div>
                <div class="email">Email</div>
                <div class="role">User Type</div>
                <div class="action">Permissions</div>
            </div>

            @if(count($users) > 0)
                @foreach($users as $user)
                    <div class="user-table">
                        <div class="role" style="width: 84px">
                            {!! Form::checkbox('users[]', $user->id) !!}
                        </div>
                        <div class="name" style="width: 215px">{{$user->displayname}}</div>
                        <div class="email">{{$user->email}}</div>
                        <div class="role">{{config('constants.USER_TYPES.'.$user->user_type)}}</div>
                        <div class="action">
                            <a title="Edit permissions" href="{{url('admin/users/edit/'.$user->id)}}" class="editUser" id="{{$user->id}}">
                                <?php $permissions = getPermissions($user->id,true) ?>
                                @if(!empty($permissions))
                                @foreach ($permissions as $permission)
                                {{@$permission->name}}<br>
                                @endforeach
                                @endif
                            </a>
                        </div>

                    </div>
                @endforeach
            @else
                No record found
            @endif
        </div>
        {!! Form::close() !!}
    </div>
    {!!  $users->render() !!}
</div>

<style>
    .user-table div.role {
        width: 120px;
    }
</style>
<div class="modal-box cart" id="confirmation_popup" style="display: none;">
    <a href="#" class="js-modal-close close">?</a>

    <div class="modal-body">
        <div class="edit-photo-poup">
            <p class="mt10" style="width: 400px;height: 30px;line-height: normal">Are you sure you want delete this
                user?</p><br>
            <a class="btn fltL blue mr10 confirmed" href="#">Yes</a>
            <a class="btn fltL grey mr10 js-modal-close" href="#">No</a>
        </div>
    </div>
</div>

<div class="modal-box cart" id="login_user" style="display: none;">
    <a href="#" class="js-modal-close close">?</a>

    <div class="modal-body">
        <div class="edit-photo-poup">
            <p class="mt10" style="width: 400px;height: 30px;line-height: normal">Your current session will be
                expired</p><br>
            <a class="btn fltL blue mr10 logged_in" href="{{route('admin.login')}}">Yes</a>
            <a class="btn fltL grey mr10 js-modal-close" href="#">No</a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).on("click", ".deleteUser", function (e) {
        e.preventDefault();
        var user_id = e.target.id;
        e.preventDefault();
        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");

        $("body").append(appendthis);
        jQuery('body').css('overflow', 'hidden');
        $(".modal-overlay").fadeTo(500, 0.7);

        $('#confirmation_popup').fadeIn($(this).data());

        var url = jQuery(this).attr('id');
        jQuery('.confirmed').attr('id', url);

    });

    $(document).on("click", ".login", function (e) {
        e.preventDefault();
        var user_id = e.target.id;
        e.preventDefault();
        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");

        $("body").append(appendthis);
        $(".modal-overlay").fadeTo(500, 0.7);

        $('#login_user').fadeIn($(this).data());

        var url = "{{url('admin/login-admin')}}/" + jQuery(this).attr('id');
        jQuery('.logged_in').attr('href', url);

    });


    jQuery(document).on('click', '.confirmed', function (e) {
        e.preventDefault();
        var user_id = $(e.target).attr('id');
        jQuery.ajax({
            type: "Post",
            url: '{{url("admin/users/delete")}}',
            data: {user_id: user_id},
            success: function (data) {
                if (data == 1) {
                    window.location.reload();
                } else {
                    alert('No delete File');
                }

            }, error: function (xhr, ajaxOptions, thrownError) {
                alert("ERROR:" + xhr.responseText + " - " + thrownError);
            }
        });
    });
    $(document).on("click", ".disableUser", function (e) {
        e.preventDefault();
        var user_id = e.target.id;
        var user = $(this).attr('href');
        jQuery.ajax({
            type: "Post",
            url: '{{url("admin/users/userStatus")}}',
            data: {user_id: user_id, user: user},
            success: function (data) {
                if (data == 1) {
                    window.location.reload();
                }
            }, error: function (xhr, ajaxOptions, thrownError) {
                alert("ERROR:" + xhr.responseText + " - " + thrownError);
            }
        });
    });
    $(document).on("click", ".ActiveUser", function (e) {
        e.preventDefault();
        var user_id = e.target.id;
        var user = $(this).attr('href');
        jQuery.ajax({
            type: "Post",
            url: '{{url("admin/users/userStatus")}}',
            data: {user_id: user_id, user: user},
            success: function (data) {
                if (data == 1) {
                    window.location.reload();
                }
            }, error: function (xhr, ajaxOptions, thrownError) {
                alert("ERROR:" + xhr.responseText + " - " + thrownError);
            }
        });
    });

</script>

@endsection
