<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Dashboard: {{ ucwords( Auth::user()->name ) }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" href="{!! asset('local/public/assets/css/style.css') !!}">
	    <link rel="stylesheet" href="{!! asset('local/public/assets/css/select2/select2.css') !!}">
    <link rel="stylesheet" href="{!! asset('local/public/assets/css/jplayer.blue.monday.min.css') !!}">


    <link rel="stylesheet" href="//releases.flowplayer.org/6.0.4/skin/functional.css">
    
    <link rel="stylesheet" type="text/css" href="{!! asset('local/public/assets/css/jquery-ui.min.css') !!}">

    <script src="{!! asset('local/public/assets/js/jquery-2.1.3.js') !!}"></script>

    <script src="{!! asset('local/public/assets/js/custom.js') !!}"></script>
    <script src="{!! asset('local/public/assets/js/custom-feedback.js') !!}"></script>
    <script src="{!! asset('local/public/assets/js/scroll-bar.js') !!}"></script>
    <script type="text/javascript" src='//code.jquery.com/jquery-1.10.2.min.js'></script>
    <script src="{!! asset('local/public/assets/js/popup.js') !!}"></script>


    <script src="//releases.flowplayer.org/6.0.4/flowplayer.min.js"></script>

</head>
<body>
@include('includes.header')

<div class="mainContainer">
    @include('includes.main-left-side')
    <div class="content">
        @yield('content')
    </div>
    @include('includes.ads-right-side')
</div>

@include('includes.footer')

@include('includes.feedback-leaderboard')
@yield('footer-scripts')
@yield('mvc-app')
@if(!$__env->yieldContent('mvc-app'))
    @include('includes.stand-alone-chat')
@endif
</body>
<script type="text/javascript">
    $(document).click(function (e)
    {
        var container = $("#popover");

        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0
            && container.is(':visible') 
            && !$(e.target).hasClass('social-share-post')) // ... nor a descendant of the container
        {
            container.hide();
        }
    });
</script>
</html>
