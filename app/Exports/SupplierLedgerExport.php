<?php

namespace App\Exports;


use App\Models\Vendor;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SupplierLedgerExport implements FromView
{


    /**
    * @return \Illuminate\Support\Collection
    */

    public function view(): View
    {
        $vendors = Vendor::with(['purchaseBillings', 'purchaseReturnBillings'])->get();

        return view('backend.excelview.suppliersLedger',compact('vendors'));

    }



}
