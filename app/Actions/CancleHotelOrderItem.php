<?php
namespace App\Actions;

use App\Enums\OrderItemStatus;
use App\Models\HotelOrder;
use App\Models\User;

class CancleHotelOrderItem {

    public function execute(User $cancledBy, HotelOrder $hotelOrder, string $reason, ?string $description): bool
    {
        if($hotelOrder->billing)
            throw new \Exception("You cannot cancled this order item since customer has already paid the bill.");

        return $hotelOrder->update([
            'status' => OrderItemStatus::CANCLED,
            'reason' => $reason,
            'description' => $description,
            'cancled_by' => $cancledBy->id,
        ]);
    }
}
