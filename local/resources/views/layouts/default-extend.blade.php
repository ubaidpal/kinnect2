<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="@if(isset($title))  {{$title}} @else Title @endif | Kinnect2" property="og:title">
    <meta content="Kinnect2"
          property="og:description">
    <meta content="{{url()}}" property="og:url">
    <meta content="{{asset('public/assets/immages/kinnect2-logo.png')}}" property="og:image">
    <link rel="icon" href="{!! asset('local/public/assets/images/favicon.ico') !!}" type="image/ico" sizes="16x16">
    <title>
        @if(isset($title))
            {{$title}}
        @else
            Title
        @endif
    </title>

    <link rel="stylesheet" href="{!! asset('local/public/assets/css/jplayer.blue.monday.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('local/public/assets/skin/functional.css') !!}">
    <link rel="stylesheet" href="{!! asset('local/public/assets/css/style.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('local/public/assets/css/jquery.bxslider.css') !!}">
    
    <script src="{!! asset('local/public/assets/js/jquery-2.1.3.js') !!}"></script>
    <script src="{!! asset('local/public/assets/js/custom-feedback.js') !!}"></script>
    <script src="{!! asset('local/public/assets/js/scroll-bar.js') !!}"></script>
    <script src="{!! asset('local/public/assets/js/store-admin.js') !!}"></script>
    <script src="{!! asset('local/public/assets/js/custom.js') !!}"></script>
    <script src="{!! asset('local/public/assets/js/script.js') !!}"></script>
    <script src="{!! asset('local/public/assets/js/jquery-1.10.2.min.js') !!}"></script>
    <script src="{!! asset('local/public/assets/js/popup.js') !!}"></script>
</head>
<body>
@include('includes.header')

@yield('content')
@yield('mvc-app')
@include('includes.footer')
@yield('footer-scripts')
@include('includes.feedback-leaderboard')

</body>
</html>
