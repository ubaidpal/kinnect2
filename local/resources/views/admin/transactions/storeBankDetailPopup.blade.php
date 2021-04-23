<a class="js-modal-close close" href="#">×</a>
<div class="modal-body">
    <div class="bank-detail-popup">
        <div class="bd-ttle">Bank Deatils</div>
        <div class="bd-itm">
            <div class="bd-itml">Bank Account Holder's Name:</div>
            <div class="bd-itmr">
                <input type="text" placeholder="" value="{{$bank->account_title}}" disabled>
            </div>
        </div>
        <div class="bd-itm">
            <div class="bd-itml">Swift Code:</div>
            <div class="bd-itmr">
                <input type="text" placeholder="" value="{{$bank->swift_code}}" disabled>
            </div>
        </div>
        <div class="bd-itm">
            <div class="bd-itml">Bank Account Number/:</div>
            <div class="bd-itmr">
                <input type="text" placeholder="" value="{{$bank->account_number}}" disabled>
            </div>
        </div>
        <div class="bd-itm">
            <div class="bd-itml">Bank Account IBAN:</div>
            <div class="bd-itmr">
                <input type="text" placeholder="" value="{{$bank->iban_number}}" disabled>
            </div>
        </div>
        <div class="bd-itm">
            <div class="bd-itml">Bank Name in Full:</div>
            <div class="bd-itmr">
                <input type="text" placeholder="" value="{{$bank->bank_name}}" disabled>
            </div>
        </div>
        <div class="bd-itm">
            <div class="bd-itml">Bank Branch City:</div>
            <div class="bd-itmr">
                <input type="text" placeholder="" value="{{$bank->bank_branch_city}}" disabled>
            </div>
        </div>
        <div class="bd-itm">
            <div class="bd-itml">Bank Branch Country:</div>
            <div class="bd-itmr">
                <input type="text" placeholder="" value="{{$bank->bank_branch_country_code}}" disabled>
            </div>
        </div>
    </div>
</div>