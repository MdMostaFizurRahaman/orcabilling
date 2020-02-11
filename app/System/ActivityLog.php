<?php

namespace App\System;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'users_log';
    protected $primaryKey = 'id_log';
    protected $guarded = [];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
