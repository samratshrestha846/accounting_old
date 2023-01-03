<?php

namespace App\Http\Requests;

use App\Models\UserCompany;
use Illuminate\Validation\Rule;

class UpdateStaffRequest extends StoreStaffRequest
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
        $staff = $this->staff;
        $user = auth()->user();
        $currentCompany = UserCompany::where('user_id', $user->id)->where('is_selected', 1)->first();

        $rules = [
            'employee_id' => [
                Rule::unique('staff')->where(function ($query) use($staff){
                    return $query->where('id','!=', $staff->id)->where('department_id', $this->department);
                })
            ],
            'email' => ['unique:staff,email,'.$staff->id]
        ];

        return array_merge_recursive(
            $rules,
            $this->commonRules()
        );
    }
}
