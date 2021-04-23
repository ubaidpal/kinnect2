@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.store-banner')		

<div class="mainCont">

@include('includes.store-admin-leftside')

<div class="product-Analytics">   
	<div class="post-box">
    	<h1>Product Analytics</h1>
        <img width="872" height="1340" alt="" src="{!! asset('local/public/assets/images/analytics-img.jpg') !!}">
    </div>
</div>
</div>
@endsection