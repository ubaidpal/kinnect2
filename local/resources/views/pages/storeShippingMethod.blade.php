@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')		

<div class="mainCont">

@include('includes.store-admin-leftside')

<div class="product-Analytics">   
	<div class="addProduct">
    	<h1>Add Shipping Cost</h1>
        
<form action="">
	<div class="shipping-method-wrapper">
    	<div class="shipping-box">
        	<div class="shipping-title">Africa:</div>
            <div class="shipping-cost">
            	<input type="text" placeholder="Add shipping cost here...">
                <select>
                	<option>Enable</option>
                    <option>Disable</option>
                </select>
             </div>
            <div class="shipping-countries"><a class="btn blue fltL" href="javascript:();">Countries</a></div>
        </div>
        <div class="shipping-box">
        	<div class="shipping-title">Americas:</div>
            <div class="shipping-cost">
            	<input type="text" placeholder="Add shipping cost here...">
                <select>
                	<option>Enable</option>
                    <option>Disable</option>
                </select>
             </div>
            <div class="shipping-countries"><a class="btn blue fltL" href="javascript:();">Countries</a></div>
        </div>
        <div class="shipping-box">
        	<div class="shipping-title">Asia:</div>
            <div class="shipping-cost">
            	<input type="text" placeholder="Add shipping cost here...">
                <select>
                	<option>Enable</option>
                    <option>Disable</option>
                </select>
             </div>
            <div class="shipping-countries"><a class="btn blue fltL" href="javascript:();">Countries</a></div>
        </div>
        <div class="shipping-box">
        	<div class="shipping-title">Europe:</div>
            <div class="shipping-cost">
            	<input type="text" placeholder="Add shipping cost here...">
                <select>
                	<option>Enable</option>
                    <option>Disable</option>
                </select>
             </div>
            <div class="shipping-countries"><a class="btn blue fltL" href="javascript:();">Countries</a></div>
        </div>
        <div class="shipping-box">
        	<div class="shipping-title">Oceania:</div>
            <div class="shipping-cost">
            	<input type="text" placeholder="Add shipping cost here...">
                <select>
                	<option>Enable</option>
                    <option>Disable</option>
                </select>
             </div>
            <div class="shipping-countries"><a class="btn blue fltL" href="javascript:();">Countries</a></div>
        </div>
        <div class="shipping-box">
        	<div class="shipping-title">&nbsp;</div>
            <div class="shipping-cost">
            	<a class="btn blue fltL mt20 mr10" href="javascript:();">Save</a>
          		<a class="btn grey fltL mt20" href="javascript:();">Cancel</a>
            </div>
        </div>
    </div>
    
    
</form>
    </div>
</div>
</div>
@endsection