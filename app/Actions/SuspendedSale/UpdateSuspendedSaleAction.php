<?php
namespace App\Actions\SuspendedSale;

use App\Http\Requests\UpdateSuspendSaleRequest;
use App\Models\SuspendedBill;
use App\Models\Tax;
use App\Models\User;
use App\Services\ProductSaleService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UpdateSuspendedSaleAction
{
    protected bool $canBulkTax = true;
    protected bool $canIndividualTax = true;
    protected bool $canBulkDiscount = true;
    protected bool $canIndividualDiscount = true;
    protected bool $canBothDiscount = true;

    public function execute(User $suspendedUser, SuspendedBill $suspendedBill, UpdateSuspendSaleRequest $request): SuspendedBill
    {
        DB::beginTransaction();
        try {
            $products = $request['products'];
            $tax = Tax::find($request['alltax']);

            $productSaleService = (new ProductSaleService($products))
                ->when(
                    Arr::get($request,'alltaxtype') && Arr::get($request, 'alltax'),
                    function($callback) use($request, $tax) {
                        return $callback->setTaxRate($request['alltaxtype'], $tax->percent);
                    }
                )
                ->when(
                    Arr::get($request,'alldiscounttype') && Arr::get($request,'alldiscount'),
                    function($callback) use($request) {
                        return $callback->setDiscountRate($request['alldiscounttype'], $request['alldiscount']);
                    }
                )
                ->calculate();

            $suspendedBill->update([
                'customer_id' => $request['customer_id'],
                'tax_type' => $request['alltaxtype'],
                'tax_rate_id' => $tax->id ?? null,
                'tax_value' => $tax->percent ?? null,
                'discount_type' => $request['alldiscounttype'],
                'discount_value' => $request['alldiscount'],
                'total_tax' => $productSaleService->getTotalTax(),
                'total_discount' => $productSaleService->getTotalDiscount(),
                'sub_total' => $productSaleService->getSubTotal(),
                'total_cost' => $productSaleService->getTotalCost(),
                'is_canceled' => false,
                'suspended_by' => auth()->user()->id,
            ]);

            $saleItems = [];

            $suspendedBill->suspendedItems()->delete();

            $currentcomp = $suspendedUser->currentCompany;

            foreach($productSaleService->getContents() as $content){
                $saleItems[] = [
                    'company_id' => $currentcomp->company_id,
                    'branch_id' => $currentcomp->branch_id,
                    'suspended_id' => $suspendedBill->id,
                    'product_id' => Arr::get($content, 'product_id'),
                    'product_name' => Arr::get($content, 'product_name'),
                    'product_code' => Arr::get($content, 'product_code'),
                    'quantity' => Arr::get($content, 'quantity'),
                    'unit_price' => Arr::get($content, 'unit_price'),
                    'purchase_type' => Arr::get($content, 'purchase_type'),
                    'purchase_unit' => Arr::get($content, 'purchase_unit'),
                    'tax_type' => Arr::get($content, 'tax_type'),
                    'tax_rate_id' => Arr::get($content, 'tax_rate_id'),
                    'tax_value' => Arr::get($content, 'tax_value'),
                    'discount_type' => Arr::get($content, 'discount_type'),
                    'discount_value' => Arr::get($content, 'discount_value'),
                    'total_tax' => Arr::get($content, 'total_tax'),
                    'total_discount' => Arr::get($content, 'total_discount'),
                    'sub_total' => Arr::get($content, 'sub_total'),
                    'total_cost' => Arr::get($content, 'total_cost')
                ];
            }

            $suspendedBill->suspendedItems()->insert($saleItems);

            // dd($items);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }

        return $suspendedBill;
    }
}
