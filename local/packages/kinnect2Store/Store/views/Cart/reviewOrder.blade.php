@extends('Store::layouts.cart')
@section('content')
<div class="mainContainer mt70">
    <div class="checkout-main">
        <div class="frm-container">
            <div class="form-main-block">
                <h1>Your shipping information <a href="{{url('store/'.Auth::user()->username.'/shipping/address/'.$sellerBrandIdEncoded)}}" class="edit">Edit</a></h1>
                <div class="shipping-info-wrapper">
                    <div class="shipping-info">
                        <div class="text">Name:</div>
                        <div class="value">{{$address['first_name'].' '.$address['last_name']}}</div>
                    </div>
                    <div class="shipping-info">
                        <div class="text">Address:</div>
                        <div class="value">{{$address['address1'].' '.$address['city'].' '.$address['state_province_region']}}</div>
                    </div>
                    <div class="shipping-info">
                        <div class="text">Mobile:</div>
                        <div class="value">{{$address['phone_number']}}</div>
                    </div>

                </div>
                <h1>Choose payment method</h1>
                {!! Form::open(['url' => url('store/pay/'.$sellerBrandIdEncoded), "id" => "orderForm", "method"=>"get"]) !!}
                    <div class="payment-block">
                        <div class="choose-method">
                            <label><input type="radio" name="payment_type" value="card">&nbsp;Credit card</label>
                        </div>
                        <div class="method-options">
                            <img src="" alt=""/>
                        </div>
                    </div>
                    <!--<div class="payment-block">
                        <div class="choose-method">
                            <label><input type="radio" name="payment_type" value="paypal">&nbsp;PayPal</label>
                        </div>
                        <div class="method-options">
                            <img src="" alt=""/>
                        </div>
                    </div>-->
                    <div id="typeError" style="color:#b40000;@if(empty($payment_type_error))display: none;@endif" class="error">
                        Please select payment method
                    </div>
                </form>
            </div>
            <div class="continue-order">
                <h1>Order Summary</h1>
                <div class="cart-checkout">
                    <div class="cart-selected-main">
                        <?php $totatlPrice = 0; ?>
                        @if(count($cartProducts) > 0)
                            @foreach($cartProducts as $p)
                                @if(isset($p['product_id']) )
                                    <?php
                                    $product      = getProductDetailsByID($p['product_id']);
                                    ?>

                                        <?php
                                        $productOwner = getUserDetail($product->owner_id);

                                        if(!isset($productOwner->id)){
                                            continue;
                                        }else{
                                            if($sellerBrandId != $productOwner->id){
                                                if($sellerBrandId != 'buy-all'){
                                                    continue;
                                                }else{
                                                    $price = $product->price;
                                                    if(!empty($product->discount)){
                                                        $discount = ($product->price * $product->discount)/100;
                                                        $price = $price - $discount;
                                                    }
                                                    $totatlPrice = $totatlPrice + $price;
                                                }
                                            }else{
                                                $price = $product->price;
                                                if(!empty($product->discount)){
                                                    $discount = ($product->price * $product->discount)/100;
                                                    $price = $price - $discount;
                                                }
                                                $totatlPrice = $totatlPrice + $price;
                                            }
                                        }
                                        ?>
                                    @else
                                        <?php continue; ?>
                                    @endif
                                <div class="cart-selected-item">
                                    <div class="cart-selected-title productsToBeOrder">
                                        <p class="selected-title">{{$product->title}}</p>
                                        <p class="selected-seller-name">Seller: {{$productOwner->displayname}}</p>
                                    </div>
                                    <div class="cart-selected-quantity">
                                        <p>Qty: {{$p['quantity_id']}}</p>
                                    </div>
                                    <div class="cart-selected-price">
                                        <p>Price: ${{format_currency($price)}}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="total-charges">
                        <div>Total: ${{format_currency($totatlPrice)}}</div>
                        <div>+ Shipping: ${{format_currency($totalShippingCost)}}</div>
                    </div>
                    <div class="total">
                        <span class="total-item">Total: </span>
                        <span class="total-value">${{format_currency($totatlPrice + $totalShippingCost)}}</span>
                    </div>
                    </div>
					<div class="proceed-container">
                        <span class="review-order">
                            Go to payment screen
                        </span>
                        <a class="btn-proceed" href="#" id="continue">Continue &raquo;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.ads-right-side')

    <script type="text/javascript">

        var productsToBeOrder = [];

        $(".productsToBeOrder").each(function () {
            productsToBeOrder.push($(this).attr('data-id'));
        });

        if(productsToBeOrder.length < 1){
            var url1 = '{{url('store/cart/your-cart-is-empty' )}}';
            window.location.href = url1;
        }

        $(document).on('click','#continue', function (e) {
            e.preventDefault();
            var payment_type = jQuery('input[name="payment_type"]:checked').val();
            jQuery('#typeError').hide();
            if(typeof payment_type == 'undefined'){
                jQuery('#typeError').show();
            }else{
                jQuery('#orderForm').submit();
            }
        });
    </script>
@endsection
