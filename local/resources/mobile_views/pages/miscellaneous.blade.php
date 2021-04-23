@extends('layouts.default')
@section('content')

<!-- Cover Photo Container -->
<div class="page-cover-container">
	<!-- Cover Image -->
	<div class="cover-img">
		<img src="{!! asset('local/public/assets/images_mobile/cover-photo.jpg') !!}" alt="image">
		<a class="btn-cover" href="javascript:void(0)"></a>
	</div>
	<!-- Profile Image -->
	<div class="profile-img-container">
		<div class="prfl-img-block">
			<a class="profile-img" href="javascript:void(0)">
				<img src="{!! asset('local/public/assets/images_mobile/profile-img.jpg') !!}" alt="img">
			</a>
			
			<a class="btn-cover btn-prfl-set" href="javascript:void(0)"></a>
		</div>

		<div class="profile-img-title">
			<a class="profile-title" href="javascript:void(0)">Profile Title Profile Title Profile Title Profile Title</a>
		</div>
	</div>
</div>

<!-- Sub Nav -->
<div class="sub-nav-container">
	<a class="sub-nav-item" href="javascript:void(0)">
		<div class="sub-nav-txt">What's New</div>
	</a>
	<a class="sub-nav-item" href="javascript:void(0)">
		<div class="sub-nav-txt active">Activity Log</div>
	</a>
	<a class="sub-nav-item" href="javascript:void(0)">
		<div class="sub-nav-txt">Kinnectors</div>
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

<!-- Title Bar -->
<div class="title-bar">
	<span>My Brands</span>
</div>

<!-- New Post Container -->
<div class="new-post-container">
	<form action="" class="form-container">
		<input class="form-item" type="text" name="" value="" placeholder="What would you like to post?">
		<div class="adpost-btn">
			<a class="btn btn-upload" href="javascript:void(0)">Upload</a>
			<a class="btn btn-upload fR" href="javascript:void(0)">Share</a>
		</div>
	</form>
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
			<p class="post-story-txt">
				Lorem ipsum dolor sit amet.
			</p>
		</div>

		<!-- Post Video/Image Container -->
		<div class="post-video-container">
			<div class="post-video-item">
				<img src="{!! asset('local/public/assets/images_mobile/post-image.jpg') !!}" alt="image">
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

<!-- Accordian Container -->
<div class="accordionContainer">
	<!-- Accordian Item -->
	<div class="accordian-item">
	    <div class="accordionButton">
		  <span class="indicator"></span>
	      <h4 class="acrdn-btn-txt">Sample Text</h4>
	    </div>
	    <div class="accordionContent">
	        <div class="accordion-content-txt">
	        	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio eius, explicabo accusamus a aut eligendi consequuntur consequatur, vero ad facilis.
	        </div>
	        <ol class="acrdn-list">
	        	<li>Lorem ipsum dolor sit amet.</li>
	        	<li>Lorem ipsum dolor sit amet.</li>
	        	<li>Lorem ipsum dolor sit amet.</li>
	        </ol>
	        <div class="accordion-content-note">
	        	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Hic maiores iste fuga, voluptas consequatur tenetur aperiam, minus ipsa dolorum repellendus.
	        </div>
	    </div>
	</div>
	<!-- Accordian Item -->
	<div class="accordian-item">
	    <div class="accordionButton">
		  <span class="indicator"></span>
	      <h4 class="acrdn-btn-txt">Sample Text</h4>
	    </div>
	    <div class="accordionContent">
	        <div class="accordion-content-txt">
	        	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio eius, explicabo accusamus a aut eligendi consequuntur consequatur, vero ad facilis.
	        </div>
	        <ol class="acrdn-list">
	        	<li>Lorem ipsum dolor sit amet.</li>
	        	<li>Lorem ipsum dolor sit amet.</li>
	        	<li>Lorem ipsum dolor sit amet.</li>
	        </ol>
	        <div class="accordion-content-note">
	        	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Hic maiores iste fuga, voluptas consequatur tenetur aperiam, minus ipsa dolorum repellendus.
	        </div>
	    </div>
	</div>
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
			<p class="post-story-txt">
				Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate distinctio a similique, voluptatum ea quaerat numquam? Excepturi accusamus, voluptatibus deserunt optio commodi ipsum repellat unde sint, minus molestias ducimus dolor.
			</p>
			<p class="post-story-txt">
				Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestiae, aliquid?
			</p>
			<p class="post-story-txt">
				Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident nobis culpa repudiandae molestiae blanditiis et quisquam, repellat minima veritatis libero.
			</p>
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
				
				<!-- <div class="feed-item">
					<a class="btn-feed-item social" href="javascript:void(0)"></a>
				</div>

				<div class="feed-item">
					<a class="btn-feed-item flag" href="javascript:void(0)"></a>
				</div> -->
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

<!-- Progress Bar -->
<div class="prog-bar-item">
	<div class="prog-bar-title">
		<h3>Blue Orca Studios</h3>
	</div>
	<div class="prog-bar-votes">
		<p>Votes: 15</p>
	</div>
	<div class="progress-bar">
	  <span class="bar" style="width: 25%"></span>
	</div>
</div>

<!-- Activity Log -->
<div class="activity-log-item">
	<div class="activity-log-date">
		<span>December 2, 2015</span>
	</div>
	<div class="activity-log-status">
		<div class="activity-title dinline">
			<a href="javascript:void(0)">Richard Walker</a>
		</div>
		<div class="activity-status dinline">
			<span>joined the brand</span>
		</div>
		<div class="activity-about dinline">
			<a href="javascript:void(0)">IRIS Corporation</a>
		</div>
	</div>
</div>

<!-- Round Image Container -->
<div class="round-img-container">
	<!-- Round Img Item -->
	<div class="round-img-item">
		<div class="round-img-contnr">
			<a class="round-img" href="javascript:void(0)">
				<img src="{!! asset('local/public/assets/images_mobile/kinnector-img.jpg') !!}" alt="img">
			</a>
		</div>
		<div class="round-img-title">
			<a class="round-title-txt" href="javascript:void(0)">John Doe</a>
		</div>
		<div class="round-img-followers">
			<span>50K Followers</span>
		</div>
		<div class="">
			<a class="btn-round-img" href="javascript:void(0)">Unfollow</a>
		</div>
	</div>
</div>

@endsection
