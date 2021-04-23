@extends('layouts.masterDynamic')
@section('content')
@include('includes.ads-left-nav')
        <!--Create Album-->
<div class="ad_main_wrapper">
    <div class="ad_inner_wrapper">
    	<div class="main_heading">
             <h1>Genrated Report</h1>
        </div>
        <div class="cadva_detail_table">
            <table width="100%" cellpadding="0" cellspacing="0">
                <thead>
                <tr>
                    <th>Summarize By:</th>
                    <th>
                        {{$summarize_by}}
                    </th>
                    <th>Time Summary:</th>
                    <th>
                        {{$filter_by_time}}
                    </th>
                    <th>Duration:</th>
                    <th>
                        {{$start_date}} <b>To</b> {{$end_date}}
                    </th>
                </tr>
                </thead>
            </table>
        </div>
    
        <div class="cadva_total_reports">
            <div><span> {{$statistics['totalViews']}} </span> Views</div>
            <div><span> {{$statistics['totalClicks']}}  </span> Clicks</div>
    
            <div>
                <span>
                    <?php
                    $statistics['totalClicks'] = ($statistics['totalClicks'] == 0)? 1: $statistics['totalClicks'];
                    $statistics['totalViews'] = ($statistics['totalViews'] == 0)? 1: $statistics['totalViews'];
                        echo round(($statistics['totalClicks'] / $statistics['totalViews']) * 100, 2); ?>%
                </span> CTR
            </div>
        </div>
        <div class="">
            <div id="stat_table" class="cmpn_list">
                <table border="0" width="100%" cellpadding="0" cellspacing="0">
                   <thead>
                    <tr>
                        <th width="75">Date</th>
                        <th style="text-align:left;">Campaign Name</th>
                        @if($summarize_by == 'ads')<th style="text-align:left;">Ad Name</th>@endif
                        <th width="136">Country Views</th>
                        <th width="86">Views</th>
                        <th width="102">Country Clicks</th>
                        <th width="80">Clicks</th>
                        <th width="52">CTR (%)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($statistics['statistics_views'] as $statistic)
                    <tr>
                        <td>{{$statistic->created_at}}</td>
                        <td style="text-align:left;">
                            <?php $currentCampaign = Kinnect2::getCampaign($statistic->campaign_id); ?>
                            <a href="{{url('/ads/manage/campaign/'.$currentCampaign->id)}}" title="{{$currentCampaign->name}}" target="_blank">
                                {{$currentCampaign->name}}
                            </a>
                        </td>
                        @if($summarize_by == 'ads')<td style="text-align:left;">
                            <?php $currentAd = Kinnect2::getAd($statistic->ad_id); ?>
                            <a href="{{url('/ads/manage/ad/'.$currentAd->id)}}" title="{{$currentAd->cads_title}}" target="_blank">
                                {{$currentAd->cads_title}}
                            </a>
                        </td>@endif
                        <td title="Country Views">
                            <?php
                            $currentCampaignStats = Kinnect2::getCampaignStatistics($statistic->created_at, $statistic->campaign_id);
                            $clickedCountries = array_count_values(explode(', ', $currentCampaignStats['view_country_list']));
    
                            if(count($clickedCountries) > 1)
                            {
                                foreach($clickedCountries as $key => $clickedCountry)
                                {
                                    if($key > 0)
                                    {
                                        echo Kinnect2::getCountryName($key).': '.$clickedCountry.' Views,';
                                    }
    
                                }
                            }       else{
                                echo 'N/A';
                            }
                            ?>
                        </td>
                        <td title="Views">
                                Total Views: {{$currentCampaignStats['camp_total_views']}}
                        </td>
                        <td title="Country Clicks">
                            <?php
                            $currentCampaignStats = Kinnect2::getCampaignStatistics($statistic->created_at, $statistic->campaign_id);
                            $clickedCountries = array_count_values(explode(', ', $currentCampaignStats['click_country_list']));
                            if(count($clickedCountries) > 1)
                            {
                                foreach($clickedCountries as $key => $clickedCountry)
                                {
                                    if($key > 0)
                                    {
                                        echo Kinnect2::getCountryName($key).': '.$clickedCountry.'Clicks,';
                                    }
    
                                }
                            }       else{
                                echo 'N/A';
                            }
                            ?>
                        </td>
                        <td title="Clicks">
                           Total Click: {{$currentCampaignStats['camp_total_clicks']}}
                        </td>
                        <td title="CTR (%)">
                            <?php
                            $currentCampaignStats['camp_total_views'] = ($currentCampaignStats['camp_total_views'] == 0)? 1: $currentCampaignStats['camp_total_views'];
                            $currentCampaignStats['camp_total_clicks'] = ($currentCampaignStats['camp_total_clicks'] == 0)? 1: $currentCampaignStats['camp_total_clicks'];
                           echo round(($currentCampaignStats['camp_total_clicks'] / $currentCampaignStats['camp_total_views']) * 100, 2);
                            ?>
                        </td>
                    </tr>
                    @endforeach
    
                    </tbody>
                </table>
            </div>
        </div>

	</div>
</div>
@endsection
