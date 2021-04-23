@extends((Auth::check()) ? 'layouts.default' : 'layouts.signup')
@section('content')
    <div class="error-container">
        <div class="error_img"><img src="{!! asset('local/public/assets/images_mobile/content.svg') !!}" alt=""/></div>
        <h2>404</h2>
        <h2>Page not found</h2>

        <p>The link you followed probably broken, or page might have been removed, had its name changed, or is temporarily unavailable </p>
        <div class="link404">
            <a class="l404" href="{{url('/')}}">Take me out of here</a>
        </div>
    </div>

@endsection
