<?php

namespace App\Models\Pivots;

use App\Models\GodownSerialNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GodownProductSerialNumber extends Pivot
{
    use HasFactory;

    public function serial_numbers()
    {
        return $this->hasMany(GodownSerialNumber::class,'godown_product_id');
    }
}
