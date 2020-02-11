<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\User\User as UserResource;
use App\Http\Resources\User\UserCollection;
use Auth;
use App\Staff;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth = Auth::user();
        $staff = Staff::where('client_id', $auth->staff->client_id)->get();
        $userIds = [];
        foreach ($staff as $sf) {
            $userIds[] = $sf->user_id;
        }

        $users = User::whereIn('id', $userIds)->orderBy("created_at", "desc")->with('roles')->get();
        return new UserCollection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('add_bank')) {
            $user = User::find($request->id);

            $user->acc_holder = $request->acc_holder;
            $user->acc_no = $request->acc_no;
            $user->bank_code = $request->bank_code;
            $user->bank_name = $request->bank_name;
            $user->iban_no = $request->iban_no;
            $user->swift_code = $request->swift_code;
            $user->save();

            return response()->json($user);
        } else {
            $number = count(User::all());

            $validatedData = $request->validate([
                'name' => 'required|string|min:3|max:255',
                'id_no' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|max:32',
                'phone_no' => 'required|max:14',
                'branch_id' => 'required',
                'nhif_no' => 'required|unique:users',
                'kra_no' => 'required',
                'job_title' => 'required',
            ]);

            if ($validatedData) {
                $user = new User();
                $user->name = ucwords($request->name);
                $user->phone_no = $request->phone_no;
                $user->email = $request->email;
                $user->id_no = $request->id_no;
                $user->branch_id = $request->branch_id;
                $user->nhif_no = $request->nhif_no;
                $user->kra_no = $request->kra_no;
                $user->password = Hash::make($request->password);
                $user->created_at = Carbon::now();
                $user->employee_no = 'NYM' . '-' . str_pad($number + 1, 4, "0", STR_PAD_LEFT);
                $user->save();

                $user->roles()->attach($request->job_title);

                return response()->json($user);
            }
        }
    }

    // public function roles()
    // {
    //     return Auth::user()->roles;
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $roles = User::where('id', $id)->with('roles')->first();
        return response()->json($roles);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where('username', $id)->with('roles')->first();
        return response()->json($user);
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
        $user = User::find($id);

        if (!$user) {
            return null;
        }

        // update user roles
        if ($request->has(['userId', 'rolesId'])) {
            $rolesId = $request->rolesId;
            $update = true;

            if (!empty($rolesId)) {
                $user->roles()->sync($rolesId);
                return response()->json($user->roles);
            } else {
                $user->roles()->sync(5);
                return null;
            }
        }

        $user->name = ucwords($request->name);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone = $request->phone;
        $user->county = $request->county;
        $user->bio = $request->bio;
        $user->updated_at = Carbon::now('Africa/Nairobi');
        $user->status = $request->status;
        $user->roles()->attach(5);
        $user->update();

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$id) {
            return null;
        }

        $user = User::find($id);
        $user->delete();

        return response()->json($user);
    }

    /**
     * Check if password exists
     *
     * @return \Illuminate\Http\Response
     */
    public function check_pswd(Request $request)
    {
        $user = User::find($request->userId);

        if (!Hash::check($request->pswd, $user->password)) {
            return response()->json(['error' => 'Your password is incorrect!']);
        }
    }

    /**
     * Check if password exists
     *
     * @return \Illuminate\Http\Response
     */
    public function auth()
    {
        $auth = Auth::user();
        return new UserResource($auth);
    }
}
