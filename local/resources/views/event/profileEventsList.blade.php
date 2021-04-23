
<div class="content-gray-title mb10">
        <h4>Events</h4>
        <?php $perm = Kinnect2::getGroupsEventCreationPrivacy($group->id); ?>
        @if($perm['permission'] == Config::get('constants.PERM_GROUP_OFFICERS_AND_OWNERS'))
           @if(Kinnect2::isGroupOwner($group,Auth::user()->id) > 0)
               <a class="btn fltR mr10" title="Create Event" href="{{url('/event/create/parent_type/group/parent_id')}}/{{$group->id}}">Create Event</a>
           @endif
        @elseif($perm['permission'] == Config::get('constants.PERM_GROUP_MEMBERS'))
             @if(Kinnect2::isFollowingGroup($group['id'],Auth::user()->id) > 0)
                <a class="btn fltR mr10" title="Create Event" href="{{url('/event/create/parent_type/group/parent_id')}}/{{$group->id}}">Create Event</a>
             @endif
        @else
            <a class="btn fltR mr10" title="Create Event" href="{{url('/event/create/parent_type/group/parent_id')}}/{{$group->id}}">Create Event</a>
        @endif

</div>
<!-- Post Div-->
@foreach($events as $event)
        <div class="my-battles">
                <div class="img">
                        <a href="{{url('event')}}/{{$event->id}}">
                                <img src="{{Kinnect2::getPhotoUrl($event->photo_id, $event->id, 'event', 'event_thumb')}}" title="{{$event->title}}" alt="image">
                        </a>
                </div>
                <div class="tag-post">
                        <?php
                         $eventOwner = Kinnect2::eventOwner($event->user_id);
                        ?>
                        <div class="tag"><a href="{{url('event')}}/{{$event->id}}">{{$event->title}}</a></div>
                        <div class="posted-by">Posted by <a href="{{url(Kinnect2::profileAddress($eventOwner))}}">{{$eventOwner->displayname}}</a></div>
                        <div class="post-date">{{$event->created_at}}</div>
                </div>
        </div>
@endforeach
