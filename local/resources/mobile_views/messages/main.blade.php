@extends('layouts.default')
@section('content')
    <?php //echo '<tt><pre>'; print_r($conversations); die; ?>
    <div class="title-bar">
        <span>Messages</span>
    </div>
    <div class="compose-msg">
        <a href="{{url('messages/new-thread')}}" class="btn"><span class="icon create">New Message</span></a>
    </div>
    <div class="threads">
        @foreach($conversations as $row)
            <div class="cursor_pointer usr-msg-item conversation active" id="conv-{{$row['id']}}">
                <a href="{{url('messages/'.$user->username.'/'.$row['id'])}}">
                    <div class="usr-msg-block">
                        <div class="usr-msg-img">
                            @if($row['type'] == 'couple')
                                @foreach($row['participants'] as $member)
                                    <img src="{{$member['profile_pic']}}" alt="{{$member['name']}}"
                                         title="{{$member['name']}}">
                                @endforeach
                            @else
                                @foreach($row['participants'] as $member)
                                    <img src="{{$member['profile_pic']}}" alt="{{$member['name']}}"
                                         title="{{$member['name']}}">
                                @endforeach
                            @endif
                        </div>
                        <div class="usr-msg-content">
                            <p title="Kinnect, Horia" class="usr-msg-title courser_pointer">
                                @if($row['type'] == 'couple')
                                    @foreach($row['participants'] as $member)
                                        {{$member['name']}}
                                    @endforeach
                                @else
                                    {{$row['title']}}
                                @endif

                            </p>

                            <p class="usr-msg-txt">{{$row['last_message']}}</p>
                        </div>
                    </div>
                    <p class="unread"></p>

                    <div class="usr-msg-timing">
                        {{\Carbon\Carbon::parse($row['time'])->diffForHumans()}}
                    </div>
                </a>
            </div>
        @endforeach
    </div>

@endsection

