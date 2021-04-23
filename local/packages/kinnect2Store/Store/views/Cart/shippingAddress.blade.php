@extends('Store::layouts.cart')
@section('content')
    <div class="adminContainer mt70">
        <div class="checkout-main">
            <div class="frm-container">
                <div class="form-main-block">
                    <h1>Shipping Address</h1>
                    <style>
                        .backToLastStore{
                            position: absolute;
                            top: 0px;
                            right: 522px;
                            color: #ffffff;
                            background-color: #0080e8;
                            border: none;
                            border-radius: 8px;
                            padding: 7px 10px 7px 10px;
                        }
                    </style>
                    <a id="backToLastStore" class="backToLastStore" href="#">Continue Shopping</a>
                    <p class="mb10">
                        Please enter a shipping address for this order. Please also indicate whether your billing
                        address is the same as the shipping address entered. When finished, click the "Continue" button.
                        Or, if you're sending items to more than one address, click the "Add another address" button to
                        enter additional addresses
                    </p>

                    <div id="previousUsedAddresses" class="">
                        @foreach($previousAddresses as $previousAddress)
                            <div id="presetShippingAddress_{{$previousAddress->id}}" class="shipping-address-wrapper">
                                <div class="select-radio">
                                    <input id="selectPresetAddress_{{$previousAddress->id}}" value="{{$previousAddress->id}}" class="addressSelectionRadio" name="prevAddress" type="radio">
                                </div>
                                <div class="shipping-detail">
                                    <div class="detail-box">
                                        <div class="title">Name:</div>
                                        <div class="detail">{{$previousAddress->first_name.' '.$previousAddress->last_name}}</div>
                                    </div>
                                    <div class="detail-box">
                                        <div class="title">Address:</div>
                                        <div class="detail">{{$previousAddress->st_address_1.' '.$previousAddress->st_address_2}}</div>
                                    </div>
                                    <div class="detail-box">
                                        <div class="title">Country:</div>
                                        <div class="detail">{{countryNameById($previousAddress->country_id)}}</div>
                                    </div>
                                    <div class="detail-box">
                                        <div class="title">Mobile:</div>
                                        <div class="detail">{{$previousAddress->phone_number}}</div>
                                    </div>
                                </div>
                                <div class="edit-shipping">
                                    <a href="javascript:void(0);" id="edit_address_{{$previousAddress->id}}" class="edit-address">Edit</a> |
                                    <a href="javascript:void(0);" id="delete_address_{{$previousAddress->id}}" class="delete-address">Delete</a>
                                </div>
                            </div>
                        @endforeach
                        <a href="javascript:void(0);" class="add-new-ad add-new-address-btn">Add shipping address</a>

                    </div>

                    <div id="shippingProductsInfo"></div>

                    <div class="frm-container shipping-address-from-wrap" style="display: none;">

                        {!! Form::open(['url' => url("store/".Auth::user()->username."/add/shipping/address/".$sellerBrandIdEncoded), 'class' => 'form-block', 'id' => 'new_shipping_detail', "enctype"=>"multipart/form-data"]) !!}
                        <div class="select-country">
                            <input type="hidden" id="address_id" name="address_id" value="" />
                            {!! Form::label('Country or region') !!}<br/>
                            {!!  Form::select('countries', $countries, @$addressData['country_id'], ['class' => 'form-control' , 'id' => 'countryToBeShipped' ,'required' => 'required'])!!}
                        </div>
                        <div class="input-name mr20">
                            <label for="first_name">Your first name</label>
                            <input id="first_name" name="first_name" value="{{ @$addressData->first_name}}" type="text"
                                   placeholder="Your first name">
                            @if($errors->has('first_name'))
                                <span id="cat-error" style="color: red;">{{ $errors->first('first_name') }}</span>
                            @endif
                        </div>
                        <div class="input-name">
                            <label for="last_name">Your last name</label>
                            <input id="last_name" name="last_name" type="text" value="{{ @$addressData['last_name']}}"
                                   placeholder="Your last name">
                            @if($errors->has('last_name'))
                                <span id="cat-error" style="color: red;">{{ $errors->first('last_name') }}</span>
                            @endif
                        </div>

                        <div class="street-address">
                            <label for="address">Street address</label>
                            <input id="st_address_1" type="text" name="address1" value="{{ @$addressData['address1']}}"
                                   placeholder="Street address 1">
                            @if($errors->has('address1'))
                                <span id="cat-error" style="color: red;">{{ $errors->first('address1') }}</span>
                            @endif
                            <input class="mt10" type="text" id="st_address_2" name="address2" value="{{ @$addressData['address2']}}"
                                   value="" placeholder="Street address 2">
                            @if($errors->has('address2'))
                                <span id="cat-error" style="color: red;">{{ $errors->first('address2') }}</span>
                            @endif
                        </div>

                        <div class="city">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" value="{{ @$addressData['city']}}" placeholder="City Name">
                            @if($errors->has('city'))
                                <span id="cat-error" style="color: red;">{{ $errors->first('city') }}</span>
                            @endif
                        </div>
                        <div class="state">
                            <label for="state_province_region">State/Province/Region</label>
                            <input type="text" id="state" name="state_province_region"
                                   value="{{ @$addressData['satate']}}"
                                   placeholder="State/Province/Region">
                            @if($errors->has('state_province_region'))
                                <span id="cat-error"
                                      style="color: red;">{{ $errors->first('state_province_region') }}</span>
                            @endif
                        </div>
                        <div class="zip-code">
                            <label for="zip_code">Zip</label>
                            <input type="text" id="zip_code" name="zip_code" value="{{ @$addressData['zip_code']}}"
                                   placeholder="Zip">
                            @if($errors->has('zip_code'))
                                <span id="cat-error" style="color: red;">{{ $errors->first('zip_code') }}</span>
                            @endif
                        </div>
                        <div class="phone-number">
                            <label for="phone_number">Phone number</label>
                            <input type="tel" id="phone_number" name="phone_number" value="{{ @$addressData['phone_number']}}"
                                   placeholder="(e.g: +92-1111111111)">
                            @if($errors->has('phone_number'))
                                <span id="cat-error" style="color: red;">{{ $errors->first('phone_number') }}</span>
                            @endif
                        </div>
                        <div class="email-address">
                            <label for="email_address">Email address</label>
                            <span>We’ll email you an order confirmation</span>
                            <input type="text" value="{{ @$addressData['email_address']}}" id="email" name="email_address"
                                   placeholder="Email address">
                            @if($errors->has('email_address'))
                                <span id="cat-error" style="color: red;">{{ $errors->first('email_address') }}</span>
                            @endif
                        </div>
                        <div class="email-address">
                            <label for="re_enter_email">Re-enter your email address</label>
                            <input type="text" id="re_enter_email"  name="re_enter_email" value="{{ @$addressData['re_enter_email']}}"
                                   placeholder="Re-enter your email address">
                            @if($errors->has('re_enter_email'))
                                <span id="cat-error" style="color: red;">{{ $errors->first('re_enter_email') }}</span>
                            @endif
                        </div>
                        <input type="hidden" name="_token" value="{{Session::token()}}">
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="continue-order">
                    <h1>Order Summary</h1>

                    <div class="cart-checkout">
                        <div class="cart-selected-main">
                            <?php $totatlPrice = 0; ?>
                            @if($cartProductsCount > 0)
                                @foreach($cartProducts as $brand_id => $products)
                                    @foreach($products as $p)
                                    <?php
                                    $product = getProductDetailsByID($p['product_id']);
                                    $productOwner = getBrandInfo($brand_id);
                                    $price = $product->price;
                                    if(!empty($product->discount)){
                                        $discount = ($product->price * $product->discount)/100;
                                        $price = $price - $discount;
                                    }
                                    $totatlPrice = $totatlPrice + ($price * $p['quantity']);
                                    ?>
                                    <div class="cart-selected-item">
                                        <div class="cart-selected-title productsToBeOrder"
                                             id="product_in_cart_{{$product->id}}" data-id="{{$product->id}}">
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
                                            </p>
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
                            <div id="totalCost" class="totalCost">Sub Total: <span id="sub_total" class="sub_total">{{format_currency($totatlPrice)}}</span>
                            </div>
                            <div>+ Shipping: $<span id="shippingCost" class="shippingCost">0</span></div>
                        </div>
                        <div class="total">
                            <span class="total-item">Total: </span>
                            <span class="total-value">$<span id="new_total" class="new_total">{{format_currency($totatlPrice)}}</span></span>
                        </div>
                    </div>
                    <div class="proceed-container">
                        <span class="review-order">
                            Continue to review your order
                        </span>
                        <a href="javascript:void(0);" id="payment_redirect" class="btn-proceed ">Continue »</a>
                    </div>
                    <div class="payment_methods fltR mt20"></div>
                    <div class="clrfix"></div>
                    <div class="address-wrapper">
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
                </div>
            </div>
        </div>
    </div>
    <div class="modal-box delete" id="">
        <a href="#"  class="js-modal-close close">�</a>
        <div class="modal-body">
            <div class="edit-photo-poup">
                <h3 style="color: #0080e8;">Delete Address</h3>
                <p class="mt10" style="width: 315px;height: 26px;line-height: normal">Are You Sure You Want To delete this address? </p>
                <input type="button" class="btn fltL blue mr10" id="yes" value="Yes"/>
                <input type="button" id="no" class="btn blue js-modal-close fltL close" value="Cancel"/>
            </div>
        </div>
    </div>

    <script src="{!! asset('/local/public/assets/js/jquery.validate.min.js') !!}"></script>
    <style>
        span.error{color:#FF0000}
    </style>
    <script type="text/javascript">

<?php
if(isset($productOwner->username)){
        $lastStore = url('store/'.$productOwner->username);
}else{
        $lastStore = url('/');
}
?>
        jQuery(document).ready(function (e) {
            var lastStoreName = '{{$lastStore}}';

            $("#backToLastStore").attr('href', lastStoreName);
           $.validator.addMethod('customphone', function (value, element) {
              return this.optional(element) || /^[+]?([0-9]*[\.\s\-\(\)]|[0-9]+){3,24}$/.test(value);
            }, "Please enter a valid phone number");

            jQuery('#new_shipping_detail').validate({
                'errorElement': 'span',
                rules: {
                    'countryToBeShipped': {required: true},
                    'first_name': {required: true},
                    'last_name': {required: true},
                    'st_address_1': {required: true},
                    //'st_address_2': {required: true},
                    'city': {required: true},
                    'state_province_region': {required: true},
                    'zip_code': {
                        required: true,
                        minlength: 4,
                        digits: true
                    },
                    phone_number: 'customphone',
                    'email_address': { required: true, email: true},
                    're_enter_email': {required: true, equalTo: '#email'}
                }
            });
        });
    </script>
    <script type="text/javascript">
        var notAllowedToContinue = false;

        function checkAvailAbiliity() {
            var countryId = $("#countryToBeShipped").val();

            if (countryId == 0) {
                return false;
            }

            var productsToBeOrder = [];

            $(".productsToBeOrder").each(function () {
                productsToBeOrder.push($(this).attr('data-id'));
            });

            if(productsToBeOrder.length < 1){
                var url1 = '{{url('store/cart/your-cart-is-empty' )}}';
                window.location.href = url1;
            }
            var subTotal = $(".sub_total").html();
            
            jQuery.ajax({
                url: '{{url("store/checkProductShippingCountry")}}',
                type: "Post",
                data: {products_ids: productsToBeOrder, country_id: countryId,sub_total:subTotal},

                success: function (data) {

                    var shippingCost = $(".shippingCost").html(data.totalShippingCost);

                    var newTotal = data.grand_total;

                    $(".new_total").html(newTotal);

                    var productsAllowed = '<div id="allowedCountry">';

                    $.each(data.allowedProducts, function (key, val) {
                        productsAllowed += '<div class="allowedProducts">';
                        productsAllowed += $("#product_in_cart_" + val).html();
                        productsAllowed += '</div>';
                    });

                    productsAllowed += "</div>";

                    var countNotAllowedProduct = 0;
                    var productsNotAllowed = '<div class="product-alert"><div class="alert-div">Following items does not deliver to your selected Country/Region.</div>';

                    $.each(data.notAllowedProducts, function (key, val) {
                        countNotAllowedProduct++;
                        productsNotAllowed += '<div class="notAllowedItem">';
                        productsNotAllowed += '<a href="{{url('store/cart/delete-product/')}}/'+val+'" class="remove-items remove-product">Click here to remove</a>';
                        productsNotAllowed += $("#product_in_cart_" + val).html();
                        productsNotAllowed += '</div>';
                    });

                    productsNotAllowed += "</div>";

                    if (countNotAllowedProduct > 0) {

                        notAllowedToContinue = true;
                        $("#shippingProductsInfo").html(productsAllowed + productsNotAllowed);
                    } else {
                        notAllowedToContinue = false;
                        $("#shippingProductsInfo").html('');
                    }

                }, error: function (xhr, ajaxOptions, thrownError) {
                    console.log("ERROR:" + xhr.responseText + " - " + thrownError);
                }
            });
        }

        $(document).ready(function () {
            $(".shipping-address-from-wrap").hide();
            checkAvailAbiliity();
        });

        jQuery(document).on('change', '#countryToBeShipped', function (e) {
            checkAvailAbiliity();
        });

        jQuery(document).on('click','.remove-product',function (e) {
           e.preventDefault();
           var url = jQuery(this).attr('href');
           jQuery.ajax({
               url : url
           }).done(function (data) {
               window.location.reload();
           });
        });

        jQuery(document).on('click', '#payment_redirect', function (e) {
            var country = $("#countryToBeShipped").val();

            if(country == 0){
                $("#countryToBeShipped").val('');
            }
            if (notAllowedToContinue === true) {
                alert('Your order product are not allowed to be shipped in your addressed country, please try again with different country.');
                return false;
            }

            e.preventDefault();
            if (jQuery('#new_shipping_detail').valid()) {
                $(".shipping-address-from-wrap").show();
                jQuery('#new_shipping_detail').submit();
            }else{
                jQuery('.shipping-address-from-wrap').css('display','block')
            }
        });

        jQuery(document).on('click', '#payment_redirect_2', function (e) {
            var country = $("#countryToBeShipped").val();

            if(country == 0){
                $("#countryToBeShipped").val('');
            }
            if (notAllowedToContinue === true) {
                alert('Your order product are not allowed to be shipped in your addressed country, please try again with different country.');
                return false;
            }
            e.preventDefault();
            if (jQuery('#new_shipping_detail').valid()) {
                jQuery('#new_shipping_detail').submit();
            }
        });

        function validate() {
            var country = document.getElementById("form-control").value;
            if (country == 0) {
                document.getElementById("form-control").value = '';
                return false;
            }
        }

        jQuery(document).on('click', '.add-new-address-btn', function (e) {
            $(".shipping-address-from-wrap").show();

            $('#new_shipping_detail').find("input[type=text], input[type=hidden], textarea, tel").val("");

        });

        jQuery(document).on('click', '.addressSelectionRadio', function (e) {
            var id = e.target.id;

            id = id.match(/\d+/)[0];

            jQuery.ajax({
                url: '{{url("store/getEditAddressFormInfo")}}',
                type: "Post",
                data: {address_id: id},

                success: function (data) {

                    $.each(data.userAddressesInfo, function (key, val) {

                        if(key==='id'){
                            $("#address_id").val(val);
                        }

                        if(key==='country_id'){
                            $("#countryToBeShipped").val(val);
                        }

                       if( $("#"+key) != undefined){
                           $("#"+key).val(val);

                           if(key==='email'){
                               $("#re_enter_email").val(val);
                           }
                       }
                    });

                    checkAvailAbiliity();
                }, error: function (xhr, ajaxOptions, thrownError) {
                    alert("ERROR:" + xhr.responseText + " - " + thrownError);
                }
            });
        });

        jQuery(document).on('click', '.delete-address', function (e) {
            e.preventDefault();
            var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
            $("body").append(appendthis);

            $(".modal-overlay").fadeTo(500, 0.7);
            var id = e.target.id;
            id = id.match(/\d+/)[0];
            $("#address_id").val('');
            $(".delete").attr("id", 'popup2-' + id);
            $(".delete").show();
            $('#yes').click(function () {
                jQuery.ajax({
                    beforeSend: function(){


                    },
                    url: '{{url("store/sofDeleteAddressInfo")}}',
                    type: "Post",
                    data: {address_id: id},

                    success: function (data) {
                        if (data > 0) {
                            $(".delete").hide();
                            $('#presetShippingAddress_' + id).remove();
                            $(".modal-overlay").remove();
                            $("#presetShippingAddress_" + data).remove();

                        } else {
                            return false;
                        }

                    }, error: function (xhr, ajaxOptions, thrownError) {
                        alert("ERROR:" + xhr.responseText + " - " + thrownError);
                    }
                });

            });
            $('#no').click(function () {
                $('body').css({'overflow-y': 'auto', 'position': 'static', 'width': 'auto'});
                $(".modal-box, .modal-overlay").fadeOut(500, function () {
                    $(".modal-overlay").remove();
                });
                $(".delete").hide();
                return false;
            });
            $('.close').click(function () {
                $('body').css({'overflow-y': 'auto', 'position': 'static', 'width': 'auto'});
                $(".modal-box, .modal-overlay").fadeOut(500, function () {
                    $(".modal-overlay").remove();
                });
                $(".delete").hide();
                return false;
            });
        });

        jQuery(document).on('click', '.add-new-address-btn', function (e) {
            event.preventDefault();

            $(".payment_redirect_2").remove();

            $("#new_shipping_detail").append('<div class="proceed-container payment_redirect_2"><span class="review-order">Continue to review your order</span><a href="javascript:void(0);" id="payment_redirect_2" class="btn-proceed ">Continue »</a></div>');

            var target = "#new_shipping_detail";
            $('html, body').animate({
                scrollTop: $(target).offset().top - 100
            }, 2000);

        });

        jQuery(document).on('click', '.edit-address', function (e) {
            $(".shipping-address-from-wrap").show();

            var id = e.target.id;

            id = id.match(/\d+/)[0];
            $("#selectPresetAddress_"+id).click();

            event.preventDefault();

            $(".payment_redirect_2").remove();

            $("#new_shipping_detail").append('<div class="proceed-container payment_redirect_2"><span class="review-order">Continue to review your order</span><a href="javascript:void(0);" id="payment_redirect_2" class="btn-proceed ">Continue »</a></div>');

            var target = "#new_shipping_detail";
            $('html, body').animate({
                scrollTop: $(target).offset().top - 100
            }, 2000);

        });
        $(document).ready(function(){
            $('#previousUsedAddresses .shipping-address-wrapper:first-child').find("input[type=radio]").click();
            checkAvailAbiliity();
        });

        $(document).ready(function () {
            var form =  $( "input[name*='selectPresetAddress']" ).val();
            if(form == 'on')
            {
                $(".shipping-address-from-wrap").hide();
            }else{
                $(".shipping-address-from-wrap").show();
            }

        });
    </script>
@endsection
