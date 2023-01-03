<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletProduct extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'outlet_id',
        'product_id',
        'primary_stock',
        'primary_opening_stock',
        'primary_closing_stock',
        'secondary_stock',
        'secondary_opening_stock',
        'secondary_closing_stock',
        'primary_stock_alert',
        'secondary_stock_alert'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function user_outlet()
    {
        return $this->belongsTo(OutletBiller::class,'outlet_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class,'outlet_id');
    }

    public function scopeFilters(Builder $query, array $filters): Builder
    {
        return $query->whereHas('product', function($q) use($filters) {
            $q->filters($filters);
        });
    }

    public function scopeUserOulet(Builder $query, Outlet $outlet)
    {
        return $query->where('outlet_id', $outlet->id);
    }

    public function scopeHasOutletNotOfStock(Builder $query): Builder
    {
        return $query->where('secondary_stock', '>', 0);
    }

    public function scopeActiveProduct(Builder $query): Builder
    {
        return $query->whereHas('product', function($q){
            return $q->where('status', 1);
        });
    }
}
