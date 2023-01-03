<?php

namespace App\Http\Controllers\API\SyncOfflineData;

use App\Actions\Billing\CreateSalesBillingAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalesBillingRequest;
use App\Models\Godown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesBillingController extends Controller
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
                $godown = Godown::findOrFail($data['godown_id'] ?? null);
                $request = new StoreSalesBillingRequest();
                $request->replace($data);
                $pruchaseBillings[] = (new CreateSalesBillingAction())->syncOfflineData()->execute(auth()->user(), $godown, $request);
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
            'Sales billing stored successfully',
            $pruchaseBillings
        );
    }

    public function validateData()
    {
        $rules = [
            'data' => ['required','array'],
            'data.*.godown_id'=>[
                'required',
                'exists:godowns,id',
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

        foreach((new StoreSalesBillingRequest())->rules() as $key => $rule) {

            if($key == "products.*.product_id"){
                $rule = [
                    'required',
                    'exists:products,id',
                ];
            }
            $newRules['data.*.'.$key] = $rule;
        }

        return array_merge_recursive(
            $rules,
            $newRules
        );
    }
}
