@extends('layouts.default')

@section('mvc-app')
@include('includes.client-side-mvc')
@endsection

@section('content')
        <!-- Post Div-->
<style>
    .target.hide {
        display: none;
    }
</style>
@include('includes.group-detail')
@include('includes.groups-right-panel')
<div id="whats-new" class="target">
<div id="mvc-main" data-screen="groupProfile">

</div>
</div>
@include('group.profile-view-links')
@endsection
@section('footer-scripts')

    <script src="{!! asset('local/public/assets/js/inner-pages.js') !!}"></script>
@endsection
