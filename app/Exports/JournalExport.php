<?php

namespace App\Exports;

use App\Models\FiscalYear;
use App\Models\JournalVouchers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JournalExport implements FromView
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
        $fiscal_year = FiscalYear::findorFail($this->id)->fiscal_year;
        $journalvouchers = JournalVouchers::where('fiscal_year_id', $this->id)->get();
        return view('backend.excelview.journalexcel', compact('journalvouchers', 'fiscal_year'));
    }
}
