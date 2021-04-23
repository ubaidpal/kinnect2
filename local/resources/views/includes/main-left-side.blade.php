<div class="leftPnl" id="stick" @if(Request::is('profile/*') or Request::is('brand/*')) data-page="profile" @endif>
    <div>
        <div class="box">
            <!-- My Brands-->
            <div class="heading bIcon">My Brands</div>
            <div class="brands">
                <?php $brands = Kinnect2::myBrands(); ?>
                @if($brands != FALSE)
                    @foreach( $brands as $brand )
                        @if(isset($brand->id))
                        <a href="{{url(Kinnect2::profileAddress($brand))}}"
                           class="@if(isset($brand->brand_detail) && $brand->brand_detail->store_created == 1) store-created @endif brandUrl_{{ $brand->username }}">
                            <img id="{{ ucwords(isset($brand->brand_detail) ? $brand->brand_detail->brand_name : $brand->name) }}" width="70" height="70"
                                 class="is_s_pressed" title="{{ ucwords(isset($brand->brand_detail) ? $brand->brand_detail->brand_name : $brand->name) }}"
                                 src="{{Kinnect2::getPhotoUrl($brand->photo_id, $brand->id, 'brand', 'thumb_profile')}}">
                            @if(isset($brand->brand_detail) && $brand->brand_detail->store_created == 1 && env('STORE_ENABLED'))
                                <span class="store brand_store_link" id="{{$brand->username}}"></span>
                            @endif
                        </a>
                        @endif
                    @endforeach
                @endif
            </div>
            <a class="btn mrgn mtMin5" title="View All My Brands" href="{{url('/brands/manage')}}">View All My
                                                                                                   Brands</a>

            <!-- Recomended Brands-->
            <div class="links_head">Recomended Brands</div>
            <div class="brands">
                <?php $brands = Kinnect2::recomendedBrands();?>
                @if($brands != FALSE)
                    @foreach( $brands as $brand )
                        <?php if(!isset($brand->id)){continue;}?>
                        <a class="@if(isset($brand->brand_detail) && $brand->brand_detail->store_created == 1) store-created @endif brandUrl_{{ $brand->username }}"
                           href="{{url(Kinnect2::profileAddress($brand))}}">
                            <img id="{{ ucwords(isset($brand->brand_detail) ? $brand->brand_detail->brand_name : $brand->name) }}"
                                 class="is_s_pressed" width="70" height="70"
                                 title="{{ ucwords(isset($brand->brand_detail) ? $brand->brand_detail->brand_name : $brand->name) }}"
                                 src="{{Kinnect2::getPhotoUrl($brand->photo_id, $brand->id, 'brand', 'thumb_profile')}}">
                            @if(isset($brand->brand_detail) && $brand->brand_detail->store_created == 1 && env('STORE_ENABLED'))
                                <span class="store brand_store_link" id="{{$brand->username}}"></span>
                            @endif
                        </a>

                    @endforeach
                @endif
            </div>
            <a class="btn mrgn mtMin5" title="View All Recommended Brands" href="{{url('/brands')}}">View All
                                                                                                     Recommended
                                                                                                     Brands</a>
        </div>
        <!-- Gruops-->
        <div class="box">
            <div class="heading gIcon">Groups</div>
            <div class="brands">
                <?php $groups = Kinnect2::myGroups() ?>
                @if($groups != FALSE)
                    @foreach( $groups as $group )
                        <a href="{{url('group')}}/{{$group->id}}" title="{{$group->title}}">
                            <img width="70" height="70" title="{{$group->title}}"
                                 src="{{Kinnect2::getPhotoUrl($group->photo_id, $group->id, 'group', 'group_thumb')}}">
                        </a>
                    @endforeach
                @endif
            </div>
            <a class="btn mrgn" title="Create Group" href="{{url('group/create')}}">Create Group</a>
            @if(Auth::user()->GroupMembership()->first() != NULL)
                <a class="btn mrgn" title="View All My Groups" href="{{url('groups/manage')}}">View All My Groups</a>
            @endif
            <div class="links_head">Recommended Groups</div>
            <div class="brands">
                <?php $groups = Kinnect2::recomendedGroups() ?>
                @if($groups != FALSE)
                    @foreach( $groups as $group )
                        <?php $user = Kinnect2::groupMember($group->creator_id) ?>
                        @if(isset($user))
                            <a href="{{url('group')}}/{{$group->id}}" title="{{$group->title}}">
                                <img width="70" height="70" title="{{$group->title}}"
                                     src="{{Kinnect2::getPhotoUrl($group->photo_id, $group->id, 'group', 'group_thumb')}}">
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>
            <a class="btn mrgn" title="View All Recommended Groups" href="{{url('groups')}}">View All Recommended
                                                                                             Groups</a>
        </div>

        <!-- Polls-->
        <div class="box">
            <div class="heading pIcon">Polls</div>
            @if ((Auth::user()->Poll()->first() != NULL))
                <?php $polls = Kinnect2::myPolls() ?>
                @if($polls != FALSE)
                    @foreach( $polls as $poll )
                        <a href="{{url('view/poll')}}/{{$poll->id}}" class="battle-question" title="{{$poll->title}}">
                            <img class="battle_question_img" title="{{$poll->title}}"
                                 src="{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_profile')}}">
                            <span>{{$poll->title}}</span>
                        </a>
                    @endforeach
                @endif
            @endif
            <a class="btn mrgn" title="Create Poll" href="{{ URL::to('polls/create')}}">Create Poll</a>
            @if ((Auth::user()->Poll()->first() != NULL))
                <a class="btn mrgn" title="View All Polls" href="{{ URL::to('polls/manage')}}">View All My Polls</a>
            @endif
            <div class="links_head">Recomended Polls</div>
            <?php $polls = Kinnect2::recomendedPolls() ?>
            @if($polls != FALSE)
                @foreach( $polls as $poll )
                    <?php $user = Kinnect2::groupMember($poll->user_id) ?>
                    @if(isset($user))
                        <a href="{{url('view/poll')}}/{{$poll->id}}" class="battle-question" title="{{$poll->title}}">
                            <img class="battle_question_img" title="{{$poll->title}}"
                                 src="{{Kinnect2::getPhotoUrl($user->photo_id, $poll->user_id, 'user', 'thumb_profile')}}">
                            <span>{{$poll->title}}</span>
                        </a>
                    @endif
                @endforeach
            @endif
            <a class="btn mrgn" title="View All Recommended Polls" href="{{ URL::to('polls')}}">View All Recommended
                                                                                                Polls</a>
        </div>

        <!-- Battle of The Brands-->
        <div class="box">
            <div class="heading btlIcon">Battle of the Brands</div>

            @if ((Auth::user()->Battle()->first() != NULL))
                <?php $battles = Kinnect2::myBattles() ?>
                @if($battles != FALSE)
                    @foreach( $battles as $battle )
                        <a href="{{url('view/battle')}}/{{$battle->id}}" class="battle-question" title="{{$battle->title}}">
                            <img class="battle_question_img" title="{{$battle->title}}"
                                 src="{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_profile')}}">
                            <span>{{$battle->title}}</span>
                        </a>
                    @endforeach
                @endif
            @endif
            <a class="btn mrgn" title="Create Battle" href="{{ URL::to('battles/create')}}">Create Battle</a>
            @if ((Auth::user()->Battle()->first() != NULL))
                <a class="btn mrgn" title="View All Battles" href="{{ URL::to('battles/manage')}}">View All My
                                                                                                   Battles</a>
            @endif
            <div class="links_head">Recomended Battles</div>
            <?php $battles = Kinnect2::recomendedBattles() ?>
            @if($battles != FALSE)
                @foreach( $battles as $battle )
                    <?php $user = Kinnect2::groupMember($battle->user_id) ?>
                    @if(isset($user))
                        <a href="{{url('view/battle')}}/{{$battle->id}}" class="battle-question" title="{{$battle->title}}">
                            <img class="battle_question_img" title="{{$battle->title}}"
                                 src="{{Kinnect2::getPhotoUrl($user->photo_id, $battle->user_id, 'user', 'thumb_profile')}}">
                            <span>{{$battle->title}}</span>
                        </a>
                    @endif
                @endforeach
            @endif
            <a class="btn mrgn" title="View All Recommended Battles" href="{{ URL::to('battles')}}">View All Recommended
                                                                                                    Battles</a>

        </div>
    </div>
</div>
<script>
    $(".brand_store_link").click(function(event){
        var brandNameStore = event.target.id;
        var hrefBrandStore = "<?php echo url('store')?>/";
        hrefBrandStore = hrefBrandStore + brandNameStore;
        $(".brandUrl_" + brandNameStore).attr('href', hrefBrandStore);
    });
</script>
