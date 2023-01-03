<?php

namespace App\Http\Requests;

use App\Enums\DiscountType;
use App\Enums\TaxType;
use App\Models\GodownProduct;
use Illuminate\Foundation\Http\FormRequest;
use function App\NepaliCalender\dateeng;
use function App\NepaliCalender\datenep;
class StoreSalesBillingRequest extends FormRequest
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
    public function rules($godownId = null): array
    {
        $godownId = $godownId ? $godownId : $this->godown['id'] ?? null;
        return [
            'fiscal_year_id' => ['required','exists:fiscal_years,id'],
            'customer_id' => ['required','exists:clients,id'],
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
                'exists:godown_products,product_id,godown_id,'.$godownId,
            ],
            'products.*.quantity' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use($godownId) {

                    //if the products is not array then stop here
                    if(!is_array($this->products))
                        return true;

                    $index = (int) filter_var($attribute, FILTER_SANITIZE_NUMBER_INT);
                    $product = $this->products[$index];

                    $productId = $product['product_id'] ?? null;
                    $quantity = $value;

                    $exist = GodownProduct::where(['product_id' => $productId,'godown_id'=> $godownId])
                    ->where('stock','>=', $quantity)
                    ->exists();

                    if(!$exist){
                        $fail('The '.$attribute.' cannot exceed more than primary stock ');
                    }

                },
            ],
            // 'products.*.product_price' => ['required','numeric'],
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
            'remarks'=> ['required'],
            'vat_refundable_amount' => ['nullable','numeric'],
            'sync_ird' => ['required','integer','between:0,1'],
            'status' => ['nullable','integer','between:0,1']
        ];
    }
}
