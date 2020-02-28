<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItems extends Model
{
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }
}
