@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

@include('Store::admin.product_analytics.canvas.canvas')
<div class="mainCont">

    @include('Store::includes.store-admin-leftside')

    <div class="product-Analytics">
        <div class="addProduct">
            <h1>{{$product->title}} Analytics <small>In stock Products:  {{$product->quantity}} | Sold Products: {{$product->sold}}</small></h1>
            <div class="selectdiv" id="row_1">
                <div class="chart" >
                    <div id="chartContainerView"
                         style="float:left; height: 300px; width: 100%;"></div>
                    <a class="btn btn-add" href="#number-views">View Detail</a>
                </div>

            </div>
           <!-- <div class="selectdiv" id="row_1">

                <div id="totalNumberOfSellings" style="float:right; height: 300px; width: 49%;">
                    <div id="totalNumberOfSelling" style="float:left; height: 300px; width: 50%;"></div>
                    <div id="totalNumberOfSellingsInfo">
                        <div id="totalNumberOfSellingsTotalProducts">
                            In stock Products
                            {{$product->quantity}}
                        </div>

                        <div id="totalNumberOfSellingsSoldProducts">
                            Sold Products
                            {{$product->sold}}
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="selectdiv" id="row_2">
                <div class="chart" style="width: 33.5%; float: left">
                    <div id="chartContainerAge" style="float: left; height: 300px; width: 100%;"></div>
                    <a class="btn btn-add" href="#age-views" style="float: left; width: 100%;">View Detail</a>
                </div>
                <div class="chart" style="width: 30%; float: left">
                    <div id="chartContainerGender" style="float:left; height: 300px; width: 100%;"></div>
                    <a class="btn btn-add" href="#gender-views" style="float: left; width: 100%;">View Detail</a>
                </div>
                <div class="chart" style="width: 33.5%; float: left">
                    <div id="chartContainerRegion" style="float:left; height: 300px; width: 100%;"></div>
                    <a class="btn btn-add" href="#country-views" style="float: left; width: 100%;">View Detail</a>
                </div>
            </div>
            {{--<div class="field-item product-images" id="row_3">
                <div id="lineChart" style="height: 300px; width: 100%;"></div>
            </div>--}}
            <div class="chart" style="width: 100%; float: left">
                <div class="field-item product-images" id="row_4">
                    <div id="chartContainerView-1" style="height: 300px; width: 100%;"></div>
                </div>
                <a class="btn btn-add" href="#peak-views" style="float: left; width: 100%;">View Detail</a>
            </div>

        </div>
    </div>
</div>
{{--<script src="http://canvasjs.com/assets/script/canvasjs.min.js"></script>--}}


@endsection

@section('includes')
    @include('Store::admin.product_analytics.chart-detail.number-views')
    @include('Store::admin.product_analytics.chart-detail.number-sales')
    @include('Store::admin.product_analytics.chart-detail.age-view')
    @include('Store::admin.product_analytics.chart-detail.peak-traffic-view')
    @include('Store::admin.product_analytics.chart-detail.gender')
    @include('Store::admin.product_analytics.chart-detail.country')

    {!! HTML::script('local/packages/kinnect2Store/assets/js/charts.js') !!}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script type="text/javascript">

        $(document).ready(function(){

            /*var myAllCountries = [];
             $.each(document.getElementsByClassName("abc"), function (i, feature) {
             myAllCountries.push(feature.value);
             });*/

            var chart = new CanvasJS.Chart("chartContainerRegion", {
                title : {
                    text : "Region"
                },axisX:{
                    title:'Region'
                },axisY:{
                        title:'Views'
                }, animationEnabled : true, data : [{
                    // Change type to "doughnut", "line", "splineArea", etc.
                    type : "column", dataPoints : [<?php echo $myAllCountries; ?>
                        ]
                }]
            });
            chart.render();
        });

        $(document).ready(function(){
            var chart = new CanvasJS.Chart("chartContainerAge", {
                title : {
                    text : "Age"
                },axisX:{
                    title:'Age'
                },axisY:{
                    title:'Views'
                }, animationEnabled : true, data : [{
                    // Change type to "doughnut", "line", "splineArea", etc.
                    type : "column", dataPoints : [{
                        color : '#c5d6dd', label : "10-25", y : <?php echo $product_statics_by_age["firstCountView"]?>



                    }, {
                        color : '#78acc1', label : "25-35", y : <?php echo $product_statics_by_age["secondCountView"]?>



                    }, {
                        color : '#c5d6dd', label : "35-45", y : <?php echo $product_statics_by_age["thirdCountView"]?>



                    }, {
                        color : '#78acc1', label : "45-55", y : <?php echo $product_statics_by_age["fourthCountView"]?>



                    }, {
                        color : '#c5d6dd', label : "> 55", y : <?php echo $product_statics_by_age["fifthCountView"]?>

                    },]
                }]
            });
            chart.render();
        });

        $(document).ready(function(){
            var chart = new CanvasJS.Chart("chartContainerView-1", {
                animationEnabled : true, animationDuration : 2000,
                title : {
                    text : "Traffic and Peak hours",
                    font: "12px Arial,Helvetica,sans-serif"
                }, axisX : {
                    title : "Hours of the day ({{\Carbon\Carbon::now()->format('d-m-Y')}})", // gridThickness: 3,
                    interval : 1, intervalType : "hour", valueFormatString : "hh", //labelAngle: -30
                }, axisY : {
                    title : "Views",
                    interval : 1, intervalType : "number",
                }, data : [{
                    type : "column", color : "#78b0c1", indexLabelFontColor : "green",
                    dataPoints :[ <?php echo  $preparedStatViewsHours; ?>]
                }]
            });
            chart.render();
        });

        $(document).ready(function(){
            var chart = new CanvasJS.Chart("chartContainerGender", {
                title : {
                    text : "Gender", /*fontFamily: "Impact",
                     fontWeight: "normal"*/
                },axisX:{
                    title:'Gender'
                },axisY:{
                    title:'Views'
                }, animationEnabled : true,

                legend : {
                    verticalAlign : "bottom", horizontalAlign : "center"
                }, data : [{

                    type : "doughnut", showInLegend : true, dataPoints : [{
                        y : <?php echo $product_statics_by_age["maleCountView"]?>,
                        color : '#c5d6dd',
                        legendText : "Male <?php echo round($product_statics_by_age["maleCountViewPercent"]);?>%",
                        indexLabel : "Male <?php echo round($product_statics_by_age["maleCountViewPercent"]);?>%"
                    }, {
                        y : <?php echo $product_statics_by_age["femaleCountView"]?>,
                        color : '#dbbcce',
                        legendText : "Female <?php echo round($product_statics_by_age["femaleCountViewPercent"]);?>%",
                        indexLabel : "Female <?php echo round($product_statics_by_age["femaleCountViewPercent"]);?>%"
                    },

                    ]
                }]
            });

            chart.render();
        });

        /*$(document).ready(function () {
         var chart = new CanvasJS.Chart("lineChart",
         {
         title: {
         text: "Total Number of Like Dislike Favorites and Comments"
         },
         animationEnabled: true,
         /!*axisY:{
         title: "",
         includeZero: false
         },*!/
         axisX: {
         title: "Months",
         interval: 1
         },
         toolTip: {
         shared: true,
         content: function (e) {
         var body = new String;
         var head;
         for (var i = 0; i < e.entries.length; i++) {
         var str = "<span style= 'color:" + e.entries[i].dataSeries.color + "'> " + e.entries[i].dataSeries.name + "</span>: <strong>" + e.entries[i].dataPoint.y + "</strong>'' <br/>";
         body = body.concat(str);
         }
         head = "<span style = 'color:DodgerBlue; '><strong>" + (e.entries[0].dataPoint.label) + "</strong></span><br/>";

         return (head.concat(body));
         }
         },
         legend: {
         horizontalAlign: "center"
         },
         data: [
         {
         type: "spline",
         showInLegend: true,
         name: "Like",
         dataPoints: [
         {label: "Jan", y: 3.92},
         {label: "Feb", y: 3.31},
         {label: "Mar", y: 3.85},
         {label: "Apr", y: 3.60},
         {label: "May", y: 3.24},
         {label: "Jun", y: 3.22},
         {label: "Jul", y: 3.06},
         {label: "Aug", y: 3.37},
         {label: "Sep", y: 3.47},
         {label: "Oct", y: 3.79},
         {label: "Nov", y: 3.98},
         {label: "Dec", y: 3.73}
         ]
         },
         {
         type: "spline",
         showInLegend: true,
         name: "Dislike",
         dataPoints: [
         {label: "Jan", y: 2.98},
         {label: "Feb", y: 3.11},
         {label: "Mar", y: 2.4},
         {label: "Apr", y: 0.63},
         {label: "May", y: 0.24},
         {label: "Jun", y: 0.08},
         {label: "Jul", y: 0.03},
         {label: "Aug", y: 0.14},
         {label: "Sep", y: 0.26},
         {label: "Oct", y: 0.36},
         {label: "Nov", y: 1.13},
         {label: "Dec", y: 1.79}
         ]
         },
         {
         type: "spline",
         showInLegend: true,
         name: "Favorites",
         dataPoints: [
         {label: "Jan", y: 3.16},
         {label: "Feb", y: 2.42},
         {label: "Mar", y: 2.99},
         {label: "Apr", y: 3.04},
         {label: "May", y: 3.35},
         {label: "Jun", y: 3.82},
         {label: "Jul", y: 3.14},
         {label: "Aug", y: 3.87},
         {label: "Sep", y: 3.84},
         {label: "Oct", y: 3.19},
         {label: "Nov", y: 3.92},
         {label: "Dec", y: 3.8}
         ]
         },
         {
         type: "spline",
         showInLegend: true,
         name: "Comments",
         dataPoints: [
         {label: "Jan", y: 5.24},
         {label: "Feb", y: 4.09},
         {label: "Mar", y: 3.92},
         {label: "Apr", y: 2.75},
         {label: "May", y: 2.03},
         {label: "Jun", y: 1.55},
         {label: "Jul", y: 0.93},
         {label: "Aug", y: 1.16},
         {label: "Sep", y: 1.61},
         {label: "Oct", y: 3.24},
         {label: "Nov", y: 5.67},
         {label: "Dec", y: 6.06}
         ]
         }

         ],
         legend: {
         cursor: "pointer",
         itemclick: function (e) {
         if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
         e.dataSeries.visible = false;
         }
         else {
         e.dataSeries.visible = true;
         }
         chart.render();
         }
         }

         });

         chart.render();
         });*/

        /*$(document).ready(function(){
            var chart = new CanvasJS.Chart("totalNumberOfSelling", {
                title : {
                    text : "Total number of sale",
                }, animationEnabled : true,

                legend : {
                    verticalAlign : "bottom", horizontalAlign : "center"
                }, data : [{
                    type : "doughnut", showInLegend : true, dataPoints : [{
                        y : <?php echo round(100 - $salePercent)?>,
                        color : '#c5d6dd',
                        legendText : null,
                        indexLabel : ""
                    }, {
                        y : <?php echo round($salePercent) ?>,
                        color : '#dbbcce',
                        legendText : null,
                        indexLabel : "<?php echo round($salePercent);?>%"
                    },

                    ]
                }]
            });

            chart.render();
        });*/

        $(document).ready(function(){
            var chart = new CanvasJS.Chart("chartContainerView", {
                title : {
                    text : "Total Number of views"
                }, data : [{
                    type : "splineArea", //or stackedColumn
                    color : "#78b0c1", dataPoints : [<?php echo $preparedStatViews ?>
                               /* {label: "Week 9" , y: 2 },{label: "Week 10" , y: 6 },*/]
                }]
            });

            chart.render();
        });
    </script>
    <style>

        .cssPopup_overlay {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            transition: opacity 500ms;
            visibility: hidden;
            opacity: 0;
        }

        .cssPopup_overlay:target {
            visibility: visible;
            opacity: 1;
            z-index: 5;
        }

        .cssPopup_popup {
            margin: 70px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            width: 800px;
            position: relative;
            transition: all 5s ease-in-out;
        }

        .cssPopup_popup .cssPopup_close {
            position: absolute;
            top: 20px;
            right: 30px;
            transition: all 200ms;
            font-size: 30px;
            font-weight: bold;
            text-decoration: none;
            color: #333;
        }

        .bd-itm .bd-itmr input[type="checkbox"] {
            height: auto;
            width: auto;
        }

        .bd-itmr.label-box {
            float: left;
            width: 100px;
        }
        button.ui-datepicker-close {display: none;}​
    </style>
@endsection
