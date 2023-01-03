<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
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
            'company_name' => $this->company_name,
            'company_email' => $this->company_email,
            'company_phone' => (string) $this->company_phone,
            'company_address' => $this->company_address,
            'pan_vat' => $this->pan_vat,
            'concerned_name' => $this->concerned_name,
            'concerned_phone' => (string) $this->concerned_phone,
            'concerned_email' => $this->concerned_email,
            'designation' => $this->designation,
            'supplier_code' => $this->supplier_code,
            'created_at' => $this->created_at,
        ];
    }
}
