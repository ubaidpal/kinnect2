@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')		

<div class="mainCont">

@include('includes.store-product-leftside')

<div class="brand-store">
	<div class="brand-store-title">
		<span>55&Prime; JU6800 6 Series Flat UHD 4K Nano Crystal Smart TV</span>
		<a class="store-title-back" href="javascript:void(0);">&laquo; back</a>
	</div>

	<div class="brand-store-img-title">
		<div class="brand-img">
			<img src="{!! asset('local/public/assets/images/brand-store-img.jpg') !!}" alt="img">
		</div>
        
        <div class="product-detail-wrapper">
        	<div class="product-list-wrapper">
            	<div class="heading">Price:</div>
                <div class="detail"><sub class="prev">&#36;1800</sub><span class="current-price">&#36;1350</span><em>/ piece</em><span class="availability">Availabilty: <strong>In Stock</strong></span></div>
            </div>
            
            <div class="product-list-wrapper">
            	<div class="heading">Quantity:</div>
                <div class="detail">
                	<div class="increse-quantity">
                    	<a href="#">-</a><div><input type="text" /></div><a href="#">+</a>
                    </div>
                    <div class="available">piece (99 pieces available)</div>
                </div>
            </div>
            
            <div class="product-list-wrapper">
            	<div class="heading">Color:</div>
                <div class="detail">
                	<a href="#" class="cs-item active">Silver</a>
                    <a href="#" class="cs-item">Black</a>
                    <a href="#" class="cs-item">Gold</a>
                </div>
            </div>
            
            <div class="product-list-wrapper">
            	<div class="heading">Sizes:</div>
                <div class="detail">
                	<a href="#" class="cs-item active">15&Prime;</a>
                    <a href="#" class="cs-item">17&Prime;</a>
                    <a href="#" class="cs-item">21&Prime;</a>
                </div>
            </div>
            
            <div class="product-list-wrapper">
            	<div class="heading">Shipping:</div>
                <div class="detail">
                	<img src="{!! asset('local/public/assets/images/country-img.jpg') !!}" width="194" height="38" alt="Country"/>
                    <div class="alert">We do not deliver to your selected Country/Region.</div>
                </div>
            </div>
        </div>
        
        
		<div class="img-features">
			<div class="features fltL">
				<span>Buyer Protection:</span>
				<ul>
					<li>Full Refund if you don't received your order</li>
					<li>Refund or Keep items not described</li>
				</ul>
			</div>			
			<div class="btn-cart">
				<a href="javascript:void(0);">Add to Cart</a>
                <span>Guranted Seller</span>
			</div>
		</div>
		
        <div class="return-policy">
        	<p><strong>Return Policy: </strong> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p><a href="#">Learn more</a>
        </div>
	</div>


	<div class="tabs-container mt10">
	    <ul class="tabs-menu">
	        <li class="current"><a href="#tab-features">Features</a></li>
	        <li><a href="#tab-specs">Tech Specs</a></li>
	        <li><a href="#tab-reviews">Reviews</a></li>
	    </ul>
	    <div class="tab">
	        <div id="tab-features" class="tab-content-main">
	        	<div class="tab-content-item">
		        	<p class="tab-content-title">Sensational Nano Crystal Colour</p>
		            <p>
		            	The latest development in screen technology, JU6800 UHD Nano Crystal TV delivers 1.2x more colour than standard UHD creating amazingly lifelike scenes with true to life tones in four times the detail of Full HD. This true UHD screen offers colour range that meets the highest of industry standards emitting the purest light source delivering increased brightness levels, colours and content as the director intended.
		            </p>
		        </div>
		        <div class="tab-content-item">
		        	<p class="tab-content-title">Sensational Nano Crystal Colour</p>
		            <p>
		            	The latest development in screen technology, JU6800 UHD Nano Crystal TV delivers 1.2x more colour than standard UHD creating amazingly lifelike scenes with true to life tones in four times the detail of Full HD. This true UHD screen offers colour range that meets the highest of industry standards emitting the purest light source delivering increased brightness levels, colours and content as the director intended.
		            </p>
		        </div>
		        <div class="tab-content-item">
		        	<p class="tab-content-title">Sensational Nano Crystal Colour</p>
		            <p>
		            	The latest development in screen technology, JU6800 UHD Nano Crystal TV delivers 1.2x more colour than standard UHD creating amazingly lifelike scenes with true to life tones in four times the detail of Full HD. This true UHD screen offers colour range that meets the highest of industry standards emitting the purest light source delivering increased brightness levels, colours and content as the director intended.
		            </p>
		        </div>
	        </div>

	        <div id="tab-specs" class="tab-content-main">
	            <div class="tab-content-item">
		        	<p class="tab-content-title">Sensational Nano Crystal Colour</p>
		            <p>
		            	The latest development in screen technology, JU6800 UHD Nano Crystal TV delivers 1.2x more colour than standard UHD creating amazingly lifelike scenes with true to life tones in four times the detail of Full HD. This true UHD screen offers colour range that meets the highest of industry standards emitting the purest light source delivering increased brightness levels, colours and content as the director intended.
		            </p>
		        </div>
		        <div class="tab-content-item">
		        	<p class="tab-content-title">Sensational Nano Crystal Colour</p>
		            <p>
		            	The latest development in screen technology, JU6800 UHD Nano Crystal TV delivers 1.2x more colour than standard UHD creating amazingly lifelike scenes with true to life tones in four times the detail of Full HD. This true UHD screen offers colour range that meets the highest of industry standards emitting the purest light source delivering increased brightness levels, colours and content as the director intended.
		            </p>
		        </div>
		        <div class="tab-content-item">
		        	<p class="tab-content-title">Sensational Nano Crystal Colour</p>
		            <p>
		            	The latest development in screen technology, JU6800 UHD Nano Crystal TV delivers 1.2x more colour than standard UHD creating amazingly lifelike scenes with true to life tones in four times the detail of Full HD. This true UHD screen offers colour range that meets the highest of industry standards emitting the purest light source delivering increased brightness levels, colours and content as the director intended.
		            </p>
		        </div>
		        <div class="tab-content-item">
		        	<p class="tab-content-title">Sensational Nano Crystal Colour</p>
		            <p>
		            	The latest development in screen technology, JU6800 UHD Nano Crystal TV delivers 1.2x more colour than standard UHD creating amazingly lifelike scenes with true to life tones in four times the detail of Full HD. This true UHD screen offers colour range that meets the highest of industry standards emitting the purest light source delivering increased brightness levels, colours and content as the director intended.
		            </p>
		        </div>	        
	        </div>

	        <div id="tab-reviews" class="tab-content-main">
	            <!--<div class="tab-content-item">
		        	<p class="tab-content-title">Sensational Nano Crystal Colour</p>
		            <p>
		            	The latest development in screen technology, JU6800 UHD Nano Crystal TV delivers 1.2x more colour than standard UHD creating amazingly lifelike scenes with true to life tones in four times the detail of Full HD. This true UHD screen offers colour range that meets the highest of industry standards emitting the purest light source delivering increased brightness levels, colours and content as the director intended.
		            </p>
		        </div>
		        <div class="tab-content-item">
		        	<p class="tab-content-title">Sensational Nano Crystal Colour</p>
		            <p>
		            	The latest development in screen technology, JU6800 UHD Nano Crystal TV delivers 1.2x more colour than standard UHD creating amazingly lifelike scenes with true to life tones in four times the detail of Full HD. This true UHD screen offers colour range that meets the highest of industry standards emitting the purest light source delivering increased brightness levels, colours and content as the director intended.
		            </p>
		        </div>
		        <div class="tab-content-item">
		        	<p class="tab-content-title">Sensational Nano Crystal Colour</p>
		            <p>
		            	The latest development in screen technology, JU6800 UHD Nano Crystal TV delivers 1.2x more colour than standard UHD creating amazingly lifelike scenes with true to life tones in four times the detail of Full HD. This true UHD screen offers colour range that meets the highest of industry standards emitting the purest light source delivering increased brightness levels, colours and content as the director intended.
		            </p>
		        </div>
		        <div class="tab-content-item">
		        	<p class="tab-content-title">Sensational Nano Crystal Colour</p>
		            <p>
		            	The latest development in screen technology, JU6800 UHD Nano Crystal TV delivers 1.2x more colour than standard UHD creating amazingly lifelike scenes with true to life tones in four times the detail of Full HD. This true UHD screen offers colour range that meets the highest of industry standards emitting the purest light source delivering increased brightness levels, colours and content as the director intended.
		            </p>
		        </div>-->
                
                <div class="write-reviews">
                	<div class="addReview">
                    	<a href="javascript:void(0);" class="userImg"><img src="{!! asset('local/public/assets/images/leaderboard-tabs-content.jpg') !!}" width="45" height="45" alt="User Image"/></a>
                        <div><img src="{!! asset('local/public/assets/images/stars.png') !!}" width="95" height="14" alt="Rating" /></div>
                        <a href="javascript:void(0);" class="orngBtn fltR">Add Review</a>
                    </div>
                    <div class="publish">
                    	<a href="javascript:void(0);" class="orngBtn fltR">Publish</a>
                    	<input type="text" placeholder="What you think about this product?" />
                    </div>
                </div>
                <div class="comment-pnl reviews">
                    <a class="user-image" title="username" href="javascript:void(0);"><img width="45" height="45" title="user" alt="" src="{!! asset('local/public/assets/images/profile.jpg') !!}"></a>
                    <div class="comment-text">
                        <a href="javascript:void(0);" class="commentor-name">Peter John</a>
                        <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                        <span class="date">15 April at 10:24pm</span>
                        <div><img width="95" height="14" alt="Rating" src="{!! asset('local/public/assets/images/stars.png') !!}"></div>
                    </div>
                </div>
                <div class="comment-pnl reviews">
                    <a class="user-image" title="username" href="javascript:void(0);"><img width="45" height="45" title="user" alt="" src="{!! asset('local/public/assets/images/profile.jpg') !!}"></a>
                    <div class="comment-text">
                        <a href="javascript:void(0);" class="commentor-name">Peter John</a>
                        <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                        <span class="date">15 April at 10:24pm</span>
                        <div><img width="95" height="14" alt="Rating" src="{!! asset('local/public/assets/images/stars.png') !!}"></div>
                    </div>
                </div>
                <div class="comment-pnl reviews">
                    <a class="user-image" title="username" href="javascript:void(0);"><img width="45" height="45" title="user" alt="" src="{!! asset('local/public/assets/images/profile.jpg') !!}"></a>
                    <div class="comment-text">
                        <a href="javascript:void(0);" class="commentor-name">Peter John</a>
                        <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                        <span class="date">15 April at 10:24pm</span>
                        <div><img width="95" height="14" alt="Rating" src="{!! asset('local/public/assets/images/stars.png') !!}"></div>
                    </div>
                </div>
                <div class="comment-pnl reviews">
                    <a class="user-image" title="username" href="javascript:void(0);"><img width="45" height="45" title="user" alt="" src="{!! asset('local/public/assets/images/profile.jpg') !!}"></a>
                    <div class="comment-text">
                        <a href="javascript:void(0);" class="commentor-name">Peter John</a>
                        <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                        <span class="date">15 April at 10:24pm</span>
                        <div><img width="95" height="14" alt="Rating" src="{!! asset('local/public/assets/images/stars.png') !!}"></div>
                    </div>
                </div>
                <div class="comment-pnl reviews">
                    <a class="user-image" title="username" href="javascript:void(0);"><img width="45" height="45" title="user" alt="" src="{!! asset('local/public/assets/images/profile.jpg') !!}"></a>
                    <div class="comment-text">
                        <a href="javascript:void(0);" class="commentor-name">Peter John</a>
                        <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                        <span class="date">15 April at 10:24pm</span>
                        <div><img width="95" height="14" alt="Rating" src="{!! asset('local/public/assets/images/stars.png') !!}"></div>
                    </div>
                </div>
	        </div>
	    </div>
	</div>
        
</div>

@include('includes.ads-right-side')

</div>

@endsection
