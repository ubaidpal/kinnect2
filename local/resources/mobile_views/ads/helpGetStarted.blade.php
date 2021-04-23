@extends('layouts.masterDynamic')
@section('content')
@include('includes.ads-left-nav')
        <!--Create Album-->
<div class="ad_main_wrapper">
	<div class="ad_inner_wrapper">
        <div class="main_heading">
             <h1>Get Started</h1>
        </div>

	<!-- Help & More Content-Block -->
	<div class="help-content-block">
		<!-- Get-Started Item -->
		<div class="get-started-item">
			<div class="content-head">
				<p>There are 5 quick steps to create your ad:</p>
			</div>
			<ol class="content-text">
				<li>Choose the Ad Type and the best Ad Package based on your requirements. Chose the one which gives your brand the best options according to your needs. Choose an ad based on the type and package, whether you want to create your own ad, or want to advertise content from your dashboard on the platform site.</li>
				<li>Identify your goals and design your ad. Put in an accurate and appealing ad description</li>
				<li>Target your ads by defining who you want to reach with your ad, and set a schedule for your ad if you want.</li>
				<li>Review your ad to see if it looks the way you want it to.</li>
				<li>Make payment for your advertisement if required.</li>
			</ol>
		</div>

		<!-- Get-Started Item -->
		<div class="get-started-item">
			<div class="content-head">
				<p>Tips:</p>
			</div>
			<ol class="content-text">
				<li>Design your Ads effectively?</li>
				<li>Your ads are comprised of image, title and description. Ensure that these are relevant to your brand and are appealing.</li>
				<li>Make sure your ad stands out with the content.</li>
				<li>Focus on one goal at a time in your ad for an effective campaign.</li>
			</ol>
		</div>

		<!-- Button Assistance -->
		<div class="need-assistance clrfix">
			<span>Need Assistance:</span>
			<a class="assistance-btn" href="javascript:void(0)">Contact Our Sales Team</a>
		</div>
	</div>
    </div>
</div>
@endsection