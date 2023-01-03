<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceSaleBillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fiscal_year_id' => 'required',
            'nep_date' => 'required',
            'eng_date' => 'required',
            'client_id' => '',
            'ledger_no' => '',
            'file_no' => '',
            'payment_method' => '',
            'online_portal' => '',
            'customer_portal_id' => '',
            'bank_id' => '',
            'cheque_no' => '',
            'particulars' => 'required',
            'quantity' => 'required',
            'rate' => 'required',
            'total' => 'required',
            'subtotal' => 'required',
            'discountamount' => '',
            'alldiscounttype' => '',
            'alldtamt' => '',
            'taxamount' => '',
            'alltaxtype' => '',
            'alltax' => '',
            'shipping' => '',
            'grandtotal' => 'required',
            'vat_refundable' => '',
            'sync_ird' => 'required',
            'status' => 'required',
            'payment_type' => 'required',
            'payment_amount' => 'required',
            'remarks' => 'required'
        ];
    }
}
