<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $table = "leaves_type";

    public function leaves()
    {
        return $this->hasMany('App\Leave');
    }
}
