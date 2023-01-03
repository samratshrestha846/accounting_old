<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosSettings extends Model
{
    use HasFactory;

    protected $table = 'pos_settings';

    public $timestamps = false;

    protected $fillable = [
        'display_products',
        'default_category',
        'default_customer',
        'default_biller',
        'default_currency',
        'show_tax',
        'show_discount',
        'kot_print_after_placing_order'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Client::class,'default_customer','id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class,'default_category','id');
    }

    public function createOrUpdate(array $data)
    {
        $posSetting = $this->first();

        if($posSetting){
            $posSetting->update($data);
        }else{
            $this->create($data);
        }
    }
}
