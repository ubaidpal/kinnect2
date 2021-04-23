@extends('layouts.default')
@section('content')
<!-- Polls Detail-->
<div class="content-gray-title mb10">
    <h4>Polls Graph</h4>
</div>
<!-- Post Div-->
<div class="post-box">
     <div class="battle-detail">
         <div class="polls-detail">
         <div class="polls-detail-title">
          <div class="polls-title-img">
           <img src="{!! asset('local/public/assets/images/notification-img.jpg') !!}" alt="image">
          </div>
          
          <a class="polls-title-name" href="javascript:();">Maria Maria</a>
         </div>
        
         <div class="polls-detail-text">
          Which one is best?
         </div>
        
         <div class="polls-progress-bar">
          <div class="polls-vote-name fltL">Apple</div>
          <div class="polls-vote-value fltR">Votes&colon; 500</div>
        
          <div class="progress-bar">
            <span class="green" style="width: 75%"></span>
          </div>
         </div>
        
         <div class="polls-progress-bar">
          <div class="polls-vote-name fltL">Dell</div>
          <div class="polls-vote-value fltR">Votes&colon; 300</div>
        
          <div class="progress-bar">
            <span class="orange" style="width: 50%"></span>
          </div>
         </div>
        
         <div class="polls-progress-bar">
          <div class="polls-vote-name fltL">Huawei</div>
          <div class="polls-vote-value fltR">Votes&colon; 100</div>
        
          <div class="progress-bar">
            <span class="red" style="width: 25%"></span>
          </div>
         </div>
        </div>
    </div>
    <div class="feed-options">
        <a href="javascript:();" title="Like" class="like"></a>
        <a href="javascript:();" title="Dislike" class="dislike"></a>
        <a href="javascript:();" title="Favourite" class="favourite"></a>
        <a href="javascript:();" title="Share" class="share"></a>
        <a href="javascript:();" title="Social" class="social-link"></a>
        <a href="javascript:();" title="Flag" class="flag"></a>
    </div>
    <div class="otherLikes">
        <a href="javascript:();" title="name">Jessica shire</a>‚ <a href="javascript:();">Peter John</a> and <a href="javascript:();">50 others</a> like this.
    </div>
    <div class="post-write-comment">
        
        <div class="options-detail">1000 Likes | 500 Dislikes | 50,000 Comments | 1000 Shares</div>
        <a href="javascript:();" class="btn showP">Show Previous Comments</a>
        <div class="comment-pnl">
            <a href="javascript:();" title="username" class="user-image"><img width="45" height="45" src="http://localhost/kinnect2/local/public/assets/images/profile.jpg" alt="" title="user"></a>
            <div class="comment-text">
                <a class="commentor-name" href="javascript:();">Peter John</a>
                <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                <span class="like"><a href="javascript:();"></a> &ndash; 500 Like this</span>
                <span class="date">15 April at 10:24pm</span>
            </div>
        </div>
        <div class="comment-pnl">
            <a href="javascript:();" title="username" class="user-image"><img width="45" height="45" src="http://localhost/kinnect2/local/public/assets/images/profile.jpg" alt="" title="user"></a>
            <div class="comment-text">
                <a class="commentor-name" href="javascript:();">Peter John</a>
                <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
                <span class="like"><a href="javascript:();"></a> &ndash; 500 Like this</span>
                <span class="date">15 April at 10:24pm</span>
            </div>
        </div>
        
        <div class="write-comment">
            <a href="javascript:();" title="user name" class="user-image"><img width="45" height="45" src="http://localhost/kinnect2/local/public/assets/images/profile.jpg" title="username"></a>
            <form method="" action="" id="">
              <input type="text" placeholder="Write Comment" alt="" maxlength="" size="60" id="" name="" autocomplete="off">
            </form>
            <a href="javascript:();" class="orngBtn" title="Comment">Comment</a>
        </div>
    </div>
</div>
@endsection