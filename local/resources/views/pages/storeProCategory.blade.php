@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')		

<div class="mainCont">

@include('includes.store-admin-leftside')

<div class="product-Analytics">   
	<div class="post-box">
    	<h1>Categories</h1>
        <div class="selectdiv m0">
        	<input type="text" placeholder="Add Category..." class="storeInput fltL mr10 w340">
            <a href="javascript:void(0);" class="btn blue fltL">Add</a>
        </div>
        <div class="categoryList">
            <div>Mobiles</div>
            <div class="actW">
                 <a class="editProduct ml20 mr20" href="javascript:void(0);"></a>
                 <a class="deleteProduct" href="javascript:void(0);"></a>
            </div>
        </div>
        <div class="categoryList">
            <div>Mobiles</div>
            <div class="actW">
                 <a class="editProduct ml20 mr20" href="javascript:void(0);"></a>
                 <a class="deleteProduct" href="javascript:void(0);"></a>
            </div>
        </div>
        <div class="categoryList">
            <div>Mobiles</div>
            <div class="actW">
                 <a class="editProduct ml20 mr20" href="javascript:void(0);"></a>
                 <a class="deleteProduct" href="javascript:void(0);"></a>
            </div>
        </div>
        <div class="categoryList">
            <div>Mobiles</div>
            <div class="actW">
                 <a class="editProduct ml20 mr20" href="javascript:void(0);"></a>
                 <a class="deleteProduct" href="javascript:void(0);"></a>
            </div>
        </div>
        <div class="categoryList">
            <div>Mobiles</div>
            <div class="actW">
                 <a class="editProduct ml20 mr20" href="javascript:void(0);"></a>
                 <a class="deleteProduct" href="javascript:void(0);"></a>
            </div>
        </div>
        <div class="categoryList">
            <div>Mobiles</div>
            <div class="actW">
                 <a class="editProduct ml20 mr20" href="javascript:void(0);"></a>
                 <a class="deleteProduct" href="javascript:void(0);"></a>
            </div>
        </div>
        <div class="categoryList">
            <div>Mobiles</div>
            <div class="actW">
                 <a class="editProduct ml20 mr20" href="javascript:void(0);"></a>
                 <a class="deleteProduct" href="javascript:void(0);"></a>
            </div>
        </div>
        <div class="categoryList">
            <div>Mobiles</div>
            <div class="actW">
                 <a class="editProduct ml20 mr20" href="javascript:void(0);"></a>
                 <a class="deleteProduct" href="javascript:void(0);"></a>
            </div>
        </div>
        <div class="mt20">
        	<a href="javascript:void(0);" class="btn grey fltR">Cancel</a>
            <a href="javascript:void(0);" class="btn blue fltR mr10">Save</a>
        </div>
    </div>
</div>
</div>
@endsection