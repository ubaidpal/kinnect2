@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')  

<div class="mainCont">

@include('includes.store-admin-leftside')

	<div class="product-Analytics">   
 		<div class="post-box">
     		<div class="bsmp-title">
                <div class="bsmp-ttle">Withdrawals</div>
                <div class="y-balance">
                    <div class="yb-txt">Your Balance:</div>
                    <div class="yb-ammount">$2000.60</div>
                </div>
        	</div>
            <div class="dispute-wrapper">
            	<p>You currently have no withdrawals pending or queued for processing.</p>
                <div class="dispute-row">
                    <div class="detail">
                        <a href="javascript:void(0)" class="greyBtn">Make a withdrawal</a>
                        <a href="javascript:void(0)" class="change-bank">Change bank account</a>
                   	</div>
                </div>
            </div>
            <div class="dispute-wrapper">
                <div class="dispute-row">
               		<div class="title mW">Pending withdrawals:</div>
                    <div class="detail bb"><div class="tn">$2000 <b>to Bank Account</b></div><div>Requested Date: &nbsp; 2016-10-15 1:04</div></div>
                </div>
                <div class="dispute-row">
               		<div class="title mW">&nbsp;</div>
                    <div class="detail bb">Your withdrawals will be processed within <b>(7 - 14 business days)</b></div>
                </div>
            </div>
            <div class="dispute-wrapper">
            	<div class="field-item dispute-row">
					<div class="title mW">Payment Type:</div>
                    <div class="detail bb">
                    <label>Available Balance<input type="radio" id="" name="refund" class="" value="full"></label><br><br>
                    <label id="">Other Amount - $ 
                        <input type="radio" name="refund" class="" id="" value="partial">
                        <input type="text" name="claimed_amount" value="" id="" class="full_refund" placeholder="Amount"></label>
                    <span id="info2" style="color:red;display:none"></span>
                    </div>
                </div>
                
                
                
            	<div class="dispute-row">
               		<div class="title mW">Kinnect2 Fee:</div>
                    <div class="detail bb"><b>$0.00</b> (10% of the total withdrawal amount)</div>
                </div>
                <div class="dispute-row">
               		<div class="title mW">&nbsp;</div>
                    <div class="detail bb">You are about to send <b>$0.00</b> to you your bank account</div>
                </div>
                <div class="dispute-row">
               		<div class="title mW"></div>
                    <div class="detail">
                    	<a href="javascript:void(0)" class="orngBtn">Submit Request</a>
                        <a class="greyBtn" href="javascript:void(0)">Cancel</a>
                   	</div>
                </div>
            </div>
    	</div>
	</div>
</div>
@endsection