<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DailyExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $billImageUrl = null;

        if($this->bill_image) {
            $billImageUrl = url('/').'/'.$this->bill_image;
        }
        return [
            'id' => $this->id,
            'vendor_id' => (int) $this->vendor_id,
            'purchase_date' => $this->date,
            'bill_image' => $billImageUrl,
            'bill_number' => number_format($this->bill_number,2),
            'bill_amount' => number_format($this->bill_amount, 2),
            'paid_amount' => number_format($this->paid_amount, 2),
            'purpose' => $this->purpose,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
