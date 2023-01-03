<?php

namespace App\Exports;

use App\Models\FiscalYear;
use App\Models\JournalVouchers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JournalFilterExport implements FromView
{
    public function __construct(int $id, string $start_date, string $end_date)
    {
        $this->id = $id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function view(): View
    {
        $fiscal_year = FiscalYear::findorFail($this->id)->fiscal_year;
        $journalvouchers = JournalVouchers::where('fiscal_year_id', $this->id)->where('entry_date_english', '>=', $this->start_date)->where('entry_date_english', '<=', $this->end_date)->get();
        return view('backend.excelview.journalexcel', compact('journalvouchers', 'fiscal_year'));
    }
}
