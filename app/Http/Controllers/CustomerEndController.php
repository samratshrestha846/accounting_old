<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ClientOrder;
use App\Models\DealerUserCompany;
use App\Models\FiscalYear;
use App\Models\Province;
use App\Models\PurchaseOrder;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function App\NepaliCalender\dateeng;
use function App\NepaliCalender\datenep;

class CustomerEndController extends Controller
{
    //

    public function pruchaseordercreate(){
        $fiscal_years = FiscalYear::latest()->get();
        $date = date( 'Y-m-d' );
        $nepalidate = datenep( $date );
        $exploded_date = explode( '-', $nepalidate );

        $current_year = $exploded_date[0].'/'.( $exploded_date[0] + 1 );
        $current_fiscal_year = FiscalYear::where( 'fiscal_year', $current_year)->first();
        $units = Unit::all();
        return view('customerbackend.purchaseordercreate', compact('fiscal_years', 'current_fiscal_year', 'units'));
    }

    public function pruchaseorderindex(){
        $user_id = Auth::user()->id;
        $currentcomp = DealerUserCompany::with('dealeruser')->where('dealer_user_id', $user_id)
            ->where('is_selected', 1)
            ->first();
        $client_id = $currentcomp->dealeruser->client_id;
        $clientOrders = ClientOrder::where('client_id', $client_id)->paginate(10);
        return view('customerbackend.purchaseorderindex', compact('clientOrders'));
    }
}
