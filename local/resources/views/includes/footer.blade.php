<div class="ftr-main">
    <div class="ftr-container">
        <div class="footer-nav fltL">
            @if(Auth::check())

                <!--<div class="company-name">&copy; 2014-16.</div>
                <a href="javascript:void(0);">About</a>
                   <span class="sptr-ftr">|</span>
                <a href="{{url('policy/terms')}}">Terms</a>
                <span class="sptr-ftr">|</span>
                <a href="{{url('policy/condition')}}">Privacy</a>
                <span class="sptr-ftr">|</span>
                <a href="http://newsroom.kinnect2.com" target="_blank">Press</a>
                <span class="sptr-ftr">|</span>
                <a href="http://blog.kinnect2.com" target="_blank">Blog</a>

                <span>Kinnect2&trade; Ltd Company Registration Number : SC442762 Date of Incorporation</span>-->

            @else

                <div class="company-name">&copy; 2014-{{date('y')}}</div>
                <a href="{{url('login/ads')}}" title="Create Ad">Create Ad</a>
                <span class="sptr-ftr">|</span>
                <a href="http://blog.kinnect2.com/" title="Blog">Blog</a>
                <span class="sptr-ftr">|</span>
                <a href="http://newsroom.kinnect2.com/" title="News">News</a>
                <span class="sptr-ftr">|</span>
                <a href="http://newsroom.kinnect2.com/about-us/" title="About">About</a>
                <span class="sptr-ftr">|</span>
                <a href="{{url('pages/help_center')}}" title="Help Center">Help Center</a>
                <span class="sptr-ftr">|</span>
                <a href="{{url('policy/terms')}}" title="Terms">Terms</a>
                <span class="sptr-ftr">|</span>
                <a href="{{url('policy/condition')}}" title="Privacy">Privacy</a>
                <span class="sptr-ftr">|</span>
                <a href="{{url('policy/condition#cookies')}}" title="Cookies">Cookies</a>

            @endif
        </div>
    </div>
</div>
<div class="chat-wrapper not-ready offline">
    @include('includes.chat')
</div>