<?php

namespace App\Models;

use App\Enums\OrderItemStatus;
use App\Filters\HotelOrder\HotelOrderFilters;
use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HotelOrder extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'order_type_id',
        // 'floor_id',
        // 'room_id',
        // 'table_id',
        'delivery_partner_id',
        'customer_id',
        'order_at',
        'service_charge_type',
        'service_charge',
        'tax_type',
        'tax_rate_id',
        'tax_value',
        'discount_type',
        'discount_value',
        'is_cabin',
        'cabin_charge',
        'total_service_charge',
        'total_tax',
        'total_discount',
        'sub_total',
        'total_cost',
        'status',
        'waiter_id',
        'total_items',
        'reason',
        'description',
        'cancled_by',
        'suspended_by',
        'created_by',
        'billing_id',
    ];

    public function floor(): BelongsTo
    {
        return $this->belongsTo(HotelFloor::class,'floor_id');
    }

    public function order_type(): BelongsTo
    {
        return $this->belongsTo(HotelOrderType::class,'order_type_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(HotelRoom::class,'room_id');
    }

    public function tables(): BelongsToMany
    {
        return $this->belongsToMany(HotelTable::class,'hotel_order_table','order_id','table_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Client::class,'customer_id');
    }

    public function waiter(): BelongsTo
    {
        return $this->belongsTo(User::class,'waiter_id');
    }

    public function delivery_partner(): BelongsTo
    {
        return $this->belongsTo(HotelDeliveryPartner::class,'delivery_partner_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function order_items(): HasMany
    {
        return $this->hasMany(HotelOrderItem::class,'order_id');
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class,'tax_rate_id');
    }

    public function billing(): BelongsTo
    {
        return $this->belongsTo(Billing::class,'billing_id');
    }

    public function scopeFilters(Builder $query, array $filters): Builder
    {
        return (new HotelOrderFilters($filters))->filter($query);
    }

    public function scopeOngoingOrder(Builder $query)
    {
        return $query->where('status', OrderItemStatus::PENDING);
    }

    public function scopeTakeaway(Builder $query): Builder
    {
        return $query->where('order_type_id', 3);
    }

    public function scopeTableOrder(Builder $query): Builder
    {
        return $query->where('order_type_id', 1);
    }

    public function scopeOnlineDelivery(Builder $query): Builder
    {
        return $query->where('order_type_id', 2);
    }

    public function scopeCompletedOrder(Builder $query)
    {
        return $query->where('status', OrderItemStatus::SERVED);
    }

    public function scopeCancledOrder(Builder $query)
    {
        return $query->where('status', OrderItemStatus::CANCLED);
    }

    public function scopeSuspendedOrder(Builder $query)
    {
        return $query->where('status', OrderItemStatus::SUSPENDED);
    }

    public function scopeNotCancledOrder(Builder $query): Builder
    {
        return $query->where('status', '!=', OrderItemStatus::CANCLED);
    }

    public function scopeNotSuspendedOrder(Builder $query): Builder
    {
        return $query->where('status', '!=', OrderItemStatus::SUSPENDED);
    }

    public function isCancled(): bool
    {
        return $this->status == OrderItemStatus::CANCLED;
    }

    public function isSuspended(): bool
    {
        return $this->status == OrderItemStatus::SUSPENDED;
    }

    public function isServed(): bool
    {
        return $this->status == OrderItemStatus::SERVED;
    }

    public function saveBilling(Billing $billing)
    {
        return $this->update([
            'billing_id' => $billing->id,
        ]);
    }

    public function restoredOrder(): bool
    {
        return $this->update([
            'status' => OrderItemStatus::PENDING
        ]);
    }

    public function getStatusNameAttribute()
    {
        $status = "";
        switch($this->status) {
            case 0:
                $status = "Cancled";
                break;
            case 1:
                $status = "Pending";
                break;
            case 2:
                $status = "Ready";
                break;
            case 3:
                $status = "Served";
                break;
        }

        return $status;
    }


    /**
     * Marked the status of order item as cacanled by customer or waiter
     *
     * @return bool
     */
    public function markedStatusAsCancled(): bool
    {
        return $this->update([
            'status' => OrderItemStatus::CANCLED,
        ]);
    }

    /**
     * Marked the status of order item as cacanled by customer or waiter
     *
     * @return bool
     */
    public function markedStatusAsReady(): bool
    {
        return $this->update([
            'status' => OrderItemStatus::READY,
        ]);
    }

    /**
     * Marked the status of order item as served to customer
     *
     * @return bool
     */
    public function markedStatusAsServed(): bool
    {
        return $this->update([
            'status' => OrderItemStatus::SERVED,
        ]);
    }
}
