{{--

    * Created by   :  Muhammad Yasir
    * Project Name : local
    * Product Name : PhpStorm
    * Date         : 27-11-15 3:19 PM
    * File Name    : 

--}}
@foreach($strings as $string)

    <li value="175" class="@if($string['is-read'] == 1) notifications_read @else notifications_unread @endif ">
    <span class="notification_item_general notification_type_friend_accepted">
        <a href="{{url('goto/'.$string['notification_id'].'?redirect-uri='.base64_encode($string['url']))}}">
            {!! $string['string'] !!}
        </a>
    </span>
    </li>
@endforeach
