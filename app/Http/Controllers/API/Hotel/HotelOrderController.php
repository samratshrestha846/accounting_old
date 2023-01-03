<?php

namespace App\Http\Controllers\API\Hotel;

use App\Actions\CancleHotelOrderItem;
use App\Actions\RestoredCancledHotelOrderItem;
use App\Actions\SuspendHotelOrderItem;
use App\Http\Controllers\Controller;
use App\Http\Resources\Restaurant\HotelOrderResource;
use App\Models\HotelOrder;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class HotelOrderController extends Controller
{
    public function index(Request $request)
    {
        $this->can(['hotel-order-invoice','hotel-order-view']);

        $perPage = $request->per_page;

        $hotelOrders = HotelOrder::query()->select([
            'id',
            'order_type_id',
            'customer_id',
            'billing_id',
            'delivery_partner_id',
            'order_at',
            'total_items',
            'tax_type',
            'tax_rate_id',
            'tax_value',
            'discount_type',
            'discount_value',
            'service_charge_type',
            'service_charge',
            'total_service_charge',
            'total_tax',
            'total_discount',
            'sub_total',
            'total_cost',
            'waiter_id',
            'status',
        ])->with([
            'customer' => function($q) {
                $q->select('id','name','phone','email');
            },
            'tables' => function($q) {
                $q->select('hotel_tables.id','hotel_tables.name','hotel_tables.floor_id','hotel_tables.room_id')
                    ->with('floor:id,name','room:id,name');
            },
            'waiter' => function($q) {
                $q->select('id','name','email');
            },
            'order_type:id,name',
            'delivery_partner:id,name',
        ])->filters($request->all())
        ->when($request->status, function($q, $value) {
            $q->where('status', $value);
        });

        if($perPage && $perPage > 0) {
            $hotelOrders = $hotelOrders->paginate($perPage);
        } else {
            $hotelOrders = $hotelOrders->get();
        }

        return HotelOrderResource::collection($hotelOrders);
    }

    public function getOngoingOrderList(Request $request)
    {
        $this->can(['hotel-order-invoice','hotel-order-view']);

        $perPage = $request->per_page;

        $hotelOrders = HotelOrder::select([
            'id',
            'customer_id',
            'billing_id',
            'delivery_partner_id',
            'order_at',
            'total_items',
            'tax_type',
            'tax_rate_id',
            'tax_value',
            'discount_type',
            'discount_value',
            'service_charge_type',
            'service_charge',
            'total_service_charge',
            'total_tax',
            'total_discount',
            'sub_total',
            'total_cost',
            'waiter_id',
            'status',
        ])->with([
            'customer' => function($q) {
                $q->select('id','name','phone','email');
            },
            'tables' => function($q) {
                $q->select('hotel_tables.id','hotel_tables.name','hotel_tables.floor_id','hotel_tables.room_id')
                    ->with('floor:id,name','room:id,name');
            },
            'waiter' => function($q) {
                $q->select('id','name','email');
            },
            'order_type:id,name',
            'delivery_partner:id,name',
        ])->ongoingOrder();

        if($perPage && $perPage > 0) {
            $hotelOrders = $hotelOrders->paginate($perPage);
        } else {
            $hotelOrders = $hotelOrders->get();
        }

        return HotelOrderResource::collection($hotelOrders);
    }

    public function show(HotelOrder $hotelOrder)
    {
        $this->can(['hotel-order-invoice','hotel-order-view']);

        return $this->responseOk(
            "Hotel order fetched successfully",
            $hotelOrder->load([
                'order_items' => function($q) {
                    $q->selectedAttr();
                },
                'customer' => function($q) {
                    $q->select('id','name','phone','email');
                },
                'floor' =>  function($q) {
                    $q->select('id','name','code');
                },
                'room' =>  function($q) {
                    $q->select('id','name','code');
                },
                'tables' => function($q) {
                    $q->select('hotel_tables.id','hotel_tables.name','hotel_tables.floor_id','hotel_tables.room_id')
                        ->with('floor:id,name','room:id,name');
                },
                'waiter' => function($q) {
                    $q->select('id','name','email');
                },
                'order_type:id,name',
                'delivery_partner:id,name',
            ]),
        );
    }

    public function cancleOrderItem(Request $request, HotelOrder $hotelOrder)
    {
        $this->can(['hotel-order-invoice','hotel-order-cancelled']);


        $request->validate([
            'reason' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $user = auth()->user();

        try {
            (new CancleHotelOrderItem)->execute($user, $hotelOrder, $request->reason , $request->description);
        } catch(\Exception $e) {
            return $this->responseError($e->getMessage());
        }

        return $this->responseSuccessMessage(
            "Order item cancled successfully"
        );
    }

    public function suspendOrderItem(Request $request, HotelOrder $hotelOrder)
    {
        $this->can(['hotel-order-invoice','hotel-order-suspended']);

        $request->validate([
            'reason' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $user = auth()->user();

        try {
            (new SuspendHotelOrderItem)->execute($user, $hotelOrder, $request->reason , $request->description);
        } catch(\Exception $e) {
            return $this->responseError($e->getMessage());
        }

        return $this->responseSuccessMessage(
            "Order item suspended successfully"
        );
    }

    public function restoreOrderItem(HotelOrder $hotelOrder)
    {
        $this->can(['hotel-order-invoice','hotel-order-restored']);

        try {
            (new RestoredCancledHotelOrderItem)->execute($hotelOrder);
        } catch(\Exception $e) {
            return $this->responseError($e->getMessage());
        }

        return $this->responseSuccessMessage(
            "Order item restored successfully"
        );
    }
}
