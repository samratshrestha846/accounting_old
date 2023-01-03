<?php

namespace App\Exports;


use App\Models\Vendor;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\WithColumnWidths;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class SupplierExport implements FromView, WithEvents
{


    /**
    * @return \Illuminate\Support\Collection
    */

    public function view(): View
    {
        $vendors = Vendor::latest()->get();

        return view('backend.excelview.suppliers',compact('vendors'));

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {

                $event->sheet->getRowDimension('1')->setRowHeight(40);
                $event->sheet->getColumnDimension('A')->setWidth(50);

            },
        ];
    }


}
