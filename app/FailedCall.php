<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FailedCall extends Model
{
    protected $table = 'failed_calls';
    protected $guarded = [];
    public $timestamps = false;

    public function client()
    {
        return $this->belongsTo("App\Client", 'client_id');
    }

    public function rate()
    {
        return $this->belongsTo('App\Rate', 'tariff_id');
    }

    public function gateway()
    {
        return $this->belongsTo("App\Gateway", 'id_route');
    }

}
