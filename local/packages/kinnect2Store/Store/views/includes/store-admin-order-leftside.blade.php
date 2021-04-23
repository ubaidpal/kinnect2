<?php
$allOrders          = (strpos($_SERVER['REQUEST_URI'],'/orders') !== false)? 'active' : '';
$manage_feedback    = (strpos($_SERVER['REQUEST_URI'],'/manage_reviews') !== false)? 'active' : '';
$shipping_address   = (strpos($_SERVER['REQUEST_URI'],'/shipping-address') !== false)? 'active' : '';
?>
<div class="leftPnl">
    <div class="box">
        <div id="cssmenu">
            <h2>Manage Orders</h2>
            <ul>
               <?php $user = getUserDetail($url_user_id) ?>
               <li class="{{$allOrders}}"><a href="{{url('store/'.$user->username.'/admin/orders')}}" ><span>All Orders</span></a></li>
               <li class="{{$manage_feedback}}"><a href="{{ url('store/'.$user->username.'/admin/manage_reviews') }}" ><span>Manage Feedbacks</span></a></li>
            </ul>
        </div>
    </div>
 </div>
 
