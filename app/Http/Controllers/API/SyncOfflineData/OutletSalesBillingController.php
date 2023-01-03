<?php

namespace App\Http\Controllers\API\SyncOfflineData;

use App\Actions\Billing\CreateOutletBillingAction;
use App\Exceptions\GrossTotalInvalidException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SalesPaymentRequest;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OutletSalesBillingController extends Controller
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

        $outletBillings = [];

        DB::beginTransaction();
        try {
            foreach($request->data as $data) {
                $outlet = Outlet::findOrFail($data['outlet_id'] ?? null);
                $request = new SalesPaymentRequest();
                $request->replace($data);
                $outletBillings[] = (new CreateOutletBillingAction())->syncOfflineData()->execute(auth()->user(), $outlet, $request);
            }
            DB::commit();
        } catch(GrossTotalInvalidException $e) {
            return response()->json([
                'error' => true,
                'status' => 412,
                'message' => $e->getMessage(),
            ], 412);
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
            $outletBillings
        );
    }

    public function validateData(): array
    {
        $rules = [
            'data' => ['required','array'],
            'data.*.outlet_id'=>[
                'required',
                'exists:outlets,id',
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

        foreach((new SalesPaymentRequest())->rules() as $key => $rule) {
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
