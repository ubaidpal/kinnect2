{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 12-11-15 11:27 AM
    * File Name    : 

--}}
<div class="content-gray-title mb10">
    <h4>Kinnectors</h4>

    <span class="fltR">
        <input placeholder="Type for search" title="Type and press enter" type="text" value="" name="search" data-type="kinnectors" data-UserId="{{$user->id}}" class="search-peoples">
    </span>
</div>
<div style="text-align: center;display: none " id="loading-content" class="search-loader">
    <img alt="Loading..." src="{{asset('local/public/images/loading.gif')}}" id="loading-image">
</div>
@if(@$permission['view_permissions'])
    @if(count($data['friends']) > 0)
        <div class="all-brands paginate-data" data-url="{{url('kinnectors-paginate')}}">
            @foreach($data['friends'] as $row)
                <div class="myBrands">
                    <div class="img">
                        <a href="{{url(\Kinnect2::profileAddress($row))}}">
                            <img src="{{Kinnect2::getPhotoUrl(($row->photo_id=='')?0:$row->photo_id, $row->user_id, 'user', 'thumb_normal')}}" alt="image">
                        </a>
                    </div>
                    <div class="tag-post">
                        <div class="tag"><a class="bName"
                                            href="{{url(\Kinnect2::profileAddress($row))}}">{{$row->displayname}}</a>
                        </div>
                        {{-- <div class="posted-by">Professional Model</div>
                         <div class="post-date"><a href="javascript:void(0);">Paul</a> s a mutual friend</div>--}}
                    </div>
                    <div class="battles-btn">
                        <!--  @if($row->resource_approved == 1)
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
                            <a class="btn friend-toggle noToggleBtn" href="{{URL::to('friends/unfriend/'.$row->user_id)}}">
                                <span class="del-battle"></span>Unfriend
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
            <?php echo $data['friends']->render(); ?>
        </div>
    @else
        <div class="myBrands" style="width: 100%"> {{$user->displayname}} have no kinnector yet.</div>
    @endif
@else
    <div class="my-battles">{{Config::get('constants.NOT_AUTHORIZED')}}</div>
@endif
