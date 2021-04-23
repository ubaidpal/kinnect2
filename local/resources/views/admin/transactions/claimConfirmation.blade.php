<a href="#"  class="js-modal-close close">&times;</a>
<div class="modal-body">
    <div class="edit-photo-poup">
        <h3 style="color: #0080e8;">Make Payment</h3>
        <p class="mt10 mb10">You are going to transfer &dollar;{{format_currency($withdrawal->amount)}} to {{@$user->first_name}}&nbsp;{{@$user->last_name}}</p>
        <input type="button" class="btn fltL blue mr10" id="confirm_claim_payment" value="Confirm"/>
        <input type="button" id="no" class="btn blue js-modal-close fltL close" value="Cancel"/>
        <input type="hidden" id="claim_request_id" value="{{$withdrawal->id}}">
    </div>
</div>