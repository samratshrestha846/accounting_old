<?php

use App\Http\Controllers\API\Hotel\BillingController;
use App\Http\Controllers\API\Hotel\FloorController as HotelFloorController;
use App\Http\Controllers\API\Hotel\FoodItemController;
use App\Http\Controllers\API\Hotel\HotelOrderController;
use App\Http\Controllers\API\Hotel\HotelOrderDetail;
use App\Http\Controllers\API\Hotel\PosOrderController;
use App\Http\Controllers\API\Hotel\RoomController as HotelRoomController;
use App\Http\Controllers\API\Hotel\SendEmailPosOrderInvoiceBilling;
use App\Http\Controllers\API\Hotel\TableController as HotelTableController;
use App\Http\Controllers\API\HotelDeliveryPartnerController as APIHotelDeliveryPartnerController;
use App\Http\Controllers\Hotel\CabinTypeController;
use App\Http\Controllers\Hotel\FloorController;
use App\Http\Controllers\Hotel\FoodController;
use App\Http\Controllers\Hotel\FoodOrderItemController;
use App\Http\Controllers\Hotel\KitchenController;
use App\Http\Controllers\Hotel\PosOrderInvoiceController;
use App\Http\Controllers\Hotel\ReservationController;
use App\Http\Controllers\Hotel\RoomController;
use App\Http\Controllers\Hotel\SalesReportController;
use App\Http\Controllers\Hotel\TableController;
use App\Http\Controllers\HotelDeliveryPartnerController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {



    Route::post('hotel-floor/search', [FloorController::class, 'search'])->name('hotel-floor.search');

    Route::resource('cabintype', CabinTypeController::class);


    // Route::get('order/pos_invoice', [PosOrderInvoiceController::class, 'index']);

    Route::resource('hotel-floor', FloorController::class);
    Route::resource('hotel-room', RoomController::class);
    Route::resource('hotel-table', TableController::class);
    Route::resource('hotel-kitchen', KitchenController::class);
    Route::resource('hotel-food', FoodController::class);
    Route::resource('delivery-partner', HotelDeliveryPartnerController::class);


    //sales report single Food
    Route::get('hotel-food/sales/{id}', [SalesReportController::class, 'sales_report'])->name('hotel-sales-report.sales_report');


    //hotel order
    Route::resource('hotel-order', FoodOrderItemController::class);
    Route::get('hotel_order/ongoingorder', [FoodOrderItemController::class, 'getOngoingOrderItem'])->name('hotel_order.ongoing_order');
    Route::get('hotel_order/takeaway',[FoodOrderItemController::class,'getTakeAwayOrderItem'])->name('hotel_order.take_away');
    Route::get('hotel_order/onlinedelivery',[FoodOrderItemController::class,'getOnlineDeliveryOrderItem'])->name('hotel_order.online_delivery');
    Route::get('hotel_order/tableorder',[FoodOrderItemController::class,'getTableOrderItem'])->name('hotel_order.table_order');
    Route::get('hotel_order/completeorder', [FoodOrderItemController::class, 'getCompleteOrderItem'])->name('hotel_order.complete_order');
    Route::get('hotel_order/cancledorder', [FoodOrderItemController::class, 'getCancledOrderItem'])->name('hotel_order.cancled_order');
    Route::get('hotel_order/suspendedorder', [FoodOrderItemController::class, 'getSuspendedOrderItem'])->name('hotel_order.suspended_order');
    Route::post('hotel-order/{hotel_order}/suspend', [FoodOrderItemController::class, 'suspendOrder'])->name('hotel_order.suspend');
    Route::post('hotel-order/{hotel_order}/cancle', [FoodOrderItemController::class, 'cancleOrder'])->name('hotel_order.cancle');

    Route::post('hotel-order/{hotel_order}/restored', [FoodOrderItemController::class, 'restoredOrder'])->name('hotel_order.restored');

    Route::get('hotel-reservation/avaliable_table', [ReservationController::class, 'avaliable_table'])->name('hotel-reservation.avaliable_table');
    Route::get('hotel-reservation/cancel/{hotelReservation}', [ReservationController::class, 'cancel'])->name('hotel-reservation.cancel');

    Route::resource('hotel-reservation', ReservationController::class);


    Route::get('hotel-sales-report', [SalesReportController::class, 'index'])->name('hotel-sales-report');
    Route::get('hotel-sales-report/filter-by', [SalesReportController::class, 'filter_by'])->name('hotel-sales-report.filter');
    Route::get('hotel-sales-report/single/{salesReport}', [SalesReportController::class, 'view_sales_single'])->name('hotel-sales-report.single');



    //testing available time slot
    // Route::get('hotel-reservation/table/checkAvailability', [ReservationController::class,'getAvailableTableList']);

    //hotel_pos
    Route::get('order/pos_invoice', [PosOrderInvoiceController::class, 'index'])->name('hotel_order.pos_invoice');
    Route::get('order/pos_invoice/{hotel_order}', [PosOrderInvoiceController::class, 'edit'])->name('hotel_order.pos_invoice.edit');
    Route::get('order/billing/{billing}/view-invoice', [PosOrderInvoiceController::class, 'viewBillingInvoice']);
    Route::get('order/billing/{billing}/generateinvoice', [PosOrderInvoiceController::class, 'greateBillingInvoice'])->name('hotel_order.pos.generateinvoicebill');
    Route::get('order/billing/{billing}/generateinvoice-pdf', [PosOrderInvoiceController::class, 'greateBillingInvoicePdf'])->name('hotel_order.pos.generateinvoicebill.pdf');

    //print
    Route::get('order/pos_invoice/{hotel_order}/print_kot', [PosOrderInvoiceController::class, 'printKotOrderItem'])->name('hotel_order.print.kot');
    Route::get('order/pos_invoice/{hotel_order}/print_order_item_invoice', [PosOrderInvoiceController::class, 'printOrderItemInvoice'])->name('hotel_order.print.order_invoice');

    //send order item pos invoice
    Route::post('api/hotel/order/billing/{billing}/sendinvoice', [SendEmailPosOrderInvoiceBilling::class])->name('hotel_order.billing_invoice.send_email');
    // //testing available time slot
    // Route::get('hotel-reservation/checkAvailability', [ReservationController::class,'getAvailableTableList']);

    /**
     * Api
     */

    //table, floor and room roue
    Route::get('api/tables',[HotelTableController::class, 'index']);
    Route::get('api/floors',[HotelFloorController::class, 'index']);
    Route::get('api/rooms',[HotelRoomController::class,'index']);

    //delivery partner
    Route::get('api/hotel/order/delivery_partners', [APIHotelDeliveryPartnerController::class,'index']);

    // order detail route
    Route::get('/api/hotel/order/get_order_detail', [HotelOrderDetail::class, 'getTodaySaleOrderReport']);

    //orders route
    Route::get('api/hotel/order/fooditems', [FoodItemController::class, 'index']);
    Route::get('api/hotel/order/tables', [HotelTableController::class, 'getHotelOrderTableList']);
    Route::post('api/hotel/order/fooditems/place', [PosOrderController::class, 'placePosItemOrder']); //place the order item
    Route::patch('api/hotel/order/fooditems/{hotel_order}', [PosOrderController::class, 'updateOrderItem']); //update the order item
    Route::get('api/hotel/order/orders', [HotelOrderController::class, 'index']);
    Route::get('api/hotel/ongoing/orders', [HotelOrderController::class, 'getOngoingOrderList']);
    Route::get('api/hotel/order/orders/{hotel_order}', [HotelOrderController::class, 'show']); //find order item
    Route::post('api/hotel/order/orders/{hotel_order}/cancle', [HotelOrderController::class, 'cancleOrderItem']); //cancle order item
    Route::post('api/hotel/order/orders/{hotel_order}/suspend', [HotelOrderController::class, 'suspendOrderItem']); //suspend order item
    Route::post('api/hotel/order/orders/{hotel_order}/restore', [HotelOrderController::class, 'restoreOrderItem']); //restore order item


    //billing
    Route::get('api/hotel/order/billings/{billing}', [BillingController::class, 'show']);

    Route::post('api/hotel/order/fooditems/{hotel_order}/payment', [PosOrderController::class, 'makePayment']);

    //send order item pos invoice
    Route::post('api/hotel/order/billing/{billing}/sendinvoice', [SendEmailPosOrderInvoiceBilling::class])->name('hotel_order.billing_invoice.send_email');
});
