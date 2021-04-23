{{--

    * Created by   :  Muhammad Yasir
    * Project Name : kinnect2
    * Product Name : PhpStorm
    * Date         : 14-Mar-2016 4:50 PM
    * File Name    : 

--}}


<div id="country-views" class="cssPopup_overlay">
    <div class="cssPopup_popup">
        <a class="cssPopup_close" href="#">&times;</a>

        {!! Form::open(['url' => "store/$user->username/admin/product-analytics/country-view/$product_id", 'method'=>'post', 'enctype'=>"multipart/form-data", 'id' =>'country-form']) !!}


        <div class="bank-detail-popup" style="width: 100%">
            <div class="ana-detail-title">Country</div>
            <div class="get-detail">
                <div class="bd-btnc">
                    <button type="button" id="getCountryView">Get Detail</button>

                </div>
                <div class="bd-itm">
                    <div class="bd-itml">Select Date Range:</div>
                    <div class="bd-itmr">
                        <input type="text" placeholder="Select Start Date" name="start_date" required
                               class="start_date">
                    </div>
                    <div class="bd-itmr">
                        <input type="text" placeholder="Select End Date" name="end_date" required class="end_date">
                    </div>
                </div>
            </div>
            <div id="country-detail-view"
                 style="padding-right:2px; border-right:2px solid black; float:left; height: 300px; width: 100%;"></div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(document).ready(function () {
        var countryChart = new CanvasJS.Chart("country-detail-view", {
            title: {
                text: "Region"
            }, axisY: {
                title: 'View'
            }, axisX: {
                title: 'Country'
            }, animationEnabled: true, data: [{
                // Change type to "doughnut", "line", "splineArea", etc.
                type: "column", dataPoints: [<?php echo $myAllCountries; ?>
                        ]
            }]
        });
        countryChart.render();

        $("#getCountryView").click(function (e) {
            e.preventDefault();
            var data = $('#country-form').serialize();
            $.ajax({
                type: 'POST', url: $('#country-form').attr('action'), data: data, success: function (resultD) {

                    countryChart.options.data[0].dataPoints = resultD;

                    countryChart.render();
                }
            })
        })
    });


</script>

