<?php

namespace App\Actions;

use App\Helpers\BillingNumberGenerator;
use App\Helpers\NepaliDate;
use App\Models\Billing;
use App\Models\BillingExtra;
use App\Models\FiscalYear;
use App\Models\HotelFood;
use App\Models\HotelOrder;
use App\Models\HotelSaleReport;
use App\Models\PaymentInfo;
use App\Models\Paymentmode;
use App\Models\TaxInfo;
use App\Models\User;
use Illuminate\Support\Arr;

use function app\NepaliCalender\datenep;

class PosOrderInvoicePayment
{

    public function execute(User $user, HotelOrder $hotelOrder, Paymentmode $paymentmode, float $payment_amount, ?string $remarks = null)
    {
        $billing = $hotelOrder->billing;

        if ($billing)
            throw new \Exception("You cannot create billing of order item since it has already billing");

        //create a new billing for hotel_order
        $billing = $this->createBilling($user, $hotelOrder, $paymentmode, $payment_amount, $remarks);

        //update the billing_id of hotel_order
        $hotelOrder->saveBilling($billing);

        //marked the order item status as served to customer
        $hotelOrder->markedStatusAsServed();

        return $billing;
    }

    public function createBilling(User $user, HotelOrder $hotelOrder, Paymentmode $paymentmode, float $payment_amount, ?string $remarks): Billing
    {
        $current_fiscal_year = FiscalYear::where('fiscal_year', $this->getCurrentYear())->first();

        //nepali date
        $engtoday = date('Y-m-d');
        $nepalidate = datenep($engtoday);
        $nepmonth = (new NepaliDate($engtoday))->getMonthName();

        //billing number
        $billingNumberGenerator = (new BillingNumberGenerator(8));
        $transaction_no = $billingNumberGenerator->getTransactionNumber();
        $reference_no = $billingNumberGenerator->getRefereneceNumber();

        $approval_by = $user->id;
        $is_realtime = 1;
        $user_id = $user->id;

        $billing = $hotelOrder->billing;

        $billing = Billing::create([
            'billing_type_id' => 8,
            'client_id' => $hotelOrder->customer_id,
            'transaction_no' => $transaction_no,
            'reference_no' => $reference_no,
            'ledger_no' => 'HOTEL_POS',
            'file_no' => 'HOTEL_POS',
            'remarks' => $remarks,
            'eng_date' => $engtoday,
            'nep_date' => $nepalidate,
            'payment_mode' => $paymentmode->id,
            // 'godown'=>$request['godown'],
            'entry_by' => $user_id,
            'status' => 1,
            'fiscal_year_id' => $current_fiscal_year->id,
            'alltaxtype' => $hotelOrder->tax_type,
            'alltax' => $hotelOrder->tax_rate_id,
            'taxamount' => $hotelOrder->total_tax,
            'alldiscounttype' => $hotelOrder->discount_type,
            'discountamount' => $hotelOrder->discount_value ?? 0,
            'alldtamt' => $hotelOrder->total_discount,
            'service_charge_type' => $hotelOrder->service_charge_type,
            'service_charge' => $hotelOrder->service_charge,
            'servicechargeamount' => $hotelOrder->total_service_charge,
            'is_cabin' => true,
            'cabinchargeamount' => $hotelOrder->cabin_charge,
            'subtotal' => $hotelOrder->sub_total,
            'shipping' => 0.00,
            'grandtotal' => $hotelOrder->total_cost,
            'is_cancelled' => 0,
            'approved_by' => $approval_by,
            'vat_refundable' => 0.00,
            'sync_ird' => 1,
            'is_realtime' => $is_realtime,
            'is_pos_data' => 0,
        ]);
        //Tax Info
        $taxcount = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->count();
        $unpaidtaxes = TaxInfo::where('is_paid', 0)->get();
        $unpaids = [];
        foreach ($unpaidtaxes as $unpaidtax) {
            array_push($unpaids, $unpaidtax->total_tax);
        }
        $duetax = array_sum($unpaids);
        if ($taxcount < 1) {
            $salestax = TaxInfo::create([
                'fiscal_year' => $current_fiscal_year->fiscal_year,
                'nep_month' => $nepmonth,
                'sales_tax' => $hotelOrder->total_tax,
                'total_tax' => $hotelOrder->total_tax,
                'is_paid' => 0,
                'due' => $duetax,
            ]);
            $salestax->save();
        } else {
            $salestax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
            $sales_tax = $salestax->sales_tax + (float)$hotelOrder->total_tax;
            $total_tax = $salestax->total_tax + (float)$hotelOrder->total_tax;
            $salestax->update([
                'sales_tax' => $sales_tax,
                'total_tax' => $total_tax,
            ]);
            $salestax->save();
        }
        // Payment Info
        (new CreateNewPaymentInfo)->execute($billing, $payment_amount, $payment_amount, $user);

        // Billing Extra
        $billingextras = [];
        $salesrecord = [];
        $particulars = [];
        $quantity = [];
        $HotelSalesReport = [];
        foreach ($hotelOrder->order_items as $content) {

            $billingextras[] = [
                'billing_id' => $billing['id'],
                'particulars' => $content->food_id,
                'particular_type' => HotelFood::class,
                'quantity' => $content->quantity,
                'purchase_type' => $content->purchase_type,
                'purchase_unit' => $content->purchase_unit,
                'rate' => $content->unit_price,
                'unit' => null,
                'discountamt' => $content->discount_value,
                'discounttype' => $content->discount_type,
                'dtamt' => $content->total_discount / $content->quantity,
                'taxamt' => $content->total_tax / $content->quantity,
                'tax' => $content->tax_value,
                'itemtax' => $content->total_tax,
                'taxtype' => $content->tax_type,
                'total' => $content->total_cost,
            ];
            $salesrecord[] = [
                'billing_id' => $billing['id'],
                'product_id' => Arr::get($content, 'product_id'),
                'stock_sold' => Arr::get($content, 'quantity'),
                'date_sold' => $engtoday
            ];
            array_push($particulars, Arr::get($content, 'product_id'));
            array_push($quantity, Arr::get($content, 'quantity'));

            $HotelSalesReport[] = [
                'billing_id' => $billing['id'],
                'food_id' => $content->food_id,
                'created_by' => $user_id,
                'date' => today(),
            ];
        }
        $count = count($billingextras);

        // dd('rame');
        BillingExtra::insert($billingextras);
        // SalesRecord::insert($salesrecord);

        HotelSaleReport::insert($HotelSalesReport);

        return $billing;
    }

    public function getCurrentYear()
    {
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        return $exploded_date[0] . '/' . ($exploded_date[0] + 1);
    }
}
