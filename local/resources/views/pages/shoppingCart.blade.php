@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')		

<div class="mainCont">

@include('includes.store-product-leftside')

<div class="brand-store">
    <div class="brand-store-title">
		<span>Your Kinnect2 Shopping Cart</span>
	</div>

	<div class="cart-item">
		<div class="cart-seller-title">
			<div class="seller-name">
				Seller <span>Al-Noor Traders</span>
			</div>
			<div class="price-title">
				<span>Price</span>
			</div>
			<div class="quantity-title">
				<span>Quantity</span>
			</div>
		</div>

		<div class="cart-item-detail">
			<div class="cart-item-img">
				<img src="{!! asset('local/public/assets/images/cart-item-img.jpg') !!}" alt="img">
			</div>
			<div class="cart-item-title">
				<p class="cart-item-name">
					Samsung Galaxy Tab S2 8.0" 32GB (Wi-Fi), White
				</p>
				<p class="cart-condition">
					Condition: New
				</p>
				<p class="cart-availability">
					In Stock
				</p>
			</div>
			<div class="cart-item-price">
				<span>$299</span>
			</div>
			<div class="cart-item-quantity">
				<select name="" id="">
					<option value="">1</option>
					<option value="">2</option>
					<option value="">3</option>
				</select>
			</div>
		</div>
		<div class="btn-del-save">
			<a href="javascript:void(0);">Delete</a>
			<span class="seperator"></span>
			<a href="javascript:void(0);">Save for Later</a>
		</div>
	</div>

	<div class="cart-item">
		<div class="cart-seller-title">
			<div class="seller-name">
				Seller <span>Al-Noor Traders</span>
			</div>
			<div class="price-title">
				<span>Price</span>
			</div>
			<div class="quantity-title">
				<span>Quantity</span>
			</div>
		</div>

		<div class="cart-item-detail">
			<div class="cart-item-img">
				<img src="{!! asset('local/public/assets/images/cart-item-img.jpg') !!}" alt="img">
			</div>
			<div class="cart-item-title">
				<p class="cart-item-name">
					Samsung Galaxy Tab S2 8.0" 32GB (Wi-Fi), White
				</p>
				<p class="cart-condition">
					Condition: New
				</p>
				<p class="cart-availability">
					In Stock
				</p>
			</div>
			<div class="cart-item-price">
				<span>$299</span>
			</div>
			<div class="cart-item-quantity">
				<select name="" id="">
					<option value="">1</option>
					<option value="">2</option>
					<option value="">3</option>
				</select>
			</div>
		</div>
		<div class="btn-del-save">
			<a href="javascript:void(0);">Delete</a>
			<span class="seperator"></span>
			<a href="javascript:void(0);">Save for Later</a>
		</div>
	</div>

	<div class="cart-item">
		<div class="cart-seller-title">
			<div class="seller-name">
				Seller <span>Al-Noor Traders</span>
			</div>
			<div class="price-title">
				<span>Price</span>
			</div>
			<div class="quantity-title">
				<span>Quantity</span>
			</div>
		</div>

		<div class="cart-item-detail">
			<div class="cart-item-img">
				<img src="{!! asset('local/public/assets/images/cart-item-img.jpg') !!}" alt="img">
			</div>
			<div class="cart-item-title">
				<p class="cart-item-name">
					Samsung Galaxy Tab S2 8.0" 32GB (Wi-Fi), White
				</p>
				<p class="cart-condition">
					Condition: New
				</p>
				<p class="cart-availability">
					In Stock
				</p>
			</div>
			<div class="cart-item-price">
				<span>$299</span>
			</div>
			<div class="cart-item-quantity">
				<select name="" id="">
					<option value="">1</option>
					<option value="">2</option>
					<option value="">3</option>
				</select>
			</div>
		</div>
		<div class="btn-del-save">
			<a href="javascript:void(0);">Delete</a>
			<span class="seperator"></span>
			<a href="javascript:void(0);">Save for Later</a>
		</div>
	</div>

	<div class="cart-checkout">
		<div class="subtotal">
			<span class="subtotal-item">Subtotal <em>(6 Items): </em></span>
			<span class="subtotal-value">$7955.99</span>
		</div>

		<div class="saving">
			<span class="saving-item">Total Saving: </span>
			<span class="saving-value">$0</span>
		</div>
		<div class="total">
			<span class="total-item">Total: </span>
			<span class="total-value">$7955.99</span>
		</div>
		<a class="btn btn-continue" href="javascript:void(0);">Continue Shopping</a>
		<a class="btn btn-proceed " href="javascript:void(0);">Proceed to checkout</a>
	</div>
</div>

@include('includes.ads-right-side')

</div>

@endsection
