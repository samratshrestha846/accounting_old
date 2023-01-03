<?php
namespace App\Actions;

use App\Models\HotelTable;

class DeleteHotelTable {
    public function execute(HotelTable $table): bool
    {
        return $table->delete();
    }
}
