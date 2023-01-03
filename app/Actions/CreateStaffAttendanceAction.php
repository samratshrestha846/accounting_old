<?php
namespace App\Actions;

use App\FormDatas\StaffAttendanceFormData;
use App\Models\Attendance;
use Carbon\Carbon;

class CreateStaffAttendanceAction {

    public function execute(StaffAttendanceFormData $data): Attendance
    {
        $isPresent = $data->status == 1 ? true : false;
        $isPaidLeave = $data->status == 2 ? true : false;
        $isUnapidLeave = $data->status == 3 ? true: false;

        return Attendance::updateOrCreate(
            [
                'staff_id' => $data->staffId,
                'date' => $data->date,
            ],
            [

                'monthyear' => Carbon::parse($data->date)->format('F, Y'),
                'present' => $isPresent ,
                'paid_leave' => $isPaidLeave,
                'unpaid_leave' => $isUnapidLeave,
                'entry_time' => $data->inTime,
                'exit_time' => $data->outTime,
                'overtime' => $data->overTime
            ]
        );
    }
}
