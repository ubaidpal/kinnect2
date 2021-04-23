{{--

    * Created by   :  Muhammad Yasir
    * Project Name : kinnect2
    * Product Name : PhpStorm
    * Date         : 18-Feb-2016 7:54 PM
    * File Name    : 

--}}
@extends('layouts.default')
@section('content')
    {!! Form::open(['url' => 'messages/store', 'id' => 'msg-form','enctype'=>"multipart/form-data"]) !!}
    <input type="file" accept="" id="postFiles" name="attachment"
           style="position: fixed; top: -30px;"/>
    <div class="new-message-field" data-msg="1">
        <select id="friends" multiple="multiple" class="tokenize-sample" name="members[]">
            @foreach($friends as $row)
                <option value="{{$row->user_id}}">{{$row->displayname}}</option>
            @endforeach
        </select>
    </div>
    <!-- Type Text Area-->
    @include('messages.reply-form')
    {!! Form::close() !!}
@endsection
@section('footer-scripts')
    {!! HTML::script('/local/public/js/jquery.tokenize.js') !!}
    {!! HTML::script('local/public/assets/mobile-js/pages/messages.js') !!}
    <script>
        $(document).ready(function(){
            $('#friends').tokenize({
                placeholder : 'Type to select friend'
            });
        })
    </script>
@endsection
