@extends('layouts.default')
@section('content')
	<!-- Post Div-->
    <!--<div class="post">
    	<form method="" action="" id="">
          <input type="text" placeholder="What would you like to post?" alt="" maxlength="" size="60" id="" name="" autocomplete="on">
        </form>
        <div class="shareDiv">
        	<a href="javascript:();" title="Upload">Upload</a>
            <a href="javascript:();" title="Share">Share</a>
        </div>
    </div>-->
    
    <!-- Post-->
<div class="post">


<!--Links Preview Start-->
<div id="links-preview">
    <div class="link-images">
        <ul>
            <li>
                <img src="{!! asset('local/public/assets/images/browse-battle.jpg') !!}" width="100%" height="auto">
            </li>
            
        </ul>
    </div>
    <div class="link-content">
        <div class="title">Build software better, together</div>
        <div class="desc">GitHub is where people build software. More than 11 million people use GitHub to discover, fork, and contribute to over 30 million projects.</div>
    </div>
    <div class="clrfix"></div>
    <a class="close" href="#">×</a>
</div>
<!--Links Preview End-->

    <div id="postUploadingFiles">
        <div>
            <ul class="uploads-container">
                <li class="">
                    <div class="delete">
                    	<a href="javascirpt:void(0)"></a>
                    </div>
                    <img style="width:60px; height:70px" src="{!! asset('local/public/assets/images/BlueOrcaStudios_5109_538961.jpeg') !!}" alt="Blue Orca" />
                    <div class="uploading-inprogress"><img src="{!! asset('local/public/images/loading.gif') !!}" alt="Uploading..."/></div>
                </li>
                <li class="">
                    <div class="delete">
                    	<a href="javascirpt:void(0)"></a>
                    </div>
                    <img style="width:60px; height:70px" src="{!! asset('local/public/assets/images/browse-battle.jpg') !!}" alt="Blue Orca" />
                </li>
                <li class="">
                    <div class="delete">
                    	<a href="javascirpt:void(0)"></a>
                    </div>
                    <img style="width:60px; height:70px" src="{!! asset('local/public/assets/images/profile.jpg') !!}" alt="Blue Orca" />
                </li>
                <li class="audio">
                    <div class="delete">
                    	<a href="javascirpt:void(0)"></a>
                    </div>
                </li>
                <li class="video">
                    <div class="delete">
                    	<a href="javascirpt:void(0)"></a>
                    </div>
                </li>
                
                <li class="upload-more">
                    <div class="more"></div>
                </li>
            </ul>
        </div>
    </div>
    <form action="" id="postForm" method="post" enctype="multipart/form-data">
        <input type="text" placeholder="What would you like to post?" alt="" maxlength="" size="60" id="k2post"
               name="text" autocomplete="on">

        <input type="file" style="position: fixed; top: -30px;" name="video" id="postFiles"
               accept="image/*,video/*,audio/*" multiple>
        <input type="submit" style="display: none" id="savePostBtn">
        <!--<input type="file" style="position: fixed; top: -30px;" name="photos" id="postFiles" accept="image/*,video/*,audio/*" multiple />-->
        <button type="submit" id="btn" style="width: 0px; height: 0px; display: none;">Upload Files!</button>
    </form>
    <div id="response"></div>
    <div class="shareDiv">
        <a href="javascript:void(0);" title="Upload" id="status-file-upload">Upload</a>
        <a href="javascript:void(0);" title="Share">Share</a>
    </div>
</div>
    <!-- End Post -->
    
	<div class="post-box brand">
    	<!-- Product Auto Post -->
        <div class="posts-contain">
         <div class="product-auto-post">
          <div class="user-name-pic">
                    <a href="javascript:void(0);">
                        <img src="{!! asset('local/public/assets/images/blankImage.png') !!}" width="55" height="55">
                    </a>
                    <div>
                        <div class="post-header-one">
                            <a href="javascript:void(0);">Apple</a>
                        </div>
                        <em title="">March 15, 2016 11:36 AM</em>
                    </div>
           
           <!-- btn Buy Now -->
           <a class="btn-buynow" href="javascript:void(0);">Buy Now</a>
                </div>
        
                <div class="pap-content">
                 <div class="pap-img">
                  <img src="{!! asset('local/public/assets/images/pap-detail.jpg') !!}">
                 </div>
                 <div class="pap-detail">
                  <div class="pap-dtitle">
                   <h1>Apple Macbook Pro</h1>
                   <div class="pap-dreview">
                    <div class = "pap-dreview-img">
                       <a class = "pap-dreview-itm full" href = "javascript: void (0);"> </a>
                       <a class = "pap-dreview-itm half" href = "javascript: void (0);"> </a>
                       <a class = "pap-dreview-itm" href = "javascript: void (0);"> </a>
                    </div>
                    <span class="pap-dreview-review">(12 Reviews)</span>
                   </div>
                <div class="pap-title-label">$1700</div>
                  </div>
                  <p class="pap-txt">
                   Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus, aspernatur sit enim quae maiores omnis ipsum libero a ullam quis iure architecto illo nam atque nihil, recusandae id tempore repellat molestias in nobis impedit hic.
                  </p>
                 </div>
                </div>
        
          <div class="feed-options">
                    <a href="javascript:void(0);" title="Like" class="like like-post "></a>
                    <a href="javascript:void(0);" title="Dislike" class="dislike dislike-post "></a>
                    <a href="javascript:void(0);" title="Favourite" class="favourite favourite-post "></a>
                    <a href="javascript:void(0);" data-id="reShare" title="Share" class="share share-post-kinnct"></a>
                    <a href="javascript:void(0);" title="Social" class="social-link social-share-post"></a>
                </div>
        
          <div class="otherLikes">2 Kinnectors like this.</div>
        
          <div class="post-write-comment">
                    <div class="options-detail">
                        <span class="likes-count"> 2 Likes </span> |
                        <span class="dislikes-count"> 0 Dislikes </span> |
                        <span class="comments-count"> 0 Comments </span> |
                        <span class="shares-count"> 0 Shares </span>
                    </div>
                </div>
        
                <div class="comment-item">
                 <div class="write-comment">
               <form class="my-comment">
                   <textarea placeholder="Write Comment" alt="" maxlength="" size="60" class="box-comment disable-required write-comment-box" name="" autocomplete="off"></textarea>
               </form>
               <a class="orngBtn send-comment disable-required" title="Comment">Comment</a>
           </div>
          </div>
        
         </div>
        </div>
    </div>

    <div class="post-box brand">
    	<div class="user-name-pic">
            <a href="javascript:();">
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
        <div class="media" href="javascript:();" title="Image">
        	<img src="{!! asset('local/public/assets/images/SGS_slide_1-690x300.jpg') !!}" width="auto" height="auto" alt="Image" />
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
            <a href="javascript:();" title="name">Jessica shire</a>&sbquo; <a href="javascript:();">Peter John</a> and <a href="javascript:();">50 others</a> like this.
        </div>
        <div class="post-write-comment">
        	
            <div class="options-detail">1000 Likes | 500 Dislikes | 50,000 Comments | 1000 Shares</div>
            <div class="comment-pnl">
            	<a href="javascript:();" title="username" class="user-image"><img src="{!! asset('local/public/assets/images/profile.jpg') !!}" width="45" height="45" alt="" title="user" /></a>
                <div class="comment-text">
                	<a class="commentor-name" href="javascript:();">Peter John</a>
                    <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
					<span class="like"><a href="javascript:();"></a> &ndash; 500 Like this</span>
                    <span class="date">15 April at 10:24pm</span>
                </div>
            </div>
            <div class="comment-pnl">
            	<a href="javascript:();" title="username" class="user-image"><img src="{!! asset('local/public/assets/images/profile.jpg') !!}" width="45" height="45" alt="" title="user" /></a>
                <div class="comment-text">
                	<a class="commentor-name" href="javascript:();">Peter John</a>
                    <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
					<span class="like"><a href="javascript:();"></a> &ndash; 500 Like this</span>
                    <span class="date">15 April at 10:24pm</span>
                </div>
            </div>
            
            <div class="write-comment">
            	<a href="javascript:();" title="user name" class="user-image"><img src="{!! asset('local/public/assets/images/profile.jpg') !!}" width="45" height="45" title="username" /></a>
                <form method="" action="" id="">
                  <input type="text" placeholder="Write Comment" alt="" maxlength="" size="60" id="" name="" autocomplete="off">
                </form>
                <a href="javascript:();" class="orngBtn" title="Comment">Comment</a>
            </div>
        </div>
    </div>
    <div class="post-box">
    	<div class="user-name-pic">
            <a href="javascript:();">
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
        <div class="media" href="javascript:();" title="Image">
        	<img src="{!! asset('local/public/assets/images/SGS_slide_1-690x300.jpg') !!}" width="auto" height="auto" alt="Image" />
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
            <a href="javascript:();" title="name">Jessica shire</a>&sbquo; <a href="javascript:();">Peter John</a> and <a href="javascript:();">50 others</a> like this.
        </div>
        <div class="post-write-comment">
        	
            <div class="options-detail">1000 Likes | 500 Dislikes | 50,000 Comments | 1000 Shares</div>
            <div class="comment-pnl">
            	<a href="javascript:();" title="username" class="user-image"><img src="{!! asset('local/public/assets/images/profile.jpg') !!}" width="45" height="45" alt="" title="user" /></a>
                <div class="comment-text">
                	<a class="commentor-name" href="javascript:();">Peter John</a>
                    <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
					<span class="like"><a href="javascript:();"></a> &ndash; 500 Like this</span>
                    <span class="date">15 April at 10:24pm</span>
                </div>
            </div>
            <div class="comment-pnl">
            	<a href="javascript:();" title="username" class="user-image"><img src="{!! asset('local/public/assets/images/profile.jpg') !!}" width="45" height="45" alt="" title="user" /></a>
                <div class="comment-text">
                	<a class="commentor-name" href="javascript:();">Peter John</a>
                    <p>Cras bibendum nisi eu ligula lacinia, vitae convallis justo hendrerit. Phasellus pellentesque ante non egestas congue. Nunc at metus nulla. Donec ac erat eu sem vulputate facilisis et id diam</p>
					<span class="like"><a href="javascript:();"></a> &ndash; 500 Like this</span>
                    <span class="date">15 April at 10:24pm</span>
                </div>
            </div>
            
            <div class="write-comment">
            	<a href="javascript:();" title="user name" class="user-image"><img src="{!! asset('local/public/assets/images/profile.jpg') !!}" width="45" height="45" title="username" /></a>
                <form method="" action="" id="">
                  <input type="text" placeholder="Write Comment" alt="" maxlength="" size="60" id="" name="" autocomplete="off">
                </form>
                <a href="javascript:();" class="orngBtn" title="Comment">Comment</a>
            </div>
        </div>
    </div>
@endsection
