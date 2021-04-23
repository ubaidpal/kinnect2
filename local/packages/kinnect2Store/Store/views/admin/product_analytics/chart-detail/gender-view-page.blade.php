<div id="gender-views" class="cssPopup_overlay">
    <div class="cssPopup_popup">
        <a class="cssPopup_close" href="#">&times;</a>

        {!! Form::open(['url' => "store/$user->username/admin/product-analytics/gender-view/page/$owner_id", 'method'=>'post', 'enctype'=>"multipart/form-data", 'id' =>'gender-form']) !!}


        <div class="bank-detail-popup" style="width: 100%">
            <div class="ana-detail-title">Gender</div>
            <div class="get-detail">
                <div class="bd-btnc">
                    <button type="button" id="getGenderView">Get Detail</button>

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
            <div id="gender-detail-view"
                 style="padding-right:2px; border-right:2px solid black; float:left; height: 300px; width: 100%;"></div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(document).ready(function () {
        var genderChart = new CanvasJS.Chart("gender-detail-view", {
            title: {
                text: "Gender", /*fontFamily: "Impact",
                 fontWeight: "normal"*/
            }, animationEnabled: true,

            legend: {
                verticalAlign: "bottom", horizontalAlign: "center"
            }, data: [{

                type: "doughnut", showInLegend: true, dataPoints: [{
                    y: <?php echo $product_statics_by_age["maleCountView"]?>,
                    color: '#c5d6dd',
                    legendText: "Male <?php echo round($product_statics_by_age["maleCountViewPercent"]);?>%",
                    indexLabel: "Male <?php echo round($product_statics_by_age["maleCountViewPercent"]);?>%"
                }, {
                    y: <?php echo $product_statics_by_age["femaleCountView"]?>,
                    color: '#dbbcce',
                    legendText: "Female <?php echo round($product_statics_by_age["femaleCountViewPercent"]);?>%",
                    indexLabel: "Female <?php echo round($product_statics_by_age["femaleCountViewPercent"]);?>%"
                },

                ]
            }]
        });

        genderChart.render();

        $("#getGenderView").click(function (e) {
            e.preventDefault();
            var data = $('#gender-form').serialize();
            $.ajax({
                type: 'POST', url: $('#gender-form').attr('action'), data: data, success: function (resultD) {
                    console.log(genderChart.options.data[0].dataPoints)
                    //var points = [];
                    genderChart.options.data[0].dataPoints = resultD;
                    console.log(genderChart.options.data[0].dataPoints)
                    genderChart.render();
                }
            })
        })
    });
</script>

