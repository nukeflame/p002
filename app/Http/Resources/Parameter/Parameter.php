<?php

namespace App\Http\Resources\Parameter;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Parameter\ParameterCategory as ResourceParameterCategory;

class Parameter extends JsonResource
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
            'defValue' => $this->default_value,
            'description' => $this->description,
            'isRequired' => $this->isRequired,
            'isDefault' => $this->isDefault,
            'category' => new ResourceParameterCategory($this->category),
            'name' => $this->name,
            'useDefault' => $this->useDefault,
            'amount' => $this->whenPivotLoaded('staff_parameters', function () {
                return $this->pivot->amount;
            }),
            'isActive' => $this->whenPivotLoaded('staff_parameters', function () {
                return $this->pivot->is_active;
            }),
            'text' => $this->name,
        ];
    }
}
