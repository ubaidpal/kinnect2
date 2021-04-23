@extends('layouts.default')

@section('mvc-app')
    @include('includes.client-side-mvc')
@endsection




@section('content')

    <div id="mvc-main" data-screen="postDetail" data-options="{{ $post }}">

    </div>

    <div id="popup-wrapper"></div>
@endsection
