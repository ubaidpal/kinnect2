@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.user-profile-banner')

<div class="mainCont">
    @include('includes.main-left-side')
    <div class="profile-content">

        <div class="post">
            <form method="" action="" id="">
                <input type="text" placeholder="What would you like to post?" alt="" maxlength="" size="60" id="" name="" autocomplete="on">
            </form>
            <div class="shareDiv">
                <a href="javascript:void(0);" title="Upload">Upload</a>
                <a href="javascript:void(0);" title="Share">Share</a>
            </div>
        </div>

        <div class="post-box brand">
            <div class="user-name-pic">
                <a href="javascript:void(0);">
                    <img src="{!! asset('local/public/assets/images/profile.jpg') !!}" width="55" height="55" alt="User Name"/>
                    <div>
                        <span>Paul Smith</span>
                        <em title="">31 Aug 15 at 7:00 am</em>
                    </div>
                </a>
            </div>
            <div class="posted-text">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            </div>
            <div class="media" href="javascript:void(0);" title="Image">
                <img src="{!! asset('local/public/assets/images/SGS_slide_1-690x300.jpg') !!}" width="auto" height="auto" alt="Image" />
            </div>
            <div class="feed-options">
                <a href="javascript:void(0);" title="Like" class="like"></a>
                <a href="javascript:void(0);" title="Dislike" class="dislike"></a>
                <a href="javascript:void(0);" title="Favourite" class="favourite"></a>
                <a href="javascript:void(0);" title="Share" class="share"></a>
                <a href="javascript:void(0);" title="Social" class="social-link"></a>
                <a href="javascript:void(0);" title="Flag" class="flag"></a>
            </div>
            <div class="otherLikes">
                <a href="javascript:void(0);" title="name">Jessica shire</a>&sbquo; <a href="javascript:void(0);">Peter John</a> and <a href="javascript:void(0);">50 others</a> like this.
            </div>
            <div class="post-write-comment">

                <div class="options-detail">1000 Likes | 500 Dislikes | 50,000 Comments | 1000 Shares</div>
                <div class="comment-pnl">
                    <a href="javascript:void(0);" title="username" class="user-image"><img src="{!! asset('local/public/assets/images/profile.jpg') !!}" width="45" height="45" alt="" title="user" /></a>
                    <div class="comment-text">
                        <a class="commentor-name" href="javascript:void(0);">Peter John</a>
                        <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                        <span class="like"><a href="javascript:void(0);"></a> &ndash; 500 Like this</span>
                        <span class="date">15 April at 10:24pm</span>
                    </div>
                </div>
                <div class="comment-pnl">
                    <a href="javascript:void(0);" title="username" class="user-image"><img src="{!! asset('local/public/assets/images/profile.jpg') !!}" width="45" height="45" alt="" title="user" /></a>
                    <div class="comment-text">
                        <a class="commentor-name" href="javascript:void(0);">Peter John</a>
                        <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                        <span class="like"><a href="javascript:void(0);"></a> &ndash; 500 Like this</span>
                        <span class="date">15 April at 10:24pm</span>
                    </div>
                </div>

                <div class="write-comment">
                    <a href="javascript:void(0);" title="user name" class="user-image"><img src="{!! asset('local/public/assets/images/profile.jpg') !!}" width="45" height="45" title="username" /></a>
                    <form method="" action="" id="">
                        <input type="text" placeholder="Write Comment" alt="" maxlength="" size="60" id="" name="" autocomplete="off">
                    </form>
                    <a href="javascript:void(0);" class="orngBtn" title="Comment">Comment</a>
                </div>
            </div>
        </div>
        <div class="post-box">
            <div class="user-name-pic">
                <a href="javascript:void(0);">
                    <img src="{!! asset('local/public/assets/images/profile.jpg') !!}" width="55" height="55" alt="User Name"/>
                    <div>
                        <span>Paul Smith</span>
                        <em title="date">31 Aug 15 at 7:00 am</em>
                    </div>
                </a>
            </div>
            <div class="posted-text">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            </div>
            <div class="media" href="javascript:void(0);" title="Image">
                <img src="{!! asset('local/public/assets/images/SGS_slide_1-690x300.jpg') !!}" width="auto" height="auto" alt="Image" />
            </div>
            <div class="feed-options">
                <a href="javascript:void(0);" title="Like" class="like"></a>
                <a href="javascript:void(0);" title="Dislike" class="dislike"></a>
                <a href="javascript:void(0);" title="Favourite" class="favourite"></a>
                <a href="javascript:void(0);" title="Share" class="share"></a>
                <a href="javascript:void(0);" title="Social" class="social-link"></a>
                <a href="javascript:void(0);" title="Flag" class="flag"></a>
            </div>
            <div class="otherLikes">
                <a href="javascript:void(0);" title="name">Jessica shire</a>&sbquo; <a href="javascript:void(0);">Peter John</a> and <a href="javascript:void(0);">50 others</a> like this.
            </div>
            <div class="post-write-comment">

                <div class="options-detail">1000 Likes | 500 Dislikes | 50,000 Comments | 1000 Shares</div>
                <div class="comment-pnl">
                    <a href="javascript:void(0);" title="username" class="user-image"><img src="{!! asset('local/public/assets/images/profile.jpg') !!}" width="45" height="45" alt="" title="user" /></a>
                    <div class="comment-text">
                        <a class="commentor-name" href="javascript:void(0);">Peter John</a>
                        <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                        <span class="like"><a href="javascript:void(0);"></a> &ndash; 500 Like this</span>
                        <span class="date">15 April at 10:24pm</span>
                    </div>
                </div>
                <div class="comment-pnl">
                    <a href="javascript:void(0);" title="username" class="user-image"><img src="{!! asset('local/public/assets/images/profile.jpg') !!}" width="45" height="45" alt="" title="user" /></a>
                    <div class="comment-text">
                        <a class="commentor-name" href="javascript:void(0);">Peter John</a>
                        <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                        <span class="like"><a href="javascript:void(0);"></a> &ndash; 500 Like this</span>
                        <span class="date">15 April at 10:24pm</span>
                    </div>
                </div>

                <div class="write-comment">
                    <a href="javascript:void(0);" title="user name" class="user-image"><img src="{!! asset('local/public/assets/images/profile.jpg') !!}" width="45" height="45" title="username" /></a>
                    <form method="" action="" id="">
                        <input type="text" placeholder="Write Comment" alt="" maxlength="" size="60" id="" name="" autocomplete="off">
                    </form>
                    <a href="javascript:void(0);" class="orngBtn" title="Comment">Comment</a>
                </div>
            </div>
        </div>
    </div>
    @include('includes.ads-right-side')
</div>
@endsection
@section('footer-scripts')
    <script src="{!! asset('local/public/assets/js/inner-pages.js') !!}"></script>
@endsection
