@extends('Store::layouts.cart')
@section('content')
    <div class="mainContainer mt70">
        <div class="checkout-main">
            <div class="frm-container">
                <div class="form-main-block">
                    <h1>Make Payment
                    	<div class="payment_options"></div>
                    </h1>
                    @if(isset($e))
                        <div>
                            {{'Error description: ' . $e->getDescription()}}
                        </div>
                        <div>
                            {{'Error message: ' . $e->getMessage()}}
                        </div>
                    @endif
                    {!! Form::open(['url' => url('store/makePayment/'.$sellerBrandIdEncoded.'?method='.$method), "id" => "paymentForm",  "class"=>"form-block"]) !!}
                        <span id="paymentErrors"></span>

                        <div class="form-row">
                            <label>Name on Card</label>
                            <input data-worldpay="name" placeholder="Name on Card" name="name" type="text" />
                        </div>
                        <div class="form-row">
                            <label>Card Number</label>
                            <input data-worldpay="number" placeholder="Card Number" size="20" type="text" />
                        </div>
                        <div class="form-row">
                            <label>CVC</label>
                            <input data-worldpay="cvc" size="4" type="text" placeholder="cvc" />
                        </div>
                        <div class="form-row exp-date">
                            <label>Expiration (MM/YYYY)</label>
                            <input data-worldpay="exp-month" placeholder="MM" size="2" type="text" />
                            <label class="sep"> / </label>
                            <input data-worldpay="exp-year" placeholder="YYYY" size="4" type="text" />
                        </div>
                    </form>
                </div>
                
                <div class="continue-order">
                    <h1>Shipping info</h1>
                    <div class="cart-checkout">
                        <div class="cart-selected-main">
                            <div class="cart-selected-item">
                                <div class="cart-selected-title">
                                    <p class="ship_info"><strong>{{$address->first_name}}&nbsp;{{$address->last_name}}</strong></p>
                                    <p class="ship_info">{{$address->address1}}</p>
                                    <p class="ship_info">{{$address->city}}&nbsp;{{$address->state}}&nbsp;{{$address->zip_code}}</p>
                                    <p class="ship_info">{{countryNameById($address->country_id)}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="continue-order mt20">
                    <h1>Order Summary</h1>
                    <div class="cart-checkout">
                        <div class="cart-selected-main">
                        <?php $totatlPrice = 0; ?>
                        @if($cartProductsCount > 0)
                            @foreach($cartProducts as $brand_id => $products)
                                @foreach($products as $p)
                                    <?php
                                    $product      = getProductDetailsByID($p['product_id']);
                                    $productOwner = getBrandInfo($product->owner_id);
                                    $price = $product->price;
                                    if(!empty($product->discount)){
                                        $discount = ($product->price * $product->discount)/100;
                                        $price = $price - $discount;
                                    }
                                    $totatlPrice = $totatlPrice + ($price * $p['quantity']);
                                    ?>
                                <div class="cart-selected-item">
                                    <div class="cart-selected-title productsToBeOrder">
                                        <p class="selected-title">{{$product->title}}</p>
                                        @if(!empty($p['size_id']) || !empty($p['color_id']))
                                            <p class="selected-seller-name">
                                            @if(!empty($p['size_id']))
                                            <?php $size = getProductAttribute($p['size_id']); ?>
                                            <span>{{ucfirst($size->attribute)}}&nbsp;:&nbsp;{{$size->value}}</span>
                                            @endif
                                            @if(!empty($p['color_id']))
                                            <?php $color = getProductAttribute($p['color_id']); ?>
                                            <span class="ml10">{{ucfirst($color->attribute)}}&nbsp;:&nbsp;{{$color->value}}</span>
                                            @endif
                                        @endif
                                        <p class="selected-seller-name">Seller: {{$productOwner->displayname}}</p>
                                    </div>
                                    <div class="cart-selected-quantity">
                                        <p>Qty: {{$p['quantity']}}</p>
                                    </div>
                                    <div class="cart-selected-price">
                                        <p>Price: ${{format_currency($price)}}</p>
                                    </div>
                                </div>
                                @endforeach
                            @endforeach
                        @endif
                    </div>
                        <div class="total-charges">
                            <div>Sub Total: ${{format_currency($totatlPrice)}}</div>
                            <div>+ Shipping: ${{format_currency($totalShippingCost)}}</div>
                        </div>
                    	<div class="total">
                            <span class="total-item">Total: </span>
                            <span class="total-value">${{format_currency($totatlPrice + $totalShippingCost)}}</span>
                        </div>
                    </div>
                    <div class="proceed-container">
                        <img src="{{asset('local/public/assets/images/poweredByWorldPay.gif')}}">
                        <a href="javascript:void(0);" class="btn-proceed " id="place_order">Place Order</a>
                    </div>
                </div>
                <div class="clrfix"></div>
                	<div class="address-wrapper fltR">
                    	<div class="info-box">
                        	<h4>Our Company</h4>
                            <address>
                            	Kinnect2 Limited, Scotland<br/><br/>
                                <b>Address:</b> 95-107 Lancefield Street, Glasgow, G3 8HZ,<br/><br/>
                                <b>Registration:</b> SC442762<br/><br/>
                                <b>Phone:</b> 00441412219511<br/><br/>
                                <b>Email:</b> hassan@kinnect2.com
                            </address>
                        </div>
                    </div>
                <div class="clrfix"></div>
            </div>
        </div>
    </div>
    <style>
        #loading-div-background
        {
            display:none;
            position:fixed;
            top:0;
            left:0;
            background:black;
            width:100%;
            height:100%;
        }#loading-div
         {
             width: 300px;
             height: 200px;
             text-align:center;
             position:absolute;
             left: 50%;
             top: 50%;
             margin-left:-150px;
             margin-top: -100px;
         }


    </style>
    <div id="loading-div-background">
        <div id="loading-div" class="ui-corner-all" >
            <img style="width: 38px;" src="{!! asset('local/public/images/loading.gif') !!}" alt="Loading.."/>
            <h2 style="color:gray;font-weight:normal;margin-top: 25px;">Please wait....</h2>
        </div>
    </div>

    <?php
     $client_key = \Config::get('constants_brandstore.WORLDPAY_CLIENT_KEY');
     ?>
    <script src="https://cdn.worldpay.com/v1/worldpay.js"></script>
    <script type="text/javascript">

		jQuery(document).on("click",'#place_order',function(e){
			e.preventDefault();
            $("#loading-div-background").css({ opacity: 0.8 });
            $("#loading-div-background").show();
            //$("#loading-div-background").hide();

			jQuery('#paymentForm').submit();
		});
        var form = document.getElementById('paymentForm');

        Worldpay.useOwnForm({
            'clientKey': '{{$client_key}}',
            'form': form,
            'reusable': true,
            'callback': function(status, response) {
                document.getElementById('paymentErrors').innerHTML = '';
                if (response.error) {
                    $("#loading-div-background").css({ opacity: 0 });
                    $("#loading-div-background").hide();
                    Worldpay.handleError(form, document.getElementById('paymentErrors'), response.error);
                } else {
                    var token = response.token;
                    Worldpay.formBuilder(form, 'input', 'hidden', 'token', token);
                    form.submit();
                }
            }
        });
    </script>
@endsection
