<?php

namespace App\Http\Requests;

use App\Models\UserCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HotelKitchenStoreRequest extends FormRequest
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
        $userId = auth()->id();
        $currentCompany = UserCompany::where('user_id', $userId)->where('is_selected', 1)->first();

        return [
            'room' => [
                'required',
                Rule::exists('hotel_rooms','id')->where(function ($query) use($currentCompany) {
                    return $query->where(['company_id' => $currentCompany->company_id,'branch_id' => $currentCompany->branch_id]);
                })
            ],
            'kitchen_name' => [
                'required',
                Rule::unique('hotel_kitchens','kitchen_name')->where(function ($query) use($currentCompany) {
                    return $query->where(['company_id' => $currentCompany->company_id,'branch_id' => $currentCompany->branch_id]);
                })
            ],
            'remarks' => ['nullable','string','max:1000'],
        ];
    }
}
