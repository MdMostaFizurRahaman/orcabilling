<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ClientPasswordResetNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    use Notifiable;

    protected $table = 'clients';
    protected $guarded = [];

    public function tariff()
    {
        return $this->belongsTo("App\TariffName", 'tariff_id', 'id');
    }

    public function currency()
    {
                                // $related, $through, $through local_key, $related local_key, $through foreign_key, $related foreign_key
        return $this->hasOneThrough("App\Currency", "App\TariffName", 'id', 'id', 'tariff_id', 'currency_id');
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

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ClientPasswordResetNotification($token));
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
