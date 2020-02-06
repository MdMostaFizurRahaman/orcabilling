<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    protected $table = 'ips';
    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo('App\Client', 'client_id');
    }
}
