@extends('layouts.cart')
@section('content')
<div class="mainContainer mt70">
	<div class="checkout-main">
	<div class="frm-container">
    	<div class="form-main-block">
        	<h1>Your shipping information</h1>
            <div class="product-alert">
            	<div class="alert-div">There are few items which does not deliver to your selected Country/Region.</div>
                <a class="remove-items" href="#">Click here to remove</a>
            </div>
        	<div class="shipping-address-wrapper">
            	<div class="select-radio"><input type="radio"></div>
                <div class="shipping-detail">
                	<div class="detail-box">
                    	<div class="title">Name:</div>
                        <div class="detail">John Doe</div>
                    </div>
                    <div class="detail-box">
                    	<div class="title">Address:</div>
                        <div class="detail">6434 6th Avenue, Deerfield Beach, FL 33442</div>
                    </div>
                    <div class="detail-box">
                    	<div class="title">Country:</div>
                        <div class="detail">United States</div>
                    </div>
                    <div class="detail-box">
                    	<div class="title">Mobile:</div>
                        <div class="detail">072154870331</div>
                    </div>
                </div>
                <div class="edit-shipping">
                	<a href="#">Edit</a> | 
                    <a href="#">Delete</a>
                </div>
            </div>
            <div class="shipping-address-wrapper">
            	<div class="select-radio"><input type="radio"></div>
                <div class="shipping-detail">
                	<div class="detail-box">
                    	<div class="title">Name:</div>
                        <div class="detail">John Doe</div>
                    </div>
                    <div class="detail-box">
                    	<div class="title">Address:</div>
                        <div class="detail">6434 6th Avenue, Deerfield Beach, FL 33442</div>
                    </div>
                    <div class="detail-box">
                    	<div class="title">Country:</div>
                        <div class="detail">United States</div>
                    </div>
                    <div class="detail-box">
                    	<div class="title">Mobile:</div>
                        <div class="detail">072154870331</div>
                    </div>
                </div>
                <div class="edit-shipping">
                	<a href="#">Edit</a> | 
                    <a href="#">Delete</a>
                </div>
            </div>
            <a href="#" class="add-new-ad">Add new address</a>
		</div>
		<div class="continue-order">
        	<h1>Order Summary</h1>
			<div class="cart-checkout">
            	<div class="cart-selected-main">
		<div class="cart-selected-item">
			<div class="cart-selected-title">
				<p class="selected-title">Samsung Galaxy Tab S2 8.0" 32GB (Wi-Fi), White</p>
				<p class="selected-seller-name">Seller: Al Noor Traders</p>
			</div>
			<div class="cart-selected-quantity">
				<p>Qty: 1</p>
			</div>
			<div class="cart-selected-price">
				<p>Price: $299</p>
			</div>
		</div>

		<div class="cart-selected-item">
			<div class="cart-selected-title">
				<p class="selected-title">Three-Door with Twin Cooling Plus, 520 L</p>
				<p class="selected-seller-name">Seller: Authentic Gold</p>
			</div>
			<div class="cart-selected-quantity">
				<p>Qty: 1</p>
			</div>
			<div class="cart-selected-price">
				<p>Price: $299</p>
			</div>
		</div>

		<div class="cart-selected-item">
			<div class="cart-selected-title">
				<p class="selected-title">ATIV Book 9 (12.2” LED WQXGA / Core™ M)</p>
				<p class="selected-seller-name">Seller: Areatrend</p>
			</div>
			<div class="cart-selected-quantity">
				<p>Qty: 1</p>
			</div>
			<div class="cart-selected-price">
				<p>Price: $299</p>
			</div>
		</div>
	</div>
    			<div class="total-charges">
                	<div>Total: $7955.99</div>
                    <div>+ Shipping: $5.00</div>
                </div>
				<div class="total">
					<span class="total-item">Total: </span>
					<span class="total-value">$7955.99</span>
				</div>
				
			</div>
            <div class="proceed-container">
            	<span class="review-order">
					Continue to review your order
				</span>
				<a class="btn-proceed " href="javascript:void(0);">Continue &raquo;</a>
            </div>
			<!--<p class="money-guarantee">
				Covered by <a href="javascript:void(0);">Kinnect2 Money Back Guarantee</a>
			</p>-->
		</div>
	</div>

	
</div>
</div>
@endsection
