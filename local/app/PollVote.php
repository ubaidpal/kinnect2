<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    protected $table = 'poll_votes';

    protected $fillable = ['poll_id','user_id','poll_option_id'];

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function Poll()
    {
        return $this->belongsTo('App\Poll');
    }
}