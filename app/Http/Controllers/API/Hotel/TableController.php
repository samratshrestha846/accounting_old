<?php

namespace App\Http\Controllers\API\Hotel;

use App\Enums\OrderItemStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Restaurant\HotelOrderTableResource;
use App\Http\Resources\Restaurant\TableResource;
use App\Models\HotelOrder;
use App\Models\HotelTable;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index(Request $request)
    {
        $tables = HotelTable::select('id','name','code','room_id','floor_id','is_cabin','cabin_charge')
            ->filters($request->all())
            ->get();

        return response()->json([
            'data' => $tables
        ]);
    }

    public function getHotelOrderTableList()
    {
        $this->can(['hotel-order-invoice']);

        $tables = HotelTable::select('id','name','code','room_id','floor_id','is_cabin','cabin_charge')
            ->withCount(['busyOrders' => function($q) {
                $q->whereDate('order_at', now())->limit(1);
            }])
            ->with([
                'room' => function($q){
                    return $q->select('id','name','code');
                },
                'floor' => function($q){
                    return $q->select('id','name','code');
                },
            ])
            ->get();

        return HotelOrderTableResource::collection($tables);
    }
}
