@extends('layouts.default')
@section('content')

    <div class="content-gray-title mb10">
        <h3>My Groups</h3>
    </div>

    <ul>
        <?php $groups = Kinnect2::myAllGroups() ?>
        @if($groups != false)
            @foreach($groups as $group)
                <div class="comment-item">
                    <div class="comment-img">
                        <a class="comnt-imgc" href="javascript:void(0)">
                            <img src="{{Kinnect2::getPhotoUrl($group->photo_id, $group->id, 'group', 'group_thumb')}}" alt="img">
                        </a>
                    </div>
                    <div class="comment-txt">
                        <div class="cmnt-title">
                            <a href="javascript:void(0)">{{$group->title}}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif


    </ul>

    <div class="content-gray-title mb10">
        <h4>Recommended Groups</h4>
    </div>

    <ul>
        <div class="browse-battle" style="width: 680px">`
            <?php $groups = Kinnect2::recomendedAllGroups() ?>
            @if($groups != false)
                @foreach($groups as $group)
                    <?php $groupOwner = Kinnect2::groupOwner($group->creator_id);
                            if($groupOwner == false){continue;}
                    ?>
                    @if (Auth::user()->id != $group->user_id)
                        <div class="browse-battle-item eH" id="group_{{$group->id}}">
                            <a class="browse-battle-img" href="{{ url('group/' .$group->id ) }}">
                                <img src="{{Kinnect2::getPhotoUrl($group->photo_id, $group->id, 'group', 'group_thumb')}}" alt="image">
                            </a>
                            <div class="battle-item-txt">
                                <div class="item-txt-title"><a href="{{ url('group/' .$group->id ) }}">{{$group->title}}</a></div>

                                <div class="item-txt-post">Posted by <a href="{{url(Kinnect2::profileAddress($groupOwner))}}">
                                        {{$groupOwner->displayname}}
                                    </a></div>
                                <div class="post-date">Followers: {{Kinnect2::followers($group->id)}}</div>
                                <?php $friendsFollowing = Kinnect2::FriendsFollowers($group->id,Auth::user()->id)?>
                                @if($friendsFollowing != NULL)
                                    <?php $total = count($friendsFollowing)?>
                                    <?php $name = Kinnect2::groupOwner($friendsFollowing[0]->user_id)?>
                                    @if($total == 1)
                                        <div class="joinM">
                                           <a href="{{url(Kinnect2::profileAddress($name))}}" style="color:#ee4b08">{{$name['displayname']}}</a>
                                            is member
                                        </div>
                                    @elseif($total > 1)
                                        <div class="joinM">
                                            <a href="{{url(Kinnect2::profileAddress($name))}}" style="color:#ee4b08">{{$name['displayname']}}</a>
                                            and
                                            <a class="js-open-modal" data-modal-id="popup6-{{$group->id}}" href="#" style="color:#ee4b08">
                                                {{$total-1}} Other Friends
                                                {!! Form::open(array()) !!}
                                                  @include('includes.popup',
                                                  [ 'id' => 'popup6-'.$group->id,
                                                    'list'=>$friendsFollowing,
                                                    'type' => 'popup6'])
                                                {!! Form::close() !!}
                                            </a>
                                            are members
                                        </div>
                                    @endif
                                @endif
                                <div class="item-txt-date">
                                @if($group->approval_required == 1)
                                     <?php $status = Kinnect2::checkGroupRequestStatus($group->id,Auth::user()->id) ?>
                                     @if($status == [])
                                          <a class="orngBtn follow-btn-perm" title="Sent Request to Join {{$group->title}}" id="{{$group->id}}"><span class="del-battle"></span>Request Membership</a>
                                     @elseif(($status->group_owner_approved == 1)&& ($status->user_approved == 0))
                                         <a class="orngBtn fltL mr5" onclick="approve_req('<?php echo $group->id ?>','<?php echo Auth::user()->id ?>',event); return false;" id="{{$group['id']}}" title="Accept Invite for joining {{$group['title']}}">
                                             Approve Invite
                                         </a>
                                         <a class="orngBtn fltL greyBg" onclick="reject_request('<?php echo $group->id ?>','<?php echo Auth::user()->id ?>')" id="{{$group['id']}}" title="Reject Invite for joining {{$group['title']}}">
                                              Reject Invite
                                         </a>
                                     @elseif(($status->group_owner_approved == 0)&& ($status->user_approved == 1))
                                          <a class="orngBtn cancel-btn-perm" id="{{$group['id']}}" title="Reject Request for joining {{$group['title']}}">
                                               Cancel Request
                                          </a>
                                     @else
                                          <a class="orngBtn follow-btn-perm" title="Sent Request to Join {{$group->title}}" id="{{$group->id}}"><span class="del-battle"></span>Request Membership</a>
                                     @endif
                                @else
                                     <?php $status = Kinnect2::checkGroupRequestStatus($group->id,Auth::user()->id) ?>
                                     @if($status == [])
                                         <a class="orngBtn follow-btn" title="Join {{$group->title}}" id="{{$group->id}}"><span class="del-battle"></span>Join Group</a>
                                     @elseif(($status->group_owner_approved == 1)&& ($status->user_approved == 0))
                                         <a class="orngBtn fltL mr5" onclick="approve_req('<?php echo $group->id ?>','<?php echo Auth::user()->id ?>',event); return false;" id="{{$group['id']}}" title="Accept Invite for joining {{$group['title']}}">
                                             Approve Invite
                                         </a>
                                         <a class="orngBtn fltL" onclick="reject_request('<?php echo $group->id ?>','<?php echo Auth::user()->id ?>')" id="{{$group['id']}}" title="Reject Invite for joining {{$group['title']}}">
                                              Reject Invite
                                         </a>
                                     @else
                                         <a class="orngBtn follow-btn" title="Join {{$group->title}}" id="{{$group->id}}"><span class="del-battle"></span>Join Group</a>
                                     @endif
                                @endif
                                <div class="clrfix"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </ul>
    <script>
        function follow_group(group_id){
        if($('#'+group_id).html() == 'Please wait..') return false;
        $('#'+group_id).html('Please wait..');

        var dataString = "group_id="+group_id;
        $.ajax({
            type: 'POST',
            url:  '{{url('group/follow')}}',
            data: dataString,
            success: function(response){
                $("#group_"+group_id).remove();
            }
        });
            window.location.reload();
        {{--var groupUrl = '{{url("group")}}'+'/'+group_id;--}}
         {{--window.location.href = groupUrl;--}}
        }//follow_group(group_id)

        $('.follow-btn').click(function (evt) {
            var group_id = evt.target.id;
            follow_group(group_id);
        });

    </script>

    <script>
        function approve_req(group_id,member_id, e){
            e.preventDefault();
          var dataString = {
          group_id: group_id,
          member_id:member_id
          };
          $.ajax({
              type: 'POST',
              url:  '{{url('groups/approveReq')}}',
              data: dataString,
              success: function(response){
              }
          });
            window.location.reload();
           {{--var groupUrl = '{{url("group")}}'+'/'+group_id;--}}
           {{--window.location.href = groupUrl;--}}

        }//follow_group(group_id)

        function reject_request(group_id,member_id){
                reject_req(group_id,member_id);
            }
        function reject_req(group_id,member_id){

          var dataString = {
          group_id: group_id,
          member_id:member_id
          };
          $.ajax({
              type: 'POST',
              url:  '{{url('groups/rejectReq')}}',
              data: dataString,
              success: function(response){
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

        $('.follow-btn-perm').click(function (evt) {
            var group_id = evt.target.id;
            follow_group_perm(group_id);
        });

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
                     window.location.reload();
                }
            });
        }//follow_group(group_id)

        $('.cancel-btn-perm').click(function (evt) {
            var group_id = evt.target.id;
            cancel_group_perm(group_id);
        });


    </script>
@stop()
