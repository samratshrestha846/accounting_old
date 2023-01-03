<?php

namespace App\Models;

use App\Filters\Product\ProductFilters;
use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'product_name',
        'product_code',
        'category_id',
        'size',
        'weight',
        'lot_no',
        'color',
        'opening_stock',
        'total_stock',
        'original_vendor_price',
        'charging_rate',
        'final_vendor_price',
        'carrying_cost',
        'transportation_cost',
        'miscellaneous_percent',
        'other_cost',
        'cost_of_product',
        'custom_duty',
        'after_custom',
        'tax',
        'total_cost',
        'margin_type',
        'margin_value',
        'profit_margin',
        'product_price',
        'description',
        'status',
        'primary_number',
        'primary_unit',
        'primary_unit_code',
        'secondary_number',
        'secondary_unit',
        'secondary_unit_code',
        'supplier_id',
        'brand_id',
        'series_id',
        'refundable',
        'secondary_unit_selling_price',
        'primary_unit_id',
        'secondary_unit_id',
        'secondary_code',
        'has_serial_number',
        'warranty_months',
        'selected_filter_option',
        'declaration_form_no',
        'child_account_id',
        'expiry_date',
        'manufacturing_date',
    ];

    public const UPLOAD_DIRECTORY = "images/products";

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id','id');
    }
    public function series()
    {
        return $this->belongsTo(Series::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'supplier_id', 'id');
    }

    public function godownproduct()
    {
        return $this->hasMany(GodownProduct::class, 'product_id', 'id');
    }

    public function godowns(): BelongsToMany
    {
        return $this->belongsToMany(Godown::class, 'godown_products');
    }

    public function godown()
    {
        return $this->godownproduct()->godown();
    }

    // public function serialNumbers()
    // {
    //     return $this->godownproduct()->allserialnumbersforedit();
    // }

    public function product_images()
    {
        return $this->hasMany(ProductImages::class, 'product_id', 'id');
    }

    public function primaryUnit()
    {
        return $this->belongsTo(Unit::class, 'primary_unit_id');
    }

    public function secondaryUnit()
    {
        return $this->belongsTo(Unit::class, 'secondary_unit_id');
    }

    public function outletProduct()
    {
        return $this->hasOne(OutletProduct::class, 'product_id');
    }

    public function scopeFilters(Builder $query, array $filters): Builder
    {
        return (new ProductFilters($filters))->filter($query);
    }

    public function scopeHasOutletsNotOfStock(Builder $query)
    {
        return $query->whereHas('outletProducts', function ($q) {
            return $q->where('secondary_stock', '>', 0);
        });
    }

    public function scopeNotOutOfStock(Builder $query)
    {
        return $query->where('total_stock', '>', 0);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    public function opening_balance(){
        return $this->hasOne(OpeningBalance::class,'child_account_id','child_account_id');
    }
}
