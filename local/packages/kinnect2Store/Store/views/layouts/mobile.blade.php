<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=yes"/>

    <title>testing > login Page</title>
    <link rel="stylesheet" href="{!! asset('local/public/assets/css/style.mobile.css') !!}">
  </head>
    <body>
    	@include('includes.header-plane')
        
        @yield('content')
        
        @include('includes.footer')
  </body>
</html>