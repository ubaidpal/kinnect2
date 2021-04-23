<?php
$allOrders = (strpos($_SERVER['REQUEST_URI'], '/my-orders') !== false) ? 'active' : '';
$manage_feedback = (strpos($_SERVER['REQUEST_URI'], '/manage-feedbacks') !== false) ? 'active' : '';
$shipping_address = (strpos($_SERVER['REQUEST_URI'], '/shipping-address') !== false) ? 'active' : '';
?>
<div class="leftPnl">
    <div class="box admin">
        <div id="cssmenu">
            <h2>Manage Orders</h2>
            <ul>
                <?php $user = getUserDetail($url_user_id) ?>
                <li class="{{$allOrders}}"><a href="{{url('store/my-orders')}}" title="All Orders"><span>All Orders</span></a></li>
                <li class="{{$manage_feedback}} "><a title="Feedbacks"
                            href="{{ url('store/manage-feedbacks') }}"><span class="pending_reviews_requests">Feedbacks ({{countRequestToReviseCurrentUser()}})</span></a></li>
                {{--<li class="{{$shipping_address}}"><a href="{{ url('store/shipping-address') }}"><span>Shipping Address</span></a></li>--}}
            </ul>
        </div>
    </div>
</div>
 
