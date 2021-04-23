<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class BattleOption extends Model
{
    protected $table = 'battle_options';

    protected $fillable = [];

    public function Battle()
    {
        return $this->belongsTo('App\Battle');
    }

    public function Brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function brand_detail(  ) {
        return $this->hasOne('App\user', 'userable_id');
    }
}
