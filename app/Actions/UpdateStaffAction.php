<?php
namespace App\Actions;

use App\FormDatas\StaffFormData;
use App\Models\Staff;

class UpdateStaffAction {

    public function execute(Staff $staff, StaffFormData $staffFormData): bool
    {
        return $staff->update([
            'position_id' => $staffFormData->positionId,
            'department_id' => $staffFormData->departmentId,
            'first_name' => $staffFormData->firstName,
            'last_name' => $staffFormData->lastName,
            'email' => $staffFormData->email,
            'gender' => $staffFormData->gender,
            'emp_type' => $staffFormData->empType,
            'phone' => $staffFormData->phone,
            'date_of_birth' => $staffFormData->dateOfBirth,
            'city' => $staffFormData->city,
            'address' => $staffFormData->address,
            'postcode' => $staffFormData->postcode,
            'join_date' => $staffFormData->joinDate,
            'image' => $staffFormData->image ? $staffFormData->image : $staff->image,
            'national_id' => $staffFormData->nationalId ? $staffFormData->nationalId : $staff->national_id,
            'documents' => $staffFormData->document ? $staffFormData->document : $staff->documents,
            'contract' => $staffFormData->contract ? $staffFormData->contract : $staff->contract
        ]);
    }
}
