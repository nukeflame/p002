<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Notifications\Leaves\LeaveApplied;
use App\Http\Controllers\Controller;
use Auth;
use Notification;
use App\User;
use App\Staff;
use App\Events\Leaves\CreateLeaveEvent;
use App\Events\Leaves\DestroyLeaveEvent;
use App\Leave;
use Carbon\Carbon;
use App\Http\Resources\Leaves\Leaves as ResourceLeave;
use App\Http\Resources\Leaves\LeavesCollection;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leaves = Leave::latest()->get();
        return new LeavesCollection($leaves);
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
    public function store(Request $request)
    {
        $staff = Staff::findOrFail($request->employeeId);
       
        $leave = new Leave();
        $leave->staff_name = $staff->otherNames . ' ' . $staff->surname;
        $leave->leave_type_id = $request->leaveType;
        $leave->application_date = Carbon::now()->toDateString();
        $leave->start_date =  Carbon::parse($request->fromRange)->toDateString();
        $leave->end_date =  Carbon::parse($request->toRange)->toDateString();
        $leave->duration = 7;
        $leave->status = 0;
        $leave->staff_id = $staff->id;
        $leave->save();

        if ($request->hasFile('attachments')) {
            return response()->json('true');
        }

        // $resource = new ResourceLeave($leave);
        // event(new CreateLeaveEvent($resource));
    
        // return $resource;
        return response()->json($leave);
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
        $ids = explode(",", $id);

        if (count($ids) > 0) {
            //del leave
            $leave = Leave::whereIn('id', $ids)->get();
            foreach ($leave as $l) {
                $l->delete();
            }
           
            $resource = new LeavesCollection($leave);
            event(new DestroyLeaveEvent($resource));
    
            return $resource;
        }
    }
}
