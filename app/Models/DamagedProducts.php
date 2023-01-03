<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DamagedProducts extends Model
{
    use HasFactory, SoftDeletes, Multicompany;

    protected $fillable = [
        'company_id', 'branch_id', 'product_id', 'godown_id', 'stock', 'document', 'reason'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function godown()
    {
        return $this->belongsTo(Godown::class);
    }
}
