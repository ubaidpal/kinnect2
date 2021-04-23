{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 11-11-15 4:24 PM
    * File Name    : 

--}}
@extends('layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('includes.user-brand-profile-banner')

<div class="mainCont">
    @include('includes.main-left-side')


    <div class="profile-content target" id="kinnectors">
        <div class="content-gray-title mb10">
            <h4>Kinnectors</h4>
        </div>
        <?php
        //echo '<tt><pre>'; print_r($data); die;
        ?>
        @foreach($kinnectors as $row)
            <div class="my-battles">
                <div class="img">
                    <a href="javascript:void(0);">
                        <img src="{!! asset('local/public/assets/images/friend-request-1.jpg') !!}" alt="image">
                    </a>
                </div>
                <div class="tag-post">
                    <div class="tag"><a href="{{Kinnect2::profileAddress($row)}}">{{$row->name}}</a></div>
                    <div class="posted-by">Professional Model</div>
                    <div class="post-date"><a href="javascript:void(0);">Paul</a> s a mutual friend</div>
                </div>
                <!-- <div class="battles-btn">
            @if($row->resource_approved == 1)
                        <a class="btn btn-orange" href="{{URL::to('friends/unfollow/'.$row->user_id.'#kinnectors')}}">
                    Unfollow
                </a>
            @else
                        <a class="btn btn-orange" href="{{URL::to('friends/follow/'.$row->user_id.'#kinnectors')}}">
                    <span class="open-confirm"></span>
                    Follow
                </a>
            @endif
                        <a class="btn" href="{{URL::to('friends/unfriend/'.$row->user_id)}}"><span class="del-battle"></span>Unfriend</a>
        </div> -->
            </div>
        @endforeach

    </div>

    @include('includes.ads-right-side')
</div>
@endsection
@section('footer-scripts')
    <script src="{!! asset('local/public/assets/js/inner-pages.js') !!}"></script>
@endsection

