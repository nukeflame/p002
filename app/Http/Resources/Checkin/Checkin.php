<?php

namespace App\Http\Resources\Checkin;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class Checkin extends JsonResource
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
            'idNo' => $this->idNo,
            'staffId' => $this->staffId,
            'staffNo' => $this->staffNo,
            'timeIn' => $this->time_in ,
            'timeOut' => $this->time_out,
            'totalHrs' => $this->totalHrs,
            'overTime' => "No",
            'checkedBy' => $this->checkedBy,
        ];
    }
}
