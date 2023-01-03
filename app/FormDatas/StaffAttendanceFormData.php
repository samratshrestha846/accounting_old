<?php
namespace App\FormDatas;

class StaffAttendanceFormData {

    public int $deviceId;
    public int $staffId;
    public string $date;
    public string $inTime;
    public string $outTime;
    public int $status;
    public ?string $overTime;
    public ?string $remarks;

    public function __construct(
        int $deviceId,
        int $staffId,
        string $date,
        string  $inTime,
        string $outTime,
        int $status,
        string $overTime = null,
        string $remarks = null
    )
    {
        $this->deviceId = $deviceId;
        $this->staffId = $staffId;
        $this->date = $date;
        $this->inTime = $inTime;
        $this->outTime = $outTime;
        $this->status = $status;
        $this->overTime = $overTime;
        $this->remarks = $remarks;
    }
}
