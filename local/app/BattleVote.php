<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BattleVote extends Model
{
    protected $table = 'battle_votes';
    protected $fillable = ['battle_id','user_id','battle_option_id'];

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function Battle()
    {
        return $this->belongsTo('App\Battle');
    }
}