<?php

namespace App\Http\Requests;

use App\Enums\DiscountType;
use App\Enums\OrderItemStatus;
use App\Enums\ServiceChargeType;
use App\Enums\TaxType;
use App\Models\HotelOrderType;
use App\Models\UserCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class PosItemOrderRequest extends FormRequest
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
        $userId = auth()->id();
        $currentCompany = UserCompany::where('user_id', $userId)->where('is_selected', 1)->first();

        return [
            'order_type_id' => ['required','exists:hotel_order_types,id'],
            'customer_id' => [
                'required',
                'exists:clients,id',
            ],
            'tables' => [
                'array',
            ],
            'tables.*' => [
                (new RequiredIf($this->order_type_id == 1)),
                Rule::exists('hotel_tables','id')->where(function ($query) use($currentCompany) {
                    return $query->where(['company_id' => $currentCompany->company_id,'branch_id' => $currentCompany->branch_id]);
                })
            ],
            'delivery_partner_id' => [
                'nullable',
                (new RequiredIf($this->order_type_id == HotelOrderType::ONLINE_DELIVERY)),
                Rule::exists('hotel_delivery_partners','id')->where(function ($query) use($currentCompany) {
                    return $query->where(['company_id' => $currentCompany->company_id,'branch_id' => $currentCompany->branch_id]);
                })
            ],
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
            'items' => ['required','array'],
            'items.*.food_id' => [
                'required',
                Rule::exists('hotel_foods','id')->where(function ($query) use($currentCompany) {
                    return $query->where(['company_id' => $currentCompany->company_id,'branch_id' => $currentCompany->branch_id]);
                })
            ],
            'items.*.quantity' => [
                'required',
                'numeric',
            ],
            'items.*.tax_rate_id' => ['nullable','exists:taxes,id'],
            'items.*.tax_type' => [
                'nullable',
                Rule::in(TaxType::getAllValues())
            ],
            'items.*.discount_type' => [
                'nullable',
                Rule::in(DiscountType::getAllValues())
            ],
            'items.*.value_discount' => ['nullable','numeric'],
            'status' => ['required', Rule::in([OrderItemStatus::CANCLED, OrderItemStatus::PENDING, OrderItemStatus::SUSPENDED, OrderItemStatus::SERVED])]
        ];
    }
}
