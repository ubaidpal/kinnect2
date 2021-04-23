<!-- Right Ad Panel -->
<div class="ads-panel">
    <div class="menu-btn">
        <ul>
       @if(Kinnect2::isRequestAttending(Auth::user()->id, $event->id) > 0)
                <h1>You request to join is sented out.</h1>
           <a href="javascript:void(0);" id="{{$event->id}}" class="cancel-event-request" title="Cancel your request to join event.">Cancel request</a>
       @endif {{--Check if request already sent--}}
       @if(Kinnect2::isRequestAttending(Auth::user()->id, $event->id) == 0)
        @if( $event->approval_required == 1 AND Kinnect2::isAttending(Auth::user()->id, $event->id) == '' ) {{--This is for RSVP: meaning if owner set you have to request if you want to join? --}}
                <li><a href="javascript:void(0);" class="request-event" id="{{$event->id}}" title="Request to invite by owner for this event.">Request Invite</a></li>
            @else
            <?php echo Kinnect2::currentUserRsvp(Auth::user()->id, $event->id)?>
            <li>
                <label for="attending">Attending</label>
                <input type="radio" class="event-rsvp-membership" name="event-rsvp-membership" value="1" id="{{$event->id}}" title="Click to say your are Attentanding this event">
            </li>
            <li>
                <label for="may_be_attending">May be Attending</label>
                <input type="radio" class="event-rsvp-membership" name="event-rsvp-membership" value="2" id="{{$event->id}}" title="Click to say your are Attentanding this event">
            </li>
            <li>
                <label for="not_attending">Not Attending</label>
                <input type="radio" class="event-rsvp-membership" name="event-rsvp-membership" value="3" id="{{$event->id}}" title="Click to say your are Attentanding this event">
            </li>
            @endif
       @endif
        </ul>
    </div>
</div>
<script>

    $('.cancel-event-request').click(function(evt){
        evt.preventDefault();
        var event_id = evt.target.id;
        $('#'+event_id).html('Please wait...');
        var dataString = "event_id=" + event_id;
        $.ajax({
            type: 'POST',
            url: '{{url("request/event/delete")}}',
            data: dataString,
            success: function (response) {
                $('#'+event_id).html('Your request to join this event has been deleted.');
            }
        });
    });

    $('.request-event').click(function(evt){
        evt.preventDefault();
        var rsvp     = 0;
        var event_id = evt.target.id;
        $('#'+event_id).html('Please wait...');
        var dataString = "rsvp=" + rsvp + "&event_id=" + event_id;
        $.ajax({
            type: 'POST',
            url: '{{url("request/event/invite")}}',
            data: dataString,
            success: function (response) {
                $('#'+event_id).html('Your request to join this event has been sent.');
            }
        });
    });

    $('.event-rsvp-membership').click(function(evt){
        var rsvp     = evt.target.value;
        var event_id = evt.target.id;

        var dataString = "rsvp=" + rsvp + "&event_id=" + event_id;
        $.ajax({
            type: 'POST',
            url: '{{url("event/rsvp")}}',
            data: dataString,
            success: function (response) {
//                alert(response);
            }
        });
    });
</script>