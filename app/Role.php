<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name', 'slug', 'created_at', 'updated_at'
    ];

    /**
     * * The users that belong to the role.
    */

    public function staff()
    {
        return $this->belongsToMany('App\Staff', 'staff_roles');
    }

    /**
     * * The role that belong to permission.
    */

    public function permissions()
    {
        return $this->belongsToMany('App\Permission', 'roles_permisions');
    }

    public function clients()
    {
        return $this->hasMany('App\Client');
    }
}
