@extends('layouts.store-admin')
@section('content')
    <!-- Post Div-->
    @include('includes.arbitrator-leftnav')
 	<div class="ad_main_wrapper">
       <div class="task_inner_wrapper">
        <div class="main_heading">
             <h1>Withdrawal Popup</h1>
        </div>
        
        <!-- Withdrawl Detail Popup -->
        <div class="wd-popup">
            <div class="wd-ttle">Withdrawal Detail</div>
            <div class="wd-itm">
                <div class="wd-itml">Ref ID:</div>
                <div class="wd-itmr">12341234123412</div>
                <div class="wd-date">
                    <div class="wd-datel">Date:</div>
                    <div class="wd-dater">8 March 2016</div>
                </div>
            </div>
            <div class="wd-itm">
                <div class="wd-itml">Deposit to:</div>
                <div class="wd-itmr">F Sisto<br>MANASQUAN<br>New Jersey<br>08989<br>United States</div>
            </div>
            <div class="wd-itm">
                <div class="wd-itml">Amount:</div>
                <div class="wd-itmr">$120.00</div>
            </div>
            <div class="wd-itm">
                <div class="wd-itml">Attachment:</div>
                <div class="wd-itmr"><a href="javascript:void(0)">Download</a></div>
            </div>
        </div>
    	</div>
   	</div>
@endsection