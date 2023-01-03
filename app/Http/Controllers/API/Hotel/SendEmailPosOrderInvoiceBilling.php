<?php

namespace App\Http\Controllers\API\Hotel;

use App\Http\Controllers\Controller;
use App\Mail\Hotel\PosOrderBillingInvoiceMail;
use App\Models\Billing;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendEmailPosOrderInvoiceBilling extends Controller
{
    public function __invoke(Request $request, Billing $billing)
    {
        $this->can(['hotel-order-invoice','hotel-order-print','hotel-order-view']);

        $request->validate([
            'email' => ['required','email'],
        ]);

        $userCompany = auth()->user()->currentCompany;

        Mail::to($request['email'])->send(new PosOrderBillingInvoiceMail($userCompany, $billing));

        return $this->responseSuccessMessage("Mail sent successfully.");
    }
}
