@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')
        <!--added for ads module-->
<link rel="stylesheet" href="{!! asset('local/public/assets/css/jquery-ui.min.css') !!}">
<script src="{!! asset('local/public/assets/js/jquery-ui.min.js') !!}"></script>

<link href="{{ asset('/local/public/css/jquery.multiselect.css') }}" rel="stylesheet">
<script src="{{ asset('/local/public/js/jquery.multiselect.js') }}"></script>
<!--end of added for ads module-->
<div class="mainCont">

    @include('Store::includes.store-admin-leftside')
   
    <div class="product-Analytics">
        <div class="addProduct">
            @if(isset($product->id))
                <h1>Add Shipping Cost ($)</h1>
                @if(isset($info))
                    <h3>{{$info}}</h3>
                @endif
                <p>Adding shipping cost for product: <strong>{{$product->title}}</strong></p>
                {!! Form::open(['url' => url("store/".Auth::user()->username."/admin/add-product-shipping-cost"), "id" => "add-product-shipping-cost"]) !!}
                    <input type="hidden" value="{{$product->id}}" name="product_id" id="{{$product->id}}">
                    <div class="shipping-method-wrapper">
                        @foreach($allRegions as $region)
                            <?php

                            $costInfo = getRegionCostByProductId($region->id, $product->id);
                            echo $allCountriesOfRegionHtml = allCountriesOfRegionHtml($region->name, $product->id, $region->id);

                            if (isset($costInfo->status)) {
                                $shipping_cost = $costInfo->shipping_cost;
                            } else {
                                $shipping_cost = '';
                            }
                            ?>
                            <div class="shipping-box">
                                <div class="shipping-title">{{ucfirst($region->name)}}:</div>
                                <div class="shipping-cost">
                                    <input @if(empty($shipping_cost)) disabled @endif type="text" class="shippingCostValue" value="{{$shipping_cost}}"
                                           name="cost[{{$region->name}}]"
                                           placeholder="Add shipping cost here..." data-name="{{$region->name}}">

                                    <select name="status[{{$region->name}}]" class="region_cost_status costStatus">
                                        <option value="0" <?php
                                                if(isset($costInfo->status)){
                                                        if($costInfo->status == 0){
                                                            echo 'selected="selected"';
                                                        }
                                                }
                                                ?>>Disable</option>
                                        <option value="1" <?php
                                        if(isset($costInfo->status)){
                                            if($costInfo->status==1){
                                                echo 'selected="selected"';
                                            }
                                        }?>>Enable</option>
                                    </select>
                                </div>
                                <div class="shipping-countries"><a class="btn blue fltL" href="#countryListOfRegion_{{strtolower($region->name)}}">Countries</a>
                                </div>
                            </div>
                        @endforeach
                            <div id="valid" style="display:none;color: #ff0000;width: 418px;margin-left: auto;margin-right: auto;"></div>
                        <div class="shipping-box">
                            <div class="shipping-title">&nbsp;</div>
                            <div class="shipping-cost">
                                <a class="btn blue fltL mt20 mr10" href="javascript:void(0);" id="saveShiipingCostBtn">Save</a>
                                <a class="btn grey fltL mt20" id="resetBtn" href="javascript:void(0);">Clear</a>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <h3>No product found to Add Shipping Cost.</h3>
            @endif
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".shippingCostValue").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                        // Allow: Ctrl+A, Command+A
                    (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) ||
                        // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });
    jQuery(document).on('click','.donePopBtn',function (e) {
       e.preventDefault();

    });
    $("#resetBtn").click(function(){
        $(".shippingCostValue").each(function () {
            $(this).val('');
        });


        $(".costStatus").each(function () {
            if($(this).val() == 0){
                //$(this).val(1);
                //$(this).attr('selected', 'selected');
            }
        });

        $(".jqmsLoaded").each(function () {
            $("input:checkbox").attr('checked', false);
        });
    });

    $("#saveShiipingCostBtn").click(function(){
        var isCostAdded = true;
        var isOneCostAdded = false;
        $(".shippingCostValue").each(function (i, item) {
            if(!$(this).is(':disabled') && $(this).val() == ''){
                isCostAdded = false;
            }
            if($(this).val() != ''){
                isOneCostAdded = true;
            }
          
        });
        if(isCostAdded === false ){
            $('#valid').html('Please provide shipping enabled area(s) with cost.').show();
            return false;
        }
        if(isOneCostAdded == false){
            $('#valid').html('Please provide shipping cost for atleast one region').show();
            return false;
        }


        $("#add-product-shipping-cost").submit();
    });
    jQuery(document).on('change','.region_cost_status',function(e){
        var myVal = jQuery(this).val();
        if(myVal == 1){
            jQuery(this).prev().attr('disabled',false);
        }else{
            jQuery(this).prev().attr('disabled',true);
        }
    });
</script>
@endsection
