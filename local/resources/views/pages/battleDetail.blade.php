@extends('layouts.default')
@section('content')
<!-- Battle Detail-->
<div class="content-gray-title mb10">
    <h4>Apple Vs. Sony</h4>
    <a class="btn fltR" title="Browse" href="javascript:();">Browse</a>
    <a class="btn fltR mr10" title="Create Battel" href="javascript:();">Create Battle</a>
</div>
<!-- Post Div-->
<div class="post-box">
     <div class="battle-detail">
         <div class="battle-title cf">Quality Test Battle</div>
        
         <div class="battle-brand">
          <form action="">
            <div class="battle-brand fltL">
           <div class="brand-img brand-img-lft">
            <img src="{!! asset('local/public/assets/images/Battles-Details-brands-img-1.jpg') !!}" alt="image">
           </div>
        
           <div class="brand-name brand-name-lft">Apple</div>
        
        
           <div class="battle-brand-radio cf">
            <label class="battle-radio-btn">
             <input type="radio" name="Brand" value="apple" checked>
             <i></i>
            </label>
            <div class="battle-radio-txt">Apple</div>
           </div>
        
           <div class="battle-brand-votes">
            <div class="battle-txt-vote fltL">Votes:</div>
            <div class="battle-vote-value fltR">500</div>
        
            <div class="battle-vote-bar">
              <span style="width: 100%"></span>
            </div>
           </div>
          </div>
        
        
            <div class="battle-brand fltR">
           <div class="brand-img brand-img-rght">
            <img src="{!! asset('local/public/assets/images/img-sony.jpg') !!}" alt="image">
           </div>
        
           <div class="brand-name brand-name-rght">Sony</div>
        
        
           <div class="battle-brand-radio cf">
            <label class="battle-radio-btn">
             <input type="radio" name="Brand" value="Sony">
             <i></i>
            </label>
            <div class="battle-radio-txt">Sony</div>
           </div>
        
           <div class="battle-brand-votes fltR">
            <div class="battle-txt-vote fltL">Votes:</div>
            <div class="battle-vote-value fltR">500</div>
        
            <div class="battle-vote-bar">
              <span class="red" style="width: 100%"></span>
            </div>
           </div>
          </div>
        
            <div class="battle-vs">Vs</div>
            </form>
         </div>
    </div>
    <div class="feed-options">
        <a class="like" title="Like" href="javascript:();"></a>
        <a class="dislike" title="Dislike" href="javascript:();"></a>
        <a class="favourite" title="Favourite" href="javascript:();"></a>
        <a class="share" title="Share" href="javascript:();"></a>
        <a class="social-link" title="Social" href="javascript:();"></a>
        <a class="flag" title="Flag" href="javascript:();"></a>
    </div>
    <div class="otherLikes">
        <a title="name" href="javascript:();">Jessica shire</a>‚ <a href="javascript:();">Peter John</a> and <a href="javascript:();">50 others</a> like this.
    </div>
    <div class="post-write-comment">
        
        <div class="options-detail">1000 Likes | 500 Dislikes | 50,000 Comments | 1000 Shares</div>
        <a class="btn showP" href="javascript:();">Show Previous Comments</a>
        <div class="comment-pnl">
            <a class="user-image" title="username" href="javascript:();"><img width="45" height="45" title="user" alt="" src="{!! asset('local/public/assets/images/profile.jpg') !!}"></a>
            <div class="comment-text">
                <a href="javascript:();" class="commentor-name">Peter John</a>
                <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                <span class="like"><a href="javascript:();"></a> &ndash; 500 Like this</span>
                <span class="date">15 April at 10:24pm</span>
            </div>
        </div>
        <div class="comment-pnl">
            <a class="user-image" title="username" href="javascript:();"><img width="45" height="45" title="user" alt="" src="{!! asset('local/public/assets/images/profile.jpg') !!}"></a>
            <div class="comment-text">
                <a href="javascript:();" class="commentor-name">Peter John</a>
                <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                <span class="like"><a href="javascript:();"></a> &ndash; 500 Like this</span>
                <span class="date">15 April at 10:24pm</span>
            </div>
        </div>
        
        <div class="write-comment">
            <a class="user-image" title="user name" href="javascript:();"><img width="45" height="45" title="username" src="{!! asset('local/public/assets/images/profile.jpg') !!}"></a>
            <form id="" action="" method="">
              <input type="text" autocomplete="off" name="" id="" size="60" maxlength="" alt="" placeholder="Write Comment">
            </form>
            <a title="Comment" class="orngBtn" href="javascript:();">Comment</a>
        </div>
    </div>
</div>
@endsection