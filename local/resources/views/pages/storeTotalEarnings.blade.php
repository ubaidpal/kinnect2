@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')		

<div class="mainCont">

@include('includes.store-admin-leftside')

<div class="product-Analytics">   
	<div class="post-box">
    	<h1>Total Earnings</h1>
        <div class="total-earning-wrapper">
        	<div class="total-earning-box">
           		<div class="earning-title">Your Balance:</div> 
                <div class="earning-value"><h1>&dollar;250,005</h1></div>
                <div class="earning-link"><a href="javascript:();">Withdraw money &raquo;</a></div>
            </div>
            <div class="total-earning-box">
           		<div class="earning-title">Sales earnings this month (January):</div> 
                <div class="earning-value"><h1>&dollar;150.25</h1></div>
                <div class="earning-link"><a href="javascript:();">View detail</a></div> 
            </div>
            <div class="total-earning-box w_200">
           		<div class="earning-title">Total Sales:</div> 
                <div class="earning-value"><h1>85k</h1></div>
                <div class="earning-link"><a href="javascript:();">View detail</a></div>  
            </div>
        </div>
    </div>
</div>
</div>
@endsection