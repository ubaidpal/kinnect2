<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
    protected $table = 'battles';

    protected $fillable = ['title','description','startTime','endTime','search','is_closed','view_count','comment_count'];

    public function User()
    {
        return $this->belongsTo('App\User');
    }
    public function BattleOption()
    {
        return $this->hasMany('App\BattleOption');
    }
    public function BattleVote()
    {
        return $this->hasMany('App\BattleVote');
    }
    public function getCheckClosedAttribute($battle)
    {
        if ( $this->attributes['is_closed'] == 1){
            $this->attributes['is_closed'] = 0;
        }
        else{
            $this->attributes['is_closed'] = 1;
        }

        return($this->attributes['is_closed']);
    }
}