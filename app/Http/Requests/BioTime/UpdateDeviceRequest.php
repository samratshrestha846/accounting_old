<?php

namespace App\Http\Requests\BioTime;

use App\Models\UserCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user() && UserCompany::where('user_id', auth()->id())->where('is_selected', 1)->first();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $device = $this->device;
        $userId = auth()->id();
        $currentCompany = UserCompany::where('user_id', $userId)->where('is_selected', 1)->first();
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('devices')->where(function ($query) use($device, $currentCompany) {
                    return $query->where('id','!=', $device->id)->where(['company_id' => $currentCompany->company_id,'branch_id' => $currentCompany->branch_id]);
                })
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
                Rule::unique('devices')->where(function ($query) use($device, $currentCompany) {
                    return $query->where('id','!=', $device->id)->where(['company_id' => $currentCompany->company_id,'branch_id' => $currentCompany->branch_id]);
                })
            ],
            'ip_address' => ['required','string'],
            'area' => ['required','string'],
            'last_activity' => ['required','string'],
            'status' => ['nullable','boolean'],
        ];
    }
}
