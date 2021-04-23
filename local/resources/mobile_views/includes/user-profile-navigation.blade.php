{{--

    * Created by   :  Muhammad Yasir
    * Project Name : kinnect2
    * Product Name : PhpStorm
    * Date         : 17-Feb-2016 7:37 PM
    * File Name    : 

--}}
<div class="sub-nav-container" data-user="{{$user->username}}" data-url="{{url('profile/profile-view')}}" data-loggedinusertype="{{Auth::user()->user_type}}" data-profileinusertype="{{$user->user_type}}">
    <a class="sub-nav-item" href="javascript:void(0)">
        <div class="sub-nav-txt tab active" title="Info"  data-target="mvc-main" data-ajax="true">What's New</div>
    </a>
   {{-- <a class="sub-nav-item" href="javascript:void(0)">
        <div class="sub-nav-txt active tab" title="Activity Log" data-target="activity-log"
             data-ajax="true">Activity Log</div>
    </a>--}}
    <a class="sub-nav-item" href="javascript:void(0)">
        <div class="sub-nav-txt tab" title="Info"  data-target="info" data-ajax="true">Info</div>
    </a>

    <a class="sub-nav-item" href="javascript:void(0)">
        <div class="sub-nav-txt tab" title="Kinnectors"  data-target="kinnectors" data-ajax="true">Kinnectors({{@$friends}})</div>
    </a>

    <div class="subnav-edit">
        <a class="btn-subnav-edit trigger" href="javascript:void(0)"></a>

        <div class="subnav-edit-pp drop hide">
            <ul>
                <li><a class="subnav-pp-item tab" href="javascript:void(0)" title="Following"  data-target="following" data-ajax="true">Following({{@$following}})</a></li>
                @if($user->user_type == Config::get('constants.BRAND_USER'))
                    <li><a class="subnav-pp-item" href="javascript:void(0)">Followers({{@$followers}})</a></li>
                @endif
                {{--<li><a class="subnav-pp-item" href="javascript:void(0)">Album({{album_count($user->id)}})</a></li>
                <li><a class="subnav-pp-item" href="javascript:void(0)">Info</a></li>

                <li><a class="subnav-pp-item" href="javascript:void(0)">Edit Profile</a></li>--}}
            </ul>
        </div>
    </div>
</div>
