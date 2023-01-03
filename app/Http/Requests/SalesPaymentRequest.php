<?php

namespace App\Http\Requests;

use App\Enums\DiscountType;
use App\Enums\ProductPurchase;
use App\Enums\TaxType;
use App\Models\OutletProduct;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalesPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $outletId = $this->outlet['id'] ?? null;

        return [
            'customer_id'=>'required|exists:clients,id',
            'payment_mode'=>['required','integer','digits_between:1,3'],
            'payment_amount' => ['required','numeric'],
            'alltaxtype'=> [
                'nullable',
                function ($attribute, $value, $fail) {

                    if(!$value)
                        return true;

                    if(!in_array($value, TaxType::getAllValues())) {
                        $fail('The '.$attribute.' must be either '.TaxType::EXCLUSIVE.' or '.TaxType::INCLUSIVE);
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
                        $fail('The '.$attribute.' must be either '.DiscountType::FIXED.' or '.DiscountType::PERCENTAGE);
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
                'exists:outlet_products,product_id,outlet_id,'.$outletId,
            ],
            'products.*.quantity' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use($outletId) {

                    //if the products is not array then stop here
                    if(!is_array($this->products))
                        return true;

                    $index = (int) filter_var($attribute, FILTER_SANITIZE_NUMBER_INT);
                    $product = $this->products[$index];

                    $productId = $product['product_id'] ?? null;
                    $quantity = $value;
                    $purchaseType = $product['purchase_type'] ?? null;

                    //if the purchase type is primary unit then validate quantity with primary stock
                    if($purchaseType == ProductPurchase::PRIMARY_UNIT){

                        $exist = OutletProduct::query()
                        ->where(['outlet_id' => $outletId,'product_id' => $productId])
                        ->where('primary_stock', '>=', $quantity )->exists();

                        if(!$exist){
                            $fail('The '.$attribute.' cannot exceed than product primary stock ');
                        }
                    } else {
                        $exist = OutletProduct::query()
                        ->where(['outlet_id' => $outletId,'product_id' => $productId])
                        ->where('secondary_stock', '>=', $quantity )->exists();

                        if(!$exist){
                            $fail('The '.$attribute.' cannot exceed than product secondary stock ');
                        }
                    }
                },
            ],
            'products.*.product_price' => ['required','numeric'],
            'products.*.tax_rate_id' => ['nullable','exists:taxes,id'],
            'products.*.tax_type' => [
                'sometimes',
                function ($attribute, $value, $fail) {

                    if(!$value)
                        return true;

                    if(!in_array($value, TaxType::getAllValues())) {
                        $fail('The '.$attribute.' must be either '.TaxType::EXCLUSIVE.' or '.TaxType::INCLUSIVE);
                    }
                },
            ],
            'products.*.discount_type' => [
                'sometimes',
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
            'remarks'=>'nullable',
        ];
    }
}
