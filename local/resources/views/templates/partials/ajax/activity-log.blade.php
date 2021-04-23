{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 12-11-15 11:27 AM
    * File Name    : 

--}}
        <!-- Activity Area-->
<div class="content-gray-title">
    <h4>Activity Log</h4>
</div>
@if(count($data)>0)
    <?php $i = 1;
    foreach($data as $row):?>
    @if($row->type !== 'join' )
        <div class="activity-container">
            <div>
                <a href="{{url(Kinnect2::profileAddress(Auth::user()))}}">
                    <?php
                    if ($user->user_type == Config::get('constants.REGULAR_USER')) {
                        $name = $user->displayname;
                    } else {
                        $name = $user->brand_detail;
                        $name = $name->brand_name;
                    }
                    ?>
                    {{$name}}
                </a>
                {!!  activity_log_string($row)!!}
            </div>
            {{--<div class="posted-thing"><img src="{!! asset('local/public/assets/images/user-img.jpg') !!}" width="auto" height="auto" alt="" /></div>--}}
            <span>{{Carbon\Carbon::parse($row->created_at)->format('F d Y')}}</span>
        </div>
    @endif

    <?php

    endforeach?>
@else
    <div class="activity-container">No Activity!</div>
@endif
<a id="load_more" style="display: none;" href="javascript:void(0);">load more</a>
<div id="loading">
    <img id="loading-image" style="display:none" src="{!! asset('local/public/images/loading.gif') !!}"
         alt="Loading..."/>
</div>
