{{--

    * Created by   :  Muhammad Yasir
    * Project Name : kinnect2
    * Product Name : PhpStorm
    * Date         : 18-Feb-2016 1:22 PM
    * File Name    : 

--}}
@extends('layouts.default')
@section('content')
<?php //echo '<tt><pre>'; print_r($messages); die; ?>

        <!-- Messages -->
<div class="new-message-field">
    <div class="tokenize-sample Tokenize">
        {{$title}}

    </div>
</div>

<!-- Conversation Area-->
<div id="loading" style="text-align: center; display: none">
    <img id="loading-image" src="{!! asset('local/public/images/loading.gif') !!}" alt="Loading..."/>
</div>
<div class="conv-container" id="conversation-messages">
    @if(isset($messages) || !empty($messages))
        @foreach($messages as $row)
            <div class="@if($row['sender_id'] == $user_id) conversation-rtl @else conversation-ltr @endif">
                <div class="picA">
                    @if($row['sender_id'] == $user_id)
                        <?php
                        $url = Kinnect2::profileAddress($user);
                        $image = Kinnect2::getPhotoUrl($user->photo_id, $user->id, 'user', 'thumb_normal');
                        $name = $user->displayname;
                        ?>

                    @else
                        <?php $url = $row['sender_url'];
                        $image = $row['profile_pic'];
                        $name = $row['sender_name'];
                        ?>
                    @endif
                    <a href="{{$url}}">
                        <img src="{{$image}}" width="48" height="48" alt="image">
                    </a>
                </div>
                <div class="msgA">
                    <a href="{{$url}}" title="{{$name}}">
                        {{$name}}
                    </a>

                    <div class="clrfix"></div>
                    <div class="bubble" data-id="42">
                        <div class="mesg">
                            <?php
                            $string = filter_var($row['content'], FILTER_VALIDATE_URL);
                            ?>
                            @if($string)
                                <?php
                                $meta = extractLinkMeta($row['content']);
                                //echo '<tt><pre>'; print_r($meta);
                                ?>
                                <a href="{{$row['content']}}">{{$meta['title']}}</a><br>
                                @if(!empty($meta['images']))
                                    <img class="link-img" height="" width="200" src="{{$meta['images'][0]}}">

                                @endif
                                {{$meta['description']}}
                            @else
                                {{$row['content']}}
                            @endif
                            @if(isset($row['file_name']))
                                <span class="attachment-icon"></span>
                                <div class="linkDownload">
                                    <span class="attachment-name">{{$row['file_name']}}</span>
                                <span class="attachment-url">
                                    <a href="{{$row['url']}}" download="">Download</a></span>
                                </div>

                            @endif
                        </div>
                    <span class="msg-time">
                        {{getTimeByTZ($row['created_at'], 'M d | h:i a')}}
                    </span>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        There is no message
    @endif
</div>

<!-- Type Text Area-->

{!! Form::open(['url' => 'messages/store', 'id' => 'msg-form','enctype'=>"multipart/form-data"]) !!}
<input type="file" accept="" id="postFiles" name="attachment"
       style="position: fixed; top: -30px;"/>
{!! Form::hidden('conv_id', $conversation->id,['class' => 'conv-id']) !!}
{!! Form::hidden('is_message', 1,[]) !!}
@include('messages.reply-form')
{!! Form::close() !!}
@endsection
@section('footer-scripts')
    {!! HTML::script('local/public/assets/mobile-js/pages/messages.js') !!}
    <style>
      .conv-container{
          overflow: auto;
      }
    </style>
@endsection
