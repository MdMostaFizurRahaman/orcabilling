<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = 'rates';
    protected $guarded = [];

    public function tariff()
    {
        return $this->belongsTo('App\TariffName');
    }
}