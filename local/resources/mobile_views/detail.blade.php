@extends('layouts.default')

@section('mvc-app')
@include('includes.client-side-mvc')
@show

@section('content')
        <!--Middle Content-->

<?php

$parts = explode("/", $_SERVER['REQUEST_URI']);

?>
<link rel="stylesheet" href="{!! asset('local/public/assets/css/jquery.bxslider.css') !!}">
<div id="mvc-main" data-screen="showPostDetail" data-post="{!! end($parts) !!}">

</div>
<div style="text-align: center;display: none;" id="page_loader">
    <img src="{!! asset('local/public/images/loading.gif') !!}">
</div>
<div style="text-align: center;display: none;" id="page_end_message"></div>

<div id="popup-wrapper" style="display: none;"></div>

@endsection
