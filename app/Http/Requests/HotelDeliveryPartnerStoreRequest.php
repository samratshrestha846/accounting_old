<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HotelDeliveryPartnerStoreRequest extends FormRequest
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
        return $this->commonRules();
    }

    public function commonRules(): array
    {
        return [
            'name' => [
                'string',
                'required',
                'max:255'
            ],
            'email' => [
                'required',
                'email',
                'max:255'
            ],
            'address' => [
                'required',
                'string',
                'max:255'
            ],
            'contact_number' => [
                'required',
                'string',
                'min:5',
                'max:25'
            ],
            'logo' => [
                'nullable',
                'image',
                'max:7000',
            ],
            'province_id' => [
                'required',
                'exists:provinces,id',
            ],
            'district_id' => [
                'required',
                'exists:districts,id',
            ],
        ];
    }
}
