<?php

namespace App\Exports;

use App\Rate;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class RatesExport implements FromQuery
{
    use Exportable;

    private $tariff;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return Rate::query()->where('tariffname_id', $this->tariff);
    }

    public function forTariff($tariff)
    {
        $this->tariff = $tariff;
        return $this;
    }
}
