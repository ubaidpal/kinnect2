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
    <meta name="description" content="Signup to Kinnect2 - Re-imagined Social Media. Where opinions matter and brands listen. With Polls, Groups, Battles and Shopping all in one place." />
      <meta content="{{asset('public/assets/images/kinnect2-logo.png')}}" property="og:image">
      <link rel="icon" href="{!! asset('local/public/assets/images/favicon.ico') !!}" type="image/ico" sizes="16x16">
    <title><?php echo ( isset($page_title) )? $page_title:'Kinnect2: Page Title' ?></title>
    <link rel="stylesheet" href="{!! asset('local/public/assets/css/style.css') !!}">
</head>
<body>

    @include('includes.header-plane')
    
    <div class="mainContainer min-height-auto">
         @yield('content')
    </div>
    @if(!$__env->yieldContent('mvc-app'))
        @include('includes.stand-alone-chat')
    @endif

    @include('includes.footer')
</body>
</html>
