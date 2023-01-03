<?php

namespace App\Imports;

use App\Models\Billing;
use App\Models\BillingExtra;
use App\Models\Vendor;
use App\Models\FiscalYear;
use App\Models\PaymentInfo;
use App\Models\Product;
use App\Models\Tax;
use App\Models\Unit;
use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use function App\NepaliCalender\datenep;
use function App\NepaliCalender\dateeng;

class PurchaseImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $row)
    {
          // as.k
        //forupdatedate


        // foreach($row as $rw){

        //     $d = strtotime($rw['date']);
        //     if($d < 0){
        //         continue;
        //     }
        //     $date = date('Y-m-d',$d);
        //     $year = date('Y',$d);
        //     if($year < 2030){//english date

        //         $nepalidate = datenep($date);

        //         $engdate = $date;

        //         Billing::where('reference_no',$rw['ref_no'])->update(['eng_date'=>$engdate,'nep_date'=>(string)$nepalidate]);
        //     }else{//nepali date
        //         $engdate = dateeng($date);
        //         $nepalidate = $date;
        //         Billing::where('reference_no',$rw['ref_no'])->update(['eng_date'=>$engdate,'nep_date'=>(string)$nepalidate]);
        //     }

        // }
        // return false;//endforupdate date


        $time = strtotime("-1 year", time());
        $date = date("Y-m-d", $time);
        $nepalidate = datenep($date);
        foreach($row as $rw){

            if(isset($rw['product_code'])){

                $billingid = Billing::where('reference_no',$rw['reference_no'])->first();

                if(!$billingid){
                    continue;
                }
                $product_id = Product::where('product_code',$rw['product_code'])->first()->id ?? '' ;

                if(empty($product_id)){
                    continue;
                 }
                BillingExtra::create([
                    'billing_id' => $billingid->id,
                    'particulars' => $product_id,
                    'quantity' => $rw['quantity'],
                    'rate' => $rw['unit_price'],
                    'unit' => "",
                    'discountamt' => 0,
                    'discounttype' => '',
                    'dtamt' => '0',
                    'taxamt' => $rw['tax'],

                    'total' => $rw['gross_total'],
                ]);
            }else{
                Billing::create([
                    'vendor_id'=>Vendor::where('company_name',$rw['supplier_name'])->first()->id ?? null,
                    'billing_type_id' => 2,
                    'reference_no' => $rw['reference_no'],
                    'eng_date' => $date,
                    'nep_date' => $nepalidate,
                    'payment_method'  =>  1,
                    'godown' => 1,
                    'entry_by' => Auth::user()->id,
                    'status' => 1,
                    'fiscal_year_id' =>1,

                    'taxamount' => $rw['total_tax'],
                    'subtotal'=>$rw['inv_total'],
                    'discountamount'=>0,
                    'grandtotal' => $rw['total_tax']+$rw['inv_total'],
                    'approved_by' => Auth::user()->id,
                    'vat_refundable' => 0,
                    'sync_ird' => 1,
                    'is_realtime' => 1,
                ]);
            }

        }



        return false;
        //endas.k


        $supplier = $this->getSupplier($row['supplier_code']);
        $unit = $this->getUnit($row['unit']);
        $unitId = $unit->id;
        $productPrice = $row['price'];
        $product = $this->getProduct($row['product'], $unitId, $productPrice);
        $taxPercent = $row['tax_percent'];
        $tax = $this->getTax($taxPercent);
        $taxAmount = ($tax->percent / 100) * (float)$row['total'];
        $dateToCompare = date('Y-m-d', strtotime($row['date']));
        $nepali_date = datenep($dateToCompare);

        $today = date("Y-m-d");
        $nepalitoday = datenep($today);
        $explode = explode('-', $nepalitoday);
        $year = $explode[0];
        $month = $explode[1];

        $fiscalyear = ($month < 4) ? ($year - 1).'/'.$year : $year.'/'.($year + 1);

        $fiscal_year = FiscalYear::where('fiscal_year', $fiscalyear)->first();

        $billing = Billing::where('ledger_no', $row['party_bill_no'])
                                ->where('file_no', $row['file_nooptional'])
                                ->where('vendor_id', $supplier->id)
                                ->where('billing_type_id', 2)->first();

        if($billing)
        {
            $payment_info = PaymentInfo::where('billing_id', $billing->id)->first();
            $new_total = $billing->grandtotal + $row['total'];
            $new_taxamount = $billing->tax_amount + $taxAmount;
            $new_discountamount = $billing->discount_amount + $row['discount'];

            $billing->update([
                'discountamount' => $new_discountamount,
                'alldtamt' => $new_discountamount,
                'tax_amount' => $new_taxamount,
                'subtotal' => $new_total,
                'grandtotal' => $new_total,
            ]);

            $payment_info->update([
                'payment_amount' => $new_total,
                'total_paid_amount' => $new_total
            ]);
        }
        else
        {
            $billscount = Billing::where('billing_type_id', 2)->count();

            if($billscount == 0)
            {
                $transaction_no = str_pad(1, 8, "0", STR_PAD_LEFT);
                $reference_no = str_pad(1, 8, "0", STR_PAD_LEFT);
                $reference_no = 'PB-'.$reference_no;
            }
            else
            {
                $lastbill = Billing::where('billing_type_id', 2)->latest()->first();
                $newtransaction_no = $lastbill->transaction_no+1;
                $newreference_no = $lastbill->reference_no;
                $expref = explode('-', $newreference_no);
                $ref = $expref[1]+1;

                $transaction_no = str_pad($newtransaction_no, 8, "0", STR_PAD_LEFT);
                $reference_no = str_pad($ref, 8, "0", STR_PAD_LEFT);
                $reference_no = 'PB-'.$reference_no;
            }

            $billing = Billing::create([
                'billing_type_id' => 2,
                'vendor_id' => $supplier->id,
                'transaction_no' => $transaction_no,
                'reference_no' => $reference_no,
                'ledger_no' => $row['party_bill_no'],
                'file_no' => $row['file_nooptional'],
                'eng_date' => $row['date'],
                'nep_date' => $nepali_date,
                'payment_method'  =>  1,
                'godown' => 1,
                'entry_by' => Auth::user()->id,
                'status' => 1,
                'fiscal_year_id' => $fiscal_year->id,
                // 'alltaxtype' => 'exclusive',
                // 'alltax' => $tax->percent,
                'taxamount' => 0,
                'alldiscounttype' => 'fixed',
                'discountamount' => $row['discount'],
                // 'alldtamt' => $row['discount'],
                'subtotal' => $row['total'],
                'shipping' => 0,
                'grandtotal' => $row['total'],
                'approved_by' => Auth::user()->id,
                'vat_refundable' => 0,
                'sync_ird' => 1,
                'is_realtime' => 1,
            ]);

            PaymentInfo::create([
                'billing_id' => $billing->id,
                'payment_type' => 'paid',
                'payment_amount' => $row['total'],
                'payment_date' => $row['date'],
                'total_paid_amount' => $row['total'],
                'paid_to' => Auth::user()->id
            ]);
        }

        BillingExtra::create([
            'billing_id' => $billing->id,
            'particulars' => $product->id,
            'quantity' => $row['quantity'],
            'rate' => $row['price'],
            'unit' => $row['unit'],
            'discountamt' => $row['discount'],
            'discounttype' => 'fixed',
            'dtamt' => $row['discount'],
            'taxamt' => $taxAmount,
            'tax' => $tax->percent,
            'itemtax' => $taxAmount * $row['quantity'],
            'taxtype' => 'exclusive',
            'total' => $row['total'],
        ]);
    }

    private function getSupplier($code)
    {
        return Vendor::select('id')->where('supplier_code', $code)->first() ?? new Vendor();
    }

    private function getUnit($name)
    {
        return Unit::select('id')->where('unit', $name)->first() ?? new Unit();
    }

    private function getTax($taxPercent)
    {
        return Tax::select('percent')->where('percent', $taxPercent)->first() ?? new Unit();
    }

    private function getProduct($name, $unitId, $productPrice)
    {
        return Product::select('id')->where('product_name', $name)->where('primary_unit_id', $unitId)->where('product_price', $productPrice)->first() ?? new Product();
    }
}
