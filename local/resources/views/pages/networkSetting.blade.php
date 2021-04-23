@extends('layouts.masterDynamic')
@section('content')
    @include('includes.setting-left-nav')
	<!--Create Album-->
<div class="community-ad">
    <div class="settings form-container">
    	<form action="">
        <div class="setting-title">
            <span>Network Settings</span>
        </div>

        <div class="setting-block cf">
            <div class="setting-block-item">
                <div class="field-item mt15">
                    <label for="">Available Networks</label>
                    <p class="col-dark">
                        To add a new network, begin typing its name below.
                    </p>
                </div>
            </div>

            <div class="setting-block-item">
                <div class="field-item mt15">
                    <label for="">My Networks</label>
                    <p class="col-dark">
                        You belong to 0 networks.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="network-filter-block mt15">
            <div class="field-item">
                <input type="text" placeholder="Start typing to filter">
            </div>

            <div class="network-filter-item">
                <div class="network-name">Africa</div>
                <a href="javascript:();" class="btn btn-network">Join Network</a>
            </div>

            <div class="network-filter-item">
                <div class="network-name">Antarctica</div>
                <a href="javascript:();" class="btn btn-network">Join Network</a>
            </div>

            <div class="network-filter-item">
                <div class="network-name">Asia</div>
                <a href="javascript:();" class="btn btn-network">Join Network</a>
            </div>

            <div class="network-filter-item">
                <div class="network-name">Australia</div>
                <a href="javascript:();" class="btn btn-network">Join Network</a>
            </div>

            <div class="network-filter-item">
                <div class="network-name">Africa</div>
                <a href="javascript:();" class="btn btn-network">Join Network</a>
            </div>

            <div class="network-filter-item">
                <div class="network-name">Antarctica</div>
                <a href="javascript:();" class="btn btn-network">Join Network</a>
            </div>

            <div class="network-filter-item">
                <div class="network-name">Asia</div>
                <a href="javascript:();" class="btn btn-network">Join Network</a>
            </div>

            <div class="network-filter-item">
                <div class="network-name">Australia</div>
                <a href="javascript:();" class="btn btn-network">Join Network</a>
            </div>
        </div>

    </form>	
	</div>
</div>
@endsection