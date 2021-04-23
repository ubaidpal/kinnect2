@extends('layouts.default')
@section('content')

    <div class="content-gray-title">
        <h4>Advanced Search</h4>
    </div>

    <div id="search-wrapper">
        <div id="search">
            {!! Form::open(['adavance-search', 'url'=>url('advanced_search')]) !!}

            <div class="advance_search">
                <label for="search" class="fltL">Serach:</label>
                <input id="search" type="text" placeholder="Type and Enter to find User" class="form-control fltL"
                       value="@if(isset($search) ){!!  $search!!}@endif" name="search">
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

                <div class="fltL">
                    <button class="orngBtn" type="submit" value="Search">Search</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <?php foreach ($users as $user): ?>
    <div class="user_item_wrap advance_search_list" id="user-item">
        <a class="search-dropdown-item" href="{{url('profile')}}/{{$user->username}}">
            <img width="70" height="70"
                 src="{{Kinnect2::getPhotoUrl($user->photo_id, $user->id, 'user', 'thumb_profile')}}"
                 title="{{ $user->name }}" alt="profile-image-{{ $user->name }}">
        </a>
        <div class="fltL ml20" style="flex: 1;">
            <a class="search-dropdown-item" href="{{url('profile')}}/{{$user->username}}">
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
                <p>{{$userDetails->about_me}}</p>
                <?php $u = Kinnect2::is_friend(Auth::user()->id,$user->id) ?>
                @if($u == true)
                  <a></a>
                @else
                   @if(Auth::user()->id != $user->id)
                      <a class="orngBtn"
                         href="{{URL::to('friends/add-friend/'.$user->id)}}" onclick="friend({{$user->id}})" id="btn_{{$user->id}}">
                            Add kinnector
                      </a>
                   @endif
                @endif
            @elseif($user->userable_type == 'App\Brand')
                <?php $userDetails = Kinnect2::getBrandDetailsByUserableId($user->userable_id)?>
                <p>{{$userDetails->description}}</p>
                <p>{{$userDetails->brand_history}}</p>
                <p>Followers:{{ Kinnect2::brand_kinnectors($user->id) }}</p>
                <?php $u = Kinnect2::is_following(Auth::user()->id,$user->id) ?>
                @if($u == true)
                  <a></a>
                @else
                    @if(Auth::user()->id != $user->id)
                     <a onclick="follow_1({{$user->id}})" title="Click to Follow {{ ucwords($user->name) }}" id="btn_{{$user->id}}" class="btn orngBtn">Follow</a>
                    @endif
                @endif
            @else
            @endif
        </div>
    </div>
    <?php endforeach; ?>
    @if(!empty($products))
    @foreach ($products as $product)
    <div class="user_item_wrap advance_search_list" id="user-item">
        <?php $user = getUserDetail($product->owner_id); ?>
        <?php $userDetails = Kinnect2::getBrandDetailsByUserableId($user->userable_id); ?>
        <a class="search-dropdown-item" href="{{url('store')}}/{{$user->username}}/product/{{$product->id}}/{{preg_replace('/\s+/', '-', $product->title)}}">
            <img width="70" height="70"
                 src="{{getProductPhotoSrc(null,null,$product->id,'product_icon')}}"
                 title="{{ $product->title }}" alt="profile-image-{{ $product->title }}">
        </a>
        <div class="fltL ml20">
            <a class="search-dropdown-item" href="{{url('store')}}/{{$user->username}}/product/{{$product->id}}/{{preg_replace('/\s+/', '-', $product->title)}}">
                {{ ucwords( $product->title ) }}
            </a>
            <div>
                Seller: <a href="{{url('profile/'.$user->username)}}">{{$userDetails->brand_name}}</a>
            </div>
            @if(Auth::user()->id != $product->owner_id)
                <a title="Click to buy {{ ucwords($product->title) }}" href="{{url('store')}}/{{$user->username}}/product/{{$product->id}}/{{preg_replace('/\s+/', '-', $product->title)}}" class="btn orngBtn">Buy</a>
            @endif

        </div>
    </div>
    @endforeach
    @endif
    <?php echo $users->appends(['profile_type' => $profile_type, 'search_term' => (isset($search)) ? $search : ''])->render(); ?>


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

