@extends('layouts.default')
@section('content')
 <?php $users = Kinnect2::LeaderboardUsers() ?>
 <div class="content-gray-title mb10">
    <h4>Kinnectors</h4>
 </div>
     <ul id="consumers_leaderboard">
        @foreach($users as $user)
            <li>
              <?php $groupOwner = Kinnect2::groupOwner($user->id); ?>
              <a class="lb-content-img" href="{{url(Kinnect2::profileAddress($groupOwner))}}">
                <img src="{{Kinnect2::getPhotoUrl($user->photo_id, $user->id, 'user', 'thumb_icon')}}" alt="img">
              </a>
              <a class="kinnector-name ml10" href="{{url(Kinnect2::profileAddress($groupOwner))}}">{{$user->name}}</a>
              <p>skore:{{$user->skore}}</p>
				<div class="clrfix"></div>
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
            </li>
        @endforeach
     </ul>
<script>
    function friend(user_id){
          $('#btn_'+user_id).hide();
    }
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
@stop
