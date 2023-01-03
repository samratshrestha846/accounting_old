<?php
namespace App\Actions;

use App\FormDatas\ServiceSaleBillFormData;
use App\Models\SalesBills;
use App\Models\ServiceSalesExtra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SalesBillsController;

class UpdateServiceSaleBillAction {

    public function execute(SalesBills $serviceSale, ServiceSaleBillFormData $serviceSaleBillFormData): bool
    {
        DB::beginTransaction();
        try {
            $serviceSale->update([
                'client_id' => $serviceSaleBillFormData->client_id,
                // 'transaction_no' => $serviceSaleBillFormData->getTransactionNo(),
                // 'reference_no' => $serviceSaleBillFormData->getRegistrationNo(),
                'ledger_no' => $serviceSaleBillFormData->ledger_no,
                'file_no' => $serviceSaleBillFormData->file_no,
                'remarks' => $serviceSaleBillFormData->remarks,
                'eng_date' => $serviceSaleBillFormData->eng_date,
                'nep_date' => $serviceSaleBillFormData->nep_date,
                'payment_method' => $serviceSaleBillFormData->payment_method,
                'bank_id' => $serviceSaleBillFormData->getPaymentBankId(),
                'online_portal_id' => $serviceSaleBillFormData->getOnlinePortalId(),
                'cheque_no' => $serviceSaleBillFormData->getChequeNo(),
                'customer_portal_id' => $serviceSaleBillFormData->getCustomerPortalId(),
                'entry_by' => Auth::user()->id,
                'status' => $serviceSaleBillFormData->status,
                'approved_by' => $serviceSaleBillFormData->getApprovedBy(),
                'fiscal_year_id' => $serviceSaleBillFormData->fiscal_year_id,
                'subtotal' => $serviceSaleBillFormData->subtotal,
                'alltaxtype' => $serviceSaleBillFormData->alltaxtype,
                'taxpercent' => $serviceSaleBillFormData->alltax,
                'alltax' => $serviceSaleBillFormData->alltax,
                'alldtamt' => $serviceSaleBillFormData->alldtamt,
                'alldiscounttype' => $serviceSaleBillFormData->alldiscounttype,
                'discountpercent' => $serviceSaleBillFormData->getDiscountPercent(),
                'taxamount' => $serviceSaleBillFormData->taxamount,
                'discountamount' => $serviceSaleBillFormData->getDiscountAmount(),
                'shipping' => $serviceSaleBillFormData->shipping,
                'grandtotal' => $serviceSaleBillFormData->grandtotal,
                'payment_type' => $serviceSaleBillFormData->payment_type,
                'payment_amount' => $serviceSaleBillFormData->payment_amount,
                'sync_ird' => $serviceSaleBillFormData->getSyncIrdInfo(),
                'is_realtime' => $serviceSaleBillFormData->getRealTimeInfo(),
                'vat_refundable' => $serviceSaleBillFormData->vat_refundable,
                'vendor_id' => $serviceSaleBillFormData->vendor_id,
                'billing_type_id' => $serviceSaleBillFormData->billing_type_id,
                'reference_invoice_no' => $serviceSaleBillFormData->reference_invoice_no,
                'service_charge' => $serviceSaleBillFormData->service_charge,
                'servicediscounttype' => $serviceSaleBillFormData->servicediscounttype,
                'servicediscountamount' => $serviceSaleBillFormData->servicediscountamount,
            ]);

            $particulars = $serviceSaleBillFormData->particulars;
            $quantity = $serviceSaleBillFormData->quantity;
            $rate = $serviceSaleBillFormData->rate;
            $unit = $serviceSaleBillFormData->unit;
            $discountamt = $serviceSaleBillFormData->discountamt;
            $discounttype = $serviceSaleBillFormData->discounttype;
            $dtamt = $serviceSaleBillFormData->dtamt;
            $taxamt = $serviceSaleBillFormData->taxamt;
            $itemtax = $serviceSaleBillFormData->itemtax;
            $taxtype = $serviceSaleBillFormData->taxtype;
            $tax = $serviceSaleBillFormData->tax;
            $total = $serviceSaleBillFormData->total;
            $count = count($particulars);

            for($x = 0; $x < $count; $x++)
            {
                ServiceSalesExtra::create([
                    'sales_bills_id' => $serviceSale->id,
                    'particulars' => $particulars[$x],
                    'quantity' => $quantity[$x],
                    'unit'=>$unit[$x],
                    'rate' => $rate[$x],
                    'discountamt' => $discountamt[$x],
                    'discounttype' => $discounttype[$x],
                    'dtamt' => $dtamt[$x],
                    'taxamt' => $taxamt[$x],
                    'itemtax' => $itemtax[$x],
                    'taxtype' => $taxtype[$x],
                    'tax' => $tax[$x],
                    'total' => $total[$x],
                ]);
            }

             //forbillingcredit
             $salebill = new SalesBillsController;
             $serviceSale->due_date_nep = $serviceSaleBillFormData->due_date_nep;
             $serviceSale->due_date_eng = $serviceSaleBillFormData->due_date_eng;
             $onupdate = true;
             $salebill->billingCredit($serviceSale,$onupdate);

            DB::commit();
        } catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return true;
    }
}
