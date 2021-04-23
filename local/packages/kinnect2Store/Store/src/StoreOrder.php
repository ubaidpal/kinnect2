<?php
namespace kinnect2Store\Store;

use Illuminate\Database\Eloquent\Model;

class StoreOrder extends Model
{
    protected $table = 'store_orders';
    protected $primaryKey = 'id';

    protected $fillable =  [''];

    public function user() {
        return $this->belongsTo('App\User', 'customer_id')->select(array('username', 'displayname'));
    }
    public function storeOrderTransaction(){
        return $this->hasOne('kinnect2Store\Store\StoreOrderTransaction','order_id');
    }

    public function delivery() {
        return $this->hasOne('kinnect2Store\Store\DeliveryCourier','order_id');
    }

    public function transaction() {
        return $this->hasOne('kinnect2Store\Store\StoreOrderTransaction','order_id')->orderBy('id', 'DESC');
    }
}
