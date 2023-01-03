<?php

namespace App\Http\Resources\Godown;

use App\Http\Resources\Common\ImageResource;
use App\Http\Resources\Product\ProductBrandResource;
use App\Http\Resources\Product\ProductCategoryResource;
use App\Http\Resources\Product\ProductSeriesResource;
use App\Http\Resources\Product\ProductVendorResource;
use App\Traits\NestedRelationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'product_name' => $this->product_name,
            'product_code' => $this->product_code,
            'size' => $this->size,
            'color' => $this->color,
            'product_price' => $this->product_price,
            'description' => $this->description,
            'primary_number' => $this->primary_number,
            'secondary_number' => $this->secondary_number,
            'secondary_unit_selling_price' => $this->secondary_unit_selling_price,
            'category_id' => $this->category_id,
            'primary_unit' => $this->whenLoadedNestedRelation('primaryUnit') ? $this->primaryUnit->short_form : null,
            'secondary_unit' => $this->whenLoadedNestedRelation('secondaryUnit') ? $this->secondaryUnit->short_form : null,
            'category' => ProductCategoryResource::make($this->whenLoadedNestedRelation('category')),
            'brand' => ProductBrandResource::make($this->whenLoadedNestedRelation('brand')),
            'series' => ProductSeriesResource::make($this->whenLoadedNestedRelation('series')),
            'vendor' => ProductVendorResource::make($this->whenLoadedNestedRelation('vendor')),
            'images' => ImageResource::collection($this->whenLoadedNestedRelation('product_images')),
            'serial_numbers' => !$this->whenLoadedNestedRelation('godownproduct') instanceof \Illuminate\Http\Resources\MissingValue ? (count($this->godownproduct) ? $this->godownproduct[0]['serialnumbers'] : []) : [],
        ];
    }
}
