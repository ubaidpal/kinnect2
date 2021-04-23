@extends('layouts.masterDynamic')
@section('content')
    @include('includes.ads-left-nav')
	<!--Create Album-->
	<div class="community-ad">
 	<div class="content-gray-title mb10">
        <h4>Advertising Performance Reports</h4>
    </div>
	<div class="form-container">
        <p class="form-description">You can view performance reports of your campaigns and ads over multiple durations and time intervals. The generated reports include statistics like views, clicks and click through rate (CTR). You can also export and save the reports.</p>
        <div class="field-item">
             <label for="">Summarize By</label>
             <select id="" name="">
                  <option value="">Ads</option>
                  <option value="">Campaigns</option>
             </select>
        </div>
         <div class="field-item">
             <label for="">Filter By</label>
             <select id="" name="">
                  <option value="">No Filter</option>
                  <option value="">Campaigns</option>
             </select>
        </div>	
        <div class="field-item">
             <label for="">Time Summary</label>
             <select id="" name="">
                  <option value="">Daily</option>
                  <option value="">Montyly</option>
             </select>
        </div>
        <div class="field-item calendar m0">
           <label for="">Select Date</label>
           <div class="select-date fltL mr20">
                <b>From</b>
                <span>11/14/2015</span>
                <a href="javascript:();" class="btn-calendar"></a>
           </div>
           <div class="select-date fltL">
                <b>To</b>
                <span>11/14/2015</span>
                <a href="javascript:();" class="btn-calendar"></a>
           </div>
        </div>
        <div class="field-item">
             <label for="">Format</label>
             <select id="" name="">
                  <option value="">Webpage (.html)</option>
                  <option value="">Excel (.xls)</option>
             </select>
        </div>	
        <div class="save-changes">
           <a class="btn" href="javascript:();">Generate Report</a>
        </div>
	</div>
@endsection