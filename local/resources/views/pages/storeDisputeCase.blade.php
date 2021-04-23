@extends('layouts.store-admin')
@section('content')
    <!-- Post Div-->
    @include('includes.arbitrator-leftnav')
 	<div class="ad_main_wrapper">
       <div class="task_inner_wrapper">
    	<div class="main_heading">
             <h1>Goods not received after payment</h1>
             <a href="javascript:void(0);" class="orngBtn fltR">Resolved</a>
             <a href="javascript:void(0);" class="assignBtn fltR mr10">Assign to</a>
        </div>
        <div class="assigned-task-wrapper">
        	<div class="dispute-wrapper disputeCase">
            	<h1>Dispute Detail</h1>
                <div class="dispute-row">
               		<div class="title">Status:</div>
                    <div class="detail">Supplier denied refund request</div>
                </div>
                <div class="dispute-row">
               		<div class="title">Track Ifno:</div>
                    <div class="detail"><div class="tn">Trackin number: &nbsp; Rl0453287CN</div><div>Shipping Time: &nbsp; 2016-10-15 1:04</div></div>
                </div>
                <div class="dispute-row">
               		<div class="title">Attachment:</div>
                    <div class="detail">
                    	<img width="75" height="75" alt="dispute images" src="http://localhost/kinnect2/local/public/assets/images/blankImage.png">
                        <img width="75" height="75" alt="dispute images" src="http://localhost/kinnect2/local/public/assets/images/blankImage.png">
                        <img width="75" height="75" alt="dispute images" src="http://localhost/kinnect2/local/public/assets/images/blankImage.png">
                    </div>
                </div>
                <div class="dispute-row">
               		<div class="title">Comments:</div>
                    <div class="detail">
                    	<div class="mb10">Please wait for the supplier to respond to your dispute. You can modify the details of your dispute or cancel your dispute by clicking the button below.</div>
                     	<div>If you cannot reach an agreement with the seller, you can file a claim for the order.</div>
                   	</div>
                </div>
            </div>
            <div class="dispute-msg">
            	<h1>Messages</h1>
                <div class="comnt-wrapper">
                    <a class="comntr-pic" href="#">
                        <img src="http://localhost/kinnect2/local/public/assets/images/blankImage.png" alt="User Name">
                    </a>
                    <div class="comnt-detail">
                        <div class="post-name">
                            <a href="">Mohsin Saeed</a>
                        </div>
                        <div class="label">Buyer</div>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s</p>
                        <em title="">February 17, 2016 9:21 PM</em>
                    </div>
                </div>
                <div class="comnt-wrapper">
                    <a class="comntr-pic" href="#">
                        <img src="http://localhost/kinnect2/local/public/assets/images/blankImage.png" alt="User Name">
                    </a>
                    <div class="comnt-detail">
                        <div class="post-name">
                            <a href="">Mohsin Saeed</a>
                        </div>
                        <div class="label">Seller</div>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s</p>
                        <em title="">February 17, 2016 9:21 PM</em>
                    </div>
                </div>
            </div>
            <div class="dispute-msg-write">
            	<h1>Send a messages</h1>
            	<textarea placeholder="Write your message here..."></textarea>
                 <a href="javascript:void(0);" class="orngBtn fltR">Send</a>
                 <a href="javascript:void(0);" class="add-attachment">Add attachment</a>
                 <div class="clrfix mb10"></div>
                 <div class="dispute-attach-box">
                 	<div class="attachment-title">tearms_and_conditions.pdf</div>
                    <div class="uploading">Uploading...</div>
                 </div>
                 <div class="dispute-attach-box">
                 	<div class="attachment-title">tearms_and_conditions.pdf</div>
                    <a href="#" charset="">Delete</a>
                 </div>
            </div>
        </div>
    </div>
   	</div>
@endsection