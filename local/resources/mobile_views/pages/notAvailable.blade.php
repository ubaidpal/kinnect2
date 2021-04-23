@extends('layouts.default')
@section('content')
	<div class="error-container">
   		<div class="error_img"><img src="{!! asset('local/public/assets/images_mobile/content.svg') !!}" alt="" /></div>
        <h2>Sorry this content isn't available</h2>
        <p>The link you followed may be broken, or the page may have been removed.</p>
    </div>
@endsection
