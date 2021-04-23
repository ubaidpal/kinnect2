{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 12-11-15 11:27 AM
    * File Name    : 

--}}
<div class="content-gray-title mb10">
    <h4>Kinnectors</h4>

    {{--<span class="fltR">
        <input placeholder="Type for search" title="Type and press enter" type="text" value="" name="search" data-type="kinnectors" data-UserId="{{$user->id}}" class="search-peoples">
    </span>--}}
</div>
<div style="text-align: center;display: none " id="loading-content" class="search-loader">
    <img alt="Loading..." src="{{asset('local/public/images/loading.gif')}}" id="loading-image">
</div>
@if(@$permission['view_permissions'])
    @if(count($data['friends']) > 0)
        <div class="round-img-container all-brands paginate-data" data-url="{{url('kinnectors-paginate')}}">
            @foreach($data['friends'] as $row)
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
                        @if($user->id == Auth::user()->id)
                            <a class="btn-round-img friend-toggle noToggleBtn" href="{{URL::to('friends/unfriend/'.$row->user_id)}}">Unfriend</a>
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
