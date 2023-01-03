<?php

namespace App\Exports;

use App\Models\UpdateExcelExport as ModelsUpdateExcelExport;
use Maatwebsite\Excel\Concerns\FromCollection;

class UpdateExcelExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ModelsUpdateExcelExport::all();
    }
}
