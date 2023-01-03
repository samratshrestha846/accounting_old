<?php

namespace App\Filters\HotelOrder;
use App\Filters\FiltersAbstract;

class HotelOrderFilters extends FiltersAbstract {

    public array $filters = [
        'order_type' => OrderTypeFilter::class,
        'table_id' => TableFilter::class,
        'room_id' => RoomFilter::class,
        'floor_id' => FloorFilter::class,
        'order_at' => OrderDateFilter::class,
        'status' => OrderStatusFilter::class,
        'sort_by' => SortingFilter::class,
        'search' => SearchFilter::class,
    ];
}
