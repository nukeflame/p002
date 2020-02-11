<?php

namespace App\Http\Resources\Leaves;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Leaves\LeavesType as ResourceLeaveType;

class Leaves extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'appDate' => $this->application_date->toDateString(),
            'duration' => $this->duration,
            'employeeName' => $this->staff_name,
            'startDate' => $this->start_date->toDateString(),
            'endDate' => $this->end_date->toDateString(),
            'leaveType' => new ResourceLeaveType($this->leave_type),
            'employeeId' => $this->staff_id,
            'status' => $this->status,
        ];
    }
}
