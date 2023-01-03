<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'branch_id',
        'client_id',
        'fiscal_year_id',
        'eng_date',
        'nep_date',
        'order_no',
        'quantity',
        'remarks',
        'is_notified',
        'is_read'
    ];

    public function client_order_extras(){
        return $this->hasMany(ClientOrderExtras::class, 'client_order_id');
    }

    public function client(){
        return $this->belongsTo(Client::class, 'client_id');
    }

    // public function clients(){

    // }
}
