@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')		

<div class="mainCont">

@include('includes.store-admin-leftside')

<div class="product-Analytics">   
	<div class="post-box">
    	<h1>Manage Products</h1>
        <h2>Categories and Sub-Categories</h2>
        <div class="selectdiv">
        	<select class="selectList">
            	<option>Select Category</option>
            </select>
            <select class="selectList">
            	<option>Select Sub-Category</option>
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
                <div class="actW">Actions</div>
            </div>
            <div class="productList">
                <div class="imgW"><img src="{!! asset('local/public/assets/images/manage-pro-image.jpg') !!}" alt="image" width="80 " height="54"></div>
                <div class="titleW">55" JU6800 6 Series Flat UHD 4K Nano Crystal Smart TV</div>
                <div class="priceW">$1,100</div>
                <div class="totalW">50</div>
                <div class="availW">30</div>
                <div class="soldW">20</div>
                <div class="actW">
                     <input type="checkbox" id="show-album">
                     <a href="javascript:void(0);" class="editProduct ml20 mr20"></a>
                     <a href="javascript:void(0);" class="deleteProduct"></a>
                </div>
            </div>
            <div class="productList">
                <div class="imgW"><img src="{!! asset('local/public/assets/images/manage-pro-image.jpg') !!}" alt="image" width="80 " height="54"></div>
                <div class="titleW">55" JU6800 6 Series Flat UHD 4K Nano Crystal Smart TV</div>
                <div class="priceW">$1,100</div>
                <div class="totalW">50</div>
                <div class="availW">30</div>
                <div class="soldW">20</div>
                <div class="actW">
                     <input type="checkbox" id="show-album">
                     <a href="javascript:void(0);" class="editProduct ml20 mr20"></a>
                     <a href="javascript:void(0);" class="deleteProduct"></a>
                </div>
            </div>
            <div class="productList">
                <div class="imgW"><img src="{!! asset('local/public/assets/images/manage-pro-image.jpg') !!}" alt="image" width="80 " height="54"></div>
                <div class="titleW">55" JU6800 6 Series Flat UHD 4K Nano Crystal Smart TV</div>
                <div class="priceW">$1,100</div>
                <div class="totalW">50</div>
                <div class="availW">30</div>
                <div class="soldW">20</div>
                <div class="actW">
                     <input type="checkbox" id="show-album">
                     <a href="javascript:void(0);" class="editProduct ml20 mr20"></a>
                     <a href="javascript:void(0);" class="deleteProduct"></a>
                </div>
            </div>
            <div class="productList">
                <div class="imgW"><img src="{!! asset('local/public/assets/images/manage-pro-image.jpg') !!}" alt="image" width="80 " height="54"></div>
                <div class="titleW">55" JU6800 6 Series Flat UHD 4K Nano Crystal Smart TV</div>
                <div class="priceW">$1,100</div>
                <div class="totalW">50</div>
                <div class="availW">30</div>
                <div class="soldW">20</div>
                <div class="actW">
                     <input type="checkbox" id="show-album">
                     <a href="javascript:void(0);" class="editProduct ml20 mr20"></a>
                     <a href="javascript:void(0);" class="deleteProduct"></a>
                </div>
            </div>
            <div class="productList">
                <div class="imgW"><img src="{!! asset('local/public/assets/images/manage-pro-image.jpg') !!}" alt="image" width="80 " height="54"></div>
                <div class="titleW">55" JU6800 6 Series Flat UHD 4K Nano Crystal Smart TV</div>
                <div class="priceW">$1,100</div>
                <div class="totalW">50</div>
                <div class="availW">30</div>
                <div class="soldW">20</div>
                <div class="actW">
                     <input type="checkbox" id="show-album">
                     <a href="javascript:void(0);" class="editProduct ml20 mr20"></a>
                     <a href="javascript:void(0);" class="deleteProduct"></a>
                </div>
            </div>
            <div class="productList">
                <div class="imgW"><img src="{!! asset('local/public/assets/images/manage-pro-image.jpg') !!}" alt="image" width="80 " height="54"></div>
                <div class="titleW">55" JU6800 6 Series Flat UHD 4K Nano Crystal Smart TV</div>
                <div class="priceW">$1,100</div>
                <div class="totalW">50</div>
                <div class="availW">30</div>
                <div class="soldW">20</div>
                <div class="actW">
                     <input type="checkbox" id="show-album">
                     <a href="javascript:void(0);" class="editProduct ml20 mr20"></a>
                     <a href="javascript:void(0);" class="deleteProduct"></a>
                </div>
            </div>
            <div class="productList">
                <div class="imgW"><img src="{!! asset('local/public/assets/images/manage-pro-image.jpg') !!}" alt="image" width="80 " height="54"></div>
                <div class="titleW">55" JU6800 6 Series Flat UHD 4K Nano Crystal Smart TV</div>
                <div class="priceW">$1,100</div>
                <div class="totalW">50</div>
                <div class="availW">30</div>
                <div class="soldW">20</div>
                <div class="actW">
                     <input type="checkbox" id="show-album">
                     <a href="javascript:void(0);" class="editProduct ml20 mr20"></a>
                     <a href="javascript:void(0);" class="deleteProduct"></a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection