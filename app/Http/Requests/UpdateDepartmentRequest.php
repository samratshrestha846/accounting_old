<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
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
        $department = $this->department;
        return [
            'device' => ['nullable','exists:devices,id'],
            'name' => ['required', 'unique:departments,name,'.$department->id, 'max:255'],
            'code' => ['required', 'unique:departments,code,'.$department->id, 'max:255'],
            'location' => ['required', 'string', 'max:255']
        ];
    }
}
