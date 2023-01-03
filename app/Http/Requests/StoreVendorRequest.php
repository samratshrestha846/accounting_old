<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorRequest extends FormRequest
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
        return [
            'company_name' => ['string','required'],
            'company_email' => ['nullable','email'],
            'company_phone' => ['nullable','string'],
            'company_address' => ['nullable','string'],
            'pan_vat' => ['nullable'],
            'concerned_name' => ['nullable','string'],
            'concerned_phone' => ['nullable','string'],
            'concerned_email' => ['nullable', 'email'],
            'designation' => ['nullable','string'] ,
            'supplier_code' => ['nullable','unique:vendors'],
        ];
    }
}
