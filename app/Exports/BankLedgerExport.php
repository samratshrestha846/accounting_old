<?php

namespace App\Exports;

use App\Models\Bank;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Events\AfterSheet;
// use Maatwebsite\Excel\Concerns\WithEvents;

class BankLedgerExport implements FromView
{

    /**
    * @return \Illuminate\Support\Collection
    */

    public function view(): View
    {
        $banks = Bank::with(['purchaseBillings', 'purchaseReturnBillings', 'salesBillings', 'salesReturnBillings', 'receiptBillings', 'paymentBillings'])->get();

        return view('backend.excelview.bankledgerexcel',compact('banks'));

    }
}
