@extends('layouts.default')
@section('content')
        <!-- Polls Detail-->
<div class="content-gray-title mb10">
    <?php $Owner = Kinnect2::groupOwner($poll->user_id); ?>
    <a href="{{url(Kinnect2::profileAddress($Owner))}}">
        <h4>{{$Owner->displayname}}'s Poll</h4>
    </a>
</div>

<!-- Post Div-->
<div class="post-box">
    @if($Owner->id == $user_id || $is_authorized)
        <div class="battle-detail">
            <div class="polls-detail">
                <div class="polls-detail-title">
                    <div class="polls-title-img">
                        <?php $Owner = Kinnect2::groupOwner($poll->user_id); ?>
                        <a href="{{url(Kinnect2::profileAddress($Owner))}}">
                            <img src="{{Kinnect2::getPhotoUrl($Owner->photo_id, $Owner->id, 'user', 'thumb_profile')}}"
                                 alt="image">
                        </a>
                    </div>

                    <a class="polls-title-name" href="{{url(Kinnect2::profileAddress($Owner))}}">
                        {{$Owner->displayname}}
                    </a>
                </div>

                <div class="polls-detail-text">
                    {{$poll->title}}
                    <p>{{$poll->description}}</p>
                </div>

                <ul>
                    <?php $l = 1;?>
                    @foreach($options as $option)
                        @if ($poll->is_closed == 0)
                            @if(Auth::user()->id != $poll->user_id)
                                @if($votes == 'no')

                                    <div class="polls-progress-bar">
                                        <input type="radio" name="Brand" value="apple" onclick="voting(this.id)"
                                               id="{{$option->id}}">
                                        <label for="{{$option->id}}">{{$option->poll_option}}</label>
                                    </div>
                                @else
                                    <?php
                                    $avg = vote_avg($poll->vote_count, $option->votes);
                                    if ($avg >= 50) {
                                        $class = 'green';
                                    } elseif ($avg >= 0) {
                                        $class = 'red';
                                    } else {
                                        $class = 'red';
                                    }
                                    ?>
                                    <div class="polls-progress-bar">
                                        <div class="polls-vote-name fltL">{{$option->poll_option}} </div>
                                        <div class="polls-vote-value fltR">Votes&colon; {{$option->votes}} ({{$avg}}%)
                                        </div>
                                        <div class="progress-bar">

                                            <span class="green color-{{ $l }}" style="width: {{$avg}}%"></span>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <?php
                                $avg = vote_avg($poll->vote_count, $option->votes);
                                if ($avg >= 50) {
                                    $class = 'green';
                                } elseif ($avg >= 0) {
                                    $class = 'red';
                                } else {
                                    $class = 'red';
                                }
                                ?>
                                <div class="polls-progress-bar">
                                    <div class="polls-vote-name fltL">{{$option->poll_option}}</div>
                                    <div class="polls-vote-value fltR">Votes&colon; {{$option->votes}} ({{$avg}}%)</div>

                                    <div class="progress-bar">
                                        <span class="green color-{{ $l }}" style="width: {{$avg}}%"></span>
                                    </div>
                                </div>
                            @endif
                        @else
                            <?php
                            $avg = vote_avg($poll->vote_count, $option->votes);
                            if ($avg >= 50) {
                                $class = 'green';
                            } elseif ($avg >= 0) {
                                $class = 'red';
                            } else {
                                $class = 'red';
                            }
                            ?>
                            <div class="polls-progress-bar">
                                <div class="polls-vote-name fltL">{{$option->poll_option}}</div>
                                <div class="polls-vote-value fltR">Votes&colon; {{$option->votes}} ({{$avg}}%)</div>

                                <div class="progress-bar">
                                    <span class="green color-{{ $l }}" style="width: {{$avg}}%"></span>
                                </div>
                            </div>
                        @endif
                        <?php $l++;?>
                    @endforeach
                </ul>
            </div>
            <p>Total number of votes: {{$poll->vote_count}}</p>
        </div>
        <div class="feed-options">
            <a href="javascript:();" title="Like" class="like"></a>
            <a href="javascript:();" title="Dislike" class="dislike"></a>
            <a href="javascript:();" title="Favourite" class="favourite"></a>
            <a href="javascript:();" title="Share" class="share"></a>
            <a href="javascript:();" title="Social" class="social-link"></a>
            <a href="javascript:();" title="Flag" class="flag"></a>
        </div>
        <div class="otherLikes">
            <a href="javascript:();" title="name">Jessica shire</a>‚ <a href="javascript:();">Peter John</a> and <a
                    href="javascript:();">50 others</a> like this.
        </div>
        <div class="post-write-comment">
            <div class="options-detail">1000 Likes | 500 Dislikes | {{$poll->comment_count}} Comments | 1000 Shares
            </div>
            <div class="comment-pnl">
                <a href="{{url(Kinnect2::profileAddress(Auth::user()))}}" title="username" class="user-image">
                    <img width="45" height="45"
                         src="{{Kinnect2::getPhotoUrl(Auth::user()->photo_id, Auth::user()->id, 'user', 'thumb_profile')}}"
                         alt="" title="user">
                </a>

                <div class="comment-text">

                    <a class="commentor-name"
                       href="{{url(\Kinnect2::profileAddress(Auth::user()))}}">{{Auth::user()->displayname}}</a>

                    <p></p>
                    <span class=""><a href="javascript:();"></a></span>
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
        var pollVoteUrl = '{{url("polls")}}' + '/votes/' + clicked_id;
        window.location.href = pollVoteUrl;
    }
</script>


@stop()
