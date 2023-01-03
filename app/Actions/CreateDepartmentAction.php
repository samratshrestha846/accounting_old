<?php
namespace App\Actions;

use App\FormDatas\DepartmentFormData;
use App\Models\Department;

class CreateDepartmentAction {

    public function execute(DepartmentFormData $departmentFormData): Department
    {
        return Department::create([
            'device_id' => $departmentFormData->deviceId,
            'name' => $departmentFormData->name,
            'code' => $departmentFormData->code,
            'location' => $departmentFormData->location,
            'parent_dept' => $departmentFormData->parentDept,
        ]);
    }
}
