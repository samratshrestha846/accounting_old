<?php

namespace App\Http\Requests;

use App\Enums\EmpType;
use App\Enums\Gender;
use App\Models\UserCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStaffRequest extends FormRequest
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
        $userId = auth()->id();
        $currentCompany = UserCompany::where('user_id', $userId)->where('is_selected', 1)->first();
        $rules = [
            'employee_id' => [
                Rule::unique('staff')->where(function ($query) use($currentCompany) {
                    return $query->where([
                        'department_id'=> $this->department,
                        'company_id' => $currentCompany->company_id,
                        'branch_id' => $currentCompany->company_id
                    ]);
                })
            ],
            'email' => ['unique:staff,email']
        ];

        return array_merge_recursive(
            $rules,
            $this->commonRules()
        );
    }

    public function commonRules(): array
    {
        return [
            'employee_id' => [
                'required',
            ],
            'department' => ['required','exists:departments,id'],
            'position' => ['required', 'exists:positions,id'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'gender' => ['nullable', Rule::in(Gender::getAllValues())],
            'emp_type' => ['nullable', Rule::in(EmpType::getAllValues())],
            'phone' => ['required', 'min:6'],
            'date_of_birth' => ['nullable', 'date_format:Y-m-d'],
            'city' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'postcode' => ['nullable', 'string'],
            'join_date' => ['nullable', 'date_format:Y-m-d'],
            'image' => ['nullable','image', 'mimes:png,jpg,jpeg'],
            'national_id' => ['nullable', 'mimes:pdf'],
            'documents' => ['nullable', 'mimes:pdf'],
            'contract' => ['nullable', 'mimes:pdf'],
        ];
    }
}
