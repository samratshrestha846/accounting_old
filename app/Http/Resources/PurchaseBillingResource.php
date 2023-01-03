<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseBillingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fiscal_year' => FiscalYearResource::make($this->whenLoaded('fiscal_year')),
            'vendor' => VendorResource::make($this->whenLoaded('suppliers')),
            'transaction_no' => $this->transaction_no,
            'reference_no' => $this->reference_no,
            'ledger_no' => $this->ledger_no,
            'file_no' => $this->file_no,
            'eng_date' => $this->eng_date,
            'nep_date' => $this->nep_date,
            'payment_mode' => $this->payment_mode,
            'subtotal' => $this->subtotal,
            'alltaxtype' => $this->alltaxtype,
            'taxpercent' => $this->taxpercent,
            'alltax' => $this->alltax,
            'alldtamt' => $this->alldtamt,
            'alldiscounttype' => $this->alldiscounttype,
            'discountpercent' => $this->discountpercent,
            'taxamount' => $this->taxamount,
            'discountamount' => $this->discountamount,
            'shipping' => $this->shipping,
            'grandtotal' => $this->grandtotal,
            'reference_invoice_no' => $this->reference_invoice_no,
            'sync_ird' => $this->sync_ird,
            'is_printed' => $this->is_printed,
            'is_realtime' => $this->is_realtime,
            'printcount' => $this->printcount,
            'downloadcount' => $this->downloadcount,
            'vat_refundable' => $this->vat_refundable,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
