<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $guarded = [];

    public function tariffnames ()
    {
        return $this->hasMany('App\TariffName');
    }
}
