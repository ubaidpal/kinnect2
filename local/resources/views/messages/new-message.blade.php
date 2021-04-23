{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 06-1-16 5:47 PM
    * File Name    : 

--}}
<div class=" conversation-rtl ">
    <div class="picA">
        <a href="{{Kinnect2::profileAddress($user)}}">
            <img width="48" height="48" alt="image"
                 src="{{Kinnect2::getPhotoUrl($user->photo_id, $user->id, 'user', 'thumb_icon')}}">
        </a>
    </div>
    <div class="msgA">
        <a title="Dean Bopara" href="{{Kinnect2::profileAddress($user)}}">
            {{$user->displayname}}
        </a>

        <div class="clrfix"></div>
        <div class="bubble">
             <span class="mesg">
                 <?php
                 $string = filter_var($data['body'], FILTER_VALIDATE_URL);
                 ?>
                 @if($string)
                     <?php
                     $meta = extractLinkMeta($data['body']);
                     //echo '<tt><pre>'; print_r($meta);
                     ?>
                     <a href="{{$data['body']}}">{{$meta['title']}}</a><br>
                     @if(!empty($meta['images']))
                         <img class="link-img" height="" width="200" src="{{$meta['images'][0]}}">

                     @endif
                     {{$meta['description']}}
                 @else
                     {{$data['body']}}
                 @endif

             </span>
            @if(isset($attachment) && !empty($attachment))
                <?php
                $path = \Config::get('constants_activity.ATTACHMENT_URL');
                $url = $path . $attachment['storage_path'] . '?type=' . urlencode($attachment['mime_type']);
                $fileName = $attachment["name"];
                // $urlHtml = "<a href='$url' class='' download=''>$fileName</a>";
                ?>

                <!--<span class="attachment-icon"></span>-->

                <div class="linkDownload">
                    @if(strpos($attachment['mime_type'],'image') !== false)
                        <a href="{{$url}}" download="">
                            <img src="{{$url}}" width="250">
                        </a>
                    @elseif(strpos($attachment['mime_type'],'video') !== false)

                        <video width="300" height="250" preload="none" controls >
                            <source type="video/mp4" src="{{\Config::get( 'constants_activity.ATTACHMENT_VIDEO_URL_MOD').$attachment['storage_path']}}">
                        </video>
                    @elseif(strpos($attachment['mime_type'],'audio') !== false)
                        <audio src="{{$url}}" controls>
                            Your browser does not support the <code>audio</code> element.
                        </audio>
                    @endif
                    <!--<span class="attachment-name">{{$fileName}}</span>-->
                    <span class="attachment-url"><a href="{{$url}}" download="">Download</a></span>
                </div>

            @endif
            <span class="msg-time">
                     {{getTimeByTZ(\Carbon\Carbon::now(), 'M d | h:i a')}}
            </span>
            <input class="last_update_time" type="hidden" value="{{\Carbon\Carbon::now()->toDateTimeString()}}">
        </div>
    </div>
</div>
