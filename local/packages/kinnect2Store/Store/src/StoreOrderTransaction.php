<?php
namespace kinnect2Store\Store;

use Illuminate\Database\Eloquent\Model;

class StoreOrderTransaction extends Model
{
    protected $table = 'store_order_transactions';
    protected $primaryKey = 'id';

    protected $fillable =  [''];

    public function storeOrder(){
        return $this->belongsTo('kinnect2Store\Store\StoreOrder','order_id');
    }

}
