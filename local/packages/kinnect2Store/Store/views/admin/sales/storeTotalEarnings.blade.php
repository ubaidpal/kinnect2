@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

    @include('Store::includes.store-admin-leftside')

    <div class="product-Analytics">
        <div class="post-box">
            <h1>Total Earnings</h1>
            <div class="total-earning-wrapper">
                <div class="total-earning-box">
                    <div class="earning-title">Your Balance:</div>
                    <div class="earning-value"><h1>&dollar;@if(!empty($availableBalance)){{format_currency($availableBalance)}} @else 0.00 @endif</h1></div>
                    <div class="earning-link"><a href="{{url('store/withdrawals')}}" title="Withdraw money">Withdraw money &raquo;</a></div>
                </div>
                <div class="total-earning-box"> <?php $month_name = ''; if(isset($currentMonthSales['month_name'])){$month_name = $currentMonthSales['month_name'];} ?>
                    <div class="earning-title">Sales earnings this month ( {{$month_name}}):</div>
                    <div class="earning-value"><h1>&dollar;@if(!empty($currentMonthSales['thisMonthSales'])){{format_currency($currentMonthSales['thisMonthSales'])}} @else 0.00 @endif</h1></div>
                    <div class="earning-link"><a href="{{ url('store/'.$user->username.'/admin/statement') }}" title="View detail">View detail</a></div>
                </div>
                <div class="total-earning-box w_200">
                    <div class="earning-title">Total Sales:</div>
                    <div class="earning-value"><h1>&dollar;@if(!empty($totalSales)){{format_currency($totalSales)}} @else 0.00 @endif</h1></div>
                    <div class="earning-link"><a href="{{ url('store/'.$user->username.'/admin/statement') }}" title="View detail">View detail</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
