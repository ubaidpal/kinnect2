@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

    @include('Store::includes.store-admin-leftside')
    @include('Store::includes.num')
    <div class="product-Analytics">
        <div class="addProduct">
            @if(empty($product->id))
                <h1>Add Product</h1>
            @else
                <h1>Edit Product</h1>
            @endif
            <h2>Categories and Sub-Categories</h2>

            {!! Form::open(['url' => url("store/".Auth::user()->username."/admin/add-product"), "id" => "new_product_detail", "enctype"=>"multipart/form-data"]) !!}
                <input type="hidden" name="product_id" value="{{@$product->id}}">
            <div class="selectdiv">
                <div class="fltL">
                    {!!  Form::select('category',
                         $categories, @$product->category_id, ['id' => 'category', 'class' => 'selectList ml0'])!!}
                </div>
                <div class="fltL subCategory">
                    <select class="selectList ml10" id="sub_category" name="sub_category">
                        <option value="">Select category first</option>
                    </select>
                </div>
                <div class="clrfix"></div>

            </div>
            <div class="field-item">
                <label for="title">Product Title</label>
                <input type="text" value="{{@$product->title}}" id="title" class="field-item"  name="title" placeholder="Enter Product Title">
            </div>
            <style>
                .image_delete_icon {

                    position: absolute;
                    background-image: url("{!! asset('local/public/assets/images/svg/delete_row.svg') !!}");
                    height: 18px;
                    width: 18px;
                    margin-left: 65px;
                    float: right;
                    background-repeat: no-repeat;
                    background-size: 18px auto;
                    cursor: pointer;
                }
                .image_delete {

                    position: absolute;
                    background-image: url("{!! asset('local/public/assets/images/svg/delete_row.svg') !!}");
                    height: 18px;
                    width: 18px;
                    margin-left: 65px;
                    float: right;
                    background-repeat: no-repeat;
                    background-size: 18px auto;
                    cursor: pointer;
                }

            </style>
            <div class="field-item product-images">
                <label for="">Product Images</label>

                <div id="images_selection_wrap" class="select-img cf">
                    <?php
                    if(!empty($product->id)){
                        $productImages = product_images_edit_src($product->id);
                    }
                    ?>
                    @if(!empty($productImages))
                        <div id="images_container">
                            @foreach($productImages as $key => $productImage)
                                <div id="image_container_{{$key}}">
                                    <a type="button" id="delete_image_{{$key}}" class="image_delete"></a>
                                    <input type="hidden" id="image_id_{{$key}}" class="input_image_ids" value="{{$key}}">
                                    <img class="browse_image_thumb" width="80" height="45" src="{{$productImage}}"
                                         id="browse_image_thumb_{{$key}}" alt="img">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div id="images_container">
                        </div>
                    @endif
                    <img class="browse_image_thumb" file-data=""
                         src="{!! asset('local/public/assets/images/brand-store-admin-product-img.png') !!}"
                         id="browse_image_thumb_1" alt="img">
                    <input type="file" class="image_to_be_uploaded" id="product_picture" style="display: none;">
                    <a class="btn-add-product" href="javascript:void(0);">
                        <img src="{!! asset('local/public/assets/images/brand-store-admin-product-add.png') !!}"
                             id="plus_sign_btn" alt="img">
                    </a>
                    <div class="image-alert" id="image-alert" style="display:none;color:#F00000;padding-top: 50px;"></div>
                </div>

            </div>

            <div class="field-item">
                <label for="title">Key Features</label>
                @if(!empty($features))
                    @foreach($features as  $feature)
                        <div class="products-container mt10">
                            <!--<div class="feature-title">-->
                            <input type="hidden" value="{{$feature->id}}" name="featureID[]" class="product_feature_id">
                            <input type="hidden" class="product_features_title"
                                   name="featuretitle[]" value="{{$feature->title}}" placeholder="Enter Key feature Title">
                            <!--</div>-->
                            <div class="feature-detail">
                                <input type="text" class="product_features_detail"
                                       name="keyfeaturedetail[]" value="{{$feature->detail}}" placeholder="Enter Key feature Detail">
                            </div>
                            <div class="remove-product"><a onclick="removeTxtBx(this);" class="remove-product"  type="button">X</a> </div>
                            <div class="clrfix"></div>
                        </div>
                    @endforeach
                @else
                    <div class="products-container">
                        <!--<div class="feature-title">-->
                        <input type="hidden" class="product_features_title"
                               name="featuretitle[]" value="not needed for now" placeholder="Enter Key feature Title">
                        <!--</div>-->
                        <div class="feature-detail">
                            <input type="text" class="product_features_detail"
                                   name="keyfeaturedetail[]" placeholder="Enter Key feature Detail">
                        </div>

                        <div class="clrfix"></div>
                    </div>
                @endif
                <div class="moreForms" id="moreField"></div>
                <p id='error_msg_add' class="mt5" style="color:red; display:none;">Please fill first two fields to add more.</p>
                <a class="btn grey fltL mt10" id="addField"> Add More</a>
                <div class="clrfix"></div>

            </div>
            <div class="field-item product-images">
                <label for="title">Colors</label>
                <div class="add-specs">
                    <div id="moreColorAttributeField">
                        @if(!empty($productAttributeColors))
                            @foreach($productAttributeColors as $key => $productAttributeColor)
                                <div class="add-color" id="color_{{$key}}">
                                    <input type="hidden" value="{{$key}}" name="colorID[]" class="product_color_id">
                                    <input type="hidden" class="product_colors_title" name="colortitle[]" value="color" placeholder="Enter  Product Attribute Title">
                                    <input type="text" class="product_colors_detail" value="{{$productAttributeColor}}" name="colordetail[]" placeholder="e.g. red">
                                    <span class="delete-item" onclick="removeProductAttribute('color', '{{$key}}')" id=""></span>
                                </div>
                            @endforeach
                        @else
                            <div class="add-color">
                                <input type="hidden" class="product_colors_title" name="colortitle[]" value="color" placeholder="Enter  Product Attribute Title">
                                <input type="text" class="product_colors_detail" name="colordetail[]" placeholder="e.g. red">
                                <span class="delete-item" id=""></span>
                            </div>
                        @endif
                    </div>
                    <a class="btn grey fltL mb10" id="addColorAttributeField"> Add More</a>
                    <p id='error_msg_color_attribute' class="mt5" style="color:red; display:none;">Please fill field to add more.</p>
                </div>
            </div>

            <div class="field-item mb0">
                <label for="title" class="mt10">Sizes</label>
                <div class="add-specs">
                    <div id="moreSizesAttributeField">
                        @if(!empty($productAttributeSizes))
                            @foreach($productAttributeSizes as $key => $productAttributeSize)
                                <div class="add-color" id="size_{{$key}}">
                                    <input type="hidden" value="{{$key}}" name="sizeID[]" class="product_size_id">
                                    <input type="hidden" class="product_sizes_title" name="sizetitle[]" value="size" placeholder="Enter  Product Attribute Title">
                                    <input type="text" class="product_sizes_detail" name="sizedetail[]" value="{{$productAttributeSize}}" placeholder="e.g 15″ or Small">
                                    <span class="delete-item" onclick="removeProductAttribute('size', '{{$key}}')" id=""></span>
                                </div>
                            @endforeach
                        @else
                            <div class="add-color">
                                <input type="hidden" class="product_sizes_title" name="sizetitle[]" value="size" placeholder="Enter  Product Attribute Title">
                                <input type="text" class="product_sizes_detail" name="sizedetail[]" placeholder="e.g 15″ or Small">
                                <span class="delete-item" id=""></span>
                            </div>
                        @endif
                    </div>
                    <a class="btn grey fltL mb10" id="addSizeAttributeField"> Add More</a>
                    <p id='error_msg_size_attribute' class="mt5" style="color:red; display:none;">Please fill field to add more.</p>
                </div>
            </div>

            <div class="field-item product-images">
                <label for="title" class="mt10">Description</label>
                <textarea style="height: 250px;" id="description" placeholder="Enter Description">{{@$product->description}}</textarea>
            </div>

            <div class="field-item">
                <label for="title">Tech Specs </label>
                @if(!empty($techs))
                    @foreach($techs as  $tech)
                        <div class="products-container" style="margin-top:10px">
                            <input type="hidden" value="{{$tech->id}}" name="techID[]" class="product_tech_id">
                            <div class="feature-title">
                                <input type="text" name="techtitle[]" value="{{$tech->title}}" class="product_tech_title"
                                       placeholder="Tech Specs Title">
                            </div>
                            <div class="feature-detail">
                                <input type="text" name="techspecs[]" value="{{$tech->detail}}" id="pr_tech_detail"
                                       class="product_tech_detail" placeholder="Tech Specs Detail">
                            </div>
                            <div class="remove-product"><a onclick="removeTxtBx2(this);" class="remove-product" id="removefield" type="button">X</a> </div>
                            <div class="clrfix"></div>
                        </div>
                    @endforeach
                @else
                    <div class="products-container">
                        <div class="feature-title">
                            <input type="text" name="techtitle[]" class="feature-title product_tech_title"
                                   placeholder="Tech Specs Title">
                        </div>
                        <div class="feature-detail">
                            <input type="text" name="techspecs[]" id="pr_tech_detail"
                                   class="feature-detail product_tech_detail" placeholder="Tech Specs Detail">
                        </div>

                        <div class="clrfix"></div>
                    </div>
                @endif
                <div class="moreForms" id="moreFields"></div>
                <p id='error_msg' style="color:red; display:none; margin-top:5px; margin-left:8px;">Please fill
                    first two fields to add more.</p>
                <a class="btn grey fltL mt10" id="addFields"> Add More</a>
                <div class="clrfix"></div>

            </div>
            <div class="field-item product-images">
                <label>Weight (kg) *</label>
                <div class="products-container">
                    <div class="feature-title pb10">
                        <input value="{{@$product->weight}}" type="text" id="weight" name="weight" style="width:230px;" placeholder="Weight (kg)">
                    </div>
                </div>
            </div>
            <div class="field-item">
                <label>Dimensions</label>
                <div class="products-container">
                    <div class="feature-title">
                        <input value="{{@$product->length}}" type="text" id="length" name="length" placeholder="Length (cm)" id="length">
                    </div>
                    <div class="feature-detail">
                        <input value="{{@$product->width}}" type="text" id="width" name="width" placeholder="Width (cm)" id="width">
                    </div>
                    <div class="feature-detail">
                        <input value="{{@$product->height}}" type="text" id="height" name="height" placeholder="Height (cm)" id="height">
                    </div>
                </div>
            </div>

            <div class="field-item product-images pb10">
                <div class="products-container" id="val" >
                    <div class="feature-title" id="price_val">
                        <label>Price *</label>
                        <input id="product_price" value="{{@$product->price}}" type="text" name="price" placeholder="Add Price">
                        <span class="form-indicator">&dollar;</span>
                    </div>
                    <div class="feature-detail"><label>Discount</label>
                        <input type="text" value="{{@$product->discount}}" id="discount" name="discount" placeholder="Add Price">
                        <span class="form-indicator">&#37;</span>
                    </div>
                    <div class="feature-detail"><label>Quantity</label>
                        <input type="text" value="{{@$product->quantity}}" id="quantity" name="quantity" placeholder="Add Quantity" required="required">
                    </div>
                </div>
            </div>



            <div class="fltR">
                <div class="feature-product">
                    <label><input value="1" name="is_featured" @if(@$product->is_featured) checked @endif type="checkbox">&nbsp;Featured Product</label>
                </div>
                <input type="hidden" name="_token" value="{{Session::token()}}">
                <button id="add_product_btn" class="btn blue fltL mr10" type="submit">Next</button>
                <?php $user = getUserDetail($url_user_id) ?>
                <a class="btn grey fltL mr10" href="{{ url('store/'.$user->username.'/admin/manage-product/') }}">Cancel</a>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <span style="color: #ff0000;">
                        <li>{{ $error }}</li>
                        </span>
            @endforeach
        </ul>
    </div>
@endif

<div id="product-photo-wrapper">
    <div class="croppr-overly-product" style="display: none"></div>

    <div id="light-photo" class="product_white_content">
        <div id="product-photo-cropper" class="edit_profile_photo_inline" tabindex='1' style="display:none;">
            <div class="product-image-editor">
                <div class="feed_viewmore" id="feed_loading-product" style="display: none;">
                    <img src='{!! asset('local/public/images/loading.gif') !!}' />
                </div>

                <div id="select_product_image" class="select_profile_image"><em>Browse</em> or drop photo</div>
                <input type="file" id="select_product_photo" onchange="show_product_photo();" class="cropit-image-input" style="display:none;">
                <!-- .cropit-image-preview-container is needed for background image to work -->

                <div class="cropit-image-preview-container">
                    <div class="cropit-image-preview" crossorigin="anonymous"></div>
                </div>
                <div class="image-size-label">
                    Resize image
                </div>
                <div id="input-div">
                    <input type="range" class="cropit-image-zoom-input" min="0" max="1" step="0.01">
                </div>
                <button class="image-export" >Save</button>
                <button class="cancel_product_light_box" >Cancel</button>
            </div>
        </div>
    </div><!-- lightbox -->
    <div id="fade-product" class="black_overlay"></div>
    <!-- end of croppic tool-->
</div>
<div class="modal-box del-image" id="">
    <a href="#" class="js-modal-close close">?</a>
    <div class="modal-body">
        <div class="edit-photo-poup">
            <h3 style="color: #0080e8;">Delete Image</h3>
            <p class="mt10" style="width: 315px;height: 26px;line-height: normal">Are You Sure You Want To delete this image? </p>
            <input type="button" class="btn fltL blue mr10" id="yes" value="Yes"/>
            <input type="button" id="no" class="btn blue js-modal-close fltL close" value="Cancel"/>
        </div>
    </div>
</div>
<input type="hidden" id="hidden_image_id">
<script src="{!! asset('/local/public/assets/js/jquery.validate.min.js') !!}"></script>
<script type="text/javascript">
    jQuery(document).ready(function(e){
        var category = $("#category").val();
        if(category > 0){
            getSubCategories(category);
        }
        jQuery('#new_product_detail').validate({
            'errorElement' : 'span',
            rules : {
                'category' : {required:true},
                'sub_category' : {required:true},
                'title' : {required:true},
                //'weight' : {required:true,min:0.01},
                'length' : {number:true},
                'width'  : {number:true},
                'height' : {number:true},
                'discount': {max:100},
                'price'  : {required:true,min:0.1},
                'quantity' : {required:true,min:1}
            }
        });
    });

    $(document).on("click", "#plus_sign_btn", function (evt) {
        jQuery('.browse_image_thumb').trigger('click');
    });

    $(document).on("click", ".image_delete_icon", function (evt) {
        jQuery('#image-alert').hide();
        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
        $("body").append(appendthis);
        jQuery('body').css('overflow','hidden');
        $(".modal-overlay").fadeTo(500, 0.7);
        var file_id = $(evt.target).data('id');

        jQuery('#hidden_image_id').val(file_id);

        $(".del-image").attr("id", 'popup2-' + file_id);
        $(".del-image").show();
        jQuery('#hidden_image_id').val(file_id);
    });
    $('#no').click(function () {

        $('body').css({'overflow-y':'scroll', 'position':'static','width':'auto'});
        $(".modal-box, .modal-overlay").fadeOut(500, function () {
            $(".modal-overlay").remove();
        });
        $(".del-image").hide();
        return false;
    });
    $('#yes').click(function () {

        file_id = jQuery('#hidden_image_id').val();
        var data = new FormData();
        data.append('file_id', file_id);

        $.ajax({
            url: '{{url("store/".Auth::user()->username."/admin/delete_product_image")}}',
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function (file_data) {
                if(file_data.status == 1) {
                    jQuery('#image_container_' + file_id).remove();

                    $(".modal-box, .modal-overlay").fadeOut(500, function () {
                        $(".modal-overlay").remove();
                    });
                    $(".del-image").hide();
                }else{
                    return false;
                    //alert('Error deleting image, please later.');
                }
            },
            error: function () {
                alert('Not deleted, try again.');
            }
        });
    });
    $(".image_delete").click(function (evt) {
        evt.preventDefault();
        jQuery('#image-alert').hide();
        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
        $("body").append(appendthis);


        $(".modal-overlay").fadeTo(500, 0.7);
        var imageId = evt.target.id.match(/\d+/);
        jQuery('#hidden_image_id').val(imageId);
        $(".del-image").attr("id", 'popup2-' + imageId);
        $(".del-image").show();

    });

    $("#addField").click(function () {
        var isCompletedFeaturedItems = true;
        $('#error_msg_add').hide();
        $.each(document.getElementsByClassName("product_features_title"), function (i, feature) {
            if (feature.value == '') {
                isCompletedFeaturedItems = false;
            }
        });
        $.each(document.getElementsByClassName("product_features_detail"), function (i, feature) {
            if (feature.value == '') {

                isCompletedFeaturedItems = false;
            }
        });

        if (isCompletedFeaturedItems) {
            //Add another feature text box
            $("#moreField").append('<div class="products-container mt10"><input type="hidden" value="not needed for now" class="product_features_title" name="featuretitle[]" placeholder="Enter Key feature Title"><!--</div>--><div class="feature-detail"> <input type="text" class="product_features_detail" name="keyfeaturedetail[]" placeholder="Enter Key feature Detail" required="required"> </div><div class="remove-product"><a type="button" id="removefield" class="remove-product" onclick="removeTxtBx(this);">X</a> </div><div class="clrfix"></div></div>');

        } else {
            $('#error_msg_add').show();
        }
    });

    $("#addFields").click(function () {
        var isCompletedFeaturedItems = true;
        $('#error_msg').hide();
        $.each(document.getElementsByClassName("product_tech_title"), function (i, feature) {
            if (feature.value == '') {

                isCompletedFeaturedItems = false;
            }
        });
        $.each(document.getElementsByClassName("product_tech_detail"), function (i, feature) {
            if (feature.value == '') {

                isCompletedFeaturedItems = false;
            }
        });

        if (isCompletedFeaturedItems) {
            //Add another feature text box
            $("#moreFields").append('<div class="products-container" style="margin-top:10px"  id="products-container"><div class="feature-title"> <input  style="width: 229px;" type="text" class="product_tech_title" name="techtitle[]" placeholder="Enter Key feature Title" required="required"></div><div class="feature-title"> <input type="text" style="width: 600px;" class="product_tech_detail" name="techspecs[]" placeholder="Enter Key feature Detail" required="required"> </div><div class="remove-product"><a  type="button" id="removefield" class="remove-product" onclick="removeTxtBx2(this);">X</a> </div><div class="clrfix"></div></div>');

        } else {
            $('#error_msg').show();
        }
    });

    $("#addColorAttributeField").click(function () {
        var isCompletedFeaturedItems = true;
        $('#error_msg_color_attribute').hide();

        var product_colors_title = 0;

        $.each(document.getElementsByClassName("product_colors_title"), function (i, feature) {
            if (feature.value == '') {
                isCompletedFeaturedItems = false;
            }
        });

        $.each(document.getElementsByClassName("product_colors_detail"), function (i, feature) {
            if (feature.value == '') {
                isCompletedFeaturedItems = false;
            }
        });

        if (isCompletedFeaturedItems) {
            //Add another feature text box
            var randomIdForColor = Math.round(Math.random()*9199999) + 1;

            $("#moreColorAttributeField").append('<div class="add-color" id="color_'+randomIdForColor+'"><input type="hidden" class="product_colors_title" name="colortitle[]" value="color" placeholder="Enter Product Attribute Title"><input class="product_colors_detail" name="colordetail[]" type="text" placeholder="e.g. red"><span class="delete-item" onclick="removeProductAttribute(\'color\', '+randomIdForColor+')"></span></div>');

        } else {
            $('#error_msg_color_attribute').show();
        }
    });

    $("#addSizeAttributeField").click(function () {
        var isCompletedFeaturedItems = true;
        $('#error_msg_size_attribute').hide();
        $.each(document.getElementsByClassName("product_sizes_title"), function (i, feature) {
            if (feature.value == '') {
                isCompletedFeaturedItems = false;
            }
        });
        $.each(document.getElementsByClassName("product_sizes_detail"), function (i, feature) {
            if (feature.value == '') {
                isCompletedFeaturedItems = false;
            }
        });

        if (isCompletedFeaturedItems) {
            //Add another feature text box
            var randomIdForSize = Math.round(Math.random()*9199999) + 1;

            $("#moreSizesAttributeField").append('<div class="add-color" id="size_'+randomIdForSize+'"><input type="hidden" class="product_sizes_title" name="sizetitle[]" value="size" placeholder="Enter Product Attribute Title"><input class="product_sizes_detail" name="sizedetail[]" type="text" placeholder="e.g 15″ or Small"><span class="delete-item" onclick="removeProductAttribute(\'size\', '+randomIdForSize+')"></span></div>');

        } else {
            $('#error_msg_size_attribute').show();
        }
    });

    function removeTxtBx(elem) {
        // remove text box
        $(elem).parent().parent().remove();

    }
    function removeTxtBx2(elem) {
        // remove text box
        jQuery(elem).parent().parent().remove();

    }

    $(document).on("click", "#add_product_btn", function (evt) {
        jQuery('#image-alert').hide();
        if($("#sub_category").val() == ''){
            var target = "#sub_category";

            $('html, body').animate({
                scrollTop: $(target).offset().top - 200
            }, 1000);
        }

        if($("#category").val() == 0){
            var target = "#sub_category";

            $('html, body').animate({
                scrollTop: $(target).offset().top - 200
            }, 1000);
        }

        evt.preventDefault();
        if(jQuery('#new_product_detail').valid()){
            var errors= false;

            var category = $("#category").val();


            var total_image_ids = document.getElementsByClassName("input_image_ids").length;
            if (total_image_ids < 1) {
                $('#image-alert').html('You must select an image to continue.').show();
                /*   setTimeout(function() {
                 $('#image-alert').fadeOut('slow');
                 }, 3000);*/
                $('html, body').animate({scrollTop: $('#title').position().top}, 'fast');
                return false;
            }

            var sub_category = $("#sub_category").val();

            var title =  $( "input[name*='title']" ).val();



            var weight =  $( "input[name*='weight']" ).val();


            var length =  $( "input[name*='length']" ).val();



            var width =  $( "input[name*='width']" ).val();


            var height =  $( "input[name*='height']" ).val();




            var quantity =  $( "input[name*='quantity']" ).val();


            var price =  $( "input[name*='price']" ).val();


            var myImageIds = [];
            $.each(document.getElementsByClassName("input_image_ids"), function (i, image_ids) {
                myImageIds.push(image_ids.value);
            });

            var product_features_title = [];
            $.each(document.getElementsByClassName("product_features_title"), function (i, feature) {
                product_features_title.push(feature.value);
            });

            var product_features_detail = [];
            $.each(document.getElementsByClassName("product_features_detail"), function (i, feature) {
                product_features_detail.push(feature.value);
            });

            var product_tech_title = [];
            $.each(document.getElementsByClassName("product_tech_title"), function (i, feature) {
                product_tech_title.push(feature.value);
            });

            var product_tech_detail = [];
            $.each(document.getElementsByClassName("product_tech_detail"), function (i, feature) {
                product_tech_detail.push(feature.value);
            });

            var product_colors_title = [];
            $.each(document.getElementsByClassName("product_colors_title"), function (i, feature) {
                product_colors_title.push(feature.value);
            });

            var product_colors_detail = [];
            $.each(document.getElementsByClassName("product_colors_detail"), function (i, feature) {
                product_colors_detail.push(feature.value);
            });


            var product_sizes_title = [];
            $.each(document.getElementsByClassName("product_sizes_title"), function (i, feature) {
                product_sizes_title.push(feature.value);
            });

            var product_sizes_detail = [];
            $.each(document.getElementsByClassName("product_sizes_detail"), function (i, feature) {
                product_sizes_detail.push(feature.value);
            });

            var data = [];
            data = $("#new_product_detail").serialize();

            var description = $("#description").val();
            if(errors == false) {
                $("#add_product_btn").prop('disabled', true);
                $("#add_product_btn").text("Saving..");

                        @if(empty($product->id))
                var urlToSubmit = '<?php echo url("store/" . Auth::user()->username . "/admin/add-product"); ?>';
                        @else
                var urlToSubmit = '<?php echo url("store/" . Auth::user()->username . "/admin/update-product/" . $product->id); ?>';
                @endif


                $.post(urlToSubmit, data + '&images_ids=' + myImageIds + '&description=' + description + '&product_features_title=' + product_features_title + '&product_features_detail=' + product_features_detail + '&product_tech_title=' + product_tech_title + '&product_tech_detail=' + product_tech_detail + '&product_colors_title=' + product_colors_title + '&product_colors_detail=' + product_colors_detail + '&product_sizes_title=' + product_sizes_title + '&product_sizes_detail=' + product_sizes_detail + '&weight=' + weight + '&price=' + price, function (response) {

                    if (response > 0) {
                        // var url1 = '{{url('store/'.Auth::user()->username.'/admin/product')}}';
                        var url1 = '{{url('store/'.Auth::user()->username.'/admin/add-product-shipping-cost/' )}}';
                        window.location.href = url1 + '/' + response;
                    } else {
                        //      document.write(response);
                       window.location.href = '{{url("store/".Auth::user()->username."/add-product/not-saved")}}';
                    }

                });
            }
        }

    });

    $("#category").change(function (evt) {
        var category = $("#category").val();
        getSubCategories(category);
    });

    getSubCategories = function(category){
        jQuery.ajax({
            url: '{{url("store/".Auth::user()->username."/admin/subCategory/")}}',
            type: "Post",
            data: {category: category},
            success: function (data) {
                var myArray = jQuery.parseJSON(data);
                var optionsHtml = '';
                var sub_category = '{{@$product->sub_category_id}}';
                $.each(myArray, function (key, val) {
                    var selected = '';
                    if(sub_category == val.id){
                        selected = 'selected="selected"';
                    }
                    optionsHtml += '<option '+selected+' id="' + val.id + '_sub_cat" value=' + val.id + '>' + val.name + '</option>';
                });
                if (optionsHtml != '') {
                    $("#sub_category").html(optionsHtml);
                } else {
                    $("#sub_category").html('<option id="nop" value="">No sub category found</option>');
                }
            }, error: function (xhr, ajaxOptions, thrownError) {
                alert("ERROR:" + xhr.responseText + " - " + thrownError);
            }
        });
    }
</script>

<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script type="text/javascript">
   /* tinymce.init({
        selector: "#description",
        statusbar: false,
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        },
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste "
        ],
        toolbar: "undo redo | cut copy paste pastetext | bold italic underline strikethrough superscript subscript | alignleft aligncenter      alignright alignjustify | bullist numlist outdent indent  |spellchecker code| formats | removeformat"
    });*/
</script>


<style>
    .cropit-preview {
        background-color: #f8f8f8;
        background-size: cover;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-top: 7px;
        width: 250px;
        height: 250px;
    }

    .cropit-preview-image-container {
        cursor: move;
    }

    .image-size-label {
        margin-top: 10px;
    }

    input {
        display: block;
    }



    #result {
        margin-top: 10px;
        width: 900px;
    }

    #result-data {
        display: block;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        word-wrap: break-word;
    }
    span.error{color:#FF0000}
    .croppr-overly-product {
        background: #000 none repeat scroll 0 0;
        height: 100%;
        left: 0;
        opacity: 0.7;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 9;
    }
    .cropit-image-preview {

        border-radius: 0%;

    }
    .addProduct img {

        height: 45px;

    }


    #wrapper-user-profile-photo div#select_product_image, #wrapper-user-profile-photo div#select_cover_image {
        color: #b6b6b6;
        cursor: pointer;
        line-height: normal;
        margin-bottom: 20px;
        text-align: center;
    }

    span.error{color:#FF0000; display:inline-block; padding-top:5px; padding-left:5px;}

    #product-photo-wrapper .cropit-image-background-container {
        top: -11px !important;
        left: -10px !important;
        width: 710px !important;
        height: 425px !important;
        background-color: #000;
    }
    #product-photo-wrapper div.edit_profile_photo_inline, #product-photo-wrapper div#edit_cover_photo_inline {
        position: absolute;
        z-index: 9;
        border-radius: 8px;
        padding: 0px 10px 10px;
        background-color: #fff;
        border-color: #959595;
        width: 690px;
    }
</style>

<script src="{!! asset('/local/public/cropit/cropit.js') !!}"></script>
<script>
    var clicked ;

    $(".cancel_product_light_box").click(function(e){
        e.preventDefault();

        jQuery("body").css('height', "auto");
        jQuery("#product-photo-cropper").hide();
        jQuery("#feed_loading-product").hide();
        jQuery("#light-photo").hide();
        jQuery("#fade-product").hide();
        jQuery(".croppr-overly-product").hide();
        $('body').css({'overflow-y': 'auto', 'position': 'static', 'width': 'auto'});


        return false;
    });

    function show_product_photo(){
        var currentWindowHeight = jQuery( window ).height();
        jQuery('body').css('height', currentWindowHeight);
        document.getElementById('light-photo').style.display = 'block';
        document.getElementById('fade-product').style.display  = 'block';
        jQuery("#product-photo-cropper").show();
        //$('body').css({'overflow-y': 'scroll', 'position': 'fixed', 'width': '100%'});
        jQuery('body').css('overflow','hidden');

        jQuery(".croppr-overly-product").show();
    }//show_edit_photo()

    function removeProductAttribute(productAttributeType, productAttributeId){
        $("#"+productAttributeType+"_"+productAttributeId).remove();
    }

    function changeMimeType()
    {

    }//changeMimeType()

    jQuery(document).on( 'click','.browse_image_thumb',function () {

        if(jQuery('#images_container').children().size() >= 5){
            jQuery('#image-alert').text('Maximum 5 images are allowed for a product').show();
            return false;
        }
        $(".image-export").html('save');
        $(".image-export").show();

        clicked = $(this).data('id');

        jQuery("#select_product_photo").trigger('click');
    });

    jQuery("#select_product_image").click(function () {
        $(".image-export").show();
        jQuery("#select_product_photo").trigger('click');
    });

    jQuery(function() {
        jQuery('.product-image-editor').cropit({
            width: 688,
            height: 400,
            exportZoom: 0.25,
            imageBackground: true,
            originalSize: true,
            smallImage:"allow",
            onFileChange: changeMimeType(),
            imageBackgroundBorderWidth: 20,
            imageState: {
                src: '<?php echo asset("/local/storage/app/photos/0/default_group_profile_photo.svg") ?>',
            },
        });

        jQuery('.image-export').click(function(e) {

            function dataURItoBlob2(dataURI) {
                var binary = atob(dataURI.split(',')[1]);
                var array = [];
                for(var i = 0; i < binary.length; i++) {
                    array.push(binary.charCodeAt(i));
                }
                return new Blob([new Uint8Array(array)], {type: 'image/jpeg'});
            }

            var imageData = jQuery('.product-image-editor').cropit('export');
//                var orignalImgData = $(".cropit-image-loaded").css('background-image');

//                orignalImgData = orignalImgData.split('"');

            canvas          = dataURItoBlob2(imageData);
//                orignalImgData  = dataURItoBlob2(orignalImgData[1]);

            blob = canvas;

            $(".image-export").html('saving..');
            var filename = 'imageData';

            var data = new FormData();
            data.append('product_image', blob);
            data.append('product_id',"{{@$product->id}}");

            $.ajax({
                url :  "{{url("store/".Auth::user()->username."/admin/product_image_ajax")}}",
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function(data) {
                    newImageHtml = '<div id="image_container_'+data.id+'">';
                    newImageHtml += '<span class="image_delete_icon" data-id="'+data.id+'" ></span>';
                    newImageHtml += '<input type="hidden" value="'+data.id+'" class="input_image_ids">';
                    newImageHtml += '<img class="browse_image_thumb"  width="80" height="45" src="'+data.path+'"  alt="img">';
                    newImageHtml += '</div>';
                    $('#images_container').prepend(newImageHtml);

                    jQuery(".cancel_product_light_box").trigger('click');
                    $(".image-export").hide();
                    $('body').css({'overflow-y': 'auto', 'position': 'static', 'width': 'auto'});
                    $('#image-alert').html('You must select an image to continue.').hide();
                },
                error: function () {
                    $('#browse_image_thumb_' + clicked).attr('file-data', ' ');
                }
            });

            return;
        });
    });



    /* $('.browse_image_thumb').hover(
     function () {
     alert('sd');
     $('.image_delete_icon').html().hide();
     }

     );*/


</script>

@endsection