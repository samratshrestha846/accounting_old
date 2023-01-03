<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    protected $fillable = ['client_id','godown_id', 'stock_out_date', 'user_id', 'status'];

    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function godown(){
        return $this->belongsTo(Godown::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function stockoutproducts(){
        return $this->hasMany(StockOutProduct::class);
    }
}
