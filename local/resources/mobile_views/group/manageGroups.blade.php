@extends('layouts.default')
@section('content')
	<div class="title-bar">
    	<span>My Groups</span>
	</div>
    <ul>
        <?php $groups = Kinnect2::myAllGroups() ?>
        @if($groups != false)
            @foreach($groups as $group)
                <div class="comment-item">
                    <div class="comment-img">
                        <a class="comnt-imgc" href="{{url('group/'.$group->id)}}">
                            <img src="{{Kinnect2::getPhotoUrl($group->photo_id, $group->id, 'group', 'group_thumb')}}" alt="img">
                        </a>
                    </div>
                    <div class="comment-txt">
                        <div class="cmnt-title">
                            <a href="{{url('group/'.$group->id)}}">{{$group->title}}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </ul>
    <script>
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
