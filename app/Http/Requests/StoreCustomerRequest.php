<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create-customer');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_type'=>'required',
            'name'=>'sometimes',
            'client_code'=>'nullable|unique:clients',
            'pan_vat'=>'',
            'phone'=>'required|numeric|min:6',
            'email'=>['nullable','email'],
            'province'=>'',
            'district'=>'',
            'local_address'=>'',
            'concerned_name'=>'',
            'concerned_email'=>'',
            'concerned_phone'=>'',
            'designation'=>''
        ];
    }
}
