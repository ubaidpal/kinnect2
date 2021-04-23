<div class="leftPnl" id="stick">
    <div>
        <div class="box">
            <!-- My Brands-->
            <div class="heading bIcon">My Brands</div>
            <div class="brands">
                <?php $brands = Kinnect2::myBrands() ?>
                 @if($brands != false)
                     @foreach( $brands as $brand )
                        <a href="{{url(Kinnect2::profileAddress($brand))}}">
                        	<img width="70" height="70" title="{{$brand->first_name}}" alt="Imperial" src="{{Kinnect2::getPhotoUrl($brand->photo_id, $brand->id, 'brand', 'thumb_profile')}}">
                        </a>
                    @endforeach
                 @endif
            </div>
            <a class="btn mrgn mtMin5" title="View All My Brands" href="{{url('/brands/manage')}}">View All My Brands</a>
            
            <!-- Recomended Brands-->
            <div class="links_head">Recomended Brands</div>
             <div class="brands">
                 <?php $brands = Kinnect2::recomendedBrands() ?>
             @if($brands != false)
                     @foreach( $brands as $brand )
                         <a href="{{url(Kinnect2::profileAddress($brand))}}">
                             <img width="70" height="70" title="{{$brand->first_name}}" alt="Imperial" src="{{Kinnect2::getPhotoUrl($brand->photo_id, $brand->id, 'brand', 'thumb_profile')}}">
                         </a>

                     @endforeach
                 @endif
             </div>
            <a class="btn mrgn mtMin5" title="View All Recommended Brands" href="{{url('/brands')}}">View All Recommended Brands</a>
        </div>
        <!-- Gruops-->
        <div class="box">
            <div class="heading gIcon">Groups</div>
            <div class="brands">
            <?php $groups = Kinnect2::myGroups() ?>
            @if($groups != false)
                @foreach( $groups as $group )
                    <a href="{{url('group')}}/{{$group->id}}">
                        <img width="70" height="70" title="{{$group->title}}" alt="Imperial" src="{{Kinnect2::getPhotoUrl($group->photo_id, $group->id, 'group', 'group_thumb')}}">
                    </a>
                @endforeach
            @endif
            </div>
            <a class="btn mrgn" title="Create Group" href="{{url('group/create')}}">Create Group</a>
            @if(Auth::user()->GroupMembership()->first() != Null)
                <a class="btn mrgn" title="View All My Groups" href="{{url('groups/manage')}}">View All My Groups</a>
            @endif
            <div class="links_head">Recommended Groups</div>
            <div class="brands">
            <?php $groups = Kinnect2::recomendedGroups() ?>
                @if($groups != false)
                    @foreach( $groups as $group )
                        <a href="{{url('group')}}/{{$group->id}}">
                             <?php $user = Kinnect2::groupMember($group->creator_id) ?>
                             @if(isset($user))
                                <img width="70" height="70" title="{{$group->title}}" alt="Imperial" src="{{Kinnect2::getPhotoUrl($group->photo_id, $group->id, 'group', 'group_thumb')}}">
                            @endif
                        </a>
                    @endforeach
                @endif
            </div>
            <a class="btn mrgn" title="View All Recommended Groups" href="{{url('groups')}}">View All Recommended Groups</a>
        </div>
        
        <!-- Polls-->
        <div class="box">
            <div class="heading pIcon">Polls</div>
            @if ((Auth::user()->Poll()->first() != Null))
                <?php $polls = Kinnect2::myPolls() ?>
                @if($polls != false)
                    @foreach( $polls as $poll )
                         <a href="{{url('polls')}}/{{$poll->id}}" class="battle-question" title="">
                         <img class = "battle_question_img" title="{{$poll->name}}" alt="Imperial" src="{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_profile')}}">
                            <span>{{$poll->title}}</span>
                        </a>
                    @endforeach
                @endif
            @endif
            <a class="btn mrgn" title="Create Poll" href="{{ URL::to('polls/create')}}">Create Poll</a>
            @if ((Auth::user()->Poll()->first() != Null))
                <a class="btn mrgn" title="View All Polls" href="{{ URL::to('polls/manage')}}">View All My Polls</a>
            @endif
            <div class="links_head">Recomended Polls</div>
            <?php $polls = Kinnect2::recomendedPolls() ?>
                @if($polls != false)
                    @foreach( $polls as $poll )
                        <?php $user = Kinnect2::groupMember($poll->user_id) ?>
                        @if(isset($user))
                            <a href="{{url('polls')}}/{{$poll->id}}" class="battle-question" title="">
                                  <img class = "battle_question_img" title="{{$poll->name}}" alt="Imperial" src="{{Kinnect2::getPhotoUrl($user->photo_id, $poll->user_id, 'user', 'thumb_profile')}}">
                                   <span>{{$poll->title}}</span>
                            </a>
                        @endif
                    @endforeach
                @endif
             <a class="btn mrgn" title="View All Recommended Polls" href="{{ URL::to('polls')}}">View All Recommended Polls</a>
        </div>
        
        <!-- Battle of The Brands-->
        <div class="box">
            <div class="heading btlIcon">Battle of the Brands</div>

             @if ((Auth::user()->Battle()->first() != Null))
                <?php $battles = Kinnect2::myBattles() ?>
                @if($battles != false)
                    @foreach( $battles as $battle )
                         <a href="{{url('battles')}}/{{$battle->id}}" class="battle-question" title="">
                         <img class = "battle_question_img" title="{{$battle->name}}" alt="Imperial" src="{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_profile')}}">
                            <span>{{$battle->title}}</span>
                         </a>
                    @endforeach
                @endif
            @endif
            <a class="btn mrgn" title="Create Battle" href="{{ URL::to('battles/create')}}">Create Battle</a>
            @if ((Auth::user()->Battle()->first() != Null))
                <a class="btn mrgn" title="View All Battles" href="{{ URL::to('battles/manage')}}">View All My Battles</a>
            @endif
            <div class="links_head">Recomended Battles</div>
            <?php $battles = Kinnect2::recomendedBattles() ?>
            @if($battles != false)
                @foreach( $battles as $battle )
                    <?php $user = Kinnect2::groupMember($battle->user_id) ?>
                    @if(isset($user))
                        <a href="{{url('battles')}}/{{$battle->id}}" class="battle-question" title="">
                            <img class = "battle_question_img" title="{{$battle->name}}" alt="Imperial" src="{{Kinnect2::getPhotoUrl($user->photo_id, $battle->user_id, 'user', 'thumb_profile')}}">
                                <span>{{$battle->title}}</span>
                        </a>
                    @endif
                @endforeach
            @endif
            <a class="btn mrgn" title="View All Recommended Battles" href="{{ URL::to('battles')}}">View All Recommended Battles</a>

        </div>
    </div>
 </div>
