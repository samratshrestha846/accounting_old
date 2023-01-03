<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FiscalYear;
use Illuminate\Http\Request;

class FiscalYearController extends Controller
{
    public function index()
    {
        $fiscalYears = FiscalYear::select('id','fiscal_year','created_at')->get();

        return $this->responseOk(
            "Fiscal year fetched successfully",
            $fiscalYears
        );
    }
}
