{{--

    * Created by   :  Muhammad Yasir
    * Project Name : kinnect2
    * Product Name : PhpStorm
    * Date         : 14-Mar-2016 4:50 PM
    * File Name    : 

--}}


<div id="peak-views" class="cssPopup_overlay">
    <div class="cssPopup_popup">
        <a class="cssPopup_close" href="#">&times;</a>

        {!! Form::open(['url' => "store/$user->username/admin/product-analytics/peak-view/$product_id", 'method'=>'post', 'enctype'=>"multipart/form-data", 'id' =>'peak-form']) !!}


        <div class="bank-detail-popup" style="width: 100%">
            <div class="ana-detail-title">Traffic and Peak hours</div>
            <div class="get-detail">
                <div class="bd-btnc">
                    <button type="button" id="getPeakView">Get Detail</button>

                </div>
                <div class="bd-itm">
                    <div class="bd-itml">Select Date:</div>
                    <div class="bd-itmr">
                        <input type="text" placeholder="Select Month" name="date" required class="date">
                    </div>
                </div>
            </div>
            <div id="peak-detail-view"
                 style="padding-right:2px; border-right:2px solid black; float:left; height: 300px; width: 100%;"></div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(document).ready(function () {
        var peakChart = new CanvasJS.Chart("peak-detail-view", {
            animationEnabled: true, animationDuration: 2000, title: {
                text: "Traffic and Peak hours"
            }, axisX: {
                title: "Hours of the day", // gridThickness: 3,
                interval: 1, intervalType: "hour", valueFormatString: "hh tt",  //labelAngle: -30
            }, axisY: {
                title: "Views"
            }, data: [{
                type: "column",
                color: "#78b0c1",
                indexLabelFontColor: "green",
                 dataPoints : [//array

                 <?php echo  $preparedStatViewsHours; ?>
                 ]
            }]
        });

        peakChart.render();

        $("#getPeakView").click(function (e) {
            e.preventDefault();
            var data = $('#peak-form').serialize();
            $.ajax({
                type: 'POST', url: $('#peak-form').attr('action'), data: data, success: function (resultD) {

                    var points = [];
                    $.each(resultD, function (key, value) {
                        points.push({y: value.y, label: value.label}); // I'm
                    });
                    peakChart.options.data[0].dataPoints = points;

                    peakChart.render();
                }
            })
        })
    });


</script>

