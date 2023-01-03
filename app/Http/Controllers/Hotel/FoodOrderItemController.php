<?php

namespace App\Http\Controllers\Hotel;

use App\Actions\CancleHotelOrderItem;
use App\Actions\RestoredCancledHotelOrderItem;
use App\Actions\SuspendHotelOrderItem;
use App\Enums\DiscountType;
use App\Enums\TaxType;
use App\Http\Controllers\Controller;
use App\Http\Requests\HotelOrderItemCancellationRequest;
use App\Http\Requests\Restaurant\HotelOrderItemSuspendationRequest;
use App\Models\HotelOrder;
use App\Models\Paymentmode;
use App\Models\Tax;
use Illuminate\Http\Request;

class FoodOrderItemController extends Controller
{
    protected array $paymentTypes;
    protected array $taxTypes;
    protected array $discountTypes;
    protected $taxes;

    protected $perPages = [5, 10, 20, 50, 100, 250];

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->paymentTypes = Paymentmode::select('id','payment_mode')->active()->get()->toArray();
        $this->taxTypes = TaxType::getAllValues();
        $this->discountTypes = [DiscountType::FIXED, DiscountType::PERCENTAGE];
        $this->taxes = (new Tax())->getAll();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->can('hotel-order-view')) {
            return view('backend.permission.permission');
        }

        $perPage = $request->get('per_page', 10);

        $orderItems = HotelOrder::with([
            'customer:id,name',
            'order_type:id,name',
            'waiter',
            'tables' => function($q) {
                $q->select('hotel_tables.id','hotel_tables.name','hotel_tables.floor_id','hotel_tables.room_id')
                    ->with('floor:id,name','room:id,name');
            },
            'delivery_partner:id,name',
        ])
            ->filters($request->all())
            ->notCancledOrder()
            ->notSuspendedOrder()
            ->latest()
            ->paginate($perPage);

        return view('backend.hotel.order_item.index')
            ->with('title','Order Items')
            ->with('order_items', $orderItems)
            ->with('paymentTypes', $this->paymentTypes)
            ->with('taxTypes', $this->taxTypes)
            ->with('discountTypes', $this->discountTypes)
            ->with('taxes', $this->taxes)
            ->with('perpages', $this->perPages)
            ->with('per_page', $perPage);
    }

    /**
     * View the ongoing order item
     *
     * @return \Illuminate\Http\Response
     */
    public function getOngoingOrderItem(Request $request)
    {
        if (!$request->user()->can('hotel-order-view')) {
            return view('backend.permission.permission');
        }

        $perPage = $request->get('per_page', 10);

        $orderItems = HotelOrder::with([
            'customer',
            'waiter',
            'tables' => function($q) {
                $q->select('hotel_tables.id','hotel_tables.name','hotel_tables.floor_id','hotel_tables.room_id')
                    ->with('floor:id,name','room:id,name');
            },
            'delivery_partner:id,name',
        ])->ongoingOrder()->latest()->paginate($perPage);
        return view('backend.hotel.order_item.ongoing_order_list')
            ->with('order_items', $orderItems)
            ->with('paymentTypes', $this->paymentTypes)
            ->with('taxTypes', $this->taxTypes)
            ->with('discountTypes', $this->discountTypes)
            ->with('taxes', $this->taxes)
            ->with('perpages', $this->perPages)
            ->with('per_page', $perPage);
    }

    /**
     * View the ongoing order item
     *
     * @return \Illuminate\Http\Response
     */
    public function getTakeAwayOrderItem(Request $request)
    {
        if (!$request->user()->can('hotel-order-view')) {
            return view('backend.permission.permission');
        }

        $perPage = $request->get('per_page', 10);

        $orderItems = HotelOrder::with([
            'customer',
            'waiter',
            'tables' => function($q) {
                $q->select('hotel_tables.id','hotel_tables.name','hotel_tables.floor_id','hotel_tables.room_id')
                    ->with('floor:id,name','room:id,name');
            },
            'delivery_partner:id,name',
        ])->takeaway()->latest()->paginate($perPage);
        return view('backend.hotel.order_item.index')
            ->with('title','Take Away Orders')
            ->with('order_items', $orderItems)
            ->with('paymentTypes', $this->paymentTypes)
            ->with('taxTypes', $this->taxTypes)
            ->with('discountTypes', $this->discountTypes)
            ->with('taxes', $this->taxes)
            ->with('perpages', $this->perPages)
            ->with('per_page', $perPage);
    }

    /**
     * View the ongoing order item
     *
     * @return \Illuminate\Http\Response
     */
    public function getOnlineDeliveryOrderItem(Request $request)
    {
        if (!$request->user()->can('hotel-order-view')) {
            return view('backend.permission.permission');
        }

        $perPage = $request->get('per_page', 10);

        $orderItems = HotelOrder::with([
            'customer',
            'waiter',
            'tables' => function($q) {
                $q->select('hotel_tables.id','hotel_tables.name','hotel_tables.floor_id','hotel_tables.room_id')
                    ->with('floor:id,name','room:id,name');
            },
            'delivery_partner:id,name',
        ])->onlineDelivery()->latest()->paginate($perPage);
        return view('backend.hotel.order_item.index')
            ->with('title','Online Delivery Orders')
            ->with('order_items', $orderItems)
            ->with('paymentTypes', $this->paymentTypes)
            ->with('taxTypes', $this->taxTypes)
            ->with('discountTypes', $this->discountTypes)
            ->with('taxes', $this->taxes)
            ->with('perpages', $this->perPages)
            ->with('per_page', $perPage);
    }

    /**
     * View the table order item
     *
     * @return \Illuminate\Http\Response
     */
    public function getTableOrderItem(Request $request)
    {
        if (!$request->user()->can('hotel-order-view')) {
            return view('backend.permission.permission');
        }

        $perPage = $request->get('per_page', 10);

        $orderItems = HotelOrder::with([
            'customer',
            'waiter',
            'tables' => function($q) {
                $q->select('hotel_tables.id','hotel_tables.name','hotel_tables.floor_id','hotel_tables.room_id')
                    ->with('floor:id,name','room:id,name');
            },
            'delivery_partner:id,name',
        ])->tableOrder()->latest()->paginate($perPage);
        return view('backend.hotel.order_item.index')
            ->with('title','Table Orders')
            ->with('order_items', $orderItems)
            ->with('paymentTypes', $this->paymentTypes)
            ->with('taxTypes', $this->taxTypes)
            ->with('discountTypes', $this->discountTypes)
            ->with('taxes', $this->taxes)
            ->with('perpages', $this->perPages)
            ->with('per_page', $perPage);
    }

    /**
     * View the complete order item
     *
     * @return \Illuminate\Http\Response
     */
    public function getCompleteOrderItem(Request $request)
    {
        if (!$request->user()->can('hotel-order-view')) {
            return view('backend.permission.permission');
        }

        $perPage = $request->get('per_page', 10);

        $orderItems = HotelOrder::with([
            'customer',
            'waiter',
            'tables' => function($q) {
                $q->select('hotel_tables.id','hotel_tables.name','hotel_tables.floor_id','hotel_tables.room_id')
                    ->with('floor:id,name','room:id,name');
            },
            'delivery_partner:id,name',
        ])->completedOrder()->latest()->paginate($perPage);
        return view('backend.hotel.order_item.complete_order_list')
            ->with('order_items', $orderItems)
            ->with('paymentTypes', $this->paymentTypes)
            ->with('taxTypes', $this->taxTypes)
            ->with('discountTypes', $this->discountTypes)
            ->with('taxes', $this->taxes)
            ->with('perpages', $this->perPages)
            ->with('per_page', $perPage);
    }

    /**
     * View the cancled order item
     *
     * @return \Illuminate\Http\Response
     */
    public function getCancledOrderItem(Request $request)
    {

        if (!$request->user()->can('hotel-order-view')) {
            return view('backend.permission.permission');
        }

        $perPage = $request->get('per_page', 10);

        $orderItems = HotelOrder::with([
            'customer',
            'waiter',
            'tables' => function($q) {
                $q->select('hotel_tables.id','hotel_tables.name','hotel_tables.floor_id','hotel_tables.room_id')
                    ->with('floor:id,name','room:id,name');
            },
            'delivery_partner:id,name',
        ])->cancledOrder()->latest()->paginate($perPage);
        return view('backend.hotel.order_item.cancled_order_list')
            ->with('order_items', $orderItems)
            ->with('paymentTypes', $this->paymentTypes)
            ->with('taxTypes', $this->taxTypes)
            ->with('discountTypes', $this->discountTypes)
            ->with('taxes', $this->taxes)
            ->with('perpages', $this->perPages)
            ->with('per_page', $perPage);
    }

    /**
     * View the suspended order item
     *
     * @return \Illuminate\Http\Response
     */
    public function getSuspendedOrderItem(Request $request)
    {

        if (!$request->user()->can('hotel-order-view')) {
            return view('backend.permission.permission');
        }

        $perPage = $request->get('per_page', 10);

        $orderItems = HotelOrder::with([
            'customer',
            'waiter',
            'tables' => function($q) {
                $q->select('hotel_tables.id','hotel_tables.name','hotel_tables.floor_id','hotel_tables.room_id')
                    ->with('floor:id,name','room:id,name');
            },
            'delivery_partner:id,name',
        ])->suspendedOrder()->latest()->paginate($perPage);
        return view('backend.hotel.order_item.suspended_order_list')
            ->with('order_items', $orderItems)
            ->with('paymentTypes', $this->paymentTypes)
            ->with('taxTypes', $this->taxTypes)
            ->with('discountTypes', $this->discountTypes)
            ->with('taxes', $this->taxes)
            ->with('perpages', $this->perPages)
            ->with('per_page', $perPage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,HotelOrder $hotelOrder)
    {
        if (!$request->user()->can('hotel-order-view')) {
            return view('backend.permission.permission');
        }

        return view('backend.hotel.order_item.show')
            ->with(
                'orderItem',
                $hotelOrder->load([
                    'waiter',
                    'customer',
                    'tables' => function($q) {
                        $q->select('hotel_tables.id','hotel_tables.name','hotel_tables.floor_id','hotel_tables.room_id')
                            ->with('floor:id,name','room:id,name');
                    },
                    'delivery_partner:id,name',
                    'order_items.food',
                    'billing.billingextras',
                    'billing.payment_infos'
                ])
            )
            ->with('paymentTypes', $this->paymentTypes)
            ->with('taxTypes', $this->taxTypes)
            ->with('discountTypes', $this->discountTypes)
            ->with('taxes', $this->taxes);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Cancle the order item
     *
     * @return \Illuminate\Http\Response
     */
    public function cancleOrder(HotelOrderItemCancellationRequest $request, HotelOrder $hotelOrder)
    {
        if (!$request->user()->can('hotel-order-cancelled')) {
            return view('backend.permission.permission');
        }

        $user = auth()->user();

        try {
            (new CancleHotelOrderItem)->execute($user, $hotelOrder, $request->reason, $request->description);
        } catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('hotel_order.cancled_order')->with('success','Order Item cancled successfully');
    }

    public function suspendOrder(HotelOrderItemSuspendationRequest $request, HotelOrder $hotelOrder)
    {

        if (!$request->user()->can('hotel-order-suspended')) {
            return view('backend.permission.permission');
        }

        $user = auth()->user();

        try {
            (new SuspendHotelOrderItem)->execute($user, $hotelOrder, $request->reason, $request->description);
        } catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('hotel_order.suspended_order')->with('success','Order Item suspended successfully');
    }

    public function restoredOrder(Request $request, HotelOrder $hotelOrder, RestoredCancledHotelOrderItem $restoredCancledHotelOrderItem)
    {
        if (!$request->user()->can('hotel-order-restored')) {
            return view('backend.permission.permission');
        }

        try{
            $restoredCancledHotelOrderItem->execute($hotelOrder);
        } catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('hotel-order.index')->with("success","Order item restored successfully");
    }
}
