<?php
namespace kinnect2Store\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $table = 'store_product_categories';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

}

