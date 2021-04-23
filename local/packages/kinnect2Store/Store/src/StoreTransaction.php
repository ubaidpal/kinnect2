<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : kinnect2
 * Product Name : PhpStorm
 * Date         : 10-Mar-2016 11:45 AM
 * File Name    : StoreTransaction.php
 */

namespace kinnect2Store\Store;


use Illuminate\Database\Eloquent\Model;

class StoreTransaction extends Model
{
    public function user(){
        return $this->belongsTo('App\User','user_id')->select(['id','first_name','last_name','displayname']);
    }
}
