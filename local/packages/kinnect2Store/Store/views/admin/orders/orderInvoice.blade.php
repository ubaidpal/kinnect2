@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

    @include('Store::includes.store-admin-leftside')

    <div class="product-Analytics">
        <div class="post-box">
            <!-- Order Detail -->
            <div class="o-detail">
                <div class="od-item">
                    <div class="od-iteml">Order Number :</div>
                    <div class="od-itemr">{{$order->order_number}}</div>
                </div>
                <div class="od-item">
                    <div class="od-iteml">Status :</div>
                    <div class="od-itemr"><?php $orderStatus = getStatusForSellerOrderById($order->status);
                        echo $orderStatus['status'] ?></div>
                </div>
                @if(\Config::get('constants_brandstore.ORDER_CANCELED') == $order->status)
                    <div class="od-item">
                        <div class="od-iteml">Reason:</div>
                        <div class="od-itemr">{{\Config::get('constants_brandstore.ORDER_CANCEL_REASONS.'.$order->cancellation_reason)}}</div>
                    </div>
                @endif
                <div class="od-item">
                    <div class="od-iteml">Reminder :</div>
                    <div class="od-itemr">{{ $orderStatus['reminder']}}</div>
                </div>
            </div>

            <!-- Order Detail Label -->
            <div class="od-label">Shipping Information</div>

            <!-- Order Detail Shipping Information -->
            <div class="od-ship">
                <!-- Order Detail Shipping Information - Title -->
                <div class="ods-title">
                    <div class="ods-title-item">Courier Company</div>
                    <div class="ods-title-item">Tracking Number</div>
                    <div class="ods-title-item">Estimated Delivery Time</div>
                    <div class="ods-title-item"><!--Processing Time--></div>
                </div>

                <!-- Order Detail Shipping Information - Content -->
                <div class="ods-content">
                    <div class="ods-content-title">
                        @if(isset($orderCourier->id))
                            <div class="ods-ct-item">{{$orderCourier->courier_service_name}}</div>
                            <div class="ods-ct-item">{{$orderCourier->order_tracking_number}}</div>
                            <div class="ods-ct-item">{{$orderCourier->delivery_estimated_time}}</div>
                            <div class="ods-ct-item">{{humanDifferenceInDateNow($orderCourier->date_to_be_delivered)}}</div>
                        @else
                            <div class="ods-ct-item">Order is not dispatched.</div>
                        @endif
                    </div>

                    <div class="ods-content-detail">
                        @foreach($orderAddresses as $orderAddress)
                            <div class="ods-cd-item">
                                <div class="ods-cd-title">Ship to:</div>
                                <div class="ods-cd-detail">
                                    <div class="ods-cdd-item">
                                        <div class="ods-cdd-iteml">Contact Name :</div>
                                        <div class="ods-cdd-itemr">{{$orderAddress->first_name.' '. $orderAddress->last_name}}</div>
                                    </div>
                                    <div class="ods-cdd-item">
                                        <div class="ods-cdd-iteml">Address :</div>
                                        <div class="ods-cdd-itemr">{{$orderAddress->st_address_1.' '.$orderAddress->st_address_2}}</div>
                                    </div>
                                    <div class="ods-cdd-item">
                                        <div class="ods-cdd-iteml">Contact Name :</div>
                                        <div class="ods-cdd-itemr">{{$orderAddress->first_name.' '. $orderAddress->last_name}}</div>
                                    </div>
                                    <div class="ods-cdd-item">
                                        <div class="ods-cdd-iteml">Zip Code :</div>
                                        <div class="ods-cdd-itemr">{{$orderAddress->zip_code}}</div>
                                    </div>
                                    <div class="ods-cdd-item">
                                        <div class="ods-cdd-iteml">Mobile :</div>
                                        <div class="ods-cdd-itemr">{{$orderAddress->phone_number}}</div>
                                    </div>
                                    <div class="ods-cdd-item">
                                        <div class="ods-cdd-iteml">Tel :</div>
                                        <div class="ods-cdd-itemr">{{$orderAddress->phone_number}}</div>
                                    </div>
                                </div>
                            </div>
                            <div style="clear:both;"></div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Detail Label -->
            <div class="od-label">Financial Information</div>

            <!-- Order Detail Label - Small -->
            <div class="od-labels">Total Amount:</div>

            <div class="ods-title">
                <div class="ods-title-item">Price</div>
                <div class="ods-title-item">Discount</div>
                <div class="ods-title-item">Shipping Cost</div>
                <div class="ods-title-item">Total Amount</div>
            </div>
            <div class="ods-title ods-title-ammount">
                <div class="ods-title-item">&dollar; {{format_currency($order->total_price - $order->total_shiping_cost)}}</div>
                <div class="ods-title-item">&dollar;{{format_currency($order->total_discount)}}</div>
                <div class="ods-title-item">&dollar; {{format_currency($order->total_shiping_cost)}}</div>
                <div class="ods-title-item">&dollar; {{format_currency($order->total_price - $order->total_discount) }}</div>
            </div>

            <!-- Order Detail Label - Small -->
            <div class="od-labels">Payment Received:</div>
            <div class="ods-title">
                <div class="ods-title-item">Total</div>
                <div class="ods-title-item">Received</div>
                <div class="ods-title-item">Payment Method</div>
                <div class="ods-title-item">Date</div>
            </div>

            @foreach($orderPayments as $orderPayment)
                <div class="ods-title ods-title-ammount">
                    <div class="ods-title-item">USD </div>
                    <div class="ods-title-item">&dollar;{{format_currency($orderPayment->amount)}}</div>
                    <div class="ods-title-item">{{getPaymentGateway($orderPayment->gateway_id)}}</div>
                    <div class="ods-title-item">{{$orderPayment->created_at}}</div>
                </div>
                @endforeach

                <!-- Order Detail Label -->
                <div class="od-label">Order Details</div>
                <div class="ods-title ods-title5">
                    <div class="ods-title-item">Product Details</div>
                    <div class="ods-title-item">Price Per Unit</div>
                    <div class="ods-title-item">Discount</div>
                    <div class="ods-title-item">Quantity</div>
                    <div class="ods-title-item">Order Total</div>
                    <div class="ods-title-item">Status</div>
                </div>
                <?php $orderAllProducts = getOrderAllProducts( $order->id ); ?>
                @foreach($orderAllProducts as $orderProduct)
                <?php $product = getProductDetailsByID( $orderProduct->product_id ); ?>
                <div class="orderb-item">
                        <div class="oi-header orderDetail">
                            <div class="oi-image">
                                <div class="oi-product">
                                    <a class="product-img" href="{{getProductUrlByIdAndOwnerId($orderProduct->product_id, $product->owner_id)}}">
                                        <?php $imageThumb = getThumbSrcWithProductId( $product->id, 'product_thumb' ) ?>
                                        <img width="100" height="100" src="{{$imageThumb}}" alt="IMAGE"></a>

                                    <div class="oi-title mb10">{{$product->title}}</div>
                                    <div class="">
                                        <?php
                                        $attributes = getStoreItemAttributes($orderProduct->id);
                                        ?>
                                        @if(!empty($attributes))
                                            @foreach($attributes as $key => $value)
                                                <?php $productAttribute =  getProductAttribute($key)?>
                                                <span style="display: block;">{{ucfirst($productAttribute->attribute)}}:&nbsp;{{$productAttribute->value}}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="oi-amount">
                                <?php $discountValue = ($product->price / 100) * $product->discount ?>


                                <p class="oi-price">${{format_currency($orderProduct->product_price)}}</p>
                            </div>
                            <div class="oi-quantity"><p class="oi-price">&dollar;{{format_currency($discountValue)}}</p></div>
                            <div class="oi-quantity">
                                <p class="oi-price">{{$orderProduct->quantity}} Piece</p>

                            </div>
                            <div class="oi-amount">&dollar;{{format_currency($discountValue)}}</div>
                            <div class="oi-amount">
                                {{$orderProduct->quantity}} Piece
                            </div>
                            <div class="oi-amount">
                                ${{($product->price - $discountValue ) * $orderProduct->quantity}}
                            </div>
                            <div class="oi-amount"><?php echo $orderStatus['status'] ?></div>
                        </div>

                        @endforeach
                        <div class="product-total">
                            <div class="product-amount">
                                <div class="title">Total Amount</div>
                                <div class="value">USD &dollar; {{format_currency($order->total_price - $order->total_discount)}}</div>
                            </div>
                            <div class="shipping-amount">
                                <div class="title">Shipping Cost</div>
                                <div class="value">USD &dollar; {{format_currency($order->total_shiping_cost)}}</div>
                            </div>

                            <div>
                                <div class="title">Discount</div>

                                <div class="value">{{format_currency($order->total_discount)}}</div>
                            </div>
                            <div class="total-cost">
                                <div class="title">Product Amount</div>
                                <div class="value">USD &dollar; {{format_currency($order->total_price - $order->total_shiping_cost)}}</div>
                            </div>
                        </div>
                    </div>
        </div>
    </div>
</div>
@endsection
