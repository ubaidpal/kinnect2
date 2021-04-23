<link href="{{ asset('/local/public/css/jquery.multiselect.css') }}" rel="stylesheet">
<script src="{{ asset('/local/public/js/jquery.multiselect.js') }}"></script>

<?php
$approval_required = false;
$request_sent = false;
?>
        <!-- Event Left Panel -->
<div class="ads-panel container-left-panel">
    <div class="left-panel">
        <div class="left-menu-img">
            <a href="javascript:void(0);">

                <img src="{{Kinnect2::getPhotoUrl($event->photo_id, $event->id, 'event', 'event_profile')}}"
                     id='event_profile' alt="image" width="250" height="250">
            </a>
        </div>
        <div class="menu-btn">
            <?php $status = Kinnect2::checkEventRequestStatus($event->id, Auth::user()->id) ?>
            @if(Kinnect2::isEventOwner($event))
                <a href="{{ url('event/edit/') }}/{{$event->id}}" class="btn btn-edit active">Edit Event</a>
            @elseif(Kinnect2::isEventGroupOwner($event,Auth::user()->id))
                <a href="{{ url('event/edit/') }}/{{$event->id}}" class="btn btn-edit active">Edit Event</a>
            @endif
            <a href="javascript:void(0);" class="btn btn-share js-open-modal" data-modal-id="popup4">Share This Event
            </a>

            @if($event->approval_required == 1 && Kinnect2::isRequestAttending(Auth::user()->id, $event->id) > 0)
                <?php $request_sent = true ?>
                @if((Kinnect2::isEventOwner($event) < 1) OR Kinnect2::isEventGroupOwner($event,Auth::user()->id) <1)
                    {{--@if(Kinnect2::isEventGroupOwner($event,Auth::user()->id) < 1)--}}
                    @if((($status->event_approved != 1)&& ($status->user_approved != 0)))
                        <a href="javascript:void(0);" id="{{$event->id}}" class="btn cancel-event-request"
                           title="Cancel your request to join event.">Cancel request</a>
                    @else
                        <a href="javascript:void(0);" id="{{$event->id}}" class="btn approve-event-request"
                           title="Approve Invite to join event.">Approve request</a>
                        <a href="javascript:void(0);" id="{{$event->id}}" class="btn cancel-event-request"
                           title="Cancel Invite to join event.">Cancel request</a>
                    @endif
                    {{--@endif--}}
                @endif


                {{--Check if request already sent--}}

            @elseif(Kinnect2::isRequestAttending(Auth::user()->id, $event->id) == 0)
                @if( $event->approval_required == 1 AND Kinnect2::isAttending(Auth::user()->id, $event->id) == '' ) {{--This is for RSVP: meaning if owner set you have to request if you want to join? --}}
                <?php $approval_required = true ?>
                @if((Kinnect2::isEventOwner($event) < 1) OR Kinnect2::isEventGroupOwner($event,Auth::user()->id) <1)
                    @if($status == [])
                        <a href="javascript:void(0);" class="btn request-event" id="{{$event->id}}"
                           title="Request to invite by owner for this event.">Request Invite</a>
                    @elseif((($status->event_approved != 1)&& ($status->user_approved != 0)))
                        <a href="javascript:void(0);" class="btn request-event" id="{{$event->id}}"
                           title="Request to invite by owner for this event.">Request Invite</a>
                    @else
                        <a href="javascript:void(0);" id="{{$event->id}}" class="btn approve-event-request"
                           title="Approve Invite to join event.">Approve request</a>
                        <a href="javascript:void(0);" id="{{$event->id}}" class="btn cancel-event-request"
                           title="Cancel Invite to join event.">Cancel request</a>
                    @endif
                @endif
                @endif


            @elseif(Kinnect2::isRequestAttending(Auth::user()->id, $event->id) == 0)
                @if( $event->approval_required == 0 AND Kinnect2::isAttending(Auth::user()->id, $event->id) == '' )
                    @if((Kinnect2::isEventOwner($event) < 1) OR Kinnect2::isEventGroupOwner($event,Auth::user()->id) <1)
                        @if($status == [])
                        @elseif((($status->event_approved == 1)&& ($status->user_approved == 0)))
                            <a href="javascript:void(0);" id="{{$event->id}}" class="btn approve-event-request"
                               title="Approve Invite to join event.">Approve request</a>
                            <a href="javascript:void(0);" id="{{$event->id}}" class="btn cancel-event-request"
                               title="Cancel Invite to join event.">Cancel request</a>
                        @endif
                    @endif
                @endif


            @elseif(Kinnect2::isRequestAttendingOpenEvent(Auth::user()->id, $event->id) == 1)


                @if($status == [])
                @elseif((($status->event_approved == 1) && ($status->user_approved == 0)))
                    <a href="javascript:void(0);" id="{{$event->id}}" class="btn approve-event-request"
                       title="Approve Invite to join event.">Approve request</a>
                    <a href="javascript:void(0);" id="{{$event->id}}" class="btn cancel-event-request"
                       title="Cancel Invite to join event.">Cancel request</a>

                @endif
            @endif

            @if($event->member_can_invite == 1 OR Kinnect2::isEventOwner($event))
                <a href="javascript:void(0);" class="btn btn-invite js-open-modal" data-modal-id="popup2">Invite
                    Guests</a>
                @endif
                        <!--<a class="btn btn-msg" href="javascript:void(0);">Message</a>-->
                @if(Kinnect2::isEventOwner($event) OR Kinnect2::isEventGroupOwner($event,Auth::user()->id))
                    <a class="btn btn-del btn js-open-modal" data-modal-id="popup-{{$event->id}}">Delete Event
                        {!! Form::open(array('method'=> 'get','url'=> "event/delete/".$event->id)) !!}
                        @include('includes.popup',
                            ['submitButtonText' => 'Delete Event',
                            'cancelButtonText' => 'Cancel',
                            'title' => 'Delete this Event',
                            'text' => 'Are You Sure You Want To Event This Battle?',
                            'id' => 'popup-'.$event->id])
                        {!! Form::close() !!}
                    </a>
                @endif
        </div>
    </div>
    @if($approval_required == FALSE AND $request_sent == FALSE)
        <div class="left-menu-radio">
            <p>Your RSVP</p>

            <form action="">
                <div class="mt10">
                    <label class="rsvp-radio-btn cf">
                        <input type="radio" class="event-rsvp-membership" name="event-rsvp-membership"
                               @if(Kinnect2::isAttending(Auth::user()->id, $event->id) == 'Attending') checked="checked"
                               @endif value="1" id="{{$event->id}}"
                               title="Click to say your are Attentanding this event">
                        <i></i> Attending
                    </label>
                </div>

                <div class="mt10">
                    <label class="rsvp-radio-btn cf">
                        <input type="radio" class="event-rsvp-membership" name="event-rsvp-membership"
                               @if(Kinnect2::isAttending(Auth::user()->id, $event->id) == 'Maybe Attending') checked="checked"
                               @endif value="2" id="{{$event->id}}"
                               title="Click to say your are Attentanding this event">
                        <i></i> Maybe Attending
                    </label>
                </div>

                <div class="mt10">
                    <label class="rsvp-radio-btn cf">
                        <input type="radio" class="event-rsvp-membership" name="event-rsvp-membership"
                               @if(Kinnect2::isAttending(Auth::user()->id, $event->id) == 'Not Attending') checked="checked"
                               @endif value="3" id="{{$event->id}}"
                               title="Click to say your are Attentanding this event">
                        <i></i> Not Attending
                    </label>
                </div>
            </form>
        </div>
    @endif
</div>

<div class="modal-box" id="popup4">
    <a href="#" class="js-modal-close close">×</a>

    <div class="modal-body">
        <div class="edit-photo-poup">
            <h3>Share</h3>

            <p>Share this by re-posting it with your own message</p>

            <div class="wall-photos">
                <div class="photoDetail">
                    <div class="form-container">
                        {!! Form::open(array('method'=> 'post','url'=> "shareEvent/".$event->id)) !!}
                        <div class="field-item">
                            <label for=""></label>
                            <textarea id="" name="text" placeholder="Write your message here"></textarea>
                        </div>
                        <div class="saveArea">
                            <input class="orngBtn fltL" type="submit" value="Share Event"/>
                            <input class="orngBtn js-modal-close close" type="button" style="margin-left: 110px;"
                                   value="Cancel"/>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div>
                <img src="{{Kinnect2::getPhotoUrl($event->photo_id, $event->id, 'event', 'event_profile')}}"
                     style="height: 50px; width: 50px">

                <div style="display: inline-block;margin-top: 40px;padding-left: 20px;">
                    <h4>{{$event['title']}}</h4>

                    <p style="margin-top: 10px; max-height:200px; overflow-y:auto;">{{$event['description']}}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-box" id="popup2">
    <a href="#" class="js-modal-close close">×</a>

    <div class="modal-body">
        <div class="edit-photo-poup">
            <h3>Invite Members</h3>

            <p>Choose the people you want to invite to this event.</p>

            <div class="wall-photos" style="overflow: auto">
                <div class="photoDetail">
                    <div class="form-container">
                        {!! Form::open(array('method'=> 'post','url'=> "inviteEvent/".$event->id)) !!}
                        <div>
                            <label for=""></label>
                            @if($friends == [])
                                <p>No Friend Left To Send Request</p>
                            @else
                                <select id="list_field" type="text" placeholder="Write the name of the person"
                                        name="list_field[]" multiple>
                                    @foreach($friends as $friend)
                                        <option value={{$friend['id']}}>{{@$friend['value']}}</option>
                                    @endforeach
                                </select>
                            @endif
                            <p id='error_msg' style="color:red; display:none;padding-top:5px">Please select some friends
                                to send invite</p>
                        </div>
                        <div class="clrfix"></div>
                        <div class="saveArea mt10">
                            @if($friends != [])
                                <input class="orngBtn fltL" type="submit" value="Send Invites" id="Send_invites"/>
                                <input class="orngBtn js-modal-close close" style="margin-left: 110px;" type="button"
                                       value="Cancel"/>
                            @else
                                <input class="orngBtn js-modal-close close" style="margin-left:130px;" type="button"
                                       value="Cancel"/>
                            @endif

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="stick"></div>


<style>
    .ms-options {
        position: relative !important;
    }
</style>

<script>
    $('#Send_invites').click(function (e) {
        var val1 = $('.ms-options-wrap' > button).val();
        if (val1 == '') {
            $('#error_msg').show();
            return false;
        }
        else {
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

    $('.approve-event-request').click(function (evt) {
        evt.preventDefault();
        var event_id = evt.target.id;
        var dataString = "event_id=" + event_id;
        $.ajax({
            type: 'POST',
            url: '{{url("request/event/approve")}}',
            data: dataString,
            success: function (response) {
                $('#' + event_id).removeClass('approve-event-request');
                window.location.reload();
                //cancel-event-request request-event
            }
        });
    });

    $('.cancel-event-request').click(function (evt) {
        evt.preventDefault();
        var event_id = evt.target.id;
        var dataString = "event_id=" + event_id;
        $.ajax({
            type: 'POST',
            url: '{{url("request/event/delete")}}',
            data: dataString,
            success: function (response) {
                $('#' + event_id).html('Request deleted.');
                $('#' + event_id).removeClass('cancel-event-request');
                $('#' + event_id).addClass('request-event');
                window.location.reload();
                //cancel-event-request request-event
            }
        });
    });

    $('.request-event').click(function (evt) {
        evt.preventDefault();
        var rsvp = 0;
        var event_id = evt.target.id;
        $('#' + event_id).html('Please wait...');
        var dataString = "rsvp=" + rsvp + "&event_id=" + event_id;
        $.ajax({
            type: 'POST',
            url: '{{url("request/event/invite")}}',
            data: dataString,
            success: function (response) {
                $('#' + event_id).html('Request sent.');
                $('#' + event_id).removeClass('request-event');
                $('#' + event_id).addClass('cancel-event-request');
                window.location.reload();
            }
        });
    });

    $('.event-rsvp-membership').click(function (evt) {
        var rsvp = evt.target.value;
        var event_id = evt.target.id;

        var dataString = "rsvp=" + rsvp + "&event_id=" + event_id;
        $.ajax({
            type: 'POST',
            url: '{{url("event/rsvp")}}',
            data: dataString,
            success: function (response) {
                window.location.reload();
//                alert(response);
            }
        });
    });

    $('select[multiple]').multiselect({
        columns: 1,
        search: true,
        placeholder: 'Select options'
    });
</script>
