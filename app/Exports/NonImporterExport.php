<?php

namespace App\Exports;

use App\Models\ProductNonImporter;
use Maatwebsite\Excel\Concerns\FromCollection;

class NonImporterExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ProductNonImporter::all();
    }
}
