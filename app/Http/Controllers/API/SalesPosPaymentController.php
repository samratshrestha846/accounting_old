<?php

namespace App\Http\Controllers\API;

use App\Actions\Billing\CreateOutletBillingAction;
use App\Actions\Pos\UpdateOutletProductStockAction;
use App\Exceptions\GrossTotalInvalidException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SalesPaymentRequest;
use App\Models\Billing;
use App\Models\BillingExtra;
use App\Models\FiscalYear;
use App\Models\Outlet;
use App\Models\OutletProduct;
use App\Models\PaymentInfo;
use App\Models\SalesRecord;
use App\Models\Tax;
use App\Models\TaxInfo;
use App\Services\ProductSaleService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function App\NepaliCalender\datenep;

class SalesPosPaymentController extends Controller
{
    public function store(SalesPaymentRequest $request, Outlet $outlet){

        $this->can('manage-pos');

        try {
            $billing = (new CreateOutletBillingAction())->execute(auth()->user(), $outlet, $request);
        } catch(GrossTotalInvalidException $e) {
            return response()->json([
                'error' => true,
                'status' => 412,
                'message' => $e->getMessage()
            ], 412);
        }

        return $this->responseOk(
            'Sales Successfully Created',
            $billing,
            201
        );
    }
}
