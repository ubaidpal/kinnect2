@extends('layouts.masterDynamic')
@section('content')
@include('includes.ads-left-nav')
{{--<script src="{{ asset('/local/public/js/chartist.min.js') }}"></script>--}}
<script src="{{ asset('/local/public/assets/js/popup.js') }}"></script>
<script src="{{ asset('/local/public/js/chart.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/local/public/css/chartist.min.css') }}">

<!--Create Album-->
<div class="ad_main_wrapper">
	<div class="ad_inner_wrapper">
    <div class="main_heading">
         <h1>Manage Ad</h1>
    </div>
	<div class="ad_name">
    	<h4>{{$ad->cads_title}}</h4>
    </div>

    <div id="table_content" class="cmpn_list">
        <table width="100%" cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <!--<th width="20"><input name="check_all_delete_multiple_ads" id="check_all_delete_multiple_ads"  type="checkbox" class="checkbox"></th>-->
                {{--<th class="compTitle"><a title="{{$ad->cads_title}}" onclick="" href="{{url('/ads/manage/campaign/'.$ad->id)}}">Ad Name</a></th>--}}
                <th class="compTitle"><a title="{{$campaign->name}}" onclick="" href="{{url('/ads/manage/campaign/'.$campaign->id)}}">Campaign Name</a></th>
                <th width="75"><a title="Start Date" href="javascript:void(0);">Start Date</a></th>
                <th width="75"><a title="End Date" href="javascript:void(0);">End Date</a></th>
                <th width="40"><a title="Remaining Click" href="javascript:void(0);">Views</a></th>
                <th width="65"><a title="Remaining Views" href="javascript:void(0);">Remaining Views</a></th>
                <th width="40"><a title="Total Clicks" onclick="" href="javascript:void(0);">Clicks</a></th>
                <th width="65"><a title="Remaining Clicks" onclick="" href="javascript:void(0);">Remaining Clicks</a></th>
                <!--<th width="55"><a title="Ad is Enabled or not?" onclick="" href="javascript:void(0);">Status</a></th>
                <th width="40"><a title="Ad is Enabled or not?" onclick="" href="javascript:void(0);">Active</a></th>
                <th width="55"><a title="Payment paid for this?" onclick="" href="javascript:void(0);">Payment Status</a></th>-->
                <th width="45"><a title="Click Through Rate" onclick="" href="javascript:void(0);">CTR (%)</a></th>
                <th width="142">Options</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <!--<td><input type="checkbox" id="delete_multiple_ads_{{$ad->id}}" name="delete_multiple_ads" value="{{$ad->id}}" class="checkbox"></td>-->
                {{--<td class="compTitle"><a title="{{$ad->cads_title}}" href="{{url('ads/manage/ad/'.$ad->id)}}">{{$ad->cads_title}}</a></td>--}}
                <td class="compTitle"><a title="{{$campaign->name}}" href="{{url('/ads/manage/campaign/'.$campaign->id)}}">{{$campaign->name}}</a></td>
                <td>{{$ad->cads_start_date}}</td>
                <td>{{$ad->cads_end_date}}</td>
                <td><?php echo $adViews = Kinnect2::countAdTotalViews($ad->id); $adViews = ($adViews == 0)? 1:$adViews; ?></td>
                <td><?php echo ($ad->price_model == 'Pay/view')?$ad->limit_view : 'N/A' ?></td>
                <td><?php echo $adClicks = Kinnect2::countAdTotalClicks($ad->id) ;$adClicks = ($adClicks == 0)? 1:$adClicks; ?></td>
                <td><?php echo ($ad->price_model == 'Pay/click')?$ad->limit_click : 'N/A' ?></td>
                <!--<td><? //php $isPaidOrPaused = ($ad->payment_status == 0)? 'Payment Due':'Paused by You'; echo $adEnabled = ($ad->status == 0)? $isPaidOrPaused:'Active'; ?></td>
                <td><? //php echo $adEnabled = ($ad->enable == 0)? 'No':'Yes'; ?></td>
                <td><? //php echo $adEnabled = ($ad->payment_status == 0)? 'Unpaid':'Paid'; ?></td>-->
                <td><?php  $ctrPercent = ($adClicks / $adViews) * 100;
                    echo round($ctrPercent, 2)?></td>
                <td class="options">
                    <?php
                    if(Kinnect2::isAdPaused($ad->id) == 0)
                    {
                        $pauseOrActive = '<a href="'.url('/ads/activate/ad/').'/'.$ad->id.'">Activate It</a>';
                    }
                    else
                    {
                        $pauseOrActive = '<a href="'.url('/ads/pause/ad/').'/'.$ad->id.'">Pause It</a>';
                    }

                    ?>
                    <a href="{{url('/ads/edit/ad/')}}/{{$ad->id}}">Edit</a> | <?php echo ($ad->payment_status == 0)? '<a class="payment_ad_btn" id="pay_pal_'.$ad->id.'" href="'.url('/paypal/ad?ad_id='.$ad->id.'&pkg_id='.$ad->package_id).'">Pay Fee</a>':$pauseOrActive; ?>  |
                        <a class="cursor_pointer js-open-modal" data-modal-id="popup-{{$ad->id}}"><span class="close-battle"></span>
                            Delete
                            {!! Form::open(array('method'=> 'get','url'=> "/ads/delete/ad/".$ad->id)) !!}
                            @include('includes.popup',
                                ['submitButtonText' => 'Delete Ad',
                                'cancelButtonText' => 'Cancel',
                                'title' => 'Delete this Ad',
                                'text' => 'Are You Sure You Want To Delete This Ad?',
                                'id' => 'popup-'.$ad->id ])
                            {!! Form::close() !!}
                        </a>
                </td>
            </tr>
            </tbody>
        </table>
        <!--<button type="submit" id="btn-delete-all" class="orngBtn">Delete Selected</button>-->
        <form action="" method="post" id="delete_selected">
            <input type="hidden" value="" name="ids" id="ids">
        </form>
    </div>

    <div class="cadmc_statistics">
        <p>
            Use the below filter to observe various metrics of your ad campaigns over different time periods. <span>(for last 1 year)</span>
        </p>
        <div class="filter_box">
        <div class="cadmc_statistics_search">
                {!! Form::open(['id' => 'filter_form', 'id' => url('ads/manage/campaign'.$ad->id), 'class' => 'global_form_box', "enctype"=>"multipart/form-data"]) !!}

                <div class="form-elements">
                    {{--<div>
                        <label class="optional" tag="" for="mode">See</label>
                        <select id="mode" name="mode">
                            <option selected="selected" value="normal">All</option>
                            <option value="cumulative">Cumulative</option>
                            <option value="delta">Change in</option>
                        </select>
                    </div>--}}
                    <div>
                        <label class="optional" tag="" for="type">Metric</label>
                        <select id="type" name="type">
                            <option selected="selected" value="all">All</option>
                            <option value="view">Views</option>
                            <option value="click">Clicks</option>
                            {{--<option value="CTR">CTR</option>--}}
                        </select>
                    </div>
                    <div>
                        <label class="optional" tag="" for="period">Duration</label>
                        <select onchange="return filterDropdown($(this))" id="period" name="period">
                            <option value="ww">This Week</option>
                            <option value="MM">This Month</option>
                            <option value="y">This Year</option>
                        </select>
                    </div>
                    <div>
                        <label class="optional" tag="" for="chunk">Time Summary</label>
                        <select id="chunk" name="chunk">
                            <option value="dd">By Day</option>
                        </select>
                    </div>
                    <div id="submit-wrapper">
                        <button class="orngBtn" onclick="" type="submit" id="submit" name="submit">Filter</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="chart">
            <div id="graph_separator">
                @if($adStatistics['views'] == 1)
                    <div style="float:left">
                        <div>Views</div>
                        <div id="views" class="views_box"></div>
                    </div>
                @endif

                @if($adStatistics['clicks'] == 1)
                    <div style="float:left; margin-left: 10px">
                        <div>Clicks</div>
                        <div id="clicks" class="clicks_box"></div>
                    </div>
                @endif
            </div>
            <div style="clear:both;"></div>
            <div class="ct-chart ct-golden-section">
                    <canvas id="canvas" />
            </div>

            @if(isset($adStatistics['chunk']))
               <div id="x-axis_label" style="margin-top: -107px; margin-left: 13px;"> {{$adStatistics['chunk']}}</div>
            @endif

            {{--<div class="cadmc_statistics_nav">
                <a href="javascript:void(0);" onclick="" class="icon_previous" id="">Previous</a>
                <a href="javascript:void(0);" onclick="" class="icon_next" id="">Next</a>
            </div>--}}
</div>
		</div>
        <?php //echo '<tt><pre>'; print_r($adStatistics); ?>
    </div>
    </div>
</div>
@endsection
@section('footer-scripts')
    <script type="text/javascript">

        function filterDropdown(element) {
            var optn1 = document.createElement("OPTION");
            optn1.text = 'By Week';
            optn1.value = 'ww';
            var optn2 = document.createElement("OPTION");
            optn2.text = 'By Month';
            optn2.value = 'MM';

            switch(element.val()) {
                case 'ww':
                    removeOption('ww');
                    removeOption('MM');
                    break;

                case 'MM':
                    addOption(optn1,'ww' );
                    removeOption('MM');
                    break;

                case 'y':
                    addOption(optn1,'ww' );
                    addOption(optn2,'MM' );
                    break;
            }
        }

        function addOption(option,value )
        {
            var addoption = false;
            var chunk = document.getElementById("chunk");
            var OptionLength = chunk.length;
            for (var i = (OptionLength-1); i >= 0; i--) {
                var val = chunk.options[ i ].value;
                if (val == value) {
                    addoption = true;
                    break;
                }
            }
            if(!addoption) {
                chunk.options.add(option);
            }
        }

        function removeOption(value)
        {
            var chunk = document.getElementById("chunk");
            var OptionLength = chunk.length;

            for (var i = (OptionLength-1); i >= 0; i--)
            {
                var val = chunk.options[ i ].value;
                if (val == value) {
                    chunk.options[i] = null;
                    break;
                }
            }
        }

        $(".payment_ad_btn").click(function(e){
            $("#"+e.target.id).html('<img src="{!! asset('local/public/images/loading.gif') !!}" alt="Loading..."  title="Connecting to payment Gateway." />');
        });

        $("#check_all_delete_multiple_ads").click(function(e){
            if(this.checked) {
                // Iterate each checkbox
                $(':checkbox').each(function() {
                    this.checked = true;
                });
            }
            else
            {
                $(':checkbox').each(function() {
                    this.checked = false;
                });
            }
        });

        function confirm_delete(id){
            var check= confirm('Do You Want to Delete this Ad');
            if (check == true)
            {
                var adsDeleteUrl = '{{url('/ads/delete/ad/')}}/'+id;
                window.location.href = adsDeleteUrl;
            }

            else
            {
                return false;
            }
        }
    </script>

            <script>
                var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
                var lineChartData = {
                    labels: [<?php echo ($adStatistics['labels_clicks']=='' AND $adStatistics['labels_views']=='')?'0,1,2,3,4,5,6,7,8':''; echo ($adStatistics['labels_clicks'] > $adStatistics['labels_views'])?$adStatistics['labels_clicks']:$adStatistics['labels_views']; ?>],
                    datasets : [
                        {
                            label: "Views",
                            fillColor : "rgba(220,220,220,0.2)",
                            strokeColor : "rgba(220,220,220,1)",
                            pointColor : "rgba(220,220,220,1)",
                            pointStrokeColor : "#fff",
                            pointHighlightFill : "#fff",
                            pointHighlightStroke : "rgba(220,220,220,1)",
                            data : [<?php echo ($adStatistics['labels_clicks']=='' AND $adStatistics['labels_views']=='')?'0,0,0,0,0,0,0,0,0':''; echo $adStatistics['values_views']; ?>]

                        },
                        {
                            label: "Clicks",
                            fillColor : "rgba(151,187,205,0.2)",
                            strokeColor : "rgba(151,187,205,1)",
                            pointColor : "rgba(151,187,205,1)",
                            pointStrokeColor : "#fff",
                            pointHighlightFill : "#fff",
                            pointHighlightStroke : "rgba(151,187,205,1)",
                            data : [<?php echo $adStatistics['values_clicks']; ?>]
                        }
                    ]

                }

                window.onload = function(){
                    var ctx = document.getElementById("canvas").getContext("2d");
                    window.myLine = new Chart(ctx).Line(lineChartData, {
                        responsive: true
                    });
                }

            </script>

@endsection
