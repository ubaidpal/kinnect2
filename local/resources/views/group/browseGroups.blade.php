@extends('layouts.default')
@section('content')

    <div class="content-gray-title mb10">
        <h4>My Groups</h4>
        <a title="Browse" class="btn fltR" href="{{ action('GroupController@manageGroups')}}">Manage Groups</a>
    </div>

    <div class="search-box">
        <form method="get" action="{{url('groups')}}">
            <input type="text" name="title" value="{{$title}}">
            <input type="hidden" value="1" name="search_init">
            <button type="submit" class="orngBtn">Search</button>
            @if(!empty($title))
                <a href="{{url('groups')}}">Clear</a>
            @endif
        </form>
    </div>

    <ul>
        @if($groups)
            @foreach($groups as $group)
                    <?php
                    $groupOwner = Kinnect2::groupOwner($group->creator_id);
                        if($groupOwner == false){continue;}
                    ?>
                <div class="my-battles" id="group_{{$group->id}}">
                    <div class="img">
                        <a href="{{ url('group/' .$group->id ) }}">
                            <img src="{{Kinnect2::getPhotoUrl($group->photo_id, $group->id, 'group', 'group_thumb')}}" alt="image">
                        </a>
                    </div>
                    <div class="tag-post">
                        <div class="tag">
                            <a href="{{ url('group/' .$group->id ) }}">{{$group->title}}</a>
                            <img id="close_img" style="display: none" src="{!! asset('local/public/assets/images/close.png') !!}" alt="image">
                        </div>

                        <div class="posted-by">Created by <a href="{{url(Kinnect2::profileAddress($groupOwner))}}">{{$groupOwner->displayname}}</a></div>
                        <div class="post-date">Members: {{Kinnect2::followers($group->id)}}</div>
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
                    </div>
                    <div class="battles-btn">
                        @if((Kinnect2::isGroupOwner($group) > 0)||(Kinnect2::isGroupManager($group->id,Auth::user()->id) > 0))
                            <a class="btn" href="{{ url('group/edit/' .$group->id ) }}"><span class="edit"></span>Edit Group</a>
                            <a class="btn js-open-modal" data-modal-id="popup-{{$group->id}}"><span class="del-battle"></span>
                                Delete Group
                            </a>
                            @if($group->creator_id != Auth::user()->id)
                                <a class="btn js-open-modal" data-modal-id="popup45-{{$group->id}}"><span class="del-battle"></span>
                                Leave Managership
                                </a>
                                {!! Form::open(array('method'=> 'get','url'=> "group/leaveManagership/".$group->id)) !!}
                                  @include('includes.popup',
                                      ['submitButtonText' => 'Leave Managership',
                                      'cancelButtonText' => 'Cancel',
                                      'title' => 'Self Demote',
                                      'text' => 'Are You Sure You Want To Leave Managership Of This Group?',
                                      'id' => 'popup45-'.$group->id])
                                {!! Form::close() !!}
                            @endif
                        @else
                            <a class="btn js-open-modal" data-modal-id="popup1-{{$group->id}}" title="Leave {{$group->title}}"><span class="del-battle"></span>Leave Group
                                
                            </a>
                            {!! Form::open(array('method'=> 'get','url'=> "group/unfollow/".$group->id)) !!}
                              @include('includes.popup',
                                  ['submitButtonText' => 'Leave Group',
                                  'cancelButtonText' => 'Cancel',
                                  'title' => 'Leave this Group',
                                  'text' => 'Are You Sure You Want To Leave This Group?',
                                  'id' => 'popup1-'.$group->id])
                            {!! Form::close() !!}
                        @endif
                    </div>
                </div>
                {!! Form::open(array('method'=> 'get','url'=> "group/delete/".$group->id)) !!}
                  @include('includes.popup',
                      ['submitButtonText' => 'Delete Group',
                      'cancelButtonText' => 'Cancel',
                      'title' => 'Delete this Group',
                      'text' => 'Are You Sure You Want To Delete This Group?',
                      'id' => 'popup-'.$group->id])
                {!! Form::close() !!}
            @endforeach
        @endif


    </ul>

    <div class="content-gray-title mb10">
        <h4>Recommended Groups</h4>
    </div>

    <ul>
        <div class="browse-battle" style="width: 680px">`

            @if($recomended_groups)
                @foreach($recomended_groups as $group)
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

                                <div class="item-txt-post">Created by <a href="{{url(Kinnect2::profileAddress($groupOwner))}}">
                                        {{$groupOwner->displayname}}
                                    </a></div>
                                <div class="post-date">Members: {{Kinnect2::followers($group->id)}}</div>
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
            //window.location.reload();
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
                     //window.location.reload();
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
                    $("#group_"+group_id).remove();
                     //window.location.reload();
                }
            });
        }//follow_group(group_id)

        $('.cancel-btn-perm').click(function (evt) {
            var group_id = evt.target.id;
            cancel_group_perm(group_id);
        });


    </script>
@stop()
