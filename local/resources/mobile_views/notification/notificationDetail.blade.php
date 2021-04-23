@extends('layouts.default')
@section('content')

    <div class="title-bar">
		<span>Recent Updates</span>
	</div>
    <div class="pulldown_contents">
        <ul id="notifications_menu" class="notifications_menu">
            @foreach($allStrings as $strings)
                <li value="175" class="@if($strings['is-read'] == 1) notifications_read @else notifications_unread @endif ">
                    {{-- <span class="notification_subject_photo">
                         <img src="{!! asset('local/public/images/leaderboard-tabs-content.jpg') !!}">
                     </span>--}}
                    <span class="noti-date">
                        {{\Carbon\Carbon::parse($strings['date'])->format(Config::get('constants.DATE_FORMAT'))}}
                    </span>
                    <span class="notification_item_general notification_type_friend_accepted">
                        <a href="{{url('goto/'.$strings['notification_id'].'?redirect-uri='.base64_encode($strings['url']))}}">
                            {!! $strings['string'] !!}
                        </a>
                    </span>
                </li>
            @endforeach
            <div class="brand-btn" id="load_more">
                <a href="javascript:void(0)" class="btn">Show More</a>
            </div>

            <div id="loading">
                <img id="loading-image" style="display:none" src="{!! asset('local/public/images/loading.gif') !!}"
                     alt="Loading..."/>
            </div>
        </ul>
    </div>
    <script>

        var is_complete = true;
        $(document).ready(function(event){
            $('#load_more').click(function(){

                if(is_complete){
                    is_complete   = false;
                    var is_end    = $('#is_end').val();
                    var next_page = $('#next_page_id').html();
                    $('#loading-image').show();
                    if(! next_page){
                        next_page = 2;
                    }

                    var page = next_page;

                    $.ajax({
                        type : "POST",
                        url : "{{url('notification/notificationDetailViewMore/')}}",
                        data : "page=" + page,
                        success : function(result){
                            $('#loading-image').hide();
                            $('#is_end').remove();
                            $('#next_page_id').remove();
                            $(result).insertBefore("#load_more");
                            is_complete = true;
                            if(result.indexOf('id="no-more"') > 0){
                                $("#load_more").remove();
                                $('#loading-image').remove();
                                is_complete = false;
                            }

                        },
                        error : function(){
                            is_complete = true;
                        }

                    });
                }
            });

        });
    </script>

@stop()

