<?php
require_once base_path() . '/config/constants_setting.php';
$constants = [
    'REGULAR_USER' => 1,
    'BRAND_USER'   => 2,


    'SUPER_ADMIN'      => 100,
    'ADMIN'            => 101,
    'ACCOUNTS MANAGER' => 102,
    'DISPUTE MANAGER'  => 103,
    'ARBITRATOR'       => 105,
    'ACCOUNTANT'       => 106,

    'USER_TYPES'       => [
        1   => 'Normal',
        2   => 'Brand',
        100 => 'Super Admin',
        101 => 'Admin',
        102 => 'Accounts Manager',
        103 => 'Dispute Manager',
        105 => 'Arbitrator',
        106 => 'Accountant',
    ],
    'ADMIN_URL_PREFIX' => 'admin/',
    'DISPLAY_LIMIT'    => 10,

    // Profile Image sizes

    'PROFILE_ICON_HEIGHT' => 48,
    'PROFILE_ICON_WIDTH'  => 48,

    'PROFILE_THUMB_HEIGHT' => 70,
    'PROFILE_THUMB_WIDTH'  => 70,

    'PROFILE_NORMAL_HEIGHT' => 170,
    'PROFILE_NORMAL_WIDTH'  => 170,


    'PROFILE_COVER_PHOTO_WIDTH'  => 1792,
    'PROFILE_COVER_PHOTO_HEIGHT' => 300,

    'GROUP_COVER_PHOTO_WIDTH'  => 710,
    'GROUP_COVER_PHOTO_HEIGHT' => 230,

    'WALL_IMAGE_HEIGHT' => 710,
    'WALL_IMAGE_WIDTH'  => 710,

    // End Of Profile Image sizes

    // AD Image sizes

    'AD_ICON_HEIGHT' => 48,
    'AD_ICON_WIDTH'  => 48,

    'AD_THUMB_HEIGHT' => 70,
    'AD_THUMB_WIDTH'  => 70,

    'AD_PROFILE_HEIGHT' => 170,
    'AD_PROFILE_WIDTH'  => 170,
    // End Of Ad Image sizes

    // Group images Sizes

    'GROUP_PROFILE_HEIGHT' => 170,
    'GROUP_PROFILE_WIDTH'  => 170,

    'GROUP_THUMB_WIDTH'  => 70,
    'GROUP_THUMB_HEIGHT' => 70,
    // End of Group images Sizes

    // Events images Sizes
    'EVENT_ICON_HEIGHT'  => 48,
    'EVENT_ICON_WIDTH'   => 48,

    'EVENT_THUMB_HEIGHT' => 170,
    'EVENT_THUMB_WIDTH'  => 170,

    'EVENT_NORMAL_HEIGHT' => 170,
    'EVENT__NORMAL_WIDTH' => 170,

    'EVENT_PROFILE_WIDTH'  => 250,
    'EVENT_PROFILE_HEIGHT' => 250,

    'WALL_EVENT_IMAGE_HEIGHT' => 710,
    'WALL_EVENT_IMAGE_WIDTH'  => 710,
    // End of Events images Sizes

    'TIME_LINE_THUMB_WIDTH'  => 688,
    'TIME_LINE_THUMB_HEIGHT' => 450,

    // Events images Sizes
    'ALBUM_ICON_HEIGHT'      => 48,
    'ALBUM_ICON_WIDTH'       => 48,

    'ALBUM_THUMB_HEIGHT' => 170,
    'ALBUM_THUMB_WIDTH'  => 170,

    'ALBUM_NORMAL_HEIGHT' => 170,
    'ALBUM_NORMAL_WIDTH'  => 170,

    'ALBUM_PROFILE_WIDTH'  => 250,
    'ALBUM_PROFILE_HEIGHT' => 250,

    'ALBUM_EVENT_IMAGE_HEIGHT' => 710,
    'ALBUM_EVENT_IMAGE_WIDTH'  => 710,
    'POST_MAX_SIZE'            => 25,
    'UPLOAD_MAX_FILESIZE'      => 200,
    // End of Events images Sizes
    
    'POLL_THUMB_WIDTH' => 100,
    'POLL_THUMB_HEIGHT' => 100,

    'COMMENT_THUMB_WIDTH' => 150,
    'COMMENT_THUMB_HEIGHT' => 150,

    /******************************************************/

    // Permissions related constants //
    "PERM_PRIVATE"             => 0,                //Only me
    //Only me
    'PERM_FRIENDS'             => 1,                // Friends only
    'PERM_FRIENDS_OF_FRIENDS'  => 2,     // Friends of Friends
    'PERM_FRIENDS_AND_NETWORK' => 3,     // Friends and Network
    'PERM_EVERYONE'            => 4,                //Public
    'PERM_REG_MEMBERS'         => 5,

    'PERM_GROUP_MEMBERS'             => 101,   //Group members only
    'PERM_GROUP_OFFICERS_AND_OWNERS' => 102,   //Group members only
    'PERM_EVENT_MEMBERS'             => 103,   //Event members only
    'API_ROUTE_PREFIX'               => 'api/v1',

    'ACTIVITY_TYPE_FRIENDSHIP' => 'friends',
    'ACTIVITY_TYPE_PROFILE'    => 'profile',
    'DATE_FORMAT'              => 'F d Y',
    'NOT_AUTHORIZED'           => 'You are not authorized to view this page',
    'PERMISSION'               => [
        0   => "PERM_PRIVATE",
        1   => 'PERM_FRIENDS',
        2   => 'PERM_FRIENDS_OF_FRIENDS',
        3   => 'PERM_FRIENDS_AND_NETWORK',
        4   => 'PERM_EVERYONE',
        5   => 'PERM_REG_MEMBERS',
        101 => 'PERM_GROUP_MEMBERS',
        102 => 'PERM_GROUP_OFFICERS_AND_OWNERS',
        103 => 'PERM_EVENT_MEMBERS',
    ],

    'USER_TIME_ZONE' => '',
    'PER_PAGE'       => '50',


    'MESSAGES_ATTACHMENT_WIDTH' => 480,
];
return array_merge($privacy, $notification, $constants);
