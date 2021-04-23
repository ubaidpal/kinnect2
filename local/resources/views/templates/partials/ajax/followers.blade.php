{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 12-11-15 11:27 AM
    * File Name    : 

--}}
<div class="content-gray-title mb10">
    <h4>Followers</h4>
    <span class="fltR">
        <input placeholder="Type for search" title="Type and press enter" type="text" value="" name="search" data-type="followers" data-UserId="{{$user->id}}" class="search-peoples">
    </span>
</div>
<div style="text-align: center;display: none " id="loading-content" class="search-loader">
    <img alt="Loading..." src="{{asset('local/public/images/loading.gif')}}" id="loading-image">
</div>
<?php
//echo '<tt><pre>'; print_r($data); die;
?>
@if(count($data['friends']) > 0)
    <div class="all-brands paginate-data" data-url="{{url('followers-paginate')}}">
        @foreach($data['friends'] as $brand)
            <div class="myBrands" id="brand_{{$brand->id}}">
                <a href="{{url(Kinnect2::profileAddress($brand))}}" title="{{ ucwords(ucwords($brand->displayname)) }}">
                    <img src="{{Kinnect2::getPhotoUrl($brand->photo_id, $brand->id, 'user', 'thumb_normal')}}"
                         width="134" height="134" alt="Apple">
                </a>
                <a href="{{Kinnect2::profileAddress($brand)}}"
                   title="{{ ucwords($brand->displayname) }}"
                   class="bName">{{ ucwords(ucwords($brand->displayname)) }} </a>
                @if(Auth::user()->id == $user->id)
                    <a href="javascript:void(0);" onclick="remove_follower({{$brand->id}},event)"
                       title="Click to Remove {{ ucwords(ucwords($brand->displayname)) }}"
                       id="btn_{{$brand->id}}" class="btn">Remove</a>
                @endif
            </div>
        @endforeach
        <?php echo $data['friends']->render(); ?>
    </div>
@else
    <div class="myBrands" style="width: 100%"> {{$user->displayname}} have no Follower yet.</div>
@endif
