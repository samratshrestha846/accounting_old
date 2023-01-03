<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BillingResource;
use App\Models\Billing;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request['per_page'];

        $billings = QueryBuilder::for(Billing::class)
        ->allowedIncludes(['client','suppliers','billingextras.product.primaryUnit','payment_infos'])
        ->with('fiscal_year')
        ->when($request['billing_type_id'], function($q, $value) {
            return $q->where('billing_type_id', $value);
        });

        if($perPage) {
            return BillingResource::collection($billings->paginate($perPage));
        }

        return $this->responseOk(
            'Billing list fetched successfully',
            BillingResource::collection($billings->get())
        );
    }
}
