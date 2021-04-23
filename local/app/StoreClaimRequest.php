<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreClaimRequest extends Model
{

    public function seller(){
        return $this->belongsTo('App\User', 'seller_id')->select(['id','displayname','username']);
    }
    public function store_claim(){
        return $this->belongsTo('kinnect2Store\Store\StoreClaim','owner_id')
                ->select(['bank_account_id','amount','id']);
    }
}
