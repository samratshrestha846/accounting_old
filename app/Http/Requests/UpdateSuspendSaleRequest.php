<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSuspendSaleRequest extends StoreSuspendSaleRequest
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
    public function rules(): array
    {
        $rules = [];

        if($this->alltax){
            $rules['alltax'] = ['exists:taxes,id'];
        }

        return array_merge_recursive($rules, $this->commonRules());
    }
}
