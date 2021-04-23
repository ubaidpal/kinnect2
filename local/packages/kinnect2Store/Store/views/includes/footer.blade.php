<div class="ftr-main">
    <div class="chat-wrapper" >
        @include('includes.chat')
    </div>
    <div class="ftr-container">
        <div class="footer-nav fltL">
            @if(Auth::check())

                <div class="company-name">&copy; 2014-16.</div>
                <!--   <a href="javascript:void(0);">About</a>
                   <span class="sptr-ftr">|</span>-->
                <a href="{{url('policy/terms')}}">Terms</a>
                <span class="sptr-ftr">|</span>
                <a href="{{url('policy/condition')}}">Privacy</a>
                <span class="sptr-ftr">|</span>
                <a href="http://newsroom.kinnect2.com" target="_blank">Press</a>
                <span class="sptr-ftr">|</span>
                <a href="http://blog.kinnect2.com" target="_blank">Blog</a>

                <!--<span>Kinnect2&trade; Ltd Company Registration Number : SC442762 Date of Incorporation</span>-->

            @else

                <div class="company-name">&copy; 2014-16.</div>
                <a href="{{url('ads/create/package')}}">Create Ad</a>
                <span class="sptr-ftr">|</span>
                <a href="http://blog.kinnect2.com/">Blog</a>
                <span class="sptr-ftr">|</span>
                <a href="http://newsroom.kinnect2.com/">News</a>
                <span class="sptr-ftr">|</span>
                <a href="http://newsroom.kinnect2.com/about-us/">About</a>
                <span class="sptr-ftr">|</span>
                <a href="{{url('pages/help_center')}}">Help Center</a>
                <span class="sptr-ftr">|</span>
                <a href="{{url('policy/terms')}}">Terms</a>
                <span class="sptr-ftr">|</span>
                <a href="{{url('policy/condition')}}">Privacy</a>
                <span class="sptr-ftr">|</span>
                <a href="{{url('policy/condition#cookies')}}">Cookies</a>

            @endif
        </div>
    </div>
</div>
