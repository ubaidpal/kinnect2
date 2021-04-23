@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')		

<div class="mainCont">

@include('includes.store-admin-leftside')

<div class="product-Analytics">   
	<div class="addProduct">
    	<h1>Add Products</h1>
        <h2>Categories and Sub-Categories</h2>
        <div class="selectdiv">
        	<select class="selectList">
            	<option>Select Category</option>
            </select>
            <select class="selectList">
            	<option>Select Sub-Category</option>
            </select>
        </div>
        <form action="">
  <div class="field-item">
   <label for="">Product Title</label>
   <input type="text">
  </div>

  <div class="field-item product-images">
   <label for="">Product Images</label>
   <div class="select-img cf">
    <img src="{!! asset('local/public/assets/images/brand-store-admin-product-img.png') !!}" alt="img">
    <img src="{!! asset('local/public/assets/images/brand-store-admin-product-img.png') !!}" alt="img">
    <img src="{!! asset('local/public/assets/images/brand-store-admin-product-img.png') !!}" alt="img">
    <img src="{!! asset('local/public/assets/images/brand-store-admin-product-img.png') !!}" alt="img">
    <img src="{!! asset('local/public/assets/images/brand-store-admin-product-img.png') !!}" alt="img">
    <img src="{!! asset('local/public/assets/images/brand-store-admin-product-img.png') !!}" alt="img">
    <img src="{!! asset('local/public/assets/images/brand-store-admin-product-img.png') !!}" alt="img">
    <a class="btn-add-product" href="javascript:();">
     <img src="{!! asset('local/public/assets/images/brand-store-admin-product-add.png') !!}" alt="img">
    </a>
   </div>
   <a class="btn blue mt10 mb10" href="javascript:();">Browse</a>
  </div>
<div class="field-item">
	<label>Key Features</label>
    <div class="products-container">
   		<div class="feature-title"><input type="text" placeholder="Key Feature Title"></div>
        <div class="feature-detail"><input type="text" placeholder="Key Feature Detail"></div>
        <div class="remove-product"><a href="#">X</a></div> 
    </div>
    <a href="javascript:();" class="btn grey fltL mt20">Add More</a>
    <div class="clrfix"></div>
</div>

<div class="field-item">
	<label>Tech Specs</label>
    <div class="products-container">
   		<div class="feature-title"><input type="text" placeholder="Tech Specs Title"></div>
        <div class="feature-detail"><input type="text" placeholder="Tech Specs Detail"></div>
        <div class="remove-product"><a href="#">X</a></div> 
    </div>
    <a class="btn grey fltL mt20" href="javascript:();">Add More</a>
    <div class="clrfix"></div>
</div>

<div class="field-item">
	<label>Weight (kg)</label>
    <div class="products-container">
   		<div class="feature-title"><input type="text" placeholder="Length (cm)" style="width:230px;"></div>
    </div>
</div>

<div class="field-item">
	<label>Dimensions</label>
    <div class="products-container">
   		<div class="feature-title"><input type="text" placeholder="Length (cm)"></div>
        <div class="feature-detail"><input type="text" placeholder="Width (cm)"></div>
        <div class="feature-detail"><input type="text" placeholder="Height (cm)"></div>
    </div>
</div>

<div class="field-item">
    <div class="products-container">
   		<div class="feature-title"><label>Price</label><input type="text" placeholder=""></div>
        <div class="feature-detail"><label>Discount (&permil;)</label><input type="text" placeholder=""></div>
        <div class="feature-detail"><label>Quantity</label><input type="text" placeholder=""></div>
    </div>
</div>

<div class="fltL mt20 mb20">
  <a class="btn blue fltL mr10" href="javascript:();">Save</a>
  <a class="btn grey fltL mr10" href="javascript:();">Cancel</a>
</div>
 </form>
    </div>
</div>
</div>
@endsection