<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kinnect2: Login / Signup</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{!! asset('local/public/assets/images/favicon.ico') !!}" type="image/ico" sizes="16x16">
    <script src="{!! asset('local/public/js/jquery-2.1.3.js') !!}"></script>
    <link href="{!! asset('local/public/assets/css/style.css') !!}" rel="stylesheet">
    <meta name="description"
          content="Signup to Kinnect2 - Re-imagined Social Media. Where opinions matter and brands listen. With Polls, Groups, Battles and Shopping all in one place."/>
    <script>
        (function(i, s, o, g, r, a, m){
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function(){
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
            a = s.createElement(o), m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-73269595-1', 'auto');
        ga('send', 'pageview');

    </script>
</head>
<body class="login_background">
<!--  BG Slider  -->
<div class="container">
    <div class="login-page-wrapper">
    <?php 
	
	$parts = explode("/", $_SERVER['REQUEST_URI']);
		$cls = "";
		$lastParam = end($parts);
		if($lastParam == "ads" || $lastParam == "createPage"){
			$cls = "hide";
		}
	?>	
        <!--  Welcome Kinnect2  -->
        <div class="welcome <?php echo $cls;?>">
            <div class="logo_area">
                <span>Welcome to</span>

                <h1>Kinnect 2</h1>

                <div class="clrfix"></div>
                <h2>Bringing Brands &amp; Consumers Together.</h2>
            </div>
            <div class="slogans">
                
                <h3 class="openion">Opinions Matter &amp; Brands Listen.</h3>

                <h3 class="voice">One Voice. Many Brands.</h3>

                <h3 class="reimagined">Reimagined Social Media.</h3>
            </div>
        </div>
        <!--  Welcome Kinnect2 - Ends -->


        <!--  Login Container  -->
        @yield('content')
                <!--  Login Container - Ends -->

    </div>
    <!-- Login page footer-->
    <div class="login_footer_container">
        <div class="login_footer_links">
            <div class="company-name">&copy; 2014-{{date('y')}}</div>
            <a href="{{url('login/ads')}}">Create Ad</a>
            <a href="http://blog.kinnect2.com/">Blog</a>
            <a href="http://newsroom.kinnect2.com/">News</a>
            <a href="http://newsroom.kinnect2.com/about-us/">About</a>
            <a href="{{url('pages/help_center')}}">Help Center</a>
            <a href="{{url('policy/terms')}}">Terms</a>
            <a href="{{url('policy/condition')}}">Privacy</a>
            <a href="{{url('policy/condition#cookies')}}">Cookies</a>
        </div>
    </div>
</div>

</body>
</html>


