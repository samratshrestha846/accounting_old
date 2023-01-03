<?php

namespace App\Http\Requests\BioTime;

use App\Models\UserCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDeviceRequest extends FormRequest
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
        $departmentId = $this->department;
        $userId = auth()->id();
        $currentCompany = UserCompany::where('user_id', $userId)->where('is_selected', 1)->first();

        return [
            'uuid' => [
                'required',
                Rule::unique('devices')->where(function ($query) use($currentCompany) {
                    return $query->where(['company_id' => $currentCompany->company_id,'branch_id' => $currentCompany->branch_id]);
                })
            ],
            'name' => [
                'required',
                'string',
                'unique:devices,name'
                // function ($attribute, $value, $fail) use($departmentId) {

                //     if(!($value && $departmentId))
                //         return true;

                //     $exist = Device::where(['department_id'=> $departmentId,'name' => $value])->exists();
                //     if($exist) {
                //         $fail('The '.$attribute.' already exist for the device');
                //     }
                // },
            ],
            'serial_number' => [
                'required',
                'string',
                'unique:devices,serial_number'
            ],
            'ip_address' => ['required','string'],
            'area' => ['required','string'],
            'last_activity' => ['nullable','string'],
            'status' => ['nullable','boolean'],
        ];
    }
}
