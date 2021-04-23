<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected  $table = 'reports';
    protected $primaryKey = 'report_id';

    public function post() {
        return $this->belongsTo('App\ActivityAction','action_id');
    }
}
