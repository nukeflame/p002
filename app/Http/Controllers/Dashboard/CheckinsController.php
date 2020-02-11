<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Checkin\CheckinCollection;
use App\Notifications\CheckInNotf;
use App\Notifications\CheckOutNotf;
use App\Notifications\Checkins\CheckInNotification;
use App\Notifications\Checkins\CheckOutNotification;
use Notification;
use App\Http\Resources\Checkin\Checkin as CheckinResource;
use App\Checkin;
use App\Staff;
use Carbon\Carbon;
use Auth;
use App\User;
use Event;
use App\Events\Attendances\AttendanceLog\DestroyAttendanceEvent;
use App\Events\AttendancesEvent;

class CheckinsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $checkins = Checkin::whereDate('created_at', Carbon::today())->latest()->get();
        return new CheckinCollection($checkins);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = json_decode($id, true);
        $Id = $data['Id'];
        $employee = Staff::where('staffNo', $Id)->first();
        // if staff exits
        if (empty($employee)) {
            return response()->json(['notFound' => 'Employee Not Found, Please try again'], 404);
        } else {
            if ($data['checkIn']) {
                $check = new Checkin();
                $check->staffNo = $employee->staffNo;
                $check->surname =  $employee->surname;
                $check->otherNames = $employee->otherNames;
                $check->idNo = $employee->idNo;
                $check->time_in = Carbon::now()->toDateTimeString();
                $check->staffId = $employee->id;
                $check->checkedBy = Auth::id();
                $check->staffNo = $employee->staffNo;
                $check->save();

                // notification
                if (!empty($check)) {
                    $users = User::with('roles')->whereHas('roles', function ($query) {
                        $query->where('slug', 'administrator');
                    })->get();

                    Notification::send($users, new CheckInNotification($check));
                }

                return new CheckinResource($check);
            } else {
                // check out
                // check if already checked in
                $result =  Checkin::where('staffNo', $Id);
                if (count($result->latest()->get()) > 0) {
                    $check = $result->first();
                    if ($check->time_in === null) {
                        return response()->json(['notFound' => 'Please Check In first before Checkin Out!'], 404);
                    }
                    
                    $now = Carbon::now();
                    $startTime = Carbon::parse($check->time_in);
                    $endTime = $now;
                    $totalHrs = $endTime->diff($startTime)->format('%H');
                    $check->time_out = $now->toDateTimeString();
                    $check->staffId = $employee->id;
                    $check->checkedBy = Auth::id();
                    $check->staffNo = $employee->staffNo;
                    $check->totalHrs= $totalHrs;
                    $check->update();
        
                    // notification
                    if (!empty($check)) {
                        $users = User::with('roles')->whereHas('roles', function ($query) {
                            $query->where('slug', 'administrator');
                        })->get();
        
                        Notification::send($users, new CheckOutNotification($check));
                    }
                        
                    return new CheckinResource($check);
                } else {
                    return response()->json(['notFound' => 'Please Check In first before Checkin Out!'], 404);
                }
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = json_decode($id, true);
        $Id = $data['Id'];
        $employee = Staff::where('staffNo', $Id)->first();
        // if staff exits
        if (empty($employee)) {
            return response()->json(['notFound' => 'Employee Not Found, Please try again'], 404);
        } else {
            // check if already checked in
            $result =  Checkin::where('staffNo', $Id);
            if ($data['checkIn']) {
                if (count($result->latest()->get()) > 0) {
                    $parse_r = $result->first();
                    $check_time = Carbon::parse($parse_r->time_in)->toTimeString();
                    return response()->json(['exitsStaff' => 'Employee Already Check In at ' . $check_time], 403);
                }

                $check = new Checkin();
                $check->staffNo = $employee->staffNo;
                $check->surname =  $employee->surname;
                $check->otherNames = $employee->otherNames;
                $check->idNo = $employee->idNo;
                $check->time_in = Carbon::now()->toDateTimeString();
                $check->staffId = $employee->id;
                $check->checkedBy = Auth::id();
                $check->staffNo = $employee->staffNo;
                $check->save();

                //notification
                if (!empty($check)) {
                    $users = User::with('roles')->whereHas('roles', function ($query) {
                        $query->where('slug', 'branch_manager');
                    })->get();
        
                    Notification::send($users, new CheckInNotf($check));
                }

                return new CheckinResource($check);
            } else {
                // check out
                $check = $result->first();
                if (count($result->latest()->get()) > 0) {
                    if ($check->time_in === null) {
                        return response()->json(['notFound' => 'Please Check In first before Checkin Out!'], 404);
                    }
                    //insert to db
                    if ($check->time_out === null) {
                        $now = Carbon::now();
                        $startTime = Carbon::parse($check->time_in);
                        $endTime = $now;
                        $totalHrs = $endTime->diff($startTime)->format('%Hhrs %Imins %Ssecs');
                        $check->time_out = $now->toDateTimeString();
                        $check->staffId = $employee->id;
                        $check->checkedBy = Auth::id();
                        $check->staffNo = $employee->staffNo;
                        $check->totalHrs= $totalHrs;
                        $check->update();
                        return new CheckinResource($check);
                    } else {
                        $out_time = Carbon::parse($check->time_out)->toTimeString();
                        return response()->json(['exitsStaff' => 'Employee Checked Out at '. $out_time], 403);
                    }
                } else {
                    return response()->json(['notFound' => 'Please Check In first before Checkin Out!'], 404);
                }
            }
        }
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
        $check = Checkin::findOrFail($id);
        
        $resource = new CheckinResource($check);
        event(new AttendancesEvent($resource));

        return $resource;
    }
}
