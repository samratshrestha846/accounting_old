<?php

namespace App\Http\Controllers\API\BioTime;

use App\Actions\CreateStaffAttendanceAction;
use App\FormDatas\StaffAttendanceFormData;
use App\Http\Controllers\Controller;
use App\Http\Requests\BioTime\StoreStaffAttendanceRequest;
use App\Http\Requests\StaffAttendanceRequest;
use App\Http\Resources\Staff\AttendanceResource;
use App\Models\Staff;
use App\Models\Device;
use Illuminate\Http\Request;

class StaffAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Create a Staff Attendance
     *
     * @bodyParam  device_id string required The id of the device. Example: 1
     * @bodyParam  employee_id string required The employee id of the employee. Example: 001
     * @bodyParam  date string required The date of the attendance. Example: 2021-03-04
     * @bodyParam  in_time string required The Enter time of the employee in the office. Example: 01:02:03
     * @bodyParam  out_time string nullable The exit time of the employeee from the office. Example: 02:02:03
     * @bodyParam  remarks nullable The remarks of the attendance. Example: Early leave
     *
     * @responseFile  responses/StaffAttendance/created.get.json
     * @responseFile 401 responses/401.get.json
     */
    public function store(StoreStaffAttendanceRequest $request)
    {
        $device = Device::where('uuid', $request->device_id)->firstOrFail();
        $employee = Staff::where('employee_id',$request->employee_id)->firstOrFail();
        $request->validate([
            'device_id' => [
                function ($attribute, $value, $fail) use($device, $employee) {

                    $exist = $employee->belongsDeviceId($device->id);
                    if(!$exist) {
                        $fail('The '.$attribute.' doesnt exist for the employee');
                    }
                },
            ]
        ]);

        $staffAttendanceForm = new StaffAttendanceFormData(
            $device->id,
            $employee->id,
            $request->date,
            $request->in_time,
            $request->out_time,
            $status = 1,
            $overtime = null,
            $request->remarks,
        );

        $attendance = (new CreateStaffAttendanceAction)->execute($staffAttendanceForm);

        return $this->responseOk(
            "Staff attendance created successfully",
            AttendanceResource::make($attendance),
            201
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
