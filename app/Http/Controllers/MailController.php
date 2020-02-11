<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\User;
use Illuminate\Http\Request;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        SendEmailJob::dispatch()->delay(now()->addSeconds(5));

        echo 'Dispatched Event Now for 5 secs';

        // $pdf = PDF::loadView('emails.payslips', ['user' => $user]);
        // // $pdf->setEncryption('1');
        // $pdf->setPaper('A4', 'portrait');
        // $pdf->save('pdf/' . $user->name . '.pdf');

        // Redis::set('name', 'Taylor');

        // // $check = SendEmailJob::dispatch()
        // //     ->delay(now()->addSeconds(5));

        // // if ($check) {
        // //     echo 'Email sent successfully';
        // // }
        // return view('emails.payslips', ['user' => $user]);
        // $users = User::with('roles')->whereHas('roles', function ($query) {
        //     $query->where('slug', 'manager');
        // })->get();

        // Notification::send($users, new CheckedIn($data));

        // return response()->json($data->notifications);

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

        $message = User::findOrFail($id);

        $redis = Redis::connection();
        $redis->publish('message', $message);

        return response()->json($message);
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
