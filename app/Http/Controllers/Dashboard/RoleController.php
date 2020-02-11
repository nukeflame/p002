<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Role;
use App\Traits\HasPermissions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Role\RoleCollection;
use App\Http\Resources\Role\Role as RoleResource;
use Auth;

class RoleController extends Controller
{
    use HasPermissions;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staff = Auth::user()->staff;
        $roles = Role::with("users")->get();
        // return new RoleCollection($roles);
        return response()->json($staff);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'role' => 'required|string|min:3|max:32',
            'description' => 'nullable|string|max:255',
            'slug' => 'required|string|unique:roles',
        ]);

        if ($validatedData) {
            $role = new Role();
            $role->name = $request->role;
            $role->slug = $request->slug;
            $role->description = $request->description;
            $role->save();
            return new RoleResource($role);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::where("id", $id)->with("permissions")->first();
        return new RoleResource($role);
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
        $role = Role::findOrFail($id);
        $role->name = $request->role;
        $role->slug = $request->slug;
        $role->description = $request->description;
        $role->update();

        return new RoleResource($role);
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
        $role = Role::find($id);

        return new RoleResource($role);
    }
}
