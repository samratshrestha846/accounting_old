<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GodownProduct extends Model
{
    use HasFactory, SoftDeletes, Multicompany;

    protected $fillable = [
        'company_id', 'branch_id', 'product_id', 'godown_id', 'opening_stock', 'stock', 'alert_on', 'floor_no', 'rack_no', 'row_no','has_serial_number'
    ];

    public function godown()
    {
        return $this->belongsTo(Godown::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id', 'product_id');
    }

    public function serialforedits(){
        return $this->hasMany(GodownSerialNumber::class, 'godown_product_id')->where('is_pos_product','=', 0);
    }

    public function allserialnumbers()
    {
        return $this->hasMany(GodownSerialNumber::class, 'godown_product_id')->where('is_pos_product','=', 0)->where('is_sold', '=', 0);
    }
    public function allserialnumbersforedit()
    {
        return $this->hasMany(GodownSerialNumber::class, 'godown_product_id')->where('is_pos_product','=', 0);
    }
    public function serialnumbers()
    {
        return $this->hasMany(GodownSerialNumber::class, 'godown_product_id')->where('is_damaged','=', 0)->where('is_pos_product','=', 0)->where('is_sold', '=', 0);
    }

    public function outletserialnumbers()
    {
        return $this->hasMany(GodownSerialNumber::class, 'godown_product_id')->where('is_damaged','=', 0)->where('is_pos_product','=', 1)->where('is_sold', '=', 0);
    }

    public function damagedserialnumbers()
    {
        return $this->hasMany(GodownSerialNumber::class, 'godown_product_id')->where('is_damaged','=', 1)->where('is_pos_product','=', 0)->where('is_sold', '=', 0);
    }

    public function expiryproducts()
    {
        return $this->hasMany(Product::class, 'id', 'product_id')->where('expiry_date', '!=', null);
    }

    public function productstock(){
        return $this->hasMany(ProductStock::class,'godown_id','godown_id');
    }
}
