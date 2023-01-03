<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class SuspendedItemRepository {

    protected array $filters = [];

    public function filters(array $filters): self
    {
        $this->filters = $filters;
        return $this;
    }

    public function getListBySuspendedBillIdAndOutletId(int $suspendedBillId,int $outletId)
    {
        return DB::table('suspended_items')
            ->select([
                'suspended_items.id as suspended_item_id',
                'products.id as product_id',
                'products.product_name',
                'products.product_code',
                'products.product_price',
                'products.secondary_unit_selling_price',
                'primary_unit.short_form as primary_unit',
                DB::raw('ifnull(secondary_unit.short_form,null) as secondary_unit'),
                'outlet_products.primary_stock',
                'outlet_products.secondary_stock',
                'suspended_items.quantity',
                'suspended_items.tax_type',
                'suspended_items.tax_rate_id',
                DB::raw('ifnull(taxes.percent,0) as tax_value'),
                'suspended_items.discount_type',
                'suspended_items.discount_value',
                'suspended_items.purchase_type',
                'suspended_items.purchase_unit',
            ])
            ->join('outlet_products','outlet_products.product_id','=','suspended_items.product_id')
            ->join('products','products.id','=','outlet_products.product_id')
            ->join('units as primary_unit','primary_unit.id','=','products.primary_unit_id')
            ->leftJoin('taxes','taxes.id','=','suspended_items.tax_Rate_id')
            ->leftJoin('units as secondary_unit','secondary_unit.id','=','products.secondary_unit_id')
            ->where('suspended_items.suspended_id',$suspendedBillId)
            ->where('outlet_products.outlet_id', $outletId)
            ->get();
    }
}
