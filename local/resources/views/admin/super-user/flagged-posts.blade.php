{{--

    * Created by   :  Muhammad Yasir
    * Project Name : kinnect2
    * Product Name : PhpStorm
    * Date         : 20-Apr-16 10:56 AM
    * File Name    : flagged-posts

--}}
@extends('admin.layout.store-admin')
@section('content')
        <!-- Post Div-->
@include('admin.layout.arbitrator-leftnav')
@include('admin.alert.alert')
<div class="ad_main_wrapper" id="ad_main_wrapper">
    <div class="task_inner_wrapper">
        <div class="main_heading">
            <h1>Flagged Posts</h1>


        </div>

        <div class="assigned-task-wrapper">
            <div class="user-table heading">
                <div class="name">Post ID</div>
                <div class="email">Reason</div>
                <div class="role">Reporter</div>
                <div class="action">Action</div>
            </div>

            @if(count($posts) > 0)
                @foreach($posts as $user)
                    <div class="user-table">
                        <div class="name">{{$user->action_id}}</div>
                        <div class="email">{{ucfirst($user->category)}}</div>
                        <div class="role">{{user_name($user->user_id)}}</div>
                        <div class="action">

                            <span class="activeState">
                                <a title="View Content" href="{{url('admin/postDetail/'.$user->action_id)}}" class="ActiveUser"
                                   id="{{$user->id}}">
                                    View Content
                                </a>
                            </span>
                            <span class="activeState">
                                <a title="Take Action" href="#" class="ActiveUser dismiss" id="{{$user->report_id}}">
                                    Take Action
                                </a>
                            </span>
                        </div>

                    </div>
                @endforeach
            @else
                No record found
            @endif
        </div>

    </div>
    {!!  $posts->render() !!}
</div>

<style>
    .user-table div.role {
        width: 120px;
    }
</style>


@endsection
@section('footer-scripts')
    <div class="modal-box cart" id="dismiss" style="display: none;">
        <a href="#" class="js-modal-close close">X</a>

        <div class="modal-body">
            <div class="edit-photo-poup">
                <p class="mt10" style="width: 400px;height: 30px;line-height: normal">
                    Are you sure that you want to delete this report? It will not be recoverable after being deleted.
                </p><br>
                <a class="btn fltL blue mr10 block-post-btn" href="{{route('admin.login')}}">Block Post</a>
                <a style="width: 115px" class="btn fltL blue mr10 dismiss-btn" href="{{route('admin.login')}}">Dismiss Report</a>
                <a class="btn fltL grey mr10 js-modal-close" href="#">Cancel</a>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $(".dismiss").click(function (e) {
                e.preventDefault();
                var user_id = e.target.id;
                var appendthis = ("<div class='modal-overlay js-modal-close'></div>");

                $("body").append(appendthis);
                $(".modal-overlay").fadeTo(500, 0.7);

                $('#dismiss').fadeIn($(this).data());

                var url = "{{url('admin/dismiss-report')}}/" + jQuery(this).attr('id');
                var urlBlock = "{{url('admin/block-post')}}/" + jQuery(this).attr('id');
                jQuery('.dismiss-btn').attr('href', url);
                jQuery('.block-post-btn').attr('href', urlBlock);

            });
        })
    </script>
@endsection
