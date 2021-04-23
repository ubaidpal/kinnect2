<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=yes"/>
    <link rel="icon" href="{!! asset('local/public/assets/images/favicon.ico') !!}" type="image/ico" sizes="16x16">
    @yield('header-styles')
    <title>
        @if(isset($title))
            {{$title}}
        @else
            Kinnect2
        @endif
    </title>
    <link rel="stylesheet" href="{!! asset('local/public/assets/css/style.mobile.css') !!}">
    <script src="{!! asset('local/public/assets/mobile-js/jquery-2.1.3.js') !!}"></script>
    <script src="{!! asset('local/public/assets/mobile-js/jquery.nicescroll.min.js') !!}"></script>
    <script src="{!! asset('local/public/assets/mobile-js/custom.js') !!}"></script>

</head>
<body>

@yield('content')

</body>
</html>
