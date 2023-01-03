<?php

namespace App\Exports;

use App\Models\FiscalYear;
use App\Models\SalesBills;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ServiceSalesBillExport implements FromView
{
    public function __construct(array $selectedcsvid, int $id)
    {
        $this->selectedcsvid = $selectedcsvid;
        $this->id = $id;
    }

    public function view(): View
    {
        $fiscal_year = FiscalYear::findorFail($this->id)->fiscal_year;
        $selectedid = $this->selectedcsvid;
        if($selectedid[0] == '')
        {
            $billings = SalesBills::latest()->where('status', '1')->where('is_cancelled', '0')->get();
        }
        else
        {
            $billings = SalesBills::latest()->whereIn('id', $selectedid)->where('status', '1')->where('is_cancelled', '0')->get();
        }
        return view('backend.excelview.servicesalesview', compact('billings', 'fiscal_year'));
    }
}
