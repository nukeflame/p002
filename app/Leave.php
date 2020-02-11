<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use SoftDeletes;
    
    protected $dates = ['application_date','start_date','end_date','deleted_at'];

    public function leave_type()
    {
        return $this->belongsTo('App\LeaveType');
    }
}
