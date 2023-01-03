<?php

namespace App\Exports;


use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view():View
    {
        return view('backend.excelview.productExports', [
            'products' => Product::all()
        ]);
    }
}
