<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    protected $table = 'gateways';
    protected $guarded = [];

    public function tariff()
    {
        return $this->belongsTo('App\TariffName', 'tariff_id', 'id');
    }

    public function calls()
    {
        return $this->hasMany('App\Call', 'id_route', 'id');
    }

    public function failed_calls()
    {
        return $this->hasMany('App\FailedCall', 'id_route', 'id');
    }
}
