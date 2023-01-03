<?php
namespace App\Factories;

use App\FormDatas\HotelDeliveryPartnerFormdata;
use Illuminate\Http\Request;

class HotelDeliveryPartnerFactory {
    public static function make(Request $request): HotelDeliveryPartnerFormdata
    {
        return new HotelDeliveryPartnerFormdata(
            $request->name,
            $request->email,
            $request->address,
            $request->contact_number,
            (int) $request->province_id,
            (int) $request->district_id,
            (bool) $request->is_active,
        );
    }
}
