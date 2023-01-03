<?php
namespace App\Actions;

use App\Models\HotelOrder;

class RestoredCancledHotelOrderItem {

    public function execute(HotelOrder $hotelOrder): bool
    {

        if(!($hotelOrder->isCancled() || $hotelOrder->isSuspended()))
            throw new \Exception("You cannot restored this order item since it is not cancled or suspended.");

        return $hotelOrder->restoredOrder();
    }
}
