<?php

namespace App\Http\Resources\Period;

use Illuminate\Http\Resources\Json\JsonResource;

class Period extends JsonResource
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
            'endingDate' => $this->ending_date,
            'payYear' => $this->pay_year,
            'payMonth' => $this->pay_month,
            'isCurrent' => $this->is_current,
            'beginingDate' => $this->begining_date,
            //sel2
            'text' => $this->payMonth
        ];
    }
}
