<?php

namespace App\Exports;

use App\Models\Billing;
use App\Models\Client;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Events\AfterSheet;
// use Maatwebsite\Excel\Concerns\WithEvents;

class ClientsaleExport implements FromView
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
        $client = Client::findorFail( $this->id );
        $billings = Billing::latest()->with( 'client' )->with( 'payment_infos' )->where( 'client_id', $client->id )->where( 'billing_type_id', 1 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->get();

        return view('backend.excelview.clentsaleexcel',compact('client','billings'));

    }
}
