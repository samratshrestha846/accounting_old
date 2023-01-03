<?php

namespace App\Http\Controllers\API\SyncOfflineData;

use App\Actions\DailyExpenses\CreateDailyExpensesAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDailyExpensesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DailyExpensesController extends Controller
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

        $dailyExpenses = [];

        foreach($request->data as $data){
            $request = (new StoreDailyExpensesRequest());
            $request->replace($data);
            $dailyExpenses[] = (new CreateDailyExpensesAction())->execute($request);
        }

        return $this->responseOk(
            "Daily expenses sycned successfully",
            $dailyExpenses
        );
    }
    public function validateData()
    {
        return [
            'data' => ['required','array'],
            'data.*.vendor_id' => ['required','exists:vendors,id'],
            'data.*.purchase_date' => ['required','date_format:Y-m-d'],
            'data.*.bill_number' => ['required','string','max:255'],
            'data.*.bill_amount' => ['required','numeric'],
            'data.*.paid_amount' => ['required','numeric'],
            'data.*.purpose' => ['required'],
            'data.*.bill_image' => ['nullable','image']
        ];
    }
}
