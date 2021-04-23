@extends('Store::layouts.cart')
@section('content')
    <div class="mainContainer mt70">
        <div class="checkout-main">
            <div class="frm-container">
                <div>
                    <h1>Make Payment
                    	<div class="payment_options"></div>
                    </h1>
                    <div class="payment-block">
                        @if(isset($e))
                            <div>
                                {{'Error description: ' . $e->getDescription()}}
                            </div>
                            <div>
                                {{'Error message: ' . $e->getMessage()}}
                            </div>
                        @endif
                        <form class="form-block" action="{{url('store/makePayment/'.$order_id.'?method='.$method)}}" id="paymentForm" method="post">
                            <span id="payment-errors"></span>
                            <input id="apm-name" type="hidden" data-worldpay="apm-name" value="paypal">
                            <div class="form-row">
                                <label>Country Code</label>
                                <input type="text" id="country-code" name="countryCode" data-worldpay="country-code" />
                            </div>
                            <!— all other fields you want to collect, e.g. name and shipping address -->
                            <div>
                                <input type="image" src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png" alt="Buy now" />
                            </div>
                        </form>
                    </div>
                    </div>
            </div>
        </div>
        @include('includes.ads-right-side')

    </div>
    <script src="https://cdn.worldpay.com/v1/worldpay.js"></script>
    <script type="text/javascript">
        var form = document.getElementById('payment-form');
        // Set token type to 'apm'
        Worldpay.tokenType = 'apm';
        // Set client key
        Worldpay.setClientKey('T_C_538a50bd-60ef-4ae1-b6be-810a5193fab5');
        Worldpay.useForm(form, function (status, response) {
            if (response.error) {
                Worldpay.handleError(form, document.getElementById('payment-errors'), response.error);
            } else {
                var token = response.token;
                Worldpay.formBuilder(form, 'input', 'hidden', 'token', token);
                form.submit();
            }
        });
    </script>
@endsection
