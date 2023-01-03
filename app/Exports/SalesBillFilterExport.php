<?php

namespace App\Exports;

use App\Models\Billing;
use App\Models\Billingtype;
use App\Models\FiscalYear;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SalesBillFilterExport implements FromView
{
    public function __construct(array $selectedcsvid, int $id, string $start_date, string $end_date, int $billingtype_id, int $pos_data)
    {
        $this->selectedcsvid = $selectedcsvid;
        $this->id = $id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->billingtype_id = $billingtype_id;
        $this->pos_data = $pos_data;
    }

    public function view(): View
    {
        $fiscal_year = FiscalYear::findorFail($this->id)->fiscal_year;
        $billing_type = Billingtype::findorFail($this->billingtype_id);
        $selectedid = $this->selectedcsvid;

        $pos_data = $this->pos_data;
        if($pos_data == 1)
        {
            $billings = Billing::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', $this->billingtype_id)->where('status', '1')->where('is_pos_data', 1)->where('is_cancelled', '0')->where('eng_date', '>=', $this->start_date)->where('eng_date', '<=', $this->end_date)->get();
        }
        else if($selectedid[0] == ''){
            $billings = Billing::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', $this->billingtype_id)->where('status', '1')->where('is_pos_data', 0)->where('is_cancelled', '0')->where('eng_date', '>=', $this->start_date)->where('eng_date', '<=', $this->end_date)->get();
        }else{
            $billings = Billing::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', $this->billingtype_id)->whereIn('id', $selectedid)->where('status', '1')->where('is_pos_data', 0)->where('is_cancelled', '0')->where('eng_date', '>=', $this->start_date)->where('eng_date', '<=', $this->end_date)->get();
        }
        return view('backend.excelview.salesBills', compact('billings', 'fiscal_year', 'billing_type', 'pos_data'));
    }
}
