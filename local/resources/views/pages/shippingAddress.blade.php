@extends('layouts.cart')
@section('content')
<div class="mainContainer mt70">
	<div class="checkout-main">
	<div class="frm-container">
    	<div class="form-main-block">
        <h1>Shipping Address</h1>
        <p class="mb10">
            Please enter a shipping address for this order. Please also indicate whether your billing address is the same as the shipping address entered. When finished, click the "Continue" button.  Or, if you're sending items to more than one address, click the "Add another address" button to enter additional addresses
        </p>
		<form class="form-block" action="">
			<div class="select-country">
				<label for="">Country or region</label>
				<select name="" id="">
					<option value="">Pakistan</option>
					<option value="">Pakistan</option>
					<option value="">Pakistan</option>
				</select>
			</div>
			
			<div class="input-name mr20">
				<label for="">Your first name</label>
				<input type="text">
			</div>
			<div class="input-name">
				<label for="">Your last name</label>
				<input type="text">
			</div>

			<div class="street-address">
				<label for="">Street adress</label>
				<input type="text" name="" value="" placeholder="">
				<input class="mt10" type="text" name="" value="" placeholder="">
			</div>

			<div class="city">
				<label for="">City</label>
				<input type="text">
			</div>
			<div class="state">
				<label for="">State/Province/Region</label>
				<input type="text">
			</div>
			<div class="zip-code">
				<label for="">Zip</label>
				<input type="text">
			</div>
			<div class="phone-number">
				<label for="">Phone number</label>
				<input type="tel">
			</div>
			<div class="email-address">
				<label for="">Email address</label>
				<span>We’ll email you an order confirmation</span>
				<input type="text">
			</div>
			<div class="email-address">
				<label for="">Re-enter your email address</label>
				<input type="tel">
			</div>
		</form>
        <h1>Select shipping method</h1>
        <div class="shipping-block">
        	<div class="choose-method">
            	<input type="radio">
                <label>FedEx Express</label>
            </div>
            <div class="method-price">
            	<span>$15.00</span>
                 (15 - 20 Days)
            </div>
        </div>
        <div class="shipping-block">
        	<div class="choose-method">
            	<input type="radio">
                <label>DHL Services</label>
            </div>
            <div class="method-price">
            	<span>$15.00</span>
                 (15 - 20 Days)
            </div>
        </div>
        <div class="shipping-block">
        	<div class="choose-method">
            	<input type="radio">
                <label>Hopewiser Atlas</label>
            </div>
            <div class="method-price">
            	<span>$5.00</span>
                 (15 - 20 Days)
            </div>
        </div>
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
				<a class="btn-proceed " href="javascript:();">Continue &raquo;</a>
            </div>
			<!--<p class="money-guarantee">
				Covered by <a href="javascript:();">Kinnect2 Money Back Guarantee</a>
			</p>-->
		</div>
	</div>

	
</div>
</div>
@endsection