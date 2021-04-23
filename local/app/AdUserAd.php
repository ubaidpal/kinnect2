<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdUserAd extends Model
{
    protected  $table = 'ad_user_ads';
    protected $primaryKey = 'id';

    protected  $guarded  = [''];
}
