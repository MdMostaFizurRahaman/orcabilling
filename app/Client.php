<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use Notifiable;

    protected $table = 'clients';
    protected $guarded = [];

    public function tariff()
    {
        return $this->belongsTo("App\TariffName", 'tariff_id', 'id');
    }

    public function ips()
    {
        return $this->hasMany('App\Ip');
    }

    public function ip($ip)
    {
        return $this->hasOne('App\Ip')->where('ip', $ip)->first();
    }

    public function calls()
    {
        return $this->hasMany('App\Call');
    }

    public function failed_calls()
    {
        return $this->hasMany('App\FailedCall');
    }

    public function getNameAttribute()
    {
        return $this->full_name;
    }
}
