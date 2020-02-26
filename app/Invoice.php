<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public function client()
    {
        return $this->hasOne('App\Client');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function company()
    {
        return $this->hasOne('App\System\Company');
    }
}
