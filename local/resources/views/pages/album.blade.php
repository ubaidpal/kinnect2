@extends('layouts.default')
@section('content')
<!-- Album-->
<div class="content-gray-title">
    <h4>Album</h4>
</div>
<div class="create-album">
    <ul>
        <li>
            <a href="javascript:();"><img src="{!! asset('local/public/assets/images/add-image.jpg') !!}" width="150" height="150" alt="" /></a>
            <a class="txt" href="javascript:();" title="Create an Album">Create an Album</a>
            <div class="clrfix"></div>
        </li>
        <li>
            <a href="javascript:();"><img src="{!! asset('local/public/assets/images/user-img.jpg') !!}" width="150" height="150" alt="" /></a>
            <a class="txt" href="javascript:();" title="Profile Photo">Profile Photo</a>
            <div class="clrfix"></div>
            <span>3 Photos</span>
        </li>
        <li>
            <a href="javascript:();"><img src="{!! asset('local/public/assets/images/user-img.jpg') !!}" width="150" height="150" alt="" /></a>
            <a class="txt" href="javascript:();" title="Cover Photo">Cover Photo</a>
            <div class="clrfix"></div>
            <span>3 Photos</span>
        </li>
        <li>
            <a href="javascript:();"><img src="{!! asset('local/public/assets/images/user-img.jpg') !!}" width="150" height="150" alt="" /></a>
            <a class="txt" href="javascript:();" title="Wall Photos">Wall Photos</a>
            <div class="clrfix"></div>
            <span>3 Photos</span>
        </li>
        <li>
            <a href="javascript:();"><img src="{!! asset('local/public/assets/images/user-img.jpg') !!}" width="150" height="150" alt="" /></a>
            <a class="txt" href="javascript:();" title="Mobile Uploads">Mobile Uploads</a>
            <div class="clrfix"></div>
            <span>3 Photos</span>
        </li>
    </ul>
</div>
@endsection