<?php
namespace kinnect2Store\Store;

use Illuminate\Database\Eloquent\Model;

class StoreOrderItemAttribute extends Model
{
    protected $table = 'store_order_item_attributes';

    protected $primaryKey = 'id';

    protected $fillable =  [''];
}
