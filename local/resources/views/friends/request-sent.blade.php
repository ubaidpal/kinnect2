@extends('layouts.default')
@section('content')
<?php
use App\Repository\Eloquent\FriendshipRepository;
?>


        <!--Friend Requests-->
<div class="content-gray-title mb10">
    <h4>My Sent Requests</h4>
    <a class="btn fltR" title="View sent Requests" href="{{URL::to('friends/request')}}">People you may know</a>
</div>
<!-- Post Div-->
@if($sent_requests)
    @foreach($sent_requests as $row)

        <div class="myBrands">
            <div class="img">
                <a href="{{url(\Kinnect2::profileAddress($row))}}">
                    <img src="{{Kinnect2::getPhotoUrl(($row->photo_id=='')?0:$row->photo_id, $row->user_id, 'user', 'thumb_normal')}}" alt="image">
                </a>
            </div>
            <div class="tag-post">
                <div class="tag"><a class="bName" href="{{url(\Kinnect2::profileAddress($row))}}">{{$row->displayname}}</a></div>
               {{-- <div class="posted-by">Professional Model</div>
                <div class="post-date"><a href="javascript:void(0);">Paul</a> s a mutual friend</div>--}}
            </div>
            <div class="battles-btn">
                <div class="battles-btn">
                    <a  class="btn friend-toggle btn-add-friend" href="{{URL::to('friends/delete/'.$row->user_id)}}">
                        <span class="del-battle"></span>Cancel Request
                    </a>
                </div>
            </div>
        </div>
    @endforeach


    @endsection
@section('footer-scripts')
    <script src="{!! asset('local/public/assets/js/inner-pages.js') !!}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            var btn = $('.friend-toggle');
            btn.click(function (e) {
                e.preventDefault();
                var url = $(this).attr('href');

                var $this = $(this);
                $this.text('Please Wait...');
                $this.attr('href', '');
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function (data) {
                        if (data == 'success') {
                            $this.parent().parent().remove();
                        } else {
                            $this.text('Send Request');
                            $this.attr('href', url);
                        }
                    }
                });
            });
        });
    </script>
    @else
        <div class="my-battles"> You have no request sent!</div>
    @endif
@endsection
