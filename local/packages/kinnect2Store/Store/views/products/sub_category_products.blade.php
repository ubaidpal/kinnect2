@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

    @include('Store::includes.store-product-leftside')
    <div class="brand-store">
        <div class="three-product-container">
            @if(is_object($allProducts))
                <?php $user = getUserDetail($url_user_id) ?>
                <div class="product-container-title">
                    <span>{{$categoryName }}</span>
                </div>
                <div class="brand-product-item-main">
                    @foreach($allProducts as $p)

                        <div class="brand-product-item">
                            <div class="range-item-img">
                                <a href="{{url('store/'.$user->username.'/product/'.$p->id.'/'.preg_replace('/\s+/', '-', $p->title) )}}">
                                    <img src="{{ getProductPhotoSrc('','',$p->id, 'product_profile') }}" width="210"
                                         height="151" alt="img">
                                </a>
                            </div>
                            <div class="range-item-txt">
                                <a href="{{url('store/'.$user->username.'/product/'.$p->id.'/'.preg_replace('/\s+/', '-', $p->title) )}}"
                                   class="heading">{{$p->title}}</a>
                                <!--<div id="pasha"><?//php echo $p->description ?></div>-->
                            </div>
                            <?php $isReviewed = isProductRatingExist($p->id) ?>

                            @if($isReviewed > 0)
                                <?php $review = getRatings($p->id) ?>
                                <div class="item-rating">
                                    <div class="rating-stars">
                                        <?php
                                        $r_review = $review;
                                        $review = $review / 5 * 100;
                                        $review = "width:".$review."%"; ?>
                                        <div class="fill" style="<?php echo $review; ?>;"></div>
                                    </div>
                                    <div class="rating-value">{{ round($r_review,2) }}</div>
                                </div>
                                <!-- @if($review == 0)
                                        <img class="rated_stars"
                                             src="{!! asset('local/public/assets/images/star.png') !!}"
                                         alt="Rating"/>
                                @endif
                                @for($i=1;$i<=$review;$i++)
                                        <img class="rated_stars"
                                             src="{!! asset('local/public/assets/images/rattingstar.png') !!}"
                                         alt="Rating"/>
                                @endfor -->
                            @else
                            <div class="item-rating">
                                <div class="rating-stars">
                                    <div class="fill" style="width: 0px"></div>
                                </div>
                            </div>
                            @endif
                            <div class="range-item-price">
                                <?php
                                $price = $p->price;
                                if ($p->discount > 0) {
                                $price = round($p->price - ($p->price * $p->discount / 100), 2);
                                }
                                ?>
                                <div class="item-price">&dollar;{{format_currency($price)}}</div>
                                @if($p->discount > 0)
                                    <div class="prev-price">&dollar;{{format_currency($p->price)}}</div>
                                @endif
                            </div>
                            <a class="range-item-btn"
                               href="{{url('store/'.$user->username.'/product/'.$p->id.'/'.preg_replace('/\s+/', '-', $p->title) )}}">View
                                Details</a>
                        </div>
                    @endforeach
                </div>
        </div>
        @else
            <div style="clear: both;" class="brand-store-title">
                <span>No Product Found..</span>
            </div>
        @endif

    </div>
    @include('includes.ads-right-side')
</div>
<div class="modal-box cart" id="">
    <a href="#" class="js-modal-close close">?</a>

    <div class="modal-body">
        <div class="edit-photo-poup">
            <p class="mt10" style="width: 400px;height: 30px;line-height: normal">A new item has been added to your
                Shopping Cart. You now have <span id="countCartItemText"><strong>1</strong></span> item in your Shopping
                Cart. </p></br>
            <a style="width:150px;" class="btn fltL blue mr10 js-modal-close" href="#">Continue Shopping</a>
            <a style="width:150px;background-color:#6ad700" class="btn fltL blue mr10" href="{{url('store/cart')}}">View
                Shopping Cart</a>
        </div>
    </div>
</div>
<style>
    .rated_stars {
        width: 20px;
        height: 15px
    }
</style>

@endsection
@section('footer-scripts')

    <script src="{!! asset('local/public/assets/js/inner-pages.js') !!}"></script>

    {!! HTML::script('local/public/assets/js/searchAndPagination.js') !!}
    <script>
        jQuery(document).on('click', '.cart_del', function (e) {
            e.preventDefault();
            var id = e.target.id;

            var url = jQuery(this).attr('href');
            jQuery.ajax({
                url: url,
            }).done(function (data) {
                jQuery('#add_container_for_' + id).css('display', '');
                jQuery('#remove_container_for_' + id).css('display', 'none');
                jQuery('.add_cart_container').css('display', '');
                jQuery('.add_cart').css('display', '');
                jQuery('.remove_cart_container').css('display', 'none');
                updateCartCounter(data.total_items);

            });
        });

        jQuery(document).on('click', '.add_cart', function (e) {
            e.preventDefault();
            var id = e.target.id;
            id = id.match(/\d+/)[0];
            //var quantity = jQuery('#product_qty_'+id).val();
            var quantity = 1;

            jQuery.ajax({
                url: '{{url('store/cart/add-product/')}}',
                type: "Post",
                data: {product_id: id, quantity: quantity},

                success: function (data) {
                    if (data.message == 'quantity_overflow') {
                        jQuery('#quantity_overflow').addClass('error').text(data.message_text);
                    } else {
                        jQuery('#quantity_overflow').removeClass('error').text('In Stock');
                        jQuery('#add_container_for_' + id).css('display', 'none');
                        jQuery('#remove_container_for_' + id).css('display', '');
                        updateCartCounter(data.total_items)

                        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
                        $("body").append(appendthis);
                        //$('body').css({'overflow-y': 'scroll', 'position': 'fixed', 'width': '100%'});
                        jQuery('body').css('overflow','hidden');
                        $(".modal-overlay").fadeTo(500, 0.7);
                        var brandStoreUrl = '{{url('store/')}}';

                        $("#countCartItemText").html('<strong>' + data.total_items + '</strong>');
                        $(".cart").show();
                        return false;
                    }
                }, error: function (xhr, ajaxOptions, thrownError) {
                    alert("ERROR:" + xhr.responseText + " - " + thrownError);
                }
            });
        });

        updateCartCounter = function (total) {
            if (total > 0) {
                $("#the_cart").html('<span class="skore">' + total + '</span>');
            } else {
                $("#the_cart").html('');
            }
        };
    </script>
@endsection
