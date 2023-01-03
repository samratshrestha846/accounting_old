<?php
namespace App\Actions;

use App\Models\HotelDeliveryPartner;
use Illuminate\Http\UploadedFile;

class UploadDeliveryPartnerAction {
    public function execute(HotelDeliveryPartner $hotelDeliveryPartner,UploadedFile $image): bool
    {
        // $image = $request->file('brand_logo');
        $imagename = $image->store('food_image', 'uploads');

        if($hotelDeliveryPartner->logo){
            unlink("uploads/".$hotelDeliveryPartner->logo);
        }

        return $hotelDeliveryPartner->update([
            'logo' => $imagename
        ]);
    }
}
