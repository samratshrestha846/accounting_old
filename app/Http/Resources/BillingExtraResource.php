<?php

namespace App\Http\Resources;

use App\Traits\NestedRelationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BillingExtraResource extends JsonResource
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
        $product = !$this->whenLoadedNestedRelation('product') instanceof \Illuminate\Http\Resources\MissingValue ? $this->product : null;
        $primaryUnit = !$this->whenLoadedNestedRelation('product.primaryUnit') instanceof \Illuminate\Http\Resources\MissingValue ? ($this->product ? $this->product->primaryUnit->short_form : null) : null;
        return [
            "id" => $this->id,
            "product_id" => $this->particulars,
            'product_name' => $product['product_name'] ?? null,
            'product_code' => $product['product_code'] ?? null,
            'primary_unit' => $primaryUnit,
            'product_price' => $product['product_price'] ?? null,
            'selected_tax_type' => $this->taxtype,
            'selected_discount_type' => $this->discounttype,
            'discount_data' => (double) $this->discountamt,
            'added_tax' => (double) $this->taxamt,
            'added_discount' => (double) $this->dtamt,
            "narration" => $this->narration,
            "cheque_no" => $this->cheque_no,
            "quantity" => $this->quantity,
            "tax_type" => $this->taxtype,
            "tax_rate_id" => $this->tax,
            "discount_type" => $this->discounttype,
            "value_discount" => $this->discountamt,
            "purchase_type" => $this->purhcase_type,
            "purchase_unit" => $this->pruchase_unit,
            "rate" => $this->rate,
            "discountamt" => $this->discountamt,
            "discounttype" => $this->discounttype,
            "dtamt" => $this->dtamt,
            "taxamt" => $this->taxamt,
            "itemtax" => $this->itemtax,
            "taxtype" => $this->taxtype,
            "tax" => $this->tax,
            "unit" => $this->unit,
            "total_price" => $this->total,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
