@extends('layouts.masterDynamic')
@section('content')
@include('includes.setting-left-nav')
        <!--Create Album-->

<div class="community-ad">
    <div class="form-container settings">

        {!! Form::open(array('url' => 'settings/generalSettingSave')) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="setting-title">
            <span>General Settings</span>
        </div>
 
        <div class="field-item">
            <label for="">Email Address</label>
            <input type="text" placeholder="paulsmith&commat;gmail.com" disabled  value="{{Request::old('email') ?: Auth::user()->email   }}">
        </div>

        <div class="field-item">
            <label for="">Profile Address</label>
            <input type="text" placeholder="paulsmith" disabled value="{{Request::old('first_name') ?: Auth::user()->name   }}">
        </div>

        <div class="field-item">
            {!! Form::label('Time Zone') !!}<br />
            <p class="col-dark mb10">Select the city closest to you that shares your same timezone.</p>
            {!!  Form::select('timezone', $timezonesList, $user->timezone, ['class' => 'form-control'])!!}
            @if($errors->first('timezone'))
                <span>{{ $errors->first('timezone') }}</span>
            @endif

        </div>
        @if ($current->user_type == Config::get('constants.REGULAR_USER'))
                @if(isset($consumer->birthdate) )
                <div class="field-item">
                    <label for="">Date of Birth</label>
                    <input type='text' class="form-control" id='datepicker' name="datepicker" value="{{$consumer->birthdate}}" />
                </div>
                @endif
        @endif

        <div class="save-changes">
            <input type="submit" class="btn" id="save" name="save" value="Save">
        </div>
        {!! Form::close() !!}
    </div>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<style>
    .ui-datepicker {
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.5);
        font: 9pt Arial,sans-serif;
        height: auto;
        margin: 5px auto 0;
        width: 327px;
    }
    .ui-datepicker a {
        text-decoration: none;
    }
    .ui-datepicker table {
        width: 100%;
    }
    .ui-datepicker-header {
        border-color: #111111;
        border-style: solid;
        border-width: 1px 0 0;
        box-shadow: 0 1px 1px 0 rgba(250, 250, 250, 0.2) inset;
        color: #ee4b08;
        font-weight: bold;
        line-height: 30px;

    }
    .ui-datepicker-title {
        text-align: center;
    }
    .ui-datepicker-prev, .ui-datepicker-next {
        background-repeat: no-repeat;
        cursor: pointer;
        display: inline-block;
        height: 30px;
        line-height: 600%;
        overflow: hidden;
        text-align: center;
        width: 30px;
    }
    .ui-datepicker-prev {
        background-position: center -30px;
        float: left;
    }
    .ui-datepicker-next {
        background-position: center 0;
        float: right;
    }
    .ui-datepicker thead {
        border-bottom: 1px solid #bbbbbb;
    }
    .ui-datepicker th {
        color: #ee4b08;
        font-size: 8pt;
        padding: 5px 0;
        text-shadow: 1px 0 0 #ffffff;
        text-transform: uppercase;
    }
    .ui-datepicker tbody td {
        border-right: 1px solid #bbbbbb;
        padding: 0;
    }
    .ui-datepicker tbody td:last-child {
        border-right: 0 none;
    }
    .ui-datepicker tbody tr {
        border-bottom: 1px solid #bbbbbb;
    }
    .ui-datepicker tbody tr:last-child {
        border-bottom: 0 none;
    }
    .ui-datepicker td span, .ui-datepicker td a {
        color: #666666;
        display: inline-block;
        font-weight: bold;
        height: 30px;
        line-height: 30px;
        text-align: center;
        text-shadow: 1px 1px 0 #ffffff;
        width: 30px;
    }
    .ui-datepicker-calendar .ui-state-default {
        background: #ededed;
        background: -moz-linear-gradient(top,  #ededed 0%, #dedede 100%);
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ededed), color-stop(100%,#dedede));
        background: -webkit-linear-gradient(top,  #ededed 0%,#dedede 100%);
        background: -o-linear-gradient(top,  #ededed 0%,#dedede 100%);
        background: -ms-linear-gradient(top,  #ededed 0%,#dedede 100%);
        background: linear-gradient(top,  #ededed 0%,#dedede 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ededed', endColorstr='#dedede',GradientType=0 );
        -webkit-box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);
        -moz-box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);
        box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);
    }
    .ui-datepicker-calendar .ui-state-hover {
        background: #f7f7f7 none repeat scroll 0 0;
    }
    .ui-datepicker-calendar .ui-state-active {
        background: #ee4b08 none repeat scroll 0 0;
        border: 1px solid #55838f;
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1) inset;
        color: #e0e0e0;
        margin: -1px;
        position: relative;
        text-shadow: 0 1px 0 #4d7a85;
    }
    .ui-datepicker-unselectable .ui-state-default {
        background: #f4f4f4 none repeat scroll 0 0;
        color: #b4b3b3;
    }
    .ui-datepicker-calendar td:first-child .ui-state-active {
        margin-left: 0;
        width: 29px;
    }
    .ui-datepicker-calendar td:last-child .ui-state-active {
        margin-right: 0;
        width: 29px;
    }
    .ui-datepicker-calendar tr:last-child .ui-state-active {
        height: 29px;
        margin-bottom: 0;
    }
</style>
<script>

    $(function(){
        $('#datepicker').datepicker({
            inline: true,
            showOtherMonths: true,
            dateFormat: 'yy-mm-dd' ,
            dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],

        });
    });
</script>

@endsection


