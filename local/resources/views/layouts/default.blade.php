<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>@if(isset($page_Title))
            {{$page_Title }}
        @elseif(Auth::user()->user_type == \Config::get('constants.BRAND_USER'))
            Dashboard: {{ ucwords( Auth::user()->brand_name ) }}
        @else
            @if(!empty(Auth::user()->displayname))
                {{ ucwords( Auth::user()->displayname ) }}
            @elseif(!empty(Auth::user()->name) )
                {{ ucwords( Auth::user()->name ) }}
            @endif
        @endif</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <link rel="icon" href="{!! asset('local/public/assets/images/favicon.ico') !!}" type="image/ico" sizes="16x16">
    <link rel="stylesheet" href="{!! asset('local/public/assets/css/style.css') !!}">
    <link rel="stylesheet" href="{!! asset('local/public/assets/css/select2/select2.css') !!}">
    <link rel="stylesheet" href="{!! asset('local/public/assets/css/jplayer.blue.monday.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('local/public/assets/css/media_elements/mediaelementplayer.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('local/public/assets/css/media_elements/mejs-skins.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('local/public/assets/css/jquery-ui.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('local/public/assets/css/jquery.bxslider.css') !!}">

    <script src="{!! asset('local/public/assets/js/jquery-2.1.3.js') !!}"></script>

    <script src="{!! asset('local/public/assets/js/custom.js') !!}"></script>
    <script src="{!! asset('local/public/assets/js/custom-feedback.js') !!}"></script>
    <script src="{!! asset('local/public/assets/js/scroll-bar.js') !!}"></script>
    <script src="{!! asset('local/public/assets/js/jquery-1.10.2.min.js') !!}"></script>

    <script src="{!! asset('local/public/assets/js/popup.js') !!}"></script>

    
    @yield('header-styles')
</head>
<body>
<div class="">
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

</div>
</body>
<script type="text/javascript">
    $(document).click(function(e){
        var container = $("#popover");

        if(! container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0 && container.is(':visible') && ! $(e.target).hasClass('social-share-post')) // ... nor a descendant of the container
        {
            container.hide();
        }
    });
</script>

</html>
