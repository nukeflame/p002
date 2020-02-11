<?php 

namespace  App\Traits;

use Auth;
use App\Role;
use App\Permission;

/**
 * User permissions traits
 */
trait userPermissions
{
    public function canEdit()
    {
        // $user = Permission::where("name","=", "edit")->with("roles")->get();
        

        // $role = Role::where("slug", "admin")->with("permissions")->get();
        // $perm = Permission::where("create", "admin")->get();


        

        // return response()->json($role); 
    }

    public function canRead()
    {
        $user = Role::find(2)->permissions;

        return response()->json($user);
    }

    public function canUpdate()
    {
        $user = Role::find(2)->permissions;

        return response()->json($user);
    }

    public function canCreate()
    {
        $user = Role::find(2)->permissions;

        return response()->json($user);
    }

    public function canDelete()
    {
        $user = Role::find(2)->permissions;

        return response()->json($user);
    }
    
}
