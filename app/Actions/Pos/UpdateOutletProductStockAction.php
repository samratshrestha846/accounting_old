<?php
namespace App\Actions\Pos;

use App\Enums\ProductPurchase;
use App\Models\Outlet;
use App\Models\OutletProduct;
use Illuminate\Support\Arr;

class UpdateOutletProductStockAction {

    public function execute(OutletProduct $outletProduct, string $purchase_type, float $quantity, float $secondary_number)
    {

        $totalPrimaryStock = 0;
        $totalSecondaryStock = 0;

        if($purchase_type == ProductPurchase::PRIMARY_UNIT){
            $totalPrimaryStock = $quantity;
            $totalSecondaryStock = $quantity * $secondary_number;
        }

        if($purchase_type == ProductPurchase::SECONDARY_UNIT){
            $totalSecondaryStock = $quantity;
        }

        // $secondary_stock = $outletProduct->secondary_stock - $totalSecondaryStock;
        // $primary_stock = floor ($secondary_stock/$secondary_number);

        $outletProduct->update([
            'primary_stock' => $outletProduct->primary_stock - $quantity,
            'secondary_stock' => 0,
        ]);
    }
}
