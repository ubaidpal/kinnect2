<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta content="@if(isset($title))  {{$title}} @else Title @endif | Kinnect2" property="og:title">
      <meta content="Kinnect2"
            property="og:description">
      <meta content="{{url()}}" property="og:url">
      <meta content="{{asset('public/assets/images/kinnect2-logo.png')}}" property="og:image">
	  <link rel="icon" href="{!! asset('local/public/assets/images/favicon.ico') !!}" type="image/ico" sizes="16x16">
      <title>
          @if(isset($title))
              {{$title}}
          @else
              Title
          @endif
      </title>

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
    	@include('includes.admin-header')
		
        <div class="adminContainer">
			@yield('content')
     	</div>
        
        @include('includes.footer')
        
        @include('includes.feedback-leaderboard')
        @yield('footer-scripts')
        @yield('mvc-app')
        @if(!$__env->yieldContent('mvc-app'))
            @include('includes.stand-alone-chat')
        @endif

    </body>
</html>
