<?php

namespace App\Http\Controllers\Dashboard;

use App\Attendance;
use App\Events\UserCheckedIn;
use App\Events\UserCheckedOut;
use App\Http\Controllers\Controller;
use App\Notifications\CheckInNotf;
use App\Notifications\CheckOutNotf;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Notification;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::with('attendance', 'notifications')->get();
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Attendance::all();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $workdays = array();
        $type = CAL_GREGORIAN;
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days

        //loop through all days
        for ($i = 1; $i <= $day_count; $i++) {

            $date = $year . '/' . $month . '/' . $i; //format date
            $get_name = date('l', strtotime($date)); //get week day
            $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

            if ($day_name == 'Sun') {
                $workdays[] = $i;
            }
        }

        $sundays = count($workdays);
        $working_days = Carbon::now()->daysInMonth - $sundays;

        // database
        $field = new Attendance();
        $field->user_id = $request->userId;
        $field->time_in = Carbon::now();
        $field->checkin_by = Auth::id();
        $field->working_days = $working_days;
        $field->save();

        $attendance = Attendance::where('id', $field->id)->first();

        if (!empty($field)) {
            $users = User::with('roles')->whereHas('roles', function ($query) {
                $query->where('slug', 'branch_manager');
            })->get();

            Notification::send($users, new CheckInNotf($attendance));
        }

        return response()->json($attendance);
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
        $field = Attendance::findOrFail($id);
        $field->time_out = Carbon::now();
        $field->total_hours = $request->totalHours;
        $field->checkout_by = Auth::id();
        $field->updated_at = Carbon::now();
        $field->save();

        $attendance = Attendance::where('id', $field->id)->first();

        if (!empty($field)) {
            $users = User::with('roles')->whereHas('roles', function ($query) {
                $query->where('slug', 'branch_manager');
            })->get();

            Notification::send($users, new CheckOutNotf($attendance));
        }

        return response()->json($attendance);
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
