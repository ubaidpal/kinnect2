@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')  

<div class="mainCont">

@include('includes.store-admin-leftside')

<div class="product-Analytics">   
<div class="post-box">



<!-- Brand Store Manager Panel -->
<div class="bs-managerPanel">
    <!-- Store Manager Panel - Title -->
    <div class="bsmp-title">
        <div class="bsmp-ttle">Statement</div>
        <div class="y-balance">
            <div class="yb-txt">Your Balance:</div>
            <div class="yb-ammount">$2000.60</div>
        </div>
    </div>
	 <!-- Brand Store Manager Panel - Search Field -->
    <div class="bsmp-serachf">
        <form>
            <div class="form-field">
                <label>from</label>
                <div class="form-item">
                    <input type="text" placeholder="01/10/2014">
                </div>
            </div>
            <div class="form-field">
                <label>to</label>
                <div class="form-item">
                    <input type="text" placeholder="01/10/2014">
                    <span class="cl-icon"></span>
                </div>
            </div>
            <div class="form-field">
                <label>Transaction Type</label>
                <select>
                    <option value="">All Transaction Types</option>
                </select>
            </div>
            <div>
                <a class="bsmp-btnsf">Search</a>
            </div>
        </form>
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
    <div class="bsmp-6con">
        <div class="bsmp-6item">Oct. 13 2014</div>
        <div class="bsmp-6item item-ref">64125090856047<a class="vd-link" href="javascript:void(0)">View Details</a></div>
        <div class="bsmp-6item">Withdrawal Fee</div>
        <div class="bsmp-6item item-des">55" JU6800 6 Series Flat UHD 4K Nano Crystal Smart TV</div>
        <div class="bsmp-6item">$10000.30</div>
        <div class="bsmp-6item">$10000.30</div>
    </div>

    <!-- Brand Store Manager Panel - 6 item Container -->
    <div class="bsmp-6con">
        <div class="bsmp-6item">Oct. 13 2014</div>
        <div class="bsmp-6item item-ref"></div>
        <div class="bsmp-6item">Withdrawal Fee</div>
        <div class="bsmp-6item item-des">55" JU6800 6 Series Flat UHD 4K Nano Crystal Smart TV</div>
        <div class="bsmp-6item">$10000.30</div>
        <div class="bsmp-6item">$10000.30</div>
    </div>

    <!-- Brand Store Manager Panel - 6 item Container -->
    <div class="bsmp-6con">
        <div class="bsmp-6item">Oct. 13 2014</div>
        <div class="bsmp-6item item-ref"></div>
        <div class="bsmp-6item">Withdrawal</div>
        <div class="bsmp-6item item-des">Funds transfer to<br>johndoe@email.com via Escrow</div>
        <div class="bsmp-6item">$10000.30</div>
        <div class="bsmp-6item">$10000.30</div>
    </div>

    <!-- Brand Store Manager Panel - 6 item Container -->
    <div class="bsmp-6con">
        <div class="bsmp-6item">Oct. 13 2014</div>
        <div class="bsmp-6item item-ref">64125090856047<a class="vd-link" href="javascript:void(0)">View Details</a></div>
        <div class="bsmp-6item">Sale Reversal</div>
        <div class="bsmp-6item item-des">Reversal of 55" JU6800 6 Series Flat UHD 4K Nano Crystal Smart TV</div>
        <div class="bsmp-6item">$10000.30</div>
        <div class="bsmp-6item">$10000.30</div>
    </div>

    <!-- Brand Store Manager Panel - 6 item Container -->
    <div class="bsmp-6con">
        <div class="bsmp-6item">Oct. 13 2014</div>
        <div class="bsmp-6item item-ref">64125090856047<a class="vd-link" href="javascript:void(0)">View Details</a></div>
        <div class="bsmp-6item">Sale</div>
        <div class="bsmp-6item item-des">55" JU6800 6 Series Flat UHD 4K Nano Crystal Smart TV</div>
        <div class="bsmp-6item">$10000.30</div>
        <div class="bsmp-6item">$10000.30</div>
    </div>

    <!-- Brand Store Manager Panel - Footer -->
    <div class="bsmp-footer">
        <div class="bsmp-period">
            <div class="bsmp-periodl">Statement Period:</div>
            <div class="bsmp-periodr">Oct. 01 2014 to Oct. 15 2014</div>
        </div>
        <div class="bsmp-endingBalance">
            <div class="eb-item">
                <div class="eb-iteml">Beginnning Balance:</div>
                <div class="eb-itemb">$1000.30</div>
            </div>
            <div class="eb-item">
                <div class="eb-iteml">Total Debits:</div>
                <div class="eb-itemr">$2000.60</div>
            </div>
            <div class="eb-item">
                <div class="eb-iteml">Total Credits:</div>
                <div class="eb-itemr">$2000.60</div>
            </div>
            <div class="eb-item">
                <div class="eb-iteml">Ending Balance:</div>
                <div class="eb-itemb">$2000.60</div>
            </div>
        </div>

    </div>
</div>

</div>
</div>
</div>
@endsection