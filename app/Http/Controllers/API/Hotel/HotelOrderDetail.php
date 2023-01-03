<?php

namespace App\Http\Controllers\API\Hotel;

use App\Http\Controllers\Controller;
use App\Services\SalesReportService;
use Illuminate\Http\Request;

class HotelOrderDetail extends Controller
{
    public function getTodaySaleOrderReport(SalesReportService $salesReportService)
    {
        $this->can(['hotel-order-invoice', 'hotel-order-view']);

        return [
            'data' => $salesReportService->getHotelOrderTodaySaleReport(auth()->user())
        ];
    }
}
