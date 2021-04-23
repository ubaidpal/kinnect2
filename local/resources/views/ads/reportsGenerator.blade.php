@extends('layouts.masterDynamic')
@section('content')
@include('includes.ads-left-nav')

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
    $(function() {
        $( "#report_start_date" ).datepicker({
            dateFormat: "yy-mm-dd"
        });

        $( "#report_end_date" ).datepicker({
            dateFormat: "yy-mm-dd"
        });
    });
</script>
<div class="ad_main_wrapper">
    <div class="ad_inner_wrapper">
        <div class="main_heading">
             <h1>Advertising Performance Reports</h1>
        </div>
        <p class="form-description">You can view performance reports of your campaigns and ads over multiple durations and time intervals. The generated reports include statistics like views, clicks and click through rate (CTR). You can also export and save the reports.</p>
        <div class="form-elements ad_reports">
            {!! Form::open(['url' => "ads/reports/generator"]) !!}
            
            <div class="field-item">
                <label for="">Summarize By</label>
                <select id="summarize_by" name="summarize_by">
                    <option value="ads">Ads</option>
                    <option value="campaigns">Campaigns</option>
                </select>
            </div>
            <div class="field-item">
                <label for="filter_by">Filter By</label>
                <select name="filter" id="filter">
                    <option value="no" selected="selected">No Filter</option>
                    <option id="filter_campaign" value="campaign">Campaigns</option>
                    <option id="filter_ads" value="ads">Ads</option>
                </select>
                @if($errors->first('filter_by'))
                    <span>{{ $errors->first('filter_by') }}</span>
                @endif
            </div>
            <div id="filter_by_values">
                <ul class="filter_by_campaigns_wrap" style="display: none;">
                    @foreach($campaigns as $campaign)
                        <li>
                            <input type="checkbox" name="campaign_list[]" id="campaign_list-{{$campaign->id}}" value="{{$campaign->id}}">
                            <label for="campaign_list-{{$campaign->id}}">{{$campaign->name}}</label>
                        </li>
                    @endforeach
                </ul>
    
                <ul class="filter_by_ads_wrap" style="display: none;">
                    @foreach($ads as $ad)
                        <li>
                            <input type="checkbox" name="ad_list[]" id="ad_list-{{$ad->id}}" value="{{$ad->id}}">
                            <label for="ad_list-{{$ad->id}}">{{$ad->cads_title}}</label>
                        </li>
                    @endforeach
                </ul>
    
            </div>
            <div class="field-item">
                <label for="filter_by_time">Time Summary</label>
                <select id="filter_by_time" name="filter_by_time">
                    <option value="daily">Daily</option>
                    <option value="monthly">Montly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            <div class="field-item calendar">
                <label for="start_date">Select Date</label>
                <div class="select-date fltL mr20">
                    <b>From</b>
                    <div class="select-date">
                        <input type="text" name="start_date" placeholder="YYYY-MM-DD" value="{{$start_date}}" title="Select date to start." id="report_start_date">
                    </div>
                </div>
                <div class="select-date fltL">
                    <b>To</b>
                    <div class="select-date">
                        <input type="text" name="end_date" placeholder="YYYY-MM-DD" value="{{$end_date}}" title="Select date to end." id="report_end_date">
                    </div>
                </div>
            </div>
            <div class="field-item">
                <label for="report_destination_type">Format</label>
                <select id="report_destination_type" name="report_destination_type">
                    <option value="html">Webpage (.html)</option>
                    <option value="xls">Excel (.xls)</option>
                </select>
            </div>
            <div class="save_area">
            	<button type="submit" id="submit" class="orngBtn fltR">Generate Report</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
    <script>

        $('#filter').change(function(evt){
            $('.filter_by_ads_wrap').hide();
            $('.filter_by_campaigns_wrap').hide();

            if(this.value == 'campaign'){
                $('.filter_by_campaigns_wrap').show();
            }

            if(this.value == 'ads'){
                $('.filter_by_ads_wrap').show();
            }

        });


        $('#summarize_by').change(function(evt){
            $('#filter_ads').show();

            if(this.value != 'ads'){
                $('#filter_ads').hide();
            }

        });

        $('#filter_by_time').change(function(evt){
            if(this.value == 'yearly'){
                $('.calendar').hide();
            }
            else{
                $('.calendar').show();
            }
        });
    </script>
@endsection
