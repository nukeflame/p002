<?php

namespace App\Http\Resources\Payments;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Staff\Staff as StaffResource;

class AdvancePay extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $staff = new StaffResource($this->staff);

        return [
           'id' => $this->id,
           'dateIssued' => $this->date_issued,
           'repayIn' => $this->installments,
           'unpaidBal' => $this->unpaid_balance,
           'amountBorrowed' => $this->amount_borrowed,
           'surname' => $this->surname,
           'otherNames' => $this->otherNames,
           'status' => $this->status,
           'staffNo' => $staff->staffNo,
           
        ];
    }
}
