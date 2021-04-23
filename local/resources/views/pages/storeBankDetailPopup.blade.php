@extends('layouts.store-admin')
@section('content')
    <!-- Post Div-->
    @include('includes.arbitrator-leftnav')
 	<div class="ad_main_wrapper">
       <div class="task_inner_wrapper">
    	<div class="main_heading">
             <h1>Bank Detail Popup</h1>
        </div>
        <div class="bank-detail-popup">
            <div class="bd-ttle">Bank Deatils</div>
            <div class="bd-itm">
                <div class="bd-itml">Bank Account Holder's Name:</div>
                <div class="bd-itmr">
                    <input type="text" placeholder="Micheal Jordan">
                </div>
            </div>
            <div class="bd-itm">
                <div class="bd-itml">Swift Code:</div>
                <div class="bd-itmr">
                    <input type="text" placeholder="012345">
                </div>
            </div>
            <div class="bd-itm">
                <div class="bd-itml">Bank Account Number/IBAN:</div>
                <div class="bd-itmr">
                    <input type="text" placeholder="0000 AAAA 0000 AAAA">
                </div>
            </div>
            <div class="bd-itm">
                <div class="bd-itml">Bank Name in Full:</div>
                <div class="bd-itmr">
                    <input type="text" placeholder="Royal Bank of Scotland">
                </div>
            </div>
            <div class="bd-itm">
                <div class="bd-itml">Bank Branch City:</div>
                <div class="bd-itmr">
                    <input type="text" placeholder="London">
                </div>
            </div>
            <div class="bd-itm">
                <div class="bd-itml">Bank Branch Country:</div>
                <div class="bd-itmr">
                    <input type="text" placeholder="United Kingdom">
                </div>
            </div>
        </div>
    </div>
   	</div>
@endsection