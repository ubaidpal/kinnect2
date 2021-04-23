@extends('layouts.default')
@section('content')
<!-- Title Bar -->
<div class="title-bar">
	<span>Polls</span>
</div>

<!-- Post Start From Here -->
<div class="post-container">
	<div class="post-wrapper">
		<!-- Post Header -->
		<header class="post-header">
			<!-- Post Profile-Image -->
			<div class="post-hdr-img">
				<a href="javascript:void(0)">
					<img src="{!! asset('local/public/assets/images_mobile/post-title-img.jpg') !!}" alt="img">
				</a>
			</div>

			<!-- Post Text Content -->
			<div class="post-hdr-content">
				<div class="post-hdr-title">
					<h3 class="hdr-txt-container">
						<a class="hdr-txt" href="javascript:void(0)">Post Title</a>
					</h3>
				</div>

				<div class="post-hdr-date">
					<span>Tuseday at 10:00pm</span>
				</div>
			</div>

			<!-- Post Delete btn -->
			<div class="post-del-btn">
				<a class="btn-del-post" href="javascript:void(0)"></a>
			</div>
		</header>

		<!-- Post Story -->
		<div class="post-story-centainer">
			<p class="post-story-txtb">
				Technology
			</p>
			<p class="post-story-txt">
				Lorem ipsum dolor sit amet.
			</p>
		</div>

		<!-- Progress Bar -->
		<div class="prog-bar-item">
			<div class="prog-bar-title">
				<h3>Blue Orca Studios</h3>
			</div>
			<div class="prog-bar-votes">
				<p>Votes: 15</p>
			</div>
			<div class="progress-bar">
			  <span class="color-1" style="width: 25%"></span>
			</div>
		</div>
		<!-- Progress Bar -->
		<div class="prog-bar-item">
			<div class="prog-bar-title">
				<h3>Blue Orca Studios</h3>
			</div>
			<div class="prog-bar-votes">
				<p>Votes: 15</p>
			</div>
			<div class="progress-bar">
			  <span class="color-2" style="width: 25%"></span>
			</div>
		</div>
		<!-- Progress Bar -->
		<div class="prog-bar-item">
			<div class="prog-bar-title">
				<h3>Blue Orca Studios</h3>
			</div>
			<div class="prog-bar-votes">
				<p>Votes: 15</p>
			</div>
			<div class="progress-bar">
			  <span class="color-3" style="width: 25%"></span>
			</div>
		</div>


		<!-- Feeds Container -->
		<footer class="feed-container">
			<!-- Likes/Dislikes Feeds -->
			<div class="feed-block">
				<div class="feed-item">
					<a class="btn-feed-item like" href="javascript:void(0)"></a>
				</div>

				<div class="feed-item">
					<a class="btn-feed-item dislike" href="javascript:void(0)"></a>
				</div>

				<div class="feed-item">
					<a class="btn-feed-item favorite" href="javascript:void(0)"></a>
				</div>
				
				<div class="feed-item">
					<a class="btn-feed-item comment" href="javascript:void(0)"></a>
				</div>

				<div class="feed-item">
					<a class="btn-feed-item share" href="javascript:void(0)"></a>
				</div>
				
				<div class="feed-item">
					<a class="btn-feed-item social" href="javascript:void(0)"></a>
				</div>

				<div class="feed-item">
					<a class="btn-feed-item flag" href="javascript:void(0)"></a>
				</div>
			</div>

			<!-- Post Feeds Detail Container -->
			<div class="feeds-detail-container">
				<span class="feed-detail-item">50 Likes</span>
				<span class="feed-detail-item">0 Dislikes</span>
				<span class="feed-detail-item">20 Comments</span>
				<span class="feed-detail-item">0 Shares</span>
			</div>
		</footer>
	</div>
</div>
<!-- Comment Item -->
<div class="comment-item">
	<div class="comment-img">
		<!-- Round Image -->
		<a class="comnt-imgc" href="javascript:void(0)">
			<img src="{!! asset('local/public/assets/images_mobile/profile-img.jpg') !!}" alt="img">
		</a>
		<!-- Rectangle Image -->
		<!-- <a class="comnt-imgr" href="javascript:void(0)">
			<img src="../webroot/images/profile-img.jpg" alt="img">
		</a> -->
	</div>
	<div class="comment-txt">
		<div class="cmnt-title">
			<a href="javascript:void(0)">Rose</a>
		</div>
		<div class="cmnt-text">
			<p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit.</p>
		</div>
		<div class="comment-footer">
			<div class="comment-time">
				<span>10 minutes ago</span>
			</div>
			<div class="comment-btn">
				<a href="javascript:void(0)">Delete</a>
			</div>
			<div class="comment-btn">
				<a href="javascript:void(0)">Unlike</a>
			</div>
			<div class="comment-btn">
				<a href="javascript:void(0)">Like</a>
			</div>
		</div>
	</div>
</div>
<!-- Comment Item -->
<div class="comment-item">
	<div class="comment-img">
		<!-- Round Image -->
		<a class="comnt-imgc" href="javascript:void(0)">
			<img src="{!! asset('local/public/assets/images_mobile/profile-img.jpg') !!}" alt="img">
		</a>
		<!-- Rectangle Image -->
		<!-- <a class="comnt-imgr" href="javascript:void(0)">
			<img src="../webroot/images/profile-img.jpg" alt="img">
		</a> -->
	</div>
	<div class="comment-txt">
		<div class="cmnt-title">
			<a href="javascript:void(0)">Rose</a>
		</div>
		<div class="cmnt-text">
			<p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit.</p>
		</div>
		<div class="comment-footer">
			<div class="comment-time">
				<span>10 minutes ago</span>
			</div>
			<div class="comment-btn">
				<a href="javascript:void(0)">Delete</a>
			</div>
			<div class="comment-btn">
				<a href="javascript:void(0)">Unlike</a>
			</div>
			<div class="comment-btn">
				<a href="javascript:void(0)">Like</a>
			</div>
		</div>
	</div>
</div>
<!-- Comment Item -->
<div class="comment-item">
	<div class="comment-img">
		<!-- Round Image -->
		<a class="comnt-imgc" href="javascript:void(0)">
			<img src="{!! asset('local/public/assets/images_mobile/profile-img.jpg') !!}" alt="img">
		</a>
		<!-- Rectangle Image -->
		<!-- <a class="comnt-imgr" href="javascript:void(0)">
			<img src="../webroot/images/profile-img.jpg" alt="img">
		</a> -->
	</div>
	<div class="comment-txt">
		<div class="cmnt-title">
			<a href="javascript:void(0)">Rose</a>
		</div>
		<div class="cmnt-text">
			<p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit.</p>
		</div>
		<div class="comment-footer">
			<div class="comment-time">
				<span>10 minutes ago</span>
			</div>
			<div class="comment-btn">
				<a href="javascript:void(0)">Delete</a>
			</div>
			<div class="comment-btn">
				<a href="javascript:void(0)">Unlike</a>
			</div>
			<div class="comment-btn">
				<a href="javascript:void(0)">Like</a>
			</div>
		</div>
	</div>
</div>

@endsection
