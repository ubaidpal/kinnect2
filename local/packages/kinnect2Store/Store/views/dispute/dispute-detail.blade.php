{{--

    * Created by   :  Muhammad Yasir
    * Project Name : kinnect2
    * Product Name : PhpStorm
    * Date         : 25-Feb-2016 1:14 PM
    * File Name    :

--}}
@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">
    @if($user->user_type == config('constants.BRAND_USER'))
        @include('Store::includes.store-admin-leftside')
    @else
        @include('Store::includes.store-order-leftside')
    @endif
    <div class="product-Analytics">
        <div class="post-box">
            <h1>Refund Request Detail</h1>

            <div class="dispute-wrapper">
                @if(Session::has('error'))
                    <div class="dispute-row">
                        <div class="title">&nbsp;</div>
                        <div class="detail bb" style="color:#f00000">{{html_entity_decode(Session::get('error'))}}</div>
                    </div>
                @endif
                @if(Session::has('success'))
                    <div class="dispute-row">
                        <div class="title">&nbsp;</div>
                        <div class="detail bb"
                             style="color:#008000">{{html_entity_decode(Session::get('success'))}}</div>
                    </div>
                @endif
                <div class="dispute-row">
                    <div class="title">Order ID:</div>
                    <div class="detail bb">{{$order->order_number}}</div>
                </div>
                @if(isset($claim_detail))
                    <div class="dispute-row">
                        <div class="title">Title:</div>
                        <div class="detail bb">

                            {{$claim_detail->title}}
                        </div>
                    </div>
                    @if($claim_detail->fee_paid)
                        <div class="dispute-row">
                            <div class="title">Fee Paid:</div>
                            <div class="detail bb">&dollar;{{format_currency($claim_detail->fee_amount)}}</div>
                        </div>
                    @endif
                @endif
                <div class="dispute-row">
                    <div class="title">Status:</div>
                    <div class="detail bb">

                        {{ dispute_status($dispute->status, $user->user_type) }}
                    </div>
                </div>
                <div class="dispute-row">
                    <div class="title">Track Info:</div>
                    <div class="detail bb">
                        <div class="tn">Tracking number:
                            &nbsp;@if($shipping_info)  {{$shipping_info->order_tracking_number}}@endif</div>
                        <div>Shipping Time:
                            &nbsp; @if($shipping_info)  {{$shipping_info->date_to_be_delivered}}@endif</div>
                    </div>
                </div>
                <div class="dispute-row">
                    <div class="title">Details:</div>
                    <div class="detail bb">
                        @if(isset($claim_detail))
                            {{$claim_detail->detail}}
                        @else
                            {{$dispute->detail}}
                        @endif
                    </div>
                </div>
                <div class="dispute-row">
                    <div class="title">Claimed amount:</div>
                    <div class="detail bb">&dollar;
                        @if($dispute->claim_request == 'full')
                            {{format_currency($order_transection->amount)}}
                        @else
                            {{format_currency($dispute->claimed_amount)}}
                        @endif
                    </div>
                </div>

                @if(isset($claim_detail) && $claim_detail->status == config('admin_constants.CLAIM_STATUS.RESOLVED'))
                    <div class="dispute-row">
                        <div class="title">Resolved in Favour of:</div>
                        <div class="detail bb">
                            @if($claim_detail->favour_of_seller == 1 && $claim_detail->favour_of_buyer ==1)
                                Both(Seller & Buyer)
                            @elseif($claim_detail->favour_of_seller == 1)
                                Seller
                            @else
                                Buyer
                            @endif
                        </div>
                    </div>
                    <div class="dispute-row">
                        <div class="title">Decided Amount:</div>
                        <div class="detail">
                            &dollar;
                            {{format_currency($claim_detail->amount)}}

                        </div>
                    </div>
                @endif
                @if($order->is_refunded)
                    <div class="dispute-row">
                        <div class="title">Refunded Amount:</div>
                        <div class="detail bb">&dollar;{{format_currency($order->refund_amount)}}</div>
                    </div>
                @endif
                @if(isset($requestedProducts) && count($requestedProducts) > 0)
                    <div class="field-item dispute-row">
                        <div class="title mW mt15">Selected Product:</div>
                        <div class="detail bb">
                            <div class="image-box">Product Image</div>
                            <div class="price-box">Product Price</div>

                        </div>
                        @foreach($requestedProducts as $product)
                            <div class="detail bb">
                                <div class="image-box">
                                    <img src="{{ getProductPhotoSrc('','',$product->id, 'product_profile') }}"
                                         width="210"
                                         height="151" alt="img">
                                </div>
                                <?php $discountedPercented = ($product->price / 100 ) * $product->discount; ?>

                                <div class="price-box">&dollar;{{format_currency($product->price - $discountedPercented)}}</div>

                            </div>
                        @endforeach
                    </div>
                @endif

                @if($files != '')
                    <div class="dispute-row">
                        <div class="title">Attachment:</div>
                        <div class="detail bb">

                            @foreach($files as $row)
                                <?php //echo '<tt><pre>'; print_r($row->storageFile); die;
                                ?>
                                <a class="attachment-img" href="{{getPhotoUrlByFile($row->storageFile)}}">
                                <img src="{!! getPhotoUrlByFile($row->storageFile) !!}" width="75" height="44"
                                     alt="dispute images"/>
                                </a>
                            @endforeach

                        </div>
                    </div>
                @endif
                @if($user->user_type == config('constants.BRAND_USER') || $user->user_type == config('constants.REGULAR_USER'))
                    {{--@if($dispute->status != config('constants_brandstore.DISPUTE_STATUS.DISPUTE_CANCELLED_BUYER'))--}}
                    @if(is_null($dispute->status))
                        <div class="dispute-row">
                            <div class="title">Note:</div>
                            <div class="detail bb">
                                @if($user->user_type == config('constants.BRAND_USER'))
                                    @if($dispute->status == \Config::get('constants_brandstore.DISPUTE_STATUS.DISPUTE_ACCEPTED'))
                                        <div class="mb10">{{config('constants_brandstore.DISPUTE_NOTE.DISPUTE_ACCEPTED_SELLER')}}</div>
                                    @else
                                        <div class="mb10">{{config('constants_brandstore.DISPUTE_NOTE.BRAND')}}
                                        </div>
                                    @endif
                                @else
                                    @if($dispute->status == \Config::get('constants_brandstore.DISPUTE_STATUS.DISPUTE_ACCEPTED'))
                                        <div class="mb10">{{config('constants_brandstore.DISPUTE_NOTE.DISPUTE_ACCEPTED_BUYER')}}</div>
                                    @else
                                        <div class="mb10">
                                            {{config('constants_brandstore.DISPUTE_NOTE.BUYER')}}
                                        </div>
                                    @endif
                                @endif

                            </div>
                        </div>
                    @endif
                @endif
                <div class="dispute-row">
                    <div class="title"></div>
                    <div class="detail">
                        @if($user->user_type == Config::get('constants.REGULAR_USER'))
                            @if(is_null($dispute->status))
                                <a href="{{url('store/dispute/modify/'.$dispute->reference_id)}}" class="blueBtn">
                                    Modify
                                </a>
                                <a class="greyBtn"
                                   href="{{url('store/dispute/cancel/'.$dispute->reference_id)}}">Cancel</a>
                            @endif
                            @if($dispute->status != config('constants_brandstore.DISPUTE_STATUS.DISPUTE_CANCELLED_BUYER') && $dispute->status != config('constants_brandstore.DISPUTE_STATUS.RESOLVED') && $dispute->status != config('constants_brandstore.DISPUTE_STATUS.CLAIMED_BY_BUYER') && $dispute->status != config('constants_brandstore.DISPUTE_STATUS.DISPUTE_ACCEPTED') )
                                <a class="file-claim" href="#courierServiceInfo">Open Dispute</a>
                            @endif
                        @else
                            @if(is_null($dispute->status)  )
                                <a href="{{url('store/dispute/accept/'.$dispute->reference_id)}}"
                                   class="blueBtn accept_dispute">
                                    Accept Request
                                </a>
                                <a class="greyBtn"
                                   href="{{url('store/dispute/cancel/'.$dispute->reference_id)}}">Reject</a>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
        @if(is_null($dispute->status) || $dispute->status == config('constants_brandstore.DISPUTE_STATUS.CLAIMED_BY_BUYER'))
            <h1 class="mb10">Leave a message</h1>
        @endif
        <div id="messageBox">
            @if(isset($messages) && $messages !=  'No more Message')
                @foreach($messages as $msg)
                    <div class="comnt-wrapper">
                        <a href="{{$msg['sender_url']}}" class="comntr-pic">
                            <img alt="{{$msg['sender_name']}}" src="{!! $msg['profile_pic'] !!}">
                        </a>

                        <div class="comnt-detail">
                            <div class="post-name">
                                <a href="">{{$msg['sender_name']}}</a>
                            </div>
                            <p>{{$msg['content']}}</p>
                            @if(isset($msg['file_name']) && !empty($msg['file_name']))
                                <span class="attachment-icon"></span>

                                <div class="linkDownload">
                                    <span class="attachment-name">{{$msg['file_name']}}</span>
                                    <span class="attachment-url"><a href="{{$msg['url']}}"
                                                                    download="">Download</a></span>
                                </div>

                            @endif
                            <em title="">{{getTimeByTZ($msg['created_at'], 'F d Y h:i A')}}</em>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
        @if(is_null($dispute->status) || $dispute->status == config('constants_brandstore.DISPUTE_STATUS.CLAIMED_BY_BUYER'))
            <div id="loading-2" style="text-align: center; display: none">
                <img id="loading-image" src="{!! asset('local/public/images/loading.gif') !!}"
                     alt="Loading..."/>
            </div>
            <div class="leave-msg">
                <div class="attachIcon" id="chat-attachment">
                    Attach
                </div>
                {!! Form::open(['url' => 'store/dispute/message', 'id' => 'msg-form']) !!}

                <input type="file" accept="" id="postFiles" name="attachment"
                       style="position: fixed; top: -30px;"/>
                @if(is_null($dispute->conv_id))
                    {!! Form::hidden('receiver_id',$seller_id) !!}
                    {!! Form::hidden('dispute_id',$dispute->reference_id) !!}
                @else
                    {!! Form::hidden('conv_id',$dispute->conv_id) !!}
                @endif
                <textarea id="msg-body" name="body" class="" maxlength=""
                          placeholder="Start discussion from here..."></textarea>

                <div>
                    <button type="submit" title="Send" class="blueBtn cht-send">Send</button>
                </div>
                {!! Form::close() !!}

            </div>
        @endif
    </div>
</div>

<div class="modal-box delete" id="accept_dispute_popup">
    <a href="#" class="js-modal-close close">&times;</a>

    <div class="modal-body">
        <div class="edit-photo-poup">
            <h3 style="color: #0080e8;">Accept Refund Request</h3>

            <p class="mt10 mb10">Please note that the claimed amount for this request will be transferred to the<br>
                buyer via worldpay refund facility.</p>
            <input type="button" class="btn fltL blue mr10" id="confirm" value="Confirm"/>
            <input type="button" id="no" class="btn blue js-modal-close fltL close" value="Cancel"/>
        </div>
    </div>
</div>

<div class="modal-box" id="attachment">
    <a href="#" class="js-modal-close close">&times;</a>

    <div class="modal-body">
        <div class="edit-photo-poup">
            <img src="" id="attachment_img" width="650">
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).on('click', '.accept_dispute', function (e) {
        e.preventDefault();
        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
        $("body").append(appendthis);

        $(".modal-overlay").fadeTo(500, 0.7);

        jQuery('#accept_dispute_popup').show();
    });
    jQuery(document).on('click', '#confirm', function (e) {
        e.preventDefault();
        window.location = jQuery('.accept_dispute').attr('href');
    });
    jQuery(document).on('click','.attachment-img',function (e) {
        e.preventDefault();
        var src = jQuery(this).attr('href');

        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
        $("body").append(appendthis);

        $(".modal-overlay").fadeTo(500, 0.7);
        jQuery('#attachment').show();

        jQuery('#attachment_img').attr('src',src);
    });
</script>
@endsection
@section('footer-scripts')
    {!! HTML::script('local/public/assets/js/pages/dispute.js') !!}
    <div id="courierServiceInfo" class="cssPopup_overlay">
        <div class="cssPopup_popup claim-detail p0">
            <a class="cssPopup_close" href="#">&times;</a>

            <div class="">
                <div class="addProduct">
                    <h1>Dispute Detail</h1>


                    <div id="delivery_info_form_wrap" class="delivery_info_form_wrap">
                        {!!  Form::open( [ 'url' => url( "store/claim/store" ), "id" => "add_courier_service_info", "enctype" => "multipart/form-data" ] ) !!}
                        {!! Form::hidden('dispute_id', $dispute->id) !!}
                        {!! Form::hidden('owner_type', 'dispute') !!}
                        {!! Form::hidden('user_id', $user_id) !!}
                        <div class="field-item">
                            <label for="courier_service_name">Title</label>
                            {!! Form::text('title') !!}
                        </div>
                        <div class="field-item">
                            <label for="courier_service_name">Select Reason</label>
                            {!! Form::select('reason',  $reasons,NULL) !!}
                        </div>


                        <div class="field-item">
                            <label for="date_to_be_delivered">Detail</label>
                            <textarea id="date_to_be_delivered" name="detail"
                                      placeholder="Enter Order delivery detail" required></textarea>
                        </div>
                        <!--<h1>Bank Detail</h1>

                        <div class="field-item">
                            <label for="courier_service_name">Account Title</label>
                            {!! Form::text('account_title') !!}
                                </div>
                                <div class="field-item">
                                    <label for="courier_service_name">Bank Name</label>
                                    {!! Form::text('bank_name') !!}
                                </div>
                                <div class="field-item">
                                    <label for="courier_service_name">Account Number</label>
                                    {!! Form::text('account_number') !!}
                                </div>
                                <div class="field-item">
                                    <label for="courier_service_name">IBAN Code</label>
                                    {!! Form::text('iban_code') !!}
                                </div>
                                <div class="field-item">
                                    <label for="courier_service_name">Swift Code</label>
                                    {!! Form::text('swift_code') !!}
                                </div>
                                <div class="field-item">
                                    <label for="courier_service_name"> Bank Country</label>
                                    {!! Form::select('country', $countries,NULL) !!}
                                </div>
                                -->
                        <div class="fltR mt20 mb20">

                            <button id="addOrderDeliveryButton" class="btn blue fltL mr10"
                                    type="submit">Save
                            </button>
                        </div>


                        {!!Form::close() !!}


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

