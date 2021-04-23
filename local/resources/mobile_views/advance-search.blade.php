@extends('layouts.default')
@section('content')

    <div class="title-bar">
		<span>Advance Search</span>
	</div>

    <div id="search-wrapper">
        <div id="search">
            {!! Form::open(['adavance-search', 'url'=>url('advanced_search'),'method' => 'get']) !!}

            <div class="advance_search">
                <input id="search" type="text" placeholder="Type and Enter to find User" class="form-item"
                       value="@if(isset($search) ){!!  $search!!}@endif" name="search_term">
                <select name="profile_type" id="profile_type">
                    <option value="1"
                            @if(isset($profile_type)) @if($profile_type == 1) selected="selected" @endif @endif>
                        Consumers
                    </option>
                    <option value="2"
                            @if(isset($profile_type)) @if($profile_type == 2) selected="selected" @endif @endif>Brands
                    </option>
                    <option value="3"
                            @if(isset($profile_type)) @if($profile_type == 3) selected="selected" @endif @endif>Products
                    </option>
                </select>

                <div class="fltL mt10">
                    <button class="orngBtn" type="submit" value="Search"><span class="icon searchIcon">Search</span></button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <?php foreach ($users as $user): ?>
    <div class="user_item_wrap advance_search_list" id="user-item">
        <a class="search-dropdown-item user-pic" href="{{url('profile')}}/{{$user->username}}">
            <img width="70" height="70"
                 src="{{Kinnect2::getPhotoUrl($user->photo_id, $user->id, 'user', 'thumb_profile')}}"
                 title="{{ $user->name }}" alt="profile-image-{{ $user->name }}">
        </a>
        <div class="user-detail">
        
            <a class="search-dropdown-item user-name" href="{{url('profile')}}/{{$user->username}}">
                <?php
                $displayname = preg_replace("/[^A-Za-z]/", "", $user->displayname);
                $name = preg_replace("/[^A-Za-z]/", "", $user->name);
                ?>

                <?php $isShown = false; ?>

                @if(strlen($displayname) > 0)
                    <?php $isShown = true; ?>
                    {{ ucwords( $user->displayname ) }}
                @endif

                @if(strlen($name) > 0 AND $isShown == FALSE)
                    <?php $isShown = true; ?>
                    {{ ucwords( $user->name ) }}
                @endif

                @if(strlen($name) < 1 AND strlen($displayname) < 1 AND $isShown == FALSE)
                    {{ ucwords( $user->username ) }}
                @endif
            </a>
            
             @if($user->userable_type == 'App\Consumer')
                <?php $userDetails = Kinnect2::getUserDetailsByUserableId($user->userable_id)?>
                <?php $country = Kinnect2::getCountryName($user->country)?>
                    <p>{{$country}}</p>
                    <p>{{$userDetails->birthdate}}</p>
                    <!--<p>{{$userDetails->about_me}}</p>-->
           
            @elseif($user->userable_type == 'App\Brand')
                <?php $userDetails = Kinnect2::getBrandDetailsByUserableId($user->userable_id)?>
               <!-- <p>{{$userDetails->description}}</p>
                <p>{{$userDetails->brand_history}}</p>-->
                <p>Followers:{{ Kinnect2::brand_kinnectors($user->id) }}</p>
               
            @endif
        </div>
        @if($user->userable_type == 'App\Consumer')
            
                <?php $u = Kinnect2::is_friend(Auth::user()->id,$user->id) ?>
                @if($u == true)
                    <a></a>
                @else
                    @if(Auth::user()->id != $user->id)
                        <a class="orngBtn"
                           href="{{URL::to('friends/add-friend/'.$user->id)}}" onclick="friend({{$user->id}})" id="btn_{{$user->id}}">
                            Add
                        </a>
                    @endif
                @endif
            @elseif($user->userable_type == 'App\Brand')
          
                <?php $u = Kinnect2::is_following(Auth::user()->id,$user->id) ?>
                @if($u == true)
                    <a></a>
                @else
                    @if(Auth::user()->id != $user->id)
                        <a onclick="follow_1({{$user->id}})" title="Click to Follow {{ ucwords($user->name) }}" id="btn_{{$user->id}}" class="orngBtn">Follow</a>
                    @endif
                @endif
            @else
            @endif
    </div>
    <?php endforeach; ?>



<script>
     function follow_1(brand_id){
         if($('#btn_'+brand_id).html() == 'Please wait..') return false;
         $('#btn_'+brand_id).html('Please wait..');

         var dataString = "brand_id="+brand_id;
         $.ajax({
             type: 'GET',
             url:  '{{url('follow')}}',
             data: dataString,
             success: function(response){
                 $("#brand_"+brand_id).remove();
                 window.location.reload();
             }
         });
     }//follow(brand_id)
 </script>

 <script>
     function friend(user_id){
           $('#btn_'+user_id).hide();
     }
 </script>
@endsection
@section('footer-scripts')
    <style>
        .pagination {
            line-height: normal;
            margin-top: 10px;
        }
        .pagination li {
            float: left;
        }
    </style>
@endsection

