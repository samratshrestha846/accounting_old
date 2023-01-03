<?php

namespace App\Http\Controllers\API\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function show($billing_id)
    {
        $this->can(['hotel-order-invoice','hotel-order-view']);

        $billing = Billing::select('*')
            ->with('billingextras','payment_infos','user_entry')
            ->hotelPosBill()
            ->findOrFail($billing_id);

        return $this->responseOk(
            "Billing fetched successfully",
            $billing
        );
    }
}
