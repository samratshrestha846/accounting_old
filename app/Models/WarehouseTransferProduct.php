<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseTransferProduct extends Model
{
    use HasFactory;
    protected $fillable = ['warehouse_pos_transfer_id', 'product_id', 'stock'];

    public function outlet(){
        return $this->belongsTo(Outlet::class);
    }
    public function user(){
        return $this->belongsTo(User::class, 'transfered_by');
    }
    public function godown(){
        return $this->belongsTo(Godown::class);
    }
}
