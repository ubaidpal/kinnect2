@extends('admin.layout.store-admin')
@section('content')
        <!-- Post Div-->
@include('admin.layout.arbitrator-leftnav')
@include('admin.alert.alert')
<div class="ad_main_wrapper" id="ad_main_wrapper">
    <div class="task_inner_wrapper">
        <div class="main_heading">
            <h1>User Management</h1>


        </div>
        <div class="task-tabs">
            <a href="{{route('admin.users')}}"
               class="@if(\Request::is('admin/users')) active @endif">Admin Users</a>
            <a href="{{route('normal.users')}}"
               class="@if(\Request::is('admin/normal-users')) active @endif">Normal Users</a>

        </div>
        <div class="awr-search">
            <form method="get" action="{{route('admin.user.search')}}" id="serachForm">
                <div class="fltR">
                    <div class="awr-ttle">
                        <input type="text" placeholder="Type user name" class="search" name="username"
                               value="@if(isset($username)){{$username}}@endif">
                    </div>
                    <div class="awr-ttle">
                        <input type="text" placeholder="Type email" class="search" name="email"
                               value="@if(isset($email)){{$email}}@endif">
                    </div>
                    <div class="awr-btn">
                        <button class="searchFormBtn" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="assigned-task-wrapper">
            <div class="user-table heading">
                <div class="name">Name</div>
                <div class="email">Email</div>
                <div class="role">User Type</div>
                <div class="action">Action</div>
            </div>

            @if(count($users) > 0)
                @foreach($users as $user)
                    <div class="user-table">
                        <div class="name">
                            @if($user->user_type == config('constants.USER_TYPES.BRAND_USER'))
                                {{$user->displayname}}
                            @else
                                {{$user->first_name}}&nbsp;{{$user->last_name}}
                            @endif
                        </div>
                        <div class="email">{{$user->email}}</div>
                        <div class="role">{{config('constants.USER_TYPES.'.$user->user_type)}}</div>
                        <div class="action">
                            <?php if($user->active <= 0){ ?>
                            <?php if($user->id != Auth::user()->id){ ?>
                            <span class="activeState"><a href="{{$user->active}}" class="ActiveUser" id="{{$user->id}}">Active</a></span>
                            <?php } ?>
                            <?php } ?>
                            <?php if($user->active == 1){ ?>
                            <?php if($user->id != Auth::user()->id){ ?>
                            <span class="activeState2"><a href="{{$user->active}}" class="disableUser"
                                                          id="{{$user->id}}">Disable</a></span>
                            <?php } ?>
                            <?php } ?>
                            <a href="{{route('normal.users.edit',$user->id)}}" class="editUser"
                               id="{{$user->id}}" title="Edit">Edit</a>
                            <a href="#" class="deleteUser" id="{{$user->id}}" title="Delete">Delete</a>
                            <a href="#" class="login" id="{{$user->id}}" title="Login">Login</a>
                        </div>

                    </div>
                @endforeach
            @else
                No record found
            @endif
        </div>

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
