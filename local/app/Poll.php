<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $table = 'polls';

    protected $fillable = ['title','description','startTime','endTime','search','is_closed','view_count','comment_count'];

    public function User()
    {
        return $this->belongsTo('App\User');
    }
    public function PollOption()
    {
        return $this->hasMany('App\PollOption');
    }
    public function PollVote()
    {
        return $this->hasMany('App\PollVote');
    }
    public function getCheckClosedAttribute($poll)
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