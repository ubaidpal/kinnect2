<div class="leftPnl" id="stick">
    <div class="searchDisc">


        @if(getenv('STORE_ENABLED') == 'true')
            <div class="search-filter-tabs">
                <a title="Messages" href="javascript:void(0);" class="active msg-filter" data-type="messages-conv">
                    Messages <span>(<em>@if(isset($message_count)) {{$message_count}} @else 0 @endif</em>)</span>
                </a>
                |
                <a title="Orders" href="javascript:void(0);" class="msg-filter" data-type="dispute">
                    Orders <span>(<em>@if(isset($dispute_count)) {{$dispute_count}} @else 0 @endif</em>)</span>
                </a>

                {{--| <a href="javascript:void(0);" class="msg-filter" data-type="order-conv">
                    Orders <span>(<em>0</em>)</span>
                </a>--}}
            </div>
        @endif
        <form method="" action="" id="search-form">
            <input type="text" placeholder="Search messages" alt="" maxlength="100" size="20" name=""
                   class="search" id="search-conversation" autocomplete="on">
            <a href="javascript:void(0);" title="search" class="sIcon"></a>
        </form>
        <div class="conv-box">
            <?php  $order_count = 0;?>
            @if(isset($message_count) &&  $message_count == 0)
                <div class="user-msgs-box messages-conv conv-for">
                    <h3>You Have no conversation</h3>
                </div>
            @elseif(isset($dispute_count) && $dispute_count == 0)
                <div class="user-msgs-box dispute conv-for hide">
                    <h3>You Have no conversation</h3>
                </div>
            @elseif($order_count == 0)
                <div class="user-msgs-box order-conv conv-for hide">
                    <h3>You Have no conversation</h3>
                </div>
            @endif
            @if(isset($conversation) && count($conversation) > 0)

                @foreach($conversation as $row)

                    <?php
                    $type = $conv_data[$row->getId()]->type;
                    $conv_for = $conv_data[$row->getId()]->conv_for;

                    ?>
                    <div class="conv-for @if($conv_for == 'dispute') dispute hide @elseif($conv_for == 'messages' || is_null($conv_for) || $conv_for == '0') messages-conv @endif">
                        <div id="conv-{{$row->getId()}}"
                             data-url="{{url('messages/'.$user->username.'/'.$row->getId())}}"
                             class="cursor_pointer usr-msg-item conversation @if($conv_id == $row->getId()) active @endif ">
                            <div class="usr-msg-block">
                                <div class="usr-msg-img">
                                    <a href="javascript:void(0)">
                                        @if($type == 'couple' || $conv_for == 'dispute')
                                            <?php
                                            $participant = $row->getTheOtherParticipant($user_id);
                                            ?>
                                            <img src="@if(isset($users[$participant])){{Kinnect2::getPhotoUrl($users[$participant]->photo_id, $users[$participant]->id, 'user', 'thumb_normal')}}@endif"
                                                 alt="img">
                                        @else
                                            <?php
                                            //$participant = $row->getAllParticipants();
                                            $participant = get_participant($row->getId());
                                            //echo '<tt><pre>'; print_r($participant); die;
                                            $participants = array_diff($participant, [$user_id]);
                                            $members_name = $participants;
                                            if(count($participants) > 1) {
                                                $members_name = array_slice($participants, 0, 2);
                                            }

                                            ?>

                                            @if(count($participant) >= 4)
                                                <img width="55" height="55"
                                                     title="{{get_chat_group_name($conv_data,$row->getId(), $participants, $users)}}"
                                                     alt="Group"
                                                     src="{{Kinnect2::getPhotoUrl(0, 0, 'group', 'group_thumb')}}">
                                            @else
                                                @foreach($members_name as $participant)
                                                    <?php $src = '';?>
                                                    @if(isset($users[$participant]))
                                                        <?php

                                                        $src = Kinnect2::getPhotoUrl($users[$participant]->photo_id, $users[$participant]->id, 'user', 'thumb_normal');
                                                        if($src) {
                                                            $src = str_ireplace(".svg", ".png", $src);
                                                        } else {
                                                            $src = '';
                                                        }

                                                        ?>
                                                    @endif
                                                    <img width="@if(count($participants) > 1) 50% @endif"
                                                         style="float: left; height: 100%;"
                                                         src="{{$src}}"
                                                         alt="img">
                                                @endforeach
                                            @endif

                                        @endif
                                    </a>
                                </div>

                                <div class="usr-msg-content">
                                    <p class="usr-msg-title courser_pointer"
                                       title="{{get_chat_group_name($conv_data,$row->getId(), $participants, $users)}}">

                                        @if($type == 'couple' || $conv_for == 'dispute')
                                            <?php
                                            $lastMessage = $row->getLastMessage();
                                            $senderId = $lastMessage->getSender();
                                            $status = $lastMessage->getStatus();
                                            ?>
                                            @if(isset($users[$participant]))
                                                {{$users[$participant]->displayname}}
                                            @endif


                                        @else
                                            <?php
                                            $members_name = array();
                                            ?>

                                            {{get_chat_group_name($conv_data,$row->getId(), $participants, $users)}}
                                        @endif

                                    </p>

                                    <p class="usr-msg-txt">
                                        <?php
                                        $lastMessage = $row->getLastMessage();
                                        echo $content = $lastMessage->getContent();
                                        ?>
                                    </p>


                                </div>
                            </div>
                            <p class="unread">
                                @if($row->getUnreadCount($row->getId()) > 0)
                                    Unread: {{$row->getUnreadCount($row->getId())}}
                                @endif
                            </p>

                            <div class="usr-msg-timing">
                                <?php
                                $created = $lastMessage->getCreated();
                                $year = Carbon\Carbon::parse($created)->format('Y');
                                $month = Carbon\Carbon::parse($created)->format('m');
                                $day = Carbon\Carbon::parse($created)->format('d');
                                $hour = Carbon\Carbon::parse($created)->format('H');
                                $min = Carbon\Carbon::parse($created)->format('i');
                                $sec = Carbon\Carbon::parse($created)->format('s');
                                $time = \Carbon\Carbon::create($year, $month, $day, $hour, $min, $sec);
                                echo $time->diffForHumans();
                                ?>
                                {{-- <span>May 25</span>
                                 <span>09:00am</span>--}}
                            </div>
                            @if($type == 'group' && $conv_for == 'messages')
                                <div class="leave-conversation btn-user-del"
                                     data-url="{{url('messages/leave-group/'.$row->getId())}}">
                                    <a href="javascript:void(0)" title="Leave Group" class="leave-conv-a"></a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

            @else
                <div class="user-msgs-box" id="no-message">
                    <h3>You Have no conversation</h3>
                </div>
            @endif
        </div>
    </div>
</div>
