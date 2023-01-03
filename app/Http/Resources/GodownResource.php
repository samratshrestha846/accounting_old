<?php

namespace App\Http\Resources;

use App\Traits\NestedRelationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GodownResource extends JsonResource
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
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'branch_id' => $this->branch_id,
            'godown_name' => $this->godown_name,
            'province_id' => $this->province_id,
            'district_id' => $this->district_id,
            'local_address' => $this->local_address,
            'godown_code' => $this->godown_code,
            'products' => !$this->whenLoadedNestedRelation('godownproduct') instanceof \Illuminate\Http\Resources\MissingValue ?  json_encode(GodownProductResource::collection($this->whenLoaded('godownproduct'))) : "",
            'created_at' => $this->created_at,
        ];
    }
}
