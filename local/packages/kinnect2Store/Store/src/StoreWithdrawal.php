<?php

namespace kinnect2Store\Store;

use Illuminate\Database\Eloquent\Model;

class StoreWithdrawal extends Model
{
    protected $table = 'store_withdrawals';
    protected $primaryKey = 'id';

    protected $fillable =  [''];

    public function seller(){
        return $this->belongsTo('App\User', 'seller_id')->select(['id','displayname','username']);
    }
}
