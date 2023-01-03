<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CBMSResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if(count($this->billprint) > 0){
            $count = count($this->billprint) - 1;
            $print_date =  date('Y-m-d', strtotime($this->billprint[$count]->print_time));
            $printed_by = $this->billprint[$count]->print_by->name;
        }
        else
        {
            $print_date =  "Not Printed";
            $printed_by = "Not Printed";
        }

        $billing_extras = $this->billingextras;
        $taxable_amount = 0;
        foreach($billing_extras as $extra){
            if(!$extra->itemtax == 0 || !$extra->itemtax == null){
                $taxable_amount += $extra->total;
            }
        }


        if ($this->vendor_id == null)
        {
            return [
                // 'id' => $this->id,
                'Fiscal Year' => $this->fiscal_years->fiscal_year,
                'Bill_no' => $this->reference_no,
                'Customer_name' => $this->client->name,
                'Customer_Pan' => $this->client->pan_vat == null ? 'Not Provided' : $this->client->pan_vat,
                'Bill_Date' => $this->nep_date,
                'Amount' => 'Rs. ' . $this->subtotal,
                'Discount' => 'Rs. ' . $this->discountamount,
                'Taxable_Amount' => 'Rs. ' . $taxable_amount,
                'Tax_Amount' => 'Rs. ' . $this->taxamount,
                'shipping_charge' => 'Rs. ' . $this->shipping,
                'Total_Amount' => 'Rs. ' . $this->grandtotal,
                'Sync_with_IRD' => $this->sync_ird == 0 ? 'No' : 'Yes',
                'Is_Bill_Printed' => $this->is_printed == 0 ? 'Not Printed' : 'Printed',
                'Is_Bill_Active' => $this->status == 0 ? 'Inactive' : 'Active',
                'Print_Time' => $print_date,
                'Entered_By' => $this->user_entry->name,
                'Printed_By' => $printed_by,
                'Is_realtime' => $this->is_realtime ? 'Yes' : 'No',
                'Payment_Method' => $this->payment_modes->payment_mode,
                'VAT_Refund_Amount(If any)' => $this->vat_refundable,
                'Transaction_Id(If any)' => $this->transaction_no
            ];
        }
        else
        {
            return [
                // 'id' => $this->id,
                'Fiscal Year' => $this->fiscal_years->fiscal_year,
                'Bill_no' => $this->reference_no,
                'supplier' => $this->suppliers->company_name,
                'supplier_pan_vat' => $this->suppliers->pan_vat == null ? 'Not Provided' : $this->suppliers->pan_vat,
                'Bill_Date' => $this->nep_date,
                'Amount' => 'Rs. ' . $this->subtotal,
                'Discount' => 'Rs. ' . $this->discountamount,
                'Taxable_Amount' => 'Rs. ' . $taxable_amount,
                'Tax_Amount' => 'Rs. ' . $this->taxamount,
                'shipping_charge' => 'Rs. ' . $this->shipping,
                'Total_Amount' => 'Rs. ' . $this->grandtotal,
                'Sync_with_IRD' => $this->sync_ird == 0 ? 'No' : 'Yes',
                'Is_Bill_Printed' => $this->is_printed == 0 ? 'Not Printed' : 'Printed',
                'Is_Bill_Active' => $this->status == 0 ? 'Inactive' : 'Active',
                'Print_Time' => $print_date,
                'Entered_By' => $this->user_entry->name,
                'Printed_By' => $printed_by,
                'Is_realtime' => $this->is_realtime ? 'Yes' : 'No',
                'Payment_Method' => $this->payment_modes->payment_mode,
                'VAT_Refund_Amount(If any)' => $this->vat_refundable,
                'Transaction_Id(If any)' => $this->transaction_no
            ];
        }
    }
}
