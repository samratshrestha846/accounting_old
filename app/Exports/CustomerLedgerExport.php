<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Events\AfterSheet;
// use Maatwebsite\Excel\Concerns\WithEvents;

class CustomerLedgerExport implements FromView
{

    /**
    * @return \Illuminate\Support\Collection
    */

    public function view(): View
    {
        $customers = Client::with(['salesBillings', 'salesReturnBillings'])->get();

        return view('backend.excelview.clientledgerexcel',compact('customers'));

    }
}
