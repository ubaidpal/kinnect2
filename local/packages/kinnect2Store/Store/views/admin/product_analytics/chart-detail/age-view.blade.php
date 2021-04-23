{{--

    * Created by   :  Muhammad Yasir
    * Project Name : kinnect2
    * Product Name : PhpStorm
    * Date         : 14-Mar-2016 4:50 PM
    * File Name    : 

--}}



<div id="age-views" class="cssPopup_overlay">
    <div class="cssPopup_popup">
        <a class="cssPopup_close" href="#">&times;</a>

        {!! Form::open(['url' => "store/$user->username/admin/product-analytics/age-view/$product_id", 'method'=>'post', 'enctype'=>"multipart/form-data", 'id' =>'age-form']) !!}


        <div class="bank-detail-popup" style="width: 100%">
            <div class="ana-detail-title">Views By Age</div>
            <div class="get-detail">
            	<div class="bd-btnc">
                <button type="button" id="getAgeView">Get Detail</button>
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
            <div id="age-detail-view"
                 style="padding-right:2px; border-right:2px solid black; float:left; height: 300px; width: 100%;"></div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(document).ready(function(){
        var ageView = new CanvasJS.Chart("age-detail-view", {
            title : {
                text : "Age"
            },axisY:{
                title:'View'
            },axisX:{
                title:'Age'
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
                    color : '#c5d6dd',
                    label : "> 55",
                    y : <?php echo $product_statics_by_age["fifthCountView"]?>
                    },]
            }]
        });
        ageView.render();

        $("#getAgeView").click(function(e){
            e.preventDefault();
            var data = $('#age-form').serialize();
            $.ajax({
                type : 'POST', url : $('#age-form').attr('action'), data : data, success : function(resultD){
                    console.log(ageView.options.data[0].dataPoints)
                    //var points = [];
                    ageView.options.data[0].dataPoints = resultD;
                    console.log(ageView.options.data[0].dataPoints)
                    ageView.render();
                }
            })
        })
    });


</script>

