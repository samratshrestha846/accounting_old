<?php

namespace App\Http\Resources;

use App\Models\PaymentInfo;
use App\Traits\NestedRelationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BillingResource extends JsonResource
{
    use NestedRelationResource;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $billingType = "";

        switch($this->billing_type_id) {
            case 1:
                $billingType = "sales";
                break;
            case 2:
                $billingType = "purchase";
                break;
            default:
                $billingType = "none";
        }

        if($this->billing_type_id == 1 && $this->outlet_id) {
            $billingType = "pos";
        }

        return [
            'id' => $this->id,
            'fiscal_year_id' => $this->fiscal_year_id,
            'customer_id' => $this->client_id,
            'vendor_id' => $this->vendor_id,
            'godown_id' => $this->godown,
            'outlet_id' => $this->outlet_id,
            'type' => $billingType,
            'transaction_no' => $this->transaction_no,
            'reference_no' => $this->reference_no,
            'ledger_no' => $this->ledger_no,
            'file_no' => $this->file_no,
            'entry_date_in_ad' => $this->eng_date,
            'entry_date_in_bs' => $this->nep_date,
            'payment_mode' => $this->payment_mode,
            'subtotal' => (string) $this->subtotal,
            'alltaxtype' => (string) $this->alltaxtype,
            'taxpercent' => $this->taxpercent,
            'alltax' => (int) $this->alltax,
            'alldtamt' => $this->alldtamt,
            'alldiscounttype' => (string) $this->alldiscounttype,
            'discountpercent' => $this->discountpercent,
            'taxamount' => $this->taxamount,
            'alldiscountvalue' => (string) $this->discountamount,
            'shipping_amount' => (double) $this->shipping,
            'gross_total' => (string) $this->grandtotal,
            'reference_invoice_no' => $this->reference_invoice_no,
            'sync_ird' => $this->sync_ird,
            'is_printed' => $this->is_printed,
            'is_realtime' => $this->is_realtime,
            'printcount' => $this->printcount,
            'downloadcount' => $this->downloadcount,
            'vat_refundable_amount' => (string) $this->vat_refundable,
            'remarks' => $this->remarks,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'fiscal_year' => FiscalYearResource::make($this->whenLoaded('fiscal_year')),
            'vendor' => VendorResource::make($this->whenLoaded('suppliers')),
            'customer' => CustomerResource::make($this->whenLoaded('client')),
            'products' => !$this->whenLoadedNestedRelation('billingextras') instanceof \Illuminate\Http\Resources\MissingValue ? json_encode(BillingExtraResource::collection($this->whenLoaded('billingextras'))) : json_encode([]),
            'payment_infos' => PaymentInfoResource::collection($this->whenLoaded('payment_infos')),
        ];
    }
}
