<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserBrand extends Model
{
    protected $table = 'users_brands';

    protected $primaryKey = 'brand_id';
}