<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SummaryExport implements FromView
{
    private $collection;
    private $exportPage;

    // public function __construct($type)
    // {
    //     $this->type = $type;
    // }

    public function view(): View
    {
        return view($this->exportPage, [
            'collection' => $this->collection,
        ]);
    }

    public function forCollection($collection)
    {
        $this->collection = $collection;
    }

    public function forExportPage($exportPage)
    {
        $this->exportPage = $exportPage;
    }

}
