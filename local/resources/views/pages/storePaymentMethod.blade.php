@extends('layouts.cart')
@section('content')
<div class="mainContainer mt70">
	<div class="checkout-main">
	<div class="frm-container">
    	<div class="form-main-block">
        <h1>Your shipping information <a href="javascript:void(0);" class="edit">Edit</a></h1>
       	<div class="shipping-info-wrapper">
        	<div class="shipping-info">
            	<div class="text">Name:</div>
                <div class="value">John Doe</div>
            </div>
            <div class="shipping-info">
            	<div class="text">Address:</div>
                <div class="value">Model Town Extension Block, Lahore Punjab, Pakistan</div>
            </div>
            <div class="shipping-info">
            	<div class="text">Mobile:</div>
                <div class="value">00923008480874</div>
            </div>
            
        </div>
        <h1>Choose payment method</h1>
        <div class="payment-block">
        	<div class="choose-method">
            	<input type="radio">
                <label>Credit card</label>
            </div>
            <div class="method-options">
            	<img src="" alt=""/>
            </div>
            <form class="form-block" action="">
                <div class="street-address">
                    <label for="">Card number</label>
                    <input type="text" name="" value="" placeholder="">
                </div>
                <div class="street-address">
                    <label for="">Card holder name</label>
                    <input type="text" name="" value="" placeholder="">
                </div>
                <div class="date">
                    <label for="">Expiration Date</label>
                    <div><select><option>mm</option></select></div>
                    <div><select><option>year</option></select></div>
                </div>
                <div class="verification-code">
                    <label for="">Card verification code</label>
                    <input type="text">
                </div>
            </form>
        </div>
		<div class="payment-block">
        	<div class="choose-method">
            	<input type="radio">
                <label>PayPal</label>
            </div>
            <div class="method-options">
            	<img src="" alt=""/>
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