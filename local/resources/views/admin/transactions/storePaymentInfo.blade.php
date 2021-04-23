<a class="js-modal-close close" href="#">×</a>
<div class="bank-detail-popup">
    @if(!empty($claim))
    <form enctype="multipart/form-data" method="post" id="paymentInfoForm" action="{{url('admin/saveClaimPaymentinfo/'.$withdrawal_id)}}">
    @else
    <form enctype="multipart/form-data" method="post" id="paymentInfoForm" action="{{url('admin/savePaymentinfo/'.$withdrawal_id)}}">
    @endif
        <div class="bd-ttle">Enter Info</div>
        <div class="bd-itm">
            <div class="bd-itml">Deposit to:</div>
            <div class="bd-itmr">
                <input type="text" placeholder="Deposited to" name="deposited_to" value="{{$withdrawal->deposited_to}}">
            </div>
        </div>
        <div class="bd-itm">
            <div class="bd-itml">Date:</div>
            <div class="bd-itmr">
                <input type="text" placeholder="Deposit Date" name="deposit_date" value="{{$withdrawal->deposit_date}}">
            </div>
        </div>
        <div class="bd-itm">
            <div class="bd-itml">Deposit Slip Number:</div>
            <div class="bd-itmr">
                <input type="text" placeholder="Slip Number" name="deposit_slip_number" value="{{$withdrawal->deposit_slip_number}}">
            </div>
        </div>
        <div class="bd-itm">
            <div class="bd-itml">Attachment:</div>
            <div class="bd-itmr">
                <input class="btn-upld" type="file" id="deposit_slip_attachment" name="attachment" style="visibility: hidden;">
                <span id="attachment_name"></span>
                <div class="bd-btn-upload" id="uploadFile">Browse</div>
            </div>
        </div>
        @if(!empty($withdrawal->attachment_path))
        <div class="bd-itm">
            <div class="bd-itml">Previous Attachment</div>
            <div class="bd-itmr">
                <a href="{{$withdrawal->attachment_path}}" target="_blank">
                    <img src="{{$withdrawal->attachment_path}}" width="100">
                </a>
            </div>
        </div>
        @endif
        <div class="bd-btnc">
            <img src="{!! asset('local/public/images/loading.gif') !!}" style="display: none" id="myLoader">
            <button type="button" id="submitPaymentFrom">Confirm</button>
            <button type="button" class="button-grey js-modal-close">Cancel</button>
        </div>
    </form>
</div>