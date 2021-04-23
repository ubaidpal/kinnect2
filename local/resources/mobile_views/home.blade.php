@extends('layouts.default')

@section('mvc-app')
@include('includes.client-side-mvc')
@show

@section('content')
<!--Middle Content-->
<div id="mvc-main" data-screen="dashboard">

</div>
<div style="text-align: center;display: none;" id="page_loader">
    <img src="{!! asset('local/public/images/loading.gif') !!}">
</div>
<div style="text-align: center;display: none;" id="page_end_message"></div>

<div id="popup-wrapper" style="display: none;"></div>
@endsection
