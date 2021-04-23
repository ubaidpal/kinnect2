<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdCampaign extends Model
{
    protected  $table = 'ad_campaigns';
    protected $guarded  = ['order'];
}
