<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="@if(isset($title))  {{$title}} @else Admin @endif | Kinnect2" property="og:title">
    <meta content="Kinnect2"
          property="og:description">
    <meta content="{{url()}}" property="og:url">
    <meta content="{{asset('public/assets/images/kinnect2-logo.png')}}" property="og:image">
    <link rel="icon" href="{!! asset('local/public/assets/images/favicon.ico') !!}" type="image/ico" sizes="16x16">
    <title>
        @if(isset($title))
            {{$title}}
        @else
            Admin
        @endif
    </title>

    <title><?php echo (isset($page_title)) ? $page_title : 'Kinnect2: Page Title' ?></title>
    <link rel="stylesheet" href="{!! asset('local/public/assets/css/style.css') !!}">
    <script src="{!! asset('local/public/assets/js/jquery-2.1.3.js') !!}"></script>
    {!! HTML::script('local/public/assets/admin/general.js') !!}
    {!! HTML::script('local/public/assets/js/popup.js') !!}
    @yield('header-styles')
</head>
<body>
@include('includes.admin-header')

<div class="adminContainer">
    @yield('content')
</div>

<?php /*@include('includes.footer')*/?>

{{--@include('includes.feedback-leaderboard')--}}
@yield('footer-scripts')


</body>
</html>
