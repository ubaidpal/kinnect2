@extends('layouts.cart')
@section('content')
<div class="mainContainer mt70">
<div class="checkout-main">
	<div class="frm-container">
  
    <div class="review-order-main">
       <div class="review-order-title">
        <p>Order Delivery to</p>
       </div>
       <div class="review-order-personal-detail">
        <p>Your Name Here</p>
        <p>Your delivery adress</p>
        <p>Adress line 2</p>
        <p>Lahore, Panjab 54000</p>
        <p>03454941058</p>
        <p>ahmdesigns@gmail.com</p>
       </div>
       <div class="change-delivering-address">
        <p>[<a href="javascript:();">Change your order delivering address</a>]</p>
       </div>
       <div class="payment-method">
        <div class="pay-with">
         <span>Pay with</span>
        </div>
        <form action="">
         <div class="by-cards">
          <input type="checkbox">
          <a href="javascript:();"><img src="{!! asset('local/public/assets/images/order-review-visa.png') !!}"></a>
          <a href="javascript:();"><img src="{!! asset('local/public/assets/images/order-review-master.png') !!}"></a>
          <a href="javascript:();"><img src="{!! asset('local/public/assets/images/order-review-discover.png') !!}"></a>
          <a href="javascript:();"><img src="{!! asset('local/public/assets/images/order-review-american.png') !!}"></a>
          <p>Processed by PayPal</p>
         </div>
         <div class="by-paypal">
          <input type="checkbox">
          <a href="javascript:();"><img src="{!! asset('local/public/assets/images/order-review-paypal.png') !!}"></a>
         </div>
        </form>
       </div>
      </div>
    
    
		
    <div class="continue-order">
       <div class="cart-checkout">
        <div class="total">
         <span class="total-item">Total: </span>
         <span class="total-value">$7955.99</span>
        </div>
        <span class="review-order">Continue to PayPal to complete your purchase order</span>
        <span class="review-order">By clicking Continue you agree to</span>
        <span class="review-order">Kinnect2’s <a href="javascript:();">User Agreement</a> and <a href="javascript:();">Privacy Policy</a>.</span>
        <a class="btn btn-proceed" href="javascript:();">Continue</a>
       </div>
       <p class="money-guarantee">
        Covered by <a href="javascript:();">Kinnect2 Money Back Guarantee</a>
       </p>
    </div>
</div>

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
   <div class="cart-selected-quanti`ty">
    <p>Qty: 1</p>
   </div>
   <div class="cart-selected-price">
    <p>Price: $299</p>
   </div>
  </div>
</div>
</div>
</div>
@endsection