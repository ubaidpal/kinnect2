@extends('layouts.store-admin')
@section('content')
    <!-- Post Div-->
    @include('includes.arbitrator-leftnav')
 	<div class="ad_main_wrapper">
       <div class="task_inner_wrapper">
    	<div class="main_heading">
             <h1>Enter Info</h1>
        </div>
        <div class="bank-detail-popup">
            <div class="bd-ttle">Enter Info</div>
            <div class="bd-itm">
                <div class="bd-itml">Deposit to:</div>
                <div class="bd-itmr">
                    <input type="text" placeholder="Micheal Jordan">
                </div>
            </div>
            <div class="bd-itm">
                <div class="bd-itml">Date:</div>
                <div class="bd-itmr">
                    <input type="text" placeholder="08 March 2016">
                </div>
            </div>
            <div class="bd-itm">
                <div class="bd-itml">Deposit Slip Number:</div>
                <div class="bd-itmr">
                    <input type="text" placeholder="123456">
                </div>
            </div>
            <div class="bd-itm">
                <div class="bd-itml">Attachment:</div>
                <div class="bd-itmr">
                    <input class="btn-upld" type="text" placeholder="Deposit_slip_snapshot.jpg">
                    <div class="bd-btn-upload">Browse</div>
                </div>
            </div>
            <div class="bd-btnc">
                <button type="button">Confirm</button>
                <button type="button" class="button-grey">Cancel</button>
            </div>
        </div>
    </div>
   	</div>
@endsection