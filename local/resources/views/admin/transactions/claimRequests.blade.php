@extends('admin.layout.store-admin')
@section('content')
    <!-- Post Div-->
@include('admin.layout.arbitrator-leftnav')
<div class="ad_main_wrapper">
    <div class="task_inner_wrapper">
        <div class="main_heading">
            <h1>Payments</h1>
        </div>
        <div class="task-tabs">
            <a  href="{{url('admin/withdrawalRequests')}}">Withdrawals</a>
            <a class="active" class="" href="{{url('admin/claimRequests')}}">Other Amount</a>
        </div>
        <!-- Admin Withdrawal Request - Search -->
        <div class="awr-search">
            <form method="get" action="{{url('admin/claimRequests')}}" id="serachForm">
            <div class="fltR">
                <div class="awr-ttle">
                    <input type="text" placeholder="Type store name" class="search" name="term" value="{{@$term}}" >
                </div>
                <div class="awr-select">
                    <select name="status">
                        <option value="">All statuses</option>
                        <option @if($status == 'pending') selected @endif value="pending">Pending</option>
                        <option @if($status == 'processing') selected @endif value="processing">processing</option>
                        <option @if($status == 'completed') selected @endif value="completed">Completed</option>
                    </select>
                </div>
                <div class="awr-btn">
                    <button class="searchFormBtn" type="button">Search</button>
                </div>
            </div>
            </form>
        </div>
        @if(Session::has('message'))
        <div>{{Session::get('message')}}</div>
        @endif
        @if(Session::has('error'))
        <div style="color: #F00000;" class="mb10">{{Session::get('error')}}</div>
        @endif
        @if(Session::has('success'))
        <div style="color: #008000;" class="mb10">{{Session::get('success')}}</div>
        @endif
        <!-- Admin Withdrawal Request -->
        <div class="awd-6con">
            <!-- Admin Withdrawal Request - Item Bold -->
            <div class="awd-item awd-itemb">
                <!--<div class="awdi-itm">Seller</div>-->
                <div class="awdi-itm">Consumer</div>
                <div class="awdi-itm">Date</div>
                <div class="awdi-itm">Status</div>
                <div class="awdi-itm">&nbsp;</div>
                <div class="awdi-itm">Amount</div>
                <div class="awdi-itm">Action</div>
            </div>
            @if(!$withdrawals->isEmpty())
            @foreach($withdrawals as $withdrawal)
            <div class="awd-item">
                <!--<div class="awdi-itm"><span class="brand">{{@$withdrawal->seller->displayname}}</span><span class="awdi-badge">(Seller)</span></div>-->
                <div class="awdi-itm">{{@$withdrawal->user->first_name}}&nbsp;{{@$withdrawal->user->last_name}}</div>
                <div class="awdi-itm">{{getTimeByTZ($withdrawal->created_at,'m-d-Y')}}</div>
                <div class="awdi-itm">{{ucfirst($withdrawal->status)}}</div>
                <div class="awdi-itm">&nbsp;</div>
                <div class="awdi-itm">${{format_currency($withdrawal->amount - ($withdrawal->amount*$withdrawal->fee_percentage)/100,2)}}</div>
                <div class="awdi-itm">
                    <!--<a class="awdi-btn view_bank_detail" href="{{url('admin/viewBankDetails/'.@$withdrawal->store_claim->bank_account_id)}}">View bank detail</a>-->
                    @if($withdrawal->status == 'pending')
                    <a class="start_process awdi-btn" href="#" id="{{$withdrawal->id}}">Pay</a>
                    @elseif($withdrawal->status == 'processing')
                    <a class="change_status awdi-btn" href="{{url('admin/chageClaimPaymentStatus/'.@$withdrawal->id)}}">Mark Paid</a>
                    @elseif($withdrawal->status == 'completed')
                            Completed
                        <!--<a class="view_payment_info awdi-btn" href="{{url('admin/viewPaymentInfo/'.$withdrawal->id)}}?from=claim">View Payment Info</a>-->
                    @endif

                </div>
            </div>
            @endforeach
                <div class="pagination">{!! $withdrawals->render() !!}</div>
            @else
                <div class="awd-item">
                    <div class="awdi-itm">No record found</div>
                </div>
            @endif
        </div>
    </div>
</div>

<div style="text-align: center;display: none;z-index: 999999999;" id="page_loader">
    <img src="{!! asset('local/public/images/loading.gif') !!}">
</div>
<div class='modal-overlay js-modal-close'></div>
<div id="popup_container" class="modal-box" style="display: none;"></div>

<div class="modal-box delete" id="make_claim_payment">

</div>

<link rel="stylesheet" href="{!! asset('local/public/assets/css/jquery-ui.min.css') !!}">
<script type="text/javascript" src="{!! asset('local/public/assets/js/jquery.validate.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('local/public/assets/js/jquery.form.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('local/public/assets/js/jquery-ui.min.js') !!}"></script>
<script type="text/javascript">
    jQuery(document).on('click','.searchFormBtn',function(e){
        e.preventDefault();
        jQuery('#serachForm').submit();
    });
    jQuery(document).on('click','.start_process',function (e) {
        e.preventDefault();
        var request_id = jQuery(this).attr('id');
        var appendthis = ("<div class='modal-overlay'></div>");
        jQuery('body').append(appendthis);
        $(".modal-overlay").fadeTo(500, 0.7);
        jQuery('#page_loader').show();
        var baseUrl = "{{url('admin/getClaimInfo/')}}";
        jQuery.ajax({
            url : baseUrl + '/' + request_id,
        }).done(function (response) {
            e.preventDefault();
            jQuery('#popup_container').html(response).css('display','block');
            jQuery('#page_loader').hide();
        });
    });
    jQuery(document).on('click','#confirm_claim_payment',function (e) {
       e.preventDefault();
       var request_id = jQuery('#claim_request_id').val();
       var baseUrl = "{{url('admin/makeClaimPayment/')}}";
       window.location = baseUrl + '/' + request_id;
    });
    jQuery(document).on('click','.view_bank_detail',function(e){
        e.preventDefault();
        var appendthis = ("<div class='modal-overlay'></div>");
        jQuery('body').append(appendthis);
        $(".modal-overlay").fadeTo(500, 0.7);
        jQuery('#page_loader').show();
        jQuery.ajax({
            url : jQuery(this).attr('href'),
        }).done(function(data){
            jQuery('#popup_container').html(data).css('display','block');
            jQuery('#page_loader').hide();
        });
    });

    jQuery(document).on('click','.change_status',function(e){
        e.preventDefault();
        var appendthis = ("<div class='modal-overlay'></div>");
        jQuery('body').append(appendthis);
        $(".modal-overlay").fadeTo(500, 0.7);
        jQuery('#page_loader').show();
        jQuery.ajax({
            url : jQuery(this).attr('href'),
        }).done(function(data){
            jQuery('#popup_container').html(data).css('display','block');
            jQuery('input[name="deposit_date"]').datepicker();
            jQuery('#page_loader').hide();
        });
    });

    jQuery(document).on('click','.js-modal-close', function (e) {
        e.preventDefault();
            $(".modal-box, .modal-overlay").fadeOut(500, function () {
        });
    });

    jQuery(document).on('click','.view_payment_info',function(e){
        e.preventDefault();
        var appendthis = ("<div class='modal-overlay'></div>");
        jQuery('body').append(appendthis);
        $(".modal-overlay").fadeTo(500, 0.7);
        jQuery('#page_loader').show();
        jQuery.ajax({
            url : jQuery(this).attr('href'),
        }).done(function(data){
            jQuery('#popup_container').html(data).css('display','block');
            jQuery('#page_loader').hide();
        });
    });

    jQuery(document).on('click','#uploadFile',function(e){
        e.preventDefault();
        jQuery('#deposit_slip_attachment').trigger('click');
    });
    jQuery(document).on('click','#submitPaymentFrom',function(e){
        e.preventDefault();
        validateForm();
        jQuery(this).hide();
        if(jQuery('#paymentInfoForm').valid()){
            jQuery('#myLoader').show();
            jQuery('#paymentInfoForm').ajaxSubmit({
                success: function(responseText, statusText, xhr, $form){
                    if(responseText.status == 1){
                        window.location.reload();
                        $(".modal-box, .modal-overlay").fadeOut(500);
                    }
                }
            });
        }
    });

    validateForm = function(){
        jQuery('#paymentInfoForm').validate({
            errorElement : 'span',
            rules : {
                'deposited_to' : {required:true},
                'deposit_date' : {required:true},
                'slip_number' : {required:true}
            }
        });
    }
</script>
@endsection