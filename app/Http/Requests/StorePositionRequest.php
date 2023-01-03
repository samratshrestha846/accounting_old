<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePositionRequest extends FormRequest
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
        $rules = [
            'name' => ['unique:positions,name']
        ];

        return array_merge_recursive(
            $rules,
            $this->commonRules()
        );
    }

    public function commonRules(): array
    {
        return [
            'name' => ['required'],
            'status' => ['integer','digits_between:0,1']
        ];
    }
}
