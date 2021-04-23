@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

    @include('Store::includes.store-product-leftside')
            <!-- slider test drive-->
    @include('Store::includes.slider.slider')
            <!-- end of test drive -->
    <div class="brand-store">
        <div class="brand-store-title">
		<span>{{$productDetail['title']}}
		</span>
            @if($isStoreOwner > 0)
                <a class="store-title-back" href="{{url('store/'.Auth::user()->username.'/admin/manage-product/')}}">&laquo; back</a>
            @else
                <a class="store-title-back" href="{{url('store/'.$storeName)}}">&laquo; back</a>
            @endif
        </div>

        <div class="brand-store-img-title">
            <div class="brand-img">

                <div class="pap-img product-photo-slider">
                    <?php
                    $images = product_images_src($productDetail['id']); //echo 'sony';'<tt><pre>'; print_r($images); die;
                    ?>
                    @if(isset($images['mainImageFiles']))
                        @foreach($images['mainImageFiles'] as $key => $mainImage)
                            <img class="slid" src="{{ asset('local/storage/app/photos/'.$mainImage->storage_path)}}">
                        @endforeach
                    @endif
                </div>


                <div class="slid-thumbs">
                    <?php
                    $images = product_images_src($productDetail['id']); //echo 'sony';'<tt><pre>'; print_r($images); die;
                    ?>
                    @if(isset($images['mainImageFiles']))
                        <?php $count = 0; ?>
                        @foreach($images['mainImageFiles'] as $key => $mainImage)
                            <a data-slide-index="{{$count}}}" href=""><img class="slid-thumb" src="{{ asset('local/storage/app/photos/'.$mainImage->storage_path)}}"></a>
                            <?php $count++; ?>
                        @endforeach
                    @endif
                </div>

                <script>
                    $(".product-photo-slider").bxSlider({
                        pagerCustom: ".slid-thumbs",
                        controls: false,
						onSliderLoad: function(){
							$(".pap-img").css("transform", "translate3d("+($(".pap-img img:eq(0)").width()*-1)+"px, 0px, 0px)");
						}
                    });
                </script>
            </div>
            <!--<div class="img-features">
			<div class="features fltL">
				<span>Key Features:</span>
			@if($key_feature > 0)
            <?php $featuresCount = 0; ?>
            @foreach($key_feature as $key_features)
                    <ul>
                        <?php
            $featuresCount++;

            if($featuresCount > 3){
                continue;
            }
            //$key_features->title.':';
            ?>
                    <li><strong>{{$key_features->detail}}</strong></li>
					</ul>
				@endforeach
            @endif
                    </div>

                    <div class="img-feature-price fltL">
                        @if($productDetail['discount'] > 0)
                    <div class="prev-price">${{format_currency($productDetail['price'])}}</div>
				@endif
                    <div class="current-price">${{format_currency($productDetail['price'] - ($productDetail['price'] / 100 * $productDetail['discount']))}}</div>
				@if($productDetail->quantity > 0)
                    <div id="quantity_overflow">In Stock</div>
                        @else
                    <div id="not_in_stock">Not In Stock</div>
                @endif
                    <span id="error" style="color:#F00000;display: none">Out of Stock</span>
                </div>
                <div class="btn-cart">
                    @if($storeName == Auth::user()->username)
                    <a href="{{url('/store/'.$storeName.'/admin/edit/product/'.$productDetail['id'])}}">Edit this Product</a>
				@else
            <?php
            $product_added_cart = Session::get('cart.products.'.$productDetail['owner_id'].'.'.$productDetail['id']);
            if(!empty($product_added_cart['quantity'])){
                $quantity = $product_added_cart['quantity'];
            }else{
                $quantity = 1;
            }
            ?>
                    <div class="remove_cart_container" style="display: @if(empty($product_added_cart['product_id'])) none; @endif">
					<input onch type="number" value="{{$quantity}}" min="1" name="quantity_update">

					<a class="cart_del"  href="{{url('store/cart/delete-product/'.$productDetail['id'])}}">Remove From Cart  </a>
				</div>

					@if($productDetail->quantity > 0)
                    <div class="add_cart_container" style="display: @if(!empty($product_added_cart['product_id'])) none; @endif">
							<input type="number" id="product_quatity_<?php echo $productDetail->id ?>" value="{{$quantity}}" min="1"                                          name="quantity">
							<a class="add_cart" id="add_in_cart_{{$productDetail['id']}}" href="#">Add to Cart  </a>
						</div>
					@endif
            @endif
                    </div>
                </div>-->


            <div class="product-detail-wrapper">
                <div class="product-list-wrapper">
                    <div class="heading">Price:</div>
                    <?php
                    $product_added_cart = Session::get('cart.products.'.$productDetail['owner_id'].'.'.$productDetail['id']);
                    if(!empty($product_added_cart['quantity'])){
                        $quantity = $product_added_cart['quantity'];
                    }else{
                        $quantity = 1;
                    }
                    ?>
                    <div class="detail">@if($productDetail['discount'] > 0)<sub class="prev">${{format_currency($productDetail['price'])}}</sub>@endif<span class="current-price">${{format_currency($productDetail['price'] - ($productDetail['price'] / 100 * $productDetail['discount']))}}</span><em>/ piece</em> <span class="availability">Availabilty: <strong>@if($productDetail->quantity > 0) In Stock @else Not In Stock @endif </strong></span></div>
                </div>
                @if($productDetail->quantity > 0)
                <div class="product-list-wrapper">
                    <div class="heading">Quantity:</div>
                    <div class="detail">
                        <div class="increse-quantity">
                            <a href="javascript:void(0);" class="addRemoveQtyFromCart" id="remove_product_qty_for_cart">-</a><div><input type="text" id="productQtyValueForCart" name="productQtyValueForCart" value="{{$quantity}}" disabled></div><a href="javascript:void(0);" class="addRemoveQtyFromCart" id="add_product_qty_for_cart">+</a>
                        </div>
                        <div class="available">piece (<span id="product_qty_available">{{$productDetail->quantity - $quantity}}</span> pieces available)</div>
                    </div>
                </div>
                @endif

                <?php $count = 0; ?>
                @if($attributes)
                    @foreach($attributes as $key => $productAttributes)
                        <div class="product-list-wrapper">
                            <div class="heading">{{ucfirst($key)}}:</div>
                            <div class="detail">
                            <?php $countingForColors = 0; ?>
                            @foreach($productAttributes as $attr)
                                    <?php $countingForColors++; ?>
                                <a href="javascript:void(0);" id="{{$attr->attribute}}_{{$attr->id}}" class="cs-item cart_product_{{$attr->attribute}}_selection @if($countingForColors == 1) active @endif">{{$attr->value}}</a>
                                @endforeach
                                <div class="clrfix"></div>
                                <div class="{{$attr->attribute}}-error" style="display: none;"> Please select {{$key}} to proceed</div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <div class="product-list-wrapper">
                    <div class="heading">Shipping:</div>
                    <div class="detail">
                        <select name="country"></select>
                        {{--<img src="http://localhost/kinnect2/local/public/assets/images/country-img.jpg" width="194" height="38" alt="Country">--}}
                        <div class="alert" id="shipping-error" style="display: none;">We do not deliver to your selected Country/Region.</div>
                    </div>
                </div>
                <div class="product-list-wrapper">
            		<div class="payment_methods fltR"></div>
                </div>
            <div class="img-features">
                <div class="features fltL">
                    <span>Buyer Protection:</span>
                    <ul>
                        <li>Full Refund if you don't received your order</li>
                        <li>Refund or Keep items not described</li>
                    </ul>
                </div>
                <div class="btn-cart">
                    @if($storeName == Auth::user()->username)
                        <a href="{{url('/store/'.$storeName.'/admin/edit/product/'.$productDetail['id'])}}">Edit this Product</a>
                    @else
                        @if($productDetail->quantity > 0)
                        <a class="add_cart" id="add_in_cart_{{$productDetail['id']}}" href="#">Add to Cart</a>
                        @endif
                    @endif
                    <span>Guaranteed Seller</span>
                </div>
            </div>
            <div class="return-policy">
                <p><strong>Return Policy: </strong>Return request can be made within 28 days of purchase, this should be made via</p><a class="learn-more" href="#">Learn more</a>
            </div>

            @if($product_post)
                <div class="feed-options mt10 mb10" data-action="{{$product_post->action_id}}" data-product-id="{{$productDetail['id']}}">
                    <a href="javascript:void(0);" title="Like" class="like like-post "></a>
                    <a href="javascript:void(0);" title="Dislike" class="dislike dislike-post "></a>
                    <a href="javascript:void(0);" title="Favourite" class="favourite favourite-post "></a>
                    <a href="javascript:void(0);" data-id="reShare" title="Share" class="share share-post-kinnct"></a>

                    <?php $reviewRated = "width:0%"; ?>

                    @if(is_array($reviews))
                        @foreach($reviews as $review)
                            @if($review->owner_id == Auth::user()->id)
                                <?php
                                $reviewRated = $review->rating / 5 * 100;
                                $reviewRated = "width:".$reviewRated."%";
                                ?>
                            @endif
                        @endforeach
                    @endif

                    <div class="rating-stars fltR mt10">
                        <div class="fill" style="{{$reviewRated}}"></div>
                    </div>
                </div>

                <div class="post-write-comment">
                    <div class="options-detail">
                        <span class="likes-count"> {{$product_post->like_count}} Likes </span> |
                        <span class="dislikes-count"> {{$product_post->dislike_count}} Dislikes </span> |
                        <span class="comments-count"> {{$product_post->comment_count}} Comments </span>
                    </div>
                </div>

            @endif

        </div>


        <div class="tabs-container mt10">
            <ul class="tabs-menu">
                <li class="current"><a href="#tab-features">Description</a></li>
                <li><a href="#tab-key-features">Key Features</a></li>
                <li><a href="#tab-specs">Tech Specs</a></li>
                <li><a href="#tab-reviews">Reviews</a></li>
            </ul>
            <div class="tab">
                <div id="tab-features" class="tab-content-main">
                    <div class="tab-content-item">
                        {!! $productDetail['description'] !!}

                    </div>
                </div>

                <div id="tab-key-features" class="tab-content-main">
                    @if($key_feature > 0)
                        <div class="tab-content-item">
                            @foreach($key_feature as $key_features)
                                <?php
                                //$key_features->title.':';
                                ?>
                                <p class="tab-content-title">{{$key_features->detail}}</p>
                            @endforeach
                        </div>
                    @else
                        <div class="tab-content-item">
                            <p class="tab-content-title">No Key Features added by brand.</p>
                            <p></p>
                        </div>
                    @endif
                </div>

                <div id="tab-specs" class="tab-content-main">
                    @if($tech_spechs > 0)
                        @foreach($tech_spechs as $tech_spech)
                            <div class="tab-content-item">
                                <p class="tab-content-title">{{$tech_spech->title}}</p>
                                <p>
                                    {{$tech_spech->detail}}
                                </p>
                            </div>
                        @endforeach
                    @else
                        <div class="tab-content-item">
                            <p class="tab-content-title">No Tech Spechs added by brand.</p>
                            <p></p>
                        </div>
                    @endif
                </div>

                <div id="tab-reviews" class="tab-content-main">
                    <?php $isReviewed = isProductRatingExist($productDetail['id']) ?>
                    @if($isReviewed > 0)
                        <?php //$checkIfAlreadyGiven = CheckIfReviewAlreadyGiven($productDetail['id'],Auth::user()->id); ?>
                        @if($isAbleToReview > 0 and $isReviewed == 0 AND $storeName != Auth::user()->username)
                            <div class="write-reviews">
                                {!! Form::open(array('method'=> 'post','url'=> "stores/review/".$productDetail['id'])) !!}
                                <div class="addReview">
                                    <?php $Owner = getUserDetail(Auth::user()->id); ?>
                                    <a href="{{url(profileAddress($Owner))}}" class="userImg">
                                        <img src="{{getPhotoUrlRegularUser(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_profile')}}"
                                             width="45" height="45" alt="User Image"/>
                                    </a>
                                    <div>
                                        <input type="text" style="display: none" id="stars_rating" name="stars_rating">
                                        <img class="rating_stars" src="{!! asset('local/public/assets/images/star.png') !!}" alt="Rating" />
                                        <img class="rating_stars" src="{!! asset('local/public/assets/images/star.png') !!}" alt="Rating" />
                                        <img class="rating_stars" src="{!! asset('local/public/assets/images/star.png') !!}" alt="Rating" />
                                        <img class="rating_stars" src="{!! asset('local/public/assets/images/star.png') !!}" alt="Rating" />
                                        <img class="rating_stars" src="{!! asset('local/public/assets/images/star.png') !!}" alt="Rating" />
                                    </div>
                                </div>
                                <div class="publish">
                                    {{--<a href="{{ URL::to('stores/review/'.$productsDetails[0]->id) }}" class="orngBtn fltR">Publish</a>--}}
                                    <input type="text" required="required" name="review_description" placeholder="What you think about this product?"
                                           style="float: left;margin-left: 30px;"/>
                                    <input type="submit" class="orngBtn fltR" value="Publish" title="Save Your Review" style="width: 20px;"/>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        @endif
                        @if(is_array($reviews))

                            @foreach($reviews as $review)
                                <div class="comment-pnl reviews">
                                    <?php $Owner = getUserDetail($review->owner_id); ?>
                                    <a class="user-image" title="username" href="{{url(profileAddress($Owner))}}">
                                        <img width="45" height="45" title="user" alt="" src="{{getPhotoUrlRegularUser($Owner->photo_id, $Owner->id, 'user', 'thumb_profile')}}">
                                    </a>
                                    <div class="comment-text">
                                        <a href="{{url(Kinnect2::profileAddress($Owner))}}" class="commentor-name">{{$Owner->displayname}}</a>
                                        @if($review->owner_id == Auth::user()->id)
                                            <a class="js-open-modal" data-modal-id="popup199-{{$review->id}}" title="Edit Your Review"  href="#">
                                                <span class="editProduct fltR mr20"></span>
                                            </a>
                                        @endif
                                        <p>{{$review->description}}</p>
                                        <span class="date mr10">{{$review->created_at}}</span>
                                        <div>
                                            @if($review->rating == 0)
                                                <img class="rated_stars" src="{!! asset('local/public/assets/images/star.png') !!}" alt="Rating" />
                                            @endif
                                            <div class="item-rating">
                                                <div class="rating-stars">
                                                    <?php
                                                    $reviewRated = $review->rating / 5 * 100;
                                                    $reviewRated = "width:".$reviewRated."%"; ?>
                                                    <div class="fill" style="<?php echo $reviewRated; ?>;"></div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                {!! Form::open(array('method'=> 'patch','url'=> "store/edit/ProductReview/".$review->id)) !!}
                                @include('Store::includes.Editpop',
                                ['submitButtonText' => 'Update',
                                 'cancelButtonText' => 'Cancel',
                                 'title'=>'Update You Review',
                                 'description' => $review->description,
                                 'type' => 'ProductReview',
                                 'previousRatings' => $reviewRated,
                                 'id' => 'popup199-'.$review->id])
                                {!! Form::close() !!}
                            @endforeach
                        @endif
                    @else
                        <div class="comment-pnl reviews">
                            <div class="comment-text">
                                <p>No reviews found</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <div class="modal-box cart" id="">
        <a href="#"  class="js-modal-close close">?</a>
        <div class="modal-body">
            <div class="edit-photo-poup">
                <p class="mt10" style="width: 400px;height: 30px;line-height: normal">A new item has been added to your Shopping Cart. You now have <span id="countCartItemText"><strong>1</strong></span> item in your Shopping Cart. </p></br>
                <a style="width:150px;" class="btn fltL blue mr10 js-modal-close" href="#" >Continue Shopping</a>
                <a style="width:150px;background-color:#6ad700" class="btn fltL blue mr10" href="{{url('store/cart')}}" >View Shopping Cart</a>
            </div>
        </div>
    </div>

    <div class="modal-box learn-more-btn" id="">
        <a href="#"  id="no" class="js-modal-close close">?</a>
        <div class="modal-body">
            <div class="edit-photo-poup">
                <h3 style="color: #0080e8;">Return Policy:</h3>
                <p class="mt10" style="width: 400px;height: 100px;line-height: 1.3;overflow: auto;">Return request can be made within 28 days of purchase, this should be made via the website and the product should remain in its original packaging without any use of the product ensuring the seller would accept the product.
                    Payment refunds can be requested in 28 days</p></br>
            </div>
        </div>
    </div>
    
</div>
    @include('includes.ads-right-side')



<div class="modal-box delete" id="share-product-post">
    <a href="#" class="js-modal-close close">&times;</a>

    <div class="modal-body">
        <div class="edit-photo-poup">
            <h3 style="color: #0080e8;">Share this product</h3>
            <div  class="mt10 mb10 share-product-pp">
                <textarea name="share-text" class="share-product-pp-txt"></textarea>
            </div>

            <input type="button" class="btn fltL blue mr10" id="confirm" value="Confirm"/>
            <input type="button" id="no" class="btn blue js-modal-close fltL close" value="Cancel"/>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css">
<style>

.bx-viewport{
	height: 400px !important;
}
</style>
<script src="{{url("local/public/js/select2.min.js")}}"></script>
<script>

    $(document).on("click", ".learn-more", function (e) {
        e.preventDefault();
        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
        $("body").append(appendthis);
        jQuery('body').css('overflow','hidden');
       // $('body').css({'overflow-y': 'scroll', 'position': 'fixed', 'width': '100%'});

        $(".modal-overlay").fadeTo(500, 0.7);
        var product_id = e.target.id;
        $(".learn-more-btn").attr("id", 'popup2-' + product_id);
        $(".learn-more-btn").show();

        $('#no').click(function () {
            $('body').css({'overflow-y': 'auto', 'position': 'static', 'width': 'auto'});
            $(".modal-box, .modal-overlay").fadeOut(500, function () {
                $(".modal-overlay").remove();
            });
            $(".learn-more-btn").hide();
            return false;
        });

    });

    $('.rating_stars').hover(
            // Handles the mouseover
            function() {
                $(this).prevAll().andSelf().attr("src" , "{!! asset('local/public/assets/images/rattingstar.png') !!}");
                $(this).nextAll().attr("src" , "{!! asset('local/public/assets/images/star.png') !!}");
            },
            // Handles the mouseout
            function() {
                $(this).prevAll().andSelf().attr("src" , "{!! asset('local/public/assets/images/rattingstar.png') !!}");
            },

            $('.rating_stars').click(function(){
                var count =  $(this).prevAll().length;
                document.getElementById("stars_rating").value = count;
                var var1= document.getElementById("stars_rating").value;
            })
    );

    jQuery(document).on('click','.cart_del',function (e) {
        e.preventDefault();
        var url = jQuery(this).attr('href');
        jQuery.ajax({
            url : url,
        }).done(function (data) {
            jQuery('.add_cart_container').css('display','');
            jQuery('.remove_cart_container').css('display','none');
            updateCartCounter(data.total_items);
        });
    });

    jQuery(document).on('click', '.add_cart', function (e) {
        e.preventDefault();
        var validationErr = false;
        if($(e.target).hasClass("disabled")){
            validationErr = true;
        }

        if($(".cart_product_size_selection").length > 0 && $(".cart_product_size_selection.active").length < 1){
            validationErr = true;
            $(".size-error").show();
        }else{
            $(".size-error").hide();
        }


        if($(".cart_product_color_selection").length > 0 && $(".cart_product_color_selection.active").length < 1){
            validationErr = true;
            $(".color-error").show();
        }else{
            $(".color-error").hide();
        }

        if(validationErr){
            return false;
        }


        var productColorId = $(".cs-item.active.cart_product_color_selection").attr("id");

        if (typeof productColorId === 'undefined') {
        }else{
            productColorId     = productColorId.match(/\d+/)[0];
        }

        var productSizeId = $(".cs-item.active.cart_product_size_selection").attr("id");

        if (typeof productSizeId === 'undefined') {
        }else{
            productSizeId     = productSizeId.match(/\d+/)[0];
        }

        var id = e.target.id;

        id = id.match(/\d+/)[0];
        var quantity = $('#productQtyValueForCart').val();

        jQuery.ajax({
            url: '{{url('store/cart/add-product/')}}',
            type: "Post",
            data: {product_id: id, quantity: quantity, productSizeId: productSizeId, productColorId: productColorId},

            success: function (data) {
                if(data.message == 'quantity_overflow'){
                    jQuery('#quantity_overflow').addClass('error').text(data.message_text);
                }else {
                    jQuery('#quantity_overflow').removeClass('error').text('In Stock');
                    jQuery('.add_cart_container').css('display', 'none');
                    jQuery('.remove_cart_container').css('display', '');
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
    jQuery(document).on('change','input[name="quantity_update"]',function (e) {
        quantityUpdate();
    });

    var timer = null;
    function quantityUpdate(){
        window.clearTimeout(timer);
        timer = window.setTimeout(function() {
            updateProductQuantity();
        }, 500);
    };

    updateProductQuantity = function () {
        var quantity =$('input[name="quantity_update"]').val();

        var dataString = {
            quantity: quantity,
            product_id: '{{$productDetail['id']}}'
        };

        $.ajax({
            type: 'POST',
            url: '{{url('store/cart/quantityUpdate')}}',
            data: dataString,
            success: function (response) {
                if(response.message == 'quantity_overflow'){
                    jQuery('#quantity_overflow').addClass('error').text(response.message_text);
                }else {
                    jQuery('#quantity_overflow').removeClass('error').text('In Stock');
                    updateCartCounter(response.total_items);
                }
            }
        });
    };
    updateCartCounter = function (total) {
        if(total > 0) {
            $("#the_cart").html('<span class="skore">' + total + '</span>');
        }else{
            $("#the_cart").html('');
        }
    };


    //JS Related to feed options, like,dislike, share etc
    var ajaxPathPrefix = "{{url('')}}";

    function likePost(id){
        var requestData = {
            "id": id
        };
        var params = {
            url: ajaxPathPrefix+"/likeStatus/"+id,
            type: "GET",
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            data: requestData,
            success: function (response) {
                if(response.message == "status_liked"){
                    $(".").removeClass("")
                }
            },
            error: function (x, t, m) {

            }
        };
        $.ajax(params);
    }

    function dislikePost(id, $ele){
        var requestData = {
            "id": id
        };
        var params = {
            url: ajaxPathPrefix+"/dislikeStatus/"+id,
            type: "GET",
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            data: requestData,
            success: function (response) {
                if(response.message == "status_disliked"){
                    $('.feed-options .like-post').removeClass('active');
                    $ele.addClass('active');
                    $('.likes-count').text(response.likes.like_count+' Likes');
                    $('.dislikes-count').text(response.likes.dislike_count+' Dislikes');
                }
            },
            error: function (x, t, m) {

            }
        };
        $.ajax(params);
    }

    function unlikePost(id, $ele){
        var requestData = {
            "id": id
        };
        var params = {
            url: ajaxPathPrefix+"/unlikeStatus/"+id,
            type: "GET",
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            data: requestData,
            success: function (response) {
                if(response.message == "status_unliked"){
                    $ele.removeClass('active');
                    $('.likes-count').text(response.likes.like_count+' Likes');
                }
            },
            error: function (x, t, m) {

            }
        };
        $.ajax(params);
    }


    function likePost(id, $ele){
        var requestData = {
            "id": id
        };
        var params = {
            url: ajaxPathPrefix+"/likeStatus/"+id,
            type: "GET",
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            data: requestData,
            success: function (response) {
                if(response.message == "status_liked"){
                    $('.feed-options .dislike-post').removeClass('active');
                    $ele.addClass('active');
                    $('.likes-count').text(response.likes.like_count+' Likes');
                    $('.dislikes-count').text(response.likes.dislike_count+' Dislikes');
                }
            },
            error: function (x, t, m) {

            }
        };

        $.ajax(params);
    }
    function unDoDislikePost(id, $ele){
        var requestData = {
            "id": id
        };
        var params = {
            url: ajaxPathPrefix+"/undoDislike/"+id,
            type: "GET",
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            data: requestData,
            success: function (response) {
                if(response.message == "undone_unlike"){

                    $ele.removeClass('active');
                    $('.dislikes-count').text(response.likes.dislike_count+' Dislikes');
                }
            },
            error: function (x, t, m) {

            }
        };

        $.ajax(params);
    }


    function makeFavourite(id, el){
        var requestData = {
            "id": id
        };
        var params = {
            url: ajaxPathPrefix+"/makeActivityFavourite/"+id,
            type: "GET",
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            data: requestData,
            success: function (response) {
                if(response.message == "status_fav"){
                    el.addClass("active");
                }
            },
            error: function (x, t, m) {
                alert("Error in Unliking post");
            }

        };
        $.ajax(params);
    }

    function undoPostFavourite(id, el){
        var requestData = {
            "id": id
        };
        var params = {
            url: ajaxPathPrefix+"/removeActivityFavourite/"+id,
            type: "GET",
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            data: requestData,
            success: function (response) {
                if(response.message == "status_unfav"){
                    el.removeClass("active");
                }
            },
            error: function (x, t, m) {
                alert("Error in Unliking post");
            }

        };
        $.ajax(params);
    }


    function reSharePost(options){
        var requestData = {
            "text" : options.text,
            "object_id" : options.object_id,
            "object_type" : options.object_type,
        };

        var params = {
            url: ajaxPathPrefix+"/shareActivity",
            type: "POST",
            //contentType: 'application/json; charset=utf-8',
            //dataType: 'json',
            data: requestData,
            beforeSend : function(xhr){
                $(".modal-box, .modal-overlay").fadeOut(500, function () {
                    jQuery('textarea.share-product-pp-txt').val('');
                    $(".modal-overlay").remove();
                });
            },
            success: function (response) {
                if(response.message == "status_shared"){

                }else {
                    alert((response.message).replace('_',' '));
                }
            },
            error: function (x, t, m) {
            }

        };
        $.ajax(params);
    }

    $(document).ready(function(){
        var reference = $(".feed-options").data("action");
        $(".feed-options .like-post").click(function(){
            var $this = $(this);
            if($this.hasClass('active')){
                unlikePost(reference, $this)
            }else{
                likePost(reference, $this)
            }

        });

        $(".feed-options .dislike-post").click(function(){
            var $this = $(this);
            if($this.hasClass('active')){
                unDoDislikePost(reference, $this)
            }else{
                dislikePost(reference, $this)
            }

        });


        $(".feed-options .favourite-post").click(function(){
            var $this = $(this);
            if(!$this.hasClass('active')){
                makeFavourite(reference, $this)
            }else{
                undoPostFavourite(reference, $this)
            }
        });



        //////////////////

        $(function() {
            var isoCountries = [
                { id: 'AF', text: 'Afghanistan'},
                { id: 'AX', text: 'Aland Islands'},
                { id: 'AL', text: 'Albania'},
                { id: 'DZ', text: 'Algeria'},
                { id: 'AS', text: 'American Samoa'},
                { id: 'AD', text: 'Andorra'},
                { id: 'AO', text: 'Angola'},
                { id: 'AI', text: 'Anguilla'},
                { id: 'AQ', text: 'Antarctica'},
                { id: 'AG', text: 'Antigua And Barbuda'},
                { id: 'AR', text: 'Argentina'},
                { id: 'AM', text: 'Armenia'},
                { id: 'AW', text: 'Aruba'},
                { id: 'AU', text: 'Australia'},
                { id: 'AT', text: 'Austria'},
                { id: 'AZ', text: 'Azerbaijan'},
                { id: 'BS', text: 'Bahamas'},
                { id: 'BH', text: 'Bahrain'},
                { id: 'BD', text: 'Bangladesh'},
                { id: 'BB', text: 'Barbados'},
                { id: 'BY', text: 'Belarus'},
                { id: 'BE', text: 'Belgium'},
                { id: 'BZ', text: 'Belize'},
                { id: 'BJ', text: 'Benin'},
                { id: 'BM', text: 'Bermuda'},
                { id: 'BT', text: 'Bhutan'},
                { id: 'BO', text: 'Bolivia'},
                { id: 'BA', text: 'Bosnia And Herzegovina'},
                { id: 'BW', text: 'Botswana'},
                { id: 'BV', text: 'Bouvet Island'},
                { id: 'BR', text: 'Brazil'},
                { id: 'IO', text: 'British Indian Ocean Territory'},
                { id: 'BN', text: 'Brunei Darussalam'},
                { id: 'BG', text: 'Bulgaria'},
                { id: 'BF', text: 'Burkina Faso'},
                { id: 'BI', text: 'Burundi'},
                { id: 'KH', text: 'Cambodia'},
                { id: 'CM', text: 'Cameroon'},
                { id: 'CA', text: 'Canada'},
                { id: 'CV', text: 'Cape Verde'},
                { id: 'KY', text: 'Cayman Islands'},
                { id: 'CF', text: 'Central African Republic'},
                { id: 'TD', text: 'Chad'},
                { id: 'CL', text: 'Chile'},
                { id: 'CN', text: 'China'},
                { id: 'CX', text: 'Christmas Island'},
                { id: 'CC', text: 'Cocos (Keeling) Islands'},
                { id: 'CO', text: 'Colombia'},
                { id: 'KM', text: 'Comoros'},
                { id: 'CG', text: 'Congo'},
                { id: 'CD', text: 'Congo}, Democratic Republic'},
                { id: 'CK', text: 'Cook Islands'},
                { id: 'CR', text: 'Costa Rica'},
                { id: 'CI', text: 'Cote D\'Ivoire'},
                { id: 'HR', text: 'Croatia'},
                { id: 'CU', text: 'Cuba'},
                { id: 'CY', text: 'Cyprus'},
                { id: 'CZ', text: 'Czech Republic'},
                { id: 'DK', text: 'Denmark'},
                { id: 'DJ', text: 'Djibouti'},
                { id: 'DM', text: 'Dominica'},
                { id: 'DO', text: 'Dominican Republic'},
                { id: 'EC', text: 'Ecuador'},
                { id: 'EG', text: 'Egypt'},
                { id: 'SV', text: 'El Salvador'},
                { id: 'GQ', text: 'Equatorial Guinea'},
                { id: 'ER', text: 'Eritrea'},
                { id: 'EE', text: 'Estonia'},
                { id: 'ET', text: 'Ethiopia'},
                { id: 'FK', text: 'Falkland Islands (Malvinas)'},
                { id: 'FO', text: 'Faroe Islands'},
                { id: 'FJ', text: 'Fiji'},
                { id: 'FI', text: 'Finland'},
                { id: 'FR', text: 'France'},
                { id: 'GF', text: 'French Guiana'},
                { id: 'PF', text: 'French Polynesia'},
                { id: 'TF', text: 'French Southern Territories'},
                { id: 'GA', text: 'Gabon'},
                { id: 'GM', text: 'Gambia'},
                { id: 'GE', text: 'Georgia'},
                { id: 'DE', text: 'Germany'},
                { id: 'GH', text: 'Ghana'},
                { id: 'GI', text: 'Gibraltar'},
                { id: 'GR', text: 'Greece'},
                { id: 'GL', text: 'Greenland'},
                { id: 'GD', text: 'Grenada'},
                { id: 'GP', text: 'Guadeloupe'},
                { id: 'GU', text: 'Guam'},
                { id: 'GT', text: 'Guatemala'},
                { id: 'GG', text: 'Guernsey'},
                { id: 'GN', text: 'Guinea'},
                { id: 'GW', text: 'Guinea-Bissau'},
                { id: 'GY', text: 'Guyana'},
                { id: 'HT', text: 'Haiti'},
                { id: 'HM', text: 'Heard Island & Mcdonald Islands'},
                { id: 'VA', text: 'Holy See (Vatican City State)'},
                { id: 'HN', text: 'Honduras'},
                { id: 'HK', text: 'Hong Kong'},
                { id: 'HU', text: 'Hungary'},
                { id: 'IS', text: 'Iceland'},
                { id: 'IN', text: 'India'},
                { id: 'ID', text: 'Indonesia'},
                { id: 'IR', text: 'Iran}, Islamic Republic Of'},
                { id: 'IQ', text: 'Iraq'},
                { id: 'IE', text: 'Ireland'},
                { id: 'IM', text: 'Isle Of Man'},
                { id: 'IL', text: 'Israel'},
                { id: 'IT', text: 'Italy'},
                { id: 'JM', text: 'Jamaica'},
                { id: 'JP', text: 'Japan'},
                { id: 'JE', text: 'Jersey'},
                { id: 'JO', text: 'Jordan'},
                { id: 'KZ', text: 'Kazakhstan'},
                { id: 'KE', text: 'Kenya'},
                { id: 'KI', text: 'Kiribati'},
                { id: 'KR', text: 'Korea'},
                { id: 'KW', text: 'Kuwait'},
                { id: 'KG', text: 'Kyrgyzstan'},
                { id: 'LA', text: 'Lao People\'s Democratic Republic'},
                { id: 'LV', text: 'Latvia'},
                { id: 'LB', text: 'Lebanon'},
                { id: 'LS', text: 'Lesotho'},
                { id: 'LR', text: 'Liberia'},
                { id: 'LY', text: 'Libyan Arab Jamahiriya'},
                { id: 'LI', text: 'Liechtenstein'},
                { id: 'LT', text: 'Lithuania'},
                { id: 'LU', text: 'Luxembourg'},
                { id: 'MO', text: 'Macao'},
                { id: 'MK', text: 'Macedonia'},
                { id: 'MG', text: 'Madagascar'},
                { id: 'MW', text: 'Malawi'},
                { id: 'MY', text: 'Malaysia'},
                { id: 'MV', text: 'Maldives'},
                { id: 'ML', text: 'Mali'},
                { id: 'MT', text: 'Malta'},
                { id: 'MH', text: 'Marshall Islands'},
                { id: 'MQ', text: 'Martinique'},
                { id: 'MR', text: 'Mauritania'},
                { id: 'MU', text: 'Mauritius'},
                { id: 'YT', text: 'Mayotte'},
                { id: 'MX', text: 'Mexico'},
                { id: 'FM', text: 'Micronesia}, Federated States Of'},
                { id: 'MD', text: 'Moldova'},
                { id: 'MC', text: 'Monaco'},
                { id: 'MN', text: 'Mongolia'},
                { id: 'ME', text: 'Montenegro'},
                { id: 'MS', text: 'Montserrat'},
                { id: 'MA', text: 'Morocco'},
                { id: 'MZ', text: 'Mozambique'},
                { id: 'MM', text: 'Myanmar'},
                { id: 'NA', text: 'Namibia'},
                { id: 'NR', text: 'Nauru'},
                { id: 'NP', text: 'Nepal'},
                { id: 'NL', text: 'Netherlands'},
                { id: 'AN', text: 'Netherlands Antilles'},
                { id: 'NC', text: 'New Caledonia'},
                { id: 'NZ', text: 'New Zealand'},
                { id: 'NI', text: 'Nicaragua'},
                { id: 'NE', text: 'Niger'},
                { id: 'NG', text: 'Nigeria'},
                { id: 'NU', text: 'Niue'},
                { id: 'NF', text: 'Norfolk Island'},
                { id: 'MP', text: 'Northern Mariana Islands'},
                { id: 'NO', text: 'Norway'},
                { id: 'OM', text: 'Oman'},
                { id: 'PK', text: 'Pakistan'},
                { id: 'PW', text: 'Palau'},
                { id: 'PS', text: 'Palestinian Territory, Occupied'},
                { id: 'PA', text: 'Panama'},
                { id: 'PG', text: 'Papua New Guinea'},
                { id: 'PY', text: 'Paraguay'},
                { id: 'PE', text: 'Peru'},
                { id: 'PH', text: 'Philippines'},
                { id: 'PN', text: 'Pitcairn'},
                { id: 'PL', text: 'Poland'},
                { id: 'PT', text: 'Portugal'},
                { id: 'PR', text: 'Puerto Rico'},
                { id: 'QA', text: 'Qatar'},
                { id: 'RE', text: 'Reunion'},
                { id: 'RO', text: 'Romania'},
                { id: 'RU', text: 'Russian Federation'},
                { id: 'RW', text: 'Rwanda'},
                { id: 'BL', text: 'Saint Barthelemy'},
                { id: 'SH', text: 'Saint Helena'},
                { id: 'KN', text: 'Saint Kitts And Nevis'},
                { id: 'LC', text: 'Saint Lucia'},
                { id: 'MF', text: 'Saint Martin'},
                { id: 'PM', text: 'Saint Pierre And Miquelon'},
                { id: 'VC', text: 'Saint Vincent And Grenadines'},
                { id: 'WS', text: 'Samoa'},
                { id: 'SM', text: 'San Marino'},
                { id: 'ST', text: 'Sao Tome And Principe'},
                { id: 'SA', text: 'Saudi Arabia'},
                { id: 'SN', text: 'Senegal'},
                { id: 'RS', text: 'Serbia'},
                { id: 'SC', text: 'Seychelles'},
                { id: 'SL', text: 'Sierra Leone'},
                { id: 'SG', text: 'Singapore'},
                { id: 'SK', text: 'Slovakia'},
                { id: 'SI', text: 'Slovenia'},
                { id: 'SB', text: 'Solomon Islands'},
                { id: 'SO', text: 'Somalia'},
                { id: 'ZA', text: 'South Africa'},
                { id: 'GS', text: 'South Georgia And Sandwich Isl.'},
                { id: 'ES', text: 'Spain'},
                { id: 'LK', text: 'Sri Lanka'},
                { id: 'SD', text: 'Sudan'},
                { id: 'SR', text: 'Suriname'},
                { id: 'SJ', text: 'Svalbard And Jan Mayen'},
                { id: 'SZ', text: 'Swaziland'},
                { id: 'SE', text: 'Sweden'},
                { id: 'CH', text: 'Switzerland'},
                { id: 'SY', text: 'Syrian Arab Republic'},
                { id: 'TW', text: 'Taiwan'},
                { id: 'TJ', text: 'Tajikistan'},
                { id: 'TZ', text: 'Tanzania'},
                { id: 'TH', text: 'Thailand'},
                { id: 'TL', text: 'Timor-Leste'},
                { id: 'TG', text: 'Togo'},
                { id: 'TK', text: 'Tokelau'},
                { id: 'TO', text: 'Tonga'},
                { id: 'TT', text: 'Trinidad And Tobago'},
                { id: 'TN', text: 'Tunisia'},
                { id: 'TR', text: 'Turkey'},
                { id: 'TM', text: 'Turkmenistan'},
                { id: 'TC', text: 'Turks And Caicos Islands'},
                { id: 'TV', text: 'Tuvalu'},
                { id: 'UG', text: 'Uganda'},
                { id: 'UA', text: 'Ukraine'},
                { id: 'AE', text: 'United Arab Emirates'},
                { id: 'GB', text: 'United Kingdom'},
                { id: 'US', text: 'United States'},
                { id: 'UM', text: 'United States Outlying Islands'},
                { id: 'UY', text: 'Uruguay'},
                { id: 'UZ', text: 'Uzbekistan'},
                { id: 'VU', text: 'Vanuatu'},
                { id: 'VE', text: 'Venezuela'},
                { id: 'VN', text: 'Viet Nam'},
                { id: 'VG', text: 'Virgin Islands}, British'},
                { id: 'VI', text: 'Virgin Islands}, U.S.'},
                { id: 'WF', text: 'Wallis And Futuna'},
                { id: 'EH', text: 'Western Sahara'},
                { id: 'YE', text: 'Yemen'},
                { id: 'ZM', text: 'Zambia'},
                { id: 'ZW', text: 'Zimbabwe'}
            ];

            function formatCountry (country) {
                if (!country.id) { return country.text; }
                var $country = $(
                        '<span data-iso="'+ country.id.toLowerCase() +'" class="flag-icon flag-icon-'+ country.id.toLowerCase() +' flag-icon-squared"></span>' +
                        '<span class="flag-text">'+ country.text+"</span>"
                );
                return $country;
            };

            //Assuming you have a select element with name country
            // e.g. <select name="name"></select>
            $("[name='country']").select2({
                placeholder: "Select a country",
                templateResult: formatCountry,
                data: isoCountries
            });

            $("[name='country']").on("change", function (e) {
                console.log($("[name='country']").val());
                var product_id = {{$productDetail['id']}} ;

                var params = {
                    url: "{{url('store/checkProductShippingCountryByISO')}}",
                    type: "POST",
                    data: {
                        products_ids:[  product_id],
                        country_iso: $("[name='country']").val()
                    },
                    success: function (response) {
                        //if($.inArray( ""+product_id, response.allowedProducts )){
                        if($.inArray( ""+product_id, response.allowedProducts) != -1){
                            $("#shipping-error").hide();
                            $(".add_cart").removeClass("disabled");
                        }else{
                            $(".add_cart").addClass("disabled");
                            $("#shipping-error").show();
                        }
                    },
                    error: function (x, t, m) {
                        $(".add_cart").addClass("disabled");
                        $("#shipping-error").show();
                    }
                };
                $.ajax(params);

                $(".select2-selection__rendered").prepend('<span class="flag-custom mr10 flag-icon flag-icon-'+$("[name='country']").val().toLowerCase()+' flag-icon-squared"></span>')


            });


            $("[name='country']").val("{{ ($user["country"])? $user["country"]->iso : ""}}").trigger("change")


        });



        /////////////////////

    });


    jQuery(document).on('click', '.share-post-kinnct', function (e) {
        e.preventDefault();
        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
        $("body").append(appendthis);

        $(".modal-overlay").fadeTo(500, 0.7);

        jQuery('#share-product-post').show();
    });
    jQuery(document).on('click', '#confirm', function (e) {
        e.preventDefault();
        var shareText = $.trim($(".share-product-pp-txt").val());
        var reference = $(".feed-options").data("product-id");
        if(shareText){
            reSharePost({
                text: shareText,
                object_id: reference,
                object_type : "product"
            });
        }

    });

    jQuery(document).on('input', '#product_quatity_<?php echo $productDetail->id ?>', function (e) {
        var qtyToCheck = $("#product_quatity_<?php echo $productDetail->id ?>").val();

        jQuery.ajax({
            url: '{{url('cart/product-quantity-check')}}',
            type: "Post",
            data: {product_id: '<?php echo $productDetail->id ?>', qtyToCheck: qtyToCheck},

            success: function (data) {
                if(data <= 0){
                    $('#error').show();
                    $('#quantity_overflow').hide();
                    return false;
                }else{
                    $('#error').hide();
                    $('#quantity_overflow').show();
                }



            }, error: function (xhr, ajaxOptions, thrownError) {
                alert("ERROR:" + xhr.responseText + " - " + thrownError);
            }
        });
    });

    $(".cart_product_color_selection").click(function(event){
        var id = event.target.id;
        $(".cart_product_color_selection").removeClass("active");
        $("#"+id).addClass("active");
    });

    $(".cart_product_size_selection").click(function(event){
        var id = event.target.id;

        $(".cart_product_size_selection").removeClass("active");
        $("#"+id).addClass("active");
    });

    $("#productQtyValueForCart").keyup(function(event){
        var totalInCart  = $("#productQtyValueForCart").val();

        if(totalInCart < 1){return false;}

        var totalInStock = $("#product_qty_available").html();

        if(totalInCart > totalInStock){return false;}

        totalInStock     = totalInStock.match(/\d+/)[0];

        $("#product_qty_available").html(totalInStock - totalInCart);

    });

    $(".addRemoveQtyFromCart").click(function(event){
        var updateCartRemoveAdd = event.target.id;

        var totalInCart  = $("#productQtyValueForCart").val();
        var totalInStock = $("#product_qty_available").html();
        totalInStock     = totalInStock.match(/\d+/)[0];

        if(updateCartRemoveAdd === "remove_product_qty_for_cart"){
            if(totalInCart < 2){return false;}

            $("#productQtyValueForCart").val(totalInCart - 1);
            $("#product_qty_available").html(+totalInStock + +1);
        }

        if(updateCartRemoveAdd === "add_product_qty_for_cart"){
            if(totalInStock == 0){
                return false;
            }
            totalInCart  = +totalInCart + +1;
            totalInStock = totalInStock - 1;

            $("#productQtyValueForCart").val(totalInCart);
            $("#product_qty_available").html(totalInStock);

        }

    });


</script>

@endsection
