<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\FiscalYear;
use App\Models\HotelFood;
use App\Models\HotelOrder;
use Illuminate\Http\Request;

use function App\NepaliCalender\dateeng;
use function App\NepaliCalender\datenep;

class SalesReportController extends Controller
{


    public function sales_report(Request $request, $id)
    {
        if (!$request->user()->can('hotel-order-sales')) {
            return view('backend.permission.permission');
        }

        $foodDetails = HotelFood::findOrFail($id);

        $foodSalesBillings = $foodDetails->billing()->paginate();

        return view('backend.hotel.food.sales_report', compact('foodDetails', 'foodSalesBillings'));
    }

    public function index(Request $request)
    {

        if (!$request->user()->can('hotel-order-invoice')) {
            return view('backend.permission.permission');
        }

        $today = date("Y-m-d");

        $nepali_today = datenep($today);
        $exploded_date = explode("-", $nepali_today);

        $current_year = $exploded_date[0] . '/' . ($exploded_date[0] + 1);
        $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $actual_year = explode("/", $current_fiscal_year->fiscal_year);
        $fiscal_years = FiscalYear::latest()->get();


        $hotelSales = HotelOrder::with('customer', 'table', 'waiter', 'createdBy', 'order_items', 'tax', 'billing')
            ->paginate();
        return view('backend.hotel.sales-report.index', compact('fiscal_years', 'actual_year', 'hotelSales'));
    }

    


    public function view_sales_single(Request $request, $hotelSale)
    {

        if (!$request->user()->can('hotel-order-invoice')) {
            return view('backend.permission.permission');
        }

        $hotelSale = HotelOrder::findOrFail($hotelSale);

        $today = date("Y-m-d");

        $nepali_today = datenep($today);
        $exploded_date = explode("-", $nepali_today);

        $current_year = $exploded_date[0] . '/' . ($exploded_date[0] + 1);
        $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $actual_year = explode("/", $current_fiscal_year->fiscal_year);

        return view('backend.hotel.sales-report.view', compact('hotelSale', 'current_year', 'nepali_today'));
    }
}
