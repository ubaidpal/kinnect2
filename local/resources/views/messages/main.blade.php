{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 06-1-16 11:48 AM
    * File Name    :

--}}
@extends('layouts.masterDynamic')
@section('content')
    @include('includes.discussions-leftbar')
    <div class="content">
        <?php $conv_for = '';?>
        <div class="content-gray-title mb10">
            @if(isset($first_conversation) || !empty($first_conversation))
                <?php
                $participant = $first_conversation->getAllParticipants();
                $userForChat = array_diff($participant, [Auth::user()->id]);
                $type = $conv_data[$first_conversation->getId()]->type;
                $conv_for = $conv_data[$first_conversation->getId()]->conv_for;
                ?>
                @if($type == 'couple' || $conv_for == 'dispute')

                    <?php  $participant = $first_conversation->getTheOtherParticipant($user_id) ?>
                    @if(isset($users[$participant]))
                        <h4 class="message-thread-title">{{$users[$participant]->displayname}}</h4>
                    @endif
                @else
                    <?php
                    $members_name = array();
                    $userForChat = [];
                    $participants = array_diff($participant, [$user_id]);
                    ?>
                    <div id="rename" class="rename-tooltip" style="display: none">
                        {!! Form::open(['url' => 'messages/rename-conversation', 'id' => 'rename-conversation']) !!}
                        {!! Form::text('name', $conv_data[$conv_id]->title,['class' => 'rename-conv','title' => 'Press enter to Save or Cancel']) !!}
                        {!! Form::hidden('conv_id', $conv_id,['class' => 'conv-id']) !!}
                        {!! Form::close() !!}
                    </div>
                    <h4 class=" @if($type == 'group') edit @endif message-thread-title" id="trigger">
                        {{get_chat_group_name($conv_data,$first_conversation->getId(), $participants, $users)}}
                    </h4>
                @endif
                <div class="conv-chat-trigger" data-type="{{$type}}" data-user="{{implode($userForChat)}}"
                     data-group="{{$conv_id}}"></div>
                {!! Form::hidden('user', implode($userForChat),['id'=>'userForChat']) !!}
                {!! Form::hidden('group', $conv_id,['id'=>'groupId']) !!}
                {!! Form::hidden('type', $type,['id'=>'chat_type']) !!}
            @endif
            <a href="javascript:void(0);" title="New Message" id="new-message" class="btn fltR message-btn">
                New Message
            </a>
            @if($conv_for != 'dispute')
                <a href="javascript:void(0);" title="Open in Chat" class="btn fltR mr5 chat-trigger message-btn">
                    Open in Chat
                </a>
            @endif
            <a href="javascript:void(0);" title="Close" id="close-new-message" class="btn fltR mr5 message-btn"
               style="display: none">Close</a>
        </div>
        <div id="loading" style="text-align: center; display: none">
            <!--<img id="loading-image" src="{!! asset('local/public/images/loading.gif') !!}" alt="Loading..."/>-->
             <div class="loader bubblingG mt20">
                <span id="bubblingG_1"></span>
                <span id="bubblingG_2"></span>
                <span id="bubblingG_3"></span>
            </div>
        </div>
        {!! Form::open(['url' => 'messages/store', 'id' => 'msg-form','enctype'=>"multipart/form-data"]) !!}
        <div class="new-message-field hide" id="all-friends" style="display: none">
            <select id="friends" multiple="multiple" class="tokenize-sample" name="members[]">
                @foreach($friends as $row)
                    <option value="{{$row->user_id}}">{{$row->displayname}}</option>
                @endforeach
            </select>
        </div>
        <div id="conversation-messages" class="conversation-messages-update">

            @if(isset($messages) || !empty($messages))
                <?php $current_sender = 0;?>
                @foreach($messages as $row)
                    <?php
                    if($current_sender == 0 || $current_sender != $row->getSender()) {
                        $current_sender = $row->getSender();
                    }

                    ?>
                    @include('templates.partials.ajax.conversation-messages-all',['row' => $row])

                @endforeach
            @else
                There is no message
            @endif
        </div>
        <div class="reply-discussions " @if(!isset($messages))  style="display: none" @endif>
            <div class="cht-attachment" id="chat-attachment">
                <a href="javascript:void(0)"></a>
            </div>
            <div class="form-box" id="form-box">

                <input type="file" accept="" id="postFiles" name="attachment"
                       style="position: fixed; top: -30px;"/>
                {!! Form::hidden('conv_id', $conv_id,['class' => 'conv-id']) !!}
                {!! Form::hidden('is_message', 1,[]) !!}


                <div class="cht-text">
                    <div id="loading-2" style="text-align: center; display: none; margin:52px 0 0 30px;">
                        <!--<img id="loading-image" src="{!! asset('local/public/images/loading.gif') !!}" alt="Loading..."/>-->
                        <div class="loader bubblingG mt10">
                            <span id="bubblingG_1"></span>
                            <span id="bubblingG_2"></span>
                            <span id="bubblingG_3"></span>
                        </div>
                    </div>
                    <textarea id="msg-body" placeholder="Write a message" name="body"></textarea>
                </div>

                <div class="cht-send">
                    <a href="javascript:void(0)"></a>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <input type="hidden" id="notification_last_update_time" value="{{date('Y-m-d H:i:s')}}">
    <input type="hidden" id="old_conversation_id">
    @include('includes.ads-right-side')
@endsection
@section('footer-scripts')
    <style>
        div.Tokenize {
            display: inline-block;
            position: relative;
            width: 100%;
        }

        div.Tokenize {
            background: #d8d8d8 none repeat scroll 0 0;
            left: 0;
            padding-bottom: 5px;
            position: relative !important;
            top: 0 !important;
            width: 100%;
        }
    </style>

    <!--{!! HTML::style('/local/public/css/jquery.tokenize.css') !!}-->
    {!! HTML::script('/local/public/js/jquery.tokenize.js') !!}
    {!! HTML::script('local/public/assets/js/pages/messages.js') !!}
    {!! HTML::script('local/public/js/mvc/mrb/public/js/libs/mediaelement-and-player.min.js') !!}
    <link rel="stylesheet" href="{!! asset('local/public/assets/css/media_elements/mediaelementplayer.min.css') !!}">
    <script type="text/javascript">
        jQuery('video').mediaelementplayer();
//        jQuery(document).on('click',)
    </script>
@endsection