<?php
namespace App\Actions;

use App\Models\HotelFood;
use Illuminate\Http\UploadedFile;

class UploadHotelFoodImage {
    public function execute(HotelFood $hotelFood,UploadedFile $image): bool
    {
        // $image = $request->file('brand_logo');
        $imagename = $image->store('food_image', 'uploads');

        if($hotelFood->food_image){
            unlink("uploads/".$hotelFood->food_image);
        }

        return $hotelFood->update([
            'food_image' => $imagename
        ]);
    }
}
