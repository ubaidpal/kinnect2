@extends('layouts.store-admin')
@section('content')
        <!-- Post Div-->
@include('admin.layout.arbitrator-leftnav')
<div class="ad_main_wrapper">
    <div class="task_inner_wrapper">
        <div class="main_heading">
            <h1>Sales & Accounts</h1>
            <form action="{{url('admin/transactions')}}" method="get" id="serachFrom">
                <a class="orngBtn fltR search-btn" href="#">Search</a>
                <input value="{{$key}}" class="search" name="key" type="text" placeholder="Type store name">
            </form>
        </div>
        <div class="assigned-task-wrapper">
            <div class="user-table heading">
                <div class="name">Store Name</div>
                <div class="email">Last 30 Days Sales</div>
                <div class="role">Total Orders</div>
                <div class="action">Account Balance</div>
            </div>
            @foreach($transactions as $transaction)
            <div class="user-table">
                <div class="name"><span>{{$transaction->user_id->brand_name}}</span></div>
                <div class="email">$&nbsp;{{format_currency($transaction->last_month_sales)}}</div>
                <div class="role">{{$transaction->total_orders}}</div>
                <div class="action">$&nbsp;{{format_currency($transaction->balance)}}</div>
            </div>
            @endforeach
        </div>
        <div class="pagination">
            {!! $transactions->render() !!}
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).on('click','.search-btn',function (e) {
       e.preventDefault();
       jQuery('#serachFrom').submit();
    });
</script>
@endsection