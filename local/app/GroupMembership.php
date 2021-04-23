<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupMembership extends Model
{
    protected  $table = 'group_membership';
	protected $primaryKey = 'group_id';
}
