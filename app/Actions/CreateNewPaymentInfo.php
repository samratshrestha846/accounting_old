<?php
namespace App\Actions;

use App\Models\Billing;
use App\Models\PaymentInfo;
use App\Models\User;

class CreateNewPaymentInfo {

    public function execute(Billing $billing, float $payment_amount, float $total_paid_amount, User $paidTo): PaymentInfo
    {
        $engtoday = date('Y-m-d');

        return PaymentInfo::create([
            'billing_id' => $billing->id,
            'payment_type' => $this->getPaymentType($payment_amount, $total_paid_amount),
            'payment_amount' => $payment_amount,
            'payment_date' => $engtoday,
            'total_paid_amount' => $total_paid_amount,
            'paid_to' => $paidTo->id,
        ]);
    }

    public function getPaymentType(float $payment_amount, float $total_paid_amount): string
    {
        $paymentType = "unpaid";

        if($total_paid_amount <=0){
            $paymentType = PaymentInfo::PAYMENT_TYPE['unpaid'];
        } else if($total_paid_amount < $payment_amount) {
            $paymentType = PaymentInfo::PAYMENT_TYPE['partially_paid'];
        }else {
            $paymentType = PaymentInfo::PAYMENT_TYPE['paid'];
        }

        return $paymentType;
    }
}
