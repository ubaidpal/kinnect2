@extends('layouts.masterDynamic')
@section('content')
@include('includes.ads-left-nav')
        <!--Create Album-->
<div class="ad_main_wrapper">
	<div class="ad_inner_wrapper">
        <div class="main_heading">
             <h1>Overview</h1>
        </div>
	<!-- Help & More Content-Block -->
	<div class="help-content-block mb10">
		<!-- Overview Item -->
		<div class="overview-item">
			<div class="overview-img">
				<img src="{!! asset('local/public/assets/images/svg/promoteconsumers.svg') !!}" alt="overview-img">
			</div><!-- Image Overview -->

			<div class="content-overview">
				<div class="content-head">
					<p>Promote to Your Target Consumers</p>
				</div>
				<ol class="content-text">
					<li>Connect with your potential consumers.</li>
					<li>Target your ads and choose your audience.</li>
					<li>Expressive text and image based ads ensure more clicks and attention to your ads.</li>
				</ol>
			</div>
		</div>

		<!-- Overview Item -->
		<div class="overview-item">
			<div class="overview-img">
				<img src="{!! asset('local/public/assets/images/svg/engageconsumers.svg') !!}" alt="overview-img">
			</div><!-- Image Overview -->

			<div class="content-overview">
				<div class="content-head">
					<p>Engage Consumers and Strengthen Relationships</p>
				</div>
				<ol class="content-text">
					<li>Connect with your potential consumers.</li>
					<li>Target your ads and choose your audience.</li>
					<li>Expressive text and image based ads ensure more clicks and attention to your ads.</li>
				</ol>
			</div>
		</div>

		<!-- Overview Item -->
		<div class="overview-item">
			<div class="overview-img">
				<img src="{!! asset('local/public/assets/images/svg/managebudget.svg') !!}" alt="overview-img">
			</div><!-- Image Overview -->

			<div class="content-overview">
				<div class="content-head">
					<p>Manage Your Budget</p>
				</div>
				<ol class="content-text">
					<li>Connect with your potential consumers.</li>
					<li>Target your ads and choose your audience.</li>
					<li>Expressive text and image based ads ensure more clicks and attention to your ads.</li>
				</ol>
			</div>
		</div>
        <div class="need-assistance">
        <span>Need Assistance:</span>
        <a class="assistance-btn" href="javascript:void(0)">Contact Our Sales Team</a>
    </div>
	</div>
    </div>
</div>
@endsection









