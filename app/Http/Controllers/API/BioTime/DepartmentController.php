<?php

namespace App\Http\Controllers\API\BioTime;

use App\Actions\CreateDepartmentAction;
use App\FormDatas\DepartmentFormData;
use App\Http\Controllers\Controller;
use App\Http\Requests\BioTime\StoreDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Models\Device;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Get a list of departments
     *
     * @queryParam per_page integer The perpage number. Example: 10
     * @queryParam page integer The page number. Example: 1
     *
     * @responseFile  responses/Department/departments.get.json
     */
    public function index(Request $request)
    {
        $perPage = $request['per_page'];

        $departments = Department::with(['device']);

        if($perPage){
            $departments = $departments->paginate($perPage);
        }else {
            $departments = $departments->get();
        }

        return DepartmentResource::collection($departments);
    }

    /**
     * Create a Department
     *
     * @bodyParam  device_id integer required The id of the device. Example: 1
     * @bodyParam  name string required The name of the department. Example: Software Department
     * @bodyParam  code string required The code of the department. Example: 001
     * @bodyParam  location string required The location of the department. Example: New York
     *
     * @responseFile  responses/Department/created.get.json
     */
    public function store(StoreDepartmentRequest $request)
    {
        $device = Device::where('uuid', $request->device)->firstOrFail();

        $departmentFormData = new DepartmentFormData(
            $request->name,
            $request->code,
            $request->location,
            $device->id
        );

        $department = (new CreateDepartmentAction)->execute($departmentFormData);

        return $this->responseOk(
            "Department created successfully",
            DepartmentResource::make($department->load('device'))
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
