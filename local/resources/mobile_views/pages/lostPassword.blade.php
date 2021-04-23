@extends('layouts.default')
@section('content')
<div class="hd-txt">Lost Password</div>
<p class="p-txt">If you cannot login because you have forgotten your password, please enter your email address in the field below.</p>

<!-- Form Container -->
<div class="form-container">
 <form action="">
  <!-- Form Block -->
  <div class="form-block">
   <label for="">Email *</label>
   <input class="form-item" type="text" name="" value="" placeholder="Email">
  </div>
 </form>
</div>

<!-- btn Container -->
<div class="btn-container">
 <a href="javascript:void(0)" class="btn">Send</a>
 <a href="javascript:void(0)" class="btn btn-grey">Cancel</a>
</div>
@endsection
