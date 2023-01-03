<?php

namespace App\Http\Resources;

use App\Http\Resources\Common\ImageResource;
use App\Http\Resources\Product\ProductBrandResource;
use App\Http\Resources\Product\ProductCategoryResource;
use App\Http\Resources\Product\ProductSeriesResource;
use App\Http\Resources\Product\ProductVendorResource;
use App\Traits\NestedRelationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GodownProductResource extends JsonResource
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
        $product = $this->product;

        return [
            'id' => $product->id,
            'product_name' => $product->product_name,
            'product_code' => $product->product_code,
            'size' => $product->size,
            'color' => $product->color,
            'product_price' => $product->product_price,
            'description' => $product->description,
            'primary_number' => $product->primary_number,
            'secondary_number' => $product->secondary_number,
            'secondary_unit_selling_price' => $product->secondary_unit_selling_price,
            'category_id' => $product->category_id,
            'primary_unit' => $this->whenLoadedNestedRelation('product.primaryUnit') ? $this->product->primaryUnit->short_form : null,
            'secondary_unit' => $this->whenLoadedNestedRelation('product.secondaryUnit') ? $this->product->secondaryUnit->short_form : null,
            'category' => ProductCategoryResource::make($this->whenLoadedNestedRelation('product.category')),
            'brand' => ProductBrandResource::make($this->whenLoadedNestedRelation('product.brand')),
            'series' => ProductSeriesResource::make($this->whenLoadedNestedRelation('product.series')),
            'vendor' => ProductVendorResource::make($this->whenLoadedNestedRelation('product.vendor')),
            'images' => ImageResource::collection($this->whenLoadedNestedRelation('product.product_images')),
        ];
    }
}
