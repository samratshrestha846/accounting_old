<?php
namespace App\Actions;

use App\Models\Department;

class DeleteDepartmentAction {

    public function execute(Department $department)
    {
        if($department->hasStaffs())
            throw new \Exception("You cannot delete department");
        $department->delete();
    }
}
