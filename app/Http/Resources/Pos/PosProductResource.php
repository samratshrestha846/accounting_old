<?php

namespace App\Http\Resources\Pos;

use App\Http\Resources\Common\ImageResource;
use App\Http\Resources\Product\ProductBrandResource;
use App\Http\Resources\Product\ProductCategoryResource;
use App\Http\Resources\Product\ProductSeriesResource;
use App\Http\Resources\Product\ProductVendorResource;
use App\Traits\NestedRelationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PosProductResource extends JsonResource
{

    use NestedRelationResource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {

        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'product_code' => $this->product_code,
            'secondary_code' => $this->secondary_code,
            'size' => $this->size,
            'color' => $this->color,
            'product_price' => (float) $this->product_price,
            'description' => $this->description,
            'primary_number' => (int) $this->primary_number,
            'secondary_number' => (float) $this->secondary_number,
            'secondary_unit_selling_price' => $this->secondary_unit_selling_price,
            'primary_stock' => (int) $this->pivot->primary_stock,
            'secondary_stock' => (float) $this->pivot->secondary_stock,
            'category_id' => $this->category_id,
            'primary_unit' => !$this->whenLoadedNestedRelation('primaryUnit') instanceof \Illuminate\Http\Resources\MissingValue ? $this->primaryUnit->short_form : null,
            'secondary_unit' => !$this->whenLoadedNestedRelation('secondaryUnit') instanceof \Illuminate\Http\Resources\MissingValue ? $this->secondaryUnit->short_form : null,
            'category' => ProductCategoryResource::make($this->whenLoaded('category')),
            'brand' => ProductBrandResource::make($this->whenLoaded('brand')),
            'series' => ProductSeriesResource::make($this->whenLoaded('series')),
            'vendor' => ProductVendorResource::make($this->whenLoaded('vendor')),
            'images' => ImageResource::collection($this->whenLoaded('product_images')),
        ];
    }
}
