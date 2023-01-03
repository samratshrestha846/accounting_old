<?php
namespace App\Actions;

use App\FormDatas\StaffFormData;
use App\Models\Staff;

class CreateStaffAction {

    public function execute(StaffFormData $staffFormData): staff
    {
        return Staff::create([
            'employee_id' => $staffFormData->employeeId,
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
            'image' => $staffFormData->image,
            'national_id' => $staffFormData->nationalId,
            'documents' => $staffFormData->document,
            'contract' => $staffFormData->contract
        ]);
    }
}
