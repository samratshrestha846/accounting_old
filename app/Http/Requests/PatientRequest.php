<?php

namespace App\Http\Requests;

use App\Models\Lab\Patient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PatientRequest extends FormRequest
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
        return [
            'patientCode' => [
                'required',
                Rule::unique('patients')->ignore($this->patient),
            ],
            'name' => ['required', 'string', 'max:191'],
            'address' => ['required', 'string', 'max:191'],
            'email' => ['nullable', 'email'],
            'number' => ['nullable', 'digits:10', 'max:15'],
            'date' => ['nullable', 'date'],
            'gender' => ['required', 'in:' . implode(',', array_keys(Patient::GENDER))],
            'publishStatus' => ['nullable', 'boolean'],
            'description'=>['nullable'],

            //appointment
            'testType' => ['nullable'],
            'staff' => ['nullable'],
            'appointmentdate' => ['nullable'],
            'startTime' => ['nullable'],
            'endTime' => ['nullable'],
            'notes' => ['nullable'],
            'type' => ['nullable'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'patientCode' => Str::random(8),
            'publishStatus' => request('publishStatus') ?? true,
        ]);
    }
}
