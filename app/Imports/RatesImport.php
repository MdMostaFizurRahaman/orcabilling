<?php

namespace App\Imports;

use App\Rate;
use Maatwebsite\Excel\Concerns\ToModel;

class RatesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Rate([
            'tariffname_id' => $row[0],
            'prefix'        => $row[1],
            'description'   => $row[2],
            'voice_rate'    => $row[3],
            'from_day'      => $row[4],
            'to_day'        => $row[5],
            'from_hour'     => $row[6],
            'to_hour'       => $row[7],
            'grace_period'  => $row[8],
            'minimal_time'  => $row[9],
            'resolution'    => $row[10],
            'rate_multiplier'=> $row[11],
            'free_seconds'  => $row[12],
            'effective_date'=> $row[13],
        ]);
    }
}
