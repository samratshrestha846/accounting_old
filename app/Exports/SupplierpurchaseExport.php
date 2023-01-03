<?php

namespace App\Exports;

use App\Models\Billing;
use App\Models\Vendor;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Events\AfterSheet;
// use Maatwebsite\Excel\Concerns\WithEvents;

class SupplierpurchaseExport implements FromView
{

    public function __construct(int $id)
    {
        $this->id = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */

    public function view(): View
    {
        $supplier = Vendor::findorFail( $this->id );
            $billings = Billing::latest()->with( 'suppliers' )->with( 'payment_infos' )->where( 'vendor_id', $supplier->id )->where( 'billing_type_id', 2 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->get();

        return view('backend.excelview.supplierpurchaseexcel',compact('supplier','billings'));

    }
}
