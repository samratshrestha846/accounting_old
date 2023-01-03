<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'device_id',
        'name',
        'code',
        'location',
        'parent_dept'
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class,'device_id');
    }

    public function staffs(): HasMany
    {
        return $this->hasMany(Staff::class, 'department_id');
    }

    public function hasStaffs(): bool
    {
        return $this->staffs()->exists();
    }
}
