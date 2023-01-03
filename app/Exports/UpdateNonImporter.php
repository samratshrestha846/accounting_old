<?php

namespace App\Exports;

use App\Models\UpdateNonImporter as ModelsUpdateNonImporter;
use Maatwebsite\Excel\Concerns\FromCollection;

class UpdateNonImporter implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ModelsUpdateNonImporter::all();
    }
}
