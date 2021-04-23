<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="{!! asset('local/public/assets/images/favicon.ico') !!}" type="image/ico" sizes="16x16">
    <title>testing > login Page</title>
    <link rel="stylesheet" href="{!! asset('local/public/assets/css/style.css') !!}">
  </head>
    <body>
    	@include('includes.header-plane')
        
        @yield('content')
        
        @include('includes.footer')
  </body>
</html>
