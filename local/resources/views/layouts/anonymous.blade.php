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
        <script src="{!! asset('/local/public/cropit/cropit.js') !!}"></script>
        <link href="{!! asset('local/public/assets/css/style.css') !!}" rel="stylesheet">
        <meta name="description"
              content="Re-imagined Social Media. Where opinions matter and brands listen. With Polls, Groups, Battles and Shopping all in one place."/>
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
        @yield("dom-head")
    </head>
    <body>
    	<div class="off-header-main">
            <div class="header-container">
            	<a class="logo" title="Kinnect2" href="{{url('/home')}}"></a>
                <!--  Login Form  -->
                <div class="login-first">
                	<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <table class="login-form" width="100%" cellpadding="0" cellspacing="0">
        
                        <tr>
                            <td width="180"><input type="text" placeholder="Email" name="email" value="{{ old('email') }}"></td>
                            <td width="180"><input type="password" name="password" placeholder="Password"></td>
                            <td><input type="submit" name="btn" class="inputBtn p5" value="Login" /></td>
                        </tr>
                        <tr>
                            <th colspan="3" class="login-error">
                                @if (Session::has('info'))
                                    {{Session::get('info')}}
                                @endif
        
                                @if(!isset($signup))
                                    <?php echo $errors->first('email'); ?>
                                    <?php echo $errors->first('password'); ?>
                                @endif
                            </th>
                        </tr>
                        <tr class="pt">
                            <td>
                                <input type="checkbox" id="remember-me" name="remember">
                                <label for="remember-me">Remember me</label>
                            </td>
                            <td colspan="2">
                                <a class="forgot-pass" href="{{ url('/password/email') }}">Forgot Your Password?</a>
                            </td>
                        </tr>
                    </table>
                </form>
                </div>
                <!--  Login Form - Ends -->
            </div>
        </div>
        </div>
        <div class="offline-post-container">
        	@yield('content')
		</div>
            @yield("scripts")
    </body>
</html>


