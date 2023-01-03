<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'uuid',
        'name',
        'serial_number',
        'ip_address',
        'area',
        'last_activity',
        'status'
    ];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class,'device_id');
    }

    public function hasDepartments(): bool
    {
        return $this->departments()->exists();
    }
}
