{{--

    * Created by   :  Muhammad Yasir
    * Project Name : kinnect2
    * Product Name : PhpStorm
    * Date         : 01-Mar-2016 12:13 PM
    * File Name    : new-message

--}}
<?php $user = \Auth::user(); ?>
<div class="comnt-wrapper">
    <a class="comntr-pic" href="#">
        <img src="{{Kinnect2::getPhotoUrl($user->photo_id, $user->id, 'user', 'thumb_icon')}}" alt="User Name">
    </a>
    <div class="comnt-detail">
        <div class="post-name">
            <a href="">{{$user->displayname}}</a>
        </div>

        <p>{{$data['body']}}</p>
        @if(isset($attachment) && !empty($attachment))
            <?php
            $path = \Config::get('constants_activity.ATTACHMENT_URL');
            $url = $path . $attachment['storage_path'] . '?type=' . urlencode($attachment['mime_type']);
            $fileName = $attachment["name"];
            // $urlHtml = "<a href='$url' class='' download=''>$fileName</a>";
            ?>

            <span class="attachment-icon"></span>

            <div class="linkDownload">
                <span class="attachment-name">{{$fileName}}</span>
                <span class="attachment-url"><a href="{{$url}}" download="">Download</a></span>
            </div>

        @endif
        <em title="">{{getTimeByTZ(\Carbon\Carbon::now(), 'F d Y h:i A')}}</em>
    </div>
</div>
