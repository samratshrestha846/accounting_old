<?php

namespace App\Http\Requests\BioTime;

use App\Models\Department;
use App\Models\Device;
use App\Models\UserCompany;
use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user() && UserCompany::where('user_id', auth()->id())->where('is_selected', 1)->first();;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $user = auth()->id();
        $currentCompany = UserCompany::where('user_id', $user)->where('is_selected', 1)->first();
        return [
            'device' => [
                'required',
                function ($attribute, $value, $fail) use($currentCompany) {

                    if(!($value && $currentCompany))
                        return true;

                    $exist = Device::where([
                        'uuid'=> $value,
                        'company_id' => $currentCompany->company_id,
                        'branch_id' => $currentCompany->company_id
                    ])->exists();

                    if(!$exist) {
                        $fail('The given '.$attribute.' is invalid');
                    }

                    $exist = Department::whereHas('device', function($q) use($value, $currentCompany){
                        return $q->where([
                            'id' => $value,
                            'company_id' => $currentCompany->company_id,
                            'branch_id' => $currentCompany->company_id
                        ]);
                    })->where([
                        'device_id'=> $value,
                        'company_id' => $currentCompany->company_id,
                        'branch_id' => $currentCompany->company_id
                    ])->exists();

                    if($exist) {
                        $fail('The given '.$attribute.' is already exist for department');
                    }
                },
            ],
            'name' => ['required', 'unique:departments,name', 'max:255'],
            'code' => ['required', 'unique:departments,code', 'max:255'],
            'location' => ['required', 'string', 'max:255']
        ];
    }
}
