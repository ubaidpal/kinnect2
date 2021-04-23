@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

    @include('Store::includes.store-product-leftside')
    <div class="brand-store">
        @if(is_object($allProducts))
            <div style="clear: both;" class="brand-store-title">
                <span>{{$categoryName }}</span>
            </div>
            @foreach($allProducts as $p)
                <div class="brand-product-item">
                    <div class="range-item-img">
                        <?php $review = getRatings($p->id) ?>
                        <a href="{{url('store/'.$user->username.'/product/'.$p->id )}}">
                            <img src="{!! $p->image !!}" width="210" height="151" alt="img">
                        </a>
                    </div>
                    <div class="range-item-txt mt10">
                        <a href="{{url('store/'.$user->username.'/product/'.$p->id )}}">{{$p->title}}</a>

                        <p>Description {{$p->title}}</p>
                    </div>
                    <div class="range-item-price mt15">
                        <div class="item-price">&dollar;{{$p->price}}</div>
                        @if($isReviewed > 0)
                            <?php $review = getRatings($p->id) ?>
                            <div class="item-rating">
                                <div class="rating-stars">
                                    <?php
                                    $r_review = $review;
                                    $review = $review / 5 * 100;
                                    $review = "width:".$review."%"; ?>
                                    <div class="fill" style="<?php echo $review; ?>;"></div>
                                </div>
                                <div class="rating-value">{{ round($r_review,2) }}</div>
                            </div>
                            <!-- @if($review == 0)
                                    <img class="rated_stars"
                                         src="{!! asset('local/public/assets/images/star.png') !!}"
                                         alt="Rating"/>
                                @endif
                            @for($i=1;$i<=$review;$i++)
                                    <img class="rated_stars"
                                         src="{!! asset('local/public/assets/images/rattingstar.png') !!}"
                                         alt="Rating"/>
                                @endfor -->
                        @else
                            <div class="rating-stars">
                                <div class="fill" style="width: 0px"></div>
                            </div>
                        @endif
                    </div>
                    <?php $user = getUserDetail($url_user_id) ?>
                    <a class="range-item-btn" href="{{url('store/'.$user->username.'/product/'.$p->id )}}">View
                        Details</a>
                </div>
            @endforeach
        @else
            <div style="clear: both;" class="brand-store-title">
                <span>No Product Found..</span>
            </div>
        @endif
    </div>
    @include('includes.ads-right-side')

</div>
<style>

    .rated_stars {
        width: 20px;
        height: 15px
    }
</style>

@endsection
