@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')		

<div class="mainCont">

@include('includes.store-product-leftside')

<div class="brand-store">
	<div class="three-product-container">
		<div class="product-container-title">Featured Items</div>

		<div class="brand-product-item-main">
			<div class="brand-product-item">
				<div class="range-item-img">
					<img src="{!! asset('local/public/assets/images/brand-store-product-item.jpg') !!}" alt="img">
				</div>
				<div class="range-item-txt mt10">
					<p>55" JU6800 6 Series Flat UHD</p>
					<p>4K Nano Crystal Smart TV</p>
				</div>
				<div class="range-item-price mt15">
					<div class="item-price">&dollar;890</div>
					<div class="item-rating"></div>
				</div>
				<a class="range-item-btn" href="javascript:();">View Details</a>
			</div>

			<div class="brand-product-item">
				<div class="range-item-img">
					<img src="{!! asset('local/public/assets/images/brand-store-product-item-2.jpg') !!}" alt="img">
				</div>
				<div class="range-item-txt mt10">
					<p>55" JU6800 6 Series Flat UHD</p>
					<p>4K Nano Crystal Smart TV</p>
				</div>
				<div class="range-item-price mt15">
					<div class="item-price">&dollar;890</div>
					<div class="item-rating"></div>
				</div>
				<a class="range-item-btn" href="javascript:();">View Details</a>
			</div>

			<div class="brand-product-item">
				<div class="range-item-img">
					<img src="{!! asset('local/public/assets/images/brand-store-product-item-3.jpg') !!}" alt="img">
				</div>
				<div class="range-item-txt mt10">
					<p>55" JU6800 6 Series Flat UHD</p>
					<p>4K Nano Crystal Smart TV</p>
				</div>
				<div class="range-item-price mt15">
					<div class="item-price">&dollar;890</div>
					<div class="item-rating"></div>
				</div>
				<a class="range-item-btn" href="javascript:();">View Details</a>
			</div>
		</div>
	</div>

	<div class="three-product-container mt10">
		<div class="product-container-title">Top Selling Products</div>

		<div class="brand-product-item-main">
			<div class="brand-product-item">
				<div class="range-item-img">
					<img src="{!! asset('local/public/assets/images/brand-store-product-item-1.jpg') !!}" alt="img">
				</div>
				<div class="range-item-txt mt10">
					<p>55" JU6800 6 Series Flat UHD</p>
					<p>4K Nano Crystal Smart TV</p>
				</div>
				<div class="range-item-price mt15">
					<div class="item-price">&dollar;890</div>
					<div class="item-rating"></div>
				</div>
				<a class="range-item-btn" href="javascript:();">View Details</a>
			</div>

			<div class="brand-product-item">
				<div class="range-item-img">
					<img src="{!! asset('local/public/assets/images/brand-store-product-item-4.jpg') !!}" alt="img">
				</div>
				<div class="range-item-txt mt10">
					<p>55" JU6800 6 Series Flat UHD</p>
					<p>4K Nano Crystal Smart TV</p>
				</div>
				<div class="range-item-price mt15">
					<div class="item-price">&dollar;890</div>
					<div class="item-rating"></div>
				</div>
				<a class="range-item-btn" href="javascript:();">View Details</a>
			</div>

			<div class="brand-product-item">
				<div class="range-item-img">
					<img src="{!! asset('local/public/assets/images/brand-store-product-item-5.jpg') !!}" alt="img">
				</div>
				<div class="range-item-txt mt10">
					<p>55" JU6800 6 Series Flat UHD</p>
					<p>4K Nano Crystal Smart TV</p>
				</div>
				<div class="range-item-price mt15">
					<div class="item-price">&dollar;890</div>
					<div class="item-rating"></div>
				</div>
				<a class="range-item-btn" href="javascript:();">View Details</a>
			</div>
		</div>
	</div>

	<div class="three-product-container mt10">
		<div class="product-container-title">Product Packages</div>

		<div class="brand-product-item-main">
			<div class="brand-product-item">
				<div class="range-item-img">
					<img src="{!! asset('local/public/assets/images/brand-store-product-item-2.jpg') !!}" alt="img">
				</div>
				<div class="range-item-txt mt10">
					<p>55" JU6800 6 Series Flat UHD</p>
					<p>4K Nano Crystal Smart TV</p>
				</div>
				<div class="range-item-price mt15">
					<div class="item-price">&dollar;890</div>
					<div class="item-rating"></div>
				</div>
				<a class="range-item-btn" href="javascript:();">View Details</a>
			</div>

			<div class="brand-product-item">
				<div class="range-item-img">
					<img src="{!! asset('local/public/assets/images/brand-store-product-item-3.jpg') !!}" alt="img">
				</div>
				<div class="range-item-txt mt10">
					<p>55" JU6800 6 Series Flat UHD</p>
					<p>4K Nano Crystal Smart TV</p>
				</div>
				<div class="range-item-price mt15">
					<div class="item-price">&dollar;890</div>
					<div class="item-rating"></div>
				</div>
				<a class="range-item-btn" href="javascript:();">View Details</a>
			</div>

			<div class="brand-product-item">
				<div class="range-item-img">
					<img src="{!! asset('local/public/assets/images/brand-store-product-item-1.jpg') !!}" alt="img">
				</div>
				<div class="range-item-txt mt10">
					<p>55" JU6800 6 Series Flat UHD</p>
					<p>4K Nano Crystal Smart TV</p>
				</div>
				<div class="range-item-price mt15">
					<div class="item-price">&dollar;890</div>
					<div class="item-rating"></div>
				</div>
				<a class="range-item-btn" href="javascript:();">View Details</a>
			</div>
		</div>
	</div>

</div>

@include('includes.ads-right-side')

</div>

@endsection