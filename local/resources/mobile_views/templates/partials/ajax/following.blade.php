{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 12-11-15 11:27 AM
    * File Name    : 

--}}
<div class="content-gray-title mb10">
    <h4>Following</h4>
   {{-- <span class="fltR">
        <input placeholder="Type for search" title="Type and press enter" type="text" value="" name="search"
               data-type="following" data-UserId="{{$user->id}}" class="search-peoples">
    </span>--}}
</div>
<div style="text-align: center;display: none " id="loading-content" class="search-loader">
    <img alt="Loading..." src="{{asset('local/public/images/loading.gif')}}" id="loading-image">
</div>
{{--@if(@$permission['view_permissions'])--}}

@if(count($following) > 0)
    <div class=" round-img-container all-brands paginate-data" data-url="{{url('all-my-brand')}}">
        @foreach($following as $row)



            <div class="round-img-item" id="brand_{{$row->id}}">
                <div class="round-img-contnr">
                    <a class="round-img" href="{{url(\Kinnect2::profileAddress($row))}}">
                        <img src="{{Kinnect2::getPhotoUrl($row->photo_id, $row->id, 'user', 'thumb_normal')}}"
                             alt="img">
                    </a>
                </div>

                <div class="round-img-title">
                    <a class="round-title-txt" title="{{ ucwords($row->displayname) }}"
                       href="{{url(\Kinnect2::profileAddress($row))}}">{{$row->displayname}}</a>
                </div>
                <div class="">
                    @if($user->id == Auth::user()->id)
                        <a onclick="un_follow({{$row->id}},event)" title="Click to Un-Follow {{ ucwords(ucwords($row->displayname)) }}" class="btn-round-img friend-toggle noToggleBtn" href="{{URL::to('friends/unfriend/'.$row->user_id)}}" id="btn_{{$row->id}}">Un-Follow</a>
                    @endif
                </div>
            </div>
        @endforeach

        <?php echo $following->render(); ?>
    </div>

@else
    <div class="myBrands" style="width: 100%"> {{$user->displayname}} are not following any brand yet!</div>
@endif
{{--
@else
    <div class="my-battles">{{Config::get('constants.NOT_AUTHORIZED')}}</div>
@endif--}}
