<?php

namespace App\Http\Requests;

use App\Models\UserCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HotelRoomStoreRequest extends FormRequest
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
            'room_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('hotel_rooms','name')->where(function ($query) use($currentCompany) {
                    return $query->where(['company_id' => $currentCompany->company_id,'branch_id' => $currentCompany->branch_id, 'floor_id' => $this->floor]);
                })
            ],
            'room_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('hotel_rooms','code')->where(function ($query) use($currentCompany) {
                    return $query->where(['company_id' => $currentCompany->company_id,'branch_id' => $currentCompany->branch_id]);
                })
            ],
            'floor' => ['required','exists:hotel_floors,id'],
            'table_capacity' => ['required','integer'],
        ];
    }
}
