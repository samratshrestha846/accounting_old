<?php

namespace App\Exports;

use App\Models\FiscalYear;
use App\Models\SubAccount;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use function App\NepaliCalender\datenep;

class ProfitandLossExport implements FromView
{
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $sub_accounts = SubAccount::whereIn('id', [8,9,10,13,11,12])->get();
        $date_in_eng = date('Y-m-d');
        $date_in_nep = datenep($date_in_eng);
        $current_fiscal_year = FiscalYear::latest()->first();
        return view('backend.excelview.profitandlossexcel', compact('current_fiscal_year', 'sub_accounts', 'date_in_nep'));
    }
}
