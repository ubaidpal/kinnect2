<div class="header-main" data-url="{{URL::to('/')}}">
    <div class="header-container">
        <a class="logo" title="Kinnect2" href="{{url('/home')}}"></a>
        <ul>
            <li class="profile-tooltip admin">
                <a class="profile" href="javascript:void(0);" id="profileLink"
                   data-username="{{Auth::user()->username}}" data-socket="{{Auth::user()->id}}">
                    {{ ucwords( Auth::user()->displayname ) }}
                </a>
                <!--<div id = "popUp"></div>-->
                <div id="popUpText">
					<a href="{{url('/logout')}}" title="Signout">Signout</a>
                </div>
            </li>
        </ul>
    </div>
</div>