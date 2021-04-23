@extends('layouts.masterDynamic')
@section('header-styles')

@endsection
@section('content')

    @if(Kinnect2::isEventOwner($event) || $is_authorized)
        @include('includes.event-left-nav')
        <style>
            .ui-state-active a {
                background: #ee4b08;
                color: #fff !important;
            }
        </style>
        <div class="content">

            <div class="content-gray-title mb10">
                <h4>Event Detail</h4>
                <ul id="tabs">

                    <li>
                        <a href="#tabs-photos" title="Create Battel" id="tab-photos" class="tabs btn fltR">
                            Photos({{count($event_photos->Album->AlbumPhotos)}})
                        </a>
                    </li>
                    <li><a href="#tabs-guests" id="tab-guests"
                           title="Click to see all the guest coming to join this event."
                           class="tabs btn fltR mr10">Guests ({{Kinnect2::countEventGuest($event->id)}})</a></li>
                    <li><a href="#tabs-information" title="Create Battel" id="tab-information"
                           class="tabs btn mr10 fltR">Event
                            Information</a></li>

                </ul>
                {{--<input type="text" style="display: none;" class="searchGuest" placeholder="Search Guests" id="serach_event_guest_{{$event->id}}" />--}}
            </div>
            <!-- Post Div-->
            <div class="content-content">
                <div id="tabs-information">
                    <div class="details">

                        <h4>{{$event->title}}</h4>
                        <br/>
                        @if(Kinnect2::isEventOwner($event))
                            <?php $user_ids = Kinnect2::eventPendingRequest($event->id) ?>
                            <span class="message">
                            @if( count($user_ids) > 0)
                                    {{ count($user_ids) }} waiting to join event.
                                @else
                                    No new request to join event.
                                @endif
                        </span>
                        @endif
                        <br/>

                        <p>{{$event->description}}</p>

                        <div class="details-list">
                            <div class="detail-item">
                                <div class="dtl-item">
                                    <span>Start Date&colon;</span>
                                </div>
                                <div class="dtl-value">
                                    <span>{{$event->starttime}}</span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="dtl-item">
                                    <span>End Date&colon;</span>
                                </div>
                                <div class="dtl-value">
                                    <span>{{$event->endtime}}</span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="dtl-item">
                                    <span>Venue&colon;</span>
                                </div>
                                <div class="dtl-value">
                                    <span>{{$event->location}}</span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="dtl-item">
                                    <span>Host&colon;</span>
                                </div>
                                <div class="dtl-value">
                                    <span>{{$event->host}}</span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="dtl-item">
                                    <span>Lead By&colon;</span>
                                </div>
                                <div class="dtl-value">
                                    <?php  $event_owner = Kinnect2::eventOwner($event->user_id) ?>
                                    @if($event_owner)
                                        <span><a href="{{url(Kinnect2::profileAddress($event_owner))}}">{{$event_owner->displayname}}</a></span>
                                    @endif
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="dtl-item">
                                    <span>Category&colon;</span>
                                </div>
                                <div class="dtl-value">
                                    <span><a href="javascript:void(0);">{{Kinnect2::getEventCategoryName($event->category_id)}}</a></span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="dtl-item">
                                    <span>RSVPs&colon;</span>
                                </div>
                                <div class="dtl-value">
                                <span class="mb5">
                                    <a href="javascript:void(0);">
                                        {{Kinnect2::countEventGuestAttending($event->id)}}
                                    </a> Attending
                                </span>
                                <span class="mb5">
                                    <a href="javascript:void(0);">
                                        {{Kinnect2::countEventGuestMaybeAttending($event->id)}}
                                    </a> May be attending
                                </span>
                                <span class="mb5">
                                    <a href="javascript:void(0);">
                                        {{Kinnect2::countEventGuestNotAttending($event->id)}}
                                    </a> Not attending
                                </span>
                                    @if($event->approval_required == 1)
                                        <span class="mb5">
                                            <a href="javascript:void(0);">
                                                {{Kinnect2::countEventGuestAwaitingReplyAttending($event->id)}}
                                            </a> Awaiting reply
                                        </span>
                                    @endif
                                    @if(Kinnect2::isEventOwner($event) OR Kinnect2::isEventGroupOwner($event,Auth::user()->id))
                                        <span class="mb5">
                                            <a href="javascript:void(0);">
                                                {{Kinnect2::countEventInvitesSentTo($event->id)}}
                                            </a> Invites Sent
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tabs-photos" style="display: none">
                    <div class="details">
                        {{--<h4>Tab 2</h4>--}}
                    </div>

                    <div class="create-album">
                        <ul class="">
                            <?php
                            $privacy = is_allowed($event->id, 'event', 'photo_upload', Auth::user()->id, $event->user_id);

                            ?>

                            @if(Kinnect2::isEventOwner($event) || $privacy)
                                <li class="cursor_pointer">
                                    <a href="javascript:void(0);" class="" id="add-photo">
                                        <img src="{!! asset('local/public/assets/images/add-image.jpg') !!}" width="150"
                                             height="160"
                                             alt=""/>
                                    </a>
                                </li>
                            @endif

                            <?php
                            $path = \Config::get('constants_activity.PHOTO_URL');
                            ?>
                            @if( count($event_photos->Album->AlbumPhotos) > 0)
                                @foreach($event_photos->Album->AlbumPhotos as $photos)

                                    <?php
                                    // $privacy = is_allowed( $album->album_id, 'album', 'view', Auth::user()->id, $album->owner_id )
                                    ?>

                                    <li class="cursor_pointer">
                                        <?php
                                        $photo_path = '';
                                        $photo_mime = '';
                                        if ( ! empty( $photos->storage_file->storage_path ) ) {
                                        $photo_path = $photos->storage_file->storage_path;
                                        }
                                        if ( ! empty( $album->cover_photo ) ) {
                                        $photo_mime = $photos->storage_file->mime_type;
                                        }
                                        ?>

                                        {{--<a href="{{ URL::to('albums/'.$album->album_id.'/edit') }}">--}}
                                        <span class="js-open-modal" data-modal-id="popup1"
                                              data-image="pic-{{$photos->storage_file->file_id}}">
                                            @if($photo_path)
                                                <img src="{{Kinnect2::get_photo_storage_id($photos->storage_file->file_id)}}"
                                                     width="150" height="150" alt=""/>
                                            @else
                                                <img src="{!! asset('local/public/assets/images/album.png') !!} "
                                                     width="150"
                                                     height="150" alt=""/>
                                            @endif
                                        </span>

                                    </li>


                                @endforeach

                            @endif
                        </ul>

                        {!! Form::open(array('method'=>'post','url' => 'event/add-photo', 'id'=>'submit-photo','class'=>'hide', 'enctype'=>"multipart/form-data")) !!}
                        {!! Form::file('file', array('id'=>'file-btn', 'accept'=>'image/*')) !!}
                        {!! Form::hidden('event_id', $event->id) !!}
                        {!! Form::close() !!}
                    </div>


                </div>
                <div id="tabs-guests" style="display: none;">
                    <div class="details">
                        <h4 id="guest-list">Guests</h4>

                        @if(count($eventInvitesSentToMembers) > 0)
                            @foreach($eventInvitesSentToMembers as $eventInvitesSentToMember)
                                <div class="my-battles">
                                    <div class="img">
                                        <a href="{{url(Kinnect2::profileAddress($eventInvitesSentToMember))}}">
                                            <img src="{{Kinnect2::getPhotoUrl($eventInvitesSentToMember->photo_id, $eventInvitesSentToMember->id, 'user', 'thumb_profile')}}"
                                                 title="{{$eventInvitesSentToMember->displayname}}"/>
                                        </a>
                                    </div>
                                    <div class="tag-post">
                                        <div class="tag">
                                            <a href="{{url(Kinnect2::profileAddress($eventInvitesSentToMember))}}">
                                                {{$eventInvitesSentToMember->displayname}}
                                            </a>
                                        </div>
                                        <div class="posted-by">Invite has been Sent To This Member</div>
                                        @if(Kinnect2::isEventOwner($event) OR Kinnect2::isEventGroupOwner($event,Auth::user()->id))
                                            <div class="posted-by">
                                                <a href="javascript:void(0);" title="Cancel"
                                                   onclick="cancel_waiting_guest('<?php echo $event->id ?>','<?php echo $eventInvitesSentToMember->id ?>',this)"
                                                   id="waiting_user_{{$eventInvitesSentToMember->id}}">
                                                    Cancel
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if(count($eventWaitingMembers) > 0)
                            @foreach($eventWaitingMembers as $eventWaitingMember)
                                <div class="my-battles">
                                    <div class="img">
                                        <a href="{{url(Kinnect2::profileAddress($eventWaitingMember))}}">
                                            <img src="{{Kinnect2::getPhotoUrl($eventWaitingMember->photo_id, $eventWaitingMember->id, 'user', 'thumb_profile')}}"
                                                 title="{{$eventWaitingMember->displayname}}"/>
                                        </a>
                                    </div>
                                    <div class="tag-post">
                                        <div class="tag"><a
                                                    href="{{url(Kinnect2::profileAddress($eventWaitingMember))}}">{{$eventWaitingMember->displayname}}</a>
                                        </div>
                                        <div class="posted-by">Waiting for RSVP</div>
                                        @if(Kinnect2::isEventOwner($event) OR Kinnect2::isEventGroupOwner($event,Auth::user()->id))
                                            <div class="posted-by">
                                                <a href="javascript:void(0);" title="Approve"
                                                   onclick="approve_waiting_guest('<?php echo $event->id ?>','<?php echo $eventWaitingMember->id ?>',this)"
                                                   id="waiting_user_{{$eventWaitingMember->id}}">
                                                    Approve
                                                </a>
                                                <a href="javascript:void(0);" title="Cancel"
                                                   onclick="cancel_waiting_guest('<?php echo $event->id ?>','<?php echo $eventWaitingMember->id ?>',this)"
                                                   id="waiting_user_{{$eventWaitingMember->id}}">
                                                    Cancel
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if(count($eventAttendingMembers) > 0)
                            @foreach($eventAttendingMembers as $eventAttendingMember)
                                <div class="my-battles">
                                    <div class="img">
                                        <a href="{{url(Kinnect2::profileAddress($eventAttendingMember))}}">
                                            <img src="{{Kinnect2::getPhotoUrl($eventAttendingMember->photo_id, $eventAttendingMember->id, 'user', 'thumb_profile')}}"
                                                 title="{{$eventAttendingMember->displayname}}"/>
                                        </a>
                                    </div>
                                    <div class="tag-post">
                                        <div class="tag"><a
                                                    href="{{url(Kinnect2::profileAddress($eventAttendingMember))}}">{{$eventAttendingMember->displayname}}</a>
                                        </div>
                                        <div class="posted-by">Attending</div>
                                        @if(Kinnect2::isEventOwner($event) OR Kinnect2::isEventGroupOwner($event,Auth::user()->id))
                                            @if($event->user_id != $eventAttendingMember->id )
                                                <div class="posted-by">
                                                    <a href="{{url('event/remove-member/'.$event->id.'/'.$eventAttendingMember->id)}}"
                                                       title="Remove">
                                                        Remove
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if(count($eventMaybeAttendingMembers) > 0)
                            @foreach($eventMaybeAttendingMembers as $eventMaybeAttendingMember)
                                <div class="my-battles">
                                    <div class="img">
                                        <a href="{{url(Kinnect2::profileAddress($eventMaybeAttendingMember))}}">
                                            <img src="{{Kinnect2::getPhotoUrl($eventMaybeAttendingMember->photo_id, $eventMaybeAttendingMember->id, 'user', 'thumb_profile')}}"
                                                 title="{{$eventMaybeAttendingMember->displayname}}"/>
                                        </a>
                                    </div>
                                    <div class="tag-post">
                                        <div class="tag"><a
                                                    href="{{url(Kinnect2::profileAddress($eventMaybeAttendingMember))}}">{{$eventMaybeAttendingMember->displayname}}</a>
                                        </div>
                                        <div class="posted-by">Maybe Attending</div>
                                        @if(Kinnect2::isEventOwner($event) OR Kinnect2::isEventGroupOwner($event,Auth::user()->id))
                                            @if($event->user_id != $eventMaybeAttendingMember->id )
                                                <div class="posted-by">
                                                    <a href="{{url('event/remove-member/'.$event->id.'/'.$eventMaybeAttendingMember->id)}}"
                                                       title="Remove">
                                                        Remove
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        @if(count($eventNotAttendingMembers) > 0)
                            @foreach($eventNotAttendingMembers as $eventNotAttendingMember)
                                <div class="my-battles">
                                    <div class="img">
                                        <a href="{{url(Kinnect2::profileAddress($eventNotAttendingMember))}}">
                                            <img src="{{Kinnect2::getPhotoUrl($eventNotAttendingMember->photo_id, $eventNotAttendingMember->id, 'user', 'thumb_profile')}}"
                                                 title="{{$eventNotAttendingMember->displayname}}"/>
                                        </a>
                                    </div>
                                    <div class="tag-post">
                                        <div class="tag"><a
                                                    href="{{url(Kinnect2::profileAddress($eventNotAttendingMember))}}">{{$eventNotAttendingMember->displayname}}</a>
                                        </div>
                                        <div class="posted-by">Not Attending this event</div>
                                        @if(Kinnect2::isEventOwner($event) OR Kinnect2::isEventGroupOwner($event,Auth::user()->id))
                                            @if($event->user_id != $eventNotAttendingMember->id )
                                                <div class="posted-by">
                                                    <a href="{{url('event/remove-member/'.$event->id.'/'.$eventNotAttendingMember->id)}}"
                                                       title="Remove">
                                                        Remove
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>


        </div>
    @else
        <div class="content">
            You are not authorized to view this page
        </div>
        @endif
        @include('includes.ads-right-side')

                <!--
    Popup For images Start
    -->
        <div class="modal-box" id="popup1" data-photo="">
            <a href="#" class="js-modal-close close"></a>

            <div class="modal-body">
                <div class="edit-photo-poup">
                    {{--<h3>Edit Photo</h3>--}}

                    <div class="wall-photos">
                        <ul class="bxslider">
                            <?php
                            $privacy = is_allowed($event->id, 'event', 'photo_upload', Auth::user()->id, $event->user_id)?>
                            <?php
                            $path = \Config::get('constants_activity.PHOTO_URL');
                            ?>
                            @if( count($event_photos->Album->AlbumPhotos) > 0)
                                @foreach($event_photos->Album->AlbumPhotos as $photos)

                                    <?php
                                    // $privacy = is_allowed( $album->album_id, 'album', 'view', Auth::user()->id, $album->owner_id )
                                    ?>

                                    <li class="hide all-pics" id="pic-{{$photos->storage_file->file_id}}">
                                        <?php
                                        $photo_path = '';
                                        $photo_mime = '';
                                        if ( ! empty( $photos->storage_file->storage_path ) ) {
                                        $photo_path = $photos->storage_file->storage_path;
                                        }
                                        if ( ! empty( $album->cover_photo ) ) {
                                        $photo_mime = $photos->storage_file->mime_type;
                                        }
                                        ?>

                                        {{--<a href="{{ URL::to('albums/'.$album->album_id.'/edit') }}">--}}

                                        @if($photo_path)
                                            <img src="{{$path.$photo_path.'?type='.urlencode($photo_mime) }}"
                                                 width="" height="400" alt=""/>
                                        @else
                                            <img src="{!! asset('local/public/assets/images/album.png') !!} "
                                                 width=""
                                                 height="100%" alt=""/>
                                        @endif

                                    </li>


                                @endforeach

                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--
   Popup For images Start
   -->

        <script>
            function approve_waiting_guest(event_id, guest_id, $this) {
                $($this).parent().parent().parent().remove();
                approve_waiting(event_id, guest_id);
            }

            function approve_waiting(event_id, guest_id) {
                if ($('#' + event_id).html() == 'Please wait..') return false;
                $('#' + event_id).html('Please wait..');

                var dataString = {
                    event_id: event_id, guest_id: guest_id
                };
                if ($('#' + event_id).html() != 'Approved' || $('#' + event_id).html() != 'Please wait..') {
                    $.ajax({
                        type: 'POST',
                        url: '{{url("/event/approve/request")}}',
                        data: dataString,
                        success: function (response) {
                            ('#' + event_id).html('Approved');
                            window.location.reload();
                        }
                    });
                }
            }
            ;

            function cancel_waiting_guest(event_id, guest_id, $this) {
                $($this).parent().parent().parent().remove();
                cancel_waiting(event_id, guest_id);
            }

            function cancel_waiting(event_id, guest_id) {
                if ($('#' + event_id).html() == 'Please wait..') return false;
                $('#' + event_id).html('Please wait..');

                var dataString = {
                    event_id: event_id, guest_id: guest_id
                };
                if ($('#' + event_id).html() != 'Approved' || $('#' + event_id).html() != 'Please wait..') {
                    $.ajax({
                        type: 'POST',
                        url: '{{url("/event/cancel/request")}}',
                        data: dataString,
                        success: function (response) {
                            ('#' + event_id).html('Approved');
                            window.location.reload();
                        }
                    });
                }
            }
            ;

            $('.searchGuest').keyup(function () {
                var search_value = $('.searchGuest').val();
                var event_id = '{{$event->id}}';

                var htmlContent = '';
                var url = '{{url("/event_guest_search")}}/' + event_id + '/' + search_value;
                $.getJSON(url, function (data) {
                    $('.my-battles').remove();
                    $.each(data, function (key, val) {
                        htmlContent += '<div class="my-battles"><div class="img"><a href="' + val.username + '"><img src="' + val.photo_id + '" title="' + val.displayname + '"/></a></div><div class="tag-post"> <div class="tag"><a href="' + val.username + '">' + val.displayname + '</a></div><div class="posted-by">Not Attending this event</div></div> </div>';
                    });
                    $('#guest-list').html(htmlContent);
                });
            });

            $('.tabs').click(function (e) {
                var id = e.target.id;
                if (id == 'tab-guests') {
                    $('.searchGuest').show();
                } else {
                    $('.searchGuest').hide();
                }
            });
            $('.request-to-join-event').click(function (evt) {
                var dataString = "brand_id=" + rsvp;
                $.ajax({
                    type: 'GET',
                    url: '{{url("request/event/invite")}}',
                    data: dataString,
                    success: function (response) {
                        $("#brand_" + brand_id).remove();
                    }
                });
            });
        </script>
        <style>
            .hide {
                display: none;
            }
        </style>
@endsection
@section('footer-scripts')
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <script src="{!! asset('local/public/assets/js/inner-pages.js') !!}"></script>
    <script>

        $(window).load(function () {
            var active = 2;
            if (window.location.hash) {
                var hash = window.location.hash.substring(1);
                active = $('#tabs li').find('#' + hash).parent().index();
            }
            $(".content").tabs({
                active: active
            });
        });

        $(function () {

        });
        $(document).ready(function () {
            $('.js-open-modal').click(function () {
                var id = $(this).data('image');
                $('.all-pics').addClass('hide');
                $('#' + id).removeClass('hide');
                var modal_w = $('#popup1').width();
                $(".modal-box").css({

                    left: (($(window).width() / 2) - (modal_w / 2))
                });
            })
        });
        $(window).resize(function () {
            var modal_w = $('#popup1').width();
            $(".modal-box").css({

                left: (($(window).width() / 2) - (modal_w / 2))
            });
        });

        $(window).resize();
    </script>
@endsection
