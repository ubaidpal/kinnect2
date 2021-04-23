<div class="header-main" data-url="{{URL::to('/')}}">
    <div class="header-container">
        <a class="logo" title="Kinnect2" href="{{url('/home')}}"></a>
        <ul>
            <li class="mr20">
                <form id="" action="" method="">
                    <input type="text" autocomplete="off" class="search" name="search" id="search" size="20"
                           maxlength="100" alt="" placeholder="Search">
                    <a class="sIcon" title="search" href="javascript:void(0);"></a>

                    <div class="search-dropdown" id="results"></div>
                </form>
            </li>
            <li class="header-menu">
                <a class="dashboard" title="Dashboard" href="{{url('/home', $parameters = [], $secure = null)}}"></a>
                <a class="inbox" title="Inbox" href="{{url('messages')}}" id="inbox">
                    <?php $unread = \TBMsg::getNumOfUnreadMsgs(Auth::user()->id);?>
                    @if($unread > 0)
                        <span>{{$unread}}</span><!-- new message span-->
                    @endif
                </a>

                <a class="notifications click-button" id="notifications-area" data-target="kNotificationDialog"
                   title="Notifications"
                   href="#" rel="toggle" data-hide="false" data-ajax="true">
                    <?php $notification_count = get_notification_count(Auth::user()->id); ?>
                    @if($notification_count > 0)
                        <span id="notification-header">{{ $notification_count }}</span>
                    @endif
                </a>

                <div class="pulldown_contents_wrapper" role="dialog" id="kNotificationDialog">
                    <div class="pulldown_contents">
                        <ul id="notifications_menu" class="notifications_menu">

                        </ul>
                    </div>

                    <div class="pulldown_options">
                        <a id="notifications_viewall_link" href="{{action('NotificationController@showNotification')}}">View
                            All Updates</a> <a id="notifications_markread_link" href="{{url('mark-all-read')}}">Mark All
                            Read</a></div>
                </div>
                <a id="kinnector-noti" class="invite" title="Invite Kinnectors" href="{{URL::to('friends/request')}}">
                    <?php $friend_request = get_friend_request_noti(Auth::user()->id)?>
                    @if($friend_request > 0)
                        <span>{{ $friend_request }}</span>
                    @endif
                </a>
                <a class="favourite" title="Your Favourites Feeds" href="{{ URL::to('favourites')}}"></a>

                @if(Auth::user()->user_type == 1 && env('STORE_ENABLED'))

                    <a class="cart" id="the_cart" title="Go to your Shopping Cart" href="{{url('store/cart')}}">
                        <?php
                        $product_count = Session::get('cart.total_items');
                        ?>
                        @if($product_count > 0)
                            <span class="skore">{{$product_count}}</span>
                        @endif
                    </a>
                @endif

            </li>
            <li title="sKore" class="header-menu cursor_pointer" onClick="window.location.href = '{{Url::to('skore')}}'">sKore:
                <span class="skore-digits">{{number_format(Auth::user()->skore)}}</span></li>
            <li class="profile-tooltip">
                <a class="profile" href="javascript:void(0);" id="profileLink"
                   data-username="{{Auth::user()->username}}" data-socket="{{Auth::user()->id}}"
                   data-timezone="{{Auth::user()->timezone}}">
                    <img width="23" height="23" alt="Name"
                         src="{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_icon')}}">
                    {{ ucwords( Auth::user()->displayname ) }}
                </a>
                <!--<div id = "popUp"></div>-->
                <div id="popUpText">
                    @if(Auth::user()->user_type == 1)
                        <a href="{{\App\Facades\Kinnect2::profileAddress(Auth::user())}}" title="Profile">Profile</a>

                        @if( env("STORE_ENABLED", true))
                            <a href="{{url('store/my-orders')}}" title="My Orders">My Orders</a>
                        @endif

                        <a href="{{url('settings/general')}}" title="Settings">Settings</a>
                        <a href="{{url('/logout')}}" title="Signout">Signout</a>
                    @endif

                    @if(Auth::user()->user_type == 2 )

                        <a href="{{url('/brand')}}/{{ Auth::user()->username }}" title="Profile">Profile</a>
                        @if( env("STORE_ENABLED", true) && Auth::user()->brand_detail->store_created > 0)
                            <a href="{{url('/store')}}/{{ Auth::user()->username }}"
                               title="Goto your store dashboard">Store Preview</a>
                            <a href="{{url('store/'.Auth::user()->username.'/admin/orders')}}"
                               title="Orders">Orders<span>{{getPendingOrders(Auth::user()->id)}}</span></a>
                            <a href="{{url('/store')}}/{{ Auth::user()->username }}/admin/store-earnings"
                               title="Goto your store total earnings">Earnings<span>&dollar;{{getAvailableBalance( Auth::user()->id)}}</span></a>
                        @endif
                        <a href="{{url('/settings/general')}}" title="Settings">Settings</a>
                        <a href="{{url('/logout')}}" title="Signout">Signout</a>
                    @endif

                </div>
            </li>
        </ul>
    </div>
</div>

<script>
    $(window).load(function () {
        $(this).click(function (e) {
            if (e.target.id == 'search') {
                $('#results').show();
            }
            else {
                $('#results').hide();
            }
        });
        $('#search').keyup(function () {
            var searchField = $('#search').val();
            var regex = new RegExp(searchField, "i");
            var output = '<div class="search-dropdown-block">';
            var isBrand = 0;
            var isUser = 0;
            var url = '{{url('search')}}';
            $.getJSON(url + '/' + searchField, function (data) {
                $.each(data, function (key, val) {

                    if (val.userable_type == 'App\\Brand') {
                        isBrand = isBrand + 1;
                    }
                    if (val.userable_type == 'App\\Consumer') {
                        isUser = isUser + 1;
                        if (isUser == 1) {
                            output += '<div class="search-separator">';
                            output += '<span>Users</span>';
                            output += '</div>';
                        }
                        output += '<a class="search-dropdown-item" href="{{url('profile')}}/' + val.username + '">';
                        output += '<div class="item-img fltL">';
                        output += '<img width="48" height="48" src="' + val.image + '" title="' + val.name + '" alt="img">';
                        output += '</div>';
                        output += '<div class="item-txt fltL">';
                        output += '<span>' + val.name + '</span>' +
                                '</div>';
                        output += '</a>';
                    }
                });

                if (isBrand > 0) {
                    output += '<div class="search-separator">';
                    output += '<span>Brands</span>';
                    output += '</div>';
                }

                $.each(data, function (key, val) {
                    if (val.userable_type == 'App\\Brand') {
                        output += '<a class="search-dropdown-item" href="{{url('brand')}}/' + val.username + '">';
                        output += '<div class="item-img fltL">';
                        output += '<img width="48" height="48" src="' + val.image + '" title="' + val.name + '" alt="img">';
                        output += '</div>';
                        output += '<div class="item-txt fltL">';
                        output += '<span>' + val.displayname + '</span>' +
                                '</div>';
                        output += '</a>';
                        isBrand = isBrand + 1;
                    }
                });
                output += '<div class="search-btn">';
                if (isBrand > 0 || isUser > 0) {
                    output += '<a class="orngBtn" href="{{url('advanced_search')}}">Search More Results</a>';
                } else {
                    output += '<a class="btn" style="float: none;" href="javascript:void(0);">No Results were found</a>';
                }
                output += '</div>';
                $('#results').show();
                $('#results').html(output);
            });
        });
    });
</script>

{{--@if(empty(Auth::user()->timezone))--}}
    {!! HTML::script('local/public/assets/js/timeZone.js') !!}
    <script>
        $(document).ready(function(){
            $('#inbox').click(function(e){
                e.preventDefault();
                var tz = jstz.determine(); // Determines the time zone of the browser client
                var timezone = tz.name(); //'Asia/Kolhata' for Indian Time.
                var url              = $(this).attr('href');
                window.location.href = url + '?timezone=' + encodeURIComponent(timezone);
            })
        })
    </script>
{{--@endif--}}
