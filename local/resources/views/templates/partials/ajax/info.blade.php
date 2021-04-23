{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 12-11-15 12:24 PM
    * File Name    : 

--}}
<?php
//echo '<tt><pre>'; print_r($user); die;
?>
<div class="content-gray-title mb10" data-user="{{$user->username}}"  data-url="/profile/profile-view">
    <h4> @can('consumer', $user) Personal Information @endcan @can('brand', $user)Brand Information @endcan</h4>
    @if($user->id == Auth::user()->id)
        <a href="javascript:void(0);" title="View sent Requests" class="btn fltR tab" data-target="edit-profile"
           data-ajax="true">Edit
        </a>
    @endif
</div>

<?php

$detail = $consumer;

?>
<div class="details-list">
    @can('brand', $user)
    <div class="detail-item">
        <div class="dtl-item">
            <span>Brand Name &ast;</span>
        </div>
        <div class="dtl-value">
            <span>{{$detail->brand_name}}</span>
        </div>
    </div><div class="detail-item">
        <div class="dtl-item">
            <span> Manager First Name&ast;</span>
        </div>
        <div class="dtl-value">
            <span>{{$user->first_name}}</span>
        </div>
    </div>

    <div class="detail-item">
        <div class="dtl-item">
            <span>Manager Last Name &ast;</span>
        </div>
        <div class="dtl-value">
            <span>{{$user->last_name}}</span>
        </div>
    </div>
    <div class="detail-item">
        <div class="dtl-item">
            <span>Country &ast;</span>
        </div>
        <div class="dtl-value">
            <span>{{country_name($user->country)}}</span>
        </div>
    </div>
    @endcan


   @can('consumer', $user)
    <div class="detail-item">
        <div class="dtl-item">
            <span>First Name &ast;</span>
        </div>
        <div class="dtl-value">
            <span>{{$user->first_name}}</span>
        </div>
    </div>

    <div class="detail-item">
        <div class="dtl-item">
            <span>Last Name &ast;</span>
        </div>
        <div class="dtl-value">
            <span>{{$user->last_name}}</span>
        </div>
    </div>
    <div class="detail-item">
        <div class="dtl-item">
            <span>Gender &ast;</span>
        </div>

        <div class="dtl-value">
                    <span>
                        @if($detail->gender == 1)
                            {{'Male'}}
                        @else
                            {{'Female'}}
                        @endif
                    </span>
        </div>
    </div>

    <div class="detail-item">
        <div class="dtl-item">
            <span>Birthday</span>
        </div>
        <div class="dtl-value">
                    <span>
                        {{\Carbon\Carbon::parse($detail->birthdate)->format(Config::get('constants.DATE_FORMAT'))}}
                    </span>
        </div>
    </div>
    <div class="detail-item">
        <div class="dtl-item">
            <span>Website &ast;</span>
        </div>
        <div class="dtl-value">
            <span>{{$user->website}}</span>
        </div>
    </div>
    <div class="detail-item">
        <div class="dtl-item">
            <span>Twitter &ast;</span>
        </div>
        <div class="dtl-value">
            <span>{{$user->facebook}}</span>
        </div>
    </div>
    <div class="detail-item">
        <div class="dtl-item">
            <span>Facebook &ast;</span>
        </div>
        <div class="dtl-value">
            <span>{{$user->twitter}}</span>
        </div>
    </div>
    @endcan

</div>
<div class="content-gray-title mb10">
    <h4>@can('consumer', $user) About @endcan @can('brand', $user) Description @endcan</h4>
</div>
<p class="formating">
    @can('consumer', $user)
    {{$detail->personnel_info}}
    @endcan
    @can('brand', $user)
    {{$detail->description}}
    @endcan
</p>
@can('brand', $user)
<div class="content-gray-title mb10">
    <h4>Brand History</h4>
</div>
<p class="formating">


    {{$detail->brand_history}}

</p>
@endcan
