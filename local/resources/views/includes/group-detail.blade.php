<!-- Right Ad Panel -->
<div class="full-width-banner gDetail">
    <div class="group-container">

        @if($group['creator_id'] == Auth::user()->id)
        <div class="change-cover">
            <span></span>
            <a href="javascript:void(0);" id="change_cover_btn" class="change_cover_btn" title="Hey! {{ ucwords( @$user->name ) }} click here to change your group cover photo."></a>
            <input type="hidden" name="group_id" id="group_id" value="{{$group['id']}}">
        </div>
        @endif
            <div class="group-title">{{$group['title']}}</div>
        <div data-user="{{$group['id']}}"  data-url="/group/profile-content" class="banner-links " id="group-details-wrapper"
            data-isowner="{{Kinnect2::isGroupOwner($group)}}"
            data-view-permission="{{Kinnect2::GroupViewPerm($group)}}"
            data-comment-permission="{{Kinnect2::GroupCommentPerm($group)}}"
            data-upload-permission="{{Kinnect2::GroupPrivacyPerm($group)}}"
            data-event-permission= "{{Kinnect2::GroupEventPerm($group)}}">

            <?php $perms = Kinnect2::getGroupsPrivacy($group->id,'view');?>
            @if((Kinnect2::isFollowingGroup($group['id'],Auth::user()->id) > 0) OR ($perms['permission'] == Config::get('constants.PERM_EVERYONE')))
                @if($perms['permission'] != Config::get('constants.PERM_GROUP_OFFICERS_AND_OWNERS'))
                    <a href="javascript:void(0);" title="What's New" class="tab @if(Request::is('*/group/*')) active @endif" data-target="whats-new" data-ajax="true">What's New</a>
                @else
                    @if(Kinnect2::isGroupOwner($group) > 0)
                        <a href="javascript:void(0);" title="What's New" class="tab @if(Request::is('*/group/*')) active @endif" data-target="whats-new" data-ajax="true">What's New</a>
                    @endif
                @endif
            @endif
           {{-- <a href="javascript:void(0);" title="Activity Log">Activity Log (70)</a>--}}
            <a href="#" class="@if(Request::is('*/group/members') || Request::is('group/members/*')) active @endif tab" title="See all group members." data-target="members" data-ajax="true">
                Members({{ Kinnect2::followers($group['id']) }})
            </a>
            <?php $perm = Kinnect2::getGroupsEventCreationPrivacy($group['id']); ?>
            @if((Kinnect2::isFollowingGroup($group['id'],Auth::user()->id) > 0) OR ($perm['permission'] == Config::get('constants.PERM_EVERYONE')))
                <a href="{{url('group/events/')}}/{{$group['id']}}" class="@if(Request::is('*/group/events') || Request::is('group/events/*')) active @endif tab" title="See all events about this group." data-target="events" data-ajax="true">
                    Events ({{ Kinnect2::countEvents('group', $group['id']) }})
                </a>
            @endif
            <a href="{{url('group/info/')}}/{{$group['id']}}" class="tab @if(Request::is('*/group/info') || Request::is('group/info/*')) active @endif" title="See all information about this group." data-target="info" data-ajax="true">
                Info
            </a>
        </div>
    </div>
    <div class="banner-bottom-bg"></div>

    <div class="cover-photo-container" id="cover_photo_div">
        <img height="145" width="715" src="{{$group['path'] != '' ? $group['path'] : asset('local/storage/app/photos/0/default_group_profile_photo.svg') }}" id="cover_photo"  alt="cover" title="cover">
    </div>
    <style>
        #croppic{
            width: 715px !important;
            height: 145px !important;
        }

    </style>
    <div class="change-cover-photo" style="display:none; width:715px;height:145px;" id="crop_div">
    </div>

</div>

<link rel="stylesheet" type="text/css" href="{!! asset('local/public/assets/css/croppic.css') !!}">
