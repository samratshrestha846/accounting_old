<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
            'patientId' => $this->patient->id,
        ]);
    }
}
