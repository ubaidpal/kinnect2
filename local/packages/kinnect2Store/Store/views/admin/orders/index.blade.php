@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">
    @include('Store::includes.store-admin-leftside')
    <style>
        .confirm_order_btns{
            color: #ffffff !important;
            float: left !important;
            width: 120px !important;
        }
    </style>
    <div class="product-Analytics">
        <div class="post-box target">
            <h1>Orders</h1>

            <div class="bsm-nav">
                @foreach($countOrdersStatusWise as $key => $value)
                    <?php
                    $class = '';
                    if($key == $status){
                        $class = 'filter-tab-active';
                    }
                    if(empty($status) && $key == 'All'){
                        $class = 'filter-tab-active';
                    }
                    ?>
                    @if($key == 'ORDER_DISPATCHED')
                    <a href="{{url('store/'.Auth::user()->username.'/admin/orders?status='.$key)}}" style="text-transform: capitalize;" class="filter_orders {{$class}}" title="Awaiting Acceptance ">Awaiting Acceptance ({{$value}})</a>
                    @elseif($key == 'ORDER_DISPUTED')
                    <a href="{{url('store/'.Auth::user()->username.'/admin/orders?status='.$key)}}" style="text-transform: capitalize;" class="filter_orders {{$class}}" title="Refund Requests">Refund Requests ({{$value}})</a>
                    @else
                        <?php $mod_key = strtolower(str_replace('_',' ',str_replace('ORDER_','',$key))); ?>
                    <a href="{{url('store/'.Auth::user()->username.'/admin/orders?status='.$key)}}" style="text-transform: capitalize;" class="filter_orders {{$class}}" title="{{$mod_key}}">{{$mod_key}} ({{$value}})</a>
                    @endif
                @endforeach
            </div>

            <div class="selectdiv mb10">
                <input type="text" name="search_order_number" id="search_order_number" placeholder="Search By Order Number"
                       class="storeInput fltL mr10 w_200 search_order">
                <input type="text" name="search_product_name" id="search_product_name" placeholder="Search By Product"
                       class="storeInput fltL mr10 w_200 search_order">
                <!--<a href="javascript:void(0);" class="btn blue fltL search-order-btn">Search</a>-->
            </div>
            <div class="bsmo-nav">
                <div class="bsmo-nav-itm pdt">Product</div>
                <div class="bsmo-nav-itm">Unit Price</div>
                <div class="bsmo-nav-itm">Discount</div>
                <div class="bsmo-nav-itm">Quantity</div>
                <div class="bsmo-nav-itm">Amount</div>
            </div>
            @foreach($allOrders as $order)
                <div class="orderb-item orderBox order_item_{{$order->id}}">
                    <div class="product-header ph-pset">
                        <?php $orderAllProducts = getOrderAllProducts($order->id); ?>
                        <div class="oi-header">
                            <div class="oi-image">
                                @foreach($orderAllProducts as $orderProduct)
                                    <?php $product = getProductDetailsByID($orderProduct->product_id);//Complete detail of product
                                    if (!isset($product->id)) {
                                        continue;
                                    }?>

                                    <div class="oi-product">
                                        <div class="oi-product-item pdt">
                                            <a class="product-img"
                                               href="{{getProductUrlByIdAndOwnerId($orderProduct->product_id, $product->owner_id)}}">
                                                <?php $imageThumb = getThumbSrcWithProductId($product->id, 'product_thumb') ?>
                                                <img class="product-image" width="100" height="100" src="{{$imageThumb}}"
                                                     alt="IMAGE"></a>
                                                <span>{{$product->title}}</span>
                                            </div>
                                        <?php $discountedPercented = ($orderProduct->product_price / 100) * $orderProduct->product_discount ?>
                                        <div class="oi-product-item">&dollar;{{format_currency($orderProduct->product_price)}}</div>
                                        <div class="oi-product-item">&dollar;{{format_currency($discountedPercented)}}</div>

                                        <div class="oi-product-item order_product_qty_{{$order->id}}">{{$orderProduct->quantity}}</div>

                                        <div class="oi-product-item opi-txtb">${{$orderProduct->product_price - $discountedPercented}}</div>
                                    </div>
                                @endforeach

                            </div>

                        </div>
                        <div class="oi-footer">
                            <div class="oi-detail">
                                <p class="mb5">
                                    <?php if(!isset($product->owner_id)){$storeName = 'Kinnect2 Store';}else{ $storeName = getUserNameByUserId($product->owner_id); }?>
                                    <?php $buyer = getUserDetail($order->customer_id);      ?>
                                    Order ID: {{$order->order_number}} <a
                                            href="{{url('store/'.$storeName.'/admin/order-invoice/'.$order->id)}}" title="View Detail">View
                                        Detail</a>
                                </p>

                                <p>
                                    Order time & date: {{$order->created_at}}
                                </p>
                            </div>
                            <div class="oi-profile w230x">
                                @if(isset($product->owner_id))

                                    <p class="mb5">
                                        Buyer Name: <a
                                                target="_blank"
                                                href="{{profileAddress( $buyer )}}"> <?php if(isset($buyer->id)){echo $buyerName = ucfirst($buyer->displayname);}else{echo 'Kinnect2 User'; } ?></a>
                                    </p>
                                @endif
                            </div>
                            <?php $productPrice = $order->total_price - $order->total_discount; ?>
                            <div class="oi-amount-container">
                                <div class="oi-ship-cost">
                                    <div class="oi-sc-txt">Shipping Cost:</div>
                                    <div class="oi-sc-value">${{format_currency($order->total_shiping_cost)}}</div>
                                </div>
                                <div class="oi-amount-total">
                                    <div class="oi-amount-t-txt">Order Amount:</div>
                                    <div class="oi-amount-t-value">&dollar;{{format_currency($productPrice)}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clrfix"></div>

                    <div id="orderStatusWrap" class="orderStatusWrap">
                        <?php
                        if (!isset($product->id)) {
                            $data['class'] = '';
                            $data['action_btn_1'] = '';
                            $data['action_btn_2'] = '';
                            $data['status'] = 'Product Deleted';
                            $productPrice = '';
                        } else {
                            $data = getOrderStatusForSeller($order->id, $order->status);
                        }?>
                        <div class="oi-action order_action_{{$order->id}} {{$data['class']}}">
                            {{--<a class="btn" href="javascript:void(0);"></a>--}}
                            {{--<a class="btn btng" href="javascript:void(0);"></a>--}}
                            {!! $data['action_btn_1'] !!}
                            {!! $data['action_btn_2'] !!}
                        </div>
                        <div class="oi-status order_status_{{$order->id}}">
                            <span>Order Status</span>{{$data['status']}}
                            @if(\Config::get('constants_brandstore.ORDER_CANCELED') == $order->status)
                                <span>Reason:&nbsp;{{\Config::get('constants_brandstore.ORDER_CANCEL_REASONS.'.$order->cancellation_reason)}}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="store-pagination">
                {!! $allOrders->appends(['status' => $status])->render() !!}
            </div>
        </div>
        @include('Store::profile-view-links')
    </div>
</div>

<div id="confirmationOfOrderDelete" class="modal-box">
    <a href="#"  class="js-modal-close close">&times;</a>
    <div class="modal-body">
        <div class="edit-photo-poup">
            <h3 style="color: #0080e8;">Delete Order</h3>
            <p class="mt10 mb10">Are you sure?</p>
            <input type="button" class="btn fltL blue mr10 confrim_delete_order" value="Confirm"/>
            <input type="button" class="btn blue js-modal-close fltL close" value="Cancel"/>
            <input type="hidden" id="delete_order_id" value="">
        </div>
    </div>
</div>

<script type="text/javascript" src="{!! asset('local/public/assets/js/jquery-ui.min.js') !!}"></script>
<link rel="stylesheet" href="{!! asset('local/public/assets/css/jquery-ui.min.css') !!}">
<script type="text/javascript">
    $(document).on('click', ".order_delete_btn", function (evt) {
        jQuery('#delete_order_id').val(evt.target.id);
        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
        $("body").append(appendthis);
        $(".modal-overlay").fadeTo(500, 0.7);
        jQuery('#confirmationOfOrderDelete').show();
    });

    jQuery(document).on('click','.confrim_delete_order',function (e) {
        var order_info = jQuery('#delete_order_id').val();
        jQuery.ajax({
            url: '{{url("store/".Auth::user()->username."/admin/order/delete")}}',
            type: "Post",
            data: {order_info: order_info},
            success: function (data) {
                if (/^\d+$/.test(data) != 1) {
                    alert(data);
                    return false;
                }
                $(".order_item_" + data).remove();

                $(".modal-box, .modal-overlay").fadeOut(500, function () {
                    $(".modal-overlay").remove();
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("ERROR:" + xhr.responseText + " - " + thrownError);
            }
        });
    });

    $(document).on('click', ".order_status_btn", function (evt) {
        var order_info = evt.target.id;
        jQuery.ajax({
            url: '{{url("store/".Auth::user()->username."/admin/update/order-status")}}',
            type: "Post",
            data: {order_info: order_info},
            success: function (data) {
                //{"class":"shipped","status":"Awaiting receiver approval","action_btn_1":"","action_btn_2":""}
                $(".order_action_brn_" + data.order_id).remove();
                $(".order_action_" + data.order_id).html(data.action_btn_1 + data.action_btn_2);
                $(".order_status_" + data.order_id).html(data.status);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("ERROR:" + xhr.responseText + " - " + thrownError);
            }
        });
    });
    $(document).on('keyup', ".search_order", function (evt) {
        var order_number = $("#search_order_number").val();
        var product_name = $("#search_product_name").val();
        jQuery.ajax({
            url: '{{url("store/".Auth::user()->username."/admin/serach-my-orders")}}',
            type: "Post",
            data: {order_number: order_number, product_name: product_name},
            success: function (data) {
                $("#nothing_found").remove();
                if (typeof  data === 'string') {
                    $(".orderb-item").remove();
                    $(data).insertAfter(".bsmo-nav");
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("ERROR:" + xhr.responseText + " - " + thrownError);
            }
        });
    });
</script>
@endsection

@section('footer-scripts')
    <style>
        .hide {
            display: none;
        }

        .profile-content {
            margin-left: 0;
        }
    </style>
    <script src="{!! asset('local/public/assets/js/inner-pages.js') !!}"></script>
@endsection