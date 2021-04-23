@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

    @include('Store::includes.store-order-leftside')

    <div class="product-Analytics">
        <div class="post-box">
            <h1>Reviews</h1>

            <div class="selectdiv mb10">
                <input type="text" name="search_order_number" id="search_order_number" placeholder="Search By Order Number"
                       class="storeInput fltL mr10 w_200 search_order">
                <input type="text" name="search_product_name" id="search_product_name" placeholder="Search By Product"
                       class="storeInput fltL mr10 w_200 search_order">
            </div>

            <div class="bsmo-nav">
                <div class="bsmo-product w_480">
                    <a href="javascript:void(0)">Product</a>
                </div>
                <div class="bsmo-paction w_230">
                    <a href="javascript:void(0)">Feedback State</a>
                </div>
                <div class="bsmo-oamount fltL">
                    <a href="javascript:void(0)">Rating</a>
                </div>
            </div>
            @foreach($allOrders as $order)
                    <!-- Order Brand Item -->
            <div class="orderb-item">
                <?php $orderAllProducts = getOrderAllProducts($order->id); ?>
                <?php $orderBuyer = getUserDetail($order->customer_id); ?>
                <?php $reviewsHtml = ''; ?>
                @foreach($orderAllProducts as $orderProduct)
                    <div class="oi-header">
                        <div class="oi-image product-feedback">

                            <?php $reviewsHtml = ''; $product = getProductDetailsByID($orderProduct->product_id);//Complete detail of product
                            if (!isset($product->id)) {
                                continue;
                            }?>
                            <?php
                            $review = getRatingOfUserById($order->customer_id, $product->id);
                            $storeName = getUserNameByUserId($product->owner_id);
                            ?>
                            <div class="oi-product">
                                <a class="product-img"
                                   href="{{getProductUrlByIdAndOwnerId($orderProduct->product_id, $product->owner_id)}}">
                                    <?php $imageThumb = getThumbSrcWithProductId($product->id, 'product_thumb') ?>
                                    <img class="product-image" width="100" height="100" src="{{$imageThumb}}"
                                         alt="IMAGE"></a>

                                <div class="oi-title">{{$product->title}}</div>

                                <?php $data = getReviewStatusForBuyer($review, $storeName, $order->id, $product->id);
                                $reviewsHtml .= '<div class="feedback-state order_action_' . $order->id . $product->id . ' ' . $data["class"] . '">';
                                $reviewsHtml .= $data['status'] . $data['action_btn_1'] . $data['action_btn_2'] . '</div>';
                                ?>
                                {!! $reviewsHtml !!}

                                <div class="oi-amount"><p class="oi-price">

                                    <div id="rating_status_{{$order->id.$product->id}}">
                                        @if(isset($review->rating))

                                            @if($review->rating == 0)
                                                <img class="rated_stars"
                                                     src="{!! asset('local/public/assets/images/star.png') !!}"
                                                     alt="Rating"/>
                                            @endif
                                            @for($i=1;$i<=$review->rating;$i++)
                                                <img class="rated_stars"
                                                     src="{!! asset('local/public/assets/images/rattingstar.png') !!}"
                                                     alt="Rating"/>
                                            @endfor
                                            @for($i=1; $i <= 5 - $review->rating; $i++)
                                                <img class="rating_stars"
                                                     src="http://localhost/kinnect2/local/public/assets/images/star.png"
                                                     alt="Rating">
                                            @endfor
                                            <a class="feedback_lightbox" id="feedback_{{$review->id}}"
                                               feedback-data="{{$review->description}}" href="#review_{{$review->id}}"
                                               title="View comment">View comment</a>
                                            <div id="review_{{$review->id}}" class="cssPopup_overlay">
                                                <div class="cssPopup_popup">
                                                    <a class="cssPopup_close" href="#">&times;</a>
                                                    {{$review->description}}
                                                </div>
                                            </div>
                                        @else
                                            Not rated yet
                                        @endif
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                    {!! $data['popUpHtml'] !!}
                @endforeach
                <?php if (!isset($product->id)) {
                    continue;
                }?>
                <div class="oi-footer">
                    <div class="oi-detail">
                        <p class="mb5">
                            Order ID: {{$order->order_number}} <a href="{{url('order-invoice/'.$order->id)}}">View
                                Detail</a>
                        </p>

                        <p>
                            Order time & date: {{$order->created_at}}
                        </p>
                    </div>
                    <div class="oi-profile">
                        <p class="mb5">
                            <?php $brand_info = getBrandInfo($product->owner_id); ?>
                            Store Name:<a target="_blank"
                                          href="{{url('store/'.$brand_info->username)}}"> <?php echo ucfirst($brand_info->displayname); ?></a>
                        </p>

                        <p>
                            <a href="{{url('brand/'.$storeName)}}">View Profile</a>
                        </p>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>
<script>
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

            }, error: function (xhr, ajaxOptions, thrownError) {
                alert("ERROR:" + xhr.responseText + " - " + thrownError);
            }
        });
    });

    $(".filter_orders").click(function (evt) {
        var toBeFiltered = evt.target.id;

        $.each(document.getElementsByClassName("orderb-item"), function (i, item) {
            var html = item.innerHTML;
            if (html.indexOf(toBeFiltered) > -1) {
                setTimeout(function () {
                    $(item).fadeIn('slow');
                }, 500);
                //item.style.display = "";
            } else {
                setTimeout(function () {
                    $(item).fadeOut('slow');
                }, 500);
//                item.style.display = "none";
            }
        });
    });
    $(document).on('keyup', ".search_order", function (evt) {
        var order_number = $("#search_order_number").val();
        var product_name = $("#search_product_name").val();

        jQuery.ajax({
            url: '{{url("store/reviews/serach-my-reviews")}}',
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
