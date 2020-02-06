<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class TariffName extends Model
{
    protected $table = 'tariffnames';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function clients()
    {
        return $this->hasMany('App\Client', 'id', 'tariff_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency', 'currency_id');
    }

    public function gateways()
    {
        return $this->hasMany("App\Gateway", 'id', 'tariff_id');
    }

    public function rates()
    {
        return $this->hasMany("App\Rate", "tariffname_id");
    }

    public function rate($data)
    {
        $number = $data['Called'];

        return $this->hasOne("App\Rate", "tariffname_id")->selectRaw(
            "*, locate(prefix, $number) as prefix_status"
            )->whereRaw("locate(prefix, $number) = 1")
            ->where('from_day', '<=', $this->getDayNumberOfWeek($data['StartTime']))
            ->where('to_day', '>=', $this->getDayNumberOfWeek($data['EndTime']))
            ->where('from_hour', '<=', $this->getTime($data['StartTime']))
            ->where('to_hour', '>=', $this->getTime($data['EndTime']))
            ->orderBy('prefix', "desc")
            ->first();
    }

    public function rateByPrefix($prefix)
    {
        return $this->hasOne("App\Rate", "tariffname_id")->selectRaw(
            "*, locate(prefix, $prefix) as prefix_status"
            )->whereRaw("locate(prefix, $prefix) = 1")
            ->orderBy('prefix', "desc")
            ->first();
    }

    public function getDayNumberOfWeek($unix_timestamp)
    {
        $day = date('w', $unix_timestamp);
        return $day;
    }

    public function getTime($unix_timestamp)
    {
        $datetime = new DateTime("@$unix_timestamp");
        return $datetime->format('Hi');
    }
}
