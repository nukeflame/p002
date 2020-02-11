<?php

namespace App\Http\Resources\Tax;

use Illuminate\Http\Resources\Json\JsonResource;

class Tax extends JsonResource
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
            'lowerLimit' => $this->lower_limit,
            'taxRate' => $this->tax_rate,
            'upperLimit' => $this->upper_limit,
            'hasNoLimit' => $this->no_limit
        ];
    }
}
