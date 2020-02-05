<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $table = 'calls';

    protected $guarded = [];

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
