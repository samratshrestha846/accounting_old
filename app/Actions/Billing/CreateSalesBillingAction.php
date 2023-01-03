<?php
namespace App\Actions\Billing;

use App\Actions\CreateCustomerCreditAction;
use App\Helpers\BillingNumberGenerator;
use App\Helpers\NepaliDate;
use App\Http\Requests\StoreSalesBillingRequest;
use App\Jobs\ProductNotificationAlert;
use App\Models\Billing;
use App\Models\BillingExtra;
use App\Models\Client;
use App\Models\Credit;
use App\Models\FiscalYear;
use App\Models\Godown;
use App\Models\GodownProduct;
use App\Models\PaymentInfo;
use App\Models\Product;
use App\Models\ProductNotification;
use App\Models\SalesRecord;
use App\Models\Tax;
use App\Models\TaxInfo;
use App\Models\User;
use App\Services\ProductSaleService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use function App\NepaliCalender\dateeng;
use function App\NepaliCalender\datenep;

class  CreateSalesBillingAction {

    protected bool $syncOfflineData = false;

    public function syncOfflineData(): self
    {
        $this->syncOfflineData = true;
        return $this;
    }

    public function execute(User $user, Godown $godown, StoreSalesBillingRequest $request): Billing
    {
        DB::beginTransaction();
        try {
            $data = $request->all();

            $products = collect((array) Arr::get($data, 'products'))->toArray();

            $tax = Tax::find($data['alltax'] ?? null);
            $customer = Client::findOrFail(Arr::get($data,'customer_id'));


            $productSaleService = (new ProductSaleService($products, $this->syncOfflineData))
                ->when(
                    Arr::get($data,'alltaxtype') && Arr::get($data, 'alltax'),
                    function($callback) use($data, $tax) {
                        return $callback->setTaxRate($data['alltaxtype'], $tax->percent);
                    }
                )
                ->when(
                    Arr::get($data,'alldiscounttype') && Arr::get($data,'alldiscountvalue'),
                    function($callback) use($data) {
                        return $callback->setDiscountRate($data['alldiscounttype'], $data['alldiscountvalue']);
                    }
                )
                ->calculate();

            //if client side gross total amount is not nearly equal to server side gross total then
            //throw calculation error
            if((int) $data['gross_total'] !== (int) $productSaleService->getTotalCost())
            {
                // throw new \Exception("Client side gross_total doesnt matched with server side gross_total");
            }

            $subtotal = $productSaleService->getSubTotal();
            $bulkDiscounttype = Arr::get($data, 'alldiscounttype');
            $bulkDiscount = $productSaleService->getBulkDiscount();
            $bulkTaxType = Arr::get($data, 'alltaxtype');
            $allTaxAmt = $productSaleService->getBulkTax();
            $totalGrossTotal = $productSaleService->getTotalCost() + (float) Arr::get($data,'shipping_amount');

            //fiscal year
            $current_fiscal_year = FiscalYear::where('id', Arr::get($data,'fiscal_year_id'))->firstOrFail();

            //nepali date
            $engtoday = Arr::get($data, 'entry_date_in_ad');
            $nepmonth = (new NepaliDate($engtoday))->getMonthName();

            //billing number
            $billingNumberGenerator = (new BillingNumberGenerator(1));
            $transaction_no = $billingNumberGenerator->getTransactionNumber();
            $reference_no = $billingNumberGenerator->getRefereneceNumber();


            $user_id = $user->id;

            $billing = Billing::create([
                'billing_type_id'=>1,
                'client_id'=> $customer->id,
                'transaction_no'=>$transaction_no,
                'reference_no'=>$reference_no,
                'ledger_no'=> $data['ledger_no'],
                'file_no'=> $data['file_no'],
                'remarks'=>$data['remarks'],
                'eng_date'=>$engtoday,
                'nep_date'=>$data['entry_date_in_bs'] ?? null,
                'payment_mode'=>$data['payment_mode'],
                'godown'=> $godown->id,
                'entry_by'=>$user_id,
                'status'=>1,
                'fiscal_year_id'=>$current_fiscal_year->id,
                'alltaxtype'=>$bulkTaxType,
                'alltax'=>Arr::get($data, 'alltax'),
                'taxamount'=>$allTaxAmt,
                'alldiscounttype'=>$bulkDiscounttype,
                'discountamount'=>$bulkDiscount,
                'alldtamt'=>Arr::get($data, 'alldiscountvalue'),
                'subtotal'=>$subtotal,
                'shipping'=> Arr::get($data, 'shipping_amount'),
                'grandtotal'=>$totalGrossTotal,
                'approved_by'=> $request['status'] == 1 ? $user->id : null,
                'vat_refundable'=> $data['vat_refundable_amount'] ?? 0,
                'sync_ird'=> $data['sync_ird'] ?? 0,
                'is_realtime'=> $engtoday == date('Y-m-d') ? 1 : 0,
                'is_pos_data'=>0,
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
                    'godown_id'=>$godown->id,
                    'stock_sold'=>Arr::get($content, 'quantity'),
                    'date_sold'=>$data['entry_date_in_ad'] ?? $engtoday
                ];
                array_push($particulars, Arr::get($content, 'product_id'));
                array_push($quantity, Arr::get($content, 'quantity'));


                Product::where('id', Arr::get($content, 'product_id'))->decrement('total_stock', Arr::get($content, 'quantity'));
                GodownProduct::where('product_id', Arr::get($content, 'product_id'))->where('godown_id', $godown->id)->decrement('stock', Arr::get($content, 'quantity'));
            }
            $count = count($billingextras);

            // dd('rame');
            BillingExtra::insert($billingextras);
            SalesRecord::insert($salesrecord);


            if(Arr::get($data, 'payment_type') == "partially_paid" || Arr::get($data, 'payment_type') == "unpaid")
            {


                $credit = Credit::where('customer_id', $customer->id)->where('converted', 0)->first();

                if(!$credit) {
                   $credit = (new CreateCustomerCreditAction())->execute($customer);
                }

                $credit_days_in_seconds = $credit->allocated_days * 86400;

                $date_in_string = strtotime($request['entry_date_in_ad']);
                $bill_date_in_nepali = datenep($request['entry_date_in_ad']);

                $due_date_in_string = $date_in_string + $credit_days_in_seconds;
                $due_date_in_eng = date("Y-m-d", $due_date_in_string);
                $due_date = datenep($due_date_in_eng);

                $payment_info = PaymentInfo::create([
                    'billing_id' => $billing['id'],
                    'payment_type' => Arr::get($data, 'payment_type'),
                    'payment_amount' => $totalGrossTotal,
                    'payment_date' => $engtoday,
                    'due_date' => $due_date,
                    'total_paid_amount' => Arr::get($data,'payment_amount'),
                    'paid_to' => $user->id,
                    'is_sales_invoice' => 1
                ]);
                $payment_info->save();

                $credit_bills_count = Credit::where('customer_id', $data['customer_id'])->where('converted', 0)->get()->count();

                $new_credited_bills = $credit->credited_bills + 1;
                $new_credited_amount = $totalGrossTotal - $data['payment_amount'];

                if($credit_bills_count == 1 && $credit->credited_bills == null)
                {
                    $credit->update([
                        'invoice_id' => $billing['id'],
                        'bill_eng_date' => $engtoday,
                        'bill_nep_date' => $bill_date_in_nepali,
                        'bill_expire_eng_date' => $due_date_in_eng,
                        'bill_expire_nep_date' => $due_date,
                        'credited_bills' => $new_credited_bills,
                        'credited_amount' => $new_credited_amount,
                    ]);
                }
                else
                {
                    $new_credit = Credit::create([
                        'customer_id' => $credit->customer_id,
                        'allocated_days' => $credit->allocated_days,
                        'allocated_bills' => $credit->allocated_bills,
                        'allocated_amount' => $credit->allocated_amount,
                        'invoice_id' => $billing['id'],
                        'bill_eng_date' => $engtoday,
                        'bill_nep_date' => $bill_date_in_nepali,
                        'bill_expire_eng_date' => $due_date_in_eng,
                        'bill_expire_nep_date' => $due_date,
                        'credited_bills' => $new_credited_bills,
                        'credited_amount' => $new_credited_amount,
                    ]);

                    $new_credit->save();
                }
            }
            else if (Arr::get($data, 'payment_type') == "paid")
            {
                $payment_info = PaymentInfo::create([
                    'billing_id' => $billing['id'],
                    'payment_type' => Arr::get($data, 'payment_type'),
                    'payment_amount' => $totalGrossTotal,
                    'payment_date' => $engtoday,
                    'total_paid_amount' => Arr::get($data,'payment_amount'),
                    'paid_to' => $user->id,
                ]);
                $payment_info->save();
            }

            dispatch(new ProductNotificationAlert(collect($productSaleService)->pluck('product_id')->toArray(), [$godown->id]));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }

        return $billing->load('billingextras');
    }
}
