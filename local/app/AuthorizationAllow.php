<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorizationAllow extends Model
{
    protected $table = 'authorization_allows';
    protected $fillable = ['resource_type','resource_id','action','permission','params'];

}
