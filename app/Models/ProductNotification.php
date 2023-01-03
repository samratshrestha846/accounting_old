<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductNotification extends Model
 {
    use HasFactory, Multicompany;

    protected $fillable = ['company_id', 'branch_id', 'product_id', 'godown_id', 'noti_type', 'status', 'read_at', 'read_by'];

    public function product() {
        return $this->belongsTo( Product::class, 'product_id' );
    }

    public function godown() {
        return $this->belongsTo( Godown::class, 'godown_id' );
    }
}
