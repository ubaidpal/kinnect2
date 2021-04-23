@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')  

<div class="mainCont">

@include('includes.store-admin-leftside')

	<div class="product-Analytics">   
 		<div class="post-box">
     		<h1>Dispute Detail</h1>
            <div class="dispute-wrapper">
            	<div class="dispute-row">
               		<div class="title">Order ID:</div>
                    <div class="detail bb">6412564005241</div>
                </div>
                <div class="dispute-row">
               		<div class="title">Status:</div>
                    <div class="detail bb">You have opened a dispute for this order. Please wait for the supplier to respond.</div>
                </div>
                <div class="dispute-row">
               		<div class="title">Track Ifno:</div>
                    <div class="detail bb"><div class="tn">Trackin number: &nbsp; Rl0453287CN</div><div>Shipping Time: &nbsp; 2016-10-15 1:04</div></div>
                </div>
                <div class="dispute-row">
               		<div class="title">Details:</div>
                    <div class="detail bb">The goods delivered are not satisfactory</div>
                </div>
                <div class="dispute-row">
               		<div class="title">Attachment:</div>
                    <div class="detail bb">
                    	<img src="{!! asset('local/public/assets/images/blankImage.png') !!}" width="75" height="75" alt="dispute images" />
                        <img src="{!! asset('local/public/assets/images/blankImage.png') !!}" width="75" height="75" alt="dispute images" />
                        <img src="{!! asset('local/public/assets/images/blankImage.png') !!}" width="75" height="75" alt="dispute images" />
                    </div>
                </div>
                <div class="dispute-row">
               		<div class="title">Reminder:</div>
                    <div class="detail bb">
                    	<div class="mb10">Please wait for the supplier to respond to your dispute. You can modify the details of your dispute or cancel your dispute by clicking the button below.</div>
                     	<div>If you cannot reach an agreement with the seller, you can file a claim for the order.</div>
                   	</div>
                </div>
                <div class="dispute-row">
               		<div class="title"></div>
                    <div class="detail">
                    	<a href="javascript:void(0)" class="orngBtn">Modify Dispute</a>
                        <a class="greyBtn" href="javascript:void(0)">Cancel</a>
                        <a class="file-claim" href="javascript:void(0)">File a claim</a>
                   	</div>
                </div>
            </div>
    	</div>
        <h1 class="mb10">Leave a message</h1>
        <div class="comnt-wrapper">
       		<a href="#" class="comntr-pic">
                <img alt="User Name" src="{!! asset('local/public/assets/images/blankImage.png') !!}">
            </a>
            <div class="comnt-detail">
                <div class="post-name">
                    <a href="">Mohsin Saeed</a>
                </div>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s</p>
                <em title="">February 17, 2016 9:21 PM</em>
            </div>
        </div>
        <div class="comnt-wrapper">
       		<a href="#" class="comntr-pic">
                <img alt="User Name" src="{!! asset('local/public/assets/images/blankImage.png') !!}">
            </a>
            <div class="comnt-detail">
                <div class="post-name">
                    <a href="">Mohsin Saeed</a>
                </div>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s</p>
                <em title="">February 17, 2016 9:21 PM</em>
            </div>
        </div>
        <div class="comnt-wrapper">
       		<a href="#" class="comntr-pic">
                <img alt="User Name" src="{!! asset('local/public/assets/images/blankImage.png') !!}">
            </a>
            <div class="comnt-detail">
                <div class="post-name">
                    <a href="">Mohsin Saeed</a>
                </div>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s</p>
                <em title="">February 17, 2016 9:21 PM</em>
            </div>
        </div>
        <div class="leave-msg">
        	<div class="attachIcon" id="chat-attachment"></div>
            <form class="my-comment">
                <textarea autocomplete="off" name="" class="" size="60" maxlength="" alt="" placeholder="Start discussion from here..."></textarea>
                <div><a title="Send" class="orngBtn">Send</a></div>
            </form>
            
        </div>
	</div>
</div>
@endsection