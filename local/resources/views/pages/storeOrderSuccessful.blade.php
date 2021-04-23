@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')		

<div class="mainCont">

@include('includes.store-admin-leftside')

<div class="product-Analytics">   
	<div class="addProduct">
    	<h1>Order Successful</h1>
        <div class="field-item">
            <label for="">Thank you for your order&excl;</label>
            <div class="thanksMsg">Your order has been placed and is being processed. When the item(s) are shipped, you will receive an email with the details. You can track this order through <a href="javascript:();">My Orders</a> page</div>
    	</div>
    </div>
</div>
</div>
@endsection