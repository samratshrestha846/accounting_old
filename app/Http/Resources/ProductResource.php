<?php

namespace App\Http\Resources;

use App\Http\Resources\Common\ImageResource;
use App\Http\Resources\Product\ProductBrandResource;
use App\Http\Resources\Product\ProductCategoryResource;
use App\Http\Resources\Product\ProductSeriesResource;
use App\Http\Resources\Product\ProductVendorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'product_name' => $this->product_name,
            'product_code' => $this->product_code,
            'size' => $this->size,
            'color' => $this->color,
            'opening_stock' => $this->opening_stock,
            'total_stock' => $this->total_stock,
            'original_vendor_price' => $this->original_vendor_price,
            'charging_rate' => $this->charging_rate,
            'final_vendor_price' => $this->final_vendor_price,
            'carrying_cost' => $this->carrying_cost,
            'transportation_cost' => $this->transportation_cost,
            'miscellaneous_percent' => $this->miscellaneous_percent,
            'other_cost' => $this->other_cost,
            'cost_of_product' => $this->cost_of_product,
            'custom_duty' => $this->custom_duty,
            'after_custom' => $this->after_custom,
            'tax' => $this->tax,
            'total_cost' => $this->total_cost,
            'profit_margin' => $this->profit_margin,
            'product_price' => $this->product_price,
            'description' => $this->description,
            'primary_number' => $this->primary_number,
            'primary_unit' => $this->primary_unit,
            'primary_unit_code' => $this->primary_unit_code,
            'secondary_number' => $this->secondary_number,
            'secondary_unit' => $this->secondary_unit,
            'secondary_unit_code' => $this->secondary_unit_code,
            'secondary_unit_selling_price' => $this->secondary_unit_selling_price,
            'refundable' => $this->refundable,
            'expiry_date' => $this->expiry_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'category' => ProductCategoryResource::make($this->whenLoaded('category')),
            'brand' => ProductBrandResource::make($this->whenLoaded('brand')),
            'series' => ProductSeriesResource::make($this->whenLoaded('series')),
            'vendor' => ProductVendorResource::make($this->whenLoaded('vendor')),
            'images' => ImageResource::collection($this->whenLoaded('product_images')),
        ];
    }
}
