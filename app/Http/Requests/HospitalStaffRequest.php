<?php

namespace App\Http\Requests;

use App\Models\Lab\HospitalDesignation;
use App\Rules\CanLogin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HospitalStaffRequest extends FormRequest
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

        if($this->login == 1){
            return [
                'name' => ['required'],
                'email' => ['required', 'email'],
                'designationId' => ['required', 'exists:hospital_designations,id'],
                'phone' => ['nullable', 'numeric'],
                'description' => ['nullable'],
                'address' => ['nullable'],
                'login' => ['required', 'boolean'],
                'password' => 'required_if:login,1|same:password_confirmation|string',
            ];
        }else{
            return [
                'name' => ['required'],
                'email' => ['required', 'email'],
                'designationId' => ['required', 'exists:hospital_designations,id'],
                'phone' => ['nullable', 'numeric'],
                'description' => ['nullable'],
                'address' => ['nullable'],
                'login' => ['required', 'boolean'],

            ];
        }
    }
    public function prepareForValidation()
    {
        $this->merge([
            'login' => $this->login ? true : false,
        ]);
    }
}
