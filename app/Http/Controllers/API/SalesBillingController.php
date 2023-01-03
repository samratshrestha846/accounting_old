<?php

namespace App\Http\Controllers\API;

use App\Actions\Billing\CreateSalesBillingAction;
use App\Enums\DiscountType;
use App\Enums\TaxType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalesBillingRequest;
use App\Http\Resources\SalesBillingResource;
use App\Models\Billing;
use App\Models\Godown;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class SalesBillingController extends Controller
{
    public function index(Request $request)
    {
        $this->can('manage-sales');

        $perPage = $request['per_page'];

        $billings = Billing::query()->with('fiscal_year','client','godown')->whereSaleBilling();

        if($perPage) {
            $billings = $billings->paginate($perPage);
            return SalesBillingResource::collection($billings);
        }

        return $this->responseOk(
            'Sales Billing fetched successfully',
            SalesBillingResource::collection($billings->get())
        );
    }

    public function store(Request $request, Godown $godown)
    {
        $this->can('manage-sales');

        $data = $request->all();
        // $products = $request['products'];

        // if(!is_array($products)) {
        //     $data['products'] = json_decode($products);
        // }

        $validator = Validator::make($data, (new StoreSalesBillingRequest())->rules($godown->id));

        if($validator->fails()) {
            return response()->json([
                'error' => true,
                'status' => 422,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();

        $request = (new StoreSalesBillingRequest());
        $request->replace($data);

        $billing = (new CreateSalesBillingAction())->execute($user, $godown, $request);

        return $this->responseOk(
            'Sales billing created successfully',
            $billing,
            201
        );
    }

    function isJson($string) {

        if(is_array($string))
            return false;

        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
