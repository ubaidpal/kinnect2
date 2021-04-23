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
            <a class="active" href="#">Withdrawals</a>
            <a class="" href="{{url('admin/claimRequests')}}">Other Amount</a>
        </div>
        <!-- Admin Withdrawal Request - Search -->
        <div class="awr-search">
            <form method="get" action="{{url('admin/withdrawalRequests')}}" id="serachForm">
            <div class="fltR">
                <div class="awr-ttle">
                    <input type="text" placeholder="Type store name" class="search" name="term" value="{{$term}}" >
                </div>
                <div class="awr-select">
                    <select name="status">
                        <option  value="">All statuses</option>
                        <option @if($status == 'pending') selected @endif value="pending">Pending</option>
                        <option @if($status == 'processing') selected @endif value="processing">Processing</option>
                        <option @if($status == 'completed') selected @endif value="completed">Completed</option>
                        <option @if($status == 'canceled') selected @endif value="completed">Cancelled</option>
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
            @if(!$withdrawals->isEmpty())
            @foreach($withdrawals as $withdrawal)
            <div class="awd-item">
                <div class="awdi-itm"><span class="brand">{{$withdrawal->seller->displayname}}</span><span class="awdi-badge">(Seller)</span></div>
                <div class="awdi-itm">{{getTimeByTZ($withdrawal->created_at,'m-d-Y')}}</div>
                <div class="awdi-itm">{{ucfirst($withdrawal->status)}}</div>
                <div class="awdi-itm">${{format_currency($withdrawal->balance,2)}}</div>
                <div class="awdi-itm">${{format_currency($withdrawal->amount - ($withdrawal->amount*$withdrawal->fee_percentage)/100,2)}}</div>
                <div class="awdi-itm">
                    <a class="awdi-btn view_bank_detail" href="{{url('admin/viewPaymentMethodDetails/'.$withdrawal->withdrawal_method_id)}}">View bank detail</a>
                    @if($withdrawal->status == 'pending')
                    <a class="start_process awdi-btn" href="{{url('admin/startPaymentProcess/'.$withdrawal->id)}}">Start Process</a>
                    @elseif($withdrawal->status == 'processing')
                    <a class="change_status awdi-btn" href="{{url('admin/chagePaymentStatus/'.$withdrawal->id)}}">Mark Paid</a>
                    @elseif($withdrawal->status == 'completed')
                     <a class="view_payment_info awdi-btn" href="{{url('admin/viewPaymentInfo/'.$withdrawal->id)}}?from=withdrawal">View Payment Info</a>
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

<link rel="stylesheet" href="{!! asset('local/public/assets/css/jquery-ui.min.css') !!}">
<script type="text/javascript" src="{!! asset('local/public/assets/js/jquery.validate.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('local/public/assets/js/jquery.form.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('local/public/assets/js/jquery-ui.min.js') !!}"></script>
<script type="text/javascript">
    jQuery(document).on('click','.searchFormBtn',function(e){
        e.preventDefault();
        jQuery('#serachForm').submit();
    })
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

    jQuery(document).on('click','#uploadFile',function(e){
        e.preventDefault();
        jQuery('#deposit_slip_attachment').trigger('click');
    });
    jQuery(document).on('change','#deposit_slip_attachment',function (e) {
        var fullPath = document.getElementById('deposit_slip_attachment').value;
        if (fullPath) {
            var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
            var filename = fullPath.substring(startIndex);
            if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                filename = filename.substring(1);
            }
            jQuery('#attachment_name').text(filename);
        }
    });
    jQuery(document).on('click','#submitPaymentFrom',function(e){
        e.preventDefault();
        validateForm();
        if(jQuery('#paymentInfoForm').valid()){
            jQuery('#myLoader').show();
            jQuery('#paymentInfoForm').ajaxSubmit({
                success: function(responseText, statusText, xhr, $form){
                    if(responseText.status == 1){
                        $(".modal-box, .modal-overlay").fadeOut(500);
                        window.location.reload();
                    }else{
                        alert('Error Saveing Data')
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