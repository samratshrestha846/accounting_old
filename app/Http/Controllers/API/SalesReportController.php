<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Outlet;
use App\Services\SalesReportService;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    protected SalesReportService $salesReportService;

    public function __construct()
    {
       $this->salesReportService = new SalesReportService();
    }

    public function getTodaySalesReport(Outlet $outlet)
    {
        $this->can('manage-pos');

        $user = auth()->user();

        if(!$user->canAccessOutlet($outlet->id)){
            return response()->json([
                'error' => true,
                'status' => 401,
                'message' => "You are not authorized to view this outlet salesreport"
            ], 401);
        }

        $salesReport = $this->salesReportService->getOuletTodaySalesReport($user, $outlet->id);

        return response()->json([
            'data' => $salesReport
        ]);
    }
}
