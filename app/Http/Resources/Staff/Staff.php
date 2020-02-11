<?php

namespace App\Http\Resources\Staff;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Checkin\CheckinCollection;
use App\Http\Resources\Parameter\ParameterCollection;
use App\Http\Resources\Department\Department as ResourceDepartment;

class Staff extends JsonResource
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
            'staffNo' => $this->staffNo,
            'otherNames' => $this->otherNames,
            'gender' => $this->gender,
            'idNo' => $this->idNo,
            'tel1' => $this->tel1,
            'department' => new ResourceDepartment($this->department),
            'kraPin' => $this->kraPin,
            'nhifNo' => $this->nhifNo,
            'nssfNo' => $this->nssfNo,
            'payrollNo' => $this->payrollNo,
            'email' => $this->email,
            'checkIns' => new CheckinCollection($this->checkin),
            'parameter' => new ParameterCollection($this->parameter),
            //
            'text' => $this->otherNames . ' '. $this->surname,

        ];
    }
}
