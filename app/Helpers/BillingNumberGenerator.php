<?php
namespace App\Helpers;

use App\Models\Billing;

class BillingNumberGenerator {

    protected int $billingType;
    protected string $referenceNumber = "";
    protected string $transactionNumber = "";
    protected string $code = "";

    public function __construct(int $billingType)
    {
        $this->billingType = $billingType;
        $this->setCode();
        $this->make();
    }

    public function make()
    {
        $lastbill = Billing::where('billing_type_id', $this->billingType)->latest()->first();

        if(!$lastbill)
        {
            $this->transactionNumber = str_pad(1, 8, "0", STR_PAD_LEFT);
            $reference_no = str_pad(1, 8, "0", STR_PAD_LEFT);
            $this->referenceNumber = $this->code.$reference_no;
        }
        else
        {
            $newtransaction_no = $lastbill->transaction_no+1;
            $newreference_no = $lastbill->reference_no;
            $expref = explode('-', $newreference_no);
            $ref = $expref[1]+1;

            $this->transactionNumber = str_pad($newtransaction_no, 8, "0", STR_PAD_LEFT);
            $reference_no = str_pad($ref, 8, "0", STR_PAD_LEFT);
            $this->referenceNumber = $this->code.$reference_no;
        }
    }

    public function getTransactionNumber(): string
    {
        return $this->transactionNumber;
    }

    public function getRefereneceNumber(): string
    {
        return $this->referenceNumber;
    }

    public function setCode()
    {
        switch ($this->billingType) {
            case 1:
                $this->code = 'SB-';
                break;
            case 2:
                $this->code = 'PB-';
                break;
            case 3:
                $this->code = 'CRB-';
                break;
            case 4:
                $this->code = 'PAB-';
                break;
            case 5:
                $this->code = 'PR-';
                break;
            case 6:
                $this->code = 'CR-';
                break;
            case 7:
                $this->code = 'QB-';
                break;
            case 8:
                $this->code = 'HB-';
                break;
          }
    }
}
