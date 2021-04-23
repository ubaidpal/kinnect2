@extends('layouts.store-admin')
@section('content')
    <!-- Post Div-->
    @include('includes.arbitrator-leftnav')
 	<div class="ad_main_wrapper">
       <div class="task_inner_wrapper">
    	<div class="main_heading">
             <h1>Payments</h1>
        </div>
        <div class="task-tabs">
        	<a class="active" href="#">Withdrawals</a>
            <a class="" href="#">Other Amount</a>
        </div>
        <!-- Admin Withdrawal Request - Search -->
        <div class="awr-search">
            <div class="fltR">
                <div class="awr-ttle">
                    <input type="text" placeholder="Type title name">
                </div>
                <div class="awr-select">
                    <select>
                        <option>All statuses</option>
                    </select>
                </div>
                <div class="awr-btn">
                    <button>Search</button>
                </div>
            </div>
        </div>
        <!-- Admin Withdrawal Request -->
        <div class="awd-6con">
            <!-- Admin Withdrawal Request - Item Bold -->
            <div class="awd-item awd-itemb">
                <div class="awdi-itm">Title</div>
                <div class="awdi-itm">Date</div>
                <div class="awdi-itm">Status</div>
                <div class="awdi-itm">Account <br/>Balance</div>
                <div class="awdi-itm">Withdrawal Amount</div>
                <div class="awdi-itm">Action</div>
            </div>
        
            <!-- Admin Withdrawal Request - Item -->
            <div class="awd-item">
                <div class="awdi-itm"><span class="brand">Apple</span><span class="awdi-badge">(Seller)</span></div>
                <div class="awdi-itm">March 10 2010</div>
                <div class="awdi-itm">Processing</div>
                <div class="awdi-itm">$1000</div>
                <div class="awdi-itm">$500</div>
                <div class="awdi-itm">
                    <a class="awdi-btn" href="javascript:void(0)">Bank Detail</a>
                    <a class="awdi-btn" href="javascript:void(0)">Pay</a>
                </div>
            </div>
        </div>
    </div>
   	</div>
@endsection