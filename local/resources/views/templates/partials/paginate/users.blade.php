{{--

    * Created by   :  Muhammad Yasir
    * Project Name : kinnect2
    * Product Name : PhpStorm
    * Date         : 26-1-16 4:19 PM
    * File Name    : 

--}}
@if($type == 'recommended-brands')
@foreach( $brands as $brand)
        <!-- Post Div-->
@if($brand->brand_detail)
    <div class="myBrands" id="brand_{{$brand->id}}">
        <a href="{{url(Kinnect2::profileAddress($brand))}}"
           title="{{ ucwords($brand->displayname) }}">
            <img src="{{Kinnect2::getPhotoUrl($brand->photo_id, $brand->id, 'brand', 'thumb_normal')}}"
                 width="134" height="134"
                 alt="Apple">
        </a>
        <a href="{{\Kinnect2::profileAddress($brand)}}"
           title="{{ ucwords($brand->displayname) }}"
           class="bName">{{ ucwords($brand->displayname) }}</a>


        <span>{{ Kinnect2::brand_kinnectors($brand->id) }} Followers</span>
        <a href="javascript:void(0);" onclick="follow_b({{$brand->id}})"
           title="Click to Follow {{ ucwords($brand->displayname) }}" id="btn_{{$brand->id}}"
           class="btn follow">Follow</a>
    </div>

    @endif
    @endforeach
    <?php echo $brands->render(); ?>
    @elseif($type == 'following')
    @foreach($brands as $brand)
            <!-- Post Div-->
    <div class="myBrands" id="brand_{{$brand->id}}">
        <a href="{{url(Kinnect2::profileAddress($brand))}}"
           title="{{ ucwords($brand->displayname) }}">
            <img src="{{Kinnect2::getPhotoUrl($brand->photo_id, $brand->id, 'user', 'thumb_normal')}}"
                 width="134" height="134" alt="Apple">
        </a>
        <a href="{{Kinnect2::profileAddress($brand)}}"
           title="{{ ucwords($brand->displayname) }}"
           class="bName">{{ ucwords($brand->displayname) }} </a>
        <span>{{ Kinnect2::brand_kinnectors($brand->id) }} Followers</span>
        <a href="javascript:void(0);" onclick="un_follow({{$brand->id}})"
           title="Click to Un-Follow {{ ucwords($brand->displayname) }}"
           id="btn_{{$brand->id}}" class="btn">Un-Follow</a>
    </div>
    @endforeach
    <?php echo $brands->render(); ?>
@elseif($type == 'kinnectors')
    @foreach($kinnectors as $row)
        <div class="myBrands">
            <div class="img">
                <a href="{{url(\Kinnect2::profileAddress($row))}}">
                    <img src="{{Kinnect2::getPhotoUrl(($row->photo_id=='')?0:$row->photo_id, $row->user_id, 'user', 'thumb_normal')}}"
                         alt="image">
                </a>
            </div>
            <div class="tag-post">
                <div class="tag">
                    <a class="bName" href="{{url(\Kinnect2::profileAddress($row))}}">
                        {{$row->displayname}}
                    </a>
                </div>
            </div>
            <div class="battles-btn">

                @if($user->id == Auth::user()->id)
                    <a class="btn friend-toggle" href="{{URL::to('friends/unfriend/'.$row->user_id)}}">
                        <span class="del-battle"></span>Unfriend
                    </a>
                @endif
            </div>
        </div>
    @endforeach
    <?php echo $kinnectors->render(); ?>
@elseif($type == 'followers')
    @foreach($followers as $brand)
        <div class="myBrands" id="brand_{{$brand->id}}">
            <a href="{{url(Kinnect2::profileAddress($brand))}}" title="{{ ucwords(ucwords($brand->displayname)) }}">
                <img src="{{Kinnect2::getPhotoUrl($brand->photo_id, $brand->id, 'user', 'thumb_normal')}}"
                     width="134" height="134" alt="Apple">
            </a>
            <a href="{{Kinnect2::profileAddress($brand)}}"
               title="{{ ucwords($brand->displayname) }}"
               class="bName">{{ ucwords(ucwords($brand->displayname)) }} </a>
            @if(Auth::user()->id == $userId)
                <a href="javascript:void(0);" onclick="remove_follower({{$brand->id}},event)"
                   title="Click to Remove {{ ucwords(ucwords($brand->displayname)) }}"
                   id="btn_{{$brand->id}}" class="btn">Remove</a>
            @endif
        </div>
    @endforeach
    <?php echo $followers->render(); ?>

@else
    @foreach($all_recommended as $row)

        <div class="myBrands ">
            <div class="img">
                <a href="{{URL::to('friends/request/'.$row->id)}}" class="btn-delet"></a>
                <a href="{{url(\Kinnect2::profileAddress($row))}}">
                    <img src="{{Kinnect2::getPhotoUrl(($row->photo_id=='')?0:$row->photo_id, $row->user_id, 'user', 'thumb_normal')}}"
                         alt="image">
                </a>
            </div>
            <div class="tag-post">
                <div class="tag"><a class="bName"
                                    href="{{url(\Kinnect2::profileAddress($row))}}">{{$row->displayname}}</a>
                </div>
            </div>
            <div class="battles-btn">
                @if($row->resource['user_id'] == Auth::user()->id && $row->resource['active'] == 0 && $row->resource['user_approved'] == 1)
                    <a class="btn btn-add-friend friend-toggle" href="{{URL::to('friends/delete/'.$row->id)}}">
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
    <?php echo $all_recommended->render(); ?>
@endif
