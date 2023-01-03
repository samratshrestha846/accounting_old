<?php
namespace App\Factories;

use App\Enums\ServiceChargeType;
use App\FormDatas\HotelOrderFormdata;
use App\FormDatas\HotelOrderItemFormdata;
use App\Models\HotelTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class HotelOrderFactory {
    public static function make(Request $request, User $waiter): HotelOrderFormdata
    {

        $tableIds = collect($request->tables);

        $totalCabinCharge = HotelTable::select('id','is_cabin','cabin_charge')
            ->whereIn('id',$tableIds->toArray())
            ->where('is_cabin', 1)
            ->get()
            ->sum('cabin_charge');

        $order_at = now();
        $status = (int) $request->status;

        $data = new HotelOrderFormdata(
            (int) $request->order_type_id,
            (int) $request->customer_id,
            $tableIds,
            $request->delivery_partner_id ? (int) $request->delivery_partner_id : null,
            $order_at,
            $waiter->id,
            $status,
            ServiceChargeType::PERCENT,
            $request->service_charge ? (float) $request->service_charge : null,
            $is_cabin = true,
            $totalCabinCharge,
            $request->alltaxtype,
            $request->alltax ? (int) $request->alltax: null,
            $request->alldiscounttype,
            $request->alldiscountvalue ? (float) $request->alldiscountvalue : null
        );

        $data->addOrderItem(
            ...collect($request->items)->map(function($item){
                return new HotelOrderItemFormdata(
                    $order_id = 0,
                    (int) $item['food_id'],
                    (int) $item['quantity'],
                    $item['tax_type'] ?? null,
                    Arr::get($item,'tax_rate_id') ? (int) $item['tax_rate_id'] : null,
                    $item['discount_type'] ?? null,
                    Arr::get($item,'discount_value') ? (float) $item['discount_value'] : null
                );
            })
        );

        return $data;
    }
}
