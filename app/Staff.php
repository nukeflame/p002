<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use SoftDeletes;

    protected $table = "staff";

    /**
     * The User has many checkin.
     */
    public function checkin()
    {
        return $this->hasMany('App\Checkin', 'staffId');
    }

    /**
     * Belongs to a period year
     */
    public function periods()
    {
        return $this->belongsToMany('App\Period', 'staff_periods');
    }

    /**
     * Belongs to a period year
     */
    public function parameter()
    {
        return $this->belongsToMany('App\PayrollParameter', 'staff_parameters', 'staff_id', 'parameter_id')->withPivot('amount', 'is_active')->withTimestamps();
    }

    /**
     * Belongs to a period year
     */
    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    /**
     * The Attendance has many payrolls.
     */
    public function payrolls()
    {
        return $this->hasMany('App\Payroll');
    }

    /**
     * The staff has many advances.
     */
    public function advances()
    {
        return $this->hasMany('App\AdvancePay');
    }

    /**
     * The staff has many advances.
     */
    public function overtimes()
    {
        return $this->hasMany('App\Overtime');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'linkToUser');
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'staff_roles');
    }
}
