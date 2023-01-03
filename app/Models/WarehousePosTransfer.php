<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehousePosTransfer extends Model
{
    use HasFactory;

    protected $fillable = ['godown_id', 'outlet_id', 'transfer_eng_date', 'transfer_nep_date', 'transfered_by', 'remarks'];

    public function transfer_user(){
        return $this->belongsTo(User::class, 'id');
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function outlet(){
        return $this->belongsTo(Outlet::class);
    }

    public function godown(){
        return $this->belongsTo(Godown::class);
    }
    public function user(){
        return $this->belongsTo(User::class, 'transfered_by');
    }
    public function warehousetransferproduct(){
        return $this->hasMany(WarehouseTransferProduct::class);
    }
}
