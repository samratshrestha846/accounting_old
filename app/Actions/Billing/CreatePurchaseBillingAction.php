<?php
namespace App\Actions\Billing;

use App\Helpers\BillingNumberGenerator;
use App\Helpers\NepaliDate;
use App\Http\Requests\StorePurchaseBillingRequest;
use App\Jobs\ProductNotificationAlert;
use App\Models\Billing;
use App\Models\BillingExtra;
use App\Models\FiscalYear;
use App\Models\Godown;
use App\Models\GodownProduct;
use App\Models\PaymentInfo;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\SalesRecord;
use App\Models\Tax;
use App\Models\TaxInfo;
use App\Models\User;
use App\Models\Vendor;
use App\Services\ProductSaleService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CreatePurchaseBillingAction {

    protected bool $syncOfflineData = false;

    public function syncOfflineData(): self
    {
        $this->syncOfflineData = true;
        return $this;
    }

    public function execute(User $user, Vendor $vendor, StorePurchaseBillingRequest $request): Billing
    {
        DB::beginTransaction();
        try {

            $products = collect($request['products'])->toArray();
            $tax = Tax::find($request['alltax']);

            $productSaleService = (new ProductSaleService($products, $this->syncOfflineData))
                ->when(
                    $request['alltaxtype'] && $tax,
                    function($callback) use($request, $tax) {
                        return $callback->setTaxRate($request['alltaxtype'], $tax->percent);
                    }
                )
                ->when(
                    $request['alldiscounttype'] && $request['alldiscountvalue'],
                    function($callback) use($request) {
                        return $callback->setDiscountRate($request['alldiscounttype'], $request['alldiscountvalue']);
                    }
                )
                ->calculate();

            $subtotal = $productSaleService->getSubTotal();
            $bulkDiscounttype = $request['alldiscounttype'];
            $bulkDiscount = $productSaleService->getBulkDiscount();
            $bulkTaxType = $request['alltaxtype'];
            $allTaxAmt = $productSaleService->getBulkTax();
            $totalGrossTotal = $productSaleService->getTotalCost() + (float) $request['shipping_amount'] ?? 0;


            $current_fiscal_year = FiscalYear::where('id', $request['fiscal_year_id'])->first();

            //nepali date
            $engtoday = $request['entry_date_in_ad'];
            $nepmonth = (new NepaliDate($engtoday))->getMonthName();

            //billing number
            $billingNumberGenerator = (new BillingNumberGenerator(2));
            $transaction_no = $billingNumberGenerator->getTransactionNumber();
            $reference_no = $billingNumberGenerator->getRefereneceNumber();

            $billing = Billing::create([
                'vendor_id'=> $vendor->id,
                'billing_type_id'=> 2,
                'transaction_no'=>$transaction_no,
                'reference_no'=>$reference_no,
                'ledger_no'=>$request['ledger_no'],
                'file_no'=>$request['file_no'],
                'remarks'=>$request['remarks'],
                'eng_date'=> $engtoday,
                'nep_date'=>$request['entry_date_in_bs'],
                'payment_mode'=>$request['payment_mode'],
                'godown'=> null,
                'entry_by'=>$user->id,
                'status'=>$request['status'],
                'fiscal_year_id'=> $current_fiscal_year->id,
                'alltaxtype'=>$request['alltaxtype'],
                'alltax'=>$request['alltax'],
                'taxamount'=> $allTaxAmt,
                'alldiscounttype'=> $bulkDiscounttype,
                'discountamount'=> $bulkDiscount,
                'alldtamt'=> $request['alldiscountvalue'],
                'subtotal'=> $subtotal,
                'shipping'=>$request['shipping_amount'],
                'grandtotal'=> $totalGrossTotal,
                'approved_by'=> $request['status'] == 1 ? $user->id : null,
                'vat_refundable'=>$request['vat_refundable_amount'],
                'sync_ird'=>$request['sync_ird'],
                'is_realtime'=> $engtoday == date('Y-m-d') ? 1 : 0,
            ]);

            if($request->has('convertToBill') && $request->has('purchaseOrder'))
            {
                $purchaseOrder = PurchaseOrder::where('id', $request['purchaseOrder'])->first();

                $purchaseOrder->update(['converted' => 1]);
            }

            if($request['sync_ird'] == 1 && $request['status'] == 1)
            {
                $taxcount = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->count();
                $unpaidtaxes = TaxInfo::where('is_paid', 0)->get();
                $unpaids = [];
                foreach($unpaidtaxes as $unpaidtax){
                    array_push($unpaids, $unpaidtax->total_tax);
                }
                $duetax = array_sum($unpaids);
                if($taxcount < 1)
                {
                    $purchasetax = TaxInfo::create([
                        'fiscal_year'=> $current_fiscal_year->fiscal_year,
                        'nep_month'=> $nepmonth,
                        'purchase_tax'=> $allTaxAmt,
                        'total_tax'=> $allTaxAmt,
                        'is_paid'=>0,
                        'due'=>$duetax,
                    ]);
                    $purchasetax->save();
                }
                else
                {
                    $purchasetax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $purchase_tax = $purchasetax->purchase_tax + (float)$allTaxAmt;
                    $total_tax = $purchasetax->total_tax - (float)$allTaxAmt;
                    $purchasetax->update([
                        'purchase_tax'=>$purchase_tax,
                        'total_tax'=>$total_tax,
                    ]);
                }
            }


            // Payment Info
            PaymentInfo::create([
                'billing_id' => $billing['id'],
                'payment_type' => 'paid',
                'payment_amount' => $totalGrossTotal,
                'payment_date' => $engtoday,
                'total_paid_amount' => $request['payment_amount'],
                'paid_to' => $user->id,
            ]);
            // Payment Info Ends

            // Billing Extra
            $billingextras = [];
            $particulars = [];
            $quantity =[];
            foreach($productSaleService->getContents() as $content){
                $billingextras[] = [
                    'billing_id'=>$billing['id'],
                    'particulars'=>Arr::get($content, 'product_id'),
                    'quantity'=>Arr::get($content, 'quantity'),
                    'purchase_type' => Arr::get($content,'purchase_type'),
                    'purchase_unit' => Arr::get($content,'purchase_unit'),
                    'rate' => Arr::get($content, 'unit_price'),
                    'unit'=>null,
                    'discountamt'=>Arr::get($content, 'discount_value'),
                    'discounttype'=>Arr::get($content, 'discount_type'),
                    'dtamt'=>Arr::get($content, 'total_discount')/Arr::get($content, 'quantity'),
                    'taxamt'=>Arr::get($content, 'total_tax')/Arr::get($content, 'quantity'),
                    'tax'=>Arr::get($content, 'tax_value'),
                    'itemtax'=>Arr::get($content, 'total_tax'),
                    'taxtype'=>Arr::get($content, 'tax_type'),
                    'total'=>Arr::get($content, 'total_cost'),
                ];
                array_push($particulars, Arr::get($content, 'product_id'));
                array_push($quantity, Arr::get($content, 'quantity'));
            }
            $count = count($billingextras);

            BillingExtra::insert($billingextras);
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return $billing;
    }
}
