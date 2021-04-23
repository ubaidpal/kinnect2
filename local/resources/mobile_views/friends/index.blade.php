@extends('layouts.default')
@section('content')
        <!-- Title Bar -->

<div class="title-bar">
    <span>Received Friend Requests</span>
</div>

<!-- Brands Container -->
<div class="brands-container">

    <!-- Round Img Container -->
    <div class="round-img-container">
        <!-- Round Img Item -->
        @if(count($requests) > 0)
            @foreach($requests as $row)
                <div class="round-img-item">
                    <div class="round-img-contnr">
                        <a class="round-img" href="{{url(\Kinnect2::profileAddress($row))}}">
                            <img src="{{Kinnect2::getPhotoUrl($row->photo_id, $row->id, 'user', 'thumb_normal')}}"
                                 alt="img">
                        </a>
                    </div>
                    <div class="round-img-title">
                        <a class="round-title-txt"
                           href="{{url(\Kinnect2::profileAddress($row))}}">{{$row->displayname}}</a>
                    </div>
                    <div class="">
                        <a class="btn-round-img friend-toggle-requests" href="{{URL::to('friends/confirm/'.$row->resource_id)}}">Confirm</a>
                        <a class="btn-round-img" href="{{URL::to('friends/delete/'.$row->resource_id)}}">Delete</a>
                    </div>
                </div>
            @endforeach
        @else
        No more request
        @endif

    </div>

    <!-- Button Show More -->
   {{-- <div class="brand-btn">
        <a class="btn" href="javascript:void(0)">Show More</a>
    </div>--}}
</div>

<!-- Title Bar -->
<div class="title-bar">
    <span>People You May Know</span>
</div>

<!-- Recommended Brands Container -->
<div class="round-img-container">
    <!-- Round Img Item -->
    @if(count($all_recommended) > 0)
        @foreach($all_recommended as $row)
            <div class="round-img-item">
                <div class="round-img-contnr">
                    <a class="round-img" href="{{url(\Kinnect2::profileAddress($row))}}">
                        <img src="{{Kinnect2::getPhotoUrl($row->photo_id, $row->id, 'user', 'thumb_normal')}}"
                             alt="img">
                    </a>
                </div>
                <div class="round-img-title">
                    <a class="round-title-txt" href="{{url(\Kinnect2::profileAddress($row))}}">{{$row->displayname}}</a>
                </div>
                <div class="">
                    <a class="btn-round-img friend-toggle" href="{{URL::to('friends/add-friend/'.$row->id)}}">Add</a>
                </div>
            </div>
        @endforeach
    @else
    No more request
    @endif
</div>
@endsection
@section('footer-scripts')
    <script src="{!! asset('local/public/assets/js/inner-pages.js') !!}"></script>
    {!! HTML::script('local/public/assets/js/searchAndPagination.js') !!}
    <script type="text/javascript">

    </script>
@endsection
