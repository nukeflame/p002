<?php

namespace App\Http\Resources\Payroll;

use Illuminate\Http\Resources\Json\JsonResource;

class Payroll extends JsonResource
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
            'daysIn' => $this->days_in,
            'department' => $this->department,
            'email' => $this->email,
            'taxBeforeRelief' => $this->tax_before_relief,
            'grossSalaray' => $this->gross_salary,
            'basicSalary' => $this->gross_salary,
            'nameSlip' => $this->name_slip,
            'netSalary' => $this->net_salary,
            'surname' => $this->surname,
            'otherNames' => $this->other_names,
            'paye' => $this->paye,
            'payslipNo' => $this->payroll_no,
            'periodDate' => $this->period_date,
            'periodId' => $this->period_id,
            'personalRelief' => $this->personal_relief,
            'staffId' => $this->staff_id,
            'staffNo' => $this->staff->staffNo,
            'status' => $this->status,
            'taxablePay' => $this->taxable_pay,
            'totalDeductions' => $this->total_deductions,
            'designation' => $this->staff->designation,
            'nhifNo' => $this->staff->nhifNo,
            'preTaxDeduction' => $this->staff->nhifNo,
            'postTaxDEduction' => $this->staff->nhifNo,
            'tel1' => $this->staff->tel1,
        ];
    }
}
