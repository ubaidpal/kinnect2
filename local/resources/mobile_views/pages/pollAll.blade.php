@extends('layouts.default')
@section('content')
<!-- Sub Nav -->
<div class="sub-nav-container">
	<a class="sub-nav-item" href="javascript:void(0)">
		<div class="sub-nav-txt">All Polls</div>
	</a>
	<a class="sub-nav-item" href="javascript:void(0)">
		<div class="sub-nav-txt active">My Polls</div>
	</a>
	<a class="sub-nav-item" href="javascript:void(0)">
		<div class="sub-nav-txt">Recommended</div>
	</a>

	<div class="subnav-edit">
		<a class="btn-subnav-edit" href="javascript:void(0)"></a>

		<div class="subnav-edit-pp hide">
			<ul>
				<li><a class="subnav-pp-item" href="javascript:void(0)">Info</a></li>
				<li><a class="subnav-pp-item" href="javascript:void(0)">Videos</a></li>
				<li><a class="subnav-pp-item" href="javascript:void(0)">Albums</a></li>
				<li><a class="subnav-pp-item" href="javascript:void(0)">Edit Profile</a></li>
			</ul>
		</div>
	</div>
</div>

<!-- Comment Item -->
<div class="comment-item">
	<div class="comment-img">
		<a class="comnt-imgc" href="javascript:void(0)">
			<img src="{!! asset('local/public/assets/images_mobile/profile-img.jpg') !!}" alt="img">
		</a>
	</div>
	<div class="comment-txt">
		<div class="cmnt-title">
			<a href="javascript:void(0)">Which one is the best platform?</a>
		</div>
	</div>
</div>
<div class="comment-item">
	<div class="comment-img">
		<a class="comnt-imgc" href="javascript:void(0)">
			<img src="{!! asset('local/public/assets/images_mobile/profile-img.jpg') !!}" alt="img">
		</a>
	</div>
	<div class="comment-txt">
		<div class="cmnt-title">
			<a href="javascript:void(0)">Which one is the best platform?</a>
		</div>
	</div>
</div>
<div class="comment-item">
	<div class="comment-img">
		<a class="comnt-imgc" href="javascript:void(0)">
			<img src="{!! asset('local/public/assets/images_mobile/profile-img.jpg') !!}" alt="img">
		</a>
	</div>
	<div class="comment-txt">
		<div class="cmnt-title">
			<a href="javascript:void(0)">Which one is the best platform?</a>
		</div>
	</div>
</div>
<div class="comment-item">
	<div class="comment-img">
		<a class="comnt-imgc" href="javascript:void(0)">
			<img src="{!! asset('local/public/assets/images_mobile/profile-img.jpg') !!}" alt="img">
		</a>
	</div>
	<div class="comment-txt">
		<div class="cmnt-title">
			<a href="javascript:void(0)">Which one is the best platform?</a>
		</div>
	</div>
</div>
<div class="comment-item">
	<div class="comment-img">
		<a class="comnt-imgc" href="javascript:void(0)">
			<img src="{!! asset('local/public/assets/images_mobile/profile-img.jpg') !!}" alt="img">
		</a>
	</div>
	<div class="comment-txt">
		<div class="cmnt-title">
			<a href="javascript:void(0)">Which one is the best platform?</a>
		</div>
	</div>
</div>
@endsection
