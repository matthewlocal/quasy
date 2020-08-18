<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLogs extends Model
{
    protected $fillable = [
        'user_id', 'transaction','ip'
    ];
}
