<?php

namespace App\Http\Controllers\API;

use App\Actions\Billing\CreatePurchaseBillingAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseBillingRequest;
use App\Http\Resources\PurchaseBillingResource;
use App\Models\Billing;
use App\Models\Godown;
use App\Models\Vendor;
use Illuminate\Http\Request;

class PurchaseBillingController extends Controller
{
    public function index(Request $request)
    {
        $this->can('manage-purchases');

        $perPage = $request['per_page'];

        $billings = Billing::query()->with('fiscal_year','suppliers')->wherePurchaseBilling();

        if($perPage) {
            $billings = $billings->paginate($perPage);
            return PurchaseBillingResource::collection($billings);
        }

        return $this->responseOk(
            'Sales Billing fetched successfully',
            PurchaseBillingResource::collection($billings->get())
        );
    }

    public function store(StorePurchaseBillingRequest $request, Vendor $vendor)
    {
        $this->can('manage-purchases');

        $billing = (new CreatePurchaseBillingAction())->execute(auth()->user(), $vendor, $request);

        return $this->responseOk(
            'Purchase Billing created successfully',
            $billing,
            201
        );
    }
}
