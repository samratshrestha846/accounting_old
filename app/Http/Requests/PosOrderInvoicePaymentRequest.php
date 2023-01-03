<?php

namespace App\Http\Requests;

use App\Enums\DiscountType;
use App\Enums\ServiceChargeType;
use App\Enums\TaxType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PosOrderInvoicePaymentRequest extends FormRequest
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
            'payment_mode'=>['required','exists:paymentmodes,id'],
            'payment_amount' => ['required','numeric'],
            'service_charge_type' => [
                'nullable',
                Rule::in(ServiceChargeType::getAllValues())
            ],
            'service_charge' => [
                'nullable',
                'numeric',
            ],
            'alltaxtype'=> [
                'nullable',
                Rule::in(TaxType::getAllValues()),
            ],
            'alltax'=>[
                'nullable',
                'exists:taxes,id',
            ],
            'alldiscounttype'=>[
                'nullable',
                Rule::in(DiscountType::getAllValues())
            ],
            'alldiscountvalue'=> [
                'nullable',
                'numeric',
            ],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
