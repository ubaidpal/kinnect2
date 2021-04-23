@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')  

<div class="mainCont">

@include('includes.store-admin-leftside')

<div class="product-Analytics">   
    <div class="post-box">
        <!-- Order Detail -->
        <div class="o-detail">
            <div class="od-item">
                <div class="od-iteml">Order Number :</div>
                <div class="od-itemr">64125090856047</div>
            </div>
            <div class="od-item">
                <div class="od-iteml">Status :</div>
                <div class="od-itemr">Finished</div>
            </div>
            <div class="od-item">
                <div class="od-iteml">Reminder :</div>
                <div class="od-itemr">You have confirmed order received.</div>
            </div>
        </div>

        <!-- Order Detail Label -->
        <div class="od-label">Shipping Information</div>

        <!-- Order Detail Shipping Information -->
        <div class="od-ship">
            <!-- Order Detail Shipping Information - Title -->
            <div class="ods-title">
                <div class="ods-title-item">Courier Company</div>
                <div class="ods-title-item">Tracking Number</div>
                <div class="ods-title-item">Estimated Delivery Time</div>
                <div class="ods-title-item">Processing Time</div>
            </div>

            <!-- Order Detail Shipping Information - Content -->
            <div class="ods-content">
                <div class="ods-content-title">
                    <div class="ods-ct-item">International Post Air Mail</div>
                    <div class="ods-ct-item">RL046408913CN</div>
                    <div class="ods-ct-item">15-23 Days</div>
                    <div class="ods-ct-item">20 Days</div>
                </div>

                <div class="ods-content-detail">
                    <div class="ods-cd-item">
                        <div class="ods-cd-title">Ship to:</div>
                        <div class="ods-cd-detail">
                            <div class="ods-cdd-item">
                                <div class="ods-cdd-iteml">Contact Name :</div>
                                <div class="ods-cdd-itemr">John Doe</div>
                            </div>
                            <div class="ods-cdd-item">
                                <div class="ods-cdd-iteml">Address :</div>
                                <div class="ods-cdd-itemr">23/24 Leinster Gardens, Paddington, London.</div>
                            </div>
                            <div class="ods-cdd-item">
                                <div class="ods-cdd-iteml">Contact Name :</div>
                                <div class="ods-cdd-itemr">John Doe</div>
                            </div>
                            <div class="ods-cdd-item">
                                <div class="ods-cdd-iteml">Zip Code :</div>
                                <div class="ods-cdd-itemr">JW254770</div>
                            </div>
                            <div class="ods-cdd-item">
                                <div class="ods-cdd-iteml">ZMobile :</div>
                                <div class="ods-cdd-itemr">00447204721305</div>
                            </div>
                            <div class="ods-cdd-item">
                                <div class="ods-cdd-iteml">Tel :</div>
                                <div class="ods-cdd-itemr">00447204721305</div>
                            </div>
                        </div>
                    </div>
                    <div class="ods-cd-item">
                        <div class="selected-item-detail">
                            <div class="ods-cdd-iteml">Store name :</div>
                            <div class="ods-cdd-itemr">Samsung</div>
                        </div>
                        <div class="sid-link">
                            <a href="javascript:void(0)">View Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Detail Label -->
        <div class="od-label">Financial Information</div>
        
        <!-- Order Detail Label - Small -->
        <div class="od-labels">Total Amount:</div>

        <div class="ods-title">
            <div class="ods-title-item">Price</div>
            <div class="ods-title-item"></div>
            <div class="ods-title-item">Shipping Cost</div>
            <div class="ods-title-item">Total Amount</div>
        </div>
        <div class="ods-title ods-title-ammount">
            <div class="ods-title-item">GBP £ 18.80</div>
            <div class="ods-title-item"></div>
            <div class="ods-title-item">GBP £ 0.00</div>
            <div class="ods-title-item">GBP £ 18.80</div>
        </div>
        
        <!-- Order Detail Label - Small -->
        <div class="od-labels">Payment Received:</div>
        <div class="ods-title">
            <div class="ods-title-item">Total</div>
            <div class="ods-title-item">Received</div>
            <div class="ods-title-item">Payment Method</div>
            <div class="ods-title-item">Date</div>
        </div>
        <div class="ods-title ods-title-ammount">
            <div class="ods-title-item">GBP £ 18.80</div>
            <div class="ods-title-item">GBP £ 18.80</div>
            <div class="ods-title-item">Credit Card</div>
            <div class="ods-title-item">2015-10-15 01:04</div>
        </div>

        <!-- Order Detail Label -->
        <div class="od-label">Order Details</div>
        <div class="ods-title ods-title5">
            <div class="ods-title-item">Product Details</div>
            <div class="ods-title-item">Price Per Unit</div>
            <div class="ods-title-item">Quantity</div>
            <div class="ods-title-item">Order Total</div>
            <div class="ods-title-item">Status</div>
        </div>
		<div class="orderb-item">
         <div class="oi-header orderDetail">
          <div class="oi-image">
           	<div class="oi-product">
            	<a href="javascript:void(0)"><img src="" alt="IMAGE"></a>
          		<div class="oi-title">55" JU6800 6 Series Flat UHD 5K Nano Crystal Smart TV</div>
            </div>
          </div>
           <div class="oi-amount">
            <p class="oi-price">$20.80</p>
           </div>
           <div class="oi-quantity">
            <p class="oi-price">1 Piece</p>
           </div>
           <div class="oi-amount">
            <p class="oi-price">$20.80</p>
           </div>
           <div class="oi-status">Confirmation Received</div>
         </div>
         <div class="product-total">
         	<div class="total-cost">
            	<div class="title">Product Amount</div>
                <div class="value">GBP &pound; 18.80</div>
            </div>
            <div class="shipping-amount">
            	<div class="title">Shipping Cost</div>
                <div class="value">GBP &pound; 18.80</div>
            </div>
            <div class="product-amount">
            	<div class="title">Total Amount</div>
                <div class="value">GBP &pound; 18.80</div>
            </div>
         </div>
        </div>
    </div>
</div>
@endsection