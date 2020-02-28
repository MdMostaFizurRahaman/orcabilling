<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function company()
    {
        return $this->belongsTo('App\System\Company');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function items()
    {
        return $this->hasMany('App\InvoiceItems');
    }

}
