@extends('layouts.default')

@section('mvc-app')
@include('includes.client-side-mvc')
@show

@section('content')
        <!-- Post Div-->
@include('includes.group-detail')
<div id="whats-new" class="target">
<div id="mvc-main" data-screen="groupProfile">

</div>
</div>
@include('group.profile-view-links')
@endsection
@section('footer-scripts')
    <style>
        .target.hide {
            display: none;
        }
    </style>
    <script src="{!! asset('local/public/assets/js/inner-pages.js') !!}"></script>
@endsection
