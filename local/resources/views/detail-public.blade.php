<?php $decodedPost = json_decode(base64_decode($post)); ?>




@extends('layouts.anonymous')

@section('scripts')
    @include('includes.client-side-mvc')
@endsection

@section("dom-head")

    <?php if($decodedPost->object_type == "album_photo"){
        $og_image = $decodedPost->base_url."/".$decodedPost->object_photo_path[0];
        ?>

        <meta property="og:url"                content="http://qa.kinnect2.com//photo/5991/146166804030571f48c889fb58_11067455.jpg" />
        <meta property="og:type"               content="article" />
        <meta property="og:title"              content="Shared a post on Kinnect2" />
        <meta property="og:description"        content="" />
        <meta property="og:image"              content="<?=$og_image?>" />

    <?php }elseif($decodedPost->object_type == "activity_action"){

        ?>

        <meta property="og:url"                content="<?=$decodedPost->base_url."/postDetail/".$decodedPost->post_id?>" />
        <meta property="og:type"               content="article" />
        <meta property="og:title"              content="Shared a post on Kinnect2" />
        <meta property="og:description"        content="<?=$decodedPost->post_body?>" />

    <?php }elseif($decodedPost->object_type == "video"){

        $og_video = $decodedPost->base_url."/".$decodedPost->object_path;
        $og_img = $decodedPost->base_url."/".$decodedPost->object_photo_path;
        ?>

        <meta property="og:url"                content="<?=$decodedPost->base_url."/postDetail/".$decodedPost->post_id?>" />
        <meta property="og:type"               content="article" />
        <meta property="og:title"              content="Shared a post on Kinnect2" />
        <meta property="og:description"        content="<?=$decodedPost->post_body?>" />
        <meta property="og:video"              content="<?=$og_video?>" />
        <meta property="og:image"              content="<?=$og_img?>" />
        <meta property="og:video:type"              content="video/mp4" />
        <meta property="og:video:width"              content="470" />
        <meta property="og:video:height"              content="260" />


    <?php }elseif($decodedPost->object_type == "audio"){

        $og_video = $decodedPost->base_url."/".$decodedPost->object_path;
        ?>

        <meta property="og:url"                content="<?=$decodedPost->base_url."/postDetail/".$decodedPost->post_id?>" />
        <meta property="og:type"               content="article" />
        <meta property="og:title"              content="Shared a post on Kinnect2" />
        <meta property="og:description"        content="<?=$decodedPost->post_body?>" />
        <meta property="og:video"              content="<?=$og_video?>" />

    <?php }?>

@endsection

@section('content')
<div class="offline-post">
    <div id="mvc-main" data-screen="publicPostDetail" data-options="{{ $post }}"></div>
</div>
@endsection
