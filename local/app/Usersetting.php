<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Usersetting extends Model
{
    protected $table = 'user_settings';

    protected $fillable = ['category' ,'setting' , 'setting_value' , 'user_id'];

    protected $primaryKey = 'setting_id';
}