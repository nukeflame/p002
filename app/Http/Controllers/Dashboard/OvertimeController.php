<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Staff;
use App\Overtime;
use App\Http\Resources\Overtime\OvertimeCollection;
use App\Http\Resources\Overtime\Overtime as OvertimeResource;

class OvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $overtime = Overtime::latest()->get();
        return new OvertimeCollection($overtime);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $staff = Staff::findOrFail($request->employeeId);
        if ($staff) {
            $overtime = new Overtime();
            $overtime->surname = $staff->surname;
            $overtime->otherNames = $staff->otherNames;
            $overtime->staffNo = $staff->staffNo;
            $overtime->amount = $request->amount;
            $overtime->date = $request->date;
            $overtime->notes = $request->notes;
            $overtime->from_period = $request->from;
            $overtime->hours = $request->hours;
            $overtime->to_period = $request->to;
            $overtime->status = $request->status;
            $overtime->staff_id = $staff->id;
            $overtime->save();
        }

        return new OvertimeResource($overtime);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $staff = Staff::findOrFail($request->employeeId);
        if ($staff) {
            $overtime = Overtime::findOrFail($id);
            $overtime->surname = $staff->surname;
            $overtime->otherNames = $staff->otherNames;
            $overtime->staffNo = $staff->staffNo;
            $overtime->amount = $request->amount;
            $overtime->date = $request->date;
            $overtime->notes = $request->notes;
            $overtime->from_period = $request->from;
            $overtime->hours = $request->hours;
            $overtime->to_period = $request->to;
            $overtime->status = $request->status;
            $overtime->staff_id = $staff->id;
            $overtime->update();
        }

        return new OvertimeResource($overtime);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $ex = explode(',', $id);
        // $overtime = Overtime::findOrFail($id);
        return response()->json($id);
    }
}
