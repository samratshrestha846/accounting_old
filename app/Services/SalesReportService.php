<?php
namespace App\Services;

use App\Enums\OrderItemStatus;
use App\Models\Billing;
use App\Models\HotelOrder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SalesReportService {

    /**
     * Get the sale report of outlet
     *
     * @return array
     */
    public function getOuletTodaySalesReport(User $user, int $outletId): array
    {
        return [
            'cash_payment' => Billing::where('outlet_id', $outletId)
                ->when(!$user->isSuperAdmin(), function($q) use($user){
                    $q->where('entry_by', $user->id);
                })
                ->pos()
                ->today()
                ->paymentCash()
                ->billingSale()
                ->sum('grandtotal'),
            'cheque_payment' => Billing::where('outlet_id', $outletId)
                ->when(!$user->isSuperAdmin(), function($q) use($user){
                    $q->where('entry_by', $user->id);
                })
                ->pos()
                ->today()
                ->paymentCheque()
                ->billingSale()
                ->sum('grandtotal'),
            'creditcard_payment' => 0.00,
        ];
    }

    /**
     * Get the sale report of hotel order
     *
     * @return array
     */
    public function getHotelOrderTodaySaleReport(User $user): array
    {
        return [
            'total' => DB::table('hotel_orders')->whereDate('order_at', now())->count(),
            'total_pending' => DB::table('hotel_orders')->select('id')->where('status',OrderItemStatus::PENDING)->count(),
            'total_suspended' => DB::table('hotel_orders')->select('id')->where('status',OrderItemStatus::SUSPENDED)->count(),
            'total_today_order' => DB::table('hotel_orders')->select('id')->whereDate('order_at', now())->count(),
            'total_take_away' => HotelOrder::takeAway()->count(),
            'total_online_delivery' => HotelOrder::onlineDelivery()->count(),
            'total_table_order' => HotelOrder::tableOrder()->count(),
            'total_cancled' => HotelOrder::cancledOrder()->count(),
            'total_served' => HotelOrder::completedOrder()->count(),
        ];
    }
}
