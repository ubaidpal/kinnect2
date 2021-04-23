@extends('layouts.default')
@section('content')
<div class="hd-txt">Reset Password</div>
<p class="p-txt">
 Password should be a minimum of 7 characters, and must consist of at least
</p>
<p class="help-txt">- 1 Special Character</p>
<p class="help-txt">- 1 Alphanumeric</p>
<!-- Form Container -->
<div class="form-container mt15">
 <form action="">
  <!-- Form Block -->
  <div class="form-block">
   <label for="">Email *</label>
   <input class="form-item" type="text" name="" value="" placeholder="Email">
  </div>
  <!-- Form Block -->
  <div class="form-block">
   <label for="">New Password *</label>
   <input class="form-item" type="text" name="" value="" placeholder="New Password">
  </div>
  <!-- Form Block -->
  <div class="form-block">
   <label for="">Re-Type Password *</label>
   <input class="form-item" type="text" name="" value="" placeholder="Re-type Password">
  </div>
 </form>
</div>

<!-- btn Container -->
<div class="btnSingle">
 <a href="javascript:void(0)" class="btn">Save</a>
</div>
@endsection
