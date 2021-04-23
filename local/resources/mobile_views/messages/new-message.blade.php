<div class="conversation-rtl">
    <div class="picA">

        <?php
        $url = Kinnect2::profileAddress($user);
        $image = Kinnect2::getPhotoUrl($user->photo_id, $user->id, 'user', 'thumb_normal');
        $name = $user->displayname;
        ?>

        <a href="{{Kinnect2::profileAddress($user)}}">
            <img src="{{$image}}" width="48" height="48" alt="image">
        </a>
    </div>
    <div class="msgA">
        <a href="{{$url}}" title="{{$name}}">
            {{$name}}
        </a>

        <div class="clrfix"></div>
        <div class="bubble" data-id="42">
            <div class="mesg">
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
                                <span class="attachment-url">
                                    <a href="{{$url}}" download="">Download</a></span>
                    </div>

                @endif
            </div>
                    <span class="msg-time">
                         {{getTimeByTZ(\Carbon\Carbon::now(), 'M d | h:i a')}}
                    </span>
        </div>
    </div>
</div>
