@extends('layouts.default')
@section('content')


    <div class="content-gray-title mb10">
        <h4>
            <?php
            $nameBrand6 = 'Deleted Brand';
            $nameBrand7 = 'Deleted Brand';

            $urlBrand3 = 'home';
            $urlBrand4 = 'home';

            if ( isset( $brand3 ) ) {
                $urlBrand3 = url( Kinnect2::profileAddress( $brand3 ) );
            }

            if ( isset( $brand4 ) ) {
                $urlBrand4 = url( Kinnect2::profileAddress( $brand4 ) );
            }

            if ( isset( $brand6 ) ) {
                $nameBrand6 = $brand6->brand_name;
            }

            if ( isset( $brand7 ) ) {
                $nameBrand7 = $brand7->brand_name;
            }
            ?>
            <a href="{{$urlBrand3}}"><span>{{$nameBrand6}}</span></a>
            Vs.
            <a href="{{$urlBrand4}}"><span>{{$nameBrand7}}</span></a>
        </h4>
        <a class="btn fltR" title="Browse" href="{{ URL::to('battles/manage')}}">Browse</a>
        <a class="btn fltR mr10" title="Create Battel" href="{{ URL::to('battles/create')}}">Create Battle</a>
    </div>


    <div class="post-box">
        @if($battle->user_id == $user_id || $is_authorized)
            <div class="battle-detail">
                <div class="battle-title cf">
                    {{ucfirst($battle->title)}}
                </div>
                <div class="battle-brand">
                    <form action="">
                        <div class="battle-brand fltL">
                            <div class="brand-img brand-img-lft">
                                <img src="{{Kinnect2::profilePhoto(isset($brand3->photo_id) ? $brand3->photo_id : 0, @$brand3->id, 'brand')}}"
                                     alt="image">
                            </div>

                            <div class="brand-name brand-name-lft">
                                {{$nameBrand6}}
                            </div>


                            <div class="battle-brand-radio cf">
                                <label class="battle-radio-btn">
                                    @if ($battle->is_closed == 0)
                                        @if(Auth::user()->id != $battle->user_id)
                                            @if($votes == 'no')
                                                <input type="radio" name="Brand" value="apple" onclick="voting(this.id)"
                                                       id="{{$option1->id}}">
                                                <i></i>
                                                <div class="battle-radio-txt">{{$nameBrand6}}</div>
                                            @endif
                                        @endif
                                    @endif
                                </label>

                            </div>

                            <?php
                            $avg = vote_avg( $battle->vote_count, $option1->votes );
                            ?>
                            <div class="battle-brand-votes">
                                <div class="battle-txt-vote fltL">Votes:</div>
                                <div class="battle-vote-value fltR">{{$option1->votes}} <span>({{$avg}}%)</span></div>
                                <div class="battle-vote-bar">

                                    <span class="color-1" style="width: {{$avg}}%"></span>
                                </div>
                            </div>
                        </div>


                        <div class="battle-brand fltR">
                            <div class="brand-img brand-img-rght">
                                <img src="{{Kinnect2::profilePhoto(isset($brand4->photo_id) ? $brand4->photo_id : 0, @$brand4->id, 'brand')}}"
                                     alt="image">
                            </div>

                            <div class="brand-name brand-name-rght">{{$nameBrand7}}</div>
                            <div class="battle-brand-radio cf">
                                <label class="battle-radio-btn">
                                    @if ($battle->is_closed == 0)
                                        @if (Auth::user()->id != $battle->user_id)
                                            @if($votes == [])
                                                <input type="radio" name="Brand" value="Sony" onclick="voting(this.id)"
                                                       id="{{$option2->id}}">
                                                <i></i>
                                                <div class="battle-radio-txt">{{$nameBrand7}}</div>
                                            @endif
                                        @endif
                                    @endif
                                </label>
                            </div>
                            <?php
                            $avg = vote_avg( $battle->vote_count, $option2->votes );
                            ?>
                            <div class="battle-brand-votes fltR">
                                <div class="battle-txt-vote fltL">Votes:</div>
                                <div class="battle-vote-value fltR">{{$option2->votes}} <span>({{$avg}}%)</span></div>
                                <div class="battle-vote-bar">

                                    <span class="color-2" style="width: {{$avg}}%"></span>
                                </div>
                            </div>
                        </div>
                        <div class="battle-vs">Vs</div>
                    </form>
                </div>
            </div>
            <div class="feed-options">
                <a href="javascript:void(0);" title="Like" class="like"></a>
                <a href="javascript:void(0);" title="Dislike" class="dislike"></a>
                <a href="javascript:void(0);" title="Favourite" class="favourite"></a>
                <a href="javascript:void(0);" title="Share" class="share"></a>
                <a href="javascript:void(0);" title="Social" class="social-link"></a>
                <a href="javascript:void(0);" title="Flag" class="flag"></a>
            </div>
            <div class="otherLikes">
                <a href="javascript:void(0);" title="name">Jessica shire</a>‚ <a href="javascript:void(0);">Peter
                    John</a> and <a
                        href="javascript:void(0);">50 others</a> like this.
            </div>
            <div class="post-write-comment">
                <div class="options-detail">1000 Likes | 500 Dislikes | {{$battle->comment_count}} Comments | 1000
                    Shares
                </div>
                <div class="comment-pnl">
                    <a href="{{url(Kinnect2::profileAddress(Auth::user()))}}" title="username" class="user-image">
                        <img width="45" height="45"
                             src="{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_profile')}}"
                             alt="" title="user">
                    </a>

                    <div class="comment-text">
                        <a class="commentor-name"
                           href="{{url(Kinnect2::profileAddress(Auth::user()))}}">{{Auth::user()->displayname}}</a>

                        <p></p>
                        <span class=""><a href="javascript:void(0);"></a></span>
                        <span class="date"></span>
                    </div>
                </div>
            </div>
        @else
            {{Config::get('constants.NOT_AUTHORIZED')}}
        @endif
    </div>



    <script>
        function voting(clicked_id) {
            var battleVoteUrl = '{{url("battles")}}' + '/votes/' + clicked_id;
            window.location.href = battleVoteUrl;
        }
        ;
    </script>


@stop()
