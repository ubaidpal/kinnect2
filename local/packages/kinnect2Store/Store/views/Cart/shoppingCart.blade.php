@extends('Store::layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

@include('Store::includes.store-order-leftside')
<div id="shopping_cart_details">
    <div class="brand-store" id="shopping_cart_container">
        <div class="brand-store-title">
            <span>Your Kinnect2 Shopping Cart</span>
            <div class="payment_methods fltR"></div>
        </div>
        <?php $totalItems = $subTotal = $totalSaving = 0; ?>
          @if($countCartProducts > 0)
            <?php $totalSellers = 0; ?>
            @foreach($cartProducts as $brand_id => $products)
                <?php $totalSellers++; ?>
                <div class="cart-item">
                    <div class="cart-seller-title">
                        <div class="seller-name">
                            <?php
                            $brand = getBrandInfo($brand_id);
                            ?>
                            Seller: <span><a href="{{url('store/'.$brand->username)}}" title="Click to got to store of '{{$brand->displayname}}'">{{$brand->displayname}}</a></span>
                        </div>
                        <div class="price-title">
                            <span>Price</span>
                        </div>
                        <div class="quantity-title">
                            <span>Quantity</span>
                        </div>
                    </div>

                    @foreach($products as $product)

                        <?php $pro = getProductDetailsByID($product['product_id']); ?>
                        <div class="cart-item-detail">
                            <div class="cart-item-img">
                                <img src="{!! getProductPhotoSrc('','',$product['product_id'], 'product_thumb') !!}" alt="img">
                            </div>
                            <div class="cart-item-title">
                                <p class="cart-item-name">
                                    {{$pro->title}}
                                </p>
                                @if(!empty($product['size_id']))
                                <?php $size = getProductAttribute($product['size_id']); ?>
                                    <span>{{ucfirst($size->attribute)}}&nbsp;:&nbsp;{{$size->value}}</span>
                                @endif
                                @if(!empty($product['color_id']))
                                    <?php $color = getProductAttribute($product['color_id']); ?>
                                    <span class="ml10">{{ucfirst($color->attribute)}}&nbsp;:&nbsp;{{$color->value}}</span>
                                @endif

                                @if(($pro->quantity - $product['quantity']) >= 0)
                                    <p id="over_flow_{{$pro->id}}" class="cart-availability mt5">
                                        In Stock
                                    </p>
                                @else
                                    <p id="over_flow_{{$pro->id}}" class="cart-availability"  style="color: red;font-style:oblique;font-size: 12px;">
                                        Out Of Stock, Please keep quantity minimum {{$pro->quantity}}  or equal for
                                        this product..
                                    </p>
                                @endif
                            </div>
                            <div class="cart-item-price">
                                <?php
                                $price = $pro->price;
                                $discount = 0.00;
                                if(!empty($pro->discount)){
                                    $discount = ($pro->discount * $pro->price)/100;
                                    $price = $price - $discount;
                                }
                                ?>
                                <span>${{format_currency($price)}}</span>
                            </div>
                            <?php
                                $totalItems  = $totalItems  + ($product['quantity']);
                                $subTotal    = $subTotal    + ($price * $product['quantity']);
                                $totalSaving = $totalSaving + ($discount * $product['quantity']);
                            ?>
                            <div class="cart-item-quantity">
                                <input id="product_quatity_<?php echo $pro->id ?>" name="product_quatity" value="{{$product['quantity']}}"
                                oninput="quantityUpdate('<?php echo $product['quantity'] ?>','<?php echo $pro->id ?>', event)"
                                type="number" style="width: 40px;border: medium none;height: 20px;" min="1">
                            </div>
                        </div>
                        <div class="btn-del-save">
                            <a class="del-product" href="{{url('store/cart/delete-product/'.$product['product_id'])}}">Remove from cart</a>
                            <!--<span class="seperator"></span>
                            <a href="javascript:void(0);">Save for Later</a>-->
                        </div>

                    @endforeach
                    <div class="cart-checkout p20">
                        <div class="subtotal">
                            <span class="subtotal-item">Subtotal <em>( @if($countCartProducts > 0){{$totalItems}}@else 0 @endif Items): </em></span>
                            <span class="subtotal-value">${{format_currency($subTotal)}}</span>
                        </div>

                        <div class="saving">
                            <span class="saving-item">Total Saving: </span>
                            <span class="saving-value">${{format_currency($totalSaving)}}</span>
                        </div>
                        <div class="total products-checkout">
                            <span class="total-item">Total: </span>
                            <span class="total-value">${{format_currency($subTotal)}}</span>
                        </div>

                        @if($countCartProducts > 0)
                            <div class="cart-btn-wrapper">
                                <a class="btn-continue"  href="{{url('store/'.$brand->username)}}">Continue Shopping</a>
                                <a class="btn-proceed" id="btn-proceed" href="{{url('store/'.Auth::user()->username.'/shipping/address/'.\Vinkla\Hashids\Facades\Hashids::encode($brand_id))}}">Proceed to checkout</a>
                            </div>
                        @else
                        	
                            <div class="cart-btn-wrapper">
                                <a class="btn-continue"  href="url('store/'.$brand->username)">Continue Shopping</a>
                            </div>
                        @endif
                        <?php $subTotal = $totalSaving = 0; ?>
                    </div>
                </div>
            @endforeach
                @if($countCartProducts > 0)
                <div class="cart-item">
                    @if($totalSellers > 1)
                    <div class="cart-btn-wrapper">
                        <a class="btn-continue"  href="{{url('store/'.$brand->username)}}">Continue Shopping</a>
                        <a class="btn-proceed" id="btn-proceed" href="{{url('store/'.Auth::user()->username.'/shipping/address/buy-all')}}">Proceed to checkout( Buy All)</a>
                    </div>
                    @endif
                    @else
                        <div class="cart-btn-wrapper">
                            <a class="btn-continue"  href="url('store/'.$brand->username)">Continue Shopping</a>
                        </div>
                    @endif
                </div>
          @else
            <div class="cart-message">Your shopping cart is empty</div>
          @endif
    </div>
</div>
@include('includes.ads-right-side')

</div>
<style type="text/css">.error{color:#FF0000}</style>
<script type="text/javascript">
jQuery(document).on('click','.del-product',function (e) {
    e.preventDefault();
    var url = jQuery(this).attr('href');
    jQuery.ajax({
       url : url,
    }).done(function (data) {
        if(data.status == 1){
            updateCart();
        }
    });
});

var timer = null;
function quantityUpdate(prev_quantity, product_id, e){
    e.preventDefault();
    window.clearTimeout(timer);
    timer = window.setTimeout(function() {
        updateProductQuantity(prev_quantity,product_id);
    }, 500);
}
updateProductQuantity = function (prev_quantity,product_id) {
    var quantity =$('#product_quatity_'+product_id).val();

    if(prev_quantity == quantity){
        return false;
    }
    else{
        var a = quantity - prev_quantity;
    }
    var dataString = {
        quantity: quantity,
        product_id: product_id
    };

    $.ajax({
        type: 'POST',
        url: '{{url('store/cart/quantityUpdate')}}',
        data: dataString,
        success: function (response) {
            if(response.message == 'quantity_overflow'){
                jQuery('#over_flow_'+product_id).text(response.message_text).addClass('error');
            }else{
                jQuery('#over_flow_'+product_id).text('');
                updateCart();
            }
        }
    });
}
updateCart = function(){
    jQuery('#shopping_cart_details').load("{{url('/store/cart #shopping_cart_container')}}",function (response, status, xhr) {
        jQuery('#the_cart').html(jQuery(response).find('#the_cart').html());
    });
}

</script>

@endsection
