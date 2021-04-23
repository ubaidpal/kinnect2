<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    protected $table = 'poll_options';

    protected $fillable = ['poll_option'];

    public function Poll()
    {
        return $this->belongsTo('App\Poll');
    }

}