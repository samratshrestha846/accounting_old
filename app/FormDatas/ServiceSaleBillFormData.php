<?php
namespace App\FormDatas;

use App\Models\SalesBills;
use App\Models\UserCompany;
use Illuminate\Support\Facades\Auth;

class ServiceSaleBillFormData {
    public int $fiscal_year_id;
    public string $nep_date;
    public string $eng_date;
    public ?int $client_id;
    public ?string $ledger_no;
    public ?int $file_no;
    public ?int $payment_method;
    public ?int $online_portal;
    public ?int $customer_portal_id;
    public ?int $bank_id;
    public ?string $cheque_no;
    public array $particulars = [];
    public array $quantity = [];
    public array $unit = [];
    public array $rate = [];
    public array $total = [];
    public float $subtotal;
    public float $discountamount;
    public string $alldiscounttype;
    public string $alldtamt;
    public float $taxamount;
    public string $alltaxtype;
    public string $alltax;
    public float $shipping;
    public float $grandtotal;
    public float $vat_refundable;
    public bool $sync_ird;
    public bool $status;
    public string $payment_type;
    public float $payment_amount;
    public string $remarks;
    public ?int $vendor_id;
    public int $billing_type_id;
    public ?string $reference_invoice_no;
    public array $discountamt = [];
    public array $discounttype = [];
    public array $dtamt = [];
    public array $taxamt = [];
    public array $itemtax = [];
    public array $taxtype = [];
    public array $tax = [];
    public ?string $due_date_nep;
    public ?string $due_date_eng;
    public ?float $service_charge;
    public ?string $servicediscounttype;
    public ?float $servicediscountamount;


    public function __construct(
        int $fiscal_year_id,
        string $nep_date,
        string $eng_date,
        ?int $client_id,
        ?string $ledger_no,
        ?int $file_no,
        ?int $payment_method,
        ?int $online_portal,
        ?int $customer_portal_id,
        ?int $bank_id,
        ?string $cheque_no,
        array $particulars,
        array $quantity,
        array $unit,
        array $rate,
        array $total,
        float $subtotal,
        float $discountamount,
        string $alldiscounttype,
        string $alldtamt,
        float $taxamount,
        string $alltaxtype,
        string $alltax,
        float $shipping,
        float $grandtotal,
        float $vat_refundable,
        bool $sync_ird,
        bool $status,
        string $payment_type,
        float $payment_amount,
        string $remarks,
        ?int $vendor_id,
        int $billing_type_id,
        ?string $reference_invoice_no,
        array $discountamt,
        array $discounttype,
        array $dtamt,
        array $taxamt,
        array $itemtax,
        array $taxtype,
        array $tax,
        ?string $due_date_nep,
        ?string $due_date_eng,
        ?float $service_charge,
        ?string $servicediscounttype,
        ?float $servicediscountamount
    )
    {
        $this->fiscal_year_id = $fiscal_year_id;
        $this->nep_date = $nep_date;
        $this->eng_date = $eng_date;
        $this->client_id = $client_id;
        $this->ledger_no = $ledger_no;
        $this->file_no = $file_no;
        $this->payment_method = $payment_method;
        $this->online_portal = $online_portal;
        $this->customer_portal_id = $customer_portal_id;
        $this->bank_id = $bank_id;
        $this->cheque_no = $cheque_no;
        $this->particulars = $particulars;
        $this->quantity = $quantity;
        $this->unit = $unit;
        $this->rate = $rate;
        $this->total = $total;
        $this->subtotal = $subtotal;
        $this->discountamount = $discountamount;
        $this->alldiscounttype = $alldiscounttype;
        $this->alldtamt = $alldtamt;
        $this->taxamount = $taxamount;
        $this->alltaxtype = $alltaxtype;
        $this->alltax = $alltax;
        $this->shipping = $shipping;
        $this->grandtotal = $grandtotal;
        $this->vat_refundable = $vat_refundable;
        $this->sync_ird = $sync_ird;
        $this->status = $status;
        $this->payment_type = $payment_type;
        $this->payment_amount = $payment_amount;
        $this->remarks = $remarks;
        $this->vendor_id = $vendor_id;
        $this->billing_type_id = $billing_type_id;
        $this->reference_invoice_no = $reference_invoice_no;
        $this->discountamt = $discountamt;
        $this->discounttype = $discounttype;
        $this->dtamt = $dtamt;
        $this->taxamt = $taxamt;
        $this->itemtax = $itemtax;
        $this->taxtype = $taxtype;
        $this->tax = $tax;
        $this->due_date_nep = $due_date_nep;
        $this->due_date_eng = $due_date_eng;
        $this->service_charge = $service_charge;
        $this->servicediscounttype = $servicediscounttype;
        $this->servicediscountamount = $servicediscountamount;
    }

    public function getApprovedBy()
    {
        return ($this->status == 1) ? Auth::user()->id : null;
    }

    public function getRealTimeInfo()
    {
        $thisday = date('Y-m-d');
        return ($this->eng_date == $thisday) ? 1 : 0;
    }

    public function getSyncIrdInfo()
    {
        $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
        return ($ird_sync == 1) ? $this->sync_ird : 0;
    }

    public function getPaymentBankId()
    {
        return ($this->payment_method == 2 || $this->payment_method == 3) ? $this->bank_id : null;
    }

    public function getChequeNo()
    {
        return ($this->payment_method == 2) ? $this->cheque_no : null;
    }

    public function getOnlinePortalId()
    {
        return ($this->payment_method == 4) ? $this->online_portal : null;
    }

    public function getCustomerPortalId()
    {
        return ($this->payment_method == 4) ? $this->customer_portal_id : null;
    }

    public function getTransactionNo()
    {
        $billscount = SalesBills::where('billing_type_id', $this->billing_type_id)->count();

        if($billscount == 0)
        {
            return str_pad(1, 8, "0", STR_PAD_LEFT);
        }
        else
        {
            $lastbill = SalesBills::where('billing_type_id', $this->billing_type_id)->latest()->first();
            $newtransaction_no = $lastbill->transaction_no + 1;
            return str_pad($newtransaction_no, 8, "0", STR_PAD_LEFT);
        }
    }

    public function getRegistrationNo()
    {
        $billscount = SalesBills::where('billing_type_id', $this->billing_type_id)->count();
        $ref_tag = '';
        $billing_type_id = $this->billing_type_id;
        if($billing_type_id == 1){
            $ref_tag = 'SeS';
        }elseif($billing_type_id == 2){
            $ref_tag = 'SeP';
        }elseif($billing_type_id == 5){
            $ref_tag = 'SePR';
        }elseif($billing_type_id == 6){
            $ref_tag = 'SeSR';
        }
        if($billscount == 0)
        {
            $reference_no = str_pad(1, 8, "0", STR_PAD_LEFT);
            return $ref_tag.'-'.$reference_no;
        }
        else
        {
            $lastbill = SalesBills::where('billing_type_id', $this->billing_type_id)->latest()->first();

            $newreference_no = $lastbill->reference_no;
            // print_r($newreference_no);exit;
            $expref = explode('-', $newreference_no);
            $ref = $expref[1]+1;

            $reference_no = str_pad($ref, 8, "0", STR_PAD_LEFT);
            return $ref_tag.'-'.$reference_no;
        }
    }

    public function getDiscountAmount()
    {
        return ($this->alldiscounttype == "fixed") ? $this->discountamount : null;
    }

    public function getDiscountPercent()
    {
        return ($this->alldiscounttype == "percent") ? $this->discountamount : null;
    }
}
