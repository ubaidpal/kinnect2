<?php
namespace kinnect2Store\Store;

use Illuminate\Database\Eloquent\Model;

class StoreProductStat extends Model
{
    protected $table = 'store_product_statics';
    protected $primaryKey = 'id';

    protected $fillable =  ['stat_type', 'user_id', 'user_type', 'user_age', 'user_gender', 'user_region', 'user_ip', 'product_owner_id', 'product_id'];

}

