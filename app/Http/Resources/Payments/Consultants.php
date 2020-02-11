<?php

namespace App\Http\Resources\Payments;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Consultant;

class Consultants extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $cons = Consultant::latest()->get();
        $total_amount = null;
        foreach ($cons as $c) {
            $total_amount  = $c->sum('amount');
        }

        return [
            'id' => $this->id,
            'surname' => $this->surname,
            'otherNames' => $this->otherNames,
            'alias' => $this->alias,
            'phone' => $this->phone,
            'designation' => $this->designation,
            'amount' => $this->amount,
            'email' => $this->email,
            'status' => $this->status,
            'totalAmount' => $total_amount
        ];
    }
}
