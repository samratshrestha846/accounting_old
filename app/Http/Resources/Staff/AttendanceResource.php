<?php

namespace App\Http\Resources\Staff;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $status = null;

        if($this->present) {
            $status = "Present";
        }

        if($this->paid_leave || $this->unpaid_leave){
            $status = "Absent";
        }

        return [
            'id' => $this->id,
            'employee_id' => $this->staff_id,
            'date' => $this->date,
            'in_out' => $this->entry_time,
            'out_time' => $this->exit_time,
            'remarks' => $this->remarks,
            'status' => $status
        ];
    }
}
