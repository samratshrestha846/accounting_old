<?php

namespace App\Http\Requests\BioTime;

use App\Models\Attendance;
use App\Models\Device;
use App\Models\Staff;
use App\Models\UserCompany;
use Illuminate\Foundation\Http\FormRequest;

class StoreStaffAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user() && UserCompany::where('user_id', auth()->id())->where('is_selected', 1)->first();;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $employeedId = $this->employee_id;
        $userId = auth()->id();
        $currentCompany = UserCompany::where('user_id', $userId)->where('is_selected', 1)->first();

        return [
            'device_id' => [
                'required',
                // 'exists:devices,id',
                function ($attribute, $value, $fail) use($currentCompany) {

                    if(!($value && $currentCompany))
                        return true;

                    $exist = Device::where([
                        'uuid'=> $value,
                        'company_id' => $currentCompany->company_id,
                        'branch_id' => $currentCompany->company_id
                    ])->exists();

                    if(!$exist) {
                        $fail('The given '.$attribute.' doest not exist for the employee attendance');
                    }
                },
            ],
            'employee_id' => [
                'required',
                // 'exists:staff,employee_id',
                function ($attribute, $value, $fail) use($currentCompany) {

                    if(!($value && $currentCompany))
                        return true;

                    $exist = Staff::where([
                        'employee_id'=> $value,
                        'company_id' => $currentCompany->company_id,
                        'branch_id' => $currentCompany->company_id
                    ])->exists();

                    if(!$exist) {
                        $fail('The given '.$attribute.' doesnt not exist for the employee attendance');
                    }
                },
            ],
            'date' => [
                'required',
                'date_format:Y-m-d',
                'before:tomorrow',
                // function ($attribute, $value, $fail) use($employeedId) {

                //     if(!($value && $employeedId))
                //         return true;

                //     $exist = Attendance::where(['staff_id'=> $employeedId,'date' => $value])->exists();
                //     if($exist) {
                //         $fail('The given '.$attribute.' already exist for the employee attendance');
                //     }
                // },
            ],
            'in_time' => ['required','date_format:H:i:s'],
            'out_time' => ['nullable','date_format:H:i:s','after:in_time'],
            'remarks' => ['nullable'],
        ];
    }
}
