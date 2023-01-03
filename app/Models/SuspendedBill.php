<?php

namespace App\Models;

use App\Filters\SuspendedBill\SuspendedBillFilters;
use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuspendedBill extends Model
{
    use HasFactory, Multicompany;

    protected $guarded = [];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Client::class,'customer_id','id');
    }

    public function suspendedUser(): BelongsTo
    {
        return $this->belongsTo(User::class,'suspended_by','id');
    }

    public function suspendedItems(): HasMany
    {
        return $this->hasMany(SuspendedItem::class,'suspended_id','id');
    }

    public function scopeFilters(Builder $query, array $filters): Builder
    {
        return (new SuspendedBillFilters($filters))->filter($query);
    }

    public function scopeUserData(Builder $query)
    {
        $user = auth()->user();

        return $query->when($user, function($q) use($user){
            $q->when(!$user->can('manage-suspended-bill'), function($q1) use($user){
                $q1->where('suspended_by', $user->id);
            });
        });
    }

    public function cancleIt(): bool
    {
        return $this->update([
            'is_canceled' => true,
        ]);
    }
}
