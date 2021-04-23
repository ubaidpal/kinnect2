@extends('layouts.default')
@section('content')

<?php $users = Kinnect2::LeaderboardBrands() ?>
 <div class="content-gray-title mb10">
    <h4>Brands</h4>
 </div>
     <ul id="brands_leaderboard">
        @foreach($users as $user)
            <li>
              <?php $groupOwner = Kinnect2::groupOwner($user->id); ?>
              <a class="lb-content-img" href="{{url(Kinnect2::profileAddress($groupOwner))}}">
                <img src="{{Kinnect2::getPhotoUrl($user->photo_id, $user->id, 'brand', 'thumb_icon')}}" alt="img">
                  @if(isset($user->brand_detail) && $user->brand_detail->store_created == 1 && env('STORE_ENABLED'))
                  <span style="  width: 20px; height: 20px;" class="store brand_store_link" id="{{$groupOwner->username}}"></span>
                  @endif
              </a>
              <a class="kinnector-name ml10" href="{{url(Kinnect2::profileAddress($groupOwner))}}">{{ucfirst(isset($user->brand_detail) ? $user->brand_detail->brand_name : $user->displayname)}}</a>
              <p>skore:{{$user->skore}}</p>
			  <div class="clrfix"></div>	
              <?php $u = Kinnect2::is_following(Auth::user()->id,$user->id) ?>
                @if($u == true)
                  <a></a>
                @else
                    @if(Auth::user()->id != $user->id)
                     <a href="" onclick="follow_1({{$user->id}})" title="Click to Follow {{ ucwords($user->name) }}" id="btn_{{$user->id}}" class="btn orngBtn">Follow</a>
                    @endif
                @endif
            </li>
        @endforeach
     </ul>
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
             }
         });
     }//follow(brand_id)
 </script>
    <style>
        .lb-content-img img {
            max-height: 40px;
            max-width: 40px;
            position: absolute;
        }
        .lb-content-img {


            height: 40px;

            width: 40px;
        }
    </style>
    <script>
        $(".brand_store_link").click(function(event){
            var brandNameStore = event.target.id;
            var hrefBrandStore = "<?php echo url('store')?>/";
            hrefBrandStore = hrefBrandStore + brandNameStore;
            $(".brandUrl_" + brandNameStore).attr('href', hrefBrandStore);
        });
    </script>
@stop
