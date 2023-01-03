<?php

namespace App\Exports;

use App\Models\ChildAccount;
use App\Models\FiscalYear;
use App\Models\JournalExtra;
use App\Models\JournalVouchers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use function App\NepaliCalender\dateeng;

class LedgerExport implements FromView
{
    public function __construct(int $fiscal_year_id, int $id, string $starting_date, string $ending_date)
    {
        $this->fiscal_year_id = $fiscal_year_id;
        $this->id = $id;
        $this->starting_date = $starting_date;
        $this->ending_date = $ending_date;
    }

    public function view(): View
    {
        $journal_extras = JournalExtra::where('child_account_id', $this->id)
                            ->whereHas('journal_voucher', function($q){
                                $q->orderBy('id', 'ASC');
                            })->get();
        $childAccount = ChildAccount::findorFail($this->id);

        $selected_fiscal_year = FiscalYear::findorFail($this->fiscal_year_id);

        $start_date = dateeng($this->starting_date);
        $end_date = dateeng($this->ending_date);
        $starting_date = $this->starting_date;
        $ending_date = $this->ending_date;

        $opening_balance = 0;
        foreach ($journal_extras as $extra)
        {
            $previous_journal = JournalVouchers::where('id', $extra->journal_voucher_id)
                                                ->where('entry_date_english', '<=', $start_date)
                                                ->where('is_cancelled', 0)
                                                ->where('status', 1)
                                                ->first();
            if($previous_journal)
            {
                $opening_balance = $opening_balance + $extra->debitAmount - $extra->creditAmount;
            }
        }

        $main_opening_balance = $childAccount->opening_balance + $opening_balance;

        return view('backend.excelview.ledgerexcel', compact('journal_extras', 'childAccount', 'main_opening_balance', 'selected_fiscal_year', 'start_date', 'end_date', 'starting_date', 'ending_date'));
    }
}
