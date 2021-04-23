{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 06-1-16 2:27 PM
    * File Name    :

--}}
@if(isset($users[$row->getSender()]))
    <?php $user = $users[$row->getSender()];?>
@else
    <?php
    $user = \App\User::where('id', $row->getSender())->with('album_photo.storage_file')->first();
    ?>
@endif

<div class="@if($row->getSender() == $user_id) conversation-rtl @else conversation-ltr @endif">
    <div class="picA">
        <a href="{{Kinnect2::profileAddress($user)}}">
            <img src="{{Kinnect2::getPhotoUrl($user->photo_id, $user->id, 'user', 'thumb_icon')}}"
                 width="48"
                 height="48" alt="image"/>
        </a>
    </div>
    <div class="msgA">
        <a href="{{Kinnect2::profileAddress($user)}}"
           title="{{ $user->displayname }}">
            {{ $user->displayname }}
        </a>

        <div class="clrfix"></div>
        <div class="bubble" data-id="{{$row->getId()}}">
            <div class="mesg">
                <?php
                $string = filter_var($row->getContent(), FILTER_VALIDATE_URL);
                ?>
                @if($string)
                    <?php
                        $meta = extractLinkMeta($row->getContent());
                            //echo '<tt><pre>'; print_r($meta);
                        ?>
                    <a href="{{$row->getContent()}}">{{$meta['title']}}</a><br>
                    @if(!empty($meta['images']))
                            <img class="link-img" height="" width="200" src="{{$meta['images'][0]}}">

                        @endif
                    {{$meta['description']}}
                @else
                    {{$row->getContent()}}
                @endif
            </div>
            @if(!is_null($row->getFile()))
                <!--<span class="attachment-icon"></span>-->
                <div class="linkDownload">
                    <?php $file = get_photo_by_id($row->getFile(), true,true);?>

                    @if(strpos(@$file->mime_type,'image') !== false)
                    <a href="{{$file['url']}}" download="">
                        <img src="{{@$file['url']}}" width="250">
                    </a>
                    @elseif(strpos(@$file->mime_type,'video') !== false)

                    <video width="300" height="250" preload="none" controls >
                        <source type="video/mp4" src="{{\Config::get( 'constants_activity.ATTACHMENT_VIDEO_URL_MOD').$file->storage_path}}">
                    </video>
                    @elseif(strpos(@$file->mime_type,'audio') !== false)
                    <audio src="{{@$file['url']}}" controls>
                        Your browser does not support the <code>audio</code> element.
                    </audio>
                    @endif
                    <!--<span class="attachment-name">{{@$file['name']}}</span>-->
                    <span class="attachment-url"><a href="{{$file['url']}}" download="">Download</a></span>
                </div>

            @endif
            <span class="msg-time">
            {{getTimeByTZ($row->getCreated(), 'M d | h:i a')}}
        </span>
        </div>
    </div>
</div>
