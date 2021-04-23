@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

    @include('Store::includes.store-order-leftside')

    <div class="product-Analytics">
        <div class="addProduct">
            <h1>Order Successful</h1>
            <div class="field-item">
                <label for="">Thank you for your order&excl;</label>
                <?php $order_numbers = Session::get('order_numbers');?>
                <label>You order(s) numbers are below.</label>
                @if(!empty($order_numbers))
                @foreach($order_numbers as $order_number)
                <label>Seller: {{$order_number->seller->displayname}}</label>
                <label>Order Number: {{$order_number->order_number}}</label>
                @endforeach
                @endif
                <div class="thanksMsg">Your order has been placed and is being processed. When the item(s) are shipped, you will receive an email with the details. You can track this order through <a href="{{url('store/my-orders/')}}">My Orders</a> page</div>
            </div>
        </div>
    </div>
</div>
@endsection
