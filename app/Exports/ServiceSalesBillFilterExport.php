<?php

namespace App\Exports;

use App\Models\FiscalYear;
use App\Models\SalesBills;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ServiceSalesBillFilterExport implements FromView
{
    public function __construct(array $selectedcsvid, int $id, string $start_date, string $end_date)
    {
        $this->selectedcsvid = $selectedcsvid;
        $this->id = $id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function view(): View
    {
        $fiscal_year = FiscalYear::findorFail($this->id)->fiscal_year;
        $selectedid = $this->selectedcsvid;
        if($selectedid[0] == '')
        {
            $billings = SalesBills::latest()->where('status', '1')->where('is_cancelled', '0')->where('eng_date', '>=', $this->start_date)->where('eng_date', '<=', $this->end_date)->get();
        }
        else
        {
            $billings = SalesBills::latest()->whereIn('id', $selectedid)->where('status', '1')->where('is_cancelled', '0')->where('eng_date', '>=', $this->start_date)->where('eng_date', '<=', $this->end_date)->get();
        }
        return view('backend.excelview.servicesalesview', compact('billings', 'fiscal_year'));
    }
}
