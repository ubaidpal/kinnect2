@extends('layouts.default')
@section('content')

    <div class="content-gray-title mb10">
        <h4>My Groups</h4>
        <a title="Create" style="margin-left: 5px;" class="btn fltR" href="{{ url('group/create')}}">Create Group</a>
        <a title="Browse" class="btn fltR" href="{{ url('groups')}}">Browse Groups</a>
    </div>

    @if($groups->count() || $search_init)
    <div class="search-box">
        <form method="get" action="{{url('groups/manage')}}">
            <input type="text" name="title" value="{{$title}}">
            <input type="hidden" value="1" name="search_init">
            <button type="submit" class="orngBtn">Search</button>
            @if(!empty($title))
                <a href="{{url('groups/manage')}}">Clear</a>
            @endif
        </form>
    </div>

    <ul>
        @foreach($groups as $group)
            <?php
            $groupOwner = Kinnect2::groupOwner($group->creator_id);
            if($groupOwner == false){
                continue;
            }
            ?>
            <div class="my-battles">
                <div class="img">
                    <a href="{{ url('group/' .$group->id ) }}">
                        <img src="{{Kinnect2::getPhotoUrl($group->photo_id, $group->id, 'group', 'group_thumb')}}" alt="image">
                    </a>
                </div>
                <div class="tag-post">
                    <div class="tag">
                        <a href="{{ url('group/' .$group->id ) }}">{{ucfirst($group->title)}}</a>
                        <img id="close_img" style="display: none" src="{!! asset('local/public/assets/images/close.png') !!}" alt="image">
                    </div>

                    <div class="posted-by">Created by <a href="{{url(Kinnect2::profileAddress($groupOwner))}}">{{$groupOwner->displayname}}</a></div>
                    <div class="post-date">Members: {{Kinnect2::followers($group->id)}}</div>
{{--                        <div class="posted-by">{{$group->description}}</div>--}}
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
    </ul>
    @endif
    @if($search_init && $groups->count() < 1)
    <div>
        No group found matching search criteria.
    </div>
    @elseif($groups->count() < 1)
    <div>
        No group created yet. Click on <a title="Create Group" style="background-color: #EE4B07;color: #fff;padding: 2px;border-radius: 5px;" class="" href="{{ url('group/create')}}">Create Group</a> to create your first group.
    </div>
    @endif
    <script type="text/javascript">
        function un_follow_group(group_id){
            if($('#'+group_id).html() == 'Please wait..') return false;
            $('#'+group_id).html('Please wait..');

            var dataString = "group_id="+group_id;
            $.ajax({
                type: 'POST',
                url:  '{{url('group/unfollow')}}',
                data: dataString,
                success: function(response){
                    $("#group_"+group_id).remove();
                    window.location.reload();
                }
            });
             var groupUrl = '{{url("groups/manage")}}';
             window.location.href = groupUrl;
        }//un_follow_group(group_id)

        $('.un-follow-btn').click(function (evt) {
            var group_id = evt.target.id;
            alert('Group id: ' + evt.target.id + ' is going to be unfollow.');
            un_follow_group(group_id);
        });
    </script>
@stop()
