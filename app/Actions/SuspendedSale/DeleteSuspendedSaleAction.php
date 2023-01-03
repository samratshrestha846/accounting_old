<?php
namespace App\Actions\SuspendedSale;

use App\Models\SuspendedBill;
use Illuminate\Support\Facades\DB;

class DeleteSuspendedSaleAction {

    public function __construct()
    {

    }

    public function execute(SuspendedBill $suspendedBill): SuspendedBill
    {
        DB::beginTransaction();
        try {
            $suspendedBill->suspendedItems()->delete();
            $suspendedBill->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }

        return $suspendedBill;
    }
}
