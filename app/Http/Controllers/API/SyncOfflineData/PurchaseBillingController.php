<?php

namespace App\Http\Controllers\API\SyncOfflineData;

use App\Actions\Billing\CreatePurchaseBillingAction;
use App\Enums\DiscountType;
use App\Enums\TaxType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseBillingRequest;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseBillingController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validateData());

        if($validator->fails()) {
            return response()->json([
                'error' => true,
                'status' => 422,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

        $pruchaseBillings = [];

        DB::beginTransaction();
        try {
            foreach($request->data as $data) {
                $vendor = Vendor::findOrFail($data['vendor_id'] ?? null);
                $request = new StorePurchaseBillingRequest();
                $request->replace($data);
                $pruchaseBillings[] = (new CreatePurchaseBillingAction())->syncOfflineData()->execute(auth()->user(), $vendor, $request);
            }
            DB::commit();
        } catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'error' => true,
                'status' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }

        return $this->responseOk(
            'Purchase billing stored successfully',
            $pruchaseBillings
        );

    }

    public function validateData()
    {
        $rules = [
            'data' => ['required','array'],
            'data.*.vendor_id'=>[
                'required',
                'exists:vendors,id',
            ],
            'data.*.alltaxvalue'=>[
                'nullable',
                'numeric',
            ],
            'data.*.products.*.product_price' => [
                'required',
                'numeric'
            ],
            'data.*.products.*.tax_value' => [
                'nullable',
                'numeric'
            ],
        ];

        $newRules = [];

        foreach((new StorePurchaseBillingRequest())->rules() as $key => $rule) {
            $newRules['data.*.'.$key] = $rule;
        }

        return array_merge_recursive(
            $rules,
            $newRules
        );
    }
}
