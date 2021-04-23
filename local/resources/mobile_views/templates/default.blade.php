<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ ucwords( Auth::user()->name ) }}: Dashboard</title>
    <link href="{!! asset('local/public/css/style.css') !!}" rel="stylesheet">

</head>
<body>
<!-- Header start here-->

<div class="header-main">
    <div class="header-container">
        <a href="javascript:void(0);" title="Kinnect2" class="logo"></a>
        <ul>
            <li class="mr20">
                <form method="" action="" id="">
                    <input type="text" placeholder="Search" alt="" maxlength="100" size="20" id="" name="" class="search" autocomplete="on">
                    <ul class="tag-autosearch" style="z-index: 42; opacity: 0;"></ul>
                    <a href="javascript:void(0);" title="search" class="sIcon"></a>
                </form>
            </li>
            <li class="header-menu">
                <a href="javascript:void(0);" title="Dashboard" class="dashboard"></a>
                <a href="javascript:void(0);" title="Inbox" class="inbox">
                    <span>99</span><!-- new message span-->
                </a>
                <a href="javascript:void(0);" title="Notifications" class="notifications"></a>
                <a href="javascript:void(0);" title="Invite Kinnectors" class="invite"></a>
                <a href="javascript:void(0);" title="Your Favourites Feeds" class="favourite"></a>
                <a href="javascript:void(0);" title="Kinnect2 Koins" class="koins"></a>
            </li>
            <li class="profile-tooltip">
                <a href="javascript:void(0);" class="profile" id="profileLink">
                    <img src="{!! asset('local/public/images/profile.jpg') !!}" width="23" height="23" alt="Name" />
                    {{ ucwords( Auth::user()->name ) }}
                </a>
                <div id = "popUp"></div>
                <div id = "popUpText">
                    <a href="javascript:void(0);" title="Profile">Profile</a>
                    <a href="javascript:void(0);" title="Settings">Settings</a>
                    <a href="javascript:void(0);" title="Signout">Signout</a>
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- Header end here-->
<div class="mainContainer">
    <!-- Left Panel start here-->
    <div id="stick" class="leftPnl">
        <div>
            <div class="box">
                <!-- My Brands-->
                <div class="heading bIcon">My Brands</div>
                <div class="brands">
                    <a href="javascript:void(0);"><img src="{!! asset('local/public/images/imperialtex_5128_666220.jpeg') !!}" width="70" height="70" alt="Imperial" title="Imperial" /></a>
                    <a href="javascript:void(0);"><img src="{!! asset('local/public/images/BlueOrcaStudios_5109_538961.jpeg') !!}" width="70" height="70" alt="Blue Orca" title="Blue Orca" /></a>
                    <a href="javascript:void(0);"><img src="{!! asset('local/public/images/imperialtex_5128_666220.jpeg') !!}" width="70" height="70" alt="Imperial" title="Imperial" /></a>
                    <a href="javascript:void(0);"><img src="{!! asset('local/public/images/BlueOrcaStudios_5109_538961.jpeg') !!}" width="70" height="70" alt="Blue Orca" title="Blue Orca" /></a>
                    <a href="javascript:void(0);"><img src="{!! asset('local/public/images/imperialtex_5128_666220.jpeg') !!}" width="70" height="70" alt="Imperial" title="Imperial" /></a>
                    <a href="javascript:void(0);"><img src="{!! asset('local/public/images/BlueOrcaStudios_5109_538961.jpeg') !!}" width="70" height="70" alt="Blue Orca" title="Blue Orca" /></a>
                </div>
                <a href="{{ url('/auth/logout') }}" title="View All My Brands" class="btn mrgn mtMin5">View All My Brands</a>

                <!-- Recomended Brands-->
                <div class="links_head">Recomended Brands</div>
                <div class="brands">
                    <a href="javascript:void(0);"><img src="{!! asset('local/public/images/imperialtex_5128_666220.jpeg') !!}" width="70" height="70" alt="Imperial" title="Imperial" /></a>
                    <a href="javascript:void(0);"><img src="{!! asset('local/public/images/BlueOrcaStudios_5109_538961.jpeg') !!}" width="70" height="70" alt="Blue Orca" title="Blue Orca" /></a>
                    <a href="javascript:void(0);"><img src="{!! asset('local/public/images/imperialtex_5128_666220.jpeg') !!}" width="70" height="70" alt="Imperial" title="Imperial" /></a>
                    <a href="javascript:void(0);"><img src="{!! asset('local/public/images/BlueOrcaStudios_5109_538961.jpeg') !!}" width="70" height="70" alt="Blue Orca" title="Blue Orca" /></a>
                    <a href="javascript:void(0);"><img src="{!! asset('local/public/images/imperialtex_5128_666220.jpeg') !!}" width="70" height="70" alt="Imperial" title="Imperial" /></a>
                    <a href="javascript:void(0);"><img src="{!! asset('local/public/images/BlueOrcaStudios_5109_538961.jpeg') !!}" width="70" height="70" alt="Blue Orca" title="Blue Orca" /></a>
                </div>
                <a href="javascript:void(0);" title="View All My Brands" class="btn mrgn mtMin5">View All Recomended Brands</a>
            </div>
            <!-- Gruops-->
            <div class="box">
                <div class="heading gIcon">Groups</div>
                <a href="javascript:void(0);" title="Create Group" class="btn mrgn">Create Group</a>
                <a href="javascript:void(0);" title="View All My Groups" class="btn mrgn">View All My Groups</a>
                <div class="links_head">Recomended Groups</div>
                <a href="javascript:void(0);" title="View All Recomended Groups" class="btn mrgn">View All Recomended Groups</a>
            </div>

            <!-- Polls-->
            <div class="box">
                <div class="heading pIcon">Polls</div>
                <a href="javascript:void(0);" title="Create Poll" class="btn mrgn">Create Poll</a>
                <a href="javascript:void(0);" title="View All Polls" class="btn mrgn">View All Polls</a>
                <div class="links_head">Recomended Polls</div>
                <a href="javascript:void(0);" title="View All Polls" class="btn mrgn">View All Polls</a>
            </div>

            <!-- Battle of The Brands-->
            <div class="box">
                <div class="heading btlIcon">Battle of the Brands</div>
                <a href="javascript:void(0);" title="Create Battle" class="btn mrgn">Create Battle</a>
                <div class="links_head">Recomended Battles</div>
                <a href="javascript:void(0);" title="" class="battle-question">Whis is the real monster?</a>
                <a href="javascript:void(0);" title="" class="battle-question">Vote a brand of your choice</a>
                <a href="javascript:void(0);" title="View All Battles" class="btn mrgn">View All Battles</a>
            </div>

        </div>
    </div>
    <!-- Left Panel end here-->

    <!--Middle Content-->
    <div class="content">
        <!-- Post Div-->
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
                    <img src="{!! asset('local/public/images/profile.jpg') !!}" width="55" height="55" alt="User Name"/>
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
                <img src="{!! asset('local/public/images/SGS_slide_1-690x300.jpg') !!}" width="auto" height="auto" alt="Image" />
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
                    <a href="javascript:void(0);" title="username" class="user-image"><img src="{!! asset('local/public/images/profile.jpg') !!}" width="45" height="45" alt="" title="user" /></a>
                    <div class="comment-text">
                        <a class="commentor-name" href="javascript:void(0);">Peter John</a>
                        <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                        <span class="like"><a href="javascript:void(0);"></a> &ndash; 500 Like this</span>
                        <span class="date">15 April at 10:24pm</span>
                    </div>
                </div>
                <div class="comment-pnl">
                    <a href="javascript:void(0);" title="username" class="user-image"><img src="{!! asset('local/public/images/profile.jpg') !!}" width="45" height="45" alt="" title="user" /></a>
                    <div class="comment-text">
                        <a class="commentor-name" href="javascript:void(0);">Peter John</a>
                        <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                        <span class="like"><a href="javascript:void(0);"></a> &ndash; 500 Like this</span>
                        <span class="date">15 April at 10:24pm</span>
                    </div>
                </div>

                <div class="write-comment">
                    <a href="javascript:void(0);" title="user name" class="user-image"><img src="{!! asset('local/public/images/profile.jpg') !!}" width="45" height="45" title="username" /></a>
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
                    <img src="{!! asset('local/public/images/profile.jpg') !!}" width="55" height="55" alt="User Name"/>
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
                <img src="{!! asset('local/public/images/SGS_slide_1-690x300.jpg') !!}" width="auto" height="auto" alt="Image" />
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
                    <a href="javascript:void(0);" title="username" class="user-image"><img src="{!! asset('local/public/images/profile.jpg') !!}" width="45" height="45" alt="" title="user" /></a>
                    <div class="comment-text">
                        <a class="commentor-name" href="javascript:void(0);">Peter John</a>
                        <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                        <span class="like"><a href="javascript:void(0);"></a> &ndash; 500 Like this</span>
                        <span class="date">15 April at 10:24pm</span>
                    </div>
                </div>
                <div class="comment-pnl">
                    <a href="javascript:void(0);" title="username" class="user-image"><img src="{!! asset('local/public/images/profile.jpg') !!}" width="45" height="45" alt="" title="user" /></a>
                    <div class="comment-text">
                        <a class="commentor-name" href="javascript:void(0);">Peter John</a>
                        <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                        <span class="like"><a href="javascript:void(0);"></a> &ndash; 500 Like this</span>
                        <span class="date">15 April at 10:24pm</span>
                    </div>
                </div>

                <div class="write-comment">
                    <a href="javascript:void(0);" title="user name" class="user-image"><img src="{!! asset('local/public/images/profile.jpg') !!}" width="45" height="45" title="username" /></a>
                    <form method="" action="" id="">
                        <input type="text" placeholder="Write Comment" alt="" maxlength="" size="60" id="" name="" autocomplete="off">
                    </form>
                    <a href="javascript:void(0);" class="orngBtn" title="Comment">Comment</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Right Ad Panel -->

    <div class="adPanel">
        <div class="bandsNadds">
            <div class="homesorting">
                <ul>
                <span class="sorting_list_bg">
                    <li class="sort_active_link" id=""><a title="All Activities" href="">All</a></li>
                    <li id="brand_filter_feeds"><a title="All Brands" href="">Brands</a></li>
                </span>
                    <select id="my_selection">
                        <option title="All Posts" href="" id="">More</option>
                        <option title="All Groups" href="">Groups</option>
                        <option title="All Battles" href="" id="">Battles</option>
                        <option title="All Polls" href="" id="">Polls</option>
                        <option title="All Vidoes" href="" id="">Videos</option>
                        <option title="All Music" href="" id="">Audio</option>
                    </select>
                </ul>
            </div>

            <div class="head">
                <a href="javascript:void(0);" title="Create an Ad">Create an Ad</a>
                <div class="fltR"><a href="javascript:void(0);">More Ads</a></div>
            </div>
        </div>
        <div class="adsDiv" id="stickAdd">
            <a href="javascript:void(0);" target="_blank"><img src="{!! asset('local/public/images/ad-right-panel/bmw.jpg') !!}" width="170" height="auto" alt="BMW" title="BMW" /></a>
            <a href="javascript:void(0);" target="_blank"><img src="{!! asset('local/public/images/ad-right-panel/samsung.jpg') !!}" width="170" height="auto" alt="Samsung" title="Samsung" /></a>
            <a href="javascript:void(0);" target="_blank"><img src="{!! asset('local/public/images/ad-right-panel/olx.jpg') !!}" width="170" height="auto" alt="OLX" title="OLX" /></a>
            <a href="javascript:void(0);" target="_blank"><img src="{!! asset('local/public/images/ad-right-panel/htc.jpg') !!}" width="170" height="auto" alt="HTC" title="HTC" /></a>
            <a href="javascript:void(0);" target="_blank"><img src="{!! asset('local/public/images/ad-right-panel/bmw.jpg') !!}" width="170" height="auto" alt="BMW" title="BMW" /></a>
            <a href="javascript:void(0);" target="_blank"><img src="{!! asset('local/public/images/ad-right-panel/samsung.jpg') !!}" width="170" height="auto" alt="Samsung" title="Samsung" /></a>
            <a href="javascript:void(0);" target="_blank"><img src="{!! asset('local/public/images/ad-right-panel/olx.jpg') !!}" width="170" height="auto" alt="OLX" title="OLX" /></a>
            <a href="javascript:void(0);" target="_blank"><img src="{!! asset('local/public/images/ad-right-panel/htc.jpg') !!}" width="170" height="auto" alt="HTC" title="HTC" /></a>
        </div>
    </div>
    <!--  Footer starts  -->
    <div class="ftr-main">
        <div class="ftr-container">
            <div class="footer-nav fltL">
                <a href="javascript:void(0);">About</a>
                <span class="sptr-ftr">&verbar;</span>
                <a href="javascript:void(0);">Terms</a>
                <span class="sptr-ftr">&verbar;</span>
                <a href="javascript:void(0);">Privacy</a>
            </div>
            <div class="company-name fltR">
                Powered by <a href="javascript:void(0);">Blue Orca</a>
            </div>
            <span>Kinnect2&#8482; Ltd Company Registration Number : SC442762 Date of Incorporation</span>
        </div>
    </div>
    <!--  Footer Ends  -->
</div>
<!--  FeedBack  -->
<div class="feedback-left">
    <div class="header-feedback">
        Provide Your Feedback Below
    </div>
    {!! Form::open(array('url' => 'feedback','id'=>"feedback-form")) !!}
    <textarea name="feedback" id="" placeholder="Write your thoughts here" required></textarea>
    <button type="submit" class="btn fltR" id="feedback">Comment</button>
    {!! Form::close() !!}
</div>
<div class="feedback-img"></div>
<!--  Leaderboard  -->
<div class="leaderboard">
    <div class="header-feedback">
        Most Active Kinnectors
    </div>

    <div class="lb-tabs-wrapper">
        <!--  Leaderboard  Tabs-Menu -->
        <div class="lb-tabs-menu cf">
            <a class="lb-btn lb-btn-current mr10" href="#lb-tab-kinector">Kinnectors</a>
            <a class="lb-btn" href="#lb-tab-brands">Brands</a>
        </div>

        <!--  Leaderboard  Tabs-Content -->
        <!--  kinector Content  -->
        <ul id="lb-tab-kinector" class="lb-content">
            <li>
                <a class="lb-content-img" href="javascript:void(0);"><img src="{!! asset('local/public/images/leaderboard-tabs-content.jpg') !!}" alt="img"></a>
                <a class="kinnector-name ml10" href="javascript:void(0);">Russel Brown</a>
                <a class="kinnector-add ml10 mt10" href="javascript:void(0);">Add kinnector</a>
            </li>
            <li>
                <a class="lb-content-img" href="javascript:void(0);"><img src="{!! asset('local/public/images/leaderboard-tabs-content-img-2.jpg') !!}" alt="img"></a>
                <a class="kinnector-name ml10" href="javascript:void(0);">Pamela Rose</a>
                <a class="kinnector-add ml10 mt10" href="javascript:void(0);">Add kinnector</a>
            </li>
            <li>
                <a class="lb-content-img" href="javascript:void(0);"><img src="{!! asset('local/public/images/leaderboard-tabs-content.jpg') !!}" alt="img"></a>
                <a class="kinnector-name ml10" href="javascript:void(0);">Jason Stark</a>
                <a class="kinnector-add ml10 mt10" href="javascript:void(0);">Add kinnector</a>
            </li>
            <li>
                <a class="lb-content-img" href="javascript:void(0);"><img src="{!! asset('local/public/images/leaderboard-tabs-content.jpg') !!}" alt="img"></a>
                <a class="kinnector-name ml10" href="javascript:void(0);">Mark Ruffalo</a>
                <a class="kinnector-add ml10 mt10" href="javascript:void(0);">Add kinnector</a>
            </li>
            <li>
                <a class="lb-content-img" href="javascript:void(0);"><img src="{!! asset('local/public/images/leaderboard-tabs-content.jpg') !!}" alt="img"></a>
                <a class="kinnector-name ml10" href="javascript:void(0);">Susan Smith</a>
                <a class="kinnector-add ml10 mt10" href="javascript:void(0);">Add kinnector</a>
            </li>
            <li>
                <a class="lb-content-img" href="javascript:void(0);"><img src="{!! asset('local/public/images/leaderboard-tabs-content.jpg') !!}" alt="img"></a>
                <a class="kinnector-name ml10" href="javascript:void(0);">Russel Brown</a>
                <a class="kinnector-add ml10 mt10" href="javascript:void(0);">Add kinnector</a>
            </li>
            <li>
                <a class="lb-content-img" href="javascript:void(0);"><img src="{!! asset('local/public/images/leaderboard-tabs-content-img-2.jpg') !!}" alt="img"></a>
                <a class="kinnector-name ml10" href="javascript:void(0);">Pamela Rose</a>
                <a class="kinnector-add ml10 mt10" href="javascript:void(0);">Add kinnector</a>
            </li>
            <li>
                <a class="lb-content-img" href="javascript:void(0);"><img src="{!! asset('local/public/images/leaderboard-tabs-content.jpg') !!}" alt="img"></a>
                <a class="kinnector-name ml10" href="javascript:void(0);">Jason Stark</a>
                <a class="kinnector-add ml10 mt10" href="javascript:void(0);">Add kinnector</a>
            </li>
            <li>
                <a class="lb-content-img" href="javascript:void(0);"><img src="{!! asset('local/public/images/leaderboard-tabs-content.jpg') !!}" alt="img"></a>
                <a class="kinnector-name ml10" href="javascript:void(0);">Mark Ruffalo</a>
                <a class="kinnector-add ml10 mt10" href="javascript:void(0);">Add kinnector</a>
            </li>
            <li>
                <a class="lb-content-img" href="javascript:void(0);"><img src="{!! asset('local/public/images/leaderboard-tabs-content.jpg') !!}" alt="img"></a>
                <a class="kinnector-name ml10" href="javascript:void(0);">Susan Smith</a>
                <a class="kinnector-add ml10 mt10" href="javascript:void(0);">Add kinnector</a>
            </li>
        </ul>

        <!--  Brands Content  -->
        <ul id="lb-tab-brands" class="lb-content">
            <li>
                <a class="lb-content-img" href="javascript:void(0);"><img src="{!! asset('local/public/images/leaderboard-tabs-content.jpg') !!}" alt="img"></a>
                <a class="kinnector-name ml10" href="javascript:void(0);">Sample Brand</a>
                <a class="kinnector-add ml10 mt10" href="javascript:void(0);">Add brand</a>
            </li>
            <li>
                <a class="lb-content-img" href="javascript:void(0);"><img src="{!! asset('local/public/images/leaderboard-tabs-content.jpg') !!}" alt="img"></a>
                <a class="kinnector-name ml10" href="javascript:void(0);">Sample Brand</a>
                <a class="kinnector-add ml10 mt10" href="javascript:void(0);">Add brand</a>
            </li>
            <li>
                <a class="lb-content-img" href="javascript:void(0);"><img src="{!! asset('local/public/images/leaderboard-tabs-content.jpg') !!}" alt="img"></a>
                <a class="kinnector-name ml10" href="javascript:void(0);">Sample Brand</a>
                <a class="kinnector-add ml10 mt10" href="javascript:void(0);">Add brand</a>
            </li>
            <li>
                <a class="lb-content-img" href="javascript:void(0);"><img src="{!! asset('local/public/images/leaderboard-tabs-content.jpg') !!}" alt="img"></a>
                <a class="kinnector-name ml10" href="javascript:void(0);">Sample Brand</a>
                <a class="kinnector-add ml10 mt10" href="javascript:void(0);">Add brand</a>
            </li>
            <li>
                <a class="lb-content-img" href="javascript:void(0);"><img src="{!! asset('local/public/images/leaderboard-tabs-content.jpg') !!}" alt="img"></a>
                <a class="kinnector-name ml10" href="javascript:void(0);">Sample Brand</a>
                <a class="kinnector-add ml10 mt10" href="javascript:void(0);">Add brand</a>
            </li>
        </ul>
    </div>
</div>
<div class="leaderboard-img"></div>

</body>
<script src="{!! asset('local/public/js/jquery-2.1.3.js') !!}" type="text/javascript"></script>
<script src="{!! asset('local/public/js/custom.js') !!}" type="text/javascript"></script>
</html>
