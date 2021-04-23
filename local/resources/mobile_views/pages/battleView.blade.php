@extends('layouts.default')
@section('content')
<div class="title-bar">
	<span>Battle of the Brands</span>
</div>
<!-- Battle Container -->
<div class="battle-container">

		<header class="battle-header">
			<h3 class="battle-title">Quality Test Battle</h3>
			<p class="p-txt">Which studio is the closest to you?</p>
		</header>

		<div class="battle-vs-block">
			<div class="battle-vs-item">
				<div class="battle-vs-img lbradius">
					<img src="{!! asset('local/public/assets/images_mobile/battle-img.jpg') !!}" alt="img">
				</div>
				<div class="battle-vs-title">
					<h3>Blue Orca Studios Blue Orca Studios Blue Orca Studios</h3>
				</div>
			</div>

			<div class="battle-vs-item">
				<div class="battle-vs-img rbradius">
					<img src="{!! asset('local/public/assets/images_mobile/battle-img.jpg') !!}" alt="img">
				</div>
				<div class="battle-vs-title">
					<h3 class="bvstxt">Blue Orca Studios Blue Orca Studios Blue Orca Studios Blue Orca Studios </h3>
				</div>
			</div>
		</div>

		<!-- Battle Result -->
	<div class="battle-result">
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
		<p class="total-votes">
			Total number of votes: 3
		</p>
	</div>

		<!-- Battle View -->	
    <div class="battle-view">
			<div class="bv-radio">
				<div class="battle-radio-item">
					<label class="rad">
						<input type="radio" id="bo-1" name="first-name" value="a" /><i></i>
						<h3 class="" for="bo-1">
							Blue Orca Studio Blue Orca Studio Blue Orca Studio Blue Orca Studio Blue Orca Studio
						</h3>
					</label>
				</div>
				<div class="battle-radio-item">
					<label class="rad">
						<input type="radio" id="k2-1" name="first-name" value="a" /><i></i>
						<h3 class="" for="k2-1">Kinnect 2</h3>
					</label>
				</div>
			</div>
			<p class="total-votes">
				Total number of votes: 3
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

@endsection
