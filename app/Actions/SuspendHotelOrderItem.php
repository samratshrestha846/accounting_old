<?php
namespace App\Actions;

use App\Enums\OrderItemStatus;
use App\Models\HotelOrder;
use App\Models\User;

class SuspendHotelOrderItem {

    public function execute(User $suspendedBy, HotelOrder $hotelOrder, string $reason, ?string $description): bool
    {
        if($hotelOrder->billing)
            throw new \Exception("You cannot suspend this order item since customer has already paid the bill.");

        return $hotelOrder->update([
            'status' => OrderItemStatus::SUSPENDED,
            'reason' => $reason,
            'description' => $description,
            'suspended_by' => $suspendedBy->id,
        ]);
    }
}
