@extends('Store::layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

@include('Store::includes.store-admin-leftside')

<div class="product-Analytics">   
	<div class="addProduct">
    	<h1>Add Bank Account</h1>
        	<div class="dispute-wrapper">
            	<form action="{{url('store/addBankAccount')}}" method="post" id="myForm">
                
                <div class="field-item dispute-row">
                	<div class="title mW mt5">Title of Account &lowast; :</div>
                    <div class="detail bb">
                        <input type="text" name="account_title" value="{{$bank->account_title}}" class="inp">
                        <span>{{$errors->has('account_title')}}</span>
                        <div class="note">Your full name that appears on your bank account statement.</div>
                    </div>
                </div>
                
                <div class="field-item dispute-row">
                	<div class="title mW mt5">Permanent Billing Address &lowast; :</div>
                    <div class="detail bb">
                        <input type="text" name="permanent_billing_address" value="{{$bank->permanent_billing_address}}" class="inp">
                    </div>
                </div>
                
                <div class="field-item dispute-row">
                	<div class="title mW mt5">Temporary Billing Address &lowast; :</div>
                    <div class="detail bb">
                        <input type="text" name="temp_billing_address" value="{{$bank->temp_billing_address}}" class="inp">
                    </div>
                </div>
                
				<div class="field-item dispute-row">
                	<div class="title mW mt5">City &lowast; :</div>
                    <div class="detail bb">
                        <input type="text" name="city" value="{{$bank->city}}" class="inp">
                    </div>
                </div>
				
                <div class="field-item dispute-row">
                	<div class="title mW mt5">State &lowast; :</div>
                    <div class="detail bb">
                        <input type="text" name="state" value="{{$bank->state}}" class="inp">
                        <span>{{$errors->has('state')}}</span>
                        <div class="note">Up to 4 letters, numbers or spaces e.g. New York becomes NY</div>
                    </div>
                </div>
                
                <div class="field-item dispute-row">
                	<div class="title mW mt5">Post Code &lowast; :</div>
                    <div class="detail bb">
                        <input type="text" name="post_code" value="{{$bank->post_code}}" class="inp">
                    </div>
                </div>
                
                <div class="field-item dispute-row">
                	<div class="title mW mt5">Select Country &lowast; :</div>
                    <div class="detail bb">
                        <select name="country_code"  class="sel">
                            <option>Select Country</option>
                            @foreach($countries as $key => $value)
                            <option value="{{$key}}" @if($key == @$bank->country_code) selected="selected" @endif>{{$value}}</option>
                            @endforeach
                    	</select>
                    </div>
                </div>
                
                <div class="field-item dispute-row">
                	<div class="title mW mt5">Account Number &lowast; :</div>
                    <div class="detail bb">
                        <input type="text" name="account_number" value="{{$bank->account_number}}" class="inp">
                        <span>{{$errors->has('account_number')}}</span>
                    </div>
                </div>
                
                <div class="field-item dispute-row">
                	<div class="title mW mt5">IBAN Number &lowast; :</div>
                    <div class="detail bb">
                        <input type="text" name="iban_number" value="{{$bank->iban_number}}" class="inp">
                        <div class="note">Up to 34 numbers and letters</div>
                    </div>
                </div>
                
                <div class="field-item dispute-row">
                	<div class="title mW mt5">Swift Code &lowast; :</div>
                    <div class="detail bb">
                        <input type="text" name="swift_code" value="{{$bank->swift_code}}" class="inp">
                    </div>
                </div>
                
                <div class="field-item dispute-row">
                	<div class="title mW mt5">Bank name in full &lowast; :</div>
                    <div class="detail bb">
                        <input type="text" name="bank_name" value="{{$bank->bank_name}}" class="inp">
                    </div>
                </div>
                
                <div class="field-item dispute-row">
                	<div class="title mW mt5">Bank branch country &lowast; :</div>
                    <div class="detail bb">
                        <select name="bank_branch_country_code" class="sel">
                            <option>Select Country</option>
                            @foreach($countries as $key => $value)
                            <option value="{{$key}}" @if($key == @$bank->bank_branch_country_code) selected="selected" @endif>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
				
                <div class="field-item dispute-row">
                	<div class="title mW mt5">Bank branch city &lowast; :</div>
                    <div class="detail bb">
                        <input type="text" name="bank_branch_city" value="{{$bank->bank_branch_city}}" class="inp">
                    </div>
                </div>
                
                <div class="field-item dispute-row">
                	<div class="title mW mt5">&nbsp;</div>
                    <div class="detail">
                        <a class="btn blue fltL mr10" id="save_btn" href="#">Save</a>
                    	<a class="btn grey fltL mr10" href="{{url('store/withdrawals')}}">Cancel</a>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<script src="{{url('local/public/assets/js/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
    jQuery(document).ready(function(e){
        jQuery('#myForm').validate({
            errorElement : 'span',
            rules : {
                'account_title' : {required:true},
                'state'         : {maxlength:4},
                'account_number' : {required:true,number:true},
                'swift_code' : {required:true},
                'bank_name' : {required:true},
                'iban_number': {maxlength:34,alphanumeric:true}
            },
        });
    });
   
    jQuery.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
    },'IBAN can contain alphanumeric value only');

    jQuery(document).on('click','#save_btn',function(e){
        e.preventDefault();
        jQuery('#myForm').submit();
    });

</script>
@endsection
