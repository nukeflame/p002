<?php

namespace App\Http\Resources\Overtime;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class Overtime extends JsonResource
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
            'surname' => $this->surname,
            'otherNames' => $this->otherNames,
            'staffNo' => $this->staffNo,
            'to' => $this->to_period,
            'from' => $this->from_period,
            'hours' => $this->hours,
            'date' => Carbon::parse($this->date)->format('d M Y'),
            'hours' => $this->hours,
            'notes' => $this->notes,
            'amount' => $this->amount,
            'status' => $this->status,
            'employeeId' => $this->staff_id,
        ];
    }
}
