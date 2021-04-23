@extends('Store::layouts.default-extend')

@section('content')
    <div class="mainContainer mt70">
        <div class="checkout-main">
            <div class="frm-container">
                <div class="form-main-block">
                    <h1>
                        <span class="fltL">Make Payment</span>
                        <span class="fltR">Dispute Processing Fee:&nbsp;&dollar;{{format_currency($claim_fee)}}</span>
                    </h1>

                    <div class="clrfix"></div>
                    @if(isset($e))
                    <div>
                        {{'Error description: ' . $e->getDescription()}}
                    </div>
                    <div>
                        {{'Error message: ' . $e->getMessage()}}
                    </div>
                    @endif
                    @if(Session::has('message'))
                    <div style="color:#F00000;">{{Session::get('message')}}</div>
                    @endif
                    {!! Form::open(['url' => url('store/payClaimFee/'.$claim_id), "id" => "paymentForm",  "class"=>"form-block"]) !!}
                    <span id="paymentErrors"></span>

                    <div class="form-row">
                        <label>Name on Card</label>
                        <input data-worldpay="name" placeholder="Name on Card" name="name" type="text" />
                    </div>
                    <div class="form-row">
                        <label>Card Number</label>
                        <input data-worldpay="number" placeholder="Card Number" size="20" type="text" />
                    </div>
                    <div class="form-row">
                        <label>CVC</label>
                        <input data-worldpay="cvc" size="4" type="text" placeholder="cvc" />
                    </div>
                    <div class="form-row exp-date">
                        <label>Expiration (MM/YYYY)</label>
                        <input data-worldpay="exp-month" placeholder="MM" size="2" type="text" />
                        <label class="sep"> / </label>
                        <input data-worldpay="exp-year" placeholder="YYYY" size="4" type="text" />
                    </div>
                    <div class="proceed-container">
                        <a href="#" class="btn-proceed make-payment">Pay</a>
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <style>
        #loading-div-background
        {
            display:none;
            position:fixed;
            top:0;
            left:0;
            background:black;
            width:100%;
            height:100%;
        }#loading-div
         {
             width: 300px;
             height: 200px;
             text-align:center;
             position:absolute;
             left: 50%;
             top: 50%;
             margin-left:-150px;
             margin-top: -100px;
         }


    </style>
    <div id="loading-div-background">
        <div id="loading-div" class="ui-corner-all" >
            <img style="width: 38px;" src="{!! asset('local/public/images/loading.gif') !!}" alt="Loading.."/>
            <h2 style="color:gray;font-weight:normal;margin-top: 25px;">Please wait....</h2>
        </div>
    </div>


    <?php
    $client_key = \Config::get('constants_brandstore.WORLDPAY_CLIENT_KEY');
    ?>
    <script src="https://cdn.worldpay.com/v1/worldpay.js"></script>
    <script type="text/javascript">

        jQuery(document).on("click",'.make-payment',function(e){
            e.preventDefault();
            $("#loading-div-background").css({ opacity: 0.8 });
            $("#loading-div-background").show();
            //$("#loading-div-background").hide();

            jQuery('#paymentForm').submit();
        });
        var form = document.getElementById('paymentForm');

        Worldpay.useOwnForm({
            'clientKey': '{{$client_key}}',
            'form': form,
            'reusable': true,
            'callback': function(status, response) {
                document.getElementById('paymentErrors').innerHTML = '';
                if (response.error) {
                    $("#loading-div-background").css({ opacity: 0 });
                    $("#loading-div-background").hide();
                    Worldpay.handleError(form, document.getElementById('paymentErrors'), response.error);
                } else {
                    var token = response.token;
                    Worldpay.formBuilder(form, 'input', 'hidden', 'token', token);
                    form.submit();
                }
            }
        });
    </script>
@endsection