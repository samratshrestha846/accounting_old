<?php
namespace App\Actions\DailyExpenses;

use App\Http\Requests\StoreDailyExpensesRequest;
use App\Models\DailyExpenses as ModelsDailyExpenses;
use App\Services\UploadService;

class CreateDailyExpensesAction {

    protected UploadService $uploadService;

    public function __construct()
    {
        $this->uploadService = new UploadService();
    }

    public function execute(StoreDailyExpensesRequest $request): ModelsDailyExpenses
    {

        $bill_image_path =  null;

        if($request->bill_image){
            $bill_image_path = $this->uploadService->setUploadDirectory(ModelsDailyExpenses::UPLOAD_DIRECTORY)->handleUploadFile($request['bill_image']);
        }

        return ModelsDailyExpenses::create([
            'vendor_id' => $request['vendor_id'],
            'date' => $request['purchase_date'],
            'bill_number' => $request['bill_number'],
            'bill_amount' => $request['bill_amount'],
            'paid_amount' => $request['paid_amount'],
            'purpose' => $request['purpose'],
            'bill_image' => $bill_image_path,
            'purpose' => $request['purpose'],
        ]);
    }
}
