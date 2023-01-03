<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class CustomerExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view():View
    {

        return view('backend.excelview.customersExport', [
            'clients' => Client::all()
        ]);

    }
}
