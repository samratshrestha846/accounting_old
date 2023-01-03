<?php

namespace App\Http\Requests;

use App\Enums\ProductPurchase;
use Illuminate\Foundation\Http\FormRequest;

class StoreSuspendSaleRequest extends FormRequest
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
        $rules = [];

        if($this->alltax){
            $rules['alltax'] = ['exists:taxes,id'];
        }

        return array_merge_recursive($rules, $this->commonRules());
    }

    public function commonRules(): array
    {
        return [
            'customer_id' => ['required','exists:clients,id'],
            'products' => ['required','array'],
            'products.*.product_id' => ['required','exists:products,id'],
            'products.*.quantity' => ['required','integer'],
            'products.*.purchase_type' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {

                    if(!in_array($value, ProductPurchase::getAllValues())) {
                        $fail('The '.$attribute.' doest not exist for this product.');
                    }
                },
            ],
            'products.*.tax_type' => ['nullable','string'],
            'products.*.tax_rate_id' => ['nullable','exists:taxes,id'],
            'products.*.discount_type' => ['nullable','string'],
            'products.*.discount_value' => ['nullable','numeric'],
            'alltaxtype' => ['string','nullable'],
            'alldiscounttype' => ['string','nullable'],
            'alldiscount' => ['nullable','numeric'],
        ];
    }
}
