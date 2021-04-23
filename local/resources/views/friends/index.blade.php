@extends('layouts.default')
@section('content')
    <?php
    use App\Repository\Eloquent\FriendshipRepository;
    ?>

    <div class="content-gray-title mb10">
        <h4>Received Friend Requests</h4>
        <a class="btn fltR" title="View sent Requests" href="{{URL::to('friends/sent-request')}}">View sent Requests</a>
    </div>

    <!-- Post Div-->
    @if(count($requests) > 0)
        @foreach($requests as $row)
            <div class="myBrands">
                <div class="img">
                    <a href="{{url(\Kinnect2::profileAddress($row))}}">
                        <img src="{{Kinnect2::getPhotoUrl($row->photo_id, $row->user_id, 'user', 'thumb_normal')}}"
                             alt="image">
                    </a>
                </div>
                <div class="tag-post">
                    <div class="tag"><a class="bName"
                                        href="{{url(\Kinnect2::profileAddress($row))}}">{{$row->displayname}}</a></div>
                    {{-- <div class="posted-by">Professional Model</div>
                     <div class="post-date"><a href="javascript:void(0);">Paul</a> s a mutual friend</div>--}}
                </div>
                <div class="battles-btn">
                    <!--@if($row->resource_approved == 1)
                            <a class="btn btn-orange" href="{{URL::to('friends/unfollow/'.$row->user_id.'#kinnectors')}}">
                            Unfollow
                        </a>
                    @else
                            <a class="btn btn-orange" href="{{URL::to('friends/follow/'.$row->user_id.'#kinnectors')}}">
                            <span class="open-confirm"></span>
                            Follow
                        </a>
                    @endif -->
                    @if($user->id == Auth::user()->id)
                        <a class="orngBtn friend-toggle-requests"
                           href="{{URL::to('friends/confirm/'.$row->resource_id)}}">
                            <span class="open-confirm"></span>Confirm
                        </a>
                        <a class="btn" href="{{URL::to('friends/delete/'.$row->resource_id)}}">
                            <span class="del-battle"></span>Delete
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="my-battles" style="height: auto; border: none">You have no new request!</div>
    @endif

    <div class="clrfix"></div>
    <div id="all_recommended">
        <div class="content-gray-title mb10">
            <h4>People You May Know</h4>
             <span class="fltR">
            <input placeholder="Type for search" title="Type and press enter" type="text" value="" name="search"
                   data-type="all_recommended" data-UserId="{{$user_id}}" class="search-peoples">
            </span>
        </div>
        <div style="text-align: center;display: none " id="loading-content" class="search-loader">
            <img alt="Loading..." src="{{asset('local/public/images/loading.gif')}}" id="loading-image">
        </div>
        <?php
        //echo '<tt><pre>'; print_r($all_recommended); die;
        ?>
        @if(count($all_recommended) > 0)
            <div class="browse-battle paginate-data" data-url="{{url('all-recommended')}}">

                @foreach($all_recommended as $row)

                    <div class="myBrands ">
                        <div class="img">
                            <!--<a href="{{URL::to('friends/request/'.$row->id)}}" class="btn-delet"></a>-->
                            <a href="{{url(\Kinnect2::profileAddress($row))}}">
                                <img src="{{Kinnect2::getPhotoUrl($row->photo_id, $row->id, 'user', 'thumb_normal')}}"
                                     alt="image">
                            </a>
                        </div>
                        <div class="tag-post">
                            <div class="tag">
                                <a class="bName"
                                                href="{{url(\Kinnect2::profileAddress($row))}}">{{$row->displayname}}</a>
                            </div>
                            {{-- <div class="posted-by">Professional Model</div>
                             <div class="post-date"><a href="javascript:void(0);">Paul</a> s a mutual friend</div>--}}
                        </div>
                        <div class="battles-btn">
                            <!--@if($row->resource_approved == 1)
                                    <a class="btn btn-orange" href="{{URL::to('friends/unfollow/'.$row->user_id.'#kinnectors')}}">
                                    Unfollow
                                </a>
                            @else
                                    <a class="btn btn-orange" href="{{URL::to('friends/follow/'.$row->user_id.'#kinnectors')}}">
                                    <span class="open-confirm"></span>
                                    Follow
                                </a>
                            @endif -->
                            @if($row->resource['user_id'] == Auth::user()->id && $row->resource['active'] == 0 && $row->resource['user_approved'] == 1)
                                <a class="btn btn-add-friend friend-toggle"
                                   href="{{URL::to('friends/delete/'.$row->id)}}">
                                    Cancel Request
                                </a>
                            @else
                                <a class="btn orngBtn  btn-add-friend friend-toggle"
                                   href="{{URL::to('friends/add-friend/'.$row->id)}}">
                                    Add Kinnector
                                </a>
                            @endif
                        </div>
                    </div>

                @endforeach
                {!! $all_recommended->render() !!}
            </div>
        @else
            <div class="browse-battle-item" style="height: auto">No record fond!</div>
        @endif
    </div>
@endsection
@section('footer-scripts')
    <script src="{!! asset('local/public/assets/js/inner-pages.js') !!}"></script>
    {!! HTML::script('local/public/assets/js/searchAndPagination.js') !!}
    <script type="text/javascript">

    </script>
@endsection
