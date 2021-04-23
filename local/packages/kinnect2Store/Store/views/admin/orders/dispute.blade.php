@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

    @include('Store::includes.store-order-leftside')
    <div class="product-Analytics">
        <div class="addProduct">
            <div class="selectdiv">
                {!! Form::open(['url' => url("store/order/dispute/complain"), "id" => "order_dispute_detail", "enctype"=>"multipart/form-data"]) !!}
                <h1>Open Dispute</h1>

                <div class="field-item">
                    <div class="bsm-nav">
                        <a href="javascript:void(0)">Please complete the request from below</a>
                    </div>
                </div>

                <div class="field-item">
                    <label for="">Did You receive your order:</label>
                    <label>Yes<input type="radio" name="order_receive" class="order_receive" id="order_receive"
                                     value="Yes"></label>
                    <label>No<input type="radio" name="order_receive" class="order_receive" id="order_receive"
                                    value="No"></label>
                    <span id="info1" style="color:red;display:none"></span>
                </div>

                <div class="field-item">
                    <label for="">Payment Received:</label>
                    <span>GBP $<input type="text" name="payment_received" class="amount_receive" id="amount_receive"
                                      value="{{$payment_received[0]->amount}}" disabled>
                    </span>
                    <label for="title">Shipping Time:</label>
                    <input type="text" name="shipping_time" id="shipping_time" class="shipping_time"
                           value="{{$payment_received[0]->gateway_timestamp}}" disabled>
                </div>

                <div class="field-item">

                    <label for="full_refund">Refund Request:</label>
                    <label>Full Refund<input type="radio" id="full_refund" name="full_refund" class="full_refund"
                                             id="full_refund" value="Full Refund"></label>
                    <label id="refund_txt">Partial Refund Amount Request:GDP $<input type="radio" name="partial_refund"
                                                                                     class="full_refund"
                                                                                     id="order_receive"
                                                                                     value="Partial Refund">
                        <input type="text" name="partial_refund" value="" id="partial_refund" class="full_refund"
                               placeholder="amount"></label>
                    <span id="info2" style="color:red;display:none"></span>
                </div>

                <div class="field-item">
                    {!! Form::label('Reason:') !!}<br/>
                    {{--    {!!  Form::select('data', 'data', null, ['class' => 'form-control' , 'id' => 'form-control'])!!}--}}
                    <select id="reason">
                        <option class="reason" name="reason_A" value="0">Select Reason</option>
                        @foreach(\Config::get('constants_brandstore.ORDER_DISPUTE_REASONS') as $key => $val)
                        <option class="reason" name="reason_A" value="{{$key}}">{{$val}}</option>
                        @endforeach
                    </select>
                    <span id="info" style="color:red;display:none"></span>
                </div>

                <div class="field-item">
                    <label for="detail">Detail:</label>
                    <input type="text" class="detail" id="detail" name="detail"
                           placeholder="Please provide clear reason for refund">
                    <span id="info_detail" style="color:red;display:none"></span>
                </div>

                <style>
                    .image_delete_icon {
                        position: absolute;
                        background-image: url("{!! asset('local/public/assets/images/del-btn.png') !!}");
                        height: 16px;
                        width: 16px;
                        float: right;
                        background-repeat: no-repeat;
                        cursor: pointer;
                    }

                    .error {
                        color: red;
                    }

                </style>
                <div class="field-item product-images">
                    <label for="">Product Images</label>

                    <div id="images_selection_wrap" class="select-img cf">
                        <div id="images_container">
                            {{--<span class="image_delete_icon" id="delete_image_1'" ></span>--}}
                            <img class="browse_image_thumb" file-data=""
                                 src="{!! asset('local/public/assets/images/brand-store-admin-product-img.png') !!}"
                                 id="browse_image_thumb_1" alt="img">
                            <input onchange="readURL(this, 1);" type="file" name="product_pictures_1"
                                   class="image_to_be_uploaded" id="product_pictures_1" style="display: none;">
                        </div>
                        <a class="btn-add-product" href="javascript:void(0);">
                            <img src="{!! asset('local/public/assets/images/brand-store-admin-product-add.png') !!}"
                                 id="plus_sign_btn" alt="img">
                        </a>

                    </div>
                    <span id="total_image" style="color:red;display:none"></span>
                    {{--<a class="btn blue mt10 mb10" href="javascript:void(0);">Browse</a>--}}
                </div>

                <div class="fltR mt20 mb20">
                    <input type="hidden" name="_token" value="{{Session::token()}}">
                    <button id="add_product_btn" class="btn blue fltL mr10" type="submit">Confirm</button>
                    <a class="btn grey fltL mr10" href="javascript:void(0);">Cancel</a>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<script language="javascript" type="text/javascript">
    // <editor-fold desc="image-append and image thumbnail - code">
    function readURL(input, browse_image_thumb_id) {
        if (input.files && input.files[0]) {

            var data = new FormData();
            data.append('product_image', input.files[0]);
            $.ajax({
                url: '{{url("store/".Auth::user()->username."/admin/product_image_ajax")}}',
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function (file_data) {
                    $('#browse_image_thumb_' + browse_image_thumb_id).attr('file-data', file_data);
                    var savedImageId = browse_image_thumb_id;
                    var numberOfImages = $(".browse_image_thumb").length;
                    var newImageId = numberOfImages + 1;

                    var newImageHtml = '<input type="hidden" value="' + file_data + '" name="input_image_id_' + savedImageId + '" class="input_image_ids" id="input_image_id_' + savedImageId + '">';
                    $('#images_container').prepend(newImageHtml);

                },
                error: function () {
                    $('#browse_image_thumb_' + browse_image_thumb_id).attr('file-data', ' ');
                }
            });
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#browse_image_thumb_' + browse_image_thumb_id)
                        .attr('src', e.target.result)
                        .width(80)
                        .height(45);
            };

            reader.readAsDataURL(input.files[0]);

        }
    }
    // </editor-fold>

    // <editor-fold desc="get more image on plus btn click - code">
    $(document).on("click", ".browse_image_thumb", function (evt) {
        var imageId = evt.target.id.match(/\d+/);
        $("#product_pictures_" + imageId).click();

    });

    $(document).on("click", "#plus_sign_btn", function (evt) {

        var numberOfImages = $(".browse_image_thumb").length;
        var newImageId = numberOfImages + 1;

        if (newImageId == 5) {
            $("#total_image").html('You can upload 5 Product photos only.').show();
            $("#add_product_btn").html("saving..");
            return false;
        }

        var newImageHtml = '<span class="image_delete_icon" id="delete_image_' + newImageId + '"></span><img class="browse_image_thumb" file-data="" src="<?php echo asset("local/public/assets/images/brand-store-admin-product-img.png") ?>" id="browse_image_thumb_' + newImageId + '" alt="img"><input onchange="readURL(this, ' + newImageId + ');" type="file" name="product_pictures_' + newImageId + '" class="image_to_be_uploaded" id="product_pictures_' + newImageId + '" style="display: none;">';
        $('#images_container').prepend(newImageHtml);

        $("#browse_image_thumb_" + newImageId).click();
    });
    // </editor-fold>

    // <editor-fold desc="delete selected image - code">

    $(document).on("click", ".image_delete_icon", function (evt) {
        var imageId = evt.target.id.match(/\d+/);
        var file_id = $("#browse_image_thumb_" + imageId).attr('file-data');
        var defaultImg = '<?php echo asset("local/public/assets/images/brand-store-admin-product-img.png") ?>';
        var thumbImg = $('#browse_image_thumb_' + imageId).attr('src');
        var total_images = document.getElementsByClassName("image_to_be_uploaded").length;

        if (total_images < 5) {
            $("#total_image").html('');
        }
        if (defaultImg == thumbImg && total_images > 1) {
            $("#input_image_id_" + imageId).remove();
            $("#delete_image_" + imageId).remove();
            $("#product_pictures_" + imageId).remove();
            $('#browse_image_thumb_' + imageId).remove();
            return false;
        }

        if (file_id > 0) {

            var data = new FormData();
            data.append('file_id', file_id);

            $.ajax({
                url: '{{url("store/".Auth::user()->username."/admin/delete_product_image")}}',
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function (file_data) {
                    var total_images = document.getElementsByClassName("image_to_be_uploaded").length;

                    if (total_images > 1) {
                        $("#input_image_id_" + imageId).remove();
                        $("#delete_image_" + imageId).remove();
                        $("#product_pictures_" + imageId).remove();
                        $('#browse_image_thumb_' + imageId).remove();
                    } else {
                        $('#browse_image_thumb_' + imageId).attr('file-data', file_id);
                        $('#browse_image_thumb_' + imageId).attr('src', '<?php echo asset("local/public/assets/images/brand-store-admin-product-img.png") ?>');
                    }

                },
                error: function () {
                    alert('Not deleted, try again.');
                    $('#browse_image_thumb_' + browse_image_thumb_id).attr('file-data', file_id);
                }
            });

        } else {
            alert('You must upload an image to add issue.');
        }
    });// add key feature record
    // </editor-fold>

    // <editor-fold desc="Script validations checks and send data for add store function  - code">
    $(document).on("click", "#add_product_btn", function (evt) {
        evt.preventDefault();
        if ($('input[class=order_receive]:checked').length <= 0) {
            $("#info1").html('No radio checked.').show();
            setTimeout(function () {
                $('#info1').fadeOut('slow');
            }, 4000);
            return false;
        } else {

            var order_receive = $('#order_receive').val();

        }


        /*$('input[type=radio]').on('change', function(){
         $('input[type=radio]').not(this).prop('checked', false);
         });*/

        if ($('input[class=full_refund]:checked').length <= 0) {
            $("#info2").html('No radio checked.').show();
            setTimeout(function () {
                $('#info2').fadeOut('slow');
            }, 4000);
            return false;
        } else {

            var full_refund = $('input[class=full_refund]:checked').val();

            if (full_refund == 'Partial Refund') {

                var full_refund = $("#partial_refund").val();
                if (full_refund == '') {
                    $("#info2").html('Please write issue.').show();
                    setTimeout(function () {
                        $('#info2').fadeOut('slow');
                    }, 4000);
                    return false;
                }
                var full_refund = $("#partial_refund").val();


            } else {

                var full_refund = $('input[class=full_refund]:checked').val();

            }

        }

        if ($("#reason option:selected").val() == 0) {
            $("#info").html('Please select the reason.').show();
            setTimeout(function () {
                $('#info').fadeOut('slow');
            }, 4000);
            return false;
        }
        else {
            var reason = $("#reason option:selected").text();
        }

        var detail = $("#detail").val();
        if (detail == '') {
            $("#info_detail").html('Please write down your issue.').show();
            setTimeout(function () {
                $('#info_detail').fadeOut('slow');
            }, 4000);
            return false;
        }
        var detail = $("#detail").val();

        /*if($("#add_product_btn").html() === "saving.."){
         return false;
         }
         $("#add_product_btn").html("saving..");*/
        var total_image_ids = document.getElementsByClassName("input_image_ids").length;
        if (total_image_ids < 1) {
            $("#total_image").html('You must select an image to continue.').show();
            setTimeout(function () {
                $('#total_image').fadeOut('slow');
            }, 4000);
            //$("#add_product_btn").html("save");

            return false;
        }

        var myImageIds = [];
        $.each(document.getElementsByClassName("input_image_ids"), function (i, image_ids) {
            myImageIds.push(image_ids.value);

        });


        var data = [];
        data = $("#order_dispute_detail").serialize();


        var urlToSubmit = '<?php echo url("store/order/dispute/complain"); ?>';

        $.post(urlToSubmit, data + '&order_receive=' + order_receive + '&full_refund=' + full_refund + '&reason=' + reason + '&detail=' + detail + '&myImageIds=' + myImageIds, function (response) {

            if (response > 0) {

                alert('success');
            } else {
                alert('fail');

            }

        });

    });
    // </editor-fold>
    $("input[id='full_refund']").click(function () {
        $("#refund_txt").hide();
    });

</script>

@endsection
