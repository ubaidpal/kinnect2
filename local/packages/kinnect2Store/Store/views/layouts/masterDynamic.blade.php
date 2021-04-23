<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo ( isset($page_title) )? $page_title:'Kinnect2: Page Title' ?></title>
    <link rel="stylesheet" href="{!! asset('local/public/assets/css/style.css') !!}">
	<script src="{!! asset('local/public/assets/js/jquery-2.1.3.js') !!}"></script>



      <script>
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
      </script>
      @yield('header-styles')
    <script src="{!! asset('local/public/assets/js/custom-feedback.js') !!}"></script>
    <script src="{!! asset('local/public/assets/js/custom.js') !!}"></script> 
  </head>
    <body>
    
    	@include('includes.header')
		
        <div class="mainContainer">
			@yield('content')
     	</div>
        
        @include('includes.footer')
        
        @include('includes.feedback-leaderboard')
        @yield('footer-scripts')

    </body>
</html>
