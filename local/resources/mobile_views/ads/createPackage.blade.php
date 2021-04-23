@extends('layouts.masterDynamic')
@section('content')
@include('includes.ads-left-nav')
        <!--Create Album-->
<div class="ad_main_wrapper">
    <div class="add_breadcrumb">
   		 <div class="create_p active">Create Package</div>
         <div class="design_ad">Design Your Ad</div>
         <div class="target_sch">Targeting and Scheduling</div>
    </div>
    {!! Form::model($ad = new\App\Ad,['url' => '/ads/create/package']) !!}
    <div class="form-elements">
    	<div class="main_heading">
       		 <h1>Create Package</h1>
             <span>Step 1/3</span>
        </div>
        <div id="price-wrapper" class="form-wrapper">
            <div id="price-label" class="form-label">
                <label for="price" class="required">Price (US Dollar)</label>
                <div class="description">(5 will be minimum amount.)</div>
            </div>
            <div id="price-element" class="form-element">
                <input type="text" name="price" id="price" value="5" onchange="javascript:setUserPkgModelDetail('Pay/click', 1);" autocomplete="off">
                
            </div>
        </div>
        <div id="price_model-wrapper" class="form-wrapper">
            <div id="price_model-label" class="form-label">
                <label for="price_model" class="optional">Pricing Model</label>
            </div>
            <div id="price_model-element" class="form-element">
                <p class="description">Select the pricing model for this package.</p>
                <select name="price_model" id="price_model" onchange="javascript:setUserPkgModelDetail(this.value, 0);">
                    <option value="Pay/click">Pay for Clicks</option>
                    <option value="Pay/view">Pay for Views</option>
                    <!--<option value="Pay/period">Pay for Days</option>-->
                </select>
            </div>
        </div>

        <div id="model_period-wrapper" class="form-wrapper">
            <div id="model_reward-label" class="form-label">
                <label id="model_reward_label" for="model_reward" class="optional">Clicks Limit</label>
            </div>
            <div id="model_period-element" class="form-element">
                <input onchange="javascript:setUserPkgModelDetail('Pay/click', 1);" type="text" name="model_period" id="model_reward" value="12">
                <p class="model_reward_description"></p>
            </div>
        </div>
        <div class="save_area">
            <button name="submit" id="create_usr_pkg_btn" type="submit" class="orngBtn fltR">Next</button>
            <input type="hidden" name="type" value="default" id="type">
            <input type="hidden" name="model_detail" value="" id="model_detail">
        </div>
    </div>
    {!! Form::close() !!}

</div>
@endsection

@section('footer-scripts')
    <script type="text/javascript">
        function setUserPkgModelDetail(pkgModel_detail, fromPrice){
            var pkgModel_detail = jQuery("#price_model").val();
            var price = jQuery("#price").val();
            price = parseInt(price);

            if(price < 5){
                jQuery("#price").val(5);
                price = 5;
            }

            if(pkgModel_detail == "Pay/click" ){
                totalReward = (Math.floor(price / 5 ) ) * 12;
                jQuery("#model_reward_description").html("Minimum 12 Clicks");
                jQuery("#model_reward_label").html("Clicks Limit");
                jQuery("#model_reward").val(totalReward);
            }

            if(pkgModel_detail == "Pay/view"){
                totalReward = (Math.floor(price / 5 ) ) * 500;
                jQuery("#model_reward_description").html("Minimum 500 Views");
                jQuery("#model_reward_label").html("Views Limit");
                jQuery("#model_reward").val(totalReward);
            }

            if(pkgModel_detail == "Pay/period"){
                totalReward = (Math.floor(price / 5 ) ) * 1;
                jQuery("#model_reward_description").html("Minimum 1 Day");
                jQuery("#model_reward_label").html("Period (in days)");
                jQuery("#model_reward").val(totalReward);
            }

        }
    </script>
@endsection
