@extends('layouts.masterDynamic')
@section('content')
@include('includes.ads-left-nav')
        <!--Create Album-->
<div class="ad_main_wrapper">
	<div class="ad_inner_wrapper">
        <div class="main_heading">
             <h1>Design Your Ad FAQ</h1>
        </div>

	<!-- Help & More Content-Block -->
	<div class="help-content-block">
		<!-- Get-Started Item -->
		<div class="gen-faq-item">
			<div class="content-head">
				<p>What is ad targeting and how does it work?</p>
				<ol class="content-text clrfix">
					<span class="content-text-initial">
						Ad targeting enables you to reach the target audience which is more likely to be interested in your ads. If your ad package allows targeting, then you will be able to configure targeting for your ad based on user profile option. Only users whose profiles match with your targeting criteria will be shown your targeted ad.
					</span>
				</ol>
			</div>
		</div>

		<!-- Get-Started Item -->
		<div class="gen-faq-item">
			<div class="content-head">
				<p>Do I have to configure targeting on all profile fields?</p>
				<ol class="content-text clrfix">
					<span class="content-text-initial">
						No. The more profile options that you configure for your targeted ad, the more specific the targeting becomes.
					</span>
				</ol>
			</div>
		</div>

		<!-- Get-Started Item -->
		<div class="gen-faq-item">
			<div class="content-head">
				<p>My question wasn't answered above.</p>
				<ol class="content-text clrfix">
					<span class="content-text-initial">
						If you have any queries about advertising here, or need assistance, then please contact our Sales Team.
					</span>
				</ol>
			</div>
		</div>
	</div>
    </div>
</div>
@endsection