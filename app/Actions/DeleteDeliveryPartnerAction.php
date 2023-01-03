<?php
namespace App\Actions;

use App\Models\HotelDeliveryPartner;

class DeleteDeliveryPartnerAction {
    public function execute(HotelDeliveryPartner $deliveryPartner): bool
    {
        if($deliveryPartner->logo){
            unlink("uploads/".$deliveryPartner->logo);
        }

        return $deliveryPartner->delete();
    }
}
