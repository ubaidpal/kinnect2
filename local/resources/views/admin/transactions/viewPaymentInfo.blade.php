<a class="js-modal-close close" href="#">×</a>
<div class="bank-detail-popup">
    <div class="bd-ttle">Payment Info</div>
        <div class="bd-itm">
            <div class="bd-itml">Deposit to:</div>
            <div class="bd-itmr">
                <input type="text" disabled value="{{$withdrawal->deposited_to}}">
            </div>
        </div>
    <div class="bd-itm">
        <div class="bd-itml">Date:</div>
        <div class="bd-itmr">
            <input type="text" disabled value="{{$withdrawal->deposit_date}}">
        </div>
    </div>
    <div class="bd-itm">
        <div class="bd-itml">Deposit Slip Number:</div>
        <div class="bd-itmr">
            <input type="text" disabled value="{{$withdrawal->deposit_slip_number}}">
        </div>
    </div>

    @if(!empty($withdrawal->attachment_path))
        <div class="bd-itm">
            <div class="bd-itml">Attachment</div>
            <div class="bd-itmr">
                <a href="{{$withdrawal->attachment_path}}" target="_blank">
                    <img src="{{$withdrawal->attachment_path}}" width="100">
                </a>
            </div>
        </div>
    @endif

</div>