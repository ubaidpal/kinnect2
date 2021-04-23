@extends('Store::layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

@include('Store::includes.store-admin-leftside')

<div class="product-Analytics">
    <div class="post-box">
        <div class="bsmp-title">
            <div class="bsmp-ttle">Withdrawals</div>
            <div class="y-balance">
                <div class="yb-txt">Your Balance:</div>
                <div class="yb-ammount">${{format_currency($available_balance)}}</div>
            </div>
        </div>
        <div class="dispute-wrapper">
            @if(Session::has('message'))
                <span class="error" style="color:#f00000">{{ Session::get('message') }}</span>
            @endif
            @if($pending_withdrawals->isEmpty())
            <p>You currently have no withdrawals pending or queued for processing.</p>
            @endif
            <div class="dispute-row">
                <div class="detail">
                    @if(!empty($bank->id))
                    <a href="#" id="make_withdrawal" class="greyBtn">Make a withdrawal</a>
                    <a href="{{url('store/bankAccount')}}" class="change-bank">Change bank account</a>
                    @else
                        <a href="{{url('store/bankAccount')}}" class="change-bank">Add bank account</a>
                    @endif
                </div>
            </div>
        </div>
        @if(!$pending_withdrawals->isEmpty())
        <div class="dispute-wrapper">
            <div class="dispute-row">
                <div class="title mW">Pending withdrawals:</div>
                <div class="detail bb">&nbsp;</div>
            </div>

             @foreach($pending_withdrawals as $pending)
            <div class="dispute-row">
                <div class="title mW">{{ucfirst($pending->status)}}</div>
                <div class="detail bb">
                    <div class="tn">${{format_currency($pending->amount - ($pending->amount * $pending->fee_percentage)/100)}} <b>to {{$pending->method}} Account</b></div><div>Requested Date: &nbsp; {{$pending->created_at}}</div>
                    @if($pending->status == 'pending')
                    <a class="greyBtn mt10 cancel cancel_request" href="{{url('store/cancelWithdrawalRequest/'.$pending->id)}}">Cancel</a>
                    @endif
                </div>
            </div>
            @endforeach

            <div class="dispute-row">
                <div class="title mW">&nbsp;</div>
                <div class="detail bb">Your withdrawals will be processed within <b>(7 - 14 business days)</b></div>
            </div>
        </div>
        @endif

        <div class="dispute-wrapper" style="@if(count($errors) < 1) display: none; @endif" id="request_container">
            <form method="post" action="{{url('store/sendWithdrawalRequest')}}" id="requestForm" enctype="multipart/form-data">
                <div class="field-item dispute-row">
                    <div class="title mW">Payment Type:</div>
                    <div class="detail bb">
                    <label>Available Balance<input checked type="radio"  name="payment_type" value="full"></label><br><br>
                        <label id="">Other Amount - $
                            <input type="radio" name="payment_type"  value="partial">
                            <input disabled="disabled" type="text" name="amount" class="full_refund" placeholder="Amount">
                        </label>
                        @if(count($errors) > 0)
                        @foreach ($errors->all() as $error)
                            <span class="error">{{ $error }}</span>
                        @endforeach
                        @endif
                    </div>
                </div>

                <div class="dispute-row">
                    <div class="title mW">Kinnect2 Fee:</div>
                    <?php $fee_amount = (($available_balance - $pending_amount)*$fee_percentage)/100; ?>
                    <div class="detail bb">
                        <b id="fee_amount">${{format_currency($fee_amount)}}</b> ({{$fee_percentage}}% of the total withdrawal amount)
                    </div>
                </div>
                <div class="dispute-row">
                    <div class="title mW">&nbsp;</div>
                    <div class="detail bb">You are about to send <b id="myAmount">${{format_currency(($available_balance - $pending_amount)- $fee_amount)}}</b> to you your bank account</div>
                </div>
                <div class="dispute-row">
                    <div class="title mW"></div>
                    <div class="detail">
                        <a href="#" id="submit_request" class="orngBtn">Submit Request</a>
                        <a class="greyBtn" href="#" id="canecl_request">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="dispute-wrapper" style="display: none;" id="balanced_out">
            <span class="error" style="color:#F00000;">You have not sufficient amount to proceed with withdrawal or there are pending withdrawals.</span>
        </div>
    </div>
</div>

<div class="modal-box cart" id="cancel_request" style="display: none;">
    <a href="#" class="js-modal-close close">?</a>
    <div class="modal-body">
        <div class="edit-photo-poup">
            <p class="mt10" style="width: 400px;height: 30px;line-height: normal">Are you sure to cancel the payment request?</p><br>
            <a class="btn fltL blue mr10" id="confirm" href="#">Yes</a>
            <a class="btn fltL grey mr10 js-modal-close" href="#">No</a>
        </div>
    </div>
</div>

<script src="{{url('local/public/assets/js/jquery.validate.min.js')}}"></script>

<script type="text/javascript">
    
    jQuery(document).on('click','.cancel_request',function (e) {
        e.preventDefault();
        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");

        $("body").append(appendthis);
        $(".modal-overlay").fadeTo(500, 0.7);

        $('#cancel_request').fadeIn($(this).data());

        var url = jQuery(this).attr('href');
        jQuery('#confirm').attr('href',url);
    });

    jQuery(document).on('click','#submit_request',function(e){
        e.preventDefault();
        jQuery('#requestForm').submit();
    });
    jQuery(document).on('click','#make_withdrawal',function(e){
        e.preventDefault();
        var balance = {{$available_balance - $pending_amount}};
        if(balance > 10) {
            jQuery('#request_container').show(0, function (e) {
                $('html, body').animate({
                    scrollTop: $("#request_container").offset().top
                }, 2000);
            });
        }else{
            jQuery('#balanced_out').show(0, function (e) {
                $('html, body').animate({
                    scrollTop: $("#balanced_out").offset().top
                }, 2000);
            });
        }
    });
    jQuery(document).on('click','#canecl_request',function(e){
        e.preventDefault();
        jQuery('#request_container').hide('slow');
    });
    jQuery(document).on('click','input[name="payment_type"]',function(e){
        if(jQuery(this).val() == 'partial'){
            jQuery('input[name="amount"]').prop('disabled',false);
        }else {
            jQuery('input[name="amount"]').prop('disabled',true).val("").removeClass('error');
            jQuery('#fee_amount').text('${{$fee_amount}}');
            jQuery('#amount-error').css('display','none');
        }
    });

    jQuery('input[name="amount"]').keyup('keyup',function(e){
        var amount = jQuery(this).val();
        var available = {{$available_balance-$pending_amount}};
        var percentage = {{$fee_percentage}};
        if(amount > 0 && amount <= available){
            fee = (amount * percentage)/100;
            fee = Math.round(fee*100)/100;
            jQuery('#fee_amount').text('$'+fee);
            myAmount = Math.round((amount-fee)*100)/100;
            jQuery("#myAmount").text('$'+myAmount);
        }else {
            jQuery('#fee_amount').text('$0.00');
        }
    });

    jQuery(document).ready(function(e){
        jQuery('#requestForm').validate({
            errorElement : 'span',
            rules : {
                "payment_type" : {required:true},
                "amount" : {required:function(e){
                    if(jQuery('input[name="payment_type"]').val() == 'partial'){
                        return false;
                    }else{
                        return true;
                    }
                },max:{{$available_balance - $pending_amount}},min:10,number:true}
            }
        });
    });
</script>

@endsection
