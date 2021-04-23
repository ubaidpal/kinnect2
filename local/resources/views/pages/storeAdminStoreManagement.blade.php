@extends('layouts.store-admin')
@section('content')
    <!-- Post Div-->
    @include('includes.arbitrator-leftnav')
 	<div class="ad_main_wrapper">
       <div class="task_inner_wrapper">
    	<div class="main_heading">
             <h1>Sales & Accounts</h1>
             <a class="orngBtn fltR" href="javascript:void(0);">Search</a>
             <input class="search" type="text" placeholder="Type store name">
        </div>
        <div class="assigned-task-wrapper">
        	<div class="user-table heading">
            	<div class="name">Store Name</div>
                <div class="email">Total Sales</div>
                <div class="role">Total Orders</div>
                <div class="action">Account Balance</div>
            </div>
            <div class="user-table">
            	<div class="name"><a href="#">Apple</a></div>
                <div class="email">$100,000</div>
                <div class="role">15</div>
                <div class="action">$200,000</div>
            </div>
            <div class="user-table">
            	<div class="name"><a href="#">Caterpillar</a></div>
                <div class="email">$100,000</div>
                <div class="role">15</div>
                <div class="action">$200,000</div>
            </div>
            <div class="user-table">
            	<div class="name"><a href="#">Apple</a></div>
                <div class="email">$100,000</div>
                <div class="role">15</div>
                <div class="action">$200,000</div>
            </div>
            <div class="user-table">
            	<div class="name"><a href="#">Caterpillar</a></div>
                <div class="email">$100,000</div>
                <div class="role">15</div>
                <div class="action">$200,000</div>
            </div>
            <div class="user-table">
            	<div class="name"><a href="#">Apple</a></div>
                <div class="email">$100,000</div>
                <div class="role">15</div>
                <div class="action">$200,000</div>
            </div>
            <div class="user-table">
            	<div class="name"><a href="#">Caterpillar</a></div>
                <div class="email">$100,000</div>
                <div class="role">15</div>
                <div class="action">$200,000</div>
            </div>
            <div class="user-table">
            	<div class="name"><a href="#">Apple</a></div>
                <div class="email">$100,000</div>
                <div class="role">15</div>
                <div class="action">$200,000</div>
            </div>
            <div class="user-table">
            	<div class="name"><a href="#">Caterpillar</a></div>
                <div class="email">$100,000</div>
                <div class="role">15</div>
                <div class="action">$200,000</div>
            </div>
        </div>
    </div>
   	</div>
@endsection