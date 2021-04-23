@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

    @include('Store::includes.store-admin-leftside')

    <div class="product-Analytics">
        <div class="post-box">
            <h1>Product Analytics</h1>

            <h2>Analytics for product</h2>

            <div class="selectdiv">
                {!! Form::open() !!}
                {!!  Form::select('category',
                     $categories, session('category'), ['id' => 'category', 'class' => 'selectList', 'required' => 'required'])!!}
                @if($errors->has('category'))
                    <span id="cat-error">{{ $errors->first('category') }}</span>
                @endif


                <select class="sub-category selectList" id="sub_category" name="sub_category" required="required"
                        onchange="filteredProducts()">
                    <option>Select category first</option>
                    @if ($errors->has('sub_category'))
                        <span class="" style="color: red">{{$errors->first('sub_category')}}</span>
                    @endif
                </select>
            </div>
            <div class="ProductCont">
                <div class="headerCont">
                    <div class="imgW">Images</div>
                    <div class="titleW">Title</div>
                    <div class="priceW">Price</div>
                    <div class="totalW">Total</div>
                    <div class="availW">Available</div>
                    <div class="soldW">Sold</div>
                    @if(Auth::user()->id == $url_user_id)
                        <div class="actW">Actions</div>
                    @endif
                </div>
                <div id="listing_products">
                    @if($AllProducts == [])
                    @else
                        @foreach($AllProducts as $Product)
                            <div class="productList  product_item_{{$Product->id}}">
                                <div class="imgW"><img
                                            src="{{ getProductPhotoSrc('','',$Product->id, 'product_profile') }}"
                                            alt="image" width="80 " height="54"></div>
                                <div class="titleW">
                                    <a class="titleW"
                                       href="{{url('store/'.Auth::user()->username.'/admin/product/'.$Product->id )}}">{{$Product->title}}</a>
                                </div>
                                <div class="priceW">{{$Product->price}}</div>
                                <div class="totalW">{{$Product->quantity}}</div>
                                <div class="availW">3</div>
                                <?php $sold = $Product->quantity - 3; ?>
                                <div class="soldW">{{$sold}}</div>
                                <div class="actW">
                                    <a href="{{url('store/'.Auth::user()->username.'/admin/'.$Product->id.'/product_analytics' )}}"
                                       class="editProduct ml20 mr20"></a>
                                    <a href="" id={{$Product->id}} class="deleteProduct"></a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#category").change(function (evt) {
        var category = $("#category").val();
        jQuery.ajax({
            url: '{{url("store/".Auth::user()->username."/admin/subCategory")}}',
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
                    filteredProducts();
                    //filteredProducts(category,optionsHtml);
                } else {
                    $("#sub_category").html('<option id="nop" value="">No sub category found</option>');
                }
            }, error: function (xhr, ajaxOptions, thrownError) {
                alert("ERROR:" + xhr.responseText + " - " + thrownError);
            }
        });
    });
    function filteredProducts() {
        var cat = $('#category').val();
        var sub = $('#sub_category').val();
        var Auth_id = '<?php echo(Auth::user()->id) ?>';
        jQuery.ajax({
            url: '{{ url("store/".Auth::user()->username."/admin/filteredProducts") }}',
            type: "Post",
            data: {category: cat, sub_category: sub},
            success: function (data) {
                var maArray = jQuery.parseJSON(data);
                var ProductsHtml = '<div>';
                $.each(maArray, function (key, val) {
                    ProductsHtml += '<div class="productList product_item_' + val.id + '" id="productLists">';
                    ProductsHtml += '<div  class="imgW">'
                    ProductsHtml += '<img src="' + val.image + ' " alt="image" width="80 " height="54">';
                    ProductsHtml += '</div>';
                    ProductsHtml += '<div class="titleW"><a class="titleW" href="{{url('store/'.Auth::user()->username.'/admin/product' )}}/' + val.id + '">' + val.title + '</a></div>';
                    ProductsHtml += '<div class="priceW">' + val.price + '</div>';
                    ProductsHtml += '<div class="totalW">' + val.quantity + '</div>';
                    ProductsHtml += '<div class="availW">' + 3 + '</div>';
                    ProductsHtml += '<div class="soldW">' + (val.quantity - 3) + '</div>';
                    if (Auth_id == val.owner_id) {
                        ProductsHtml += '<div class="actW">';
                        ProductsHtml += '<input type="checkbox" id="show-album">';
                        ProductsHtml += '<a class="editProduct ml20 mr20" title="Edit ' + val.title + '" href="{{url('store/'.Auth::user()->username.'/admin/edit/product')}}/' + val.id + '">' + '</a>';
                        ProductsHtml += '<a class="deleteProduct" id="' + val.id + '" href="">' + '</a>';
                        // ProductsHtml += '<a class="deleteProduct" href="{{url('store/'.Auth::user()->username.'/admin/delete/product')}}/' + val.id + '" title="Delete '+val.title+'">'+'</a>';
                        ProductsHtml += '</div>';
                    }

                    ProductsHtml += '</div>';
                });
                if (ProductsHtml != '') {
                    $("#listing_products").html(ProductsHtml);
                } else {
                    $("#listing_products").html('<h1 class="productList" id="nop" value="">No Product found</h1>');
                }
            }, error: function (xhr, ajaxOptions, thrownError) {
                alert("ERROR:" + xhr.responseText + " - " + thrownError);
            }
        });
    }


    $(document).on("click", ".deleteProduct", function (e) {
        e.preventDefault();
        var product_id = e.target.id;
        jQuery.ajax({
            type: "Post",
            url: '{{url("store/".Auth::user()->username."/admin/product/delete")}}',
            data: {product_id: product_id},
            success: function (data) {
                if (data == 1) {
                    $('.product_item_' + product_id).remove();
                } else {
                    alert('No delete File');
                }

            }, error: function (xhr, ajaxOptions, thrownError) {
                alert("ERROR:" + xhr.responseText + " - " + thrownError);
            }
        });
    });


</script>
@endsection
