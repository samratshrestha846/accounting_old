<?php
namespace App\Actions;

use App\FormDatas\HotelDeliveryPartnerFormdata;
use App\Models\HotelDeliveryPartner;

class CreateNewDeliveryPartnerAction {

    public function execute(HotelDeliveryPartnerFormdata $data): HotelDeliveryPartner
    {
        return HotelDeliveryPartner::create([
            'name' => $data->name,
            'email' => $data->email,
            'address' => $data->address,
            'contact_number' => $data->contact_number,
            'province_id' => $data->province_id,
            'district_id' => $data->district_id,
            'is_active' => $data->is_active,
        ]);
    }
}
