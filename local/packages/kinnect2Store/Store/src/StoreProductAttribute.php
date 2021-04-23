<?php
namespace kinnect2Store\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreProductAttribute extends Model
{
    protected $table = 'store_product_attributes';
    protected $primaryKey = 'id';

    protected $fillable =  [''];

}
