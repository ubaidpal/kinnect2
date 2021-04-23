@extends('layouts.masterDynamic')
@section('content')
@include('includes.ads-left-nav')
        <!--Create Album-->
<div class="ad_main_wrapper">
    <div class="ad_inner_wrapper">
    <div class="main_heading">
         <h1>My Campaigns > {{$campaign->name}}</h1>
    </div>
    <div class="form-elements">
        {!! Form::open(['url' => "/ads/edit/campaign/".$campaign->id]) !!}
        <div class="form-wrapper change_compaign_title">
            <div class="field-item">
                <label for="title">Campaign Title</label>
                <input type="hidden" name="confirm" value="25">
        
                <input type="text" name="name" maxlength="100" value="{{$campaign->name}}">
                <div class="form-group save-changes mt20">
                
                
               </div>    
               <div class="save_area">
               		<button type="submit" class="orngBtn fltR">Save Title</button>
                	<button type="button" onclick="javascript: location.href = '<?php echo url('ads/my-campaigns')?>';" class="grey_btn fltL">Cancel</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    </div>
</div>
@endsection
