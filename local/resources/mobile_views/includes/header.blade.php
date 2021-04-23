<!-- Main Nav -->
<div class="nav-container" data-url="{{url('/')}}" id="nav-container">

    <div class="icon-item">
        <a class="icon-image" href="javascript:void(0)">
            <div class="img-icm setng @if(Request::is('/')) active @endif">
            </div>
        </a>
    </div>

    <div class="icon-item">
        <a class="icon-image" href="{{url('/')}}">
            <div class="img-icm dshbrd @if(Request::is('/')) active @endif">
            </div>
        </a>
    </div>

    <div class="icon-item">
        <a class="icon-image" href="{{url('messages')}}">
            <div id="inbox" class="img-icm msg @if(Request::is('messages')) active @endif">

                <?php $unread = \TBMsg::getNumOfUnreadMsgs(Auth::user()->id);?>
                @if($unread > 0)
                    <span class="img-icm-badge">{{$unread}}</span>
                @endif
            </div>
        </a>
    </div>

    <div class="icon-item">
        <a class="icon-image" href="{{url('friends/request')}}">
            <div id="kinnector-noti" class="img-icm alrt @if(Request::is('friends/*')) active @endif">
                <?php $friend_request = get_friend_request_noti(Auth::user()->id)?>
                @if($friend_request > 0)
                    <span class="img-icm-badge">{{ $friend_request }}</span>
                @endif
            </div>
        </a>
    </div>

    <div class="icon-item">
        <a class="icon-image" href="{{url('notification')}}">
            <div id="notification-box" class="img-icm frnds @if(Request::is('notification')) active @endif">
                <?php $notification_count = get_notification_count(Auth::user()->id); ?>
                @if($notification_count > 0)
                    <span class="img-icm-badge" id="notification">{{ $notification_count }}</span>
                @endif

            </div>
        </a>
    </div>

    <div class="icon-item">
        <a class="icon-image" href="{{url('advanced_search')}}" id="">
            <div class="img-icm srch">
            </div>
        </a>
    </div>


</div>



<div class="mSideMenuOverLay" style="display:none;"></div>
<div class="mSideMenu">
    <!-- mobile side menu - Item -->
    <div class="msMenu-item msMenu-profile">
        <a class="msMenu-click" href="javascript:void(0)">
            <div class="msMenu-img">
                <img width="100%" src="{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_icon')}}" alt="img">
            </div>
            <div class="msMenu-title">
                <span>
                    @if(Auth::user()->userable_type == \Config::get('constants.BRAND_USER'))
                        {{ ucwords( $user_detail->brand_name ) }}
                    @else
                        {{ ucwords( Auth::user()->displayname ) }}
                    @endif
                    </span>
            </div>
        </a>
    </div>
    <!-- mobile side menu - Item -->
    <div class="msMenu-item">
        <a class="msMenu-click" href="{{url('brands/manage')}}">
            <div class="msMenu-img myBrand"></div>
            <div class="msMenu-title"><span>My Brands</span></div>
        </a>
    </div>
    <!-- mobile side menu - Item -->
    <div class="msMenu-item">
        <a class="msMenu-click" href="{{url('groups/manage')}}">
            <div class="msMenu-img groups"></div>
            <div class="msMenu-title"><span>Groups</span></div>
        </a>
    </div>
    <!-- mobile side menu - Item -->
    <div class="msMenu-item">
        <a class="msMenu-click" href="{{url('polls')}}">
            <div class="msMenu-img polls"></div>
            <div class="msMenu-title"><span>Polls</span></div>
        </a>
    </div>
    <!-- mobile side menu - Item -->
    <div class="msMenu-item">
        <a class="msMenu-click" href="{{url('battles/manage')}}">
            <div class="msMenu-img btlBrands"></div>
            <div class="msMenu-title"><span>Battle</span></div>
        </a>
    </div>

    <!-- Title Bar -->
    {{--<div class="title-bar">
        <span>Apps</span>
    </div>--}}

    <!-- mobile side menu - Item -->
    <div class="msMenu-item">
        <a class="msMenu-click" href="{{url('logout')}}">
            <div class="msMenu-img logout"></div>
            <div class="msMenu-title"><span>Logout</span></div>
        </a>
    </div>

</div>

{{--<div class="search hide" style="display: none; width: 100%">
    <div class="search-field">
        {!! Form::open(['url' => 'advanced_search','method' => 'get']) !!}
        <input type="search" value="" name="search_term" placeholder="Search" autocomplete="off">
        <input type="submit" value="search">
        {!! Form::close() !!}
    </div>

</div>--}}
