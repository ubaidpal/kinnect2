{{--

    * Created by   :  Muhammad Yasir
    * Project Name : kinnect2
    * Product Name : PhpStorm
    * Date         : 25-Feb-2016 2:59 PM
    * File Name    :

--}}
@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->

@include('Store::includes.store-banner')

<div class="mainCont">

    @include('Store::includes.store-order-leftside')

    <div class="product-Analytics">
        <div class="addProduct">
            <div class="selectdiv">

                {!! Form::model($dispute,['url' => url("store/order/dispute/complain"), "id" => "order_dispute_detail", "enctype"=>"multipart/form-data"]) !!}
                {!! Form::hidden('order_id', $dispute->order_id) !!}
                <h1>Request Refund</h1>

                <div class="dispute-wrapper">
                    <div class="field-item">
                        <div class="request-note">Please complete the request from below</div>
                    </div>

                    <div class="field-item dispute-row">
                        <div class="title mW">Did You receive your order:</div>
                        <div class="detail bb">
                            <label>Yes

                                {!! Form::radio('order_receive',"Yes",($dispute->is_received == "Yes"? TRUE :FALSE), ["class"=>"order_receive"] ) !!}
                            </label>
                            <label>No
                                {!! Form::radio('order_receive',"No",($dispute->is_received == "No"? TRUE :FALSE), ["class"=>"order_receive"] ) !!}
                            </label>
                            <span id="info1" style="color:red;display:none"></span>
                        </div>
                    </div>


                    <div class="field-item dispute-row">
                        @if(is_object($payment_received) AND count($payment_received) > 0 )
                            <div class="title mW">Payment Received:</div>
                            <div class="detail bb">
                                <div class="gb">$ <input type="text" name="payment_received" class="amount_receive"
                                                            id="amount_receive"
                                                            value="{{format_currency($order->total_price - $order->total_discount)}}"
                                                            style="width:auto; padding:0px;" disabled></div>
                                <div>Shipping Time:
                                    {{--<input type="text" name="shipping_time" id="shipping_time" class="shipping_time"
                                  value="{{$payment_received[0]->gateway_timestamp}}" style="width:auto; padding:0px;" disabled>--}}
                                    <input type="text" name="shipping_time" id="shipping_time" class="shipping_time"
                                           value="{{$deliveryInfo->date_to_be_delivered}}"
                                           style="width:auto; padding:0px;" disabled>
                                </div>
                            </div>
                    </div>
                    <div class="field-item dispute-row">
                        <div class="title mW">Refund Request:</div>
                        <div class="detail bb">
                            <label>Full Refund
                                {!! Form::radio('refund',"full",($dispute->claim_request == "full"? TRUE :FALSE), ["class"=>"refund"] ) !!}

                            </label><br/><br/>
                            <label id="refund_txt">
                                Partial Refund Amount Request: $
                                {!! Form::radio('refund',"partial",($dispute->claim_request == "partial"? TRUE :FALSE), ["class"=>"refund"] ) !!}
                                <input type="text" name="claimed_amount" value="{{$dispute->claimed_amount}}"
                                       id="partial_refund" class="full_refund "
                                       placeholder="amount" @if($dispute->claim_request != "partial") disabled  @endif ><br>
                                <small>Maximum  amount to be claimed {{format_currency($order->total_price - $order->total_discount)}}</small>
                            </label>
                            <span id="info2" style="color:red;display:none"></span>
                            <span id="info3" style="color:red;display:none"></span>
                        </div>
                    </div>

                    <div class="field-item dispute-row mt10">
                        <div class="title mW">{!! Form::label('Reason:') !!}<br/>
                            {{--    {!!  Form::select('data', 'data', null, ['class' => 'form-control' , 'id' => 'form-control'])!!}--}}
                        </div>
                        <div class="detail bb">
                            <select id="reason" name="reason" class="mt0">
                                <option class="reason" name="reason_A" value="0">Select Reason</option>
                                @foreach(\Config::get('constants_brandstore.ORDER_DISPUTE_REASONS') as $key => $val)
                                    <option class="reason" @if($key == $dispute->reason) selected="selected" @endif name="reason_A" value="{{$key}}">{{$val}}</option>
                                @endforeach
                            </select>
                            <span id="info" style="color:red;display:none"></span>
                        </div>
                    </div>

                    <div class="field-item dispute-row">
                        <div class="title mW mt10">Detail:</div>
                        <div class="detail bb">
                            <input type="text" id="detail" name="detail"
                                   placeholder="Please provide clear reason for refund" value="{{$dispute->detail}}">
                            <span id="info_detail" style="color:red;display:none"></span>
                        </div>
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
                    <div class="field-item dispute-row">
                        <div class="title mW mt15">Select Product:</div>
                        <div class="detail bb">
                            <div class="image-box">Product Image</div>
                            <div class="price-box">Product Price</div>
                            <div class="price-box">Select</div>
                        </div>
                        @foreach($products as $product)
                            <div class="title mW mt15">&nbsp;</div>
                            <div class="detail bb">
                                <div class="image-box">
                                    <img src="{{ getProductPhotoSrc('','',$product->id, 'product_profile') }}" width="210"
                                         height="151" alt="img">
                                </div>
                                <div class="price-box">{{format_currency($product->price)}}</div>
                                <div class="select-product">
                                    {!! Form::checkbox('products[]',$product->id, (isset($requestedProducts[$product->id])?TRUE:'') ) !!}
                                </div>

                            </div>
                        @endforeach
                    </div>
                    <div class="field-item dispute-row">
                        <div class="title mW mt15">Add Photo:</div>


                        <div id="images_selection_wrap" class="select-img cf detail bb">
                            <div id="images_container">
                                {{--<span class="image_delete_icon" id="delete_image_1'" ></span>--}}
                                @if($files != '')
                                    @foreach($files as $row)
                                        <?php //echo '<tt><pre>'; print_r($row->storageFile); die;
                                        ?>
                                        <span class="image_delete_icon"
                                              id="delete_image_{{$row->storageFile->file_id}}"></span>
                                        <img src="{!! getPhotoUrlByFile($row->storageFile) !!}"
                                             file-data="{{$row->storageFile->file_id}}" width="75" height="44"
                                             alt="dispute images" class="browse_image_thumb"
                                             id="browse_image_thumb_{{$row->storageFile->file_id}}"/>
                                    @endforeach
                                @endif
                                <input onchange="readURL(this, 1);" type="file" name="product_pictures[]"
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

                    <div class="dispute-row">
                        <div class="title mW">&nbsp;</div>
                        <div class="detail">
                            <input type="hidden" name="_token" value="{{Session::token()}}">
                            <button id="add_product_btn" class="blueBtn" type="submit">Confirm</button>
                            <a class="greyBtn fltL" href="{{URL::previous()}}">Cancel</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    @else
                        <div class="categoryList">
                            <h3>No Data.</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<?php $max_amount = $order->total_price - $order->total_discount?>;
<script language="javascript" type="text/javascript">
    // <editor-fold desc="image-append and image thumbnail - code">
    function readURL(input, browse_image_thumb_id) {
        if (input.files && input.files[0]) {

            var data = new FormData();
            data.append('product_image', input.files[0]);
            $.ajax({
                //url : '{{url("store/".Auth::user()->username."/admin/product_image_ajax")}}',
                url: '{{url("dispute-image")}}',
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function (file_data) {
                    $('#browse_image_thumb_' + browse_image_thumb_id).attr('file-data', file_data.id);
                    var savedImageId = browse_image_thumb_id;
                    var numberOfImages = $(".browse_image_thumb").length;
                    var newImageId = numberOfImages + 1;

                    var newImageHtml = '<input type="hidden" value="' + file_data.id + '" name="input_image_id[]" class="input_image_ids" id="input_image_id_' + savedImageId + '">';
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

        var newImageHtml = '<span class="image_delete_icon" id="delete_image_' + newImageId + '"></span><img class="browse_image_thumb" file-data="" src="<?php echo asset("local/public/assets/images/brand-store-admin-product-img.png") ?>" id="browse_image_thumb_' + newImageId + '" alt="img"><input onchange="readURL(this, ' + newImageId + ');" type="file" name="product_pictures[]" class="image_to_be_uploaded" id="product_pictures_' + newImageId + '" style="display: none;">';
        $('#images_container').prepend(newImageHtml);

        $("#browse_image_thumb_" + newImageId).click();
    });
    // </editor-fold>

    // <editor-fold desc="delete selected image - code">

    $(document).on("click", ".image_delete_icon", function (evt) {
        var imageId = evt.target.id.match(/\d+/);
        $(this).remove();
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
                //url : '{{url("store/".Auth::user()->username."/admin/delete_product_image")}}',
                url: '{{url("delete-dispute-image")}}',
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function (file_data) {
                    var total_images = document.getElementsByClassName("image_to_be_uploaded").length;

                    //if(total_images > 1){
                    $("#input_image_id_" + imageId).remove();
                    $("#delete_image_" + imageId).remove();
                    $("#product_pictures_" + imageId).remove();
                    $('#browse_image_thumb_' + imageId).remove();
                    // }else{
                    $('#browse_image_thumb_' + imageId).attr('file-data', file_id);
                    $('#browse_image_thumb_' + imageId).attr('src', '<?php echo asset("local/public/assets/images/brand-store-admin-product-img.png") ?>');
                    // }

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
    $('input[class=refund]').click(function() {
        if ($(this).val() == 'partial') {
            $('#partial_refund').attr('disabled', false);
        } else {
            $('#partial_refund').attr('disabled', true);
            $('#partial_refund').val('');
        }
    });
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

        if ($('input[class=refund]:checked').length <= 0) {
            $("#info2").html('No radio checked.').show();
            setTimeout(function () {
                $('#info2').fadeOut('slow');
            }, 4000);
            return false;
        } else {

            var full_refund = $('input[class=refund]:checked').val();

            if (full_refund == 'partial') {

                full_refund = $("#partial_refund").val();
                if (full_refund == '') {
                    $("#info3").html('Please enter amount.').show();
                    setTimeout(function () {
                        $('#info3').fadeOut('slow');
                    }, 4000);
                    return false;
                }
                var max_amount =  {{$max_amount}};
                if (full_refund >= max_amount + 1) {
                    $("#info3").html('Claimed amount must be less then or equal to ' + max_amount).show();
                    setTimeout(function () {
                        $('#info3').fadeOut('slow');
                    }, 4000);
                    return false;
                }
                if( full_refund < 0){

                    $("#info3").html('Claimed amount must be greater then 0').show();
                    setTimeout(function () {
                        $('#info3').fadeOut('slow');
                    }, 4000);
                    return false;
                }
            } else {

                 full_refund = $('input[class=full_refund]:checked').val();

            }

        }

        if ($("#reason option:selected").val() == 0) {
            $("#info").html('Please select the reason.').show();
            setTimeout(function () {
                $('#info').fadeOut('slow');
            }, 4000);
            return false;
        } else {
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
        /*var total_image_ids = document.getElementsByClassName("input_image_ids").length;
         if (total_image_ids < 1) {
         $("#total_image").html('You must select an image to continue.').show();
         setTimeout(function () {
         $('#total_image').fadeOut('slow');
         }, 4000);
         //$("#add_product_btn").html("save");

         return false;
         }*/

        var myImageIds = [];
        $.each(document.getElementsByClassName("input_image_ids"), function (i, image_ids) {
            myImageIds.push(image_ids.value);

        });

        var data = [];
        data = $("#order_dispute_detail").serialize();

        var urlToSubmit = '<?php echo url("store/dispute/complain"); ?>';

        $.post(urlToSubmit, data, function (response) {

            if (response != 0) {

                window.location.href = "{{url('store/dispute/detail')}}/" + response;
            } else {
                alert('fail');

            }

        });

    });
    // </editor-fold>
    $("input[id='full_refund']").click(function () {
        //$("#refund_txt").hide();
    });

</script>

@endsection
