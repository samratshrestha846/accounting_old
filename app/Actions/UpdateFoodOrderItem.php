<?php
namespace App\Actions;

use App\Enums\OrderItemStatus;
use App\FormDatas\HotelOrderFormdata;
use App\Models\HotelOrder;
use App\Models\HotelOrderType;
use App\Models\Tax;
use App\Models\User;
use App\Services\FoodItemSaleService;

class UpdateFoodOrderItem {

    protected CreateBulkFoodOrderItem $createBulkFoodOrderItem;

    public function __construct(CreateBulkFoodOrderItem $createBulkFoodOrderItem)
    {
        $this->createBulkFoodOrderItem = $createBulkFoodOrderItem;
    }

    public function execute(User $updatedBy, HotelOrder $hotelOrder, HotelOrderFormdata $data): bool
    {
        if($hotelOrder->isServed()) {
            if(!($data->status == OrderItemStatus::SERVED)) {
                throw new \Exception("You cannot update the status of order item to cancled nor pending nor suspended");
            }
        }

        $tax = Tax::find($data->tax_rate_id);

        $fooditemSaleService = (new FoodItemSaleService($data->order_items))
            ->when(
                $data->service_charge_type && $data->service_charge,
                function($callback) use($data, $tax) {
                    return $callback->setServiceCharge($data->service_charge_type, $data->service_charge);
                }
            )
            ->when(
                $data->tax_type && $data->tax_rate_id,
                function($callback) use($data, $tax) {
                    return $callback->setTaxRate($data->tax_type, $tax->percent);
                }
            )
            ->when(
                $data->discount_type && $data->discount_value,
                function($callback) use($data) {
                    return $callback->setDiscountRate($data->discount_type, $data->discount_value);
                }
            )
            ->calculate();

        $hotelOrder->update([
            'order_type_id' => $data->order_type_id,
            'customer_id' => $data->customer_id,
            'delivery_partner_id' => $data->order_type_id == HotelOrderType::ONLINE_DELIVERY ? $data->delivery_partner_id : null,
            'tax_type' => $data->tax_type,
            'tax_rate_id' => $tax->id ?? null,
            'tax_value' => $tax->percent ?? null,
            'service_charge_type' => $data->service_charge_type,
            'service_charge' => $data->service_charge,
            'is_cabin' => $data->is_cabin,
            'cabin_charge' => $data->cabin_charge,
            'discount_type' => $data->discount_type,
            'discount_value' => $data->discount_value,
            'total_service_charge' => $fooditemSaleService->getTotalServiceCharge(),
            'total_tax' => $fooditemSaleService->getTotalTax(),
            'total_discount' => $fooditemSaleService->getTotalDiscount(),
            'sub_total' => $fooditemSaleService->getSubTotal(),
            'total_cost' => $fooditemSaleService->getTotalCost(),
            'total_items' => count($data->order_items),
            'status' => $data->status,
        ]);

        $hotelOrder->tables()->sync($data->tableIds->toArray());

        $hotelOrder->order_items()->delete();

        $this->createBulkFoodOrderItem->execute($updatedBy, $hotelOrder, $fooditemSaleService->getContents());

        return true;
    }


}
