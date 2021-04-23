<div class="conv-for messages-conv ">
    <div id="conv-{{$conv_detail->id}}" data-url="{{url('messages/'.$user->username.'/'.$conv_detail->id)}}"
         class="cursor_pointer usr-msg-item conversation  active">
        @foreach($messages as $row)
            <div class="usr-msg-block">

                <div class="usr-msg-img">
                    <a href="javascript:void(0)">
                        @if($conv_detail->type == 'couple')
                            <?php
                            $participant = $members[0];

                            ?>
                            <img src="@if(isset($users[$participant])){{Kinnect2::getPhotoUrl($users[$participant]->photo_id, $users[$participant]->id, 'user', 'thumb_normal')}}@endif"
                                 alt="img">
                        @else
                            <?php
                            //$participant = $row->getAllParticipants();
                            $participant = $users_id;
                            //echo '<tt><pre>'; print_r($participant); die;
                            $participants = array_diff($participant, [$user_id]);
                            $members_name = $participants;
                            if (count($participants) > 1) {
                                $members_name = array_slice($participants, 0, 2);
                            }

                            ?>
                            @if(count($participant) >= 4)
                                <img width="55" height="55"
                                     title="{{$conv_detail->title}}"
                                     alt="Group"
                                     src="{{Kinnect2::getPhotoUrl(0, 0, 'group', 'group_thumb')}}">
                            @else
                                @foreach($members_name as $participant)
                                    <img width="@if(count($participants) > 1) 50% @endif"
                                         style="float: left; height: 100%;"
                                         src="@if(isset($users[$participant])){{Kinnect2::getPhotoUrl($users[$participant]->photo_id, $users[$participant]->id, 'user', 'thumb_normal')}}@endif"
                                         alt="img">
                                @endforeach
                            @endif

                        @endif
                    </a>
                </div>

                <div class="usr-msg-content">
                    <p class="usr-msg-title courser_pointer" title="{{$conv_detail->title}}">
                        @if($conv_detail->type == 'group')
                            {{$conv_detail->title}}
                        @else
                            @if(isset($users[$participant]))
                                {{$users[$participant]->displayname}}
                            @endif
                        @endif
                    </p>

                    <p class="usr-msg-txt">
                        {{$body}}
                    </p>


                </div>
            </div>

            <div class="usr-msg-timing">
                <?php

                /*$created = $row->getCreated();

                 $year = Carbon\Carbon::parse($created)->format('Y');
                 $month = Carbon\Carbon::parse($created)->format('m');
                 $day = Carbon\Carbon::parse($created)->format('d');
                 $hour = Carbon\Carbon::parse($created)->format('H');
                 $min = Carbon\Carbon::parse($created)->format('i');
                 $sec = Carbon\Carbon::parse($created)->format('s');
                 $time = \Carbon\Carbon::create($year, $month, $day, $hour, $min, $sec);
                 echo $time->diffForHumans();*/
                ?>
                1 second ago
                {{-- <span>May 25</span>
                 <span>09:00am</span>--}}
            </div>
            <?php break; ?>
        @endforeach
        @if($conv_detail->type == 'group')
            <div class="leave-conversation btn-user-del"
                 data-url="{{url('messages/leave-group/'.$conv_detail->id)}}">
                <a href="javascript:void(0)" title="Leave Group"></a>
            </div>
        @endif
    </div>
</div>
