<?php

namespace App\Exports;

use App\Models\FiscalYear;
use App\Models\SubAccount;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use function App\NepaliCalender\dateeng;
use function App\NepaliCalender\datenep;

class ProfitandLossReportExport implements FromView
{
    public function __construct(int $id, string $starting_date, string $ending_date)
    {
        $this->id = $id;
        $this->starting_date = $starting_date;
        $this->ending_date = $ending_date;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $sub_accounts = SubAccount::whereIn('id', [8,9,10,13,11,12])->get();
        $selected_fiscal_year = FiscalYear::latest()->first();

        $starting_date = $this->starting_date;
        $ending_date = $this->ending_date;

        $start_date = dateeng($starting_date);
        $end_date = dateeng($ending_date);
        return view('backend.excelview.profitandlossfilterexport', compact('selected_fiscal_year', 'sub_accounts', 'starting_date', 'ending_date', 'start_date', 'end_date'));
    }
}
