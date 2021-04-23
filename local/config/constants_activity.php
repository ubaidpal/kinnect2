<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : local
 * Product Name : PhpStorm
 * Date         : 02-12-15 3:42 PM
 * File Name    : constants_activity.php
 */
$url = '';
if(isset($_SERVER['REQUEST_URI'])) {
    $uri = $_SERVER['REQUEST_URI'];
    if(strpos($uri, config('app.api_prefix')) !== FALSE) {
        $url = '';
    } else {
        $url = config('app.url');
    }
}

return array(
    'notification' => array(

        'FRIEND_REQUEST'          => 'friend_request',
        'BRAND-FOLLOW'            => 'follow_brand',
        "BRAND-UNFOLLOW"          => 'unfollow_brand',
        'FRIEND_REQUEST_ACCEPTED' => 'friend_accepted',
        'GROUP_POST'              => 'post',
        'POST_LIKED'              => 'post_liked',
        'ACTIVITY_DISLIKE'        => 'activity_disliked',
        'ACTIVITY_COMMENT'        => 'activity_comment',
        'ACTIVITY_LIKE'           => 'activity_like',
        'BATTLE_CREATE_TAG'       => 'battle_create_tag',
        'INVITATION'              => [
            'BRAND' => 'brand_invitation',
            'GROUP' => 'group_invitation',
            'EVENT' => 'event_invitation',
        ],
        'OBJECT_TYPE'             => [
            'NAME'  => 'activity_action',
            'TYPES' => [
                'LIKE'    => 'activity_like',
                'COMMENT' => 'activity_comment',
                'DISLIKE' => 'activity_dislike',
                'VIDEO_PROCCESSED' => 'video_processed',
                'AUDIO_PROCCESSED' => 'audio_processed',
                'SHARE' => 'object_share',
                'FAV' => 'activity_fav'
            ],
        ],
    ),
    'OBJECT_TYPES' => array(
        // Following Format for future use... discussed with Mohsin'
        //ACTIVITY_ACTION' => array ('db_name' => 'activity_action', 'actions' => array('CREATE','DELETE')
        'PRODUCT'          => [
            'NAME'    => 'product',
            'ACTIONS' => [
                'CREATE' => 'product_create'
            ]
        ],
        'ACTIVITY_ACTION'  => 'activity_action',
        'COMMENT'          => 'activity_comments',
        'BATTLE'           => 'battle',
        'CORE_COMMENT'     => 'core_comment',
        'USER'             => [
            'NAME'    => 'user',
            'ACTIONS' => [
                'UPDATE_STATUS'  => 'status',
                'UPDATE_PROFILE' => 'update_profile',
            ],
        ],
        'VIDEO'            => 'video',
        //'POST' => 'post',
        'ACTIVITY_DISLIKE' => 'activity_dislikes',
        'ACTIVITY_LIKE'    => 'activity_likes',
        'POLLS'            => [
            'NAME'    => 'poll',
            'ACTIONS' => [
                'CREATE' => 'poll_create',
                'DELETE' => 'poll_delete',
                'VOTE'   => 'poll_vote',
                'CLOSE'  => 'poll_close',
            ],
        ],
        'BRAND'            => [
            'NAME'    => 'brand',
            'ACTIONS' => [
                'FOLLOW' => 'follow',
                'CLOSE'  => 'battle_create',
                'DELETE' => 'battle_delete',
                'VOTE'   => 'battle_vote',
            ],
        ],
        'BATTLES'          => [
            'NAME'    => 'battle',
            'ACTIONS' => [
                'CREATE' => 'battle_create',
                'CLOSE'  => 'battle_create',
                'DELETE' => 'battle_delete',
                'VOTE'   => 'battle_vote',
            ],
        ],
        'ALBUM_PHOTO'      => [
            'NAME'    => 'album_photo',
            'ACTIONS' => [
                'LIKE'                 => 'like',
                'COMMENT'              => 'comment',
                'UPDATE_PROFILE_PHOTO' => 'profile_update_photo',
            ],
        ],
        'PHOTO'            => [
            'NAME'    => 'cover_photo',
            'ACTIONS' => [
                'UPDATE_COVER_PHOTO' => 'cover_photo_update',
            ],
        ],
        'ALBUM'            => [
            'NAME'    => 'album',
            'ACTIONS' => [
                'ALBUM_PHOTO' => 'album_photo',
                'COMMENT'     => 'comment',
            ],
        ],
        'EVENT'            => [
            'NAME'    => 'event',
            'ACTIONS' => [
                'INVITATION_SENT'    => 'invitation_sent',
                'REJECTED_REQUEST'   => 'rejected_request_event',
                'APPROVED_REQUEST'   => 'approved_request_event',
                'CREATE_EVENT'       => 'event_create',
                'EVENT_PHOTO_UPLOAD' => 'event_photo_upload',
            ],
        ],
        'GROUP'            => [
            'NAME'    => 'group',
            'ACTIONS' => [
                'CREATE'            => 'group_create',
                'GROUP_SHARE'       => 'group_share',
                'POST'              => 'post',
                'REQUEST_SENT'      => 'request_sent',
                'INVITATION_SENT'   => 'invitation_sent',
                'ACCEPT_INVITATION' => 'accept_invitation',
                'JOIN'              => 'join',
                'APPROVED_REQUEST'  => 'approved_request',
                'REJECTED_REQUEST'  => 'rejected_request',
                'GROUP_MANAGER'     => 'manager_request',
            ],
        ],
        'LINK'             => [
            'NAME'    => 'link',
            'ACTIONS' => [
                'SHARE' => 'share',
                'LINK'  => 'link',
            ],
        ],
    ),

    'VIDEO_URL'                => $url . '/video/',
    'VIDEO_THUMB_URL'          => $url . '/thumb/',
    'VIDEO_URL_MOD'            => $url . '/local/storage/app/videos/',
    'ATTACHMENT_VIDEO_URL_MOD' => $url . '/local/storage/app/attachments/',
    'PHOTO_URL'                => $url . '/photo/',
    'ATTACHMENT_URL'           => $url . '/attachment/',
    'AUDIO_URL'                => $url . '/audio/',
    'AUDIO_URL_MOD'            => $url . '/local/storage/app/audios/',
    'STORAGE_PATH'             => storage_path('/') . 'app/',
    'ATTACHMENT_PATH'          => storage_path('/') . 'app/attachments/',
    'ATTACHMENT_THUMB'         => $url . '/attachment_thumb/',

    'notification_messages' => array(
        'friend_request'         => '$subject sent you a friend request',
        'friend_accepted'        => '$subject accepted your friend request',
        'post'                   => '$subject has been posted in $object',
        'activity_like'          => '$subject like your post',
        'activity_comment'       => '$subject comment on your post',
        'activity_dislike'       => '$subject dislike your post',
        'request_sent'           => '$subject wants join $object',
        'invitation_sent'        => '$subject invites you to join $object',
        'accept_invitation'      => '$subject accept your invitation to join $object',
        'approved_request'       => '$subject approved your request to join group $object',
        'approved_request_event' => '$subject approved your request to join event $object',
        'rejected_request'       => '$subject rejected your request to join group $object',
        'rejected_request_event' => '$subject rejected your request to join event $object',
        'follow_brand'           => '$subject followed you',
        'brand_invitation'       => '$subject invite you to follow $object',
        'manager_request'        => '$subject sent you a request to be a manager of $object',
        'battle_create_tag'      => '$subject created a new battle between $brand_1 and $brand_2',
        'video_processed'        => 'Your video is processed and ready to be viewed',
        'audio_processed'        => 'Your audio is processed and ready',
        'object_share' => '$subject shared your $object',
        'activity_fav' => '$subject favourited your post'
    ),

    'ACTIVITY_LOG_MESSAGE' => [
        'brand_join'           => 'joined the brand $object',
        'friends'              => 'became friends with $object',
        'profile_update_photo' => 'Updated profile picture',
        'cover_photo_update'   => 'Updated cover picture',
        'comment_video'        => 'comment on $object',
        'product_create'       => 'created product $object',
        'comment_album_photo'  => 'comment on $object',
        'like_status'          => 'likes $object',
        'event_create'         => 'create the event $object',
        'battle_create'        => 'create the battle $object',
        'comment_battle'       => 'comment on battle $object',
        'event_share'          => 'share the event $object',
        'status'               => 'update his',
        'group_create'         => 'create group $object',
        'poll_create'          => 'create poll $object',
        'poll_new'             => 'create poll $object',
        'group_share'          => 'share group $object',
        'poll_close'           => 'close poll $object',
        'update_profile'       => 'Update his profile info',
        'album_photo'          => 'add photo to album',
        'album_photo_new'      => 'add new photo',
        'follow'               => 'is following $object',
        'video_new'            => 'add video',
        'audio_new'            => 'add $object',
        'share'                => 'shared $object',
        'link'                 => 'shared video $object',
        'album'                => 'Add photo to album $object',
        'event_photo_upload'   => 'upload photo in event $object',
        'group_join'           => 'join group $object',

    ],
    'OBJECT_TYPES_STRING'  => [
        'activity_action' => 'a status',
        'album_photo'     => 'an album photo',
        'battle'          => 'battle',
        'brand'           => 'brand',
        'cover_photo'     => 'cover photo',
        'event'           => 'an event',
        'group'           => ' group',
        'link'            => ' link',
        'poll'            => ' poll',
        'product'         => ' product',
        'video'           => ' video',
    ]
);
