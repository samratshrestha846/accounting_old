<?php

namespace App\Mail;

use App\Models\Billing;
use App\Models\UserCompany;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class POSBillMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected UserCompany $userCompany;
    protected Billing $billing;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UserCompany $userCompany, Billing $billing)
    {
        $this->userCompany = $userCompany;
        $this->billing = $billing;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('backend.pos.billing.pos_invoice')
                    ->with([
                        'userCompany' => $this->userCompany,
                        'billing' => $this->billing->load('client','payment_infos','billingextras.product')
                    ]);
    }
}
