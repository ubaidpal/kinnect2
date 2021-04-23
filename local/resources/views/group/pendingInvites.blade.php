<div class="content-gray-title mb10" data-user="{{$group['id']}}"  data-url="/group/profile-content">
    <h4>Pending Invites</h4>
    <a data-target="members" data-ajax="true" title="Browse" class="btn fltR tab" href="#">All Members</a>
</div>
<div class="browse-battle">
    @if($members == [])
        <p> No Pending Invites</p>
    @else
        @foreach($members as $member)
            <div class="browse-battle-item">
                <div class="btn-del">
                  <a class="browse-battle-img" href="javascript:();">
                  <?php $groupOwner = Kinnect2::groupMember($member->user_id); ?>
                  <img src="{{Kinnect2::profilePhoto($groupOwner->photo_id, $groupOwner->id, $type = null, $thumb_type=null)}}" alt="image">
                </a>
                </div>
                <div class="battle-item-txt">
                   @if(isset($groupOwner))
                   <div class="item-txt-title"><a href="{{url(Kinnect2::profileAddress($groupOwner))}}">{{$groupOwner->displayname}}</a></div>
                   @endif
                </div>
                <div class="item-vote">
                    <a class="btn btn-add-friend" style="margin-top: 5px;width: 110px;" onclick="reject_member('<?php echo $group->id ?>','<?php echo $member->user_id ?>', this)" title="Cancel Invite for {{$groupOwner->displayname}}">Cancel Invite</a>
                </div>

            </div>
        @endforeach
    @endif
</div>

<script>

    function reject_member(group_id, member_id, $this) {
        $($this).parent().parent().remove();
        reject_invite(group_id, member_id);
    }
    function reject_invite(group_id, member_id) {

        var dataString = {
            group_id: group_id,
            member_id: member_id
        };
        $.ajax({
            type: 'POST',
            url: '{{url('groups/rejectReq')}}',
            data: dataString,
            success: function (response) {
                //window.location.reload();
            }
        });
    }//follow_group(group_id)
</script>


