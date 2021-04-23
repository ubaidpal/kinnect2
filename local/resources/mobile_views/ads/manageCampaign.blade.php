@extends('layouts.masterDynamic')
@section('content')
@include('includes.ads-left-nav')
{{--<script src="{{ asset('/local/public/js/chartist.min.js') }}"></script>--}}
<script src="{{ asset('/local/public/js/chart.min.js') }}"></script>
<script src="{{ asset('/local/public/assets/js/popup.js') }}"></script>

<link rel="stylesheet" href="{{ asset('/local/public/css/chartist.min.css') }}">

<!--Create Album-->
<div class="ad_main_wrapper">
	<div class="ad_inner_wrapper">
    <div class="main_heading">
         <h1>Manage Campaigns</h1>
    </div>
    <div id="table_content" class="cmpn_list">
        <table width="100%" cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <th width="20"><input name="check_all_delete_multiple_ads" id="check_all_delete_multiple_ads"  type="checkbox" class="checkbox"></th>
                <th class="compTitle"><a title="Campaign Name" onclick="" href="javascript:void(0);">Ad Name</a></th>
                <th width="40"><a title="Total Views" onclick="" href="javascript:void(0);">Views</a></th>
                <th width="65"><a title="Remaining Views" onclick="" href="javascript:void(0);">Remaining Views</a></th>
                <th width="40"><a title="Total Clicks" onclick="" href="javascript:void(0);">Clicks</a></th>
                <th width="65"><a title="Remaining Clicks" onclick="" href="javascript:void(0);">Remaining Clicks</a></th>
                <th width="55"><a title="Ad is Enabled or not?" onclick="" href="javascript:void(0);">Status</a></th>
                <th width="40"><a title="Ad is Enabled or not?" onclick="" href="javascript:void(0);">Active</a></th>
                <th width="55"><a title="Payment paid for this?" onclick="" href="javascript:void(0);">Payment Status</a></th>
                <th width="45"><a title="Click Through Rate" onclick="" href="javascript:void(0);">CTR (%)</a></th>
                <th width="142">Options</th>
            </tr>
            </thead>
            <tbody>
            @foreach($campaignAds as $campaignAd)
            <tr>
                <td><input type="checkbox" id="delete_multiple_ads_{{$campaignAd->id}}" name="delete_multiple_ads" value="{{$campaignAd->id}}" class="checkbox"></td>
                <td class="compTitle"><a class="limit" title="{{$campaignAd->cads_title}}" href="{{url('ads/manage/ad/'.$campaignAd->id)}}">{{$campaignAd->cads_title}}</a></td>
                <td><?php echo $adViews = Kinnect2::countAdTotalViews($campaignAd->id); $adViews = ($adViews == 0)? 1:$adViews; ?></td>
                <td><?php echo ($campaignAd->price_model == 'Pay/view')?$campaignAd->limit_view : 'N/A' ?></td>
                <td><?php echo $adClicks = Kinnect2::countAdTotalClicks($campaignAd->id) ;$adClicks = ($adClicks == 0)? 1:$adClicks; ?></td>
                <td><?php echo ($campaignAd->price_model == 'Pay/click')?$campaignAd->limit_click : 'N/A' ?></td>
                <td><?php $isPaidOrPaused = ($campaignAd->payment_status == 0)? 'Payment Due':'Paused by You'; echo $adEnabled = ($campaignAd->status == 0)? $isPaidOrPaused:'Active'; ?></td>
                <td><?php echo $adEnabled = ($campaignAd->enable == 0)? 'No':'Yes'; ?></td>
                <td><?php echo $adEnabled = ($campaignAd->payment_status == 0)? 'Unpaid':'Paid'; ?></td>
                <td><?php  $ctrPercent = ($adClicks / $adViews) * 100;
                    echo round($ctrPercent, 2)?></td>
                <td class="options">
                    <?php
                        if(Kinnect2::isAdPaused($campaignAd->id) == 0)
                        {
                            $pauseOrActive = '<a href="'.url('/ads/activate/ad/').'/'.$campaignAd->id.'">Activate</a>';
                        }
                        else
                        {
                            $pauseOrActive = '<a href="'.url('/ads/pause/ad/').'/'.$campaignAd->id.'">Pause</a>';
                        }

                    ?>
                    <a href="{{url('/ads/edit/ad/')}}/{{$campaignAd->id}}">Edit</a> | <?php echo ($campaignAd->payment_status == 0)? '<a class="payment_ad_btn" id="pay_pal_'.$campaignAd->id.'" href="'.url('/paypal/ad?ad_id='.$campaignAd->id.'&pkg_id='.$campaignAd->package_id).'">Pay Fee</a>':$pauseOrActive; ?>  |
                        <a class="cursor_pointer js-open-modal" data-modal-id="popup-{{$campaignAd->id}}"><span class="close-battle"></span>
                            Delete
                            {!! Form::open(array('method'=> 'get','url'=> "/ads/delete/ad/".$campaignAd->id)) !!}
                            @include('includes.popup',
                                ['submitButtonText' => 'Delete Ad',
                                'cancelButtonText' => 'Cancel',
                                'title' => 'Delete this Ad',
                                'text' => 'Are You Sure You Want To Delete This Ad?',
                                'id' => 'popup-'.$campaignAd->id ])
                            {!! Form::close() !!}
                        </a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <button type="submit" onclick="javascript:void(0);" id="btn-delete-all" class="orngBtn">Delete Selected</button>
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
                {!! Form::open(['id' => 'filter_form', 'id' => url('ads/manage/campaign'.$campaign->id), 'class' => 'global_form_box', "enctype"=>"multipart/form-data"]) !!}

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
                            <option value="view" @if($campaignStatistics['views'] == 1) selected="selected" @endif>Views</option>
                            <option value="click" @if($campaignStatistics['clicks'] == 1) selected="selected" @endif>Clicks</option>
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
                @if($campaignStatistics['views'] == 1)
                    <div style="float:left">
                        <div>Views</div>
                        <div id="views" class="views_box"></div>
                    </div>
                @endif

                @if($campaignStatistics['clicks'] == 1)
                    <div style="float:left; margin-left: 10px">
                        <div>Clicks</div>
                        <div id="clicks" class="clicks_box"></div>                    </div>
                @endif
            </div>
            <div style="clear:both;"></div>
            <div class="ct-chart ct-golden-section">
                    <canvas id="canvas" />
            </div>

            @if(isset($campaignStatistics['chunk']))
                <div id="x-axis_label" style="margin-top: -107px; margin-left: 13px;"> {{$campaignStatistics['chunk']}}</div>
            @endif

           {{-- <div class="cadmc_statistics_nav">
                <a href="javascript:void(0);" onclick="" class="icon_previous" id="">Previous</a>
                <a href="javascript:void(0);" onclick="" class="icon_next" id="">Next</a>
            </div>--}}
</div>
		</div>
</div>
	</div>
</div>
        <?php //echo '<tt><pre>'; print_r($campaignStatistics); ?>
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
        $('#btn-delete-all').click(function (e) {

            var adsIds = [];

            var delAds = document.getElementsByName( 'delete_multiple_ads' );

            for( i = 0; i < delAds.length; i++ ) {
                if( delAds[i].checked ) {
                    adsIds += delAds[i].value+",";
                }
            }

            if(adsIds.length > 0) {

                var check= confirm('Do You Want to Delete selected ads.');

                if (check == true) {
                    var data = new FormData();

                    data.append('adsIds', adsIds);
                    $.ajax({
                        url: '{{url("/ads/delete/ad/ajax")}}',
                        type: 'POST',
                        data: data,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            var campaigns = '{{url('/ads/my-campaigns/')}}';
                            window.location.href = campaigns;
                        },
                        error: function () {
                            var campaigns = '{{url('/ads/my-campaigns/')}}';
                            window.location.href = campaigns;
                        }
                    });
                }

                else {
                    return false;
                }
            }// if no item is selected
            else{
                alert('Select ad(s) to delete it.');
                return false;
            }
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
                    labels: [<?php echo ($campaignStatistics['labels_clicks']=='' AND $campaignStatistics['labels_views']=='')?'0,1,2,3,4,5,6,7,8':''; echo ($campaignStatistics['labels_clicks'] > $campaignStatistics['labels_views'])?$campaignStatistics['labels_clicks']:$campaignStatistics['labels_views']; ?>],
                    datasets : [
                        {
                            label: "Views",
                            fillColor : "rgba(220,220,220,0.2)",
                            strokeColor : "rgba(220,220,220,1)",
                            pointColor : "rgba(220,220,220,1)",
                            pointStrokeColor : "#fff",
                            pointHighlightFill : "#fff",
                            pointHighlightStroke : "rgba(220,220,220,1)",
                            data : [<?php echo ($campaignStatistics['labels_clicks']=='' AND $campaignStatistics['labels_views']=='')?'0,0,0,0,0,0,0,0,0':''; echo $campaignStatistics['values_views']; ?>]

                        },
                        {
                            label: "Clicks",
                            fillColor : "rgba(151,187,205,0.2)",
                            strokeColor : "rgba(151,187,205,1)",
                            pointColor : "rgba(151,187,205,1)",
                            pointStrokeColor : "#fff",
                            pointHighlightFill : "#fff",
                            pointHighlightStroke : "rgba(151,187,205,1)",
                            data : [<?php echo $campaignStatistics['values_clicks']; ?>]
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
