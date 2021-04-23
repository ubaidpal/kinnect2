<div class="content-gray-title mb10" data-user="{{$group['id']}}" data-url="/group/profile-content">
    <?php $isAuthGroupManager = Kinnect2::isGroupManager($group->id, Auth::user()->id); ?>
    <h4>Group Members</h4>
    @if((Auth::user()->id == $group->creator_id) ||($isAuthGroupManager == 1))
        <a data-target="pending-invites" data-ajax="true" title="See Pending Invites" class="btn fltR tab">Pending
                                                                                                           Invites</a>
        @if($group->approval_required == 1)
            <a data-target="pending-requests" data-ajax="true" title="See Pending Requests" class="btn fltR tab"
               style="margin-right: 5px;">Pending Requests</a>
        @endif
    @endif
</div>
<div class="browse-battle">
    @foreach($members as $member)
        <?php $groupOwner = Kinnect2::groupMember($member->user_id); ?>

        <?php if(!isset($groupOwner->id)){
            continue;
        }?>

        <div class="browse-battle-item">
            <div class="btn-del">
                <a class="browse-battle-img" href="{{url(Kinnect2::profileAddress($groupOwner))}}">
                    <img src="{{ Kinnect2::getPhotoUrl($groupOwner->photo_id, $groupOwner->id, 'user', 'thumb_normal')}}"
                         alt="image">
                </a>
            </div>
            <div class="battle-item-txt">
                @if(isset($groupOwner))
                    <div class="item-txt-title">
                        <a href="{{url(Kinnect2::profileAddress($groupOwner))}}">
                            {{$groupOwner->displayname}}
                            @if($member->user_id == $group->creator_id)
                                (owner)
                            @elseif(Kinnect2::isGroupManager($group->id,$member->user_id) > 0)
                                (manager)
                            @endif
                        </a></div>
                @endif

            </div>
            @if((Auth::user()->id == $group->creator_id) || ($isAuthGroupManager == 1))
                @if($member->user_id != $group->creator_id)
                    <?php if(Auth::user()->id == $group->creator_id){$ownerOrMemeber = '';}else{$ownerOrMemeber = 'self';}?>
                    <div class="item-vote">
                        <?php $isGroupManager = Kinnect2::isGroupManager($group->id, $member->user_id); ?>
                        @if($isGroupManager == 0)
                            <a class="follow-btn btn btn-add-friend fltL mr10"
                               onclick="remove_member('<?php echo $group->id ?>','<?php echo $member->user_id ?>', event, this)"
                               id="{{$member->user_id}}">Remove</a>
                            @if(Kinnect2::IsGroupManagerReq($group['id'],$member->user_id) > 0)
                                <a class="btn btn-add-friend fltR"
                                   onclick="demote_manager('<?php echo $group->id ?>','<?php echo $member->user_id ?>', '<?php echo $ownerOrMemeber; ?>', this)"
                                   id="rem-{{$member->user_id}}">Cancel Promotion</a>
                            @else
                                <a class="btn btn-add-friend fltR"
                                   onclick="make_manager('<?php echo $group->id ?>','<?php echo $member->user_id ?>', this)"
                                   id="make-{{$member->user_id}}">Make Manager</a>
                            @endif
                        @else
                            @if(Auth::user()->id == $member->user_id)
                                <a class="follow-btn btn btn-add-friend fltL mr10"
                                   onclick="remove_member('<?php echo $group->id ?>','<?php echo $member->user_id ?>', event, this)"
                                   id="{{$member->user_id}}">Leave Group</a>
                                <a class="btn btn-add-friend fltR"
                                   onclick="demote_manager('<?php echo $group->id ?>','<?php echo $member->user_id ?>', '<?php echo $ownerOrMemeber; ?>', '')"
                                   id="rem-{{$member->user_id}}">Self Demote</a>
                            @else
                                <a class="follow-btn btn btn-add-friend fltL mr10"
                                   onclick="remove_member('<?php echo $group->id ?>','<?php echo $member->user_id ?>', event, this)"
                                   id="{{$member->user_id}}">Remove</a>
                                <a class="btn btn-add-friend fltR"
                                   onclick="demote_manager('<?php echo $group->id ?>','<?php echo $member->user_id ?>', '', '')"
                                   id="rem-{{$member->user_id}}">Demote</a>
                            @endif
                        @endif
                    </div>

                @endif
            @endif
        </div>
    @endforeach
</div>

<script>
    function make_manager(group_id, member_id, $this){
        var dataString = {
            group_id : group_id, member_id : member_id
        };
        console.log($('#make-'+member_id));
        $.ajax({
            type : 'POST', url : '{{url('groups/makeManager')}}', data : dataString, success : function(response){
                //window.location.reload();
                console.log($this);
                //noinspection JSJQueryEfficiency
                $('#make-'+member_id).text('Cancel Promotion');
                //noinspection JSJQueryEfficiency
                $('#make-'+member_id).attr('onClick', "demote_manager(" + group_id + ", " + member_id + ", ' ', this)")
                $('#make-'+member_id).attr('id', "rem-"+ member_id);
            }
        });
    }

    function demote_manager(group_id, member_id, selfOrOwner, $this){
        var dataString = {
            group_id : group_id, member_id : member_id
        };


        $.ajax({
            type : 'POST', url : '{{url('groups/demoteManager')}}', data : dataString, success : function(response){
                //window.location.reload();
                if(selfOrOwner != 'self'){
                    $('#rem-'+member_id).text('Make Manager');
                    $('#rem-'+member_id).attr('onClick', "make_manager(" + group_id + ", " + member_id + ")");
                    $('#rem-'+member_id).attr('id', "make-"+ member_id);
                }else{
                    $('#rem-'+member_id).remove();
                }


            }
        });
    }

    function remove_member(group_id, member_id, event, $this){
        event.preventDefault();
        $($this).parent().parent().remove();
        follow_group(group_id, member_id);
    }
    function follow_group(group_id, member_id){

        var dataString = {
            group_id : group_id, member_id : member_id
        };
        $.ajax({
            type : 'POST', url : '{{url('groups/remove')}}', data : dataString, success : function(response){
                //window.location.reload();
            }
        });
    }//follow_group(group_id)
</script>


