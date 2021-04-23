<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=yes"/>
    <link rel="icon" href="{!! asset('local/public/assets/images/favicon.ico') !!}" type="image/ico" sizes="16x16">

    <link rel="stylesheet" href="{!! asset('local/public/assets/css/style.mobile.css') !!}">

</head>
<body>
@yield('content')
<script src="{!! asset('local/public/assets/js/jquery-2.1.3.js') !!}"></script>
@yield('footer-scripts')
</body>
</html>
