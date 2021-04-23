@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')  

<div class="mainCont">

@include('includes.store-admin-leftside')

<div class="product-Analytics">   
 <div class="post-box">
     <h1>Orders</h1>
     
     <div class="bsm-nav">
      <a href="javascript:void(0)">In Progress (1)</a>
      <a href="javascript:void(0)">Awaiting Dispatch (1)</a>
      <a href="javascript:void(0)">Finished Order (1)</a>
      <a href="javascript:void(0)">Dispute</a>
     </div>

        <div class="selectdiv mb10">
         <input type="text" placeholder="Order Number" class="storeInput fltL mr10 w_200">
         <input type="text" placeholder="Product" class="storeInput fltL mr10 w_200">
            <a href="javascript:void(0);" class="btn blue fltL">Add</a>
        </div>
		<div class="bsmo-nav">
             <div class="bsmo-product">
              <a href="javascript:void(0)">Product</a>
             </div>
             <div class="bsmo-paction">
              <a href="javascript:void(0)">Product Action</a>
             </div>
             <div class="bsmo-ostatus">
              <a href="javascript:void(0)">Order Status</a>
             </div>
             <div class="bsmo-oamount">
              <a href="javascript:void(0)">Order Amount</a>
             </div>
        </div>
        <!-- Order Brand Item -->
        <div class="orderb-item">
         <div class="oi-header">
          <div class="oi-image">
           	<div class="oi-product">
            	<a href="javascript:void(0)"><img src="" alt="IMAGE"></a>
          		<div class="oi-title">55" JU6800 6 Series Flat UHD 5K Nano Crystal Smart TV</div>
            </div>
            <div class="oi-product">
            	<a href="javascript:void(0)"><img alt="IMAGE" src=""></a>
          		<div class="oi-title">55" JU6800 6 Series Flat UHD 5K Nano Crystal Smart TV</div>
            </div>
            <div class="oi-product">
            	<a href="javascript:void(0)"><img alt="IMAGE" src=""></a>
          		<div class="oi-title">55" JU6800 6 Series Flat UHD 5K Nano Crystal Smart TV</div>
            </div>
          </div>
           <div class="oi-action">Confirmation Received</div>
           <div class="oi-status">Finished</div>
           <div class="oi-amount"><p class="oi-price">$20.80</p></div>
         </div>
         <div class="oi-footer">
          <div class="oi-detail">
           <p class="mb5">
            Order ID: 12451245124512 <a href="javascript:void(0)">View Detail</a>
           </p>
           <p>
            Order time & date: 09:04 Oct. 13 2014
           </p>
          </div>
          <div class="oi-profile">
           <p class="mb5">
            Buyer Name: Any Name
           </p>
           <p>
            <a href="javascript:void(0)">View Profile</a>
           </p>
          </div>
         </div>
        </div>

        <!-- Order Brand Item -->
        <div class="orderb-item">
         <div class="oi-header">
          <div class="oi-image">
           	<div class="oi-product">
            	<a href="javascript:void(0)"><img alt="IMAGE" src=""></a>
          		<div class="oi-title">55" JU6800 6 Series Flat UHD 5K Nano Crystal Smart TV</div>
            </div>
          </div>
           <div class="oi-action">
            <a class="btn" href="javascript:void(0)">Confirm</a>
            <a class="btn btng" href="javascript:void(0)">Cancel</a>
           </div>
           <div class="oi-status">Awaiting Dispatch</div>
           <div class="oi-amount">
            <p class="oi-price">$20.80</p>
           </div>
         </div>
         <div class="oi-footer">
          <div class="oi-detail">
           <p class="mb5">
            Order ID: 12451245124512 <a href="javascript:void(0)">View Detail</a>
           </p>
           <p>
            Order time & date: 09:04 Oct. 13 2014
           </p>
          </div>
          <div class="oi-profile">
           <p class="mb5">
            Buyer Name: Any Name
           </p>
           <p>
            <a href="javascript:void(0)">View Profile</a>
           </p>
          </div>
         </div>
        </div>
    </div>
</div>
</div>
@endsection