<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeRequest;
use App\Staff;
use App\Checkin;
use App\Http\Resources\Staff\StaffCollection;
use App\Http\Resources\Staff\Staff as StaffResource;
use App\Http\Resources\Checkin\Checkin as CheckinResource;
use Auth;
use Carbon\Carbon;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Staff::latest()->get();
        return new StaffCollection($employees);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        $employee = new Staff();
        $employee->staffNo = str_pad(count(Staff::all()) + 1, 4, "0", STR_PAD_LEFT);
        // $employee->bankAcc = $r->bankAcc;
        // $employee->bankName = $r->bankName;
        $employee->bank_id = 1;
        $employee->canAcess = $r->canAcess;
        $employee->currentAddress = $r->currentAddress;
        $employee->dateEmployed = $r->dateEmployed;
        $employee->department_id = $r->department;
        $employee->designation = $r->designation;
        $employee->dob = $r->dob;
        $employee->email = $r->email;
        $employee->employmentType = $r->employmentType;
        $employee->gender = $r->gender;
        $employee->idNo = $r->idNo;
        $employee->idType = $r->idType;
        $employee->kraPin = $r->kraPin;
        $employee->linkToUser = $r->linkToUser;
        $employee->maritalStatus = $r->maritalStatus;
        $employee->nationality = $r->nationality;
        $employee->nextOfKin = $r->nextOfKin;
        $employee->nextOfKinContacts = $r->nextOfKinContacts;
        $employee->nhifNo = $r->nhifNo;
        $employee->nokIdNo = $r->nokIdNo;
        $employee->nokIdType = $r->nokIdType;
        $employee->nssfNo = $r->nssfNo;
        $employee->occupation = $r->occupation;
        $employee->otherNames = $r->otherNames;
        $employee->paymentMode = $r->paymentMode;
        $employee->paymentModeRef = $r->paymentModeRef;
        $employee->payrollNo = $r->payrollNo;
        $employee->physicalAddress = $r->physicalAddress;
        $employee->placeOfWork = $r->placeOfWork;
        $employee->postalAdrress = $r->postalAdrress;
        $employee->postalCode = $r->postalCode;
        $employee->relationship = $r->relationship;
        $employee->streetHouseNo = $r->streetHouseNo;
        $employee->surname = $r->surname;
        $employee->tel1 = $r->tel1;
        $employee->tel2 = $r->tel2;
        $employee->townCity = $r->townCity;
        $employee->save();


        return new StaffResource($employee);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
