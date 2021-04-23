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
@include('includes.header')

@yield('content')

@yield('footer-scripts')

<script>
    window.onload = function() {
        $(".img-icm.setng").click(function(){
            if($('.mSideMenu').css("left") == "0px"){
                hideLeftMenu();
            }else{
                showLeftMenu();
            }
        });
        //Swipe
        var body = document.getElementsByTagName("body");

    };

    function showLeftMenu() {
        $(".mSideMenuOverLay").fadeIn().unbind("click").on("click", function () {
            hideLeftMenu();
        });
        $("body").addClass("left-menu-displayed");
        $('.mSideMenu').show().animate({"left": '0px'}).height($(window).height());
    }

    function hideLeftMenu(){
        $(".mSideMenuOverLay").fadeOut();
        $("body").removeClass("left-menu-displayed");
        $('.mSideMenu').animate({"left": '-250px'});
        setTimeout(function(){
            $('.mSideMenu').hide();
        }, 700)
    }
</script>
</body>
</html>
