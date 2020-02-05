<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SummaryExport implements FromView
{
    private $collection;
    private $summary_type;

    // public function __construct($type)
    // {
    //     $this->type = $type;
    // }

    public function view(): View
    {
        return view('pages.reports.export-'.$this->summary_type.'-summary', [
            'collection' => $this->collection,
        ]);
    }

    public function forCollection($collection)
    {
        $this->collection = $collection;
    }

    public function forSummaryType($type)
    {
        $this->summary_type = $type;
    }

}
