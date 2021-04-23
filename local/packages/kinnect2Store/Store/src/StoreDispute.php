<?php

namespace kinnect2Store\Store;

use Illuminate\Database\Eloquent\Model;

class StoreDispute extends Model
{

    public function album() {
        return $this->hasOne('kinnect2Store\Store\StoreAlbums', 'owner_id')->where('owner_type', 'order_dispute');
    }

    public static function boot() {

        parent::boot();

        StoreDispute::creating(function ($dispute) {
            $dispute->reference_id = random_id(10);
        });
    }

    public static function find($id) {
        return StoreDispute::where('reference_id', $id)->first();
    }

    public function order() {
        return $this->belongsTo('kinnect2Store\Store\StoreOrder', 'order_id');
    }
    public function user() {
        return $this->belongsTo('App\User', 'owner_id')->select(array('id', 'displayname'));
    }
}
