<?php
namespace kinnect2Store\Store;

use Illuminate\Database\Eloquent\Model;

class DeliveryCourier extends Model
{
    protected $table      = 'store_order_delivery_info';
    protected $primaryKey = 'id';
}

