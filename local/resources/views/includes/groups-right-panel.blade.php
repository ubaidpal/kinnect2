<link href="{{ asset('/local/public/css/jquery.multiselect.css') }}" rel="stylesheet">
<script src="{{ asset('/local/public/js/jquery.multiselect.js') }}"></script>

<div class="group_profile_options">
    <div id="profile_options">
        @if(Kinnect2::isFollowingGroup($group['id'],Auth::user()->id) > 0)
            @if((Kinnect2::isGroupOwner($group,Auth::user()->id) > 0)||(Kinnect2::isGroupManager($group['id'],Auth::user()->id)>0))
                <ul id="">
                    <li class="">
                        <a class="btn" href="{{ url('group/edit/' .$group->id ) }}"><span class="edit">Edit Group Details</span></a>
                    </li>
                    <li class="">
                        <a class="btn js-open-modal" data-modal-id="popup-{{$group->id}}"><span class="delete">Delete Group</span></a>
                    </li>
                    <li class="">

                        <a href="#" class="js-open-modal btn" data-modal-id="popup4"><span
                                    class="share">Share Group</span></a>
                    </li>
                    <li class="invite-nav-item">
                        <a href="#" class="js-open-modal btn" data-modal-id="popup2"><span
                                    class="invite">Invite Members</span></a>
                    </li>
                    <!--<li class="send-nav-item">
                        <a class="btn"><span class="send">Message</span></a>
                    </li>-->
                </ul>
                {!! Form::open(array('method'=> 'get','url'=> "group/delete/".$group->id)) !!}
                  @include('includes.popup',
                      ['submitButtonText' => 'Delete Group',
                      'cancelButtonText' => 'Cancel',
                      'title' => 'Delete this Group',
                      'text' => 'Are You Sure You Want To Delete This Group?',
                      'id' => 'popup-'.$group->id])
                {!! Form::close() !!}
            @else
                <ul id="">
                    @if(Kinnect2::IsGroupManagerReq($group['id'],Auth::user()->id) > 0)
                        <li>
                            <a href="#" class="btn" onclick="approve_managership('<?php echo $group->id ?>','<?php echo Auth::user()->id ?>'); return false;" id="{{$group['id']}}" title="Join {{$group['title']}}">
                                <span class="approved">Approve Managership</span>

                            </a>
                        </li>
                        <li>
                            <a href="#" class="btn" onclick="reject_managership('<?php echo $group->id ?>','<?php echo Auth::user()->id ?>')" id="{{$group['id']}}" title="Reject Request for joining {{$group['title']}}">
                                <span class="delete">Reject Request</span>
                            </a>

                        </li>
                    @endif
                    <li class="">
                        <a class="btn js-open-modal" data-modal-id="popup1-{{$group->id}}" title="Leave {{$group->title}}"><span class="delete">Leave Group</span>
                            
                        </a>
                    </li>
                    <li class="">
                        <a href="#" class="js-open-modal btn" data-modal-id="popup4"><span
                                    class="share">Share Group</span></a>
                    </li>
                    @if($group['members_can_invite'] == 1)
                        <li class="invite-nav-item">
                            <a class="js-open-modal btn" data-modal-id="popup2"><span
                                        class="invite">Invite Members</span></a>
                        </li>
                    @endif
                </ul>
            @endif
            {!! Form::open(array('method'=> 'get','url'=> "group/unfollow/".$group->id)) !!}
              @include('includes.popup',
                  ['submitButtonText' => 'Leave Group',
                  'cancelButtonText' => 'Cancel',
                  'title' => 'Leave this Group',
                  'text' => 'Are You Sure You Want To Leave This Group?',
                  'id' => 'popup1-'.$group->id])
            {!! Form::close() !!}
        @else
            <ul id="">

                @if($group['approval_required'] == 0)
                    <?php $status = Kinnect2::checkGroupRequestStatus( $group->id, Auth::user()->id ) ?>
                    @if($status == [])
                        <li>
                            <a href="#" class="btn" onclick="follow_group(<?php echo $group->id ?>)" id="{{$group['id']}}" title="Join {{$group['title']}}">
                                <span class="join">Join Group</span>
                            </a>
                        </li>
                    @elseif(($status->group_owner_approved == 1)&& ($status->user_approved == 0))
                        <li>
                            <a href="#" class="btn" onclick="approve_req('<?php echo $group->id ?>','<?php echo Auth::user()->id ?>', event); return false;" id="{{$group['id']}}" title="Join {{$group['title']}}">
                                <span class="approved">Approve Request</span>

                            </a>
                        </li>
                        <li>
                            <a href="#" class="btn" onclick="reject_request('<?php echo $group->id ?>','<?php echo Auth::user()->id ?>')" id="{{$group['id']}}" title="Reject Request for joining {{$group['title']}}">
                                <span class="delete">Reject Request</span>
                            </a>

                        </li>
                    @else
                        <li>
                            <a href="#" class="btn" onclick="follow_group(<?php echo $group->id ?>)" id="{{$group['id']}}" title="Join {{$group['title']}}"><span class="join">Join Group</span></a>
                        </li>
                    @endif

                @elseif($group->approval_required == 1)
                    <?php $status = Kinnect2::checkGroupRequestStatus( $group->id, Auth::user()->id ) ?>
                    @if($status == [])
                        <li>
                            <a href="#" class="btn" onclick="follow_group_perm(<?php echo $group->id ?>)" id="{{$group->id}}" title="Request Membership for {{$group->title}}">
                                <span class="request-membership">Request Membership</span>
                            </a>
                        </li>
                    @elseif(($status->group_owner_approved == 0)&& ($status->user_approved == 0))
                        <li>
                            <a href="#" class="btn" onclick="follow_group_perm(<?php echo $group->id ?>)" id="{{$group->id}}" title="Request Membership for {{$group->title}}">
                                <span class="request-membership">Request Membership</span>
                            </a>
                        </li>
                    @elseif($status->group_owner_approved == 0 && $status->user_approved == 1 )
                        <li>
                            <a href="#" class="btn" onclick="cancel_group_perm(<?php echo $group->id ?>)" id="{{$group->id}}" title="Cancel Membership Request for {{$group->title}}">
                                <span class="delete">Cancel Request </span>
                            </a>
                        </li>
                    @elseif($status->group_owner_approved == 1 && $status->user_approved == 1 )
                        <li>
                            <a href="#" class="btn js-open-modal" data-modal-id="popup1-{{$group->id}}" title="Leave {{$group->title}}">
                                <span class="delete">Leave Group</span>
                            </a>
                        </li>
                    @elseif(($status->group_owner_approved == 1)&& ($status->user_approved == 0))
                        <li>
                            <a href="#" class="btn" onclick="approve_req('<?php echo $group->id ?>','<?php echo Auth::user()->id ?>',event); return false;" id="{{$group['id']}}" title="Join {{$group['title']}}">
                                <span class="approved">Approve Request</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="reject_request('<?php echo $group->id ?>','<?php echo Auth::user()->id ?>')"class="btn" id="{{$group['id']}}" title="Reject Request for joining {{$group['title']}}">
                                <span class="delete">Reject Request</span>
                            </a>
                        </li>
                    @endif

                @endif
                {!! Form::open(array('method'=> 'get','url'=> "group/unfollow/".$group->id)) !!}
                  @include('includes.popup',
                      ['submitButtonText' => 'Leave Group',
                      'cancelButtonText' => 'Cancel',
                      'title' => 'Leave this Group',
                      'text' => 'Are You Sure You Want To Leave This Group?',
                      'id' => 'popup1-'.$group->id])
                {!! Form::close() !!}
                <li class="">
                    <a href="#" class="js-open-modal btn" data-modal-id="popup4"><span class="share">Share Group</span></a>
                </li>
            </ul>
        @endif
    </div>
</div>

<div class="modal-box" id="popup4">
    <a href="#" class="js-modal-close close">×</a>

    <div class="modal-body">
        <div class="edit-photo-poup">
            <h3>Share Group</h3>

            <p class="mt10">Share this by re-posting it with your own message</p>

            <div class="group_popup_title">
                <img src="{{Kinnect2::profilePhoto($group->photo_id, $group->id,'group')}}">

                <div style="width: 250px" class="description-box">
                    <h4>{{$group['title']}}</h4>

                    <p>{{limit_chr($group['description'],145)}}</p>
                </div>
            </div>
            <div class="wall-photos">
                <div class="photoDetail">
                    <div class="form-container">
                        {!! Form::open(array('method'=> 'post','url'=> "shareGroup/".$group->id)) !!}
                        <div class="field-item">
                            <label for=""></label>
                            <textarea id="" name="text" placeholder="Write your message here"
                                      style="width:312px;"></textarea>
                        </div>
                        <div class="saveArea">
                            <input class="orngBtn fltL" type="submit" value="Share Group"/>
                            <input class="orngBtn js-modal-close close fltL ml10" type="button" value="Cancel"/>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-box" id="popup2">
    <a href="#" class="js-modal-close close">×</a>

    <div class="modal-body">
        <div class="edit-photo-poup" style="overflow: auto">
            <h3>Invite Members</h3>

            <p>Choose the people you want to invite to this group.</p>

            <div class="wall-photos" style="overflow: auto">
                <div class="photoDetail">
                    <div class="form-container">
                        {!! Form::open(array('method'=> 'post','url'=> "inviteGroup/".$group->id)) !!}
                        <div>
                            <label for=""></label>
                            @if($friends == [])
                                <p>No Friend Left To Send Request</p>
                            @else
                                <select id="list_field" placeholder="Write the name of the person"
                                        name="list_field[]" multiple="multiple">
                                    @foreach($friends as $friend)
                                        <option value={{$friend['id']}}>{{$friend['value']}}</option>
                                    @endforeach
                                </select>
                            @endif
                            <p id='error_msg' style="color:red; display:none;padding-top:5px">Please select some friends to send invite</p>

                        </div>
                        <div class="clrfix"></div>
                        <div class="saveArea mt10">
                           @if($friends != [])
                            <input class="orngBtn fltL" type="submit" value="Send Invites" id="Send_invites"/>
                            <input class="orngBtn js-modal-close close" style="margin-left: 110px;" type="button"value="Cancel"/>
                           @else
                            <input class="orngBtn js-modal-close close" style="margin-left:130px;" type="button" value="Cancel"/>
                           @endif

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .ms-options {
        position: relative !important;
    }
</style>

<script>
 $('#Send_invites').click(function(e){
   var val1= $('.ms-options-wrap'> button).val();
   if(val1 == ''){
    $('#error_msg').show();
    return false;
   }
   else{
    $('#error_msg').hide();
    return true;
   }
 });
</script>
<script>
    $(function () {

        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");

        $('a[data-modal-id]').click(function (e) {
            e.preventDefault();
            $("body").append(appendthis);
            $(".modal-overlay").fadeTo(500, 0.7);
            //$(".js-modalbox").fadeIn(500);
            var modalBox = $(this).attr('data-modal-id');
            $('#' + modalBox).fadeIn($(this).data());
        });


        $(".js-modal-close, .modal-overlay").click(function () {
            $(".modal-box, .modal-overlay").fadeOut(500, function () {
                $(".modal-overlay").remove();
            });

        });

        $(window).resize(function () {
            $(".modal-box").css({
                top: ($(window).height() - $(".modal-box").outerHeight()) / 3,
                left: ($(window).width() - $(".modal-box").outerWidth()) / 2
            });
        });

        $(window).resize();

    });
</script>

<script>

   function approve_managership(group_id, member_id) {
        var dataString = {
            group_id: group_id,
            member_id: member_id
        };
        $.ajax({
            type: 'POST',
            url: '{{url('groups/acceptManagerReq')}}',
            data: dataString,
            success: function (response) {
                window.location.reload();
            }
        });
    }

    function reject_managership(group_id, member_id) {
        var dataString = {
            group_id: group_id,
            member_id: member_id
        };
        $.ajax({
            type: 'POST',
            url: '{{url('groups/demoteManager')}}',
            data: dataString,
            success: function (response) {
                window.location.reload();
            }
        });
    }

    function approve_req(group_id, member_id, e) {
        e.preventDefault();
        var dataString = {
            group_id: group_id,
            member_id: member_id
        };
        $.ajax({
            type: 'POST',
            url: '{{url('groups/approveReq')}}',
            data: dataString,
            success: function (response) {
                window.location.reload();
            }
        });

    }//follow_group(group_id)


    function reject_request(group_id, member_id) {
        reject_req(group_id, member_id);
    }
    function reject_req(group_id, member_id) {

        var dataString = {
            group_id: group_id,
            member_id: member_id
        };
        $.ajax({
            type: 'POST',
            url: '{{url('groups/rejectReq')}}',
            data: dataString,
            success: function (response) {
                window.location.reload();
            }
        });
    }//follow_group(group_id)
</script>


<script type="text/javascript">
    function un_follow_group(group_id) {
        if ($('#' + group_id).html() == 'Please wait..') return false;
        $('#' + group_id).html('Please wait..');

        var dataString = "group_id=" + group_id;
        $.ajax({
            type: 'POST',
            url: '{{url('group/unfollow')}}',
            data: dataString,
            success: function (response) {
                $("#group_" + group_id).remove();
                window.location.reload();
            }
        });

    }//un_follow_group(group_id)


    function follow_group(group_id) {
        if ($('#' + group_id).html() == 'Please wait..') return false;
        $('#' + group_id).html('Please wait..');

        var dataString = "group_id=" + group_id;
        $.ajax({
            type: 'POST',
            url: '{{url('group/follow')}}',
            data: dataString,
            success: function (response) {
                $('#' + group_id).removeClass('follow-btn');
                $('#' + group_id).addClass('un-follow-btn');
                window.location.reload();
            }
        });


    }//follow_group(group_id)


    function follow_group_perm(group_id) {
        if ($('#' + group_id).html() == 'Please wait..') return false;
        $('#' + group_id).html('Please wait..');

        var dataString = "group_id=" + group_id;

        $.ajax({
            type: 'POST',
            url: '{{url('group/follow')}}',
            data: dataString,
            success: function (response) {
                $('#' + group_id).html('Cancel Request');
                $('#' + group_id).removeClass('follow-btn-perm');
                $('#' + group_id).addClass('cancel-btn-perm');

                window.location.reload();
            }
        });

    }//follow_group(group_id)



    function cancel_group_perm(group_id) {
        if ($('#' + group_id).html() == 'Please wait..') return false;
        $('#' + group_id).html('Please wait..');

        var dataString = "group_id=" + group_id;
        $.ajax({
            type: 'POST',
            url: '{{url('group/unfollow')}}',
            data: dataString,
            success: function (response) {
                $('#' + group_id).html('Request Membership');
                $('#' + group_id).removeClass('cancel-btn-perm');
                $('#' + group_id).addClass('follow-btn-perm');
                var groupUrl = '{{url("group")}}' + '/' + group_id;
                window.location.href = groupUrl;
            }
        });

    }//follow_group(group_id)

    $('select[multiple]').multiselect({
        columns: 1,
        search: true,
        placeholder: 'Select options'
    });
</script>

