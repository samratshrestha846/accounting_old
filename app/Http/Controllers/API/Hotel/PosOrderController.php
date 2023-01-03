<?php

namespace App\Http\Controllers\API\Hotel;

use App\Actions\CreateBulkFoodOrderItem;
use App\Actions\CreateFoodItemOrder;
use App\Actions\PosOrderInvoicePayment;
use App\Actions\UpdateFoodOrderItem;
use App\Actions\UpdateHotelOrderExtraCharges;
use App\Enums\OrderItemStatus;
use App\Enums\ServiceChargeType;
use App\Factories\HotelOrderFactory;
use App\FormDatas\HotelOrderFormdata;
use App\FormDatas\HotelOrderItemFormdata;
use App\Http\Controllers\Controller;
use App\Http\Requests\PosItemOrderRequest;
use App\Http\Requests\PosOrderInvoicePaymentRequest;
use App\Models\HotelOrder;
use App\Models\HotelTable;
use App\Models\Paymentmode;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PosOrderController extends Controller
{
    public function placePosItemOrder(PosItemOrderRequest $request)
    {
        $this->can(['hotel-order-invoice','hotel-order-create']);

        $waiter = auth()->user();

        $data = HotelOrderFactory::make($request, $waiter);

        DB::beginTransaction();
        try{
            $foodOrder = (new CreateFoodItemOrder(
                new CreateBulkFoodOrderItem
            ))->execute($waiter, $data);
            DB::commit();
        } catch(\Exception $e){
            DB::rollBack();
            return $this->responseError($e->getMessage());
        }

        return $this->responseOk(
            "Food item ordered successfully",
            $foodOrder->load('order_items'),
            201
        );
    }

    public function updateOrderItem(PosItemOrderRequest $request, HotelOrder $hotelOrder)
    {
        $this->can(['hotel-order-invoice','hotel-order-edit']);

        $user = auth()->user();

        $data = HotelOrderFactory::make($request, $hotelOrder->waiter);

        DB::beginTransaction();
        try{
            (new UpdateFoodOrderItem(
                new CreateBulkFoodOrderItem
            ))->execute($user, $hotelOrder, $data);
            DB::commit();
        } catch(\Exception $e){
            DB::rollBack();
            return $this->responseError($e->getMessage());
        }

        return $this->responseSuccessMessage(
            "Food item order updated successfully"
        );
    }

    /**
     * Create a payment
     *
     * @return JSON
     */
    public function makePayment(PosOrderInvoicePaymentRequest $request, HotelOrder $hotelOrder)
    {
        $this->can(['hotel-order-invoice','hotel-order-payment']);

        $user = auth()->user();

        $paymentmode = Paymentmode::findOrFail($request->payment_mode);
        DB::beginTransaction();
        try {

            //update the order item
            (new UpdateHotelOrderExtraCharges)->execute(
                $hotelOrder,
                $request->alltaxtype,
                (int) $request->alltax,
                $request->alldiscounttype,
                (float) $request->alldiscountvalue,
                (int) $request->service_charge
            );

            $data = (new PosOrderInvoicePayment)->execute($user, $hotelOrder, $paymentmode, (float) $request->payment_amount, $request->remarks);
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->responseError($e->getMessage());
        }

        return $this->responseOk(
            "Payment successfull",
            $data
        );

    }
}
