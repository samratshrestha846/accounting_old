<?php

namespace App\Http\Requests;

use App\Models\CabinType;
use App\Models\UserCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HotelTableStoreRequest extends FormRequest
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
                Rule::exists('hotel_rooms', 'id')->where(function ($query) use ($currentCompany) {
                    return $query->where(['company_id' => $currentCompany->company_id, 'branch_id' => $currentCompany->branch_id]);
                })
            ],
            'table_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('hotel_tables', 'name')->where(function ($query) use ($currentCompany) {
                    return $query->where(['company_id' => $currentCompany->company_id, 'branch_id' => $currentCompany->branch_id, 'room_id' => $this->room]);
                })
            ],
            'table_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('hotel_tables', 'code')->where(function ($query) use ($currentCompany) {
                    return $query->where(['company_id' => $currentCompany->company_id, 'branch_id' => $currentCompany->branch_id, 'room_id' => $this->room]);
                })
            ],
            'max_capacity' => ['required'],
            'cabin_type' => [
                'nullable',
                $this->is_cabin ? 'required' : '',
            ],
            'cabin_charge' => [
                'nullable',
                $this->is_cabin ? 'required' : '',
                $this->is_cabin ? 'numeric' : '',
            ],
        ];
    }
}
