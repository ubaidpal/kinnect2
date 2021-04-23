{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 12-11-15 11:27 AM
    * File Name    : 

--}}
<div class="content-gray-title mb10">
    <h4>Following</h4>
    <span class="fltR">
        <input placeholder="Type for search" title="Type and press enter" type="text" value="" name="search"
               data-type="following" data-UserId="{{$user->id}}" class="search-peoples">
    </span>
</div>
<div style="text-align: center;display: none " id="loading-content" class="search-loader">
    <img alt="Loading..." src="{{asset('local/public/images/loading.gif')}}" id="loading-image">
</div>
{{--@if(@$permission['view_permissions'])--}}

@if(count($following) > 0)
    <div class="all-brands paginate-data" data-url="{{url('all-my-brand')}}">
        @foreach($following as $brand)
            <div class="myBrands" id="brand_{{$brand->id}}">
                <a href="{{url(Kinnect2::profileAddress($brand))}}" title="{{ ucwords(ucwords($brand->displayname)) }}">
                    <img src="{{Kinnect2::getPhotoUrl($brand->photo_id, $brand->id, 'user', 'thumb_normal')}}"
                         width="134" height="134" alt="Apple">
                </a>
                <a href="{{Kinnect2::profileAddress($brand)}}"
                   title="{{ ucwords($brand->displayname) }}"
                   class="bName">{{ ucwords(ucwords($brand->displayname)) }} </a>
                <span>{{ Kinnect2::brand_kinnectors($brand->id) }} Followers</span>
                @if(Auth::user()->id == $user->id)
                    <a href="javascript:void(0);" onclick="un_follow({{$brand->id}},event)"
                       title="Click to Un-Follow {{ ucwords(ucwords($brand->displayname)) }}"
                       id="btn_{{$brand->id}}" class="btn">Un-Follow</a>
                @endif
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
