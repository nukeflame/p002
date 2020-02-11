<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','updated_at','created_at'
    ];

    /**
     * * The role that belong to permission.
    */

    public function roles()
    {
        return $this->belongsToMany('App\Role','roles_permisions');
    }

    public function users()
    {
        return $this->belongsToMany('App\User','users_permissions');
    }
}
