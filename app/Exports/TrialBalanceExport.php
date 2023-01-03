<?php
namespace App\Exports;
use App\Models\Account;
use App\Models\FiscalYear;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TrialBalanceExport implements FromView, ShouldAutoSize
{

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $mainaccounts = Account::with('sub_accounts','sub_accounts.child_accounts')->get();
        $current_fiscal_year = FiscalYear::latest()->first();
        return view('backend.excelview.trialbalanceexcel', compact('current_fiscal_year', 'mainaccounts'));
    }


}
