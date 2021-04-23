{{--

    * Created by   :  Muhammad Yasir
    * Project Name : kinnect2
    * Product Name : PhpStorm
    * Date         : 04-Mar-2016 6:45 PM
    * File Name    : INDEX

--}}
@extends('admin.layout.store-admin')
@section('content')
        <!-- Post Div-->
@include('admin.layout.arbitrator-leftnav')
@include('admin.alert.alert')
<div class="ad_main_wrapper">
    <div class="task_inner_wrapper">
        <div class="main_heading">
            <h1>{{$claim->title}}</h1>
            @can('arbitrator', $claim)
            {{--<a href="{{url($admin_url.'claim/resolved/'.$claim->uuid)}}" class="orngBtn fltR">Resolved</a>--}}
            <a href="#claim-resolved" class="orngBtn fltR">Mark Resolved</a>

            @endcan
            {{-- <a href="javascript:void(0);" class="assignBtn fltR mr10">Assign to</a>--}}
            @role('super.admin')
            <div class="assignto">
                {!! Form::select('arbitrator', ['placeholder' => 'Assign to']+$arbitrator,$claim->arbitrator_id,['class'=>'assign-claim', 'data-id' => $claim->uuid]) !!}
            </div>
            @endrole
            @role('dispute.manager')
            <div class="assignto">
                {!! Form::select('arbitrator', ['placeholder' => 'Assign to']+$arbitrator,$claim->arbitrator_id,['class'=>'assign-claim', 'data-id' => $claim->uuid]) !!}
            </div>
            @endrole
        </div>

        <div class="assigned-task-wrapper">
            <div class="dispute-wrapper disputeCase">
                <h1>Dispute Detail</h1>
                @if($claim->status != config('admin_constants.CLAIM_STATUS.NOT_ASSIGNED'))
                    <div class="dispute-row">
                        <div class="title">Assigned to:</div>
                        <div class="detail">
                            {{user_name($claim->arbitrator_id)}}
                        </div>
                    </div>
                @endif
                <div class="dispute-row">
                    <div class="title">Title:</div>
                    <div class="detail">{{$claim->title  }}</div>
                </div>
                <div class="dispute-row">
                    <div class="title">Status:</div>
                    <div class="detail">{{ dispute_status($dispute->status, Auth::user()->user_type) }}</div>
                </div>
                <div class="dispute-row">
                    <div class="title">Track info:</div>
                    <div class="detail">
                        <div class="tn">
                            Tracking number: &nbsp; @if($shipping_info)  {{$shipping_info->order_tracking_number}}@endif
                        </div>
                        <div>Shipping Time:
                            &nbsp; @if($shipping_info)  {{$shipping_info->date_to_be_delivered}}@endif</div>
                    </div>
                </div>

                <div class="dispute-row">
                    <div class="title">Claimed Amount:</div>
                    <div class="detail">$
                        @if($dispute->claim_request == 'full')
                            {{format_currency($order_transection->amount)}}
                        @else
                            {{$dispute->claimed_amount}}
                        @endif
                    </div>
                </div>
                @if($claim->status == config('admin_constants.CLAIM_STATUS.RESOLVED'))
                    <div class="dispute-row">
                        <div class="title">Resolved in Favour of:</div>
                        <div class="detail">
                            @if($claim->favour_of_seller == 1 && $claim->favour_of_buyer ==1)
                                Both(Seller & Buyer)
                            @elseif($claim->favour_of_seller == 1)
                                Seller
                            @else
                                Buyer
                            @endif
                        </div>
                    </div>
                    <div class="dispute-row">
                        <div class="title">Decided Amount:</div>
                        <div class="detail">

                            $ {{format_currency($claim->amount)}}

                        </div>
                    </div>
                @endif
                @if(isset($requestedProducts) && count($requestedProducts) > 0)
                    <div class="dispute-row">
                        <div class="title">Selected Products:</div>
                        <div class="detail">
                            <div class="image-box">Product Image</div>
                            <div class="price-box">Product Price</div>
                        </div>
                        @foreach($requestedProducts as $product)
                        	<div class="title">&nbsp;</div>
                            <div class="detail">
                                <div class="image-box"><img src="{{ getProductPhotoSrc('','',$product->id, 'product_profile') }}"
                                     width="150"
                                     height="100" alt="img"></div>
                                <div class="price-box">{{format_currency($product->price)}}</div>
                            </div>
                        @endforeach

                    </div>
                    <div class="title">&nbsp;</div>
                    <div class="detail"><a class="orngBtn fltR" href="{{url('admin/order-invoice/'.$dispute->order_id)}}" style="padding:0px 20px;">Order Detail</a> </div>
                @endif
                @if(!empty($files))
                    <div class="dispute-row">
                        <div class="title">Attachment:</div>
                        <div class="detail">

                            @foreach($files as $row)
                                <?php //echo '<tt><pre>'; print_r($row->storageFile); die;
                                ?>
                                <img src="{!! getPhotoUrlByFile($row->storageFile) !!}" width="75" height="75"
                                     alt="dispute images"/>
                            @endforeach

                        </div>

                    </div>
                @endif
                <div class="dispute-row">
                    <div class="title">Detail:</div>
                    <div class="detail">
                        <div class="mb10">{{$claim->detail}}</div>
                        {{-- <div>If you cannot reach an agreement with the seller, you can file a claim for the order.</div>--}}
                    </div>
                </div>
            </div>

            <div class="dispute-msg">
                <h1>Messages</h1>
                <?php  //echo '<tt><pre>'; print_r($messages); die;?>
                <div id="messageBox">
                    @if(isset($messages) && $messages !=  'No more Message')
                        @foreach($messages as $msg)
                            <div class="comnt-wrapper">
                                <a class="comntr-pic" href="{{$msg['sender_url']}}">
                                    <img src="{!! $msg['profile_pic'] !!}"
                                         alt="{{$msg['sender_name']}}">
                                </a>

                                <div class="comnt-detail">
                                    <div class="post-name">
                                        <a href="">{{$msg['sender_name']}}</a>
                                    </div>
                                    <div class="label">
                                        {{config('constants.USER_TYPES.'.$msg['sender_type'])}}

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
                    @else
                        <span id="no-messages">There is no message in this conversation</span>
                    @endif
                </div>

            </div>

            @can('arbitrator', $claim)
            <div class="dispute-msg-write">

                <h1>Send a messages</h1>

                <div class="cht-attachment" id="chat-attachment">
                    <a href="javascript:void(0)">Attach</a>
                </div>
                {!! Form::open(['url' => 'admin/claim/message', 'id' => 'msg-form']) !!}

                <input type="file" accept="" id="postFiles" name="attachment"
                       style="position: fixed; top: -30px;"/>
                {!! Form::hidden('conv_id',$dispute->conv_id) !!}
                <textarea name="body" placeholder="Write your message here..." id="msg-body"></textarea>
                <button type="submit" class="orngBtn cht-send">Send</button>

                {!! Form::close() !!}
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    {!! HTML::script('local/public/assets/admin/script.js') !!}
    {!! HTML::script('local/public/assets/js/pages/dispute.js') !!}
    <style>

        .cssPopup_overlay {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            transition: opacity 500ms;
            visibility: hidden;
            opacity: 0;
        }

        .cssPopup_overlay:target {
            visibility: visible;
            opacity: 1;
            z-index: 5;
        }

        .cssPopup_popup {
            margin: 70px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            width: 620px;
            position: relative;
            transition: all 5s ease-in-out;
        }

        .cssPopup_popup .cssPopup_close {
            position: absolute;
            top: 20px;
            right: 30px;
            transition: all 200ms;
            font-size: 30px;
            font-weight: bold;
            text-decoration: none;
            color: #333;
        }

        .bd-itm .bd-itmr input[type="checkbox"] {
            height: auto;
            width: auto;
        }

        .bd-itmr.label-box {
            float: left;
            width: 100px;
        }
    </style>
    <?php
    if($dispute->claim_request == 'full') {
    $amount = $order_transection->amount;//format_currency($order_transection->amount);
    } else {
    $amount = $dispute->claimed_amount;
    }
    ?>

    <div id="claim-resolved" class="cssPopup_overlay">
        <div class="cssPopup_popup">
            <a class="cssPopup_close" href="#">&times;</a>

            {!! Form::open(['url' => $admin_url.'claim/resolved/', 'enctype'=>"multipart/form-data", 'id' => 'forms']) !!}
            {!! Form::hidden('claim_id', $claim->uuid) !!}
            @if(!empty($shipping_info))
                {!! Form::hidden('seller_id', $shipping_info->seller_id) !!}
            @endif
            <div class="bank-detail-popup">
                <div class="bd-ttle">Resolved</div>
                <div class="bd-itm">
                    <div class="bd-itml">In Favour Of:</div>
                    <div class="bd-itmr label-box">
                        <span class="label-res">Seller</span>
                        <input type="checkbox" value="1" name="seller" class="is_checked">
                    </div>
                    <div class="bd-itmr label-box">
                        <span class="label-res">Buyer</span>
                        <input type="checkbox" value="1" name="buyer" id="buyer" class="is_checked">
                    </div>
                    <span id="err-1" style="color: red"></span>
                </div>
                <div class="bd-itm hide" id="buyer-amount">
                    <div class="bd-itml">Amount:</div>
                    <div class="bd-itmr">
                        <input type="number" placeholder="Amount" name="amount" id="amount">
                        <small id="error_claim" style="color: red">
                        </small>
                    </div>
                </div>
                <div class="bd-itm">
                    <div class="bd-itml">Remarks:</div>
                    <div class="bd-itmr">
                        <textarea name="remarks" placeholder="Remarks..." required id="remarks"></textarea>
                        <span id="err-2" style="color: red"></span>
                    </div>

                </div>
                <div class="bd-itm">
                    <div class="bd-itml">Attachment:</div>
                    <div class="bd-itmr">
                        <input type="file" name="attachment" class="btn-upld">
                    </div>
                </div>
                <div class="bd-btnc">
                    <button type="submit" id="save">Save</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#save').click(function (e) {
                e.preventDefault();

                if ($('input[class=is_checked]:checked').length <= 0) {
                    $("#err-1").html('Select at least one').show();
                    setTimeout(function () {
                        $('#err-1').fadeOut('slow');
                    }, 4000);

                    return false;
                }

                if ($('#buyer').is(':checked')) {
                    var val = $('#amount').val();
                    if (val == '') {
                        $("#error_claim").html('Enter amount').show();
                        setTimeout(function () {
                            $('#error_claim').fadeOut('slow');
                        }, 4000);
                        return false;
                    }
                    var max = "{{$amount}}";
                    if (val > max) {
                        $("#error_claim").html("Amount must be less then or equal to {{$amount}}").show();
                        setTimeout(function () {
                            $('#error_claim').fadeOut('slow');
                        }, 4000);
                        return false;
                        ///$('#forms').submit();
                    }
                    if (val < 0) {
                        $("#error_claim").html("Amount must be greater then 0").show();
                        setTimeout(function () {
                            $('#error_claim').fadeOut('slow');
                        }, 4000);
                        return false;
                        ///$('#forms').submit();
                    }
                }

                var remarks = $('#remarks').val();

                if (remarks == '') {
                    $("#err-2").html('Please enter remarks').show();
                    setTimeout(function () {
                        $('#err-2').fadeOut('slow');
                    }, 4000);
                    return false;
                }
                $('#forms').submit();

            })
        });
    </script>
@endsection
