<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use SoftDeletes;

    protected $fillable = ['staff_id','period_id'];
    /**
     * The Attendance belongs to user.
     */
    public function staff()
    {
        return $this->belongsTo('App\Staff');
    }
}
