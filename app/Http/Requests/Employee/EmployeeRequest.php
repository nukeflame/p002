<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bankAcc' => 'required',
            'bankName' => 'required',
            'canAcess' => 'required',
            'currentAddress' => 'required',
            'dateEmployed' => 'required',
            'department' => 'required',
            'designation' => 'required',
            'dob' => 'required',
            'email' => 'required',
            'employmentType' => 'required',
            'gender' => 'required',
            'idNo' => 'required',
            'idType' => 'required',
            'kraPin' => 'required',
            'linkToUser' => 'required',
            'maritalStatus' => 'required',
            'nationality' => 'required',
            'nextOfKin' => 'required',
            'nextOfKinContacts' => 'required',
            'nhifNo' => 'required',
            'nokIdNo' => 'required',
            'nokIdType' => 'required',
            'nssfNo' => 'required',
            'otherNames' => 'required',
            'paymentMode' => 'required',
            'paymentModeRef' => 'required',
            'payrollNo' => 'required',
            'physicalAddress' => 'required',
            'placeOfWork' => 'required',
            'postalAdrress' => 'required',
            'postalCode' => 'required',
            'relationship' => 'required',
            'streetHouseNo' => 'required',
            'surname' => 'required',
            'tel1' => 'required',
            'tel2' => 'required',
            'townCity' => 'required',
            
        ];
    }
}
