<?php
namespace kinnect2Store\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreProduct extends Model
{
    use SoftDeletes;
    protected $table = 'store_products';
    protected $primaryKey = 'id';

    protected $fillable =  [''];
    protected $dates = ['deleted_at'];

}
