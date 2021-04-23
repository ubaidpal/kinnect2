<?php
$users = Kinnect2::LeaderboardUsers();
$brands = Kinnect2::LeaderboardBrands();
$userCount = $brandCount = 6;
if (count($users) < 6) {
    $userCount = count($users);
}

if (count($brands) < 6) {
    $brandCount = count($brands);
}
?>

        <!--  FeedBack  -->
{{--<div class="feedback-left" id="feedback-left">

    <div class="header-feedback">
        Provide Your Feedback Below
    </div>

    {!! Form::open(array('url' => 'feedback','id'=>"feedback-form")) !!}
    <textarea name="feedback" id="feedback-text" placeholder="Write your thoughts here" required></textarea>
    <button type="submit" class="btn fltR" id="feedback">Comment</button>
    {!! Form::close() !!}
</div>
<div class="feedback-img" id="feedback-img"></div>--}}
<!--  Leaderboard  -->


<div class="leaderboard" id="leaderboard">
    <div class="header-feedback" id="header-feedback">
        Most Active Kinnectors
    </div>

    <div class="lb-tabs-wrapper" id="lb-tabs-wrapper">
        <!--  Leaderboard  Tabs-Menu -->
        <div class="lb-tabs-menu cf">
            <a class="lb-btn lb-btn-current mr10" id="leader-board-kinnectors-anchor"
               href="#lb-tab-kinector" title="Kinnectors">Kinnectors</a>
            <a class="lb-btn" id="leader-board-brands-anchor" title="Brands" href="#lb-tab-brands">Brands</a>
        </div>

        <!--  Leaderboard  Tabs-Content -->
        <!--  kinector Content  -->
        @if(count($users) > 0 AND count($brands) > 0)
            <ul id="lb-tab-kinector" class="lb-content">
                @for ($i = 0; $i < $userCount; $i++)
                    <?php
                    if (count($users) < 1) {
                        break;
                    }
                    if (!isset($users[$i])) {
                        continue;
                    }
                    ?>

                    @if(isset($users[$i]))
                        <li>
                            <a class="lb-content-img" href="{{url(Kinnect2::profileAddress($users[$i]))}}">
                                <img src="{{Kinnect2::getPhotoUrl($users[$i]->photo_id, $users[$i]->id, 'user', 'thumb_icon')}}"
                                     alt="img">
                            </a>
                            <?php //$groupOwner = Kinnect2::groupOwner($users[$i]->id); ?>
                            <a class="kinnector-name" title="{{$users[$i]->name}}"
                               href="{{url(Kinnect2::profileAddress($users[$i]))}}">{{$users[$i]->name}}</a>

                            <div class="clrfix"></div>
                            <span class="total_skore">skore:{{$users[$i]->skore}}</span>
                            <?php $u = Kinnect2::is_friend(Auth::user()->id, $users[$i]->id) ?>
                            @if($u == true)
                            @else
                                @if(Auth::user()->id != $users[$i]->id)
                                    <a class="kinnector-add" title="Add Kinnector"
                                       href="{{URL::to('friends/add-friend/'.$users[$i]->id)}}"
                                       onclick="friend({{$users[$i]->id}})" id="btn_{{$users[$i]->id}}">
                                        Add kinnector
                                    </a>
                                @endif
                            @endif
                        </li>
                    @endif
                @endfor
                <li id="">
                    <a href="{{ url('/leaderboard/consumers' ) }}" title="View More">View More</a>
                </li>
            </ul>
            <!--  Brands Content  -->
            <ul id="lb-tab-brands" class="lb-content">
                @for ($i = 0; $i < $brandCount; $i++)
                    <?php
                    if(count($brands) < 1) {break;}
                    if(!isset($brands[$i])){continue;}
                    ?>
                    @if(isset($brands[$i]))
                        <li>
                            <a class="lb-content-img brandUrl_{{$brands[$i]->username}}"
                               href="{{Kinnect2::profileAddress($brands[$i])}}">
                                <img src="{{Kinnect2::getPhotoUrl($brands[$i]->photo_id, $brands[$i]->id, 'brand', 'thumb_icon')}}"
                                     alt="img">
                                @if(isset($brands[$i]->brand_detail) && $brands[$i]->brand_detail->store_created == 1 && env('STORE_ENABLED'))
                                    <span class="store brand_store_link" id="{{$brands[$i]->username}}"></span>
                                @endif
                            </a>
                            <?php //$groupOwner = Kinnect2::groupOwner($brands[$i]->id); ?>
                            <a class="kinnector-name" title="{{$brands[$i]->brand_detail->brand_name}}"
                               href="{{url(Kinnect2::profileAddress($brands[$i]))}}">{{$brands[$i]->brand_detail->brand_name}}</a>

                            <div class="clrfix"></div>
                            <span class="total_skore">skore:{{$brands[$i]->skore}}</span>

                            <?php $u = Kinnect2::is_following(Auth::user()->id, $brands[$i]->id) ?>
                            @if($u == true)
                            @else

                                @if(Auth::user()->id != $brands[$i]->id)
                                    <a href="" onclick="follow({{$brands[$i]->id}})"
                                       title="Click to Follow {{ ucwords($brands[$i]->name) }}"
                                       id="btn_{{$brands[$i]->id}}"
                                       class="btn kinnector-add">Follow</a>
                                @endif
                            @endif
                        </li>
                    @endif
                @endfor
                <li>
                    <a href="{{ url('/leaderboard/brands' ) }}" title="View More">View More</a>
                </li>
            </ul>
        @endif

    </div>
</div>
<div class="leaderboard-img" id="leaderboard-img"></div>

<script type="text/javascript">
    function follow(brand_id) {
        if ($('#btn_' + brand_id).html() == 'Please wait..') return false;
        $('#btn_' + brand_id).html('Please wait..');

        var dataString = "brand_id=" + brand_id;
        $.ajax({
            type: 'GET',
            url: '{{url('follow')}}',
            data: dataString,
            success: function (response) {
                $("#btn_" + brand_id).remove();
            }
        });
    }//follow(brand_id)

    function friend(user_id) {
        $('#btn_' + user_id).hide();
    }


    $('#feedback-left').click(function (event) {
        event.stopPropagation();
    });
    $('#leaderboard').click(function (event) {
        event.stopPropagation();
    });
    $(document).click(function (e) {
        var target = $(e.target);
        if (($("#leaderboard").css("marginRight")) == '0px') {
            $("#leaderboard").animate({marginRight: '-220px'});
            $('#leaderboard-img').removeClass('active');
            $("#leaderboard-img").animate({marginRight: '0px'});
        }
        if (($("#feedback-left").css("marginLeft")) == '0px') {
            $("#feedback-left").animate({marginLeft: '-300px'});
            $("#feedback-img").removeClass('active');
            $("#feedback-img").animate({marginLeft: '0px'});
        }
    });
</script>
<script>
    $(".brand_store_link").click(function (event) {
        var brandNameStore = event.target.id;
        var hrefBrandStore = "<?php echo url('store')?>/";
        hrefBrandStore = hrefBrandStore + brandNameStore;
        $(".brandUrl_" + brandNameStore).attr('href', hrefBrandStore);
    });
</script>
