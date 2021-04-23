@extends('layouts.default')

@section('mvc-app')
@include('includes.client-side-mvc')
@endsection


@section('content')
        <!--Middle Content-->

<div id="mvc-main" data-screen="dashboard" data-hashtag="{{$hashTag}}">

</div>
<div style="text-align: center;display: none;" id="page_loader" class="mb20 pb10">
    <div class="loader bubblingG mt10">
    	<span id="bubblingG_1"></span>
        <span id="bubblingG_2"></span>
        <span id="bubblingG_3"></span>
    </div>
</div>
<div style="text-align: center;display: none;" id="page_end_message"></div>

<div id="popup-wrapper" style="display: none;"></div>
@endsection
