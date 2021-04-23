@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')
<div class="mainCont">

    @include('Store::includes.store-admin-leftside')
    @include('Store::includes.num')
    <div class="product-Analytics">
        <div class="addProduct">
            <h1>Edit Products</h1>
            <h2>Categories and Sub-Categories</h2>
            <div class="selectdiv">

                {!! Form::model($product , ['method' => 'PATCH', 'url' => "store/".Auth::user()->username."/admin/update/product/".$product->id, "enctype"=>"multipart/form-data"]) !!}
                {!!  Form::select('category',
                     $categories, $product->category_id, ['id' => 'category', 'class' => 'selectList', ])!!}

                <select class="sub-category  selectList m0" id="sub_category" name="sub_category" required="required">
                    <option>Select category first</option>

                </select>
            </div>
            <div class="field-item">
                <label for="title">Product Title</label>
                <input type="text" name="title" id="titles" value="{{$product->title}}" placeholder="Enter Product Title">
            </div>
            <style>
                .image_delete_icon {
                    position: absolute;
                    background-image: url("{!! asset('local/public/assets/images/del-btn.png') !!}");
                    height: 16px;
                    width: 16px;
                    float: right;
                    background-repeat: no-repeat;
                    cursor: pointer;
                }
                .image_delete {
                    position: absolute;
                    background-image: url("{!! asset('local/public/assets/images/del-btn.png') !!}");
                    height: 16px;
                    width: 16px;
                    float: right;
                    background-repeat: no-repeat;
                    cursor: pointer;
                }

            </style>
            <div class="field-item product-images">
                <label for="">Product Images</label>
                <div id="images_selection_wrap" class="select-img cf">
                    <div id="images_container">
                        {{--<span class="image_delete_icon" id="delete_image_1'" ></span>--}}
                        <?php $productImages = product_images_edit_src($product->id); ?>
                        @if(!empty($productImages))
                        @foreach($productImages as $key => $productImage)
                            <div data-p="144.50">
                                <span class="image_delete" id="delete_image_{{$key}}" ></span>
                                <input type="hidden" id="image_id_{{$key}}" class="image_id input_image_ids" value="{{$key}}">
                                <img class="browse_image_thumb"  style="width:80px;height:45px" file-data="no_deletion_{{$key}}" src="{{$productImage}}" id="browse_image_thumb_{{$key}}"  alt="img">
                            </div>
                        @endforeach
                        @endif
                    </div>
                    <a class="btn-add-product" href="javascript:void(0);">
                        <img src="{!! asset('local/public/assets/images/brand-store-admin-product-add.png') !!}" id="plus_sign_btn" alt="img">
                    </a>
                </div>
                <span id="info" style="color:red;display:none"></span>
                {{--<a class="btn blue mt10 mb10" href="javascript:void(0);">Browse</a>--}}
            </div>

            <div class="field-item">
                <label for="title">Description</label>
                <textarea style="height: 250px;" id="description" placeholder="Enter Description">{{$product->description}}</textarea>
            </div>

            <div class="field-item">
                <label for="title">Key Features *</label>
                @foreach($features as  $feature)
                <div class="products-container" style="margin-top:10px">
                    <div class="feature-title">
                        <input type="text" class="product_features_title"
                               name="featuretitle[]" value="{{$feature->title}}" placeholder="Enter Key feature Title" required="required">
                    </div>
                    <div class="feature-detail">
                        <input type="text" class="product_features_detail"
                               name="keyfeaturedetail[]" value="{{$feature->detail}}" placeholder="Enter Key feature Detail" required="required">
                    </div>

                    <div class="clrfix"></div>
                </div>
                @endforeach
                <div class="moreForms" id="moreField"></div>
                <p id='error_msg_add' class="mt5" style="color:red; display:none;">Please fill first two fields to add more.</p>
                <a class="btn grey fltL mt20" id="addField"> Add More</a>
                <div class="clrfix"></div>

            </div>
            <div class="field-item">
                <label for="title">Tech Specs </label>
                @foreach($techs as  $tech)
                <div class="products-container" style="margin-top:10px">
                    <div class="feature-title">
                        <input type="text" name="techtitle[]" value="{{$tech->title}}" class="product_tech_title"
                               placeholder="Tech Specs Title" required="required">
                    </div>
                    <div class="feature-detail">
                        <input type="text" name="techspecs[]" value="{{$tech->detail}}" id="pr_tech_detail"
                               class="product_tech_detail" placeholder="Tech Specs Detail" required="required">
                    </div>

                    <div class="clrfix"></div>
                </div>
                @endforeach
                <div class="moreForms" id="moreFields"></div>
                <p id='error_msg' style="color:red; display:none;">Please fill
                    first two fields to add more.</p>
                <a class="btn grey fltL mt20" id="addFields"> Add More</a>
                <div class="clrfix"></div>

            </div>

            <div class="field-item">
                <label>Weight (kg)</label>
                <div class="products-container">
                    <div class="feature-title"><input type="text" value="{{$product->weight}}" id="weight" name="weight" style="width:230px;" placeholder="Length (cm)"></div>
                </div>
            </div>

                <div class="field-item">
                    <label>Dimensions</label>
                    <div class="products-container">
                        <div class="feature-title">
                            <input type="text" name="length" title="length" id="length" value="{{$product->length}}"
                                   placeholder="Length (cm)">
                        </div>
                        <div class="feature-detail">
                            <input type="text" name="width" title="width" id="width" value="{{$product->width}}"
                                   placeholder="Width (cm)">
                        </div>
                        <div class="feature-detail">
                            <input type="text" name="height" title="height"
                                   id="height" value="{{$product->height}}" placeholder="Height (cm)">
                        </div>
                    </div>
                </div>

                <div class="field-item">
                    <div class="products-container" id="val">
                    <div class="feature-title">
                        <label for="price">Price *</label>
                        <input type="text" name="price" id="price" value="{{$product->price}}" placeholder="Add Price">
                        <script type="text/javascript">
                            $(document).ready(function()
                            {
                                $('#price').NumBox();
                            });
                        </script>
                    </div>

                    <div class="feature-title">
                        <label for="discount">Discount</label>
                        <input type="text" name="discount" id="discount"  value="{{$product->discount}}"  placeholder="Add Price">
                        <script type="text/javascript">
                            $(document).ready(function()
                            {
                                $('#discount').NumBox({   type: 'percent'  });
                            });
                        </script>
                    </div>

                    <div class="feature-title">
                        <label for="quantity">Quantity *</label>
                        <input type="text" name="quantity" id="quantity" value="{{$product->quantity}}"  placeholder="Add Quantity">
                    </div>
                    </div>
                </div>
            </div>
            <div class="fltR mt20 mb20">
                <input type="hidden" name="_token" value="{{Session::token()}}">
                <button id="update_product_btn" class="btn blue fltL mr10" type="submit">Next</button>
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

<script type="text/javascript">
    function readURL(input, browse_image_thumb_id) {
        var fuData = input, browse_image_thumb_id;
        var FileUploadPath = fuData.value;
        var Extension = FileUploadPath.substring(FileUploadPath.lastIndexOf('.') + 1).toLowerCase();
        if (Extension == "gif" || Extension == "png" || Extension == "bmp"
                || Extension == "jpeg" || Extension == "jpg") {
            if (fuData.files && fuData.files[0]) {

            var data = new FormData();
            data.append('product_image', input.files[0]);
            $.ajax({
                url: '{{url("store/".Auth::user()->username."/admin/product_image_ajax")}}',
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function(file_data) {
                    $('#browse_image_thumb_'+browse_image_thumb_id).attr('file-data', file_data) ;
                    var savedImageId = browse_image_thumb_id;
                    var numberOfImages = $(".browse_image_thumb").length;
                    var newImageId = numberOfImages + 1;

                    var newImageHtml = '<input type="hidden" value="'+file_data+'" name="input_image_id_'+savedImageId+'" class="input_image_ids" id="input_image_id_'+savedImageId+'">';
                    $('#images_container').prepend(newImageHtml);

                },
                error: function() {
                    $('#browse_image_thumb_'+browse_image_thumb_id).attr('file-data', ' ');
                }
            });
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#browse_image_thumb_'+browse_image_thumb_id)
                        .attr('src', e.target.result)
                        .width(80)
                        .height(45);
            };

            reader.readAsDataURL(input.files[0]);

            }
        }
        else{
            $("#info").html('You can only upload images.').show();
        }
    }

    // <editor-fold desc="get text box value - code">
    $(document).on("click", "#product_price", function (evt) {
        var product_tech_title = [];
        var product_tech_detail = [];

        $(".product_tech_title").each(function () {
            product_tech_title.push($(this).val());
        });

        $(".product_tech_detail").each(function () {
            product_tech_detail.push($(this).val());
        });
    });

    // </editor-fold>

    $(document).on("click",".browse_image_thumb",function(evt) {
        var imageId = evt.target.id.match(/\d+/);
        $("#product_pictures_"+imageId).click();

    });

    $(document).on("click","#plus_sign_btn",function(evt) {

        var numberOfImages = $(".browse_image_thumb").length;
        var newImageId = numberOfImages + 1;

        if(newImageId == 11){
            //$("#info").html('You can upload 10 Product photos only.');
            $("#info").html('You can upload 10 Product photos only.').show();
            return false;
        }

        var newImageHtml = '<span class="image_delete_icon" id="delete_image_'+newImageId+'"></span><img class="browse_image_thumb" file-data="" src="<?php echo asset("local/public/assets/images/brand-store-admin-product-img.png") ?>" id="browse_image_thumb_'+newImageId+'" alt="img"><input onchange="readURL(this, '+newImageId+');" type="file" name="product_pictures_'+newImageId+'" class="image_to_be_uploaded" id="product_pictures_'+newImageId+'" style="display: none;">';
        $('#images_container').prepend(newImageHtml);
    });

    $(document).on("click",".image_delete_icon",function(evt) {
        var imageId = evt.target.id.match(/\d+/);
        var file_id = $("#browse_image_thumb_"+imageId).attr('file-data');

        var defaultImg = '<?php echo asset("local/public/assets/images/brand-store-admin-product-img.png") ?>';
        var thumbImg   = $('#browse_image_thumb_'+imageId).attr('src');
        var total_images = document.getElementsByClassName("image_to_be_uploaded").length;

        if(total_images < 11){
            $("#info").html('');
        }

        if(defaultImg == thumbImg && total_images > 1){
            $("#input_image_id_"+imageId).remove();
            $("#delete_image_"+imageId).remove();
            $("#product_pictures_"+imageId).remove();
            $('#browse_image_thumb_'+imageId).remove();
            return false;
        }

        if(file_id > 0){

            var data = new FormData();
            data.append('file_id', file_id);

            $.ajax({
                url: '{{url("store/".Auth::user()->username."/admin/delete_product_image")}}',
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function(file_data) {
                    var total_images = document.getElementsByClassName("image_to_be_uploaded").length;

                    if(total_images > 1){
                        $("#input_image_id_"+imageId).remove();
                        $("#delete_image_"+imageId).remove();
                        $("#product_pictures_"+imageId).remove();
                        $('#browse_image_thumb_'+imageId).remove();
                    }else{
                        $('#browse_image_thumb_'+imageId).attr('file-data', file_id);
                        $('#browse_image_thumb_'+imageId).attr('src', '<?php echo asset("local/public/assets/images/brand-store-admin-product-img.png") ?>');
                    }

                },
                error: function() {
                    alert('Not deleted, try again.');
                    $('#browse_image_thumb_'+browse_image_thumb_id).attr('file-data', file_id);
                }
            });

        }else{
            alert('You must upload an image to add product.');

        }
    });

    // <editor-fold desc="Add more textbox - code">
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
            $("#moreField").append('<div class="products-container" style="margin-top:10px" id="multifields"><div class="feature-title"> <input  type="text"  style="width: 229px;" class="product_features_title" name="featuretitle[]" placeholder="Enter Key feature Title" required="required"></div><div class="feature-title"> <input type="text" style="width: 600px;" class="product_features_detail" name="keyfeaturedetail[]" placeholder="Enter Key feature Detail" required="required"> </div><div class="remove-product"><a type="button" id="removefield" class="remove-product" onclick="removeTxtBx();">X</a> </div></div>');

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
            $("#moreFields").append('<div class="products-container" style="margin-top:10px" id="products-container"><div class="feature-title"> <input  type="text" style="width: 229px;" class="product_tech_title" name="techtitle[]" placeholder="Enter Key feature Title" required="required"></div><div class="feature-title"> <input type="text" style="width: 600px;" class="product_tech_detail" name="techspecs[]" placeholder="Enter Key feature Detail" required="required"> </div><div class="remove-product"><a style="width: 62px;" type="button" id="removefield" class="remove-product" onclick="removeTxtBx2();">X</a> </div></div>');

        } else {
            $('#error_msg').show();
        }
    });


    function removeTxtBx() {
        // remove text box
        $('#multifields').remove();

    }
    function removeTxtBx2() {
        // remove text box
        $('#products-container').remove();

    }
    // </editor-fold>

    // <editor-fold desc="Script validations checks and send data for add store function  - code">

    $(document).on("click", "#update_product_btn", function (evt) {
        evt.preventDefault();


        var errors= false;
        var category = $("#category").val();
        if (category == 0) {
            $("#category").val('');
            $("#sub_category").after('<div class="error" style="color:#F00000;">You must select category first</div>');
            errors = true;
        }

        var sub_category = $("#sub_category").val();
        var total_image_ids = document.getElementsByClassName("input_image_ids").length;

        if (total_image_ids < 1) {
            $(".btn-add-product").after('<div class="error" style="color:#F00000;padding-top: 50px;">You must select an image to continue.</div>');
            errors = true;
        }


        var titles   = $("#titles").val();
        if (titles == '') {
            $("#title").after('<span class="error" style="color:#F00000;">Title field is empty.</span>');
            errors = true;
        }

        var length   = $("#length").val();
        if (length == '') {
            $("#length").after('<span class="error" style="color:#F00000;">Length field is empty.</span>');
            errors = true;
        }

        var weight =  $( "input[name*='weight']" ).val();

        if (weight == '') {
            $("#weight").after('<span class="error" style="color:#F00000;padding-left:8px;">Weight field is empty.</span>');
            errors = true;
        }

        var width    = $("#width").val();
        if (width == '') {
            $("#width").after('<span class="error" style="color:#F00000;">Width field is empty.</span>');
            errors = true;
        }

        var height   = $("#height").val();
        if (height == '') {
            $("#height").after('<span class="error" style="color:#F00000;">Height field is empty.</span>');
            errors = true;
        }
        var quantity = $("#quantity").val();
        if (quantity == '') {
            $("#val").after('<div class="error" style="color:#F00000;">Quantity field is empty.</div>');
            errors = true;
        }

        var item =  $( "input[name*='price']" ).val();
        if (item == '$0.00') {
            $("#val").after('<div class="error" style="color:#F00000;">You must fill price field.</div>');
            errors = true;
        }
        var price = item.split('$');
        var discount = $("#discount").val();


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

        var data = [];
        data = $("#new_product_detail").serialize();

        var description = $("#description").val();


        $("#update_product_btn").prop('disabled',true);
        $("#update_product_btn").text("Saving..");
        var urlToSubmit = '<?php echo url("store/".Auth::user()->username."/admin/update-product/".$product->id); ?>';

        $.post(urlToSubmit, data + '&category=' + category + '&sub_category=' + sub_category + '&title=' + titles + '&length=' + length + '&width=' + width + '&height=' + height +  '&price=' + price[1]  +  '&discount=' + discount +  '&quantity=' + quantity + '&images_ids=' + myImageIds + '&description=' + description + '&product_features_title=' + product_features_title + '&product_features_detail=' + product_features_detail + '&product_tech_title=' + product_tech_title + '&product_tech_detail=' + product_tech_detail + '&weight=' + weight,
                function (response) {

            if (response > 0) {
                  /*  document.write(response);
                   alert(response);
                  exit();*/

               //var url1 = '{{url('store/'.Auth::user()->username.'/admin/product')}}';
                var url1 = '{{url('store/'.Auth::user()->username.'/admin/add-product-shipping-cost/' )}}';

                window.location.href = url1 + '/' + response;
            } else {
                /* document.write(response);
                alert(response);
                exit();*/
                window.location.href = '{{url("store/".Auth::user()->username."/add-product/not-saved")}}';
            }

        });

    });
    // </editor-fold>


    (function($) {
        var category = $("#category").val();
        jQuery.ajax({
            url: '{{url("store/".Auth::user()->username."/admin/subCategory/")}}',
            type: "Post",
            data: { category: category},
            success: function(data){
                var myArray = jQuery.parseJSON(data);

                var optionsHtml = '';
                $.each(myArray, function (key, val) {
                    optionsHtml += '<option id="'+val.id+'_sub_cat" value='+val.id+'>'+val.name+'</option>';
                });
                if(optionsHtml != ''){
                    $("#sub_category").html(optionsHtml);
                }else{
                    $("#sub_category").html('<option id="nop" value="">No sub category found</option>');
                }
            },error: function (xhr, ajaxOptions, thrownError) {alert("ERROR:" + xhr.responseText+" - "+thrownError);}
        });
    })(jQuery);
    
    $("#category").change(function (evt) {
        var category = $("#category").val();
        jQuery.ajax({
            url: '{{url("store/".Auth::user()->username."/admin/subCategory/")}}',
            type: "Post",
            data: {category: category},
            success: function (data) {
                var myArray = jQuery.parseJSON(data);
                var optionsHtml = '';
                $.each(myArray, function (key, val) {
                    optionsHtml += '<option id="' + val.id + '_sub_cat" value=' + val.id + '>' + val.name + '</option>';
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
    });


    $(".image_delete").click(function(evt){
        evt.preventDefault();
        var imageId = evt.target.id.match(/\d+/);

        // var file_id = $("#browse_image_thumb_"+id).attr('default_image');
        jQuery.ajax({
            url: '{{url("store/".Auth::user()->username."/admin/delete_edit_product_image")}}',
            type: "Post",
            data: {id:imageId },
            success: function(data){
                if(data > 0){
                    $("#delete_image_"+imageId).remove();
                    $("#image_id_"+imageId).remove();
                    $("#browse_image_thumb_"+imageId).remove();

                    var total_images = document.getElementsByClassName("image_to_be_uploaded").length;
                    if(total_images == 0){
                        var imageHtml = '<img class="browse_image_thumb" file-data="" src="{!! asset('local/public/assets/images/brand-store-admin-product-img.png') !!}" id="browse_image_thumb_1" alt="img"><input onchange="readURL(this, 1);" type="file" name="product_pictures_1" class="image_to_be_uploaded" id="product_pictures_1" style="display: none;">';
                        $('#images_container').prepend(imageHtml);
                    }

                }

            },error: function (xhr, ajaxOptions, thrownError) {alert("ERROR:" + xhr.responseText+" - "+thrownError);}
        });
    });


</script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
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
    });
</script>
<script>
    // <editor-fold desc="Call tinymce function - code">
    function isDecimalValue(evt){
        var key = evt.keyCode || evt.charCode;

        if(key >= 48 && key <= 57){
            return key;
        }

        if(key == 46 || key == 8){
            return key;
        }

        return false;
    }

    function isNumericValue(evt){
        var key = evt.keyCode || evt.charCode;

        if(key >= 48 && key <= 57){
            return key;
        }

        if(key == 8){
            return key;
        }

        return false;
    }


    $('#length, #width, #height,#weight').on('keypress', function(evt){
        return isDecimalValue(evt);
    });

    $('#product_price, #discount').on('keypress', function(evt){
        return isDecimalValue(evt);
    });

    $('#quantity').on('keypress', function(evt){
        return isNumericValue(evt);
    });
    // </editor-fold>
</script>
@endsection
