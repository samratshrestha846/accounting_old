<?php
namespace App\Actions\Billing;

use App\Actions\Pos\UpdateOutletProductStockAction;
use App\Exceptions\GrossTotalInvalidException;
use App\Helpers\BillingNumberGenerator;
use App\Helpers\NepaliDate;
use App\Http\Requests\SalesPaymentRequest;
use App\Models\Billing;
use App\Models\BillingExtra;
use App\Models\FiscalYear;
use App\Models\Outlet;
use App\Models\OutletProduct;
use App\Models\PaymentInfo;
use App\Models\SalesRecord;
use App\Models\Tax;
use App\Models\TaxInfo;
use App\Models\User;
use App\Services\ProductSaleService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

use function App\NepaliCalender\datenep;

class CreateOutletBillingAction {

    protected bool $syncOfflineData = false;

    public function syncOfflineData(): self
    {
        $this->syncOfflineData = true;
        return $this;
    }

    public function execute(User $user, Outlet $outlet, SalesPaymentRequest $request): Billing
    {
        DB::beginTransaction();
        try {
            $products = $request['products'];
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


            //if client side gross total amount is not nearly equal to server side gross total then
            //throw calculation error
            if((int) $request['gross_total'] !== (int) $productSaleService->getTotalCost())
            {
                throw new GrossTotalInvalidException("Client side gross_total doesnt matched with server side gross_total");
            }

            $subtotal = $productSaleService->getSubTotal();
            $bulkDiscounttype = $request['alldiscounttype'];
            $bulkDiscount = $productSaleService->getBulkDiscount();
            $bulkTaxType = $request['alltaxtype'];
            $allTaxAmt = $productSaleService->getBulkTax();
            $totalGrossTotal = $productSaleService->getTotalCost();

            $current_fiscal_year = FiscalYear::where('fiscal_year', $this->getCurrentYear())->first();

            //nepali date
            $engtoday = date('Y-m-d');
            $nepalidate = datenep($engtoday);
            $nepmonth = (new NepaliDate($engtoday))->getMonthName();

            //billing number
            $billingNumberGenerator = (new BillingNumberGenerator(1));
            $transaction_no = $billingNumberGenerator->getTransactionNumber();
            $reference_no = $billingNumberGenerator->getRefereneceNumber();

            $approval_by = $user->id;
            $is_realtime = 1;
            $user_id = $user->id;

            $billing = Billing::create([
                'billing_type_id'=>1,
                'client_id'=>$request['customer_id'],
                'outlet_id' => $outlet->id,
                'transaction_no'=>$transaction_no,
                'reference_no'=>$reference_no,
                'ledger_no'=>'POS',
                'file_no'=>'POS',
                'remarks'=>$request['remarks'],
                'eng_date'=>$engtoday,
                'nep_date'=>$nepalidate,
                'payment_mode'=>$request['payment_mode'],
                // 'godown'=>$request['godown'],
                'entry_by'=>$user_id,
                'status'=>1,
                'fiscal_year_id'=>$current_fiscal_year->id,
                'alltaxtype'=>$bulkTaxType,
                'alltax'=>$request['alltax'],
                'taxamount'=>$allTaxAmt,
                'alldiscounttype'=>$bulkDiscounttype,
                'discountamount'=>$bulkDiscount,
                'alldtamt'=> $request['alldiscountvalue'],
                'subtotal'=>$subtotal,
                'shipping'=>0.00,
                'grandtotal'=>$totalGrossTotal,
                'approved_by'=>$approval_by,
                'vat_refundable'=>0.00,
                'sync_ird'=>1,
                'is_realtime'=>$is_realtime,
                'is_pos_data'=>1,
            ]);
            //Tax Info
            $taxcount = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->count();
            $unpaidtaxes = TaxInfo::where('is_paid', 0)->get();
            $unpaids = [];
            foreach($unpaidtaxes as $unpaidtax){
                array_push($unpaids, $unpaidtax->total_tax);
            }
            $duetax = array_sum($unpaids);
            if($taxcount < 1){
                $salestax = TaxInfo::create([
                    'fiscal_year'=> $current_fiscal_year->fiscal_year,
                    'nep_month'=> $nepmonth,
                    'sales_tax'=>$allTaxAmt,
                    'total_tax'=>$allTaxAmt,
                    'is_paid'=>0,
                    'due'=>$duetax,
                ]);
                $salestax->save();
            }else{
                $salestax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                $sales_tax = $salestax->sales_tax + (float)$allTaxAmt;
                $total_tax = $salestax->total_tax + (float)$allTaxAmt;
                $salestax->update([
                    'sales_tax'=>$sales_tax,
                    'total_tax'=>$total_tax,
                ]);
                $salestax->save();
            }
            // Payment Info
            $payment_info = PaymentInfo::create([
                'billing_id' => $billing['id'],
                'payment_type' => 'paid',
                'payment_amount' => $totalGrossTotal,
                'payment_date' => $engtoday,
                'total_paid_amount' => $request['payment_amount'],
                'paid_to' => $user->id,
            ]);
            $payment_info->save();
            // Payment Info Ends

            // Billing Extra
            $billingextras = [];
            $salesrecord = [];
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
                $salesrecord[] =[
                    'billing_id'=>$billing['id'],
                    'product_id'=>Arr::get($content, 'product_id'),
                    'outlet_id'=> $outlet->id,
                    'stock_sold'=>Arr::get($content, 'quantity'),
                    'date_sold'=>$engtoday
                ];
                array_push($particulars, Arr::get($content, 'product_id'));
                array_push($quantity, Arr::get($content, 'quantity'));
            }
            $count = count($billingextras);

            // dd('rame');
            BillingExtra::insert($billingextras);
            SalesRecord::insert($salesrecord);

            foreach($productSaleService->getContents() as $content){

                $quantity = $content['quantity'] ?? 0;
                $secondaryNumber = $content['secondary_number'] ?? 0;
                $purchaseType = Arr::get($content,'purchase_type');

                $outletProduct = OutletProduct::where(['outlet_id' => $outlet->id, 'product_id' => $content['product_id' ?? null]])->firstOrFail();

                (new UpdateOutletProductStockAction())->execute($outletProduct, $purchaseType,(float) $quantity, (float) $secondaryNumber);
            }

            DB::commit();
        } catch(\Exception $e){
            DB::rollback();
            throw new \Exception($e->getMessage());
        }

        return $billing;
    }

    public function getCurrentYear()
    {
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        return $exploded_date[0].'/'.($exploded_date[0] + 1);
    }
}
