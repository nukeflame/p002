<?php 

namespace  App\Traits;

use DB;
use Auth;
use App\User;
use App\Role;
use App\Permission;

/**
 * User roles
 */
trait UserRoles
{
    public function isAdmin()
    {
        $user = DB::table("users")->where;

        return response()->json($user);
    }

    
}
