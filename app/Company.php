<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function roles()
    {
        return $this->hasMany('App\Role');
    }
}
