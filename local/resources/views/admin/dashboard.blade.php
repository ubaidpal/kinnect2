@extends('admin.layout.store-admin')
@section('content')
        <!-- Post Div-->
@include('admin.layout.arbitrator-leftnav')
@include('admin.alert.alert')
<div class="ad_main_wrapper" id="ad_main_wrapper">
    <div class="task_inner_wrapper">
        <div class="main_heading">
            <h1>DashBoard</h1>
        </div>
        <style>
            .box-size {
                border-radius: 8px;
                font-size: 10px;
                width: 207px;
                height: 80px;

            }

            .box-align {
                color: #fff;
                text-align: center;
				display:block;
				
            }

            .left-box {
                background-color: #fff;
                width: 400px;
                margin-top: 16px;
                border-radius: 5px;
                float: left;
            }

            .right-box {
                background-color: #fff;
                width: 382px;
                margin-top: 16px;
                border-radius: 5px;
                float: left;
                margin-left: 90px;
            }
            .amount{
                color: #fff;
                font-size: 18px;
				display:block;
				text-align:center;
            }
        </style>
        <div class="user-table heading">
            <div class="box-size" style="background-color: #447dad">
                <span class="box-align">Total Sales</span><br/>
                <span class="amount">&dollar;{{format_currency($totalSaleSum)}}</span>
            </div>
            <div class="box-size" style="margin-left: 10px;background-color: #7d6ba1">
                <span class="box-align">Reversals</span><br/>
                <span class="amount">&dollar;{{$totalReversals}}</span>
            </div>
            <div class="box-size" style="margin-left: 10px;background-color: #cc4444">
                <span class="box-align">Withdrawal Fee</span><br/>
                <span class="amount">&dollar;{{$withdrawalFees}}</span>
            </div>
            <div class="box-size" style="margin-left: 10px;background-color: #3a9c96">
                <span class="box-align">Dispute Fee</span><br/>
                <span class="amount">{{$totalDisputeFees}}</span>
            </div>

        </div>

        <div class="left-box">
            <div class="user-table heading">
                <div class="name">Top 10 Selling Brands</div>
            </div>

            <div class="user-table">
                <div class="name" style="width: 100%">
                    @foreach($topTenBrands as $brandInfo)
                        <?php $brandInfo = explode("+_+", $brandInfo); ?>
                        <span>{{$brandInfo[0]}}</span>
                        <span class="email" style="float:right">{{$brandInfo[1]}}</span>
                        <br>
                        <hr>
                    @endforeach
                </div>

            </div>
        </div>

        <div class="right-box">
            <div class="user-table heading">
                <div class="name">Quick Stats</div>
            </div>

            <div class="user-table">
                <div class="name"><span>Total Brand</span>
                    <span class="email" style="float:right">{{$totalBrandsCount}}</span>
                </div>
                <div class="name"><span>Total Consumer</span>
                    <span class="email" style="float:right">{{$totalConsumersCount}}</span>
                </div>
                <div class="name"><span>Total Product</span>
                    <span class="email" style="float:right">{{$totalProductsCount}}</span>
                </div>
                <div class="name"><span>Open Claims</span>
                    <span class="email" style="float:right">{{$openClaimsCount}}</span>
                </div>
                <div class="name"><span>Resolved Claims</span>
                    <span class="email" style="float:right">{{$resolvedClaimsCount}}</span>
                </div>
                <div class="name"><span>Open Dispute</span>
                    <span class="email" style="float:right">{{$openDisputeCount}}</span>
                </div>
                <div class="name"><span>Accepted Dispute</span>
                    <span class="email" style="float:right">{{$acceptedDisputeCount}}</span>
                </div>
                <div class="name"><span>Rejected Dispute</span>
                    <span class="email" style="float:right">{{$rejectedDisputeCount}}</span>
                </div>

            </div>
        </div>
		
        <div class="clrfix"></div>
    </div>
</div>

@endsection
