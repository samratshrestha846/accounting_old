<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicalHistroyRequest extends FormRequest
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
            'patientId' => ['required', 'exists:patients,id'],
            'staffId' => ['nullable', 'exists:hospital_staff,id'],
            'startDate' => ['nullable', 'date', 'lte:endDate'],
            'endDate' => ['nullable', 'date', 'gte:startDate'],
            'appointmentDate' => ['nullable', 'date'],
            'prescription' => ['nullable'],
            'symptoms' => ['nullable'],

        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'patientId' => $this->patient->id,
        ]);
    }
}
