@extends('layouts.masterDynamic')
@section('content')
@include('includes.ads-left-nav')
<!--Create Album-->
<div class="community-ad">
<div class="content-gray-title mb10">
    <h4>My Campaigns</h4>
    <a class="btn fltR" href="javascript:();">Create an Ad</a>
</div>

<div id="table_content" class="cmpn_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th width="20"><input type="checkbox" class="checkbox"></th>
                <th class="compTitle"><a title="Campaign Name" onclick="" href="javascript:void(0);">Campaign Name</a></th>
                <th width="70"><a title="Number of Ads which belong to this Campaign" onclick="" href="javascript:void(0);">Ads</a></th>
                <th width="70"><a title="Total Views" onclick="" href="javascript:void(0);">Views</a></th>
                <th width="70"><a title="Total Clicks" onclick="" href="javascript:void(0);">Clicks</a></th>
                <th width="70"><a title="Click Through Rate" onclick="" href="javascript:void(0);">CTR (%)</a></th>
                <th width="155">Options</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox" value="25" class="checkbox"></td>
                <td class="compTitle"><a title="Dragon Saga" href="#">Dragon Saga</a></td>
                <td>1</td>
                <td>9,874</td>
                <td>28</td>
                <td>0.2836</td>
                <td class="options">
                    <a href="#">Manage</a> | <a href="/ads/editcamp/25">Edit</a> | <a href="/ads/deletecamp/25">Delete</a>						
                </td>
            </tr>
            <tr>
                <td><input type="checkbox" value="25" class="checkbox"></td>
                <td class="compTitle"><a title="Dragon Saga" href="#">Dragon Saga</a></td>
                <td>1</td>
                <td>9,874</td>
                <td>28</td>
                <td>0.2836</td>
                <td class="options">
                    <a href="#">Manage</a> | <a href="/ads/editcamp/25">Edit</a> | <a href="/ads/deletecamp/25">Delete</a>						
                </td>
            </tr>
            <tr>
                <td><input type="checkbox" value="25" class="checkbox"></td>
                <td class="compTitle"><a title="Dragon Saga" href="#">Dragon Saga</a></td>
                <td>1</td>
                <td>9,874</td>
                <td>28</td>
                <td>0.2836</td>
                <td class="options">
                    <a href="#">Manage</a> | <a href="/ads/editcamp/25">Edit</a> | <a href="/ads/deletecamp/25">Delete</a>						
                </td>
            </tr>
            <tr>
                <td><input type="checkbox" value="25" class="checkbox"></td>
                <td class="compTitle"><a title="Dragon Saga" href="#">Dragon Saga</a></td>
                <td>1</td>
                <td>9,874</td>
                <td>28</td>
                <td>0.2836</td>
                <td class="options">
                    <a href="#">Manage</a> | <a href="/ads/editcamp/25">Edit</a> | <a href="/ads/deletecamp/25">Delete</a>						
                </td>
            </tr>
        </tbody>
    </table>
    <button type="submit" onclick="javascript:void(0);" class="orngBtn">Delete Selected</button>
    <form action="" method="post" id="delete_selected">
    	<input type="hidden" value="" name="ids" id="ids">
    </form>
</div>

    <div class="cadmc_statistics">
            <p>
            Use the below filter to observe various metrics of your ad campaigns over different time periods. <span>(for last 1 year)</span>
            </p>
        <div class="cadmc_statistics_search">
            <form method="post" action="" class="global_form_box" enctype="application/x-www-form-urlencoded" id="filter_form">
                <div class="form-elements">
                    <div>
                        <label class="optional" tag="" for="mode">See</label>
                        <select id="mode" name="mode">
                            <option selected="selected" value="normal">All</option>
                            <option value="cumulative">Cumulative</option>
                            <option value="delta">Change in</option>
                        </select>
                    </div>
                    <div>
                        <label class="optional" tag="" for="type">Metric</label>
                        <select id="type" name="type">
                            <option selected="selected" value="all">All</option>
                            <option value="view">Views</option>
                            <option value="click">Clicks</option>
                            <option value="CTR">CTR</option>
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
                    <div class="form-wrapper" id="submit-wrapper">
                        <button class="orngBtn" onclick="" type="submit" id="submit" name="submit">Filter</button>
                    </div>
                </div>
            </form>		    
        </div>
        <div class="cadmc_statistics_nav">
            <a href="javascript:void(0);" onclick="" class="icon_previous" id="">Previous</a>
            <a href="javascript:void(0);" onclick="" class="icon_next" id="">Next</a>
        </div>
    </div>
</div>
@endsection