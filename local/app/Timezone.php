<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    protected $table = 'time_zone';

    protected $fillable = ['country'];


}