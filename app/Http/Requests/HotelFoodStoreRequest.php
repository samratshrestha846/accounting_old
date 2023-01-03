<?php

namespace App\Http\Requests;

use App\Models\UserCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HotelFoodStoreRequest extends FormRequest
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
        return $this->commonRules();
    }

    public function commonRules(): array
    {
        $userId = auth()->id();
        $currentCompany = UserCompany::where('user_id', $userId)->where('is_selected', 1)->first();

        return [
            'category' => [
                'required',
                'exists:categories,id'
            ],
            'kitchen' => [
                'required',
                Rule::exists('hotel_kitchens','id')->where(function ($query) use($currentCompany) {
                    return $query->where(['company_id' => $currentCompany->company_id,'branch_id' => $currentCompany->branch_id]);
                })
            ],
            'food_name' => [
                'required',
                'string',
                'max:255',
                // Rule::unique('hotel_foods','food_name')->where(function ($query) use($currentCompany) {
                //     return $query->where(['company_id' => $currentCompany->company_id,'branch_id' => $currentCompany->branch_id]);
                // }),
            ],
            'component' => ['nullable'],
            'description' => ['nullable'],
            'food_image' => ['nullable','image','max:7120'],
            'cooking_time_hour' => ['nullable','max:12'],
            'cooking_time_min' => ['nullable','max:60'],
            'food_price' => ['required','numeric'],
        ];
    }
}
