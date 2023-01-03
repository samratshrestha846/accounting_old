<?php

namespace App\Exports;

use App\Models\Account;
use App\Models\FiscalYear;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TrialBalanceFilterExport implements FromView
{
    public function __construct(int $id, string $start_date, string $end_date)
    {
        $this->id = $id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function view(): View
    {
        $mainaccounts = Account::with('sub_accounts','sub_accounts.child_accounts')->get();
        $current_fiscal_year = FiscalYear::where('id', $this->id)->first();
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        return view('backend.excelview.trialbalancefilter', compact('current_fiscal_year', 'mainaccounts', 'start_date', 'end_date'));
    }
}
