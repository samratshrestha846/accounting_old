<?php
namespace App\Actions;

use App\FormDatas\DepartmentFormData;
use App\Models\Department;

class UpdateDepartmentAction {

    public function execute(Department $department, DepartmentFormData $data): bool
    {
        return $department->update([
            'name' => $data->name,
            'code' => $data->code,
            'location' => $data->location,
            'parent_dept' => $data->parentDept,
        ]);
    }
}
