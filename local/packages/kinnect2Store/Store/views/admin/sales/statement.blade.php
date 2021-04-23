{{--

    * Created by   :  Muhammad Yasir
    * Project Name : kinnect2
    * Product Name : PhpStorm
    * Date         : 09-Mar-2016 2:17 PM
    * File Name    : STATEMENT

--}}
@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

    @include('Store::includes.store-admin-leftside')

    <div class="product-Analytics">
        <div class="post-box">


            <!-- Brand Store Manager Panel -->
            <div class="bs-managerPanel">
                <!-- Store Manager Panel - Title -->
                <div class="bsmp-title">
                    <div class="bsmp-ttle">Statement</div>
                    <div class="y-balance">
                        <div class="yb-txt">Your Balance:</div>
                        <div class="yb-ammount">$ {{number_format($earning, 2)}}</div>
                    </div>
                </div>
                <div class="bsmp-serachf">
                    {!! Form::open(['url' => 'store/'.$user->username.'/admin/statement']) !!}
                    <div class="form-field">
                        <label>from</label>

                        <div class="form-item">
                            <input type="text" placeholder="{{\Carbon\Carbon::now()->format('Y/m/d')}}" value="{{date('Y/m/d',strtotime($from))}}"
                                   id="from" name="from" required>
                        </div>
                    </div>
                    <div class="form-field">
                        <label>to</label>

                        <div class="form-item">
                            <input type="text" placeholder="{{\Carbon\Carbon::now()->format('Y/m/d')}}" id="to" value="{{date('Y/m/d',strtotime($to))}}"
                                   name="to" required>
                        </div>
                    </div>
                    <div class="form-field">
                        <label>Transaction Type</label>
                        <select name="transaction_type">
                            <option value="">All Transaction Types</option>
                            <option @if($transaction_type == 'credit') selected="selected" @endif value="credit">Credit</option>
                            <option @if($transaction_type == 'debit') selected="selected" @endif value="debit">Debit</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bsmp-btnsf">Search</button>
                    </div>
                    {!! Form::close() !!}
                </div>
                <!-- Brand Store Manager Panel - 6 item Container -->
                <div class="bsmp-6con ttle-6c0n">
                    <div class="bsmp-6item">Date</div>
                    <div class="bsmp-6item item-ref">Reference ID</div>
                    <div class="bsmp-6item">Type</div>
                    <div class="bsmp-6item item-des">Description</div>
                    <div class="bsmp-6item">Price</div>
                    <div class="bsmp-6item">Amount</div>
                </div>

                <!-- Brand Store Manager Panel - 6 item Container -->
                <?php $debit = 0;
                $totalDebits = 0;
                $totalCredit = 0;
                ?>
                @if(count($transactions) > 0)
                    @foreach($transactions as $row)
                        <div class="bsmp-6con">

                            <div class="bsmp-6item">{{getTimeByTZ($row->created_at, 'M. d, Y')}}</div>

                            <div class="bsmp-6item item-ref">
                                @if($row->type == config('constants_brandstore.STATEMENT_TYPES.SALE'))
                                    {{\Vinkla\Hashids\Facades\Hashids::connection('store')->encode($row->id)}}

                                    <a class="vd-link"
                                       href="{{url('store/'.$url_user_id.'/admin/order-invoice/'.$row->parent_id)}}" title="View Details">
                                        View Details
                                    </a>
                                @endif
                            </div>
                            <div class="bsmp-6item">{{config('constants_brandstore.STATEMENT_TYPES_STRING.'.$row->type)}}</div>

                            @if($row->type == config('constants_brandstore.STATEMENT_TYPES.SALE'))
                                <?php $products = getOrderAllProductsDetail($row->parent_id);

                                ?>
                                <div class="bsmp-6item item-des product-detail">
                                    @foreach($products as $product)
                                        <div>{{$product->title}}</div>
                                        <div class="bsmp-6item">{{format_currency($product->price)}}</div>
                                    @endforeach
                                </div>
                                <div class="bsmp-6item">$ {{format_currency($row->amount)}}</div>
                            @elseif($row->type == config('constants_brandstore.STATEMENT_TYPES.WITHDRAW_FEE'))
                                <div class="bsmp-6item item-des">Kinnect2 Fee</div>
                                <div class="bsmp-6item">$ {{format_currency($row->amount)}}</div>

                            @elseif($row->type == config('constants_brandstore.STATEMENT_TYPES.ORDER_SHIPPING_FEE'))
                                <div class="bsmp-6item item-des">Shipping Fee</div>
                                <div class="bsmp-6item">$ {{format_currency($row->amount)}}</div>

                            @else
                                <div class="bsmp-6item item-des">Funds transfer</div>
                                <div class="bsmp-6item">$ {{format_currency($row->amount)}}</div>

                            @endif



                            @if($row->transaction_type == 'credit')
                                <?php $debit = $debit + $row->amount;
                                $totalDebits = $totalDebits + $row->amount;
                                ?>
                                <div class="bsmp-6item green">$ {{format_currency($row->amount)}}</div>
                            @elseif($row->transaction_type == 'debit')
                                <?php $debit = $debit - $row->amount;
                                $totalCredit = $totalCredit + $row->amount;
                                ?>
                                <div class="bsmp-6item red">$ -{{format_currency($row->amount)}}</div>
                            @else
                                <div class="bsmp-6item">$ -{{format_currency($row->amount)}}</div>
                            @endif
                        </div>

                @endforeach

                        <!-- Brand Store Manager Panel - Footer -->
                        <div class="bsmp-footer">
                            <div class="bsmp-period">
                                <div class="bsmp-periodl">Statement Period:</div>
                                <div class="bsmp-periodr">
                                    {{getTimeByTZ($from, 'M. d, Y')}}
                                    to {{getTimeByTZ($to, 'M. d, Y')}}
                                </div>
                            </div>
                            <div class="bsmp-endingBalance">
                                <div class="eb-item">
                                    <div class="eb-iteml">Beginnning Balance:</div>
                                    <div class="eb-itemb">$ {{number_format($beginning_balance,2)}}</div>
                                </div>
                                <div class="eb-item">
                                    <div class="eb-iteml">Total Debits:</div>
                                    <div class="eb-itemr">${{number_format($totalDebits, 2)}}</div>
                                </div>
                                <div class="eb-item">
                                    <div class="eb-iteml">Total Credits:</div>
                                    <div class="eb-itemr">$ {{number_format($totalCredit, 2)}}</div>
                                </div>
                                <div class="eb-item">
                                    <div class="eb-iteml">Ending Balance:</div>
                                    <div class="eb-itemb">${{number_format($debit+$beginning_balance,2)}}</div>
                                </div>
                            </div>

                        </div>
                        @endif
            </div>

        </div>
    </div>
</div>
@endsection
@section('footer-scripts')
    <style>
        .red {
            color: red;
        }

        .green {
            color: green;
        }
    </style>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script>
        $(function(){
            $("#from").datepicker({
                dateFormat : "yy/mm/dd",
                showOn : 'both',
                buttonImage : '{{asset('local/public/assets/images/img-Start-Time.png')}}', //minDate : 0,
                onClose : function(selectedDate){
                    $("#to").datepicker("option", "minDate", selectedDate);
                }
            });

            $("#start_date_icon").click(function(evt){
                evt.preventDefault();
                $("#start_date").click();
            });

            $("#to").datepicker({
                dateFormat : "yy/mm/dd",
                showOn : 'both',
                buttonImage : '{{asset('local/public/assets/images/img-Start-Time.png')}}', //minDate : 0,
                onClose : function(selectedDate){
                    $("#from").datepicker("option", "maxDate", selectedDate);
                    check_dates_2();
                }
            });

            $("#end_date_icon").click(function(evt){
                evt.preventDefault();
                $("#end_date").click();
            });
        });
    </script>
@endsection
