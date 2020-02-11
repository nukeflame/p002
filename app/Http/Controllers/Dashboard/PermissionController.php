<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perms = Permission::with('users')->orderBy('created_at', 'desc')->get();
        return response()->json($perms);
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
        if ($request->permType == 'basic') {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'slug' => 'required|alphadash|unique:permissions',
                'description' => 'sometimes|max:255',
            ]);

            if (!$validated) {
                return response()->json($validated);
            }

            $perm = new Permission();
            $perm->name = ucwords($request->name);
            $perm->slug = strtolower($request->slug);
            $perm->description = strtolower($request->description);
            $perm->created_at = Carbon::now("Africa/Nairobi");
            $perm->save();
            
            return response()->json($perm);
        
        } 

        if ($request->permType == 'crud') {

            $validated = $request->validate([
                'resource' => 'required|min:4|max:100|alpha',
            ]);

            if (!$validated) {
                return response()->json($validated);
            }

            $res = $request->resource;
            $crud = $request->crudSelected;
            
            if (count($crud) > 0) {
                foreach ($crud as $val) {
                    $name = ucwords($val. " " . $res);
                    $slug = strtolower($val. "-" . $res);
                    $description = "allow client to ". strtolower($val. " " . $res);

                    $perm = new Permission();
                    $perm->name = $name;
                    $perm->slug = $slug;
                    $perm->description = $description;
                    $perm->created_at = Carbon::now("Africa/Nairobi");
                    $perm->save();
                }

                return response()->json($perm);
            }

            
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
        $perm = Permission::find($id);
        return response()->json($perm);
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
        $perm = Permission::findOrFail($id);

        $perm->name = $request->name;
        $perm->slug = $request->slug;        
        $perm->description = $request->description;        
        $perm->updated_at = Carbon::now('Africa/Nairobi');
        $perm->update();

        return response()->json($perm);
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
