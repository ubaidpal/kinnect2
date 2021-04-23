{{--

    * Created by   :  Muhammad Yasir
    * Project Name : kinnect2
    * Product Name : PhpStorm
    * Date         : 14-Mar-2016 4:50 PM
    * File Name    : 

--}}


<div id="number-views" class="cssPopup_overlay">
    <div class="cssPopup_popup">
        <a class="cssPopup_close" href="#">&times;</a>

        {!! Form::open(['url' => "store/$user->username/admin/product-analytics/number-views/page/$owner_id", 'method'=>'post', 'enctype'=>"multipart/form-data", 'id' =>'number-form']) !!}


        <div class="bank-detail-popup" style="width: 100%">
            <div class="ana-detail-title">Total Number of Views</div>
            <div class="get-detail">
                <div class="bd-btnc">
                    <button type="button" id="getDayView">Get Detail</button>

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

            <div id="number-detail-view"
                 style="padding-right:2px; border-right:2px solid black; float:left; height: 300px; width: 100%;"></div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(document).ready(function () {
        var datPoints = [<?php echo $preparedStatViews ?>];
        var dayView = new CanvasJS.Chart("number-detail-view", {
            title: {
                text: "Total Number of views",

            }, axisY: {
                title: "View"
            }, axisX: {
                title: "Date"
            }, data: [{
                type: "splineArea", //or stackedColumn
                color: "#78b0c1", dataPoints: datPoints
            }]
        });

        dayView.render();

        $("#getDayView").click(function (e) {

            e.preventDefault();
            var data = $('#number-form').serialize();
            $.ajax({
                type: 'POST', url: $('#number-form').attr('action'), data: data, success: function (resultD) {


                    dayView.options.data[0].dataPoints = resultD;

                    dayView.render();
                }
            })
        })
    });


</script>

