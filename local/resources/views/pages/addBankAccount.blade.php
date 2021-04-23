@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')		

<div class="mainCont">

@include('includes.store-admin-leftside')

<div class="product-Analytics">   
	<div class="addProduct">
    	<h1>Add Bank Account</h1>
        
<form action="">
    <div class="field-item">
        <label for="">Title of Account &lowast; :</label>
        <input type="text">
        <div class="note">Your full name that appears on your bank account statement.</div>
    </div>
	
    <div class="field-item">
        <label for="">Permanent Billing Address &lowast; :</label>
        <input type="text">
    </div>
    
    <div class="field-item">
        <label for="">Temporary Billing Address &lowast; :</label>
        <input type="text">
    </div>
    
    <div class="field-item">
        <label for="">City &lowast; :</label>
        <input type="text">
    </div>
    
    <div class="field-item">
        <label for="">State &lowast; :</label>
        <input type="text">
        <div class="note">Up to 4 letters, numbers or spaces e.g. illusions becomes IL</div>
    </div>
    
    <div class="field-item">
        <label for="">Post Code &lowast; :</label>
        <input type="text">
    </div>
    
	<div class="field-item">
        <label for="">Select Country &lowast; :</label>
        <select>
        	<option>Select Country</option>
        </select>
    </div>
    
    <div class="field-item">
        <label for="">Account Number &lowast; :</label>
        <input type="text">
    </div>
    
    <div class="field-item">
        <label for="">IBAN Number &lowast; :</label>
        <input type="text">
        <div class="note">Up to 34 numbers and letters</div>
    </div>
    
    <div class="field-item">
        <label for="">Swift Code &lowast; :</label>
        <input type="text">
    </div>
    
    <div class="field-item">
        <label for="">Bank name in full &lowast; :</label>
        <input type="text">
    </div>
    
    <div class="field-item">
        <label for="">Bank branch country &lowast; :</label>
        <select>
        	<option>Select Country</option>
        </select>
    </div>
    
    <div class="field-item">
        <label for="">Bank branch city &lowast; :</label>
        <input type="text">
    </div>
    
    
	<div class="fltL mt20 mb20">
  <a class="btn blue fltL mr10" href="javascript:();">Save</a>
  <a class="btn grey fltL mr10" href="javascript:();">Cancel</a>
</div>
</form>
    </div>
</div>
</div>
@endsection