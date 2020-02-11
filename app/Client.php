<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public function sfaffs()
    {
        return $this->hasMany('App\Staff', 'client_id', 'id');
    }

    public function company()
    {
        return $this->hasOne('App\Company');
    }
}
