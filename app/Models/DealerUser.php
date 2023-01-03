<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Actions\Customer\Customer as Authenticatable;

class DealerUser extends Authenticatable
{
    use HasFactory;

    protected $guard = 'customer';
    protected $fillable = [
        'client_id',
        'name',
        'email',
        'password',
        'pin_number',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function client(){
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function dealer_user_companies(){
        return $this->hasMany(DealerUserCompany::class, 'dealer_user_id');
    }
}
