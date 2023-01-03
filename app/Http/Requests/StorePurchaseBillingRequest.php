<?php

namespace App\Http\Requests;

use App\Enums\DiscountType;
use App\Enums\TaxType;
use App\Models\GodownProduct;
use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseBillingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'fiscal_year_id' => ['required','exists:fiscal_years,id'],
            'ledger_no' => ['required'],
            'file_no' => ['required'],
            'payment_mode'=> ['required', 'digits_between:1,3'],
            'payment_amount' => ['required','numeric'],
            'payment_type' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {

                    if(!$value)
                        return true;

                    if(!in_array($value, ['partially_paid','unpaid','paid'])) {
                        $fail('The '.$attribute.' must be either partially_paid or unpaid or paid.');
                    }
                },
            ],
            'entry_date_in_ad' => ['required','date_format:Y-m-d'],
            'entry_date_in_bs' => [
                'required',
                'date_format:Y-m-d'
            ],
            'shipping_amount' => ['numeric'],
            'convertToBill' => ['nullable','boolean'],
            'purchaseOrder' => ['nullable','exists:purchase_orders,id'],
            'alltaxtype'=> [
                'nullable',
                function ($attribute, $value, $fail) {

                    if(!$value)
                        return true;

                    if(!in_array($value, TaxType::getAllValues())) {
                        $fail('The '.$attribute.' must be either '.TaxType::EXCLUSIVE.' or '.TaxType::INCLUSIVE.' or '.TaxType::NONE);
                    }
                },
            ],
            'alltax'=>[
                'nullable',
                'exists:taxes,id',
            ],
            'alldiscounttype'=>[
                'nullable',
                function ($attribute, $value, $fail) {

                    if(!$value)
                        return  true;

                    if(!in_array($value, DiscountType::getAllValues())) {
                        $fail('The '.$attribute.' must be either '.DiscountType::FIXED.' or '.DiscountType::PERCENTAGE.' or '.DiscountType::NONE);
                    }
                },
            ],
            'alldiscountvalue'=> [
                'nullable',
                'numeric',
            ],
            'products' => ['required','array'],
            'products.*.product_id' => [
                'required',
                'exists:products,id',
            ],
            'products.*.quantity' => [
                'required',
                'integer',
            ],
            // 'products.*.product_price' => ['required','numeric'],
            'products.*.tax_rate_id' => ['nullable','exists:taxes,id'],
            'products.*.tax_type' => [
                'nullable',
                function ($attribute, $value, $fail) {

                    if(!$value)
                        return true;

                    if(!in_array($value, TaxType::getAllValues())) {
                        $fail('The '.$attribute.' must be either '.TaxType::EXCLUSIVE.' or '.TaxType::INCLUSIVE);
                    }
                },
            ],
            'products.*.discount_type' => [
                'nullable',
                function ($attribute, $value, $fail) {

                    if(!$value)
                        return true;

                    if(!in_array($value, DiscountType::getAllValues())) {
                        $fail('The '.$attribute.' must be either '.DiscountType::FIXED.' or '.DiscountType::PERCENTAGE);
                    }
                },
            ],
            'products.*.value_discount' => ['nullable','numeric'],
            'gross_total' => ['required','numeric'],
            'remarks'=> ['required'],
            'vat_refundable_amount' => ['nullable','numeric'],
            'sync_ird' => ['required','integer','between:0,1'],
            'status' => ['nullable','integer','between:0,1']
        ];
    }
}
